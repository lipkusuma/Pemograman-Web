@extends('layouts.app')

@section('title', 'Admin Dashboard')

@php $currentPage = 'dashboard'; @endphp

@section('topbar')
    <div style="display: flex; align-items: center; gap: 16px;">
        <button class="menu-toggle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin:0;">Dashboard</h2>
    </div>

    <div class="topbar-actions">
        <div class="action-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        </div>
        <div class="profile-circle">
            @if(session('profile_pic'))
                <img src="{{ asset('uploads/' . session('profile_pic')) }}" alt="Profile">
            @endif
        </div>
    </div>
@endsection

@section('content')
    <!-- KPI Cards Row -->
    <div class="laporan-grid">
        <!-- Card 1: Total Revenue -->
        <div class="laporan-stat-card">
            <div class="stat-title">Total Pendapatan</div>
            <div class="stat-value">Rp{{ number_format($totalRevenueThisMonth, 0, ',', '.') }}</div>
            <div class="stat-change {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                {{ $revenueGrowth >= 0 ? '↑' : '↓' }} {{ abs(round($revenueGrowth, 1)) }}% dari bulan lalu
            </div>
        </div>

        <!-- Card 2: Total Transactions -->
        <div class="laporan-stat-card">
            <div class="stat-title">Total Transaksi</div>
            <div class="stat-value">{{ $totalTransactionsThisMonth }}</div>
            <div class="stat-change" style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">Bulan ini</div>
        </div>

        <!-- Card 3: Items Sold -->
        <div class="laporan-stat-card">
            <div class="stat-title">Produk Terjual</div>
            <div class="stat-value">{{ $totalItemsSoldThisMonth }}</div>
            <div class="stat-change" style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">Bulan ini</div>
        </div>
    </div>

    <div class="laporan-grid">
        <!-- Card 4: Active Customers -->
        <div class="laporan-stat-card">
            <div class="stat-title">Pelanggan Aktif</div>
            <div class="stat-value">{{ $activeCustomers }}</div>
            <div class="stat-change" style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">Bulan ini</div>
        </div>

        <!-- Card 5: Pending Transactions -->
        <div class="laporan-stat-card">
            <div class="stat-title">Transaksi Pending</div>
            <div class="stat-value" style="color: #facc15;">{{ $pendingTransactions }}</div>
            <div class="stat-change" style="color: var(--text-muted); font-size: 0.8rem; font-weight: 600;">Perlu tindakan</div>
        </div>

        <!-- Card 6: Low Stock -->
        <div class="laporan-stat-card">
            <div class="stat-title">Stok Rendah</div>
            <div class="stat-value" style="color: #fb7185;">{{ $lowStockCount }}</div>
            <div class="stat-change">
                <a href="{{ route('stok_barang') }}" style="color: #38bdf8; text-decoration: none;">Lihat stok →</a>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <!-- Sales Last 7 Days Chart -->
        <div class="dash-card">
            <h3 class="dash-card-title">Penjualan 7 Hari Terakhir</h3>
            <div style="height: 250px; position: relative;">
                <canvas id="chartSales7Days"></canvas>
            </div>
        </div>

        <!-- Top Products Chart -->
        <div class="dash-card">
            <h3 class="dash-card-title">5 Produk Terlaku</h3>
            <div style="height: 250px; position: relative;">
                <canvas id="chartTopProducts"></canvas>
            </div>
        </div>

        <!-- Payment Method Chart -->
        <div class="dash-card">
            <h3 class="dash-card-title">Metode Pembayaran</h3>
            <div style="height: 250px; position: relative;">
                <canvas id="chartPaymentMethod"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Transactions & Low Stock Products -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
        <!-- Recent Transactions -->
        <div class="dash-card" style="overflow-x: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 class="dash-card-title" style="margin: 0;">Transaksi Terbaru</h3>
                <a href="{{ route('laporan') }}" style="color: #38bdf8; text-decoration: none; font-size: 0.85rem;">Lihat semua →</a>
            </div>
            <table class="data-table" style="font-size: 0.9rem;">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $transaction)
                    <tr>
                        <td style="font-weight: 600;">{{ $transaction->invoice_number }}</td>
                        <td>{{ $transaction->user->name ?? '-' }}</td>
                        <td>Rp{{ number_format($transaction->total, 0, ',', '.') }}</td>
                        <td>
                            <span class="status-badge status-{{ strtolower($transaction->status) }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted);">Tidak ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Low Stock Products -->
        <div class="dash-card" style="overflow-x: auto;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 class="dash-card-title" style="margin: 0;">Stok Barang Rendah</h3>
                <a href="{{ route('stok_barang') }}" style="color: #38bdf8; text-decoration: none; font-size: 0.85rem;">Atur stok →</a>
            </div>
            <table class="data-table" style="font-size: 0.9rem;">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                    <tr>
                        <td style="font-weight: 600;">{{ $product->name }}</td>
                        <td>{{ $product->category }}</td>
                        <td>
                            <span style="color: {{ $product->stock <= 5 ? '#fb7185' : '#facc15' }}; font-weight: 600;">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>Rp{{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; color: var(--text-muted);">Semua stok baik</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        function getComputedColor(varName) {
            return getComputedStyle(document.body).getPropertyValue(varName).trim();
        }

        // Initialize charts
        const ctx1 = document.getElementById('chartSales7Days').getContext('2d');
        const chartSales = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: {!! json_encode(collect($last7Days)->pluck('date')) !!},
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: {!! json_encode(collect($last7Days)->pluck('total')) !!},
                    borderColor: '#38bdf8',
                    backgroundColor: 'rgba(56, 189, 248, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#38bdf8',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: { color: getComputedColor('--text-main') }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { color: getComputedColor('--text-muted') },
                        grid: { color: getComputedColor('--input-bg') }
                    },
                    x: {
                        ticks: { color: getComputedColor('--text-muted') },
                        grid: { color: getComputedColor('--input-bg') }
                    }
                }
            }
        });

        const ctx2 = document.getElementById('chartTopProducts').getContext('2d');
        const chartProducts = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topProducts->pluck('product.name')) !!},
                datasets: [{
                    label: 'Qty Terjual',
                    data: {!! json_encode($topProducts->pluck('total_qty')) !!},
                    backgroundColor: ['#38bdf8', '#06b6d4', '#14b8a6', '#10b981', '#f59e0b'],
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { color: getComputedColor('--text-muted') },
                        grid: { color: getComputedColor('--input-bg') }
                    },
                    y: {
                        ticks: { color: getComputedColor('--text-muted') },
                        grid: { color: getComputedColor('--input-bg') }
                    }
                }
            }
        });

        const ctx3 = document.getElementById('chartPaymentMethod').getContext('2d');
        const chartPayments = new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($paymentMethods->pluck('payment_method')) !!},
                datasets: [{
                    data: {!! json_encode($paymentMethods->pluck('count')) !!},
                    backgroundColor: ['#38bdf8', '#06b6d4', '#14b8a6', '#10b981', '#f59e0b', '#f97316'],
                    borderColor: getComputedColor('--bg-secondary'),
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: getComputedColor('--text-main') }
                    }
                }
            }
        });

        // Update charts on theme change
        function updateChartsTheme() {
            const textColor = getComputedColor('--text-main');
            const mutedColor = getComputedColor('--text-muted');
            const gridColor = getComputedColor('--input-bg');
            const bgColor = getComputedColor('--bg-secondary');

            // Sales Chart
            chartSales.options.plugins.legend.labels.color = textColor;
            chartSales.options.scales.y.ticks.color = mutedColor;
            chartSales.options.scales.y.grid.color = gridColor;
            chartSales.options.scales.x.ticks.color = mutedColor;
            chartSales.options.scales.x.grid.color = gridColor;
            chartSales.update();

            // Products Chart
            chartProducts.options.scales.x.ticks.color = mutedColor;
            chartProducts.options.scales.x.grid.color = gridColor;
            chartProducts.options.scales.y.ticks.color = mutedColor;
            chartProducts.options.scales.y.grid.color = gridColor;
            chartProducts.update();

            // Payments Chart
            chartPayments.options.plugins.legend.labels.color = textColor;
            chartPayments.data.datasets[0].borderColor = bgColor;
            chartPayments.update();
        }

        // Watch for dark mode changes
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    updateChartsTheme();
                }
            });
        });
        
        observer.observe(document.body, { attributes: true });
    </script>

    <style>
        .status-pending, .status-unpaid {
            background-color: rgba(250, 204, 21, 0.2);
            color: #facc15;
        }
        .status-completed, .status-paid {
            background-color: rgba(74, 222, 128, 0.2);
            color: #4ade80;
        }
        .status-in-progress, .status-processing {
            background-color: rgba(251, 113, 133, 0.2);
            color: #fb7185;
        }
    </style>
@endsection
