@extends('layouts.app')

@section('title', 'Pembayaran Berhasil')

@php $currentPage = 'katalog'; @endphp

@section('topbar')
    <div style="display: flex; align-items: center; gap: 16px;">
        <h2 style="font-weight: 700; color: var(--text-main); font-size: 1.5rem; margin: 0;">Pembayaran Berhasil</h2>
    </div>
    <div class="topbar-actions">
        <div class="profile-circle">
            @if(session('profile_pic'))
                <img src="{{ asset('uploads/' . session('profile_pic')) }}" alt="Profile">
            @endif
        </div>
    </div>
@endsection

@section('content')
    <div style="max-width: 500px; margin: 40px auto; text-align: center; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px; padding: 48px 32px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <!-- Mountain Checkmark Graphic -->
        <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; margin: 0 auto 28px; box-shadow: 0 8px 20px rgba(16,185,129,0.3); color: white;">
            <!-- Simple clean SVG checkmark -->
            <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>

        <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--text-main); margin-bottom: 12px; letter-spacing: -0.5px;">Pembayaran Berhasil</h1>
        
        <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 32px; padding: 0 16px;">
            Transaksi Anda telah berhasil dibayar menggunakan <strong>{{ strtoupper($transaction->payment_method) }}</strong>. Peralatan sewa Anda siap diambil di lokasi yang telah ditentukan.
        </p>

        <div style="background-color: var(--input-bg); border-radius: 8px; padding: 16px; margin-bottom: 32px; text-align: left;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.85rem;">
                <span style="color: var(--text-muted);">No. Invoice</span>
                <span style="font-weight: 700; color: var(--text-main);">{{ $transaction->invoice_number }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 0.85rem;">
                <span style="color: var(--text-muted);">Total Pembayaran</span>
                <span style="font-weight: 700; color: #1e3a8a;">Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
            </div>
        </div>

        <a href="{{ route('transaksi') }}" class="btn btn-dark" style="border-radius: 8px; font-weight: 700; width: 100%; padding: 14px;">
            Kembali ke Transaksi
        </a>
    </div>
@endsection
