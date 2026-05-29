@extends('layouts.app')

@section('title', 'Katalog Produk')

@php $currentPage = 'katalog'; @endphp

@push('styles')
<style>
/* ── Search bar topbar ── */
.kl-search-wrap {
    display: flex; align-items: center;
    background: var(--input-bg); border: 1.5px solid var(--border-color);
    border-radius: 10px; padding: 0 14px; gap: 8px;
    flex: 1; max-width: 360px; transition: border-color .2s;
}
.kl-search-wrap:focus-within { border-color: #2563eb; }
.kl-search-wrap input {
    border: none; background: transparent; outline: none;
    font-size: .9rem; color: var(--text-main); width: 100%; padding: 9px 0;
}
.kl-search-wrap input::placeholder { color: var(--text-muted); }

/* ── Banner ── */
.kl-banner {
    border-radius: 20px;
    background: linear-gradient(120deg, #1e3a8a 0%, #1d4ed8 45%, #0ea5e9 100%);
    padding: 36px 44px;
    display: flex; align-items: center; justify-content: space-between;
    gap: 24px; margin-bottom: 36px; overflow: hidden; position: relative;
}
.kl-banner::before {
    content: '';
    position: absolute; right: -60px; top: -60px;
    width: 260px; height: 260px;
    border-radius: 50%;
    background: rgba(255,255,255,.07);
}
.kl-banner::after {
    content: '';
    position: absolute; right: 80px; bottom: -80px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,.05);
}
.kl-banner-text { position: relative; z-index: 1; }
.kl-banner-text h2 {
    font-size: 1.75rem; font-weight: 800; color: #fff;
    line-height: 1.25; margin-bottom: 10px; letter-spacing: -.3px;
}
.kl-banner-text p { font-size: .9rem; color: rgba(255,255,255,.82); line-height: 1.6; }
.kl-banner-badge {
    position: relative; z-index: 1; flex-shrink: 0;
    background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.3);
    border-radius: 16px; padding: 18px 28px; text-align: center; color: #fff;
}
.kl-banner-badge .kl-off { font-size: 2.6rem; font-weight: 900; line-height: 1; }
.kl-banner-badge .kl-off-label { font-size: .78rem; font-weight: 600; opacity: .85; letter-spacing: .5px; }

/* ── Section Header ── */
.kl-section-hd {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 18px;
}
.kl-section-hd h3 { font-size: 1.05rem; font-weight: 700; color: var(--text-main); }
.kl-section-hd span { font-size: .82rem; color: var(--text-muted); font-weight: 500; }

/* ── Category pills ── */
.kl-cats {
    display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 32px;
}
.kl-cat {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 18px; border-radius: 999px;
    background: var(--card-bg); border: 1.5px solid var(--border-color);
    font-size: .85rem; font-weight: 600; color: var(--text-main);
    cursor: pointer; transition: all .2s; user-select: none;
}
.kl-cat:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
.kl-cat.active {
    background: #1e3a8a; border-color: #1e3a8a; color: #fff;
}
.kl-cat-emoji { font-size: 1rem; }

/* ── Filter + Sort bar ── */
.kl-toolbar {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 24px; flex-wrap: wrap;
}
.kl-toolbar-left { display: flex; gap: 8px; flex: 1; flex-wrap: wrap; }
.kl-sort {
    padding: 8px 14px; border: 1.5px solid var(--border-color);
    border-radius: 8px; background: var(--card-bg); color: var(--text-main);
    font-size: .84rem; font-weight: 500; cursor: pointer; outline: none;
    transition: border-color .2s;
}
.kl-sort:focus { border-color: #2563eb; }
.kl-result-count {
    font-size: .83rem; color: var(--text-muted); margin-left: auto;
    align-self: center; white-space: nowrap;
}

/* ── Products Grid ── */
.kl-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 22px;
}

/* ── Product Card ── */
.kl-card {
    background: var(--card-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 16px; overflow: hidden;
    display: flex; flex-direction: column;
    transition: transform .2s, box-shadow .2s, border-color .2s;
    position: relative;
}
.kl-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 32px rgba(0,0,0,.1);
    border-color: #c7d2fe;
}

