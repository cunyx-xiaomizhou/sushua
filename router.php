<?php
declare(strict_types=1);

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$target = __DIR__ . '/' . ltrim($path, '/');

if ($path !== '/' && (is_file($target) || is_dir($target))) {
    return false;
}

$applicationEntry = __DIR__ . '/index.php';
if (is_file($applicationEntry)) {
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['SCRIPT_FILENAME'] = $applicationEntry;
    require $applicationEntry;
    return true;
}

if ($path === '/' || $path === '/default.php') {
    $installEntry = __DIR__ . '/default.php';
    $_SERVER['SCRIPT_NAME'] = '/default.php';
    $_SERVER['SCRIPT_FILENAME'] = $installEntry;
    require $installEntry;
    return true;
}

http_response_code(404);
header('Content-Type: text/plain; charset=utf-8');
echo '系统尚未安装，请访问 /default.php 进入安装程序。';
return true;
