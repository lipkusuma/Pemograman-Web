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

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert-toast alert-success" id="alertToast">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-toast alert-error" id="alertToast">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

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
            <button class="btn-tambah" onclick="openModal('tambahModal')">
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
                @forelse($stokBarang as $barang)
                <tr>
                    <td style="font-weight:600;">{{ $barang['id'] }}</td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            @if($barang['image'])
                                <img src="{{ asset('uploads/products/' . $barang['image']) }}" alt="{{ $barang['nama'] }}" style="width: 36px; height: 36px; border-radius: 8px; object-fit: cover;">
                            @else
                                <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--input-bg); display: flex; align-items: center; justify-content: center;">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="color: var(--text-muted);"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                                </div>
                            @endif
                            {{ $barang['nama'] }}
                        </div>
                    </td>
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
                            <a href="{{ route('stok_barang.edit', $barang['id']) }}" class="btn-action btn-action-edit" title="Edit">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <button type="button" class="btn-action btn-action-delete" title="Hapus"
                                    onclick="openDeleteModal('{{ $barang['id'] }}', '{{ addslashes($barang['nama']) }}')">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 48px 20px; color: var(--text-muted);">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 12px; opacity: 0.5;">
                            <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/>
                        </svg>
                        <p style="font-weight: 600; font-size: 1rem; margin: 0 0 4px;">Belum ada barang</p>
                        <p style="font-size: 0.875rem; margin: 0;">Klik "Tambah Barang" untuk menambahkan produk baru.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         MODAL: Tambah Barang
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="modal-overlay" id="tambahModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Barang Baru</h3>
                <button class="modal-close" onclick="closeModal('tambahModal')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <form action="{{ route('stok_barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
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
                            <label class="form-label" for="add_id">Kode Barang</label>
                            <input type="text" name="id" id="add_id" class="form-input" placeholder="BRG001" value="{{ old('id') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="add_name">Nama Barang</label>
                            <input type="text" name="name" id="add_name" class="form-input" placeholder="Tenda Ultralight 2P" value="{{ old('name') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="add_category">Kategori</label>
                            <input type="text" name="category" id="add_category" class="form-input" placeholder="Tenda" list="categoryList" value="{{ old('category') }}" required>
                            <datalist id="categoryList">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="add_stock">Stok</label>
                            <input type="number" name="stock" id="add_stock" class="form-input" placeholder="10" min="0" value="{{ old('stock') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="add_price">Harga (Rp)</label>
                            <input type="number" name="price" id="add_price" class="form-input" placeholder="850000" min="0" value="{{ old('price') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="add_image">Gambar Produk</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="image" id="add_image" class="file-input" accept="image/*" onchange="previewImage(this, 'addPreview')">
                                <div class="file-upload-label" id="addUploadLabel">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                    <span>Pilih gambar atau drag & drop</span>
                                    <small>JPG, PNG, WebP — Max 2MB</small>
                                </div>
                            </div>
                            <div class="image-preview" id="addPreview" style="display: none;">
                                <img id="addPreviewImg" src="" alt="Preview">
                                <button type="button" class="preview-remove" onclick="removePreview('add_image', 'addPreview', 'addUploadLabel')">✕</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-cancel" onclick="closeModal('tambahModal')">Batal</button>
                    <button type="submit" class="btn-modal btn-save">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                        Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════
         MODAL: Konfirmasi Hapus
    ═══════════════════════════════════════════════════════════════════ --}}
    <div class="modal-overlay" id="deleteModal">
        <div class="modal-container modal-sm">
            <div class="modal-header">
                <h3 class="modal-title">Hapus Barang</h3>
                <button class="modal-close" onclick="closeModal('deleteModal')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="modal-body" style="text-align: center; padding: 32px 24px;">
                <div style="width: 64px; height: 64px; border-radius: 50%; background: #fecdd3; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#e11d48" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </div>
                <p style="font-weight: 600; font-size: 1.1rem; margin-bottom: 8px; color: var(--text-main);">Yakin ingin menghapus?</p>
                <p style="color: var(--text-muted); font-size: 0.9rem;" id="deleteItemName">Barang ini akan dihapus secara permanen.</p>
            </div>
            <div class="modal-footer" style="justify-content: center;">
                <button type="button" class="btn-modal btn-cancel" onclick="closeModal('deleteModal')">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modal btn-delete">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ── Modal Functions ──────────────────────────────────────────────
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = '';
    }

    function openDeleteModal(productId, productName) {
        document.getElementById('deleteItemName').textContent = '"' + productName + '" akan dihapus secara permanen.';
        document.getElementById('deleteForm').action = '/stok-barang/' + productId;
        openModal('deleteModal');
    }

    // Close modal on overlay click
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => {
                m.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });

    // ── Image Preview ────────────────────────────────────────────────
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
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePreview(inputId, previewId, labelId) {
        document.getElementById(inputId).value = '';
        document.getElementById(previewId).style.display = 'none';
        document.getElementById(labelId).style.display = 'flex';
    }

    // ── Auto-dismiss alert toasts ────────────────────────────────────
    const toast = document.getElementById('alertToast');
    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => toast.remove(), 300);
        }, 3500);
    }

    // Auto-open modal if there are validation errors
    @if($errors->any())
        openModal('tambahModal');
    @endif
</script>
@endpush
