<?php
session_start();

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Simulasi Database Sederhana
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header("Location: dashboard.php");
        exit();
    } elseif ($username !== '' && $password !== '') {
        // Untuk user biasa/pembeli
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
        header("Location: katalog.php");
        exit();
    } else {
        $error = "Username dan Password wajib diisi!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>
    <div class="auth-container">
        <!-- Desktop Sidebar -->
        <div class="auth-sidebar">
            <!-- Background SVG Wave -->
            <svg class="auth-sidebar-wave" preserveAspectRatio="none" viewBox="0 0 500 1000">
                <path d="M0,300 C150,400 350,200 500,300 L500,1000 L0,1000 Z" />
                <path d="M0,600 C200,800 300,500 500,700 L500,1000 L0,1000 Z" />
            </svg>
            <div class="auth-sidebar-content">
                <h1>Welcome Back!</h1>
                <p>Please enter your e-mail and password for verification</p>
            </div>
        </div>

        <!-- Main Form Area -->
        <div class="auth-main">
            <!-- Mobile Decorative Waves -->
            <svg class="mobile-wave-top" preserveAspectRatio="none" viewBox="0 0 500 150" height="150" style="fill: #38bdf8;">
                <path d="M0,150 C150,50 350,150 500,50 L500,0 L0,0 Z" />
            </svg>
            
            <div class="auth-form-container">
                <h2 class="auth-title">Login</h2>
                <p class="mobile-subtitle">Please enter your e-mail and password for verification</p>

                <?php if(!empty($error)): ?>
                    <div style="background-color: #fee2e2; color: #b91c1c; padding: 12px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 500; font-size: 0.9rem;">
                        ⚠️ <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST">
                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>

                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password" placeholder="Password" required>
                        <svg class="input-icon-right" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>

                    <!-- Additional Social Login per design if visible, though design shows small Google icon -->
                    <div class="social-login">
                        <a href="#" class="social-btn">
                            <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                        </a>
                    </div>
                    
                    <button type="submit" class="btn btn-auth">Login</button>
                </form>

                <div class="auth-footer">
                    Belum memiliki akun? <a href="register.php">Register</a>
                </div>
            </div>

            <!-- Mobile Decorative bottom waves -->
            <svg class="mobile-wave-bottom" preserveAspectRatio="none" viewBox="0 0 500 150" height="150" style="fill: #2563eb;">
                <path d="M0,150 C150,50 350,150 500,50 L500,0 L0,0 Z" />
            </svg>
        </div>
</body>
</html>
