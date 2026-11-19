# Product Requirements Document (PRD)
**Nama Produk:** Katalog Multi-Toko & Sistem Pemesanan (MVP)  
**Versi:** 2.0 (Sesuai Implementasi Aktual)  
**Status:** Implementasi Kerangka MVP Berjalan (Fase 1)  
**Lokasi Proyek:** `E:\ide\web\webDadakan`  
**Teknologi:** Laravel 13 (PHP 8.3+), Blade (HTML5 Semantik, CSS Polos *Mobile-First*, JS Interaktif Ringan), PHP OOP (`src/` PSR-4)

---

## 1. Ringkasan Produk (*Product Overview*)
Aplikasi web *mobile-first* yang berfungsi sebagai direktori etalase multi-toko (**Toko A** dan **Toko B**) dalam satu platform. Pengunjung dapat memilih toko, melihat daftar produk beserta harga dan detail spesifikasinya, menambahkan barang ke keranjang langsung dari halaman katalog toko, serta mengirim pesanan per toko langsung ke WhatsApp admin masing-masing toko **tanpa perlu login atau membuat akun terlebih dahulu**.

Pada tahap awal sebelum server dan database MySQL tersedia, aplikasi membaca data dan endpoint API dari file lokal di dalam kode (`database/databaseInCode.php` dan `routes/apiInCode.php`) dan dapat dialihkan ke database MySQL melalui konfigurasi `.env` (`APP_IN_CODE=false`) tanpa mengubah alur aplikasi.

---

## 2. Tujuan Produk (*Objectives*)
* Menyediakan katalog terpusat untuk menampilkan **Toko A** (`+62 857-0424-4288`) dan **Toko B** (`+62 895-8107-11221`).
* Mempercepat alur belanja pengguna dengan menyediakan tombol **Tambah ke Keranjang** langsung di samping tombol **Detail** pada halaman toko.
* Menyederhanakan checkout per toko menggunakan tombol **Bayar** yang terhubung langsung ke WhatsApp admin toko terkait.
* Menerapkan struktur kode modular berbasis **PHP OOP** di folder `src/` (*Namespace, Class, Object, Encapsulation, Inheritance, Polymorphism*) dan elemen HTML5 semantik.
* Memisahkan fitur masa depan (**Langganan** dan **Login**) sebagai halaman placeholder `"Masih dalam pengembangan"` yang hanya diakses dari Halaman Awal.

---

## 3. Target Pengguna (*Target Audience*)
* **Pelanggan / Pembeli:** Pengunjung yang ingin melihat daftar toko, memeriksa harga dan spesifikasi barang, memasukkan barang ke keranjang, dan mengirim pesanan ke WhatsApp admin toko secara cepat.
* **Pemilik Toko (Toko A & Toko B):** Pengelola masing-masing toko yang menerima pesanan masuk melalui nomor WhatsApp admin toko.

---

## 4. Alur Pengguna (*User Flow*)
1. Pengguna membuka **Halaman Awal (`/`)**.
2. *(Opsional)* Di pojok kanan atas Halaman Awal, pengguna dapat mengklik tombol **Langganan** (`/langganan`) atau **Login** (`/login`) → masuk ke halaman keterangan `"Masih dalam pengembangan"` beserta tautan kembali ke Beranda.
3. Pengguna mengklik salah satu kartu toko (**Toko A** atau **Toko B**) → masuk ke **Halaman Per Toko (`/toko/{slug}`)**.
4. Di Halaman Per Toko, pengguna dapat:
   * Mengatur jumlah dan mengklik **Tambah ke Keranjang** (`POST /keranjang/tambah`) → muncul notifikasi `"Barang ditambahkan ke keranjang."` (dapat ditutup lewat tombol **Tutup**) dan indikator angka pada tombol melayang **Keranjang** bertambah.
   * Mengklik **Detail** → masuk ke **Halaman Detail Barang (`/toko/{slug}/barang/{productSlug}`)** untuk melihat foto besar, harga, deskripsi, dan **List Detail Barang** (dapat dibuka/tutup lewat tombol **Sembunyikan Detail** / **Tampilkan Detail**).
5. Pengguna mengklik tombol melayang **Keranjang** di kanan bawah (tersedia di Halaman Awal, Per Toko, dan Detail Barang) → masuk ke **Halaman Keranjang (`/keranjang`)**.
6. Di Halaman Keranjang, pengguna melihat daftar item dikelompokkan per toko, dapat mengubah jumlah (**Ubah**) atau menghapus item (**Hapus** dengan konfirmasi JS), melihat **Subtotal Toko**, lalu mengklik **Bayar** di sebelah kanan subtotal → diarahkan ke tautan WhatsApp admin toko (`https://wa.me/...`) berisi ringkasan pesanan toko tersebut.

