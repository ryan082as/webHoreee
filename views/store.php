<?php
/** @var Src\Entities\StoreEntity $store */
use Src\Core\Session;

$title = $store->getName();
$showHomeNav = false;
$backUrl = '/';
$backLabel = 'Kembali ke Beranda';
$notice = Session::getFlash('notice');
include __DIR__ . '/layout/header.php';
?>

<?php if (!empty($notice)): ?>
    <section class="notice-box row" data-notice-box aria-live="polite">
        <span><?= htmlspecialchars($notice, ENT_QUOTES, 'UTF-8') ?></span>
        <button type="button" data-dismiss-notice>Tutup</button>
    </section>
<?php endif; ?>

<section class="box" aria-label="Informasi Toko">
    <h1 style="font-size: 1.5rem; margin-bottom: 8px;"><strong><?= htmlspecialchars($store->getName(), ENT_QUOTES, 'UTF-8') ?></strong></h1>
    <p style="color: var(--text-muted); margin-bottom: 12px;"><?= htmlspecialchars($store->getDescription(), ENT_QUOTES, 'UTF-8') ?></p>
    <div style="font-size: 0.9rem; color: var(--text-muted); border-top: 1px solid var(--border-color); padding-top: 8px;">
        <small>
            <?= htmlspecialchars($store->getAddress(), ENT_QUOTES, 'UTF-8') ?> | 
            <strong><?= htmlspecialchars($store->getWhatsappNumber(), ENT_QUOTES, 'UTF-8') ?></strong> | 
            <?= htmlspecialchars($store->getOperationalHours(), ENT_QUOTES, 'UTF-8') ?>
        </small>
    </div>
</section>

<section aria-labelledby="catalog-heading">
    <h3 id="catalog-heading" class="section-title">Katalog Barang</h3>

    <div class="product-grid">
        <?php foreach ($store->getProducts() as $product): ?>
            <article class="box product-card">
                <?php if (!empty($product->getThumbnailUrl())): ?>
                    <div class="product-img-wrapper">
                        <img src="<?= htmlspecialchars($product->getThumbnailUrl(), ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                <?php else: ?>
                    <div class="product-img-wrapper" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                            <line x1="12" y1="22.08" x2="12" y2="12"></line>
                        </svg>
                        <span style="font-size: 0.78rem; color: #64748b; font-weight: 500;">Produk</span>
                    </div>
                <?php endif; ?>

                <p class="product-name"><strong><?= htmlspecialchars($product->getName(), ENT_QUOTES, 'UTF-8') ?></strong></p>

                <div class="product-actions row" style="flex-wrap: wrap;">
                    <span class="product-price"><?= htmlspecialchars($product->getFormattedPrice(), ENT_QUOTES, 'UTF-8') ?></span>

                    <div style="display: flex; align-items: center; gap: 8px;">
                        <form action="/keranjang/tambah" method="POST" class="quantity-form" data-validate-qty>
                            <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                            <input type="number" name="quantity" value="1" min="1" class="input-qty" aria-label="Jumlah">
                            <button type="submit" class="btn btn-primary btn-sm">Tambah ke Keranjang</button>
                        </form>

                        <a href="/toko/<?= htmlspecialchars($store->getSlug(), ENT_QUOTES, 'UTF-8') ?>/barang/<?= htmlspecialchars($product->getSlug(), ENT_QUOTES, 'UTF-8') ?>">
                            <button type="button" class="btn btn-secondary btn-sm">Detail</button>
                        </a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>
