<?php
declare(strict_types=1);

require dirname(__DIR__) . '/src/Api.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

try {
    $db = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') .
        ';dbname=' . getenv('DB_DATABASE') . ';charset=utf8mb4',
        getenv('DB_USERNAME'),
        getenv('DB_PASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]
    );
    if ($method === 'GET' && $path === '/health') {
        $probe = $db->query('SELECT message FROM readiness_probe WHERE id = 1')->fetchColumn();
        if ($probe !== 'nginx-php-mysql-ready') {
            throw new RuntimeException('Readiness fixture missing');
        }
        sendJson(200, ['status' => 'ok', 'database' => 'connected', 'probe' => $probe]);
    }
    (new Api($db))->dispatch($method, $path);
} catch (ApiError $error) {
    sendJson($error->status, ['error' => ['code' => $error->label, 'message' => $error->getMessage()]]);
} catch (Throwable $error) {
    error_log(get_class($error) . ' while serving the equipment API');
    if ($path === '/health') {
        sendJson(503, ['error' => ['code' => 'database_unavailable', 'message' => 'Database readiness check failed.']]);
    }
    sendJson(500, ['error' => ['code' => 'internal_error', 'message' => 'The request could not be completed.']]);
}
