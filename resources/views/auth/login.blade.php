@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-container">
    <!-- Desktop Sidebar -->
    <div class="auth-sidebar">
        <h1>Welcome Back!</h1>
        <p>Please enter your e-mail and password for verification</p>
    </div>

    <!-- Main Form Area -->
    <div class="auth-main">
        <div class="auth-form-container">
            <h2 class="auth-title">Login</h2>
            <p class="mobile-subtitle">Please enter your e-mail and password for verification</p>

            @if(session('auth_error'))
                <div class="alert alert-error">⚠️ {{ session('auth_error') }}</div>
            @endif

            @if(session('auth_success'))
                <div class="alert alert-success">✅ {{ session('auth_success') }}</div>
            @endif

            <form action="{{ route('auth.login') }}" method="POST">
                @csrf
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-auth">Login</button>
            </form>

            <div class="auth-footer">
                Belum memiliki akun? <a href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </div>
</div>
@endsection
