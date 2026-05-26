@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@php $currentPage = 'katalog'; @endphp

@section('topbar')
    <div style="display: flex; align-items: center; gap: 16px;">
        <a href="{{ route('katalog') }}" style="color: var(--text-main); display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background-color: var(--input-bg); transition: background-color 0.2s;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
        <h2 style="font-weight: 700; color: var(--text-main); font-size: 1.5rem; margin: 0;">Keranjang Saya</h2>
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
    @if(session('success'))
        <div style="background-color: #dcfce7; border: 1px solid #bbf7d0; color: #166534; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div style="background-color: #fef9c3; border: 1px solid #fef08a; color: #854d0e; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
            {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background-color: #fee2e2; border: 1px solid #fecdd3; color: #9f1239; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
            {{ session('error') }}
        </div>
    @endif

    @if($carts->isEmpty())
        <div style="text-align: center; padding: 64px 24px; background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 16px;">
            <div style="font-size: 4rem; margin-bottom: 16px;">🛒</div>
            <h3 style="font-weight: 700; color: var(--text-main); margin-bottom: 8px;">Keranjang belanja Anda kosong</h3>
            <p style="color: var(--text-muted); margin-bottom: 24px;">Silakan jelajahi katalog produk kami untuk menambahkan peralatan sewa.</p>
            <a href="{{ route('katalog') }}" class="btn btn-dark" style="border-radius: 8px;">Jelajahi Katalog</a>
        </div>
    @else
        <form action="{{ route('cart.checkout') }}" method="GET" id="checkout-form">
            <div style="display: flex; flex-direction: column; gap: 16px; margin-bottom: 80px;">
                @foreach($carts as $cart)
                    <div class="trx-card" style="display: flex; align-items: center; gap: 16px; padding: 16px 24px; position: relative;">
                        <!-- Checkbox Select Item -->
                        <div style="display: flex; align-items: center; justify-content: center;">
                            <input type="checkbox" name="items[]" value="{{ $cart->id }}" class="item-checkbox" checked style="width: 20px; height: 20px; border-radius: 4px; cursor: pointer; accent-color: #1e3a8a;" onchange="updateSummary()">
                        </div>

                        <!-- Product Thumbnail -->
                        <div class="trx-img" style="display: flex; align-items: center; justify-content: center; font-size: 2.2rem; background-color: var(--input-bg); border-radius: 12px; width: 80px; height: 80px; flex-shrink: 0;">
                            @if($cart->product->category == 'Tenda') ⛺
                            @elseif($cart->product->category == 'Tas') 🎒
                            @elseif($cart->product->category == 'Alat Pribadi') 🥾
                            @elseif($cart->product->category == 'Alat Masak') 🍳
                            @elseif($cart->product->category == 'Penerangan') 💡
                            @else 📦
                            @endif
                        </div>

                        <!-- Details -->
                        <div class="trx-details" style="flex: 1; min-width: 0; gap: 4px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <h3 style="font-weight: 700; color: var(--text-main); font-size: 1.05rem; margin: 0;">{{ $cart->product->name }}</h3>
                                
                                <!-- Delete Item -->
                                <button type="button" onclick="confirmDelete('{{ route('cart.delete', $cart->id) }}')" style="background: none; color: #ef4444; cursor: pointer; padding: 4px; transition: color 0.2s;" title="Hapus Barang">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                </button>
                            </div>
                            
                            <span class="status-badge status-pending" style="display: inline-block; width: max-content; margin: 2px 0;">Menunggu Pembayaran</span>
                            
                            <div style="font-weight: 800; color: var(--text-main); font-size: 1.05rem; margin-top: 4px;">
                                Rp {{ number_format($cart->product->price, 0, ',', '.') }}<span style="font-size: 0.75rem; font-weight: 500; color: var(--text-muted);">/hari</span>
                            </div>

                            <!-- Qty Controls & Price -->
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px; flex-wrap: wrap; gap: 8px;">
                                <div style="display: flex; align-items: center; background-color: var(--input-bg); border-radius: 8px; padding: 4px 8px; gap: 12px; width: max-content;">
                                    <!-- Decrease -->
                                    <button type="submit" form="qty-form-dec-{{ $cart->id }}" style="background: none; border: none; font-size: 1.25rem; font-weight: 700; color: var(--text-main); padding: 0 4px; display: flex; align-items: center; justify-content: center; height: 24px; cursor: pointer;">-</button>
                                    <span style="font-weight: 700; font-size: 0.95rem; min-width: 16px; text-align: center;">{{ $cart->qty }}x</span>
                                    <!-- Increase -->
                                    <button type="submit" form="qty-form-inc-{{ $cart->id }}" style="background: none; border: none; font-size: 1.25rem; font-weight: 700; color: var(--text-main); padding: 0 4px; display: flex; align-items: center; justify-content: center; height: 24px; cursor: pointer;">+</button>
                                </div>
                                <div style="font-weight: 800; color: #1e3a8a; font-size: 1.1rem;" class="subtotal-price-text" data-price="{{ $cart->product->price }}" data-qty="{{ $cart->qty }}">
                                    Rp {{ number_format($cart->product->price * $cart->qty, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Sticky Bottom Bar for Cart Summary -->
            <div style="position: fixed; bottom: 0; left: 260px; right: 0; background-color: var(--card-bg); border-top: 1px solid var(--border-color); padding: 16px 32px; display: flex; align-items: center; justify-content: space-between; z-index: 99; transition: left 0.3s;" id="sticky-bottom-bar">
                <div style="display: flex; align-items: center; gap: 24px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 600; cursor: pointer; color: var(--text-main);">
                        <input type="checkbox" id="select-all-checkbox" checked style="width: 18px; height: 18px; cursor: pointer; accent-color: #1e3a8a;" onchange="toggleSelectAll()">
                        Pilih Semua
                    </label>
                    <div style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">
                        <span id="selected-count">0</span> barang terpilih
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 32px;">
                    <div style="text-align: right;">
                        <div style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">Total Harga Sementara:</div>
                        <div style="font-size: 1.3rem; font-weight: 800; color: #1e3a8a;" id="summary-total-text">Rp 0</div>
                    </div>
                    <button type="submit" class="btn btn-dark" style="border-radius: 8px; font-weight: 700; padding: 12px 28px;" id="checkout-btn">
                        Lanjut ke Rincian Sewa
                    </button>
                </div>
            </div>
        </form>

        <!-- Forms for quantity changes (need to prevent checkbox default submission) -->
        @foreach($carts as $cart)
            <form action="{{ route('cart.update', $cart->id) }}" method="POST" id="qty-form-inc-{{ $cart->id }}" style="display: none;">
                @csrf
                <input type="hidden" name="action" value="increase">
            </form>
            <form action="{{ route('cart.update', $cart->id) }}" method="POST" id="qty-form-dec-{{ $cart->id }}" style="display: none;">
                @csrf
                <input type="hidden" name="action" value="decrease">
            </form>
        @endforeach

        <!-- Form for delete -->
        <form id="delete-form" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    @endif
@endsection

@push('scripts')
<script>
    function updateSummary() {
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const selectedCountSpan = document.getElementById('selected-count');
        const summaryTotalText = document.getElementById('summary-total-text');
        const checkoutBtn = document.getElementById('checkout-btn');

        let total = 0;
        let checkedCount = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                checkedCount++;
                const card = cb.closest('.trx-card');
                const subtotalText = card.querySelector('.subtotal-price-text');
                const price = parseInt(subtotalText.getAttribute('data-price'));
                const qty = parseInt(subtotalText.getAttribute('data-qty'));
                total += price * qty;
            }
        });

        selectedCountSpan.textContent = checkedCount;
        summaryTotalText.textContent = 'Rp ' + total.toLocaleString('id-ID');

        // Check/Uncheck the select-all master checkbox
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = (checkedCount === checkboxes.length);
        }

        // Enable or disable checkout button based on selections
        if (checkedCount > 0) {
            checkoutBtn.removeAttribute('disabled');
            checkoutBtn.style.opacity = '1';
            checkoutBtn.style.cursor = 'pointer';
        } else {
            checkoutBtn.setAttribute('disabled', 'disabled');
            checkoutBtn.style.opacity = '0.5';
            checkoutBtn.style.cursor = 'not-allowed';
        }
    }

    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const checkboxes = document.querySelectorAll('.item-checkbox');

        checkboxes.forEach(cb => {
            cb.checked = selectAllCheckbox.checked;
        });

        updateSummary();
    }

    function confirmDelete(url) {
        if (confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')) {
            const form = document.getElementById('delete-form');
            form.action = url;
            form.submit();
        }
    }

    // Dynamic adjustment of the sticky bottom bar width based on sidebar
    document.addEventListener('DOMContentLoaded', () => {
        updateSummary();

        // Check if sidebar has toggled or is collapsed on mobile
        function adjustStickyWidth() {
            const bottomBar = document.getElementById('sticky-bottom-bar');
            if (!bottomBar) return;
            
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth <= 768) {
                bottomBar.style.left = '0';
            } else {
                bottomBar.style.left = '260px';
            }
        }

        window.addEventListener('resize', adjustStickyWidth);
        adjustStickyWidth();
    });
</script>
@endpush
