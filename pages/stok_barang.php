<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
$current_page = 'stok_barang';

// Data dummy stok barang
$stok_barang = [
    ['id' => 'BRG001', 'nama' => 'Tenda Ultralight 2P', 'kategori' => 'Tenda', 'stok' => 15, 'harga' => 850000, 'status' => 'Tersedia'],
    ['id' => 'BRG002', 'nama' => 'Carrier 60L Pro', 'kategori' => 'Tas', 'stok' => 8, 'harga' => 1200000, 'status' => 'Tersedia'],
    ['id' => 'BRG003', 'nama' => 'Sleeping Bag -5°C', 'kategori' => 'Alat Pribadi', 'stok' => 3, 'harga' => 450000, 'status' => 'Hampir Habis'],
    ['id' => 'BRG004', 'nama' => 'Kompor Portable Gas', 'kategori' => 'Alat Masak', 'stok' => 0, 'harga' => 175000, 'status' => 'Habis'],
    ['id' => 'BRG005', 'nama' => 'Headlamp LED 1000lm', 'kategori' => 'Penerangan', 'stok' => 22, 'harga' => 285000, 'status' => 'Tersedia'],
    ['id' => 'BRG006', 'nama' => 'Matras Foam Premium', 'kategori' => 'Alat Pribadi', 'stok' => 5, 'harga' => 320000, 'status' => 'Tersedia'],
    ['id' => 'BRG007', 'nama' => 'Nesting Set Aluminium', 'kategori' => 'Alat Masak', 'stok' => 2, 'harga' => 195000, 'status' => 'Hampir Habis'],
    ['id' => 'BRG008', 'nama' => 'Trekking Pole Carbon', 'kategori' => 'Alat Pribadi', 'stok' => 12, 'harga' => 550000, 'status' => 'Tersedia'],
];

$total_barang = count($stok_barang);
$total_stok = array_sum(array_column($stok_barang, 'stok'));
$habis = count(array_filter($stok_barang, fn($b) => $b['status'] === 'Habis'));
$hampir_habis = count(array_filter($stok_barang, fn($b) => $b['status'] === 'Hampir Habis'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Barang - Admin</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="app-container">
        <?php include('../includes/sidebar.php'); ?>

        <main class="main-content">
            <header class="topbar">
                <div style="display: flex; align-items: center; gap: 16px;">
                    <button class="menu-toggle">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                    </button>
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin:0;">Manajemen Stok Barang</h2>
                </div>
                <div class="topbar-actions">
                    <div class="action-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </div>
                    <div class="profile-circle">
                        <?php if(isset($_SESSION['profile_pic']) && !empty($_SESSION['profile_pic'])): ?>
                            <img src="../uploads/<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile">
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <div class="page-content">
                <!-- Stat Cards -->
                <div class="stok-stats">
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                        </div>
                        <div class="stat-value"><?php echo $total_barang; ?></div>
                        <div class="stat-label">Total Jenis Barang</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                        </div>
                        <div class="stat-value"><?php echo $total_stok; ?></div>
                        <div class="stat-label">Total Unit Stok</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #fef08a; color: #ca8a04;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        </div>
                        <div class="stat-value"><?php echo $hampir_habis; ?></div>
                        <div class="stat-label">Hampir Habis</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon" style="background: #fecdd3; color: #e11d48;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </div>
                        <div class="stat-value"><?php echo $habis; ?></div>
                        <div class="stat-label">Stok Habis</div>
                    </div>
                </div>

                <!-- Table -->
                <div class="dash-card" style="overflow-x: auto;">
                    <div class="stok-header">
                        <h3 class="dash-card-title" style="margin-bottom:0;">Daftar Barang</h3>
                        <button class="btn-tambah">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Tambah Barang
                        </button>
                    </div>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($stok_barang as $barang): ?>
                            <tr>
                                <td style="font-weight:600;"><?php echo $barang['id']; ?></td>
                                <td><?php echo htmlspecialchars($barang['nama']); ?></td>
                                <td style="color: var(--text-muted);"><?php echo $barang['kategori']; ?></td>
                                <td style="font-weight:600;"><?php echo $barang['stok']; ?></td>
                                <td>Rp <?php echo number_format($barang['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    $statusClass = '';
                                    if($barang['status'] === 'Tersedia') $statusClass = 'status-tersedia';
                                    elseif($barang['status'] === 'Hampir Habis') $statusClass = 'status-hampir-habis';
                                    else $statusClass = 'status-habis';
                                    ?>
                                    <span class="status-badge <?php echo $statusClass; ?>"><?php echo strtoupper($barang['status']); ?></span>
                                </td>
                                <td>
                                    <div style="display:flex; gap:8px;">
                                        <button style="background:none; color:#2563eb; cursor:pointer;" title="Edit">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </button>
                                        <button style="background:none; color:#ef4444; cursor:pointer;" title="Hapus">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/app.js"></script>
</body>
</html>
