<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 8H16L24 22V8H32V32H24L16 18V32H8V8Z"/>
                </svg>
            </div>
            
            <nav class="sidebar-menu">
                <a href="katalog.php" class="sidebar-item active">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Beranda
                </a>
                <a href="transaksi.php" class="sidebar-item">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    Transaksi
                </a>
                <a href="upload_foto.php" class="sidebar-item">
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
                        <!-- Default avatar placeholder -->
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                <!-- Promo Banner -->
                <div class="katalog-banner">
                    <div class="banner-text">
                        <h2>20% OFF TODAY AND GET<br>SPECIAL GIFT</h2>
                        <p>Today only, enjoy stylish 20% off and receive exclusive gift!<br>Elevate your wardrobe now!</p>
                    </div>
                    <div class="banner-image">
                        <!-- Simulating the jacket in the banner -->
                        <div style="width: 100px; height: 100px; background: rgba(0,0,0,0.5); border-radius: 8px;"></div>
                    </div>
                </div>

                <!-- Categories -->
                <h3 class="section-title">Kategori</h3>
                <div class="categories-grid">
                    <div class="category-item">
                        <div class="category-icon">â›º</div>
                        <span>Tenda</span>
                    </div>
                    <div class="category-item">
                        <div class="category-icon">ðŸŽ’</div>
                        <span>Tas</span>
                    </div>
                    <div class="category-item">
                        <div class="category-icon">ðŸ¥¾</div>
                        <span>Alat Pribadi</span>
                    </div>
                    <div class="category-item">
                        <div class="category-icon">ðŸ³</div>
                        <span>Alat Masak</span>
                    </div>
                    <div class="category-item">
                        <div class="category-icon">ðŸ”¦</div>
                        <span>Penerangan</span>
                    </div>
                    <div class="category-item">
                        <div class="category-icon">â€¢â€¢â€¢</div>
                        <span>Lain-lain</span>
                    </div>
                </div>

                <!-- Trending -->
                <h3 class="section-title">Trending</h3>
                <div class="products-grid">
                    <!-- Product Card -->
                    <div class="product-card">
                        <button class="favorite-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                        <div class="product-image">
                            <!-- Placeholder for jacket -->
                            <div style="width: 80px; height: 120px; background: #cbd5e1; border-radius: 8px;"></div>
                        </div>
                        <div class="product-footer">
                            <div>
                                <div class="product-title">VALENCIA<br>JACKET</div>
                            </div>
                            <div class="product-price">
                                <span class="price-currency">$</span>110<div style="font-size: 0.75rem; margin-top:2px;">.00</div>
                            </div>
                        </div>
                    </div>

                    <!-- Repeated Product Cards -->
                    <div class="product-card">
                        <button class="favorite-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                        <div class="product-image"><div style="width: 80px; height: 120px; background: #cbd5e1; border-radius: 8px;"></div></div>
                        <div class="product-footer">
                            <div><div class="product-title">VALENCIA<br>JACKET</div></div>
                            <div class="product-price"><span class="price-currency">$</span>110<div style="font-size: 0.75rem; margin-top:2px;">.00</div></div>
                        </div>
                    </div>
                    
                    <div class="product-card">
                        <button class="favorite-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                        <div class="product-image"><div style="width: 80px; height: 120px; background: #cbd5e1; border-radius: 8px;"></div></div>
                        <div class="product-footer">
                            <div><div class="product-title">VALENCIA<br>JACKET</div></div>
                            <div class="product-price"><span class="price-currency">$</span>110<div style="font-size: 0.75rem; margin-top:2px;">.00</div></div>
                        </div>
                    </div>
                    
                    <div class="product-card">
                        <button class="favorite-btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </button>
                        <div class="product-image"><div style="width: 80px; height: 120px; background: #cbd5e1; border-radius: 8px;"></div></div>
                        <div class="product-footer">
                            <div><div class="product-title">VALENCIA<br>JACKET</div></div>
                            <div class="product-price"><span class="price-currency">$</span>110<div style="font-size: 0.75rem; margin-top:2px;">.00</div></div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <!-- Simple script for mobile sidebar toggle -->
    <script>
        const btn = document.querySelector('.menu-toggle');
        const sidebar = document.querySelector('.sidebar');
        if(btn) {
            btn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
    </script>
</body>
</html>
