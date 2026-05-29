<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Instrument Sans', Arial, sans-serif; 
        background-color: #E2E8F0; 
        color: #020617; overflow-x: hidden; 
        position: relative; min-height: 100vh; }
        a { text-decoration: none; color: inherit; }
        ul { list-style: none; }
        button { cursor: pointer; border: none; outline: none; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
        .btn { padding: 12px 32px; border-radius: 24px; font-weight: 600; font-size: 1rem; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 4px 14px 0 rgba(0,0,0,0.1); }
        .btn:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.15); transform: translateY(-2px); }
        .btn-white { background-color: #f8fafc; color: #0f172a; }
        .btn-dark { background-color: #1e293b; color: #ffffff; }
        .bg-layer { position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; overflow: hidden; }

        /* NAVBAR */
        .navbar { display: flex; align-items: center; justify-content: space-between; padding: 0 40px; height: 64px; background: #ffffff; border-bottom: 1px solid #e2e8f0; position: relative; z-index: 100; width: 100%; }
        .nav-left { display: flex; align-items: center; gap: 48px; }
        .logo-wrap { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-box { width: 36px; height: 36px; background: #0f172a; border-radius: 8px; display: flex; align-items: center; justify-content: center; }
        .logo-name { font-size: 1rem; font-weight: 700; color: #0f172a; letter-spacing: -0.3px; }
        .nav-links { display: flex; align-items: center; gap: 4px; list-style: none; }
        .nav-links a { display: flex; align-items: center; gap: 6px; font-size: 0.875rem; font-weight: 500; color: #475569; padding: 6px 12px; border-radius: 7px; text-decoration: none; transition: background 0.18s, color 0.18s; }
        .nav-links a:hover { background: #f1f5f9; color: #0f172a; }
        .nav-links a.active { background: #eff6ff; color: #2563eb; }
        .nav-links a svg { width: 15px; height: 15px; opacity: 0.7; flex-shrink: 0; }
        .nav-badge { font-size: 0.65rem; font-weight: 700; background: #2563eb; color: #fff; padding: 1px 6px; border-radius: 20px; }
        .nav-right { display: flex; align-items: center; gap: 10px; }
        .btn-login { font-size: 0.875rem; font-weight: 600; color: #0f172a; padding: 7px 16px; border-radius: 8px; text-decoration: none; border: 1px solid #e2e8f0; transition: background 0.18s; }
        .btn-login:hover { background: #f8fafc; }
        .btn-register { display: flex; align-items: center; gap: 6px; font-size: 0.875rem; font-weight: 600; color: #fff; padding: 7px 18px; border-radius: 8px; text-decoration: none; background: #0f172a; transition: background 0.18s; }
        .btn-register:hover { background: #1e293b; }
        .user-pill { display: flex; align-items: center; gap: 8px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 6px 12px; }
        .user-avatar { width: 28px; height: 28px; border-radius: 6px; background: #2563eb; color: #fff; font-size: 0.7rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .user-avatar.admin { background: #7c3aed; }
        .user-info-name { font-size: 0.85rem; font-weight: 600; color: #0f172a; line-height: 1.2; }
        .user-info-role { font-size: 0.7rem; color: #94a3b8; line-height: 1.2; }
        .user-info-role.admin { color: #7c3aed; }
        .btn-logout { font-size: 0.8rem; font-weight: 600; color: #dc2626; padding: 6px 14px; border-radius: 7px; border: 1px solid #fecaca; background: #fff5f5; cursor: pointer; transition: background 0.18s; }
        .btn-logout:hover { background: #fee2e2; }

        /* MOBILE */
        .mobile-menu-btn { display: none; background: none; border: none; cursor: pointer; padding: 6px; }
        .mobile-menu { display: none; flex-direction: column; gap: 8px; padding: 16px; background: #ffffff; border-top: 1px solid #e2e8f0; position: relative; z-index: 99; width: 100%; }
        .mobile-menu.open { display: flex; }
        .mobile-menu a { display: flex; align-items: center; gap: 12px; font-weight: 600; font-size: 0.95rem; color: #475569; padding: 12px 16px; border-radius: 10px; text-decoration: none; transition: background 0.18s, color 0.18s; }
        .mobile-menu a:hover { background: #f1f5f9; color: #0f172a; }
        .mobile-menu a.active { background: #eff6ff; color: #2563eb; }
        .mobile-menu a svg { width: 18px; height: 18px; flex-shrink: 0; opacity: 0.6; }
        .mobile-menu-divider { height: 1px; background: #e2e8f0; margin: 8px 0; }
        .mobile-menu-btn-login { display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; font-size: 0.95rem; color: #0f172a; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0; text-decoration: none; transition: background 0.18s; }
        .mobile-menu-btn-login:hover { background: #f8fafc; }
        .mobile-menu-btn-register { display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 600; font-size: 0.95rem; color: #ffffff; padding: 12px 16px; border-radius: 10px; background: #0f172a; text-decoration: none; transition: background 0.18s; }
        .mobile-menu-btn-register:hover { background: #1e293b; }
        .mobile-menu-btn-logout { display: flex; align-items: center; gap: 12px; font-weight: 600; font-size: 0.95rem; color: #dc2626; padding: 12px 16px; border-radius: 10px; border: 1px solid #fecaca; background: #fff5f5; cursor: pointer; width: 100%; transition: background 0.18s; }
        .mobile-menu-btn-logout:hover { background: #fee2e2; }
        .mobile-menu-user { display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0; margin-bottom: 4px; }
        .mobile-menu-avatar { width: 36px; height: 36px; border-radius: 8px; background: #2563eb; color: #fff; font-size: 0.8rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .mobile-menu-avatar.admin { background: #7c3aed; }
        .mobile-menu-uname { font-size: 0.9rem; font-weight: 600; color: #0f172a; line-height: 1.2; }
        .mobile-menu-urole { font-size: 0.72rem; color: #94a3b8; }
        .mobile-menu-urole.admin { color: #7c3aed; }

        /* HERO */
        .hero { display: flex; align-items: center; min-height: calc(100vh - 150px); position: relative; z-index: 10; overflow: hidden; }
        .hero-content { max-width: 500px; margin-top: -50px; position: relative; z-index: 2; }
        .hero-title { font-size: 4rem; font-weight: 800; line-height: 1.1; margin-bottom: 24px; color: #000; }
        .hero-desc { display: flex; align-items: center; gap: 16px; font-size: 1rem; color: #334155; font-weight: 500; margin-bottom: 48px; line-height: 1.5; }
        .hero-desc-line { width: 48px; height: 1px; background-color: #334155; }
        .hero-image { position: absolute; right: 180px; bottom: 0; width: 560px; height: auto; z-index: 1; filter: drop-shadow(0 20px 40px rgba(0,0,0,0.15)); }

        /* STATS */
        .stats-section { background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border-top: 1px solid rgba(255,255,255,0.5); border-bottom: 1px solid rgba(255,255,255,0.5); padding: 48px 0; position: relative; z-index: 10; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; text-align: center; max-width: 700px; margin: 0 auto; }
        .stat-number { font-size: 2.2rem; font-weight: 800; color: #2563eb; }
        .stat-label { font-size: 0.85rem; color: #475569; margin-top: 4px; }

        /* FEATURES */
        .features-section { padding: 80px 0; position: relative; z-index: 10; }
        .section-label { text-align: center; color: #2563eb; font-weight: 700; font-size: 0.8rem; letter-spacing: 0.1em; margin-bottom: 12px; }
        .section-title { text-align: center; font-size: 2rem; font-weight: 800; color: #0f172a; margin-bottom: 12px; }
        .section-desc { text-align: center; color: #475569; font-size: 0.95rem; max-width: 480px; margin: 0 auto 48px; line-height: 1.7; }
        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .feature-card { background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); border-radius: 16px; padding: 28px; border: 1px solid rgba(255,255,255,0.9); box-shadow: 0 4px 16px rgba(0,0,0,0.06); transition: all 0.3s; }
        .feature-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,0.1); }
        .feature-icon { width: 48px; height: 48px; background: linear-gradient(135deg, #2563eb, #38bdf8); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; font-size: 1.4rem; }
        .feature-card h3 { font-size: 1rem; font-weight: 700; margin-bottom: 8px; color: #0f172a; }
        .feature-card p { font-size: 0.875rem; color: #475569; line-height: 1.6; }

        /* HOW IT WORKS */
        .how-section { padding: 80px 0; position: relative; z-index: 10; }
        .steps { max-width: 700px; margin: 0 auto; display: flex; flex-direction: column; gap: 28px; }
        .step { display: flex; gap: 20px; align-items: flex-start; background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border-radius: 12px; padding: 24px; border: 1px solid rgba(255,255,255,0.9); }
        .step-number { width: 44px; height: 44px; background: linear-gradient(135deg, #2563eb, #38bdf8); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1rem; flex-shrink: 0; }
        .step h3 { font-size: 1rem; font-weight: 700; margin-bottom: 6px; color: #0f172a; }
        .step p { font-size: 0.875rem; color: #475569; line-height: 1.6; }

        /* TESTIMONIALS */
        .testimonials-section { padding: 80px 0; position: relative; z-index: 10; }
        .testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .testimonial-card { background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); border-radius: 16px; padding: 28px; border: 1px solid rgba(255,255,255,0.9); box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
        .testimonial-stars { color: #f59e0b; font-size: 0.9rem; margin-bottom: 12px; }
        .testimonial-text { font-size: 0.875rem; color: #475569; line-height: 1.7; margin-bottom: 16px; }
        .testimonial-author { display: flex; align-items: center; gap: 12px; }
        .author-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #2563eb, #38bdf8); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink: 0; }
        .author-name { font-size: 0.875rem; font-weight: 700; color: #0f172a; }
        .author-role { font-size: 0.75rem; color: #94a3b8; }

        /* CTA */
        .cta-section { padding: 80px 0; position: relative; z-index: 10; text-align: center; }
        .cta-box { background: linear-gradient(135deg, #1e3a8a, #2563eb); border-radius: 24px; padding: 64px 48px; box-shadow: 0 20px 60px rgba(37,99,235,0.3); }
        .cta-box h2 { font-size: 2.2rem; font-weight: 800; color: white; margin-bottom: 12px; }
        .cta-box p { color: rgba(255,255,255,0.8); font-size: 1rem; margin-bottom: 32px; }
        .btn-cta { background: white; color: #2563eb; padding: 14px 36px; border-radius: 24px; font-weight: 700; font-size: 0.95rem; text-decoration: none; display: inline-block; transition: all 0.3s; box-shadow: 0 4px 14px rgba(0,0,0,0.1); }
        .btn-cta:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.15); }

        /* FOOTER */
        .footer { background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border-top: 1px solid rgba(255,255,255,0.5); padding: 32px 0; text-align: center; position: relative; z-index: 10; }
        .footer p { color: #475569; font-size: 0.85rem; }
        .footer span { color: #2563eb; }

        /* RESPONSIVE */
        @media (max-width: 1280px) { .hero-image { right: 60px; width: 460px; } }
        @media (max-width: 1024px) { .hero-image { right: 20px; width: 380px; } }
        @media (max-width: 768px) { .nav-links, .nav-right { display: none; } .mobile-menu-btn { display: flex; } .navbar { padding: 0 20px; } .nav-left { gap: 0; } .hero { flex-direction: column; justify-content: center; padding: 100px 0 40px; min-height: calc(100vh - 100px); } .hero-image { position: relative; right: auto; bottom: auto; width: 100%; max-width: 340px; display: block; margin: 32px auto 0; } .hero-title { font-size: 2.8rem; } .stats-grid { grid-template-columns: 1fr; gap: 16px; } .features-grid { grid-template-columns: 1fr; } .testimonials-grid { grid-template-columns: 1fr; } .cta-box { padding: 40px 24px; } .cta-box h2 { font-size: 1.6rem; } }
        @media (max-width: 480px) { .hero { padding: 90px 0 24px; } .hero-image { max-width: 260px; margin: 24px auto 0; } }
    </style>
</head>
<body>

    <!-- Background SVG Layer -->
    <div class="bg-layer">
        {{-- <svg style="position: absolute; top: 0; right: 0; width: 600px; height: 100%;" preserveAspectRatio="none" viewBox="0 0 500 1000">
            <path d="M500,0 L200,0 C150,150 300,300 200,500 C150,600 350,800 250,1000 L500,1000 Z" fill="#2563eb" opacity="0.8"/>
            <path d="M500,0 L300,0 C250,100 400,250 300,450 C250,550 450,750 350,1000 L500,1000 Z" fill="#38bdf8" opacity="0.5"/>
        </svg>
        <svg style="position: absolute; top: 50%; right: -100px; transform: translateY(-50%); width: 700px; height: 700px;" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" fill="none" stroke="#2dd4bf" stroke-width="8" opacity="0.3"/>
            <circle cx="50" cy="50" r="30" fill="none" stroke="#2563eb" stroke-width="12" opacity="0.5"/>
            <circle cx="50" cy="50" r="15" fill="#38bdf8" opacity="0.8"/>
        </svg>
        <svg style="position: absolute; top: 40%; left: 35%; width: 80px; height: 80px; filter: drop-shadow(0 10px 10px rgba(0,0,0,0.2));" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="40" fill="#2563eb"/>
            <circle cx="50" cy="50" r="20" fill="#22d3ee"/>
        </svg>
        <svg style="position: absolute; top: 65%; left: 30%; width: 90px; height: 90px; filter: drop-shadow(0 10px 10px rgba(0,0,0,0.2));" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="40" fill="#1e3a8a"/>
            <circle cx="50" cy="50" r="20" fill="#22d3ee"/>
        </svg>
        <svg style="position: absolute; bottom: 0; left: 0; width: 400px; height: 300px;" preserveAspectRatio="none" viewBox="0 0 400 300">
            <path d="M0,300 L0,150 C50,150 100,250 200,200 C300,150 350,250 400,250 L400,300 Z" fill="#22d3ee" opacity="0.8"/>
        </svg> --}}
    </div>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-left">
            <a href="{{ route('home') }}" class="logo-wrap">
                <div class="logo-box">
                    <svg width="22" height="22" viewBox="0 0 40 40" fill="none">
                        <path d="M8 8H16L24 22V8H32V32H24L16 18V32H8V8Z" fill="white"/>
                    </svg>
                </div>
                <span class="logo-name">Alips Camp</span>
            </a>
            <ul class="nav-links">
                <li>
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        Home
                    </a>
                </li>
                <li>
                    @if(session()->has('username'))
                        <a href="{{ session('role') === 'admin' ? route('dashboard') : route('katalog') }}" class="{{ request()->routeIs('dashboard', 'katalog') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            {{ session('role') === 'admin' ? 'Dashboard' : 'Katalog' }}
                        </a>
                    @else
                        <a href="{{ route('login') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                            Katalog
                            <span class="nav-badge">New</span>
                        </a>
                    @endif
                </li>
            </ul>
        </div>

        <div class="nav-right">
            @if(session()->has('username'))
                @php
                    $name = session('name', session('username'));
                    $role = session('role');
                    $initials = strtoupper(substr($name, 0, 1)) . (str_contains($name, ' ') ? strtoupper(substr(strstr($name, ' '), 1, 1)) : '');
                    $isAdmin = $role === 'admin';
                @endphp
                <div class="user-pill">
                    <div class="user-avatar {{ $isAdmin ? 'admin' : '' }}">{{ $initials }}</div>
                    <div>
                        <div class="user-info-name">{{ $name }}</div>
                        <div class="user-info-role {{ $isAdmin ? 'admin' : '' }}">{{ $isAdmin ? 'Administrator' : 'Member' }}</div>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-logout">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn-login">Login</a>
                <a href="{{ route('register') }}" class="btn-register">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/></svg>
                    Register
                </a>
            @endif
        </div>

        <button class="mobile-menu-btn" onclick="toggleMenu()" aria-label="Toggle menu">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 12h18M3 6h18M3 18h18" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </nav>

    <!-- MOBILE MENU — di luar nav -->
    <div class="mobile-menu" id="mobileMenu">
        @if(session()->has('username'))
            @php
                $name = session('name', session('username'));
                $role = session('role');
                $initials = strtoupper(substr($name, 0, 1)) . (str_contains($name, ' ') ? strtoupper(substr(strstr($name, ' '), 1, 1)) : '');
                $isAdmin = $role === 'admin';
            @endphp
            <div class="mobile-menu-user">
                <div class="mobile-menu-avatar {{ $isAdmin ? 'admin' : '' }}">{{ $initials }}</div>
                <div>
                    <div class="mobile-menu-uname">{{ $name }}</div>
                    <div class="mobile-menu-urole {{ $isAdmin ? 'admin' : '' }}">{{ $isAdmin ? 'Administrator' : 'Member' }}</div>
                </div>
            </div>
            <div class="mobile-menu-divider"></div>
            <a href="{{ route('home') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Home
            </a>
            <a href="{{ $isAdmin ? route('dashboard') : route('katalog') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                {{ $isAdmin ? 'Dashboard' : 'Katalog' }}
            </a>
            <a href="#">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                About
            </a>
            <div class="mobile-menu-divider"></div>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="mobile-menu-btn-logout">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </button>
            </form>
        @else
            <a href="{{ route('home') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Home
            </a>
            <a href="{{ route('login') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                Katalog
            </a>
            <a href="#">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                About
            </a>
            <div class="mobile-menu-divider"></div>
            <a href="{{ route('login') }}" class="mobile-menu-btn-login">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Login
            </a>
            <a href="{{ route('register') }}" class="mobile-menu-btn-register">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/></svg>
                Daftar Sekarang
            </a>
        @endif
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- HERO -->
        <main class="hero">
            <div class="hero-content">
                <h1 class="hero-title">Welcome<br>Alips Camp</h1>
                <div class="hero-desc">
                    <div class="hero-desc-line"></div>
                    <p>Temukan,<br>Petualangan seru disini</p>
                </div>
                @if(session()->has('username'))
                    @if(session('role') === 'admin')
                        <a href="{{ route('dashboard') }}" class="btn btn-white">Dashboard</a>
                    @else
                        <a href="{{ route('katalog') }}" class="btn btn-white">Mulai Sewa</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-white">Login</a>
                @endif
            </div>
            <img src="{{ asset('images/1234.webp') }}" alt="Tenda Camping" class="hero-image">
        </main>
    </div>

    <!-- STATS -->
    <div class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div><div class="stat-number">500+</div><div class="stat-label">Produk Tersedia</div></div>
                <div><div class="stat-number">1K+</div><div class="stat-label">Pelanggan Puas</div></div>
                <div><div class="stat-number">5+</div><div class="stat-label">Tahun Pengalaman</div></div>
            </div>
        </div>
    </div>

    <!-- FEATURES -->
    <div class="features-section">
        <div class="container">
            <div class="section-label">LAYANAN KAMI</div>
            <div class="section-title">Mengapa Memilih Kami?</div>
            <div class="section-desc">Kami menyediakan layanan terbaik dengan kualitas terjamin dan harga yang terjangkau untuk semua kebutuhan Anda.</div>
            <div class="features-grid">
                <div class="feature-card"><div class="feature-icon">⚡</div><h3>Proses Cepat</h3><p>Layanan kami dirancang untuk memproses setiap permintaan dengan cepat dan efisien tanpa mengorbankan kualitas.</p></div>
                <div class="feature-card"><div class="feature-icon">🔒</div><h3>Aman & Terpercaya</h3><p>Keamanan data dan transaksi Anda adalah prioritas utama kami dengan sistem perlindungan berlapis.</p></div>
                <div class="feature-card"><div class="feature-icon">🎨</div><h3>Pilihan Lengkap</h3><p>Tersedia ratusan pilihan produk yang dapat disesuaikan dengan kebutuhan dan selera Anda.</p></div>
                <div class="feature-card"><div class="feature-icon">💰</div><h3>Harga Terjangkau</h3><p>Dapatkan produk dan layanan berkualitas tinggi dengan harga yang kompetitif dan transparan.</p></div>
                <div class="feature-card"><div class="feature-icon">📦</div><h3>Pengiriman Mudah</h3><p>Layanan pengiriman ke seluruh wilayah dengan berbagai pilihan ekspedisi terpercaya.</p></div>
                <div class="feature-card"><div class="feature-icon">🎧</div><h3>Dukungan 24/7</h3><p>Tim customer service kami siap membantu Anda kapan saja dan di mana saja.</p></div>
            </div>
        </div>
    </div>

    <!-- HOW IT WORKS -->
    <div class="how-section">
        <div class="container">
            <div class="section-label">CARA KERJA</div>
            <div class="section-title">Mudah Digunakan</div>
            <div class="section-desc">Hanya dalam beberapa langkah mudah, Anda sudah bisa menikmati layanan kami.</div>
            <div class="steps">
                <div class="step"><div class="step-number">1</div><div><h3>Daftar Akun</h3><p>Buat akun baru secara gratis hanya dalam hitungan menit dengan mengisi data diri Anda.</p></div></div>
                <div class="step"><div class="step-number">2</div><div><h3>Pilih Produk</h3><p>Jelajahi katalog lengkap kami dan pilih produk yang sesuai dengan kebutuhan Anda.</p></div></div>
                <div class="step"><div class="step-number">3</div><div><h3>Lakukan Transaksi</h3><p>Selesaikan pembayaran dengan mudah melalui berbagai metode pembayaran yang tersedia.</p></div></div>
                <div class="step"><div class="step-number">4</div><div><h3>Nikmati Layanan</h3><p>Produk akan segera diproses dan dikirimkan ke alamat Anda dalam waktu singkat.</p></div></div>
            </div>
        </div>
    </div>

    <!-- TESTIMONIALS -->
    <div class="testimonials-section">
        <div class="container">
            <div class="section-label">TESTIMONI</div>
            <div class="section-title">Apa Kata Mereka?</div>
            <div class="section-desc">Ribuan pelanggan telah mempercayai layanan kami. Ini yang mereka katakan.</div>
            <div class="testimonials-grid">
                <div class="testimonial-card"><div class="testimonial-stars">★★★★★</div><p class="testimonial-text">"Layanan yang sangat memuaskan! Produk sampai tepat waktu dan kondisinya sangat baik. Pasti akan belanja lagi di sini."</p><div class="testimonial-author"><div class="author-avatar">A</div><div><div class="author-name">Andi Pratama</div><div class="author-role">Pelanggan Setia</div></div></div></div>
                <div class="testimonial-card"><div class="testimonial-stars">★★★★★</div><p class="testimonial-text">"Harganya sangat terjangkau dan kualitasnya tidak mengecewakan. Customer service juga sangat responsif dan ramah."</p><div class="testimonial-author"><div class="author-avatar">S</div><div><div class="author-name">Siti Rahma</div><div class="author-role">Pelanggan Baru</div></div></div></div>
                <div class="testimonial-card"><div class="testimonial-stars">★★★★★</div><p class="testimonial-text">"Sudah berlangganan lebih dari 2 tahun dan tidak pernah kecewa. Rekomendasi banget untuk semua orang!"</p><div class="testimonial-author"><div class="author-avatar">B</div><div><div class="author-name">Budi Santoso</div><div class="author-role">Member Premium</div></div></div></div>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="cta-section">
        <div class="container">
            <div class="cta-box">
                <h2>Siap Untuk Memulai?</h2>
                <p>Bergabunglah dengan ribuan pelanggan yang sudah mempercayai layanan kami.</p>
                @if(session()->has('username'))
                    @if(session('role') === 'admin')
                        <a href="{{ route('dashboard') }}" class="btn-cta">Ke Dashboard</a>
                    @else
                        <a href="{{ route('katalog') }}" class="btn-cta">Mulai Sekarang</a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn-cta">Daftar Gratis</a>
                @endif
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p>© {{ date('Y') }} Alips Camp. Dibuat dengan <span>♥</span> untuk pelanggan kami.</p>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('open');
        }
    </script>

</body>
</html>