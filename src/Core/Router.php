<?php

declare(strict_types=1);

namespace Src\Core;

/**
 * Router Native PHP dengan dukungan URL parameter dinamis (/toko/{slug}, dll).
 */
class Router
{
    /** @var array<string, array<int, array{pattern: string, regex: string, handler: callable|array}>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $pattern, callable|array $handler): void
    {
        $this->addRoute('GET', $pattern, $handler);
    }

    public function post(string $pattern, callable|array $handler): void
    {
        $this->addRoute('POST', $pattern, $handler);
    }

    private function addRoute(string $method, string $pattern, callable|array $handler): void
    {
        // Normalisasi pattern
        $pattern = '/' . trim($pattern, '/');
        if ($pattern !== '/' && str_ends_with($pattern, '/')) {
            $pattern = rtrim($pattern, '/');
        }

        // Konversi pattern seperti /toko/{slug}/barang/{productSlug} menjadi regex:
        // /toko/([^/]+)/barang/([^/]+)
        $regex = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $regex = '#^' . $regex . '$#';

        $this->routes[$method][] = [
            'pattern' => $pattern,
            'regex' => $regex,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        $path = $request->getPath();

        // Normalisasi path
        $path = '/' . trim($path, '/');
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        $methodRoutes = $this->routes[$method] ?? [];

        foreach ($methodRoutes as $route) {
            if (preg_match($route->regex ?? $route['regex'], $path, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[$key] = urldecode($value);
                    }
                }

                $handler = $route['handler'];

                if (is_array($handler)) {
                    [$class, $methodName] = $handler;
                    $controller = is_object($class) ? $class : new $class();
                    $result = call_user_func_array([$controller, $methodName], array_merge([$request], $params));
                } else {
                    $result = call_user_func_array($handler, array_merge([$request], $params));
                }

                if ($result instanceof Response) {
                    return $result;
                }

                if (is_string($result)) {
                    return new Response($result);
                }

                if (is_array($result) || is_object($result)) {
                    return Response::json($result);
                }

                return new Response();
            }
        }

        // Jika rute tidak ditemukan: 404
        if (str_starts_with($path, '/api/')) {
            return Response::json(['error' => 'Endpoint tidak ditemukan'], 404);
        }

        return new Response(
            '<div style="font-family:sans-serif; text-align:center; padding:50px;">' .
            '<h1>404 - Halaman Tidak Ditemukan</h1><p><a href="/">&larr; Kembali ke Beranda</a></p></div>',
            404,
            ['Content-Type' => 'text/html; charset=utf-8']
        );
    }
}
