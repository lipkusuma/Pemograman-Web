@extends('layouts.app')

@section('title', 'Edit Profil')

@php
    $currentPage = 'profile';
    $role = session('role', 'user');
    $profilePic = $user->profile_pic ?? session('profile_pic');

    $completionItems = [
        'account'  => ['label' => 'Setup akun',      'pct' => 10, 'done' => true],
        'photo'    => ['label' => 'Upload foto',      'pct' => 15, 'done' => !empty($profilePic)],
        'personal' => ['label' => 'Info Pribadi',     'pct' => 20, 'done' => !empty($user->name) && !empty($user->email) && !empty($user->phone)],
        'bio'      => ['label' => 'Bio / Deskripsi',  'pct' => 20, 'done' => !empty($user->bio)],
        'password' => ['label' => 'Password diatur',  'pct' => 35, 'done' => !empty($user->password)],
    ];
    $totalPct = 0;
    foreach ($completionItems as $item) {
        if ($item['done']) $totalPct += $item['pct'];
    }
    $progressColor = $totalPct < 40 ? '#ef4444' : ($totalPct < 75 ? '#f59e0b' : '#22c55e');
    $circumference = round(2 * 3.14159 * 52, 2);
    $dashOffset    = round($circumference * (1 - $totalPct / 100), 2);
@endphp

