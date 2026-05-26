@extends('layouts.app')

@section('title', 'Katalog')

@php $currentPage = 'katalog'; @endphp

@section('topbar')
    <button class="menu-toggle">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
    </button>
    <div class="search-bar">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Search">
    </div>
    <div class="topbar-actions">
        <a href="{{ route('cart.index') }}" class="action-icon" title="Keranjang" style="position: relative; display: inline-block;">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            @php
                $cartCount = \App\Models\Cart::where('user_id', session('user_id'))->sum('qty');
            @endphp
            @if($cartCount > 0)
                <span style="position: absolute; top: -8px; right: -8px; background-color: #ef4444; color: white; border-radius: 50%; font-size: 0.65rem; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                    {{ $cartCount }}
                </span>
            @endif
        </a>
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
    <!-- Promo Banner -->
    <div class="katalog-banner">
        <div class="banner-text">
            <h2>20% OFF TODAY AND GET<br>SPECIAL GIFT</h2>
            <p>Today only, enjoy stylish 20% off and receive exclusive gift!<br>Elevate your wardrobe now!</p>
        </div>
        <div class="banner-image">
        </div>
    </div>

    <!-- Categories -->
    <h3 class="section-title">Kategori</h3>
    <div class="categories-grid">
        <div class="category-item"><div class="category-icon">⛺</div><span>Tenda</span></div>
        <div class="category-item"><div class="category-icon">🎒</div><span>Tas</span></div>
        <div class="category-item"><div class="category-icon">🥾</div><span>Alat Pribadi</span></div>
        <div class="category-item"><div class="category-icon">🍳</div><span>Alat Masak</span></div>
        <div class="category-item"><div class="category-icon">📦</div><span>Penerangan</span></div>
        <div class="category-item"><div class="category-icon">•••</div><span>Lain-lain</span></div>
    </div>

    <!-- Trending -->
    <h3 class="section-title">Semua Produk</h3>
    <div class="products-grid">
        @foreach($products as $product)
        <div class="product-card" style="display: flex; flex-direction: column;">
            <div class="product-image" style="background-color: var(--input-bg); border-radius: 8px; height: 140px; display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">
                @if($product->category == 'Tenda') ⛺
                @elseif($product->category == 'Tas') 🎒
                @elseif($product->category == 'Alat Pribadi') 🥾
                @elseif($product->category == 'Alat Masak') 🍳
                @elseif($product->category == 'Penerangan') 💡
                @else 📦
                @endif
            </div>
            <div class="product-title" style="margin-top: 12px; font-weight: 700; font-size: 0.95rem; min-height: 40px; color: var(--text-main);">
                {{ $product->name }}
            </div>
            <div style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 8px;">
                Stok: {{ $product->stock }} | Kategori: {{ $product->category }}
            </div>
            <div class="product-footer" style="display: flex; flex-direction: column; align-items: flex-start; gap: 8px; margin-top: auto; width: 100%;">
                <div class="product-price" style="font-size: 1rem; color: var(--text-main); font-weight: 700;">
                    Rp {{ number_format($product->price, 0, ',', '.') }}<span style="font-size: 0.75rem; font-weight: 500; color: var(--text-muted);">/hari</span>
                </div>
                
                @if($product->stock > 0)
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="width: 100%; margin: 0;">
                        @csrf
                        <button type="submit" class="btn btn-dark" style="width: 100%; padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; box-shadow: none; font-weight: 600;">
                            + Keranjang
                        </button>
                    </form>
                @else
                    <button class="btn" disabled style="width: 100%; padding: 8px 16px; border-radius: 8px; font-size: 0.85rem; background: #cbd5e1; color: #94a3b8; cursor: not-allowed; box-shadow: none; font-weight: 600;">
                        Habis
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
@endsection