/* image area */
.kl-img-wrap {
    width: 100%; aspect-ratio: 4/3;
    background: var(--input-bg);
    display: flex; align-items: center; justify-content: center;
    overflow: hidden; position: relative;
}
.kl-img-wrap img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .35s;
}
.kl-card:hover .kl-img-wrap img { transform: scale(1.06); }
.kl-img-emoji { font-size: 3rem; }

/* status badge on image */
.kl-status {
    position: absolute; top: 10px; left: 10px;
    padding: 3px 10px; border-radius: 999px;
    font-size: .7rem; font-weight: 700; letter-spacing: .3px;
}
.kl-status-ok   { background: #dcfce7; color: #15803d; }
.kl-status-low  { background: #fef9c3; color: #a16207; }
.kl-status-out  { background: #fee2e2; color: #b91c1c; }

/* category tag */
.kl-cat-tag {
    position: absolute; top: 10px; right: 10px;
    padding: 3px 9px; border-radius: 6px;
    background: rgba(15,23,42,.55); color: #fff;
    font-size: .68rem; font-weight: 600; backdrop-filter: blur(4px);
}

/* card body */
.kl-body { padding: 14px 16px; flex: 1; display: flex; flex-direction: column; gap: 6px; }
.kl-name {
    font-size: .95rem; font-weight: 700; color: var(--text-main);
    line-height: 1.35;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.kl-meta {
    display: flex; align-items: center; gap: 6px;
    font-size: .76rem; color: var(--text-muted);
}
.kl-dot { width: 3px; height: 3px; border-radius: 50%; background: var(--border-color); }
.kl-price {
    font-size: 1.05rem; font-weight: 800; color: var(--text-main);
    margin-top: auto; padding-top: 6px;
}
.kl-price span { font-size: .75rem; font-weight: 500; color: var(--text-muted); }

/* card footer */
.kl-footer { padding: 0 16px 16px; }
.kl-btn-add {
    width: 100%; padding: 9px; border-radius: 10px;
    background: #1e3a8a; color: #fff;
    border: none; font-size: .87rem; font-weight: 700;
    cursor: pointer; transition: background .2s, transform .15s;
    display: flex; align-items: center; justify-content: center; gap: 6px;
}
.kl-btn-add:hover { background: #1d4ed8; transform: translateY(-1px); }
.kl-btn-out {
    width: 100%; padding: 9px; border-radius: 10px;
    background: var(--input-bg); color: var(--text-muted);
    border: none; font-size: .87rem; font-weight: 600;
    cursor: not-allowed;
}

/* ── Empty state ── */
.kl-empty {
    grid-column: 1/-1;
    display: flex; flex-direction: column; align-items: center;
    justify-content: center; padding: 64px 0; gap: 14px;
    color: var(--text-muted);
}
.kl-empty svg { opacity: .3; }
.kl-empty p { font-size: .95rem; font-weight: 500; }

/* ── Responsive ── */
@media (max-width: 640px) {
    .kl-banner { padding: 24px 20px; }
    .kl-banner-text h2 { font-size: 1.3rem; }
    .kl-banner-badge { display: none; }
    .kl-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
}
@media (max-width: 380px) {
    .kl-grid { grid-template-columns: 1fr; }
}

/* ── Toast notification ── */
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
}
</style>
@endpush

@section('topbar')
<div style="display:flex;align-items:center;gap:14px;flex:1;">
    <button class="menu-toggle">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
    </button>
    <div class="kl-search-wrap">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <input type="text" id="klSearchInput" placeholder="Cari produk...">
    </div>
</div>
<div class="topbar-actions">
    <a href="{{ route('cart.index') }}" class="action-icon" title="Keranjang" style="position:relative;display:inline-block;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        @php $cartCount = \App\Models\Cart::where('user_id', session('user_id'))->sum('qty'); @endphp
        <span id="cart-badge-topbar" style="position:absolute;top:-7px;right:-7px;background:#ef4444;color:#fff;border-radius:50%;font-size:.6rem;width:15px;height:15px;display:{{ $cartCount > 0 ? 'flex' : 'none' }};align-items:center;justify-content:center;font-weight:700;">{{ $cartCount > 0 ? $cartCount : '' }}</span>
    </a>
    <div class="profile-circle">
        @if(session('profile_pic'))
            <img src="{{ asset('uploads/' . session('profile_pic')) }}" alt="Profile">
        @endif
    </div>
</div>
@endsection

@section('content')

{{-- ── HERO BANNER ── --}}
<div class="kl-banner">
    <div class="kl-banner-text">
        <h2>Sewa Perlengkapan <br> Outdoor Terbaik</h2>
        <p>Lengkapi petualanganmu dengan peralatan berkualitas.<br>Tersedia berbagai pilihan tenda, tas, dan perlengkapan lainnya.</p>
    </div>
    <div class="kl-banner-badge">
        <div class="kl-off">20%</div>
        <div class="kl-off-label">OFF TODAY</div>
    </div>
</div>

{{-- ── CATEGORIES ── --}}
@php
    $categories = [
        'Semua',
        'Tenda',
        'Tas',
        'Alat Pribadi',
        'Alat Masak',
        'Penerangan',
    ];

@endphp

<div class="kl-section-hd">
    <h3>Kategori</h3>
</div>
<div class="kl-cats" id="klCats">
    @foreach($categories as $cat)
    <button class="kl-cat {{ $cat === 'Semua' ? 'active' : '' }}" data-cat="{{ $cat }}">
        {{ $cat }}
    </button>
    @endforeach
</div>

{{-- ── TOOLBAR ── --}}
<div class="kl-toolbar">
    <div class="kl-toolbar-left">
        <select class="kl-sort" id="klSort">
            <option value="default">Default</option>
            <option value="price_asc">Harga : Rendah ke Tinggi</option>
            <option value="price_desc">Harga : Tinggi ke Rendah</option>
            <option value="name_asc">Nama : A–Z</option>
            <option value="stock_desc">Stok : Terbanyak</option>
        </select>
    </div>
    <span class="kl-result-count" id="klCount">
        {{ $products->count() }} produk
    </span>
</div>

{{-- ── PRODUCTS GRID ── --}}
<div class="kl-section-hd">
    <h3>Semua Produk</h3>
</div>

<div class="kl-grid" id="klGrid">
    @forelse($products as $product)
    @php
        $emoji = $emojiMap[$product->category] ?? '📦';
        $isAvail = $product->stock > 0;
        $isLow   = $product->stock > 0 && $product->stock <= 3;
        $statusClass = !$isAvail ? 'kl-status-out' : ($isLow ? 'kl-status-low' : 'kl-status-ok');
        $statusLabel = !$isAvail ? 'Habis' : ($isLow ? 'Hampir habis' : 'Tersedia');
    @endphp
    <div class="kl-card"
         data-name="{{ strtolower($product->name) }}"
         data-cat="{{ $product->category }}"
         data-price="{{ $product->price }}"
         data-stock="{{ $product->stock }}">

        {{-- Image --}}
        <div class="kl-img-wrap">
            @if($product->image)
                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
            @else
                <span class="kl-img-emoji">{{ $emoji }}</span>
            @endif
            <span class="kl-status {{ $statusClass }}">{{ $statusLabel }}</span>
            <span class="kl-cat-tag">{{ $product->category }}</span>
        </div>

        {{-- Body --}}
        <div class="kl-body">
            <div class="kl-name">{{ $product->name }}</div>
            <div class="kl-meta">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
                {{-- {{ $product->id }} --}}
                {{-- <span class="kl-dot"></span> --}}
                Stok: {{ $product->stock }}
            </div>
            <div class="kl-price">
                Rp {{ number_format($product->price, 0, ',', '.') }}
                <span>/hari</span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="kl-footer">
            @if($isAvail)
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="kl-add-form">
                    @csrf
                    <button type="submit" class="kl-btn-add">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah ke Keranjang
                    </button>
                </form>
            @else
                <button class="kl-btn-out" disabled>Stok Habis</button>
            @endif
        </div>
    </div>
    @empty
    <div class="kl-empty">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
        <p>Belum ada produk tersedia.</p>
    </div>
    @endforelse
</div>

{{-- Empty state when filter returns nothing --}}
<div id="klEmptyFilter" style="display:none;" class="kl-empty">
    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <p>Tidak ada produk yang sesuai.</p>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const grid      = document.getElementById('klGrid');
    const emptyBox  = document.getElementById('klEmptyFilter');
    const countEl   = document.getElementById('klCount');
    const searchEl  = document.getElementById('klSearchInput');
    const sortEl    = document.getElementById('klSort');
    const catBtns   = document.querySelectorAll('.kl-cat');

    let activeCat  = 'Semua';
    let searchTerm = '';
    let sortMode   = 'default';

    function getCards() {
        return Array.from(grid.querySelectorAll('.kl-card'));
    }

    function applyFilters() {
        let cards = getCards();

        // Category filter
        cards.forEach(c => {
            const catMatch = activeCat === 'Semua' || c.dataset.cat === activeCat;
            const nameMatch = c.dataset.name.includes(searchTerm.toLowerCase());
            c.style.display = (catMatch && nameMatch) ? '' : 'none';
        });

        // Sort
        let visible = cards.filter(c => c.style.display !== 'none');
        visible.sort((a, b) => {
            if (sortMode === 'price_asc')  return +a.dataset.price - +b.dataset.price;
            if (sortMode === 'price_desc') return +b.dataset.price - +a.dataset.price;
            if (sortMode === 'name_asc')   return a.dataset.name.localeCompare(b.dataset.name);
            if (sortMode === 'stock_desc') return +b.dataset.stock - +a.dataset.stock;
            return 0;
        });
        visible.forEach(c => grid.appendChild(c));

        // Count + empty state
        const visibleCount = visible.length;
        countEl.textContent = visibleCount + ' produk';
        emptyBox.style.display = visibleCount === 0 ? 'flex' : 'none';
    }

    // Category click
    catBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            catBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeCat = btn.dataset.cat;
            applyFilters();
        });
    });

    // Search input (debounce)
    let debounce;
    searchEl.addEventListener('input', function () {
        clearTimeout(debounce);
        debounce = setTimeout(() => {
            searchTerm = this.value.trim();
            applyFilters();
        }, 250);
    });

    // Sort change
    sortEl.addEventListener('change', function () {
        sortMode = this.value;
        applyFilters();
    });
})();

