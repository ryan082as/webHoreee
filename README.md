# webHoreee

Kerangka web **Katalog Multi-Toko & Sistem Pemesanan (MVP)** berbasis Laravel dengan struktur PHP OOP (`src/`), HTML5 semantik, data/API lokal sementara (`database/databaseInCode.php` dan `routes/apiInCode.php`), serta integrasi checkout ke WhatsApp admin toko.

Lihat spesifikasi lengkap pada [PRD.md](PRD.md).

## Cara Menjalankan Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Lalu buka `http://127.0.0.1:8000` di browser (atau `http://127.0.0.1:8000/api/stores` untuk API lokal).

## Menjalankan Test

```bash
php artisan test
```

## Berpindah ke Database Server (MySQL)

1. Ubah `APP_IN_CODE=false` dan isi konfigurasi `DB_*` di `.env`.
2. Jalankan migrasi & seeder data awal:
   ```bash
   php artisan migrate --seed
   ```
