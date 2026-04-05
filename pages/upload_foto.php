<?php
$pesan = '';
$direktori_tujuan = '../uploads/';

// Cek apakah folder 'uploads' sudah ada, jika belum buat foldernya
if (!is_dir($direktori_tujuan)) {
    mkdir($direktori_tujuan, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto'])) {
    $file = $_FILES['foto'];
    
    // Informasi dasar file
    $nama_file = $file['name'];
    $lokasi_sementara = $file['tmp_name'];
    $error = $file['error'];
    
    // Pastikan tidak ada error saat upload
    if ($error === 0) {
        
        // --- 1. MENGGUNAKAN OPERASI STRING (Sesuai Ketentuan Tugas) ---
        // Mencari posisi titik terakhir dalam nama file
        $posisi_titik = strrpos($nama_file, '.');
        
        // Mengambil string ekstensi di sebelah kanan titik (.png / .jpg)
        // substr berfungsi memotong string dimulai dari posisi(angka) yang ditentukan
        $ekstensi = substr($nama_file, $posisi_titik + 1);
        
        // strtolower digunakan untuk mengubah ekstensi berjaga-jaga jika huruf kapital (misal: .JPG jadi .jpg)
        $ekstensi_bersih = strtolower($ekstensi);
        
        // Membersihkan nama file jika ada karakter spasi menggunakan string str_replace
        // Misal nama file "foto saya.png" menjadi "foto_saya.png"
        $nama_dasar = substr($nama_file, 0, $posisi_titik);
        $nama_bersih = str_replace(" ", "_", $nama_dasar);
        // ---------------------------------------------------------------
        
        // 2. Validasi Ekstensi (.jpg atau .png)
        $ekstensi_diizinkan = array('jpg', 'png');
        
        if (in_array($ekstensi_bersih, $ekstensi_diizinkan)) {
            
            // 3. Nama file baru agar jika ada file dengan nama sama tidak tertimpa
            $nama_file_baru = "img_" . time() . "_" . $nama_bersih . "." . $ekstensi_bersih;
            $path_tujuan = $direktori_tujuan . $nama_file_baru;
            
            // 4. Memindahkan file dan menyimpannya di direktori tujuan (File Upload Data)
            if (move_uploaded_file($lokasi_sementara, $path_tujuan)) {
                $pesan = "<div class='alert success'>Berhasil! Foto tersimpan dengan nama: <strong>$nama_file_baru</strong></div>";
            } else {
                $pesan = "<div class='alert error'>Gagal menyimpan gambar di direktori tujuan.</div>";
            }
        } else {
            $pesan = "<div class='alert error'>Gagal upload: Hanya file <strong>.jpg</strong> atau <strong>.png</strong> yang diperbolehkan! Anda mengupload file (.$ekstensi_bersih)</div>";
        }
    } else {
        $pesan = "<div class='alert error'>Terjadi kesalahan yang tidak diketahui!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil & Upload Foto</title>
    <!-- MENGHUBUNGKAN DENGAN CSS UTAMA WEB -->
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .upload-card { background: white; border-radius: 12px; padding: 32px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); max-width: 600px; }
        .alert { padding: 15px; margin-bottom: 24px; border-radius: 6px; }
        .success { background-color: #dcfce7; color: #166534; }
        .error { background-color: #fee2e2; color: #991b1b; }
        .preview-box { margin-top: 24px; border: 2px dashed #cbd5e1; border-radius: 8px; padding: 24px; text-align: center; background: #f8fafc; }
        #imagePreview { max-width: 100%; max-height: 250px; display: none; border-radius: 6px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        #emptyPreviewText { color: #94a3b8; font-weight: 500; font-size: 0.95rem; }
        .btn-upload { margin-top: 24px; width: 100%; padding: 14px; background-color: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-upload:hover { background-color: #1d4ed8; }
        .file-input-wrapper { margin-top: 16px; margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar (Identik dengan menu navigasi web lainnya) -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 8H16L24 22V8H32V32H24L16 18V32H8V8Z"/>
                </svg>
            </div>
            
            <nav class="sidebar-menu">
                <a href="dashboard.php" class="sidebar-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Dashboard
                </a>
                <a href="katalog.php" class="sidebar-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    Katalog Toko
                </a>
                <!-- TANDA ACTIVE: Hal ini menandakan kita sedang ada di halaman ini -->
                <a href="upload_foto.php" class="sidebar-item active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profil Saya
                </a>
            </nav>
            
            <div class="sidebar-footer">
                <a href="#" class="sidebar-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    Pengaturan
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Topbar Custom -->
            <header class="topbar">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <button class="menu-toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                    </button>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: #0f172a; margin:0;">Profil & Akun</h2>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content" style="background:#f1f5f9; min-height: calc(100vh - 80px);">
                
                <div class="upload-card">
                    <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 8px;">Upload Foto Profil Baru</h3>
                    <p style="color: #64748b; margin-bottom: 32px; font-size: 0.95rem;">Perbarui foto profil Anda. Hanya file yang memiliki ekstensi .jpg atau .png yang dapat diterima.</p>

                    <!-- Menampilkan pesan jika dari bagian backend (PHP) ada respon -->
                    <?php if($pesan != '') echo $pesan; ?>

                    <!-- Form Upload (Penting: harus ada enctype="multipart/form-data") -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div style="font-weight: 600; color: #0f172a;">Pilih File Foto Anda:</div>
                        <div class="file-input-wrapper">
                            <input type="file" name="foto" id="foto" accept=".jpg, .png" required style="width: 100%; border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px; background: white;">
                        </div>

                        <!-- Kotak Preview Gambar -->
                        <div class="preview-box">
                            <div id="emptyPreviewText" style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <span>Area Preview Foto</span>
                            </div>
                            <!-- Tag img untuk menyuntikkan hasil preview otomatis -->
                            <img id="imagePreview" src="" alt="Preview Gambar">
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" class="btn-upload">Simpan Foto Profil</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <!-- Script Javascript Inti -->
    <script>
        // 1. Fitur Toggle Hamburger Menu Sidebar (Mobile Web)
        const btn = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        if(btn) {
            btn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }

        // 2. Fitur Live Preview File Upload (Front-End)
        const inputFile = document.getElementById('foto');
        const imagePreview = document.getElementById('imagePreview');
        const emptyPreviewText = document.getElementById('emptyPreviewText');

        inputFile.addEventListener('change', function() {
            const file = this.files[0]; 
            
            if (file) {
                // Konfirmasi client-side memastikan itu gambar
                if (file.type === "image/jpeg" || file.type === "image/png") {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                        emptyPreviewText.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                } else {
                    alert("Tolong pilih gambar ber-ekstensi .jpg atau .png saja ya!");
                    this.value = ""; // Menolak file salah
                    imagePreview.style.display = 'none';
                    emptyPreviewText.style.display = 'flex';
                }
            } else {
                imagePreview.style.display = 'none';
                emptyPreviewText.style.display = 'flex';
            }
        });
    </script>
</body>
</html>