/* ── AJAX Add-to-Cart ── */
(function () {
    // ── Toast helper ──
    function showToast(message, isSuccess) {
        const toast = document.createElement('div');
        toast.textContent = message;
        toast.style.cssText = [
            'position:fixed',
            'bottom:90px',
            'right:24px',
            'z-index:9999',
            'padding:12px 20px',
            'border-radius:10px',
            'font-weight:600',
            'font-size:.88rem',
            'color:#fff',
            'box-shadow:0 8px 24px rgba(0,0,0,.15)',
            'animation:slideUp .3s ease',
            'background:' + (isSuccess ? '#16a34a' : '#dc2626'),
        ].join(';');
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }

    // ── Cart badge update ──
    function updateCartBadge(count) {
        const badge = document.getElementById('cart-badge-topbar');
        if (!badge) return;
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'flex';
        } else {
            badge.textContent = '';
            badge.style.display = 'none';
        }
        // Also update any other cart badge elements on the page
        document.querySelectorAll('.cart-badge-count').forEach(el => {
            el.textContent = count;
        });
    }

    // ── Intercept kl-add-form submissions ──
    document.addEventListener('submit', function (e) {
        const form = e.target.closest('.kl-add-form');
        if (!form) return;

        e.preventDefault();

        const btn         = form.querySelector('button[type="submit"]');
        const originalText = btn ? btn.innerHTML : '';
        const csrfToken   = new FormData(form).get('_token');
        const actionUrl   = form.getAttribute('action');

        // Disable button while fetching
        if (btn) btn.disabled = true;

        fetch(actionUrl, {
            method: 'POST',
            headers: {
                'Content-Type':  'application/json',
                'Accept':        'application/json',
                'X-CSRF-TOKEN':  csrfToken,
            },
            body: JSON.stringify({ _token: csrfToken }),
        })
        .then(res => res.json())
        .then(data => {
            if (btn) btn.disabled = false;

            if (data.success === true) {
                // Update cart badge
                if (data.cart_count !== undefined) {
                    updateCartBadge(data.cart_count);
                }

                // Show green toast
                showToast(data.message || 'Produk ditambahkan ke keranjang!', true);

                // Temporarily change button text
                if (btn) {
                    btn.innerHTML = '✓ Ditambahkan!';
                    setTimeout(() => { btn.innerHTML = originalText; }, 1500);
                }
            } else {
                // Show red toast
                showToast(data.message || 'Gagal menambahkan produk.', false);
            }
        })
        .catch(() => {
            if (btn) btn.disabled = false;
            showToast('Terjadi kesalahan. Coba lagi.', false);
        });
    });
})();
</script>
@endpush
