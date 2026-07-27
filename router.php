<?php
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$full = __DIR__ . $path;
if ($path !== '/' && is_file($full)) {
    return false;
}
require __DIR__ . '/default.php';
