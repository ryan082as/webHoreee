<?php
use Src\Core\Session;

$cart = Session::get('cart', []);
$cartQty = 0;
foreach ($cart as $item) {
    $cartQty += (int) ($item['quantity'] ?? 0);
}
?>
    </main>

    <?php if (!isset($hideFloatingCart) || !$hideFloatingCart): ?>
        <aside aria-label="Keranjang Belanja" class="floating-cart-wrapper">
            <a href="/keranjang" class="floating-cart">
                <span>Keranjang</span>
                <?php if ($cartQty > 0): ?>
                    <span class="cart-badge">(<?= $cartQty ?>)</span>
                <?php endif; ?>
            </a>
        </aside>
    <?php endif; ?>

    <footer class="site-footer footer">
        <div class="container">
            <small>&copy; <?= date('Y') ?> Katalog Multi-Toko. UTS Pemrograman Web UMM.</small>
        </div>
    </footer>

    <script src="/assets/js/app.js"></script>
</body>
</html>
