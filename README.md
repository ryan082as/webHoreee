## 🚀 Cara Menjalankan Proyek

### Cara 1 — Menggunakan PHP CLI Server (Paling Cepat & Praktis)

1. Buka Terminal / PowerShell di folder proyek:
   ```bash
   cd webHoreee
   ```
2. Jalankan perintah server:
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```
3. Buka browser di alamat:
   * **Website:** [http://127.0.0.1:8000](http://127.0.0.1:8000)
   * **REST API:** [http://127.0.0.1:8000/api/stores](http://127.0.0.1:8000/api/stores)

### Cara 2 — Menggunakan Laragon / XAMPP (Windows)

1. Pindahkan folder proyek ke `C:\laragon\www\webHoreee` (atau `htdocs` di XAMPP).
2. Pastikan web server Apache/Nginx aktif.
3. Karena Document Root aplikasi berada di folder `public`, Anda dapat:
   * Menjalankan terminal Laragon lalu ketik `php -S 127.0.0.1:8000 -t public`, **atau**
   * Mengatur Virtual Host Laragon agar Document Root mengarah ke `C:/laragon/www/webHoreee/public`.

---

## 🧪 Menjalankan Pengujian Otomatis (Tests)

Proyek dilengkapi dengan Test Suite otomatis yang memverifikasi 100% fungsionalitas OOP, rute, form, keranjang, semantik HTML, dan REST API.

Jalankan perintah berikut di terminal:

```bash
php tests/run_tests.php
```
