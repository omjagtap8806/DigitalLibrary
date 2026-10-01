<?php

$host = getenv('DB_HOST') ?: '';
$user = getenv('DB_USER') ?: '';
$pass = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'library';
$port = (int)(getenv('DB_PORT') ?: 4000);

$caFile = __DIR__ . '/../config/isrgrootx1.pem';

if (!file_exists($caFile)) {
    die('TiDB CA certificate not found.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = mysqli_init();

mysqli_ssl_set(
    $conn,
    null,
    null,
    $caFile,
    null,
    null
);

mysqli_real_connect(
    $conn,
    $host,
    $user,
    $pass,
    $dbname,
    $port,
    null,
    MYSQLI_CLIENT_SSL
);

mysqli_set_charset($conn, 'utf8mb4');

?>