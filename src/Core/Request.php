<?php
declare(strict_types=1);

namespace XiaoMiSlop\Core;

final class Request
{
    private array $input;
    private string $path;
    private string $method;

    public function __construct()
    {
        $raw = file_get_contents('php://input') ?: '';
        $json = json_decode($raw, true);
        $this->input = is_array($json) ? $json : [];
        $this->path = request_path();
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function method(): string
    {
        return $this->method;
    }

    public function path(): string
    {
        return $this->path;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if (array_key_exists($key, $this->input)) {
            return $this->input[$key];
        }
        if (array_key_exists($key, $_POST)) {
            return $_POST[$key];
        }
        if (array_key_exists($key, $_GET)) {
            return $_GET[$key];
        }
        return $default;
    }

    public function all(): array
    {
        return array_merge($_GET, $_POST, $this->input);
    }

    public function header(string $key, mixed $default = null): mixed
    {
        $lookup = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$lookup] ?? $default;
    }

    public function ip(): string
    {
        return (string) ($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');
    }

    public function isJson(): bool
    {
        $contentType = (string) ($_SERVER['CONTENT_TYPE'] ?? '');
        return str_contains($contentType, 'application/json');
    }
}
