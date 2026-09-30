<?php
declare(strict_types=1);

$root = dirname(__DIR__);

$requestedPath = $_GET['path'] ?? '/index.php';
unset($_GET['path']);

$requestedPath = rawurldecode((string)$requestedPath);
$requestedPath = parse_url($requestedPath, PHP_URL_PATH) ?: '/index.php';

if ($requestedPath === '/' || $requestedPath === '') {
    $requestedPath = '/index.php';
}

$requestedPath = '/' . ltrim($requestedPath, '/');

$target = realpath($root . $requestedPath);
$rootReal = realpath($root);

if (
    $target === false ||
    $rootReal === false ||
    !is_file($target) ||
    strtolower(pathinfo($target, PATHINFO_EXTENSION)) !== 'php' ||
    strncmp($target, $rootReal . DIRECTORY_SEPARATOR, strlen($rootReal . DIRECTORY_SEPARATOR)) !== 0 ||
    str_contains($target, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR)
) {
    http_response_code(404);
    echo 'Page not found.';
    exit;
}

chdir(dirname($target));

$_SERVER['SCRIPT_FILENAME'] = $target;
$_SERVER['SCRIPT_NAME'] = $requestedPath;
$_SERVER['PHP_SELF'] = $requestedPath;

require $target;