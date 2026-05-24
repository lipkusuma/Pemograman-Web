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
        <div class="action-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        </div>
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
            <div style="width: 100px; height: 100px; background: rgba(0,0,0,0.5); border-radius: 8px;"></div>
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
    <h3 class="section-title">Trending</h3>
    <div class="products-grid">
        @for($i = 0; $i < 4; $i++)
        <div class="product-card">
            <button class="favorite-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </button>
            <div class="product-image">
                <div style="width: 80px; height: 120px; background: #cbd5e1; border-radius: 8px;"></div>
            </div>
            <div class="product-footer">
                <div><div class="product-title">VALENCIA<br>JACKET</div></div>
                <div class="product-price"><span class="price-currency">$</span>110<div style="font-size: 0.75rem; margin-top:2px;">.00</div></div>
            </div>
        </div>
        @endfor
    </div>
@endsection
