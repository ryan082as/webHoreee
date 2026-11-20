# Product Requirements Document (PRD)
**Nama Produk:** Katalog Multi-Toko & Sistem Pemesanan (MVP)  
**Versi:** 3.0 (Implementasi PHP OOP Modern Native Tanpa Framework)  
**Status:** Selesai Diimplementasikan 100%  
**Teknologi:** PHP 8.x Modern OOP Murni (Tanpa Framework), HTML5 Semantik, CSS Responsif Modern, JS Interaktif

---

## 1. Ringkasan Produk (*Product Overview*)
Aplikasi web responsif (*mobile-first* & *desktop-friendly*) yang berfungsi sebagai direktori etalase multi-toko (**Toko A** dan **Toko B**) dalam satu platform. Pengunjung dapat memilih toko, melihat daftar produk beserta harga dan detail spesifikasinya, menambahkan barang ke keranjang langsung dari halaman katalog toko, serta mengirim pesanan per toko langsung ke WhatsApp admin masing-masing toko **tanpa perlu login atau membuat akun terlebih dahulu**.

Arsitektur aplikasi dirancang menggunakan pola **PHP OOP Modern** dengan PSR-4 autoloader internal di `src/` yang mematuhi prinsip OOP (*Class, Object, Encapsulation, Inheritance, Polymorphism, Namespace*) tanpa ketergantungan framework apa pun (memenuhi kriteria UTS Pemrograman Web).

---

## 2. Fitur & Alur Pengguna (*User Flow*)
1. **Halaman Awal (`/`):**
   * Menampilkan header dengan logo dan tombol **Langganan** serta **Login** (*hanya muncul di Halaman Awal*).
   * Menampilkan kartu etalase untuk **Toko A** dan **Toko B**.
   * Menampilkan tombol melayang **Keranjang** di sudut kanan bawah dengan indikator jumlah barang.
2. **Halaman Per Toko (`/toko/{slug}`):**
   * Menampilkan informasi toko: nama, deskripsi, alamat, no. WhatsApp, dan jam operasional.
   * Katalog barang dengan gambar/thumbnail, nama produk, harga terformat (`Rp ...`), input kuantitas validasi minimal 1, tombol **Tambah ke Keranjang**, dan tombol **Detail**.
   * Notifikasi berhasil saat barang dimasukkan ke keranjang dengan tombol **Tutup** (interaktivitas JS).
3. **Halaman Detail Barang (`/toko/{slug}/barang/{productSlug}`):**
   * Foto produk besar, nama produk, dan harga terformat.
   * List detail & spesifikasi produk terstruktur dengan tombol interaktif **Sembunyikan Detail** / **Tampilkan Detail**.
4. **Halaman Keranjang & Pemesanan (`/keranjang`):**
   * Item dikelompokkan rapi per toko asal.
   * Tiap item memuat nama, harga satuan, subtotal, form ubah kuantitas, dan tombol hapus dengan konfirmasi JS.
   * Bagian bawah tiap grup toko memuat **Subtotal Toko** dan tombol **Bayar** yang mengarahkan ke link WhatsApp admin toko terkait (`https://wa.me/...`) berisi rincian pesanan.
5. **Halaman Placeholder Masa Depan (`/login` & `/langganan`):**
   * Sesuai ketentuan, fitur ini disediakan sebagai kerangka antarmuka dengan status *"Masih dalam pengembangan"*.
6. **REST API JSON:**
   * `GET /api/stores`
   * `GET /api/stores/{slug}`
   * `GET /api/stores/{slug}/barang/{productSlug}`

---

## 3. Rubrik Penilaian & Arsitektur Teknis
* **Front-End (HTML/CSS/JS):** Form validasi input angka kuantitas, semantic tags (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>`), CSS grid responsif modern, JS interaktif (dismiss notice, accordion detail, modal konfirmasi hapus).
* **Penerapan PHP OOP:**
  * `Src\Entities\CatalogEntity` (abstract parent) diturunkan oleh `StoreEntity`, `ProductEntity`, dan `UserEntity`.
  * Enkapsulasi data dengan visibility `private` dan `protected`.
  * Polimorfisme pada override method `toSummary()` dan kontrak `CatalogSourceInterface`.
  * Namespace PSR-4 di `src/`.
* **Kerapian Berkas:** Penataan folder modular (`src/Core/`, `src/Entities/`, `src/Contracts/`, `src/Catalog/`, `src/Controllers/`, `views/`, `public/assets/`, `data/`).
* **Pengujian:** Tersedia test runner native `php tests/run_tests.php` dengan 53 assertions lulus (100% passed).
