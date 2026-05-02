<?php 
session_start();
if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
$current_page = 'laporan';

// Data dummy laporan transaksi
$laporan = [
    ['id' => 'TRX001', 'tanggal' => '2025-09-10', 'pelanggan' => 'Budi Santoso', 'produk' => 'Tenda Ultralight 2P', 'qty' => 1, 'total' => 850000, 'status' => 'Selesai'],
    ['id' => 'TRX002', 'tanggal' => '2025-09-10', 'pelanggan' => 'Ani Wulandari', 'produk' => 'Carrier 60L Pro', 'qty' => 1, 'total' => 1200000, 'status' => 'Selesai'],
    ['id' => 'TRX003', 'tanggal' => '2025-09-09', 'pelanggan' => 'Riko Pratama', 'produk' => 'Headlamp LED 1000lm', 'qty' => 2, 'total' => 570000, 'status' => 'Selesai'],
    ['id' => 'TRX004', 'tanggal' => '2025-09-09', 'pelanggan' => 'Dewi Lestari', 'produk' => 'Sleeping Bag -5°C', 'qty' => 1, 'total' => 450000, 'status' => 'Pending'],
    ['id' => 'TRX005', 'tanggal' => '2025-09-08', 'pelanggan' => 'Andi Firmansyah', 'produk' => 'Trekking Pole Carbon', 'qty' => 1, 'total' => 550000, 'status' => 'Selesai'],
    ['id' => 'TRX006', 'tanggal' => '2025-09-08', 'pelanggan' => 'Sari Indah', 'produk' => 'Matras Foam Premium', 'qty' => 2, 'total' => 640000, 'status' => 'Dibatalkan'],
    ['id' => 'TRX007', 'tanggal' => '2025-09-07', 'pelanggan' => 'Joko Widodo', 'produk' => 'Nesting Set Aluminium', 'qty' => 3, 'total' => 585000, 'status' => 'Selesai'],
];

$total_transaksi = count($laporan);
$total_pendapatan = array_sum(array_column(array_filter($laporan, fn($l) => $l['status'] === 'Selesai'), 'total'));
$total_produk_terjual = array_sum(array_column(array_filter($laporan, fn($l) => $l['status'] === 'Selesai'), 'qty'));
$total_pelanggan = count(array_unique(array_column($laporan, 'pelanggan')));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Admin</title>
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
                    <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin:0;">Laporan Transaksi</h2>
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
                <!-- Filter Buttons -->
                <div class="laporan-filters">
                    <button class="filter-btn active">Semua</button>
                    <button class="filter-btn">Hari Ini</button>
                    <button class="filter-btn">Minggu Ini</button>
                    <button class="filter-btn">Bulan Ini</button>
                </div>

                <!-- Stat Cards -->
                <div class="laporan-grid">
                    <div class="laporan-stat-card">
                        <div class="stat-title">Total Transaksi</div>
                        <div class="stat-value"><?php echo $total_transaksi; ?></div>
                        <div class="stat-change positive">↑ 12% dari bulan lalu</div>
                    </div>
                    <div class="laporan-stat-card">
                        <div class="stat-title">Total Pendapatan</div>
                        <div class="stat-value">Rp <?php echo number_format($total_pendapatan / 1000000, 1, ',', '.'); ?>jt</div>
                        <div class="stat-change positive">↑ 8% dari bulan lalu</div>
                    </div>
                    <div class="laporan-stat-card">
                        <div class="stat-title">Produk Terjual</div>
                        <div class="stat-value"><?php echo $total_produk_terjual; ?></div>
                        <div class="stat-change negative">↓ 3% dari bulan lalu</div>
                    </div>
                    <div class="laporan-stat-card">
                        <div class="stat-title">Pelanggan Unik</div>
                        <div class="stat-value"><?php echo $total_pelanggan; ?></div>
                        <div class="stat-change positive">↑ 5% dari bulan lalu</div>
                    </div>
                </div>

                <!-- Grafik Sederhana -->
                <div class="dash-card" style="margin-bottom: 24px;">
                    <h3 class="dash-card-title">Grafik Pendapatan Mingguan</h3>
                    <div style="height: 160px; position: relative; overflow: hidden;">
                        <svg preserveAspectRatio="none" viewBox="0 0 100 50" style="width: 100%; height: 100%;">
                            <defs>
                                <linearGradient id="gradLaporan" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#4ade80;stop-opacity:0.8" />
                                    <stop offset="100%" style="stop-color:#4ade80;stop-opacity:0.05" />
                                </linearGradient>
                            </defs>
                            <path d="M0,50 L0,35 C15,25 25,40 35,20 C45,5 55,30 65,15 C75,5 85,25 100,10 L100,50 Z" fill="url(#gradLaporan)" />
                            <path d="M0,35 C15,25 25,40 35,20 C45,5 55,30 65,15 C75,5 85,25 100,10" fill="none" stroke="#16a34a" stroke-width="1.5"/>
                        </svg>
                    </div>
                </div>

                <!-- Tabel Laporan -->
                <div class="dash-card" style="overflow-x: auto;">
                    <h3 class="dash-card-title">Detail Transaksi</h3>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($laporan as $trx): ?>
                            <tr>
                                <td style="font-weight:600;"><?php echo $trx['id']; ?></td>
                                <td style="color: var(--text-muted);"><?php echo date('d M Y', strtotime($trx['tanggal'])); ?></td>
                                <td><?php echo htmlspecialchars($trx['pelanggan']); ?></td>
                                <td><?php echo htmlspecialchars($trx['produk']); ?></td>
                                <td style="font-weight:600;"><?php echo $trx['qty']; ?></td>
                                <td>Rp <?php echo number_format($trx['total'], 0, ',', '.'); ?></td>
                                <td>
                                    <?php 
                                    $statusClass = '';
                                    if($trx['status'] === 'Selesai') $statusClass = 'status-completed';
                                    elseif($trx['status'] === 'Pending') $statusClass = 'status-pending';
                                    else $statusClass = 'status-in-progress';
                                    ?>
                                    <span class="status-badge <?php echo $statusClass; ?>"><?php echo strtoupper($trx['status']); ?></span>
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
    <script>
        // Filter button toggle
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>
</body>
</html>
