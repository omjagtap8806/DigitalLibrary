<?php

declare(strict_types=1);

$root = dirname(__DIR__);

$path = $_GET['path'] ?? '/index.php';
unset($_GET['path']);

$path = parse_url($path, PHP_URL_PATH) ?: '/index.php';
$path = '/' . ltrim($path, '/');

if ($path === '/') {
    $path = '/index.php';
}

$file = realpath($root . $path);
$rootPath = realpath($root);

if (
    $file === false ||
    $rootPath === false ||
    !is_file($file) ||
    strncmp($file, $rootPath . DIRECTORY_SEPARATOR, strlen($rootPath . DIRECTORY_SEPARATOR)) !== 0
) {
    http_response_code(404);
    echo 'Page not found.';
    exit;
}

chdir(dirname($file));

require $file;