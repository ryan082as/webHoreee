<?php

declare(strict_types=1);

namespace Src\Core;

/**
 * Engine rendering template native PHP sederhana, aman, dan modular.
 */
class View
{
    private static string $viewsDir = __DIR__ . '/../../views';

    public static function setViewsDir(string $dir): void
    {
        self::$viewsDir = rtrim($dir, '/\\');
    }

    public static function render(string $template, array $data = []): string
    {
        $file = self::$viewsDir . '/' . str_replace('.', '/', $template) . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException("View template tidak ditemukan: {$file}");
        }

        // Ekstraksi variabel ke dalam scope view
        extract($data, EXTR_SKIP);

        ob_start();
        include $file;
        return (string) ob_get_clean();
    }
}
