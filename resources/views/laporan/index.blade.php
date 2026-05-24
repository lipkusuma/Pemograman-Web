@extends('layouts.app')

@section('title', 'Laporan - Admin')

@php $currentPage = 'laporan'; @endphp

@section('topbar')
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
            @if(session('profile_pic'))
                <img src="{{ asset('uploads/' . session('profile_pic')) }}" alt="Profile">
            @endif
        </div>
    </div>
@endsection

@section('content')
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
            <div class="stat-value">{{ $totalTransaksi }}</div>
            <div class="stat-change positive">↑ 12% dari bulan lalu</div>
        </div>
        <div class="laporan-stat-card">
            <div class="stat-title">Total Pendapatan</div>
            <div class="stat-value">Rp {{ number_format($totalPendapatan / 1000000, 1, ',', '.') }}jt</div>
            <div class="stat-change positive">↑ 8% dari bulan lalu</div>
        </div>
        <div class="laporan-stat-card">
            <div class="stat-title">Produk Terjual</div>
            <div class="stat-value">{{ $totalProdukTerjual }}</div>
            <div class="stat-change negative">↓ 3% dari bulan lalu</div>
        </div>
        <div class="laporan-stat-card">
            <div class="stat-title">Pelanggan Unik</div>
            <div class="stat-value">{{ $totalPelanggan }}</div>
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
                @foreach($laporan as $trx)
                <tr>
                    <td style="font-weight:600;">{{ $trx['id'] }}</td>
                    <td style="color: var(--text-muted);">{{ \Carbon\Carbon::parse($trx['tanggal'])->translatedFormat('d M Y') }}</td>
                    <td>{{ $trx['pelanggan'] }}</td>
                    <td>{{ $trx['produk'] }}</td>
                    <td style="font-weight:600;">{{ $trx['qty'] }}</td>
                    <td>Rp {{ number_format($trx['total'], 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = match($trx['status']) {
                                'Selesai'    => 'status-completed',
                                'Pending'    => 'status-pending',
                                default      => 'status-in-progress',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ strtoupper($trx['status']) }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
<script>
    // Filter button toggle
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
@endpush
