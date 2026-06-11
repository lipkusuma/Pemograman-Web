@php
    $role        = session('role', 'user');
    $currentPage = $currentPage ?? '';
@endphp

<aside class="sidebar">
    <div class="sidebar-logo">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M8 8H16L24 22V8H32V32H24L16 18V32H8V8Z"/>
        </svg>
    </div>

    <nav class="sidebar-menu">
        @if($role === 'admin')
            {{-- Menu Admin --}}
            <a href="{{ route('dashboard') }}" class="sidebar-item {{ $currentPage === 'dashboard' ? 'active' : '' }}">
                {{-- <i class="fa-regular fa-house"></i> --}}
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Dashboard
            </a>
            <a href="{{ route('stok_barang') }}" class="sidebar-item {{ $currentPage === 'stok_barang' ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                Stok Barang
            </a>
            <a href="{{ route('laporan') }}" class="sidebar-item {{ $currentPage === 'laporan' ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Laporan
            </a>
            <a href="{{ route('admin.support.index') }}" class="sidebar-item {{ $currentPage === 'admin_support' ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H8l-5 3V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Support Chat
                <span id="support-badge" style="display:none; background:#e74c3c; color:#fff; padding:2px 6px; border-radius:12px; font-size:12px; margin-left:8px;">0</span>
            </a>
        @else
            {{-- Menu User --}}
            <a href="{{ route('katalog') }}" class="sidebar-item {{ $currentPage === 'katalog' ? 'active' : '' }}">
                {{-- <i class="fa-regular fa-house"></i> --}}
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Beranda
            </a>
            <a href="{{ route('transaksi') }}" class="sidebar-item {{ $currentPage === 'transaksi' ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                Transaksi
            </a>
            <a href="{{ route('support.chat') }}" class="sidebar-item {{ $currentPage === 'support_chat' ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H8l-5 3V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                Support Chat
                <span id="support-badge" style="display:none; background:#e74c3c; color:#fff; padding:2px 6px; border-radius:12px; font-size:12px; margin-left:8px;">0</span>
            </a>
        @endif

        {{-- Profil Saya (semua role) --}}
        <a href="{{ route('profile') }}" class="sidebar-item {{ $currentPage === 'profile' ? 'active' : '' }}">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profil Saya
        </a>
    </nav>

    <div class="sidebar-footer">
        {{-- Dark Mode Toggle --}}
        <button class="sidebar-item dark-mode-toggle" id="darkModeToggle" onclick="toggleDarkMode()">
            <svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
            <svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
            <span class="dark-mode-label">Dark Mode</span>
        </button>

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
            @csrf
            <button type="submit" class="sidebar-item" style="width:100%; text-align:left;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>
