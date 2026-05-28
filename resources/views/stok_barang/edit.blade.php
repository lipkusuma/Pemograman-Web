@extends('layouts.app')

@section('title', 'Edit Barang - Admin')

@php $currentPage = 'stok_barang'; @endphp

@section('topbar')
    <div style="display: flex; align-items: center; gap: 16px;">
        <button class="menu-toggle">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin:0;">Edit Barang</h2>
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
    <div class="edit-container">
        {{-- Back Button --}}
        <a href="{{ route('stok_barang') }}" class="btn-back">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Kembali ke Daftar Barang
        </a>

        <div class="edit-card">
            <div class="edit-card-header">
                <div class="edit-card-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </div>
                <div>
                    <h3 class="edit-card-title">Edit Barang: {{ $product->name }}</h3>
                    <p class="edit-card-subtitle">Kode: {{ $product->id }}</p>
                </div>
            </div>

            <form action="{{ route('stok_barang.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Validation Errors --}}
                @if($errors->any())
                    <div class="form-errors">
                        @foreach($errors->all() as $error)
                            <p>⚠ {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="edit_name">Nama Barang</label>
                        <input type="text" name="name" id="edit_name" class="form-input" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="edit_category">Kategori</label>
                        <input type="text" name="category" id="edit_category" class="form-input" list="editCategoryList" value="{{ old('category', $product->category) }}" required>
                        <datalist id="editCategoryList">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="edit_stock">Stok</label>
                        <input type="number" name="stock" id="edit_stock" class="form-input" min="0" value="{{ old('stock', $product->stock) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="edit_price">Harga (Rp)</label>
                        <input type="number" name="price" id="edit_price" class="form-input" min="0" value="{{ old('price', $product->price) }}" required>
                    </div>
                    <div class="form-group form-group-full">
                        <label class="form-label" for="edit_image">Gambar Produk</label>

                        {{-- Current Image --}}
                        @if($product->image)
                            <div class="current-image" id="currentImage">
                                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                                <div class="current-image-info">
                                    <span>Gambar saat ini</span>
                                    <small>Upload gambar baru untuk mengganti</small>
                                </div>
                            </div>
                        @endif

                        <div class="file-upload-wrapper">
                            <input type="file" name="image" id="edit_image" class="file-input" accept="image/*" onchange="previewImage(this, 'editPreview')">
                            <div class="file-upload-label" id="editUploadLabel">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                <span>{{ $product->image ? 'Pilih gambar baru untuk mengganti' : 'Pilih gambar atau drag & drop' }}</span>
                                <small>JPG, PNG, WebP — Max 2MB</small>
                            </div>
                        </div>
                        <div class="image-preview" id="editPreview" style="display: none;">
                            <img id="editPreviewImg" src="" alt="Preview">
                            <button type="button" class="preview-remove" onclick="removePreview('edit_image', 'editPreview', 'editUploadLabel')">✕</button>
                        </div>
                    </div>
                </div>

                <div class="edit-actions">
                    <a href="{{ route('stok_barang') }}" class="btn-modal btn-cancel">Batal</a>
                    <button type="submit" class="btn-modal btn-save">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const img = preview.querySelector('img');
        const uploadLabel = input.parentElement.querySelector('.file-upload-label');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
                uploadLabel.style.display = 'none';
                // Hide current image if exists
                const current = document.getElementById('currentImage');
                if (current) current.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePreview(inputId, previewId, labelId) {
        document.getElementById(inputId).value = '';
        document.getElementById(previewId).style.display = 'none';
        document.getElementById(labelId).style.display = 'flex';
        // Show current image again if exists
        const current = document.getElementById('currentImage');
        if (current) current.style.display = 'flex';
    }
</script>
@endpush
