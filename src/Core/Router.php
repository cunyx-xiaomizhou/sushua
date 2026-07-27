<?php
declare(strict_types=1);

namespace XiaoMiSlop\Core;

final class Router
{
    private array $routes = [];

    public function add(string $method, string $pattern, callable $handler): self
    {
        $this->routes[] = [strtoupper($method), $pattern, $handler];
        return $this;
    }

    public function dispatch(Request $request): mixed
    {
        $path = $request->path();
        $method = $request->method();

        foreach ($this->routes as [$routeMethod, $pattern, $handler]) {
            if ($routeMethod !== $method) {
                continue;
            }
            $regex = '#^' . preg_replace('#\{([^/]+)\}#', '(?P<$1>[^/]+)', rtrim($pattern, '/')) . '/?$#';
            if (preg_match($regex, rtrim($path, '/'), $matches)) {
                $params = array_filter($matches, static fn ($key) => !is_int($key), ARRAY_FILTER_USE_KEY);
                return $handler($request, $params);
            }
        }

        return null;
    }
}
