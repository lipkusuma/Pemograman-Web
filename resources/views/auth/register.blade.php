@extends('layouts.auth')

@section('title', 'Register')

@section('content')
<div class="auth-container">
    <!-- Desktop Sidebar -->
    <div class="auth-sidebar">
        <div class="auth-sidebar-content">
            <h1>Get Started!</h1>
            <p>Please enter your name, e-mail, and password for sign up!</p>
        </div>
    </div>

    <!-- Main Form Area -->
    <div class="auth-main">

        <div class="auth-form-container">
            <h2 class="auth-title">Register</h2>
            <p class="mobile-subtitle">Please enter your name, e-mail, and password for sign up!</p>

            @if(session('auth_error'))
                <div class="alert alert-error">⚠️ {{ session('auth_error') }}</div>
            @endif

            @if(session('auth_success'))
                <div class="alert alert-success">✅ {{ session('auth_success') }}</div>
            @endif

            <form action="{{ route('auth.register') }}" method="POST">
                @csrf
                <div class="input-group">
                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <input type="text" name="username" placeholder="Username"
                        pattern="^[a-zA-Z0-9_]{3,20}$"
                        title="Username hanya boleh huruf, angka, underscore (3-20 karakter)"
                        value="{{ old('username') }}" required>
                </div>

                <div class="input-group">
                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <input type="email" name="email" placeholder="Email"
                        pattern=".*@gmail\.com$"
                        title="Gunakan email berakhiran @gmail.com"
                        value="{{ old('email') }}" required>
                </div>

                <div class="input-group">
                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <input type="tel" name="phone" placeholder="Nomor Handphone"
                        pattern="^[0-9]{10,15}$"
                        title="Nomor handphone harus berupa angka (10-15 digit)"
                        value="{{ old('phone') }}" required>
                </div>

                <div class="input-group">
                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="input-group">
                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                </div>

                <button type="submit" class="btn btn-auth">Register</button>
            </form>

            <div class="auth-footer">
                Sudah memiliki akun? <a href="{{ route('login') }}">Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
