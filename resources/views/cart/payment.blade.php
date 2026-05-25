@extends('layouts.app')

@section('title', 'Pilih Metode Pembayaran')

@php $currentPage = 'katalog'; @endphp

@section('topbar')
    <div style="display: flex; align-items: center; gap: 16px;">
        <h2 style="font-weight: 700; color: var(--text-main); font-size: 1.5rem; margin: 0;">Pilih Metode Pembayaran</h2>
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

    <form action="{{ route('cart.processPayment', $transaction->id) }}" method="POST" id="payment-form" style="max-width: 600px; margin: 0 auto;">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 24px; margin-bottom: 32px;">
            <!-- Invoice Details Summary Card -->
            <div style="background: linear-gradient(135deg, #1e3a8a, #0f172a); border-radius: 12px; padding: 24px; color: white; box-shadow: 0 4px 14px rgba(0,0,0,0.15);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-size: 0.85rem; opacity: 0.8;">
                    <span>Nomor Invoice: {{ $transaction->invoice_number }}</span>
                    <span class="status-badge status-pending" style="margin: 0; background-color: #fef08a; color: #854d0e;">Belum Bayar</span>
                </div>
                <div style="font-size: 0.9rem; margin-bottom: 4px; opacity: 0.9;">Total yang harus dibayar:</div>
                <div style="font-size: 1.8rem; font-weight: 800; letter-spacing: -0.5px;">
                    Rp {{ number_format($transaction->total, 0, ',', '.') }}
                </div>
            </div>

            <!-- E-Wallet Section -->
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">E-Wallet</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="payment-method-label">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">📱</span>
                            <div>
                                <div style="font-weight: 700; color: var(--text-main);">DANA</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Bayar instan via aplikasi DANA</div>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="DANA" required style="width: 18px; height: 18px; accent-color: #1e3a8a;">
                    </label>

                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="payment-method-label">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">💸</span>
                            <div>
                                <div style="font-weight: 700; color: var(--text-main);">Gopay</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Gunakan saldo Gopay atau GoPayLater</div>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="Gopay" required style="width: 18px; height: 18px; accent-color: #1e3a8a;">
                    </label>

                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="payment-method-label">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">🛒</span>
                            <div>
                                <div style="font-weight: 700; color: var(--text-main);">Shopeepay</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Bayar langsung menggunakan akun Shopeepay Anda</div>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="Shopeepay" required style="width: 18px; height: 18px; accent-color: #1e3a8a;">
                    </label>
                </div>
            </div>

            <!-- Bank Transfer Section -->
            <div>
                <h3 style="font-size: 1.05rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">Bank Transfer (Virtual Account)</h3>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="payment-method-label">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">🏦</span>
                            <div>
                                <div style="font-weight: 700; color: var(--text-main);">Bank BCA</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Transfer virtual account BCA</div>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="BCA" required style="width: 18px; height: 18px; accent-color: #1e3a8a;">
                    </label>

                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="payment-method-label">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">🏦</span>
                            <div>
                                <div style="font-weight: 700; color: var(--text-main);">Bank Mandiri</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Transfer virtual account Bank Mandiri</div>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="Mandiri" required style="width: 18px; height: 18px; accent-color: #1e3a8a;">
                    </label>

                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="payment-method-label">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">🏦</span>
                            <div>
                                <div style="font-weight: 700; color: var(--text-main);">Bank BRI</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Transfer virtual account Bank BRI</div>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="BRI" required style="width: 18px; height: 18px; accent-color: #1e3a8a;">
                    </label>

                    <label style="display: flex; align-items: center; justify-content: space-between; padding: 16px; background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; cursor: pointer; transition: all 0.2s;" class="payment-method-label">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <span style="font-size: 1.5rem;">🏦</span>
                            <div>
                                <div style="font-weight: 700; color: var(--text-main);">Bank BNI</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">Transfer virtual account Bank BNI</div>
                            </div>
                        </div>
                        <input type="radio" name="payment_method" value="BNI" required style="width: 18px; height: 18px; accent-color: #1e3a8a;">
                    </label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-dark" style="border-radius: 8px; font-weight: 700; font-size: 1.05rem; width: 100%; padding: 16px; margin-bottom: 48px;">
            Bayar Sekarang
        </button>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const labels = document.querySelectorAll('.payment-method-label');
        
        labels.forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            
            // Check initial state
            if (radio.checked) {
                label.style.borderColor = '#1e3a8a';
                label.style.backgroundColor = 'rgba(30, 58, 138, 0.05)';
            }
            
            label.addEventListener('click', () => {
                // Remove styling from all
                labels.forEach(l => {
                    l.style.borderColor = 'var(--border-color)';
                    l.style.backgroundColor = 'var(--card-bg)';
                });
                
                // Add styling to selected
                label.style.borderColor = '#1e3a8a';
                label.style.backgroundColor = 'rgba(30, 58, 138, 0.05)';
            });
        });
    });
</script>
@endpush
