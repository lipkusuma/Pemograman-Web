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
        <a href="{{ route('laporan', ['filter' => 'semua']) }}" class="filter-btn {{ $filter === 'semua' ? 'active' : '' }}">Semua</a>
        <a href="{{ route('laporan', ['filter' => 'hari']) }}" class="filter-btn {{ $filter === 'hari' ? 'active' : '' }}">Hari Ini</a>
        <a href="{{ route('laporan', ['filter' => 'minggu']) }}" class="filter-btn {{ $filter === 'minggu' ? 'active' : '' }}">Minggu Ini</a>
        <a href="{{ route('laporan', ['filter' => 'bulan']) }}" class="filter-btn {{ $filter === 'bulan' ? 'active' : '' }}">Bulan Ini</a>
    </div>

    <!-- Stat Cards -->
    <div class="laporan-grid">
        <div class="laporan-stat-card">
            <div class="stat-title">Total Transaksi</div>
            <div class="stat-value">{{ $totalTransaksi }}</div>
            @if($filter !== 'semua')
                <div class="stat-change {{ $changeTransaksi['direction'] }}">
                    {{ $changeTransaksi['direction'] === 'positive' ? '↑' : ($changeTransaksi['direction'] === 'negative' ? '↓' : '—') }}
                    {{ $changeTransaksi['value'] }}% dari periode lalu
                </div>
            @else
                <div class="stat-change neutral">Semua waktu</div>
            @endif
        </div>
        <div class="laporan-stat-card">
            <div class="stat-title">Total Pendapatan</div>
            <div class="stat-value">
                @if($totalPendapatan >= 1000000)
                    Rp {{ number_format($totalPendapatan / 1000000, 1, ',', '.') }}jt
                @else
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                @endif
            </div>
            @if($filter !== 'semua')
                <div class="stat-change {{ $changePendapatan['direction'] }}">
                    {{ $changePendapatan['direction'] === 'positive' ? '↑' : ($changePendapatan['direction'] === 'negative' ? '↓' : '—') }}
                    {{ $changePendapatan['value'] }}% dari periode lalu
                </div>
            @else
                <div class="stat-change neutral">Semua waktu</div>
            @endif
        </div>
        <div class="laporan-stat-card">
            <div class="stat-title">Produk Terjual</div>
            <div class="stat-value">{{ $totalProdukTerjual }}</div>
            @if($filter !== 'semua')
                <div class="stat-change {{ $changeProdukTerjual['direction'] }}">
                    {{ $changeProdukTerjual['direction'] === 'positive' ? '↑' : ($changeProdukTerjual['direction'] === 'negative' ? '↓' : '—') }}
                    {{ $changeProdukTerjual['value'] }}% dari periode lalu
                </div>
            @else
                <div class="stat-change neutral">Semua waktu</div>
            @endif
        </div>
        <div class="laporan-stat-card">
            <div class="stat-title">Total Pelanggan</div>
            <div class="stat-value">{{ $totalPelanggan }}</div>
            @if($filter !== 'semua')
                <div class="stat-change {{ $changePelanggan['direction'] }}">
                    {{ $changePelanggan['direction'] === 'positive' ? '↑' : ($changePelanggan['direction'] === 'negative' ? '↓' : '—') }}
                    {{ $changePelanggan['value'] }}% dari periode lalu
                </div>
            @else
                <div class="stat-change neutral">Semua waktu</div>
            @endif
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="dash-card" style="overflow-x: auto;">
        <h3 class="dash-card-title">Detail Transaksi</h3>

        @if(count($laporan) === 0)
            <div style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 12px; opacity: 0.5;">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="1"/>
                    <path d="M9 12h6M9 16h6"/>
                </svg>
                <p style="font-weight: 600; font-size: 1rem; margin: 0 0 4px;">Belum ada transaksi</p>
                <p style="font-size: 0.875rem; margin: 0;">Data transaksi akan muncul setelah pelanggan melakukan pemesanan.</p>
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Invoice</th>
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
                                    'Lunas'                 => 'status-completed',
                                    'Menunggu Pembayaran'   => 'status-pending',
                                    'Dibatalkan'            => 'status-in-progress',
                                    default                 => 'status-pending',
                                };
                                $statusLabel = match($trx['status']) {
                                    'Lunas'                 => 'LUNAS',
                                    'Menunggu Pembayaran'   => 'MENUNGGU',
                                    'Dibatalkan'            => 'BATAL',
                                    default                 => strtoupper($trx['status']),
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
