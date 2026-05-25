# Pemrograman Web (Migrasi Laravel)

Repository ini memuat proyek antarmuka website Katalog, Dashboard Admin, serta integrasi simulasi *Login* dan *File Upload* yang telah dimigrasi ke framework **Laravel**. Dibuat menggunakan Laravel, Blade Templates, dan CSS murni.

## 📂 Struktur Direktori Setelah Migrasi Laravel

Struktur direktori sekarang mengikuti standar struktur Laravel:

```text
Pemograman-Web/
├── app/                     # ⚙️ Logika inti aplikasi (Controllers, Middleware, Models, Providers)
├── bootstrap/               # 🚀 Bootstrap file & routing cache
├── config/                  # ⚙️ Konfigurasi aplikasi
├── database/                # 🗄️ Migrasi database dan seeders
├── public/                  # 🌐 Folder publik utama (CSS, JS, Uploads, index.php)
│   ├── assets/              # 🎨 Aset gaya css & js
│   └── uploads/             # 📂 Penyimpanan file terunggah
├── resources/               # 🖥️ Tampilan Blade view dan aset mentah
│   └── views/               # 📄 Halaman Blade (Home, Auth, Dashboard, Katalog, Transaksi, dll)
├── routes/                  # 🛣️ File definisi rute web (routes/web.php)
├── storage/                 # 📂 Penyimpanan sesi, cache, dan log
└── composer.json            # 📄 File dependensi PHP Composer
```

## 🚀 Cara Menjalankan

Untuk menjalankan aplikasi ini secara lokal:
1. Pastikan Anda sudah menginstal **PHP** (>= 8.2) dan **Composer**.
2. Clone repository ini dan masuk ke dalam foldernya.
3. Buat file `.env` dari `.env.example`:
   ```bash
   cp .env.example .env
   ```
4. Instal dependensi PHP menggunakan Composer:
   ```bash
   composer install
   ```
5. Buat key aplikasi baru:
   ```bash
   php artisan key:generate
   ```
6. Konfigurasikan koneksi database Anda di file `.env` (misal MySQL/SQLite).
7. Jalankan migrasi database:
   ```bash
   php artisan migrate
   ```
8. Jalankan local development server:
   ```bash
   php artisan serve
   ```
9. Buka *browser* pilihan Anda dan akses alamat **`http://127.0.0.1:8000`**!
