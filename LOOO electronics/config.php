<?php
declare(strict_types=1);

$dbHost = getenv('LOOO_DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('LOOO_DB_PORT') ?: '3306';
$dbName = getenv('LOOO_DB_NAME') ?: 'looo_electronics';
$dbUser = getenv('LOOO_DB_USER') ?: 'root';
$dbPassword = getenv('LOOO_DB_PASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPassword,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $error) {
    http_response_code(500);
    exit('Database connection unavailable. Import database.sql into MySQL and check the settings in config.php.');
}
