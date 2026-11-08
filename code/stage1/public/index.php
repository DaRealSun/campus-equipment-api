<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
if ($path !== '/health') {
    http_response_code(501);
    echo json_encode(['error' => ['code' => 'not_implemented', 'message' => 'Part 1 environment only; project endpoints are planned for Stage 1.']]);
    exit;
}
try {
    $db = new PDO(
        'mysql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE') . ';charset=utf8mb4',
        getenv('DB_USERNAME'), getenv('DB_PASSWORD'),
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false]
    );
    $message = $db->query('SELECT message FROM readiness_probe WHERE id = 1')->fetchColumn();
    if ($message !== 'nginx-php-mysql-ready') {
        throw new RuntimeException('Readiness fixture missing');
    }
    echo json_encode(['status' => 'ok', 'database' => 'connected', 'probe' => $message]);
} catch (Throwable $error) {
    http_response_code(503);
    echo json_encode(['error' => ['code' => 'database_unavailable', 'message' => 'Database readiness check failed.']]);
}
