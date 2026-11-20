<?php

declare(strict_types=1);

namespace Src\Core;

/**
 * Enkapsulasi data HTTP Request (GET, POST, Body, URI, Path, Method).
 */
class Request
{
    private array $get;
    private array $post;
    private array $server;
    private ?array $jsonBody = null;

    public function __construct(?array $get = null, ?array $post = null, ?array $server = null)
    {
        $this->get = $get ?? $_GET;
        $this->post = $post ?? $_POST;
        $this->server = $server ?? $_SERVER;
    }

    public static function capture(): self
    {
        return new self();
    }

    public function getMethod(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function getPath(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Hilangkan trailing slash jika bukan root
        if ($path !== '/' && str_ends_with($path, '/')) {
            $path = rtrim($path, '/');
        }

        return $path;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        if (isset($this->post[$key])) {
            return $this->post[$key];
        }

        if (isset($this->get[$key])) {
            return $this->get[$key];
        }

        $json = $this->getJson();
        if (isset($json[$key])) {
            return $json[$key];
        }

        return $default;
    }

    public function integer(string $key, int $default = 0): int
    {
        $val = $this->input($key, $default);
        return is_numeric($val) ? (int) $val : $default;
    }

    public function string(string $key, string $default = ''): string
    {
        $val = $this->input($key, $default);
        return is_string($val) ? trim($val) : $default;
    }

    public function all(): array
    {
        return array_merge($this->get, $this->post, $this->getJson());
    }

    public function getJson(): array
    {
        if ($this->jsonBody !== null) {
            return $this->jsonBody;
        }

        $raw = file_get_contents('php://input');
        if (empty($raw)) {
            $this->jsonBody = [];
            return $this->jsonBody;
        }

        $decoded = json_decode($raw, true);
        $this->jsonBody = is_array($decoded) ? $decoded : [];

        return $this->jsonBody;
    }
}
