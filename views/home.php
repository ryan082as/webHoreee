<?php
/** @var array<int, Src\Entities\StoreEntity> $stores */
$title = 'Beranda';
$showHomeNav = true;
include __DIR__ . '/layout/header.php';
?>

<section aria-labelledby="stores-heading">
    <h1 id="stores-heading" class="page-title">Katalog Nama Toko</h1>

    <div class="store-grid">
        <?php foreach ($stores as $store): ?>
            <article class="box store-card">
                <a href="/toko/<?= htmlspecialchars($store->getSlug(), ENT_QUOTES, 'UTF-8') ?>" style="text-decoration: none; color: inherit; display: block;">
                    <span class="store-badge"><?= htmlspecialchars($store->getCategory(), ENT_QUOTES, 'UTF-8') ?></span>
                    <h2 style="font-size: 1.25rem; margin-bottom: 6px;"><strong><?= htmlspecialchars($store->getName(), ENT_QUOTES, 'UTF-8') ?></strong></h2>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 8px;">
                        <small><?= htmlspecialchars($store->getCategory(), ENT_QUOTES, 'UTF-8') ?> - <?= htmlspecialchars($store->getDescription(), ENT_QUOTES, 'UTF-8') ?></small>
                    </p>
                    <div style="font-size: 0.85rem; color: var(--text-muted);">
                        <span>📍 <?= htmlspecialchars($store->getAddress(), ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>
