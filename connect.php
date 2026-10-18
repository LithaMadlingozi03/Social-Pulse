<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

/*
 * Production hosting on InfinityFree does not provide user-configurable
 * PHP environment variables on all free accounts. A private config.local.php
 * file can override these local development defaults. Keep that file out of Git.
 */
$localConfigPath = __DIR__ . '/config.local.php';
$localConfig = is_file($localConfigPath) ? require $localConfigPath : [];
if (!is_array($localConfig)) {
    $localConfig = [];
}

$host = $localConfig['host'] ?? (getenv('SOCIALPULSE_DB_HOST') ?: '127.0.0.1');
$user = $localConfig['user'] ?? (getenv('SOCIALPULSE_DB_USER') ?: 'root');
$pass = $localConfig['pass'] ?? (getenv('SOCIALPULSE_DB_PASS') ?: '');
$db   = $localConfig['db']   ?? (getenv('SOCIALPULSE_DB_NAME') ?: 'socialpulse_db');

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    error_log('Social-Pulse database connection failed: ' . $e->getMessage());
    exit('Database connection failed. Check the private configuration file and confirm that the database has been imported.');
}
