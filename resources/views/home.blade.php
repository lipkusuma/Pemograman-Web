<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
</head>
<body>
    <!-- Abstract Background SVG Layer -->
    <div class="bg-layer">
        <!-- Top Right Shape Background -->
        <svg style="position: absolute; top: 0; right: 0; width: 600px; height: 100%;" preserveAspectRatio="none" viewBox="0 0 500 1000">
            <path d="M500,0 L200,0 C150,150 300,300 200,500 C150,600 350,800 250,1000 L500,1000 Z" fill="#2563eb" opacity="0.8"/>
            <path d="M500,0 L300,0 C250,100 400,250 300,450 C250,550 450,750 350,1000 L500,1000 Z" fill="#38bdf8" opacity="0.5"/>
        </svg>

        <!-- Dynamic Concentric Circles on the right -->
        <svg style="position: absolute; top: 50%; right: -100px; transform: translateY(-50%); width: 700px; height: 700px;" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="45" fill="none" stroke="#2dd4bf" stroke-width="8" opacity="0.3"/>
            <circle cx="50" cy="50" r="30" fill="none" stroke="#2563eb" stroke-width="12" opacity="0.5"/>
            <circle cx="50" cy="50" r="15" fill="#38bdf8" opacity="0.8"/>
        </svg>

        <!-- Small floating circles -->
        <svg style="position: absolute; top: 40%; left: 35%; width: 80px; height: 80px; filter: drop-shadow(0 10px 10px rgba(0,0,0,0.2));" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="40" fill="#2563eb"/>
            <circle cx="50" cy="50" r="20" fill="#22d3ee"/>
        </svg>
        <svg style="position: absolute; top: 75%; left: 37%; width: 90px; height: 90px; filter: drop-shadow(0 10px 10px rgba(0,0,0,0.2));" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="40" fill="#1e3a8a"/>
            <circle cx="50" cy="50" r="20" fill="#22d3ee"/>
        </svg>

        <!-- Bottom Left Cyan Shape -->
        <svg style="position: absolute; bottom: 0; left: 0; width: 400px; height: 300px;" preserveAspectRatio="none" viewBox="0 0 400 300">
            <path d="M0,300 L0,150 C50,150 100,250 200,200 C300,150 350,250 400,250 L400,300 Z" fill="#22d3ee" opacity="0.8"/>
        </svg>
    </div>

    <!-- Main Container Content -->
    <div class="container">
        <!-- Navigation -->
        <nav class="navbar">
            <div class="nav-left">
                <div class="logo-icon">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 8H16L24 22V8H32V32H24L16 18V32H8V8Z" fill="#000000"/>
                    </svg>
                </div>
                <ul class="nav-links">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li>
                        @if(session()->has('username'))
                            <a href="{{ session('role') === 'admin' ? route('dashboard') : route('katalog') }}">{{ session('role') === 'admin' ? 'Dashboard' : 'Katalog' }}</a>
                        @else
                            <a href="{{ route('login') }}">Katalog</a>
                        @endif
                    </li>
                    <li><a href="#">About</a></li>
                </ul>
            </div>

            @if(session()->has('username'))
            <div style="display:flex; align-items:center; gap:12px; position:relative; z-index:11;">
                <span style="font-weight:600; color:#000; font-size:0.9rem;">Halo, {{ session('name', session('username')) }}!</span>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" style="font-weight:700; color:#dc2626; font-size:0.9rem; background:none; border:none; cursor:pointer;">Logout</button>
                </form>
            </div>
            @else
            <div style="display:flex; gap:12px; position:relative; z-index:11;">
                <a href="{{ route('login') }}" style="font-weight:700; color:#000; font-size:0.95rem;">Login</a>
                <a href="{{ route('register') }}" class="btn btn-dark" style="padding:8px 20px; font-size:0.88rem; border-radius:20px;">Register</a>
            </div>
            @endif

            <button class="mobile-menu-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 12h18M3 6h18M3 18h18" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </nav>

        <!-- Hero -->
        <main class="hero">
            <div class="hero-content">
                <h1 class="hero-title">Lorem<br>Ipsum Dolor</h1>
                <div class="hero-desc">
                    <div class="hero-desc-line"></div>
                    <p>Lorem ipsum dolor sit amet,<br>consectetur adipiscing elit</p>
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
        </main>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
