<?php
/** @var string|null $notice */
$title = 'Langganan';
$showHomeNav = false;
$backUrl = '/';
$backLabel = 'Kembali ke Beranda';
include __DIR__ . '/layout/header.php';
?>

<section aria-labelledby="langganan-heading">
    <h1 id="langganan-heading" class="page-title">Langganan</h1>

    <div class="box" style="text-align: center; padding: 40px 20px;">
        <div style="font-size: 2.5rem; margin-bottom: 12px;">⭐</div>
        <h2 style="font-size: 1.25rem; margin-bottom: 8px;">Paket Langganan Member Eksklusif</h2>
        <div class="box" style="display: inline-block; padding: 10px 24px; margin-bottom: 16px; font-weight: 600;">
            Masih dalam pengembangan
        </div>
        <?php if (!empty($notice) && $notice !== 'Masih dalam pengembangan'): ?>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 16px;">
                <?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?>
            </p>
        <?php endif; ?>
        <div>
            <a href="/" class="btn btn-primary">&larr; Kembali ke Beranda</a>
        </div>
    </div>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>