---

## 5. Spesifikasi Fitur & Kebutuhan Fungsional

### 5.1. Halaman Awal (`/`) — [home.blade.php](resources/views/home.blade.php)
* **Header:**
  * Kiri: `[Logo] Katalog Multi-Toko`.
  * Kanan atas (`<nav>`): tombol **Langganan** (`/langganan`) dan **Login** (`/login`) — **hanya muncul di Halaman Awal**.
* **Konten Utama:**
  * Judul `Katalog Nama Toko`.
  * Daftar kartu `<article>` untuk **Toko A** (`Kategori A - Deskripsi singkat Toko A`) dan **Toko B** (`Kategori B - Deskripsi singkat Toko B`) yang dapat diklik menuju `/toko/{slug}`.
* **Navigasi Keranjang:** Tombol melayang `Keranjang` (beserta jumlah item jika `> 0`) di sudut kanan bawah.

### 5.2. Halaman Per Toko (`/toko/{slug}`) — [store.blade.php](resources/views/store.blade.php)
* **Header:** Link `← Kembali ke Beranda` *(tanpa tombol Langganan & Login)*.
* **Notifikasi:** Kotak pesan saat barang berhasil ditambahkan ke keranjang dengan tombol **Tutup** (interaktivitas JS).
* **Informasi Toko (`<section>`):** Nama toko, deskripsi singkat, alamat, nomor WhatsApp (`+62 857-0424-4288` untuk Toko A, `+62 895-8107-11221` untuk Toko B), dan jam operasional.
* **Katalog Barang (`<article>` per item):**
  * Placeholder/gambar thumbnail produk.
  * Nama barang.
  * Baris aksi sejajar: **Label Harga** (`Rp ...`) di kiri, serta input jumlah (`min="1"`), tombol **Tambah ke Keranjang**, dan tombol **Detail** berdampingan di kanan.
* **Navigasi Keranjang:** Tombol melayang `Keranjang` di sudut kanan bawah.

### 5.3. Halaman Detail Barang (`/toko/{slug}/barang/{productSlug}`) — [product.blade.php](resources/views/product.blade.php)
* **Header:** Link `← Kembali ke {Nama Toko}` *(tanpa tombol Langganan & Login)*.
* **Ringkasan Produk (`<article>`):** Kotak foto produk besar, nama produk, dan harga. *(Tidak menampilkan tombol Tambah ke Keranjang di halaman ini).*
* **List Detail Barang (`<section>`):**
  * Tombol JS **Sembunyikan Detail** / **Tampilkan Detail** untuk melipat/membuka rincian.
  * Deskripsi produk serta daftar spesifikasi terstruktur (`Ukuran`, `Berat`, `Varian`, `Bahan`, `Status Stok`).
* **Navigasi Keranjang:** Tombol melayang `Keranjang` di sudut kanan bawah.

### 5.4. Keranjang & Pemesanan (`/keranjang`) — [cart.blade.php](resources/views/cart.blade.php)
* **Header:** Link `← Kembali ke Beranda` *(tanpa tombol Langganan & Login)*.
* **Daftar Belanja per Toko (`<section>` per toko):**
  * Item dikelompokkan berdasarkan toko asal (`Toko A`, `Toko B`).
  * Tiap item (`<article>`) memuat nama barang, subtotal harga item, harga satuan, form ubah jumlah (`quantity` `min="0"` + tombol **Ubah**), dan form hapus (**Hapus** dengan konfirmasi JS `confirm(...)`).
  * Baris bawah tiap toko menampilkan **Subtotal Toko** di kiri dan tombol **Bayar** di kanan.
  * *(Tanpa form pengisian data pemesan dan tanpa kotak `Total Keseluruhan` di bawah).*
* **Integrasi WhatsApp (`POST /keranjang/checkout`):**
  * Mengklik **Bayar** menyusun daftar barang, qty, subtotal per barang, dan total toko ke format pesan teks, membersihkan karakter non-digit pada nomor WhatsApp toko tujuan, dan mengalihkan ke `https://wa.me/{nomor_bersih}?text=...`.

