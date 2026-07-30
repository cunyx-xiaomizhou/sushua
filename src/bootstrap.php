<?php
declare(strict_types=1);

require_once __DIR__ . '/Support/helpers.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'Sushua\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = __DIR__ . '/' . $relative . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

\Sushua\Core\Config::load();
\Sushua\Core\Session::start();

$caBundle = curl_ca_bundle_path();
if ($caBundle) {
    ini_set('openssl.cafile', $caBundle);
    ini_set('curl.cainfo', $caBundle);
}
