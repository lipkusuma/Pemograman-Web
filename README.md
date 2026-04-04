# Pemrograman Web

Repository ini memuat proyek antarmuka website Katalog, Dashboard Admin, serta integrasi simulasi *Login* dan *File Upload* (PHP). Dibuat menggunakan paduan HTML, CSS murni, JavaScript untuk interaktivitas sisi klien (*client-side*), dan file PHP.

## 📂 Struktur Direktori dan Penempatan

Penempatan file secara sengaja dipusatkan (*flat structure* pada root) untuk mempermudah _routing_ saling silang antar tautan statis tipe `.html` tanpa menggunakan library bantuan (React/Next). Sehingga strukturnya rapi namun fungsional:

```text
Pemograman-Web/
├── styles/                  # 🎨 Seluruh aset gaya tampilan (CSS)
│   ├── auth.css             # Desain khusus untuk halaman Register & Login
│   ├── dashboard.css        # Layout Grid/Sidebar untuk Admin & Katalog Utama
│   └── global.css           # Desain pondasi (font, warna global, reset)
├── index.html               # 🏠 Halaman pembuka (Landing Page utama)
├── login.html               # 🔐 Halaman form Autentikasi (dilengkapi skrip Role admin/user)
├── register.html            # 🔐 Halaman pendaftaran
├── katalog.html             # 🛍️ Antarmuka List Produk Utama (Untuk Customer)
├── transaksi.html           # 🛍️ Antarmuka Checkout dan Monitoring Order (Customer)
├── dashboard.html           # 📊 Panel Admin (Chart Statistik, Tabel User Aktif)
├── upload_foto.php          # ⚙️ TUGAS PHP: Skrip Handling Unggah Foto terintegrasi (Sidebar Profil)
└── README.md                # 📄 Panduan dokumentasi
```

*(Catatan: Direktori `uploads/` akan otomatis terbuat ketika fitur PHP dijalankan dengan sukses).*

## 🚀 Cara Menjalankan

Agar semua halaman (**terutama** form unggah file `.php` dan *routing* nya) berfungsi normal 100%:
1. Jalankan aplikasi web server Anda (Contoh: modul *Apache* di XAMPP).
2. Posisikan seluruh isi *repository* (folder `Pemograman-Web`) ini di dalam direktori server (contoh: `C:/xampp/htdocs/Pemograman-Web/`).
3. Buka *browser* pilihan Anda dan akses alamat **`http://localhost/Pemograman-Web/index.html`**!