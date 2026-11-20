<?php
/** @var array<int|string, array<string, mixed>> $cart */
$title = 'Keranjang & Pemesanan';
$showHomeNav = false;
$backUrl = '/';
$backLabel = 'Kembali ke Beranda';
$hideFloatingCart = true;
include __DIR__ . '/layout/header.php';

$grouped = [];
foreach ($cart as $item) {
    $storeId = (int) ($item['store_id'] ?? 0);
    $grouped[$storeId]['store_name'] = $item['store_name'] ?? 'Toko';
    $grouped[$storeId]['items'][] = $item;
}
?>

<section aria-labelledby="cart-heading">
    <h1 id="cart-heading" class="page-title">Keranjang &amp; Pemesanan</h1>

    <?php if (!empty($grouped)): ?>
        <?php foreach ($grouped as $storeId => $group): ?>
            <?php $storeTotal = 0; ?>
            <section class="cart-store-group" aria-label="Keranjang <?= htmlspecialchars($group['store_name'], ENT_QUOTES, 'UTF-8') ?>">
                <h2 style="font-size: 1.25rem; margin-bottom: 12px; color: var(--primary);">
                    🏪 <strong><?= htmlspecialchars($group['store_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                </h2>

                <?php foreach ($group['items'] as $item): ?>
                    <?php
                        $price = (int) ($item['price'] ?? 0);
                        $quantity = (int) ($item['quantity'] ?? 1);
                        $subtotal = $price * $quantity;
                        $storeTotal += $subtotal;
                    ?>
                    <article class="cart-item">
                        <div style="flex: 1;">
                            <strong style="font-size: 1.05rem;"><?= htmlspecialchars($item['name'] ?? '', ENT_QUOTES, 'UTF-8') ?></strong>
                            <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 2px;">
                                Harga satuan: Rp <?= number_format($price, 0, ',', '.') ?>
                            </div>

                            <div class="cart-actions">
                                <form action="/keranjang/update" method="POST" class="quantity-form" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $quantity ?>" min="0" class="input-qty" style="width: 50px; padding: 4px;" aria-label="Kuantitas">
                                    <button type="submit" class="btn btn-secondary btn-sm">Ubah</button>
                                </form>

                                <form action="/keranjang/hapus" method="POST" style="display: inline;" data-confirm-remove>
                                    <input type="hidden" name="product_id" value="<?= (int) $item['product_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>

                        <div style="text-align: right;">
                            <span style="font-weight: 700; font-size: 1.05rem; color: var(--primary);">
                                Rp <?= number_format($subtotal, 0, ',', '.') ?>
                            </span>
                        </div>
                    </article>
                <?php endforeach; ?>

                <div class="cart-store-footer">
                    <div>
                        <strong style="font-size: 1.1rem;">Subtotal Toko: Rp <?= number_format($storeTotal, 0, ',', '.') ?></strong>
                    </div>

                    <form action="/keranjang/checkout" method="POST" style="margin: 0;">
                        <input type="hidden" name="store_id" value="<?= $storeId ?>">
                        <button type="submit" class="btn btn-success">Bayar</button>
                    </form>
                </div>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <section class="box" style="text-align: center; padding: 40px 20px;">
            <p style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 16px;">Keranjang masih kosong.</p>
            <a href="/" class="btn btn-primary">&larr; Mulai Belanja di Katalog</a>
        </section>
    <?php endif; ?>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>
