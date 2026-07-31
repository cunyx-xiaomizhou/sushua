<?php
declare(strict_types=1);

function base_path(string $path = ''): string
{
    $base = __DIR__ . '/../../';
    return $path ? $base . ltrim($path, '/\\') : rtrim($base, '/\\');
}

function storage_path(string $path = ''): string
{
    return base_path('storage/' . ltrim($path, '/\\'));
}

function view_path(string $path = ''): string
{
    return base_path('views/' . ltrim($path, '/\\'));
}

function public_path(string $path = ''): string
{
    return base_path('public/' . ltrim($path, '/\\'));
}

function curl_ca_bundle_path(): ?string
{
    $candidates = array_filter([
        get_cfg_var('openssl.cafile') ?: null,
        ini_get('openssl.cafile') ?: null,
        ini_get('curl.cainfo') ?: null,
        storage_path('certs/cacert.pem'),
    ], static fn (mixed $value): bool => is_string($value) && trim($value) !== '');

    foreach ($candidates as $candidate) {
        $candidate = (string) $candidate;
        if (is_file($candidate)) {
            return $candidate;
        }
    }

    return null;
}

function apply_curl_ssl_defaults(\CurlHandle $ch): void
{
    $caBundle = curl_ca_bundle_path();
    if ($caBundle) {
        curl_setopt($ch, CURLOPT_CAINFO, $caBundle);
    }
}

function now(): string
{
    return date('Y-m-d H:i:s');
}

function application_base_path(): string
{
    static $basePath = null;
    if ($basePath !== null) {
        return $basePath;
    }

    $forwardedPrefix = trim((string) ($_SERVER['HTTP_X_FORWARDED_PREFIX'] ?? ''));
    if ($forwardedPrefix !== '' && preg_match('#^/[A-Za-z0-9._~/+-]+$#', $forwardedPrefix) === 1) {
        return $basePath = rtrim($forwardedPrefix, '/');
    }

    $scriptName = str_replace('\\', '/', (string) ($_SERVER['SCRIPT_NAME'] ?? ''));
    $scriptPath = parse_url($scriptName, PHP_URL_PATH) ?: '';
    $scriptPath = '/' . trim((string) $scriptPath, '/');
    foreach (['/index.php', '/default.php', '/router.php', '/install/index.php'] as $entryPoint) {
        if ($scriptPath === $entryPoint) {
            return $basePath = '';
        }
        if (str_ends_with($scriptPath, $entryPoint)) {
            $prefix = rtrim(substr($scriptPath, 0, -strlen($entryPoint)), '/');
            return $basePath = $prefix === '/' ? '' : $prefix;
        }
    }

    return $basePath = '';
}

function request_path(): string
{
    $source = (string) ($_SERVER['REQUEST_URI'] ?? '/');
    $path = parse_url($source, PHP_URL_PATH) ?: '/';
    $path = '/' . trim((string) $path, '/');

    $basePath = application_base_path();
    if ($basePath !== '' && ($path === $basePath || str_starts_with($path, $basePath . '/'))) {
        $path = substr($path, strlen($basePath)) ?: '/';
    }

    if ($path === '/index.php') {
        return '/';
    }

    return '/' . trim($path, '/');
}

function public_url(string $path = ''): string
{
    $base = (application_base_path() ?: '') . '/public';
    $path = ltrim(str_replace('\\', '/', $path), '/');
    return $path === '' ? $base : $base . '/' . $path;
}

function route_url(string $path = '/'): string
{
    $source = (string) $path;
    if ($source !== '' && (preg_match('#^(?:https?:)?//#i', $source) === 1 || str_starts_with($source, 'mailto:'))) {
        return $source;
    }

    $parts = parse_url($source);
    $routePath = is_array($parts) ? (string) ($parts['path'] ?? '/') : '/';
    $normalized = '/' . trim($routePath, '/');
    $basePath = application_base_path();
    $url = ($basePath ?: '') . ($normalized === '/' ? '/' : $normalized);

    if (is_array($parts) && isset($parts['query']) && $parts['query'] !== '') {
        $url .= '?' . $parts['query'];
    }
    if (is_array($parts) && isset($parts['fragment']) && $parts['fragment'] !== '') {
        $url .= '#' . $parts['fragment'];
    }

    return $url;
}

function array_get(array $array, string $key, mixed $default = null): mixed
{
    if ($key === '') {
        return $default;
    }
    $segments = explode('.', $key);
    $value = $array;
    foreach ($segments as $segment) {
        if (!is_array($value) || !array_key_exists($segment, $value)) {
            return $default;
        }
        $value = $value[$segment];
    }
    return $value;
}

function str_random(int $length = 32): string
{
    $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789';
    $result = '';
    $max = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $result .= $chars[random_int(0, $max)];
    }
    return $result;
}

function json_array(mixed $value, array $default = []): array
{
    if (is_array($value)) {
        return $value;
    }
    if (is_string($value) && $value !== '') {
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : $default;
    }
    return $default;
}

function clamp(float|int $value, float|int $min, float|int $max): float|int
{
    return max($min, min($max, $value));
}
