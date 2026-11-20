<?php
/** @var string $title */
/** @var bool|null $showHomeNav */
use Src\Core\Session;

$appName = 'Katalog Multi-Toko';
$pageTitle = isset($title) && !empty($title) ? $title . ' - ' . $appName : $appName;
$cart = Session::get('cart', []);
$cartQty = 0;
foreach ($cart as $item) {
    $cartQty += (int) ($item['quantity'] ?? 0);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <header class="site-header">
        <div class="container header-inner topbar">
            <div>
                <a href="/" class="brand-link">
                    <span class="brand-logo-badge" style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: #2563eb; color: #ffffff;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </span>
                    <strong><?= htmlspecialchars($appName, ENT_QUOTES, 'UTF-8') ?></strong>
                </a>
            </div>

            <?php if (!empty($showHomeNav)): ?>
                <!-- Tombol Langganan & Login HANYA muncul di Halaman Awal -->
                <nav class="main-nav" aria-label="Navigasi Utama">
                    <a href="/langganan"><button type="button" class="btn btn-secondary btn-sm">Langganan</button></a>
                    <a href="/login"><button type="button" class="btn btn-primary btn-sm">Login</button></a>
                </nav>
            <?php else: ?>
                <nav aria-label="Navigasi Halaman">
                    <?php if (isset($backUrl) && !empty($backUrl)): ?>
                        <a href="<?= htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8') ?>" class="back-link">&larr; <?= htmlspecialchars($backLabel ?? 'Kembali', ENT_QUOTES, 'UTF-8') ?></a>
                    <?php else: ?>
                        <a href="/" class="back-link">&larr; Kembali ke Beranda</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
