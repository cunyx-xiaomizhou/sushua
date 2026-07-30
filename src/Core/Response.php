<?php
declare(strict_types=1);

namespace Sushua\Core;

final class Response
{
    public static function json(array $payload, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function success(mixed $data = null, string $message = 'ok', int $status = 200): never
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'time' => time(),
        ], $status);
    }

    public static function error(string $message, int $status = 422, mixed $data = null): never
    {
        self::json([
            'success' => false,
            'message' => $message,
            'data' => $data,
            'time' => time(),
        ], $status);
    }

    public static function html(string $html, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        exit;
    }

    public static function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }
}
