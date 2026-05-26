@extends('layouts.app')

@section('title', 'Profil & Upload Foto')

@php $currentPage = 'profile'; @endphp

@push('styles')
<style>
    .alert { padding: 15px; margin-bottom: 24px; border-radius: 6px; }
    .success { background-color: #dcfce7; color: #166534; }
    .error { background-color: #fee2e2; color: #991b1b; }
    #imagePreview { max-width: 100%; max-height: 250px; display: none; border-radius: 6px; margin: 0 auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    #emptyPreviewText { color: #94a3b8; font-weight: 500; font-size: 0.95rem; }
    .btn-upload { margin-top: 24px; width: 100%; padding: 14px; background-color: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; }
    .btn-upload:hover { background-color: #1d4ed8; }
    .file-input-wrapper { margin-top: 16px; margin-bottom: 8px; }
</style>
@endpush

@section('topbar')
    <div style="display: flex; align-items: center; gap: 16px;">
        <button class="menu-toggle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin:0;">Profil & Akun</h2>
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
    <div class="profile-container" style="display: flex; flex-direction: column; align-items: center; gap: 24px; margin-bottom: 40px;">
        <div class="profile-info" style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
            @if(session('profile_pic'))
                <img src="{{ asset('uploads/' . session('profile_pic')) }}" alt="Profile"
                    style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            @else
                <div style="width: 120px; height: 120px; border-radius: 50%; background-color: #cbd5e1; display: flex; align-items: center; justify-content: center;">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="#94a3b8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
            @endif
            <h3 style="font-size: 1.5rem; font-weight: 700; color: var(--text-main);">{{ session('username') }}</h3>
        </div>
    </div>

    <div class="upload-card">
        <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 8px; color: var(--text-main);">Upload Foto Profil Baru</h3>
        <p style="color: var(--text-muted); margin-bottom: 32px; font-size: 0.95rem;">Perbarui foto profil Anda. Hanya file yang memiliki ekstensi .jpg atau .png yang dapat diterima.</p>

        @if(session('pesan_sukses'))
            <div class="alert success">{!! session('pesan_sukses') !!}</div>
        @endif

        @if(session('pesan_error'))
            <div class="alert error">{!! session('pesan_error') !!}</div>
        @endif

        <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="font-weight: 600; color: var(--text-main);">Pilih File Foto Anda:</div>
            <div class="file-input-wrapper">
                <input type="file" name="foto" id="foto" accept=".jpg, .png" required
                    style="width: 100%; border: 1px solid var(--border-color); padding: 12px; border-radius: 8px; background: var(--card-bg); color: var(--text-main);">
            </div>

            <div class="preview-box">
                <div id="emptyPreviewText" style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    <span>Area Preview Foto</span>
                </div>
                <img id="imagePreview" src="" alt="Preview Gambar">
            </div>

            <button type="submit" class="btn-upload">Simpan Foto Profil</button>
        </form>
    </div>
@endsection

@push('scripts')
<script>
    // Fitur Live Preview File Upload (Front-End)
    const inputFile = document.getElementById('foto');
    const imagePreview = document.getElementById('imagePreview');
    const emptyPreviewText = document.getElementById('emptyPreviewText');

    inputFile.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.type === "image/jpeg" || file.type === "image/png") {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    emptyPreviewText.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                alert("Tolong pilih gambar ber-ekstensi .jpg atau .png saja ya!");
                this.value = "";
                imagePreview.style.display = 'none';
                emptyPreviewText.style.display = 'flex';
            }
        } else {
            imagePreview.style.display = 'none';
            emptyPreviewText.style.display = 'flex';
        }
    });
</script>
@endpush
