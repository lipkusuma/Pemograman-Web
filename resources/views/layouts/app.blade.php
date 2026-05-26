<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    {{-- @vite('resources/css/app.css') --}}
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-pVnY6+1Yb6U6YfZ0nK2Qm0s1Yw2s3VZ1Y6s9Qe1Gz0Qp6K1nVjK1Qe1Gz0Qp6K1nVjK1Qe1Gz0Qp6K1nVjK1Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @stack('styles')
</head>
<body>
    <div class="app-container">
        {{-- Sidebar --}}
        @include('components.sidebar')

        {{-- Main Content Area --}}
        <main class="main-content">
            {{-- Topbar --}}
            <header class="topbar">
                @yield('topbar')
            </header>

            {{-- Page Content --}}
            <div class="page-content">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
