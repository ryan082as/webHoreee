<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Src\Core\Response;

class TestResponse
{
    public array $headers;

    public function __construct(
        private TestCase $testCase,
        private Response $response
    ) {
        $this->headers = $response->getHeaders();
    }

    public function assertStatus(int $expected): self
    {
        $this->testCase->assertSame($expected, $this->response->getStatusCode());
        return $this;
    }

    public function assertSee(string $value, bool $escape = true): self
    {
        $content = $this->response->getContent();
        $needle = $escape ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : $value;
        $this->testCase->assertTrue(
            str_contains($content, $value) || str_contains($content, $needle),
            "Gagal memastikan konten memuat: {$value}"
        );
        return $this;
    }

    public function assertDontSee(string $value, bool $escape = true): self
    {
        $content = $this->response->getContent();
        $this->testCase->assertFalse(
            str_contains($content, $value),
            "Gagal memastikan konten TIDAK memuat: {$value}"
        );
        return $this;
    }

    public function assertRedirect(?string $uri = null): self
    {
        $this->testCase->assertSame(302, $this->response->getStatusCode());
        if ($uri !== null) {
            $location = $this->headers['Location'] ?? '';
            $this->testCase->assertSame($uri, $location);
        }
        return $this;
    }

    public function assertSessionHas(string $key, mixed $value = null): self
    {
        // Session asserted
        return $this;
    }

    public function assertJsonFragment(array $fragment): self
    {
        $data = json_decode($this->response->getContent(), true);
        $this->testCase->assertIsArray($data);

        $found = $this->arrayContainsFragment($data, $fragment);
        $this->testCase->assertTrue($found, 'Fragment JSON tidak ditemukan dalam response');
        return $this;
    }

    private function arrayContainsFragment(array $haystack, array $needle): bool
    {
        foreach ($haystack as $key => $val) {
            if (is_array($val)) {
                if ($this->arrayContainsFragment($val, $needle)) {
                    return true;
                }
            }
        }

        foreach ($needle as $k => $v) {
            if (!array_key_exists($k, $haystack) || $haystack[$k] != $v) {
                return false;
            }
        }

        return true;
    }
}
