<?php
declare(strict_types=1);

final class ApiError extends RuntimeException
{
    public function __construct(public int $status, public string $label, string $message)
    {
        parent::__construct($message);
    }
}

function sendJson(int $status, ?array $body = null): never
{
    http_response_code($status);
    if ($status !== 204) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($body, JSON_THROW_ON_ERROR);
    }
    exit;
}

final class Api
{
    private const EQUIPMENT = 'SELECT e.id, e.name, e.description, NOT EXISTS (
        SELECT 1 FROM loans l WHERE l.equipment_id = e.id AND l.returned_at IS NULL
    ) AS available FROM equipment e';
    private const LOAN = 'SELECT id, user_id, equipment_id, borrowed_at, returned_at FROM loans';

    public function __construct(private PDO $db) {}

    public function dispatch(string $method, string $path): never
    {
        if ($method === 'POST' && $path === '/api/v1/auth/register') {
            sendJson(201, ['data' => $this->register()]);
        }
        if ($method === 'POST' && $path === '/api/v1/auth/login') {
            header('Cache-Control: no-store');
            sendJson(200, ['data' => $this->login()]);
        }
        if ($path === '/api/v1/equipment') {
            if ($method === 'GET') {
                sendJson(200, ['data' => $this->catalog()]);
            }
            if ($method === 'POST') {
                $this->actor('staff');
                sendJson(201, ['data' => $this->saveEquipment(null)]);
            }
        }
        if (preg_match('~^/api/v1/equipment/([0-9]+)$~D', $path, $match)) {
            $id = $this->id($match[1]);
            if ($method === 'GET') {
                sendJson(200, ['data' => $this->equipment($id)]);
            }
            if ($method === 'PUT') {
                $this->actor('staff');
                sendJson(200, ['data' => $this->saveEquipment($id)]);
            }
            if ($method === 'DELETE') {
                $this->actor('staff');
                $this->deleteEquipment($id);
                sendJson(204);
            }
        }
        if ($path === '/api/v1/loans') {
            if ($method === 'POST') {
                sendJson(201, ['data' => $this->checkout($this->actor('borrower'))]);
            }
            if ($method === 'GET') {
                sendJson(200, ['data' => $this->listLoans($this->actor())]);
            }
        }
        if ($method === 'PUT' && preg_match('~^/api/v1/loans/([0-9]+)/return$~D', $path, $match)) {
            sendJson(200, ['data' => $this->returnLoan($this->actor(), $this->id($match[1]))]);
        }
        throw new ApiError(404, 'route_not_found', 'No endpoint matches this method and path.');
    }

    private function query(string $sql, array $parameters = []): PDOStatement
    {
        $statement = $this->db->prepare($sql);
        $statement->execute($parameters);
        return $statement;
    }

    private function transaction(callable $operation): mixed
    {
        $this->db->beginTransaction();
        try {
            $result = $operation();
            $this->db->commit();
            return $result;
        } catch (Throwable $error) {
            $this->db->rollBack();
            throw $error;
        }
    }

    private function body(): array
    {
        try {
            $object = json_decode(file_get_contents('php://input'), false, 32, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new ApiError(400, 'invalid_json', 'Send a valid JSON object.');
        }
        if (!$object instanceof stdClass) {
            throw new ApiError(400, 'invalid_json', 'Send a valid JSON object.');
        }
        return (array) $object;
    }

    private function string(array $body, string $field, int $min, int $max, bool $trim = true): string
    {
        $value = $body[$field] ?? null;
        if (is_string($value)) {
            $value = $trim ? trim($value) : $value;
            if (strlen($value) >= $min && strlen($value) <= $max && !str_contains($value, "\0")) {
                return $value;
            }
        }
        throw new ApiError(400, 'invalid_input', "$field must be a string of $min to $max bytes.");
    }

    private function id(int|string $value): int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) {
            throw new ApiError(400, 'invalid_id', 'ID must be a positive integer.');
        }
        return $id;
    }

    private function email(array $body): string
    {
        $email = strtolower($this->string($body, 'email', 1, 255));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new ApiError(400, 'invalid_email', 'Enter a valid email address.');
        }
        return $email;
    }

    private function register(): array
    {
        $body = $this->body();
        $name = $this->string($body, 'name', 1, 100);
        $email = $this->email($body);
        $password = $this->string($body, 'password', 8, 72, false);
        try {
            $this->query('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)',
                [$name, $email, password_hash($password, PASSWORD_BCRYPT)]);
        } catch (PDOException $error) {
            if (($error->errorInfo[1] ?? null) === 1062) {
                throw new ApiError(409, 'email_exists', 'This email is already registered.');
            }
            throw $error;
        }
        return ['id' => (int) $this->db->lastInsertId(), 'name' => $name, 'email' => $email, 'role' => 'borrower'];
    }

    private function login(): array
    {
        $body = $this->body();
        $user = $this->query('SELECT id, password_hash FROM users WHERE email = ?', [$this->email($body)])
            ->fetch(PDO::FETCH_ASSOC);
        $password = $this->string($body, 'password', 1, 72, false);
        if (!$user || !password_verify($password, $user['password_hash'])) {
            throw new ApiError(401, 'invalid_credentials', 'Email or password is incorrect.');
        }
        $token = bin2hex(random_bytes(32));
        $this->query('INSERT INTO tokens VALUES (?, ?, DATE_ADD(UTC_TIMESTAMP(), INTERVAL 1 DAY))',
            [hash('sha256', $token), $user['id']]);
        return ['token' => $token, 'token_type' => 'Bearer'];
    }

    private function actor(?string $role = null): array
    {
        $authorization = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/^Bearer ([A-Za-z0-9_-]{1,256})$/Di', $authorization, $match)) {
            throw new ApiError(401, 'authentication_required', 'Send a valid bearer token.');
        }
        $user = $this->query('SELECT u.id, u.role FROM tokens t JOIN users u ON u.id = t.user_id
            WHERE t.token_hash = ? AND t.expires_at > UTC_TIMESTAMP()', [hash('sha256', $match[1])])
            ->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            throw new ApiError(401, 'invalid_token', 'The bearer token is invalid or expired.');
        }
        if ($role !== null && $user['role'] !== $role) {
            throw new ApiError(403, 'forbidden', 'This account cannot perform that action.');
        }
        $user['id'] = (int) $user['id'];
        return $user;
    }

    private function catalog(): array
    {
        $filter = $_GET['available'] ?? null;
        if ($filter !== null && !in_array($filter, ['true', 'false'], true)) {
            throw new ApiError(400, 'invalid_filter', 'Availability must be true or false.');
        }
        $rows = $filter === null
            ? $this->query(self::EQUIPMENT . ' ORDER BY e.id')
            : $this->query(self::EQUIPMENT . ' HAVING available = ? ORDER BY e.id', [$filter === 'true' ? 1 : 0]);
        return array_map($this->equipmentData(...), $rows->fetchAll(PDO::FETCH_ASSOC));
    }

    private function equipmentData(array $row): array
    {
        $row['id'] = (int) $row['id'];
        $row['available'] = (bool) $row['available'];
        return $row;
    }

    private function equipment(int $id): array
    {
        $row = $this->query(self::EQUIPMENT . ' WHERE e.id = ?', [$id])->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new ApiError(404, 'equipment_not_found', 'Equipment does not exist.');
        }
        return $this->equipmentData($row);
    }

    private function lockEquipment(int $id): void
    {
        if (!$this->query('SELECT id FROM equipment WHERE id = ? FOR UPDATE', [$id])->fetchColumn()) {
            throw new ApiError(404, 'equipment_not_found', 'Equipment does not exist.');
        }
    }

    private function saveEquipment(?int $id): array
    {
        $body = $this->body();
        $name = $this->string($body, 'name', 1, 100);
        $description = $this->string($body + ['description' => ''], 'description', 0, 1000);
        return $this->transaction(function () use ($id, $name, $description) {
            if ($id === null) {
                $this->query('INSERT INTO equipment (name, description) VALUES (?, ?)', [$name, $description]);
                $id = (int) $this->db->lastInsertId();
            } else {
                $this->lockEquipment($id);
                $this->query('UPDATE equipment SET name = ?, description = ? WHERE id = ?', [$name, $description, $id]);
            }
            return $this->equipment($id);
        });
    }

    private function deleteEquipment(int $id): void
    {
        $this->transaction(function () use ($id) {
            $this->lockEquipment($id);
            if ($this->query('SELECT 1 FROM loans WHERE equipment_id = ? LIMIT 1', [$id])->fetchColumn()) {
                throw new ApiError(409, 'loan_history', 'Equipment with loan history cannot be deleted.');
            }
            $this->query('DELETE FROM equipment WHERE id = ?', [$id]);
        });
    }

    private function loanData(array $row): array
    {
        foreach (['id', 'user_id', 'equipment_id'] as $key) {
            $row[$key] = (int) $row[$key];
        }
        return $row;
    }

    private function checkout(array $user): array
    {
        $item = $this->body()['equipment_id'] ?? null;
        if (!is_int($item)) {
            throw new ApiError(400, 'invalid_input', 'equipment_id must be a JSON integer.');
        }
        $id = $this->id($item);
        return $this->transaction(function () use ($id, $user) {
            $this->lockEquipment($id);
            if ($this->query('SELECT 1 FROM loans WHERE equipment_id = ? AND returned_at IS NULL LIMIT 1 FOR UPDATE',
                [$id])->fetchColumn()) {
                throw new ApiError(409, 'equipment_unavailable', 'Equipment is already on loan.');
            }
            $this->query('INSERT INTO loans (user_id, equipment_id, borrowed_at) VALUES (?, ?, UTC_TIMESTAMP())',
                [$user['id'], $id]);
            $loanId = (int) $this->db->lastInsertId();
            return $this->loanData($this->query(self::LOAN . ' WHERE id = ?', [$loanId])->fetch(PDO::FETCH_ASSOC));
        });
    }

    private function listLoans(array $user): array
    {
        $rows = $user['role'] === 'staff'
            ? $this->query(self::LOAN . ' ORDER BY id')
            : $this->query(self::LOAN . ' WHERE user_id = ? ORDER BY id', [$user['id']]);
        return array_map($this->loanData(...), $rows->fetchAll(PDO::FETCH_ASSOC));
    }

    private function returnLoan(array $user, int $id): array
    {
        return $this->transaction(function () use ($user, $id) {
            $row = $this->query(self::LOAN . ' WHERE id = ? FOR UPDATE', [$id])->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                throw new ApiError(404, 'loan_not_found', 'Loan does not exist.');
            }
            if ($user['role'] !== 'staff' && $user['id'] !== (int) $row['user_id']) {
                throw new ApiError(403, 'forbidden', 'Only the borrower or staff can return this loan.');
            }
            if ($row['returned_at'] !== null) {
                throw new ApiError(409, 'already_returned', 'This loan was already returned.');
            }
            $this->query('UPDATE loans SET returned_at = UTC_TIMESTAMP() WHERE id = ?', [$id]);
            return $this->loanData($this->query(self::LOAN . ' WHERE id = ?', [$id])->fetch(PDO::FETCH_ASSOC));
        });
    }
}
