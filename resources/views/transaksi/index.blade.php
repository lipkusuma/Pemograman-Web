@extends('layouts.app')

@section('title', 'Transaksi Saya')

@php $currentPage = 'transaksi'; @endphp

@section('topbar')
    <button class="menu-toggle">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
    </button>
    <div class="search-bar">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" placeholder="Cari Transaksi..." id="search-trx-input" onkeyup="filterTransactions()">
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
        <div class="profile-circle">
            @if(session('profile_pic'))
                <img src="{{ asset('uploads/' . session('profile_pic')) }}" alt="Profile">
            @endif
        </div>
    </div>
@endsection

@section('content')
    <!-- Navigation Tabs -->
    <div class="tabs" style="margin-bottom: 24px;">
        <div class="tab active" data-filter="Menunggu Pembayaran">Belum Bayar</div>
        <div class="tab" data-filter="Lunas">Selesai</div>
        <div class="tab" data-filter="Refund">Pengembalian Dana</div>
        <div class="tab" data-filter="Batal">Dibatalkan</div>
    </div>

    <div id="transactions-container" style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 40px;">
        @forelse($transactions as $trx)
            @php
                $firstItem = $trx->items->first();
                $totalItemsCount = $trx->items->sum('qty');
                $totalUniqueItems = $trx->items->count();
            @endphp
            <div class="trx-card" data-status="{{ $trx->status }}" style="display: flex; justify-content: space-between; align-items: center; transition: all 0.3s; padding: 24px; border: 1px solid var(--border-color);">
                <div class="trx-left" style="display: flex; gap: 24px; align-items: center;">
                    <!-- Category Icon for first item -->
                    <div class="trx-img" style="display: flex; align-items: center; justify-content: center; background-color: var(--input-bg); border-radius: 12px; width: 90px; height: 90px; flex-shrink: 0; overflow: hidden;">
                        @if($firstItem && $firstItem->product && $firstItem->product->image)
                            <img src="{{ asset('uploads/products/' . $firstItem->product->image) }}" alt="{{ $firstItem->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted);">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        @endif
                    </div>
                    <div class="trx-details" style="display: flex; flex-direction: column; gap: 4px;">
                        <h3 style="font-weight: 700; color: var(--text-main); font-size: 1.1rem; margin: 0;">
                            @if($firstItem && $firstItem->product)
                                {{ $firstItem->product->name }}
                            @else
                                Produk tidak diketahui
                            @endif
                            @if($totalUniqueItems > 1)
                                <span style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted); margin-left: 4px;">
                                    + {{ $totalUniqueItems - 1 }} barang lainnya
                                </span>
                            @endif
                        </h3>
                        
                        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500; margin-top: 2px;">
                            Invoice: <strong style="color: var(--text-main);">{{ $trx->invoice_number }}</strong>
                        </div>

                        <div class="trx-date" style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 4px; vertical-align: middle;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d M Y, H:i') }} WIB
                        </div>

                        @if($trx->status === 'Menunggu Pembayaran')
                            <div class="trx-warning" style="margin-top: 4px; font-weight: 500; font-size: 0.8rem; display: flex; align-items: center; gap: 4px;">
                                ⚠️ Selesaikan pembayaran dalam 24 jam.
                            </div>
                        @else
                            <div style="margin-top: 4px; font-size: 0.8rem; color: #166534; font-weight: 600;">
                                Metode: {{ strtoupper($trx->payment_method) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="trx-right" style="text-align: right; display: flex; flex-direction: column; gap: 8px; justify-content: center; align-items: flex-end;">
                    @if($trx->status === 'Menunggu Pembayaran')
                        <span class="status-badge status-pending" style="width: max-content;">Belum Bayar</span>
                    @elseif($trx->status === 'Lunas')
                        <span class="status-badge status-completed" style="width: max-content;">Lunas</span>
                    @else
                        <span class="status-badge status-in-progress" style="width: max-content;">{{ $trx->status }}</span>
                    @endif

                    <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 8px;">
                        Total {{ $totalItemsCount }} Produk
                    </div>
                    
                    <div style="font-weight: 800; color: #1e3a8a; font-size: 1.25rem;">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </div>

                    @if($trx->status === 'Menunggu Pembayaran')
                        <a href="{{ route('cart.payment', $trx->id) }}" class="btn btn-dark" style="padding: 8px 18px; border-radius: 6px; font-size: 0.85rem; font-weight: 700; box-shadow: none; margin-top: 4px;">
                            Bayar Sekarang
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div id="no-trx-placeholder" style="text-align: center; padding: 64px 24px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px;">
                <div style="font-size: 4rem; margin-bottom: 16px;">📂</div>
                <h3 style="font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Tidak Ada Transaksi</h3>
                <p style="color: var(--text-muted); margin-bottom: 0;">Anda belum melakukan pemesanan sewa barang.</p>
            </div>
        @endforelse

        <!-- Empty state placeholder for JS filtering -->
        <div id="filter-empty-placeholder" style="display: none; text-align: center; padding: 64px 24px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px;">
            <!-- <div style="font-size: 4rem; margin-bottom: 16px;"><i class="fa-solid fa-house-circle-xmark"></i></div> -->
            <h3 style="font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Transaksi tidak ditemukan</h3>
            <p style="color: var(--text-muted); margin-bottom: 0;">Tidak ada transaksi dengan status ini.</p>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let activeFilter = 'Menunggu Pembayaran';

    document.addEventListener('DOMContentLoaded', () => {
        const tabs = document.querySelectorAll('.tab');
        
        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all
                tabs.forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked
                tab.classList.add('active');
                
                activeFilter = tab.getAttribute('data-filter');
                filterTransactions();
            });
        });

        // Run initial filter
        filterTransactions();
    });

    function filterTransactions() {
        const cards = document.querySelectorAll('.trx-card');
        const emptyPlaceholder = document.getElementById('filter-empty-placeholder');
        const searchInput = document.getElementById('search-trx-input');
        const searchQuery = searchInput ? searchInput.value.toLowerCase().trim() : '';
        
        let visibleCount = 0;

        cards.forEach(card => {
            const status = card.getAttribute('data-status');
            const invoiceText = card.textContent.toLowerCase();
            
            // Check if card matches active filter and search query
            const matchesFilter = (status === activeFilter);
            const matchesSearch = invoiceText.includes(searchQuery);

            if (matchesFilter && matchesSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Handle placeholder
        const mainPlaceholder = document.getElementById('no-trx-placeholder');
        if (visibleCount === 0) {
            emptyPlaceholder.style.display = 'block';
            if (mainPlaceholder) mainPlaceholder.style.display = 'none';
        } else {
            emptyPlaceholder.style.display = 'none';
        }
    }
</script>
@endpush