@push('styles')
<style>
.ep-wrapper {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 24px;
    align-items: start;
    max-width: 1080px;
    margin: 0 auto;
    padding-bottom: 48px;
}
@media (max-width: 860px) {
    .ep-wrapper { grid-template-columns: 1fr; }
}
.ep-card {
    background: var(--card-bg, #fff);
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 14px;
    padding: 26px;
    margin-bottom: 18px;
}
.ep-card:last-child { margin-bottom: 0; }

/* Photo row */
.ep-photo-row { display: flex; align-items: center; gap: 22px; flex-wrap: wrap; }
.ep-avatar {
    width: 100px; height: 100px; border-radius: 50%;
    object-fit: cover; border: 3px solid var(--border-color, #e2e8f0);
    flex-shrink: 0; cursor: pointer; transition: opacity .2s;
}
.ep-avatar:hover { opacity: .82; }
.ep-avatar-ph {
    width: 100px; height: 100px; border-radius: 50%;
    background: #e2e8f0; display: flex; align-items: center;
    justify-content: center; flex-shrink: 0; cursor: pointer;
    border: 3px solid var(--border-color, #e2e8f0);
}
.ep-photo-meta { flex: 1; min-width: 160px; }
.ep-photo-meta h3 { font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0 0 4px; }
.ep-role-badge {
    display: inline-block; padding: 3px 12px; border-radius: 999px;
    font-size: .73rem; font-weight: 600; margin-bottom: 10px;
}
.badge-admin { background: #dbeafe; color: #1d4ed8; }
.badge-user  { background: #dcfce7; color: #166534; }
.btn-upload-photo {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 16px; background: var(--card-bg, #fff);
    border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 8px; font-weight: 600; font-size: .85rem;
    color: var(--text-main); cursor: pointer; transition: .2s;
}
.btn-upload-photo:hover { border-color: #2563eb; color: #2563eb; background: #eff6ff; }
.ep-photo-hint { font-size: .78rem; color: var(--text-muted, #94a3b8); margin-top: 5px; }

/* Section header */
.ep-sh {
    display: flex; 
    align-items: center;
    justify-content: space-between; 
    margin-bottom: 30px;
}
.ep-sh h4 { font-size: 1rem; font-weight: 700; color: var(--text-main); margin: 0; }
.btn-et {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 14px; background: transparent;
    border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 8px; font-size: .83rem; font-weight: 600;
    color: var(--text-muted, #64748b); cursor: pointer; transition: .2s;
}
.btn-et:hover { border-color: #2563eb; color: #2563eb; }
.btn-cancel {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 6px 14px; background: transparent; border: none;
    font-size: .83rem; font-weight: 600;
    color: var(--text-muted, #64748b); cursor: pointer; transition: .2s;
}
.btn-cancel:hover { color: #ef4444; }

/* Info grid */
.ep-ig { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; }
@media (max-width: 560px) { .ep-ig { grid-template-columns: 1fr; } }
.ep-ig-item label { font-size: .78rem; color: var(--text-muted,#94a3b8); display: block; margin-bottom: 3px; }
.ep-ig-item span { font-size: .92rem; font-weight: 600; color: var(--text-main); }

/* Form */
.ep-fg { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 14px; }
@media (max-width: 560px) { .ep-fg { grid-template-columns: 1fr; } }
.ep-fg-2 { display: grid; grid-template-columns: repeat(3,1fr); gap: 14px; margin-bottom: 14px; }
@media (max-width: 560px) { .ep-fg-2 { grid-template-columns: 1fr; } }
.ep-f label { display: block; font-size: .8rem; font-weight: 600; color: var(--text-muted,#64748b); margin-bottom: 5px; }
.ep-f input, .ep-f textarea {
    width: 100%; border: 1.5px solid var(--border-color,#e2e8f0);
    border-radius: 8px; padding: 9px 13px; font-size: .88rem;
    background: var(--card-bg,#fff); color: var(--text-main);
    transition: border-color .2s; box-sizing: border-box;
}
.ep-f input:focus, .ep-f textarea:focus {
    outline: none; border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.1);
}
.ep-f textarea { resize: vertical; min-height: 130px; }
.ep-fi { position: relative; }
.ep-fi input { padding-left: 38px; }
.ep-fi svg { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); pointer-events: none; }

/* Buttons */
.btn-save {
    padding: 9px 22px; background: #2563eb; color: #fff;
    border: none; border-radius: 8px; font-weight: 600;
    font-size: .88rem; cursor: pointer; transition: background .2s;
}
.btn-save:hover { background: #1d4ed8; }

/* Bio */
.ep-bio-txt { font-size: .9rem; line-height: 1.75; color: var(--text-main); white-space: pre-wrap; }
.ep-bio-empty { font-size: .9rem; color: var(--text-muted,#94a3b8); font-style: italic; }

/* Admin user table */
.ep-ut { width: 100%; border-collapse: collapse; font-size: .86rem; }
.ep-ut th {
    text-align: left; padding: 9px 11px;
    border-bottom: 2px solid var(--border-color,#e2e8f0);
    color: var(--text-muted,#64748b); font-weight: 600;
    font-size: .78rem; text-transform: uppercase; letter-spacing: .04em;
}
.ep-ut td { padding: 11px 11px; border-bottom: 1px solid var(--border-color,#e2e8f0); color: var(--text-main); vertical-align: middle; }
.ep-ut tr:last-child td { border-bottom: none; }
.ep-mav { width:34px; height:34px; border-radius:50%; object-fit:cover; border:2px solid var(--border-color,#e2e8f0); }
.ep-mph { width:34px; height:34px; border-radius:50%; background:#e2e8f0; display:flex; align-items:center; justify-content:center; }

/* Progress ring */
.ep-comp-card {
    background: var(--card-bg,#fff);
    border: 1px solid var(--border-color,#e2e8f0);
    border-radius: 14px; padding: 26px 22px;
    text-align: center; margin-bottom: 18px;
}
.ep-comp-card h4 { font-size: .98rem; font-weight: 700; color: var(--text-main); margin: 0 0 8px; }
.ep-ring-wrap { position: relative; width: 128px; height: 128px; margin: 0 auto 20px; }
.ep-ring-wrap svg { transform: rotate(-90deg); }
.ep-ring-pct {
    position: absolute; top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.45rem; font-weight: 800; color: var(--text-main);
}
.ep-cl { list-style: none; padding: 0; margin: 0; text-align: left; }
.ep-cl li {
    display: flex; align-items: center; justify-content: space-between;
    padding: 7px 0; border-bottom: 1px solid var(--border-color,#f1f5f9);
    font-size: .86rem;
}
.ep-cl li:last-child { border-bottom: none; }
.ep-cl-l { display: flex; align-items: center; gap: 9px; color: var(--text-main); font-weight: 500; }
.ep-ck-done { color: #22c55e; }
.ep-ck-miss { color: #d1d5db; }
.ep-pd { color: var(--text-muted,#94a3b8); font-size: .78rem; }
.ep-pm { color: #22c55e; font-size: .78rem; font-weight: 600; }

/* Alert */
.ep-alert { padding: 11px 15px; border-radius: 8px; margin-bottom: 18px; font-size: .88rem; }
.ep-alert-ok  { background: #dcfce7; color: #166534; }
.ep-alert-err { background: #fee2e2; color: #991b1b; }
</style>
@endpush

@section('topbar')
<div style="display:flex;align-items:center;gap:16px;">
    <button class="menu-toggle">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
    </button>
    <h2 style="font-size:1.2rem;font-weight:700;color:var(--text-main);margin:0;">Edit Profil</h2>
</div>
<div class="topbar-actions">
    @if($role === 'user')
    <a href="{{ route('cart.index') }}" class="action-icon" title="Keranjang" style="position:relative;display:inline-block;">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        @php $cartCount = \App\Models\Cart::where('user_id', session('user_id'))->sum('qty'); @endphp
        @if($cartCount > 0)
        <span style="position:absolute;top:-7px;right:-7px;background:#ef4444;color:#fff;border-radius:50%;font-size:.6rem;width:15px;height:15px;display:flex;align-items:center;justify-content:center;font-weight:700;">{{ $cartCount }}</span>
        @endif
    </a>
    @endif
    <div class="profile-circle">
        @if($profilePic)
            <img src="{{ asset('uploads/' . $profilePic) }}" alt="Profile">
        @endif
    </div>
</div>
@endsection

@section('content')

{{-- Global alert --}}
@if(session('pesan_sukses'))
<div class="ep-alert ep-alert-ok" id="ep-global-alert">
    <strong>Berhasil!</strong> {!! session('pesan_sukses') !!}
</div>
@endif
@if(session('pesan_error'))
<div class="ep-alert ep-alert-err" id="ep-global-alert">
    <strong>Gagal!</strong> {!! session('pesan_error') !!}
</div>
@endif

<div style="font-size:1.3rem;font-weight:800;color:var(--text-main);margin-bottom:20px;">
    Edit Profil
    @if($role === 'admin')
        <span style="font-size:.78rem;color:#1d4ed8;background:#dbeafe;padding:3px 12px;border-radius:999px;font-weight:600;margin-left:10px;vertical-align:middle;">Admin</span>
    @endif
</div>

<div class="ep-wrapper">

    {{-- LEFT COLUMN --}}
    <div>

        {{-- ─── PHOTO CARD ──────────────────────── --}}
        <div class="ep-card">
            <div class="ep-photo-row">
                {{-- Avatar --}}
                @if($profilePic)
                    <img src="{{ asset('uploads/' . $profilePic) }}" alt="Foto Profil" class="ep-avatar" onclick="document.getElementById('fotoInput').click()">
                @else
                    <div class="ep-avatar-ph" onclick="document.getElementById('fotoInput').click()">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="#94a3b8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                @endif

                <div class="ep-photo-meta">
                    <h3>{{ $user->name ?? session('username') }}</h3>
                    <span class="ep-role-badge {{ $role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                        {{ $role === 'admin' ? 'Administrator' : 'Member' }}
                    </span>
                    <br>
                    <form action="{{ route('profile.upload') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                        @csrf
                        <input type="file" name="foto" id="fotoInput" accept=".jpg,.png" style="display:none;" onchange="previewAndSubmit(this)">
                        <button type="button" class="btn-upload-photo" onclick="document.getElementById('fotoInput').click()">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            Upload foto baru
                        </button>
                    </form>
                    <p class="ep-photo-hint">Min. 800x800px. JPG atau PNG.</p>
                </div>

                {{-- Preview (hidden until file chosen) --}}
                <div id="previewWrap" style="display:none; flex-direction:column; align-items:center; gap:8px;">
                    <img id="previewImg" src="" alt="Preview" style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid #2563eb;">
                    <button type="button" class="btn-save" style="font-size:.8rem;padding:6px 16px;" onclick="document.getElementById('photoForm').submit()">Simpan Foto</button>
                    <button type="button" class="btn-cancel" style="font-size:.8rem;" onclick="cancelPreview()">Batal</button>
                </div>
            </div>
        </div>

        {{-- ─── PERSONAL INFO & BIO ────────────────────── --}}
        <div class="ep-card">
            <div class="ep-sh">
                <h4>Informasi Pribadi & Bio</h4>
                <button class="btn-et" onclick="toggleSection('personal')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    Edit
                </button>
            </div>

            {{-- View mode --}}
            <div id="personal-view">
                <div class="ep-ig" style="margin-bottom: 20px;">
                    <div class="ep-ig-item">
                        <label>Nama Lengkap</label>
                        <span>{{ $user->name ?? '-' }}</span>
                    </div>
                    <div class="ep-ig-item">
                        <label>Email</label>
                        <span>{{ $user->email ?? '-' }}</span>
                    </div>
                    <div class="ep-ig-item">
                        <label>No. Telepon</label>
                        <span>{{ $user->phone ?? '-' }}</span>
                    </div>
                </div>
                <div style="border-top: 1px solid var(--border-color, #e2e8f0); padding-top: 18px;">
                    <label style="font-size: .78rem; color: var(--text-muted,#94a3b8); display: block; margin-bottom: 6px;">Bio</label>
                    @if(!empty($user->bio))
                        <p class="ep-bio-txt" style="margin: 0;">{{ $user->bio }}</p>
                    @else
                        <p class="ep-bio-empty" style="margin: 0;">Belum ada bio. Klik Edit untuk menambahkan deskripsi diri kamu.</p>
                    @endif
                </div>
            </div>

            {{-- Edit mode --}}
            <div id="personal-edit" style="display:none;">
                <form action="{{ route('profile.updatePersonalInfo') }}" method="POST">
                    @csrf
                    <div class="ep-fg">
                        <div class="ep-f">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Nama lengkap" required>
                        </div>
                        <div class="ep-f">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="email@gmail.com" required>
                        </div>
                        <div class="ep-f">
                            <label>No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xxxxxxxxxx" required>
                        </div>
                    </div>
                    <div class="ep-f" style="margin-bottom:14px;">
                        <label>Tulis sesuatu tentang dirimu (Bio)</label>
                        <textarea name="bio" placeholder="Halo! Saya adalah...">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button type="submit" class="btn-save">Simpan Perubahan</button>
                        <button type="button" class="btn-cancel" onclick="toggleSection('personal')">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── PASSWORD ─────────────────────────── --}}
        <div class="ep-card">
            <div class="ep-sh">
                <h4>Ubah Password</h4>
                <button class="btn-et" onclick="toggleSection('pw')">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Ubah
                </button>
            </div>

            <div id="pw-view">
                <p style="font-size:.88rem;color:var(--text-muted,#94a3b8);">Password tersimpan dengan aman. Klik Ubah untuk mengganti password.</p>
            </div>

            <div id="pw-edit" style="display:none;">
                <form action="{{ route('profile.updatePassword') }}" method="POST">
                    @csrf
                    <div class="ep-fg-2">
                        <div class="ep-f">
                            <label>Password Saat Ini</label>
                            <input type="password" name="current_password" placeholder="••••••••" required>
                        </div>
                        <div class="ep-f">
                            <label>Password Baru</label>
                            <input type="password" name="new_password" placeholder="min. 6 karakter" required>
                        </div>
                        <div class="ep-f">
                            <label>Konfirmasi Password</label>
                            <input type="password" name="confirm_password" placeholder="ulangi password baru" required>
                        </div>
                    </div>
                    <div style="display:flex;gap:10px;">
                        <button type="submit" class="btn-save">Simpan Password</button>
                        <button type="button" class="btn-cancel" onclick="toggleSection('pw')">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ─── ADMIN ONLY: DAFTAR USER ─────────── --}}
        @if($role === 'admin')
        @php
            $allUsers = \App\Models\User::where('role','user')->orderBy('created_at','desc')->get();
        @endphp
        <div class="ep-card">
            <div class="ep-sh">
                <h4>Daftar Pengguna Terdaftar</h4>
                <span style="font-size:.82rem;color:var(--text-muted);font-weight:500;">Total: {{ $allUsers->count() }} user</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="ep-ut">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allUsers as $i => $u)
                        <tr>
                            <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
                            <td>
                                @if($u->profile_pic)
                                    <img src="{{ asset('uploads/' . $u->profile_pic) }}" alt="" class="ep-mav">
                                @else
                                    <div class="ep-mph">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="#94a3b8"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight:600;">{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->phone ?? '-' }}</td>
                            <td style="color:var(--text-muted);font-size:.8rem;">{{ $u->created_at->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align:center;color:var(--text-muted);padding:24px;">Belum ada pengguna terdaftar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>{{-- end left --}}

    {{-- RIGHT COLUMN --}}
    <div class="ep-right-panel">

        {{-- Account Info Card --}}
        <div class="ep-comp-card" style="text-align:left;">
            <h4 style="margin-bottom:14px;">Informasi Akun</h4>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <div style="display:flex;justify-content:space-between;font-size:.85rem;">
                    <span style="color:var(--text-muted);">Username</span>
                    <span style="font-weight:600;color:var(--text-main);">{{ $user->username }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.85rem;">
                    <span style="color:var(--text-muted);">Role</span>
                    <span class="ep-role-badge {{ $role === 'admin' ? 'badge-admin' : 'badge-user' }}" style="font-size:.72rem;padding:2px 10px;">
                        {{ ucfirst($role) }}
                    </span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:.85rem;">
                    <span style="color:var(--text-muted);">Bergabung</span>
                    <span style="font-weight:600;color:var(--text-main);">{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</span>
                </div>
                @if($role === 'admin')
                <div style="border-top:1px solid var(--border-color,#e2e8f0);padding-top:10px;margin-top:4px;">
                    <div style="display:flex;justify-content:space-between;font-size:.85rem;">
                        <span style="color:var(--text-muted);">Total User</span>
                        <span style="font-weight:700;color:#2563eb;">{{ \App\Models\User::where('role','user')->count() }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:.85rem;margin-top:8px;">
                        <span style="color:var(--text-muted);">Total Transaksi</span>
                        <span style="font-weight:700;color:#2563eb;">{{ \App\Models\Transaction::count() }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Progress card --}}

        <div class="ep-comp-card">
            <h4>Lengkapi profilmu</h4>
            <div class="ep-ring-wrap">
                <svg width="128" height="128" viewBox="0 0 128 128">
                    <circle cx="64" cy="64" r="52" fill="none" stroke="#e2e8f0" stroke-width="12"/>
                    <circle cx="64" cy="64" r="52" fill="none"
                        stroke="{{ $progressColor }}"
                        stroke-width="12"
                        stroke-linecap="round"
                        stroke-dasharray="{{ $circumference }}"
                        stroke-dashoffset="{{ $dashOffset }}"/>
                </svg>
                <div class="ep-ring-pct">{{ $totalPct }}%</div>
            </div>
            
            <ul class="ep-cl">
                @foreach($completionItems as $item)
                <li>
                    <span class="ep-cl-l">
                        @if($item['done'])
                            <svg class="ep-ck-done" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                            <svg class="ep-ck-miss" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        @endif
                        {{ $item['label'] }}
                    </span>
                    @if($item['done'])
                        <span class="ep-pd">{{ $item['pct'] }}%</span>
                    @else
                        <span class="ep-pm">+{{ $item['pct'] }}%</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>

    </div>{{-- end right --}}

</div>{{-- end wrapper --}}

@endsection

@push('scripts')
<script>
// Toggle edit/view mode
function toggleSection(key) {
    const view = document.getElementById(key + '-view');
    const edit = document.getElementById(key + '-edit');
    if (!view || !edit) return;
    const isEditing = edit.style.display !== 'none';
    view.style.display = isEditing ? 'block' : 'none';
    edit.style.display = isEditing ? 'none' : 'block';
}

// Photo preview
function previewAndSubmit(input) {
    const file = input.files[0];
    if (!file) return;
    if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
        alert('Hanya file .jpg atau .png yang diperbolehkan!');
        input.value = '';
        return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('previewWrap').style.display = 'flex';
    };
    reader.readAsDataURL(file);
}

function cancelPreview() {
    document.getElementById('fotoInput').value = '';
    document.getElementById('previewWrap').style.display = 'none';
    document.getElementById('previewImg').src = '';
}

// Auto-open section if there was an error/success from that section
@if(session('_section'))
    toggleSection('{{ session("_section") }}');
@endif

// Auto dismiss alert
setTimeout(function() {
    const a = document.getElementById('ep-global-alert');
    if (a) { a.style.transition = 'opacity .5s'; a.style.opacity = '0'; setTimeout(() => a.remove(), 500); }
}, 4000);
</script>
@endpush
