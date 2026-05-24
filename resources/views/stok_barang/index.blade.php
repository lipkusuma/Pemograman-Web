@extends('layouts.app')

@section('title', 'Stok Barang - Admin')

@php $currentPage = 'stok_barang'; @endphp

@section('topbar')
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
            @if(session('profile_pic'))
                <img src="{{ asset('uploads/' . session('profile_pic')) }}" alt="Profile">
            @endif
        </div>
    </div>
@endsection

@section('content')
    <!-- Stat Cards -->
    <div class="stok-stats">
        <div class="stat-card">
            <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            </div>
            <div class="stat-value">{{ $totalBarang }}</div>
            <div class="stat-label">Total Jenis Barang</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            </div>
            <div class="stat-value">{{ $totalStok }}</div>
            <div class="stat-label">Total Unit Stok</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fef08a; color: #ca8a04;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="stat-value">{{ $hampirHabis }}</div>
            <div class="stat-label">Hampir Habis</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: #fecdd3; color: #e11d48;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            </div>
            <div class="stat-value">{{ $habis }}</div>
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
                @foreach($stokBarang as $barang)
                <tr>
                    <td style="font-weight:600;">{{ $barang['id'] }}</td>
                    <td>{{ $barang['nama'] }}</td>
                    <td style="color: var(--text-muted);">{{ $barang['kategori'] }}</td>
                    <td style="font-weight:600;">{{ $barang['stok'] }}</td>
                    <td>Rp {{ number_format($barang['harga'], 0, ',', '.') }}</td>
                    <td>
                        @php
                            $statusClass = match($barang['status']) {
                                'Tersedia'     => 'status-tersedia',
                                'Hampir Habis' => 'status-hampir-habis',
                                default        => 'status-habis',
                            };
                        @endphp
                        <span class="status-badge {{ $statusClass }}">{{ strtoupper($barang['status']) }}</span>
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
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
