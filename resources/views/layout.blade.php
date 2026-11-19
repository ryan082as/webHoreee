<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body { font-family: sans-serif; margin: 0 auto; padding: 16px 16px 80px; max-width: 520px; }
        .topbar { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #ccc; padding-bottom: 10px; margin-bottom: 16px; gap: 8px; }
        .box { border: 1px solid #999; padding: 12px; margin-bottom: 12px; }
        .row { display: flex; justify-content: space-between; align-items: center; gap: 8px; }
        .floating-cart { position: fixed; right: 16px; bottom: 16px; border: 1px solid #000; background: #fff; padding: 10px 14px; text-decoration: none; color: #000; }
        .footer { margin-top: 24px; border-top: 1px solid #ccc; padding-top: 10px; color: #555; }
    </style>
</head>
<body>
    @yield('header')

    <main>
        @yield('content')
    </main>

    @hasSection('floating_cart')
        @php $cartQty = array_sum(array_column(session('cart', []), 'quantity')); @endphp
        <aside aria-label="Keranjang Belanja">
            <a href="{{ route('cart.index') }}" class="floating-cart">
                Keranjang{{ $cartQty > 0 ? " ({$cartQty})" : '' }}
            </a>
        </aside>
    @endif

    <footer class="footer">
        <small>&copy; {{ date('Y') }} {{ config('app.name') }}</small>
    </footer>

    <script>
        document.addEventListener('click', function (event) {
            var dismissBtn = event.target.closest('[data-dismiss-notice]');
            if (dismissBtn) {
                var box = dismissBtn.closest('[data-notice-box]');
                if (box) {
                    box.style.display = 'none';
                }
            }

            var toggleBtn = event.target.closest('[data-toggle-specs]');
            if (toggleBtn) {
                var target = document.getElementById(toggleBtn.getAttribute('data-toggle-specs'));
                if (target) {
                    var isHidden = target.style.display === 'none';
                    target.style.display = isHidden ? '' : 'none';
                    toggleBtn.textContent = isHidden ? 'Sembunyikan Detail' : 'Tampilkan Detail';
                }
            }
        });

        document.addEventListener('submit', function (event) {
            var form = event.target.closest('[data-confirm-remove]');
            if (form && !window.confirm('Hapus barang ini dari keranjang?')) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
