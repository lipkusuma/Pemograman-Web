# Pemrograman Web

Repository ini memuat proyek antarmuka website Katalog, Dashboard Admin, serta integrasi simulasi *Login* dan *File Upload* (PHP). Dibuat menggunakan paduan HTML, CSS murni, JavaScript untuk interaktivitas sisi klien (*client-side*), dan file PHP.

## 📂 Struktur Direktori dan Penempatan

Penempatan file secara sengaja dipusatkan (*flat structure* pada root) untuk mempermudah _routing_ saling silang antar tautan statis tipe `.html` tanpa menggunakan library bantuan (React/Next). Sehingga strukturnya rapi namun fungsional:

```text
Pemograman-Web/
├── assets/
│   └── css/                 # 🎨 Seluruh aset gaya (global.css, auth.css, dashboard.css)
├── pages/                   # 📂 Penempatan seluruh layout aplikasi & fungsi
│   ├── login.html           # 🔐 Halaman Autentikasi Login (Simulasi Role)
│   ├── register.html        # 🔐 Halaman Pendaftaran
│   ├── katalog.html         # 🛍️ Antarmuka List Produk Utama (Customer)
│   ├── transaksi.html       # 🛍️ Antarmuka Checkout dan Order (Customer)
│   ├── dashboard.html       # 📊 Panel Admin (Tabel User, Chart)
│   └── upload_foto.php      # ⚙️ Fitur Handling PHP (Diintegrasikan di Profil)
├── index.html               # 🏠 Halaman pembuka (Landing Page utama) di Root
└── README.md                # 📄 Panduan dokumentasi proyek
```

*(Catatan: Direktori `uploads/` akan otomatis terbentuk pada level root saat `upload_foto.php` berhasil dieksekusi dari sisi server).*

## 🚀 Cara Menjalankan

Agar semua halaman (**terutama** form unggah file `.php` dan *routing* nya) berfungsi normal 100%:
1. Jalankan aplikasi web server Anda (Contoh: modul *Apache* di XAMPP).
2. Posisikan seluruh isi *repository* (folder `Pemograman-Web`) ini di dalam direktori server (contoh: `C:/xampp/htdocs/Pemograman-Web/`).
3. Buka *browser* pilihan Anda dan akses alamat **`http://localhost/Pemograman-Web/index.html`**!