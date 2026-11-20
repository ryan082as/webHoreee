/**
 * JavaScript Interaktivitas & Validasi Form
 * Memenuhi Rubrik Penilaian Front-End UTS Pemrograman Web
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Interaktivitas: Dismiss Notice (Tutup Pesan Notifikasi)
    document.addEventListener('click', function (event) {
        var dismissBtn = event.target.closest('[data-dismiss-notice]');
        if (dismissBtn) {
            var box = dismissBtn.closest('[data-notice-box]');
            if (box) {
                box.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                box.style.opacity = '0';
                box.style.transform = 'translateY(-10px)';
                setTimeout(function () {
                    box.style.display = 'none';
                }, 300);
            }
        }
    });

    // 2. Interaktivitas: Toggle Accordion List Detail / Spesifikasi Barang
    document.addEventListener('click', function (event) {
        var toggleBtn = event.target.closest('[data-toggle-specs]');
        if (toggleBtn) {
            var targetId = toggleBtn.getAttribute('data-toggle-specs');
            var targetEl = document.getElementById(targetId);
            if (targetEl) {
                var isHidden = targetEl.style.display === 'none';
                targetEl.style.display = isHidden ? 'block' : 'none';
                toggleBtn.textContent = isHidden ? 'Sembunyikan Detail' : 'Tampilkan Detail';
            }
        }
    });

    // 3. Interaktivitas: Konfirmasi Hapus Barang dari Keranjang
    document.addEventListener('submit', function (event) {
        var confirmForm = event.target.closest('[data-confirm-remove]');
        if (confirmForm) {
            var confirmed = window.confirm('Apakah Anda yakin ingin menghapus barang ini dari keranjang belanja?');
            if (!confirmed) {
                event.preventDefault();
            }
        }
    });

    // 4. Validasi Form Kuantitas (Front-End Form Validation)
    document.addEventListener('submit', function (event) {
        var qtyForm = event.target.closest('.quantity-form, [data-validate-qty]');
        if (qtyForm) {
            var qtyInput = qtyForm.querySelector('input[type="number"][name="quantity"]');
            if (qtyInput) {
                var val = parseInt(qtyInput.value, 10);
                var min = parseInt(qtyInput.getAttribute('min') || '1', 10);

                if (isNaN(val) || val < min) {
                    event.preventDefault();
                    alert('Jumlah barang harus berupa angka minimal ' + min + '.');
                    qtyInput.focus();
                    return false;
                }
            }
        }
    });
});
