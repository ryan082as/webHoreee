<?php
/** @var Src\Entities\StoreEntity $store */
/** @var Src\Entities\ProductEntity $product */
$title = $product->getName() . ' - ' . $store->getName();
$showHomeNav = false;
$backUrl = '/toko/' . $store->getSlug();
$backLabel = 'Kembali ke ' . $store->getName();
include __DIR__ . '/layout/header.php';
?>

<article class="box" style="margin-bottom: 20px;">
    <?php if (!empty($product->getImageUrl())): ?>
        <div style="width: 100%; height: 260px; border-radius: var(--radius-sm); overflow: hidden; margin-bottom: 14px;">
            <img src="<?= htmlspecialchars($product->getImageUrl(), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8') ?>" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    <?php else: ?>
        <div style="height: 220px; border-radius: var(--radius-sm); display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); color: #64748b; margin-bottom: 14px; gap: 10px; border: 1px solid #e2e8f0;">
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                <line x1="12" y1="22.08" x2="12" y2="12"></line>
            </svg>
            <span style="font-size: 0.95rem; font-weight: 600; color: #475569;"><?= htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8') ?></span>
        </div>
    <?php endif; ?>

    <h1 style="font-size: 1.6rem; margin-bottom: 6px;"><strong><?= htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8') ?></strong></h1>
    <p class="product-price" style="font-size: 1.35rem;"><?= htmlspecialchars($product->getFormattedPrice(), ENT_QUOTES, 'UTF-8') ?></p>
</article>

<section class="box" aria-labelledby="specs-heading">
    <div class="row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">
        <h3 id="specs-heading" style="margin: 0; font-size: 1.2rem;">List Detail Barang</h3>
        <button type="button" class="btn btn-secondary btn-sm" data-toggle-specs="product-specs-body">Sembunyikan Detail</button>
    </div>

    <div id="product-specs-body">
        <p style="margin-bottom: 16px; color: var(--text-main);"><?= htmlspecialchars($product->getDescription(), ENT_QUOTES, 'UTF-8') ?></p>
        
        <h4 style="font-size: 1rem; margin-bottom: 8px; color: var(--text-muted);">Spesifikasi Produk:</h4>
        <ul class="specs-list">
            <?php foreach ($product->getSpecs() as $label => $value): ?>
                <li>
                    <strong><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></strong>
                    <span><?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8') ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>
