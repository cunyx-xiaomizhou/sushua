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

function runtime_path(string $path = ''): string
{
    return base_path('runtime/' . ltrim($path, '/\\'));
}

function curl_ca_bundle_path(): ?string
{
    $candidates = array_filter([
        get_cfg_var('openssl.cafile') ?: null,
        ini_get('openssl.cafile') ?: null,
        ini_get('curl.cainfo') ?: null,
        runtime_path('certs/cacert.pem'),
        runtime_path('cacert.pem'),
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

function request_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    return '/' . trim($path, '/');
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