### 5.5. Halaman Placeholder (`/langganan` & `/login`)
* **[langganan.blade.php](resources/views/langganan.blade.php):** Hanya memuat link `← Kembali ke Beranda`, judul `Langganan`, dan kotak teks `Masih dalam pengembangan`.
* **[login.blade.php](resources/views/login.blade.php):** Hanya memuat link `← Kembali ke Beranda`, judul `Login`, dan kotak teks `Masih dalam pengembangan`.

---

## 6. Spesifikasi Arsitektur, Data, API, & Pengujian

### 6.1. Struktur OOP (`src/`) & Modularitas Sumber Data
* **Kontrak & Entity OOP (`src/`):**
  * `Src\Contracts\CatalogSourceInterface` sebagai kontrak tunggal sumber data.
  * `Src\Entities\CatalogEntity` (abstract parent) diturunkan oleh `StoreEntity` dan `ProductEntity` dengan properti terenkapsulasi (`private`/`protected`), getter publik, serta override polimorfik `toSummary()`.
  * `Src\Catalog\BaseCatalogSource` diturunkan oleh `InCodeCatalogSource` dan `DatabaseCatalogSource`, di-bind oleh [AppServiceProvider.php](app/Providers/AppServiceProvider.php) berdasarkan `APP_IN_CODE` di `.env`.
* **Data Awal (`database/databaseInCode.php`):** Menyimpan 2 toko (`Toko A` dengan 2 produk, `Toko B` dengan 1 produk) saat `APP_IN_CODE=true`.
* **Skema Database Siap Server:** Migration `stores` dan `products` beserta Model `App\Models\Store` dan `App\Models\Product` siap dipakai saat `APP_IN_CODE=false`.

### 6.2. API Lokal Modular (`routes/api.php` & `routes/apiInCode.php`)
* `GET /api/stores` → mengembalikan daftar seluruh toko dalam format JSON.
* `GET /api/stores/{slug}` → mengembalikan detail toko beserta daftar produknya dalam format JSON.
* `GET /api/stores/{slug}/barang/{productSlug}` → mengembalikan detail 1 produk dalam format JSON (`404` jika tidak ditemukan).
* Saat `APP_IN_CODE=true`, endpoint dilayani melalui [apiInCode.php](routes/apiInCode.php); saat `APP_IN_CODE=false`, dilayani melalui [ApiController.php](app/Http/Controllers/ApiController.php).

### 6.3. Semantik HTML, CSS, JS, & Pengujian
* **Semantik HTML5:** Seluruh view memakai `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, dan `<footer>`.
* **CSS & JS:** Tampilan menggunakan CSS polosan *mobile-first* (`max-width: 520px`, kotak garis sederhana) dan JS interaktif ringan (`data-dismiss-notice`, `data-toggle-specs`, `data-confirm-remove`).
* **Pengujian Otomatis (`tests/`):**
  * [CatalogOopTest.php](tests/Unit/CatalogOopTest.php) menguji inheritance, encapsulation, polymorphism, dan normalisasi nomor WhatsApp.
  * [WebDadakanSkeletonTest.php](tests/Feature/WebDadakanSkeletonTest.php) menguji seluruh rute web, API `/api/stores*`, penempatan tombol `Langganan`/`Login`/`Tambah ke Keranjang`/`Bayar`, penghapusan form checkout & `Total Keseluruhan`, serta redirect `wa.me` untuk Toko A dan Toko B.

---

## 7. Ruang Lingkup Belum Dikerjakan / Tahap Lanjutan (*Backlog*)
1. **Estetika UI & Layout Responsif Lanjutan:** Styling warna, tipografi, kartu modern, serta breakpoint `@media` query untuk tablet/desktop *(sengaja ditunda)*.
2. **Validasi Form Sisi Klien (JS):** Penambahan validasi form via JavaScript apabila nantinya ada input form pengguna *(sengaja ditunda)*.
3. **Database Server MySQL (`APP_IN_CODE=false`):** Penyediaan server database, eksekusi `php artisan migrate`, serta pengisian data toko/produk ke MySQL.
4. **Fase 2 (Login & Register):** Implementasi autentikasi penuh di [AuthController.php](app/Http/Controllers/AuthController.php) dan pengelolaan toko per pemilik.
5. **Fase 3 (Langganan & Payment Gateway):** Implementasi paket langganan di [SubscriptionController.php](app/Http/Controllers/SubscriptionController.php) serta opsi pembayaran digital jika tombol **Bayar** dikembangkan ke payment gateway.
