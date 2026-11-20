<?php

declare(strict_types=1);

/**
 * Native PSR-4 Autoloader & Bootstrap
 * Menjalankan aplikasi tanpa dependensi framework eksternal.
 */

spl_autoload_register(function (string $class): void {
    $prefix = 'Src\\';
    $baseDir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

// Inisialisasi Session jika belum aktif
if (session_status() === PHP_SESSION_NONE) {
    $currentPath = session_save_path();
    if (empty($currentPath) || !@is_dir($currentPath) || !@is_writable($currentPath)) {
        $tempDir = sys_get_temp_dir();
        if (@is_dir($tempDir) && @is_writable($tempDir)) {
            @session_save_path($tempDir);
        }
    }
    @session_start();
}
