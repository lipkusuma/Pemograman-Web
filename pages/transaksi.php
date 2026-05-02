<?php 
session_start();
if(!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$current_page = 'transaksi';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?php include('../includes/sidebar.php'); ?>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <button class="menu-toggle">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
                </button>
                <div class="search-bar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" placeholder="Search">
                </div>
                <div class="topbar-actions">
                    <div class="action-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
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

            <!-- Page Content -->
            <div class="page-content">
                <!-- Navigation Tabs -->
                <div class="tabs">
                    <div class="tab active">Belum Bayar</div>
                    <div class="tab">Selesai</div>
                    <div class="tab">Pengembalian Dana</div>
                    <div class="tab">Dibatalkan</div>
                </div>

                <!-- Transaction Card -->
                <div class="trx-card">
                    <div class="trx-left">
                        <div class="trx-img"></div>
                        <div class="trx-details">
                            <h3 style="font-weight: 700; color: var(--text-main);">Jaket</h3>
                            <p style="font-size:0.9rem; color:var(--text-muted);">$100</p>
                            <div class="trx-date">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                10 Sep 2025
                            </div>
                            <div class="trx-warning">Transaksi otomatis batal dalam 01:00:00</div>
                        </div>
                    </div>
                    <div class="trx-right">
                        <div style="font-weight: 600; color: var(--text-main);">Belum Bayar</div>
                        <div style="font-size: 0.85rem; color:var(--text-muted); margin-top:24px;">$100</div>
                        <div style="font-weight: 700; color: var(--text-main);">Total 1 Produk: $100</div>
                        <button class="btn btn-white" style="border: 1px solid var(--border-color); margin-top:8px;">Bayar Sekarang</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../assets/js/app.js"></script>
</body>
</html>
