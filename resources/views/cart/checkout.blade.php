@extends('layouts.app')

@section('title', 'Detail Sewa')

@php $currentPage = 'katalog'; @endphp

@section('topbar')
    <div style="display: flex; align-items: center; gap: 16px;">
        <a href="{{ route('cart.index') }}" style="color: var(--text-main); display: flex; align-items: center; justify-content: center; width: 40px; height: 40px; border-radius: 50%; background-color: var(--input-bg); transition: background-color 0.2s;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        </a>
        <h2 style="font-weight: 700; color: var(--text-main); font-size: 1.5rem; margin: 0;">Konfirmasi Sewa</h2>
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
    @if(session('error'))
        <div style="background-color: #fee2e2; border: 1px solid #fecdd3; color: #9f1239; padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('cart.processCheckout') }}" method="POST" id="checkout-form" style="max-width: 800px; margin: 0 auto;">
        @csrf
        <!-- Keep track of selected cart items -->
        @foreach($selectedIds as $id)
            <input type="hidden" name="items[]" value="{{ $id }}">
        @endforeach

        <div style="display: flex; flex-direction: column; gap: 24px;">
            <!-- 1. Detail Barang -->
            <div style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
                <h3 style="font-weight: 700; color: var(--text-main); font-size: 1.1rem; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <span>🎒</span> Barang yang Disewa
                </h3>
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($cartItems as $item)
                        <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 16px; border-bottom: 1px dashed var(--border-color); last-child: border-bottom: none;">
                            <div style="display: flex; align-items: center; justify-content: center; font-size: 2rem; background-color: var(--input-bg); border-radius: 8px; width: 64px; height: 64px; flex-shrink: 0;">
                                @if($item->product->category == 'Tenda') ⛺
                                @elseif($item->product->category == 'Tas') 🎒
                                @elseif($item->product->category == 'Alat Pribadi') 🥾
                                @elseif($item->product->category == 'Alat Masak') 🍳
                                @elseif($item->product->category == 'Penerangan') 💡
                                @else 📦
                                @endif
                            </div>
                            <div style="flex: 1; min-width: 0;">
                                <h4 style="font-weight: 700; color: var(--text-main); font-size: 0.95rem; margin: 0;">{{ $item->product->name }}</h4>
                                <div style="color: var(--text-muted); font-size: 0.85rem; margin-top: 4px;" class="item-qty-text" data-price="{{ $item->product->price }}" data-qty="{{ $item->qty }}">
                                    {{ $item->qty }}x @ Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                </div>
                            </div>
                            <div style="font-weight: 700; color: var(--text-main); text-align: right;">
                                <span class="duration-days-badge">1</span> Hari
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Tanggal Sewa -->
            <div style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
                <h3 style="font-weight: 700; color: var(--text-main); font-size: 1.1rem; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <span>📅</span> Durasi Sewa
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}" value="{{ old('start_date', date('Y-m-d')) }}" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--input-bg); color: var(--text-main); font-weight: 600;" onchange="calculateRental()">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('end_date', date('Y-m-d', strtotime('+1 day'))) }}" style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--input-bg); color: var(--text-main); font-weight: 600;" onchange="calculateRental()">
                    </div>
                </div>
                <div style="margin-top: 12px; color: var(--text-muted); font-size: 0.8rem; font-style: italic;">
                    *jadwal sewa dapat disesuaikan kembali sesuai kebutuhan.
                </div>
            </div>

            <!-- 3. Lokasi Pengambilan -->
            <div style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
                <h3 style="font-weight: 700; color: var(--text-main); font-size: 1.1rem; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <span>📍</span> Lokasi Pengambilan
                </h3>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <textarea name="pickup_location" id="pickup_location" rows="3" required style="width: 100%; padding: 12px; border: 1px solid var(--border-color); border-radius: 8px; background-color: var(--input-bg); color: var(--text-main); font-size: 0.9rem; line-height: 1.5; resize: none;" placeholder="Masukkan alamat lengkap toko atau titik pengambilan...">{{ old('pickup_location', 'Jalan Sigura Gura II no. 8, Desa/Kelurahan Ketawanggede, Kecamatan Lowokwaru, Kota Malang, Jawa Timur 65145.') }}</textarea>
                </div>
            </div>

            <!-- 4. Rincian Pembayaran -->
            <div style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
                <h3 style="font-weight: 700; color: var(--text-main); font-size: 1.1rem; margin-bottom: 16px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                    <span>💳</span> Rincian Pembayaran
                </h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-muted); font-weight: 500;">Subtotal (<span id="calc-duration-text">1</span> hari)</span>
                        <span style="font-weight: 700; color: var(--text-main);" id="subtotal-display">Rp 0</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="color: var(--text-muted); font-weight: 500;">Diskon</span>
                        <span style="font-weight: 700; color: #ef4444;" id="discount-display">- Rp 0</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border-color); padding-top: 12px; margin-top: 4px;">
                        <span style="font-size: 1.05rem; font-weight: 700; color: var(--text-main);">Total Pembayaran</span>
                        <span style="font-size: 1.25rem; font-weight: 800; color: #1e3a8a;" id="total-display">Rp 0</span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-dark" style="border-radius: 8px; font-weight: 700; font-size: 1.05rem; width: 100%; padding: 16px;">
                Pilih Metode Pembayaran
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    function calculateRental() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        const calcDurationText = document.getElementById('calc-duration-text');
        const durationBadges = document.querySelectorAll('.duration-days-badge');
        
        const subtotalDisplay = document.getElementById('subtotal-display');
        const totalDisplay = document.getElementById('total-display');

        const start = new Date(startDateInput.value);
        const end = new Date(endDateInput.value);

        // Minimum end date must be at least start date + 1 day
        const minEndDate = new Date(start);
        minEndDate.setDate(minEndDate.getDate() + 1);
        
        const yyyy = minEndDate.getFullYear();
        const mm = String(minEndDate.getMonth() + 1).padStart(2, '0');
        const dd = String(minEndDate.getDate()).padStart(2, '0');
        endDateInput.min = `${yyyy}-${mm}-${dd}`;

        if (end <= start) {
            endDateInput.value = `${yyyy}-${mm}-${dd}`;
        }

        // Re-calculate dates
        const finalStart = new Date(startDateInput.value);
        const finalEnd = new Date(endDateInput.value);
        
        const timeDiff = Math.abs(finalEnd.getTime() - finalStart.getTime());
        let durationDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (isNaN(durationDays) || durationDays <= 0) {
            durationDays = 1;
        }

        // Update duration displays
        calcDurationText.textContent = durationDays;
        durationBadges.forEach(badge => {
            badge.textContent = durationDays;
        });

        // Calculate pricing
        let subtotal = 0;
        const itemTexts = document.querySelectorAll('.item-qty-text');
        itemTexts.forEach(el => {
            const price = parseInt(el.getAttribute('data-price'));
            const qty = parseInt(el.getAttribute('data-qty'));
            subtotal += price * qty * durationDays;
        });

        subtotalDisplay.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
        totalDisplay.textContent = 'Rp ' + subtotal.toLocaleString('id-ID'); // total is subtotal - discount (0)
    }

    document.addEventListener('DOMContentLoaded', () => {
        calculateRental();
    });
</script>
@endpush
