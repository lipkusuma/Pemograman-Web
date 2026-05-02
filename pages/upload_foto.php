<?php
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$current_page = 'upload_foto';

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
        $posisi_titik = strrpos($nama_file, '.');
        $ekstensi = substr($nama_file, $posisi_titik + 1);
        $ekstensi_bersih = strtolower($ekstensi);
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
                $_SESSION['profile_pic'] = $nama_file_baru;
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
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .alert { padding: 15px; margin-bottom: 24px; border-radius: 6px; }
        .success { background-color: #dcfce7; color: #166534; }
        .error { background-color: #fee2e2; color: #991b1b; }
        #imagePreview { max-width: 100%; max-height: 250px; display: none; border-radius: 6px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        #emptyPreviewText { color: #94a3b8; font-weight: 500; font-size: 0.95rem; }
        .btn-upload { margin-top: 24px; width: 100%; padding: 14px; background-color: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
        .btn-upload:hover { background-color: #1d4ed8; }
        .file-input-wrapper { margin-top: 16px; margin-bottom: 8px; }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?php include('../includes/sidebar.php'); ?>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <button class="menu-toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                    </button>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin:0;">Profil & Akun</h2>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content" style="min-height: calc(100vh - 80px);">
                
                <div class="upload-card">
                    <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 8px; color: var(--text-main);">Upload Foto Profil Baru</h3>
                    <p style="color: var(--text-muted); margin-bottom: 32px; font-size: 0.95rem;">Perbarui foto profil Anda. Hanya file yang memiliki ekstensi .jpg atau .png yang dapat diterima.</p>

                    <?php if($pesan != '') echo $pesan; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div style="font-weight: 600; color: var(--text-main);">Pilih File Foto Anda:</div>
                        <div class="file-input-wrapper">
                            <input type="file" name="foto" id="foto" accept=".jpg, .png" required style="width: 100%; border: 1px solid var(--border-color); padding: 12px; border-radius: 8px; background: var(--card-bg); color: var(--text-main);">
                        </div>

                        <div class="preview-box">
                            <div id="emptyPreviewText" style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                <span>Area Preview Foto</span>
                            </div>
                            <img id="imagePreview" src="" alt="Preview Gambar">
                        </div>

                        <button type="submit" class="btn-upload">Simpan Foto Profil</button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/app.js"></script>
    <script>
        // Fitur Live Preview File Upload (Front-End)
        const inputFile = document.getElementById('foto');
        const imagePreview = document.getElementById('imagePreview');
        const emptyPreviewText = document.getElementById('emptyPreviewText');

        inputFile.addEventListener('change', function() {
            const file = this.files[0]; 
            if (file) {
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
                    this.value = "";
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
