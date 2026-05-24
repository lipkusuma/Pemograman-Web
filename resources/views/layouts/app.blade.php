<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
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
