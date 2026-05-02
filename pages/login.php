<?php
session_start();

// Ambil pesan error/success dari session flash
$error = $_SESSION['auth_error'] ?? '';
$success = $_SESSION['auth_success'] ?? '';
// Hapus pesan setelah diambil
unset($_SESSION['auth_error'], $_SESSION['auth_success']);
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
    <!-- Dark Mode Toggle -->
    <button class="auth-dark-toggle" onclick="toggleDarkMode()">
        <svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
        <svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
    </button>

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

                <?php if(!empty($error)): ?>
                    <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if(!empty($success)): ?>
                    <div class="alert alert-success">✅ <?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form action="../auth/login.php" method="POST">
                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" name="username" placeholder="Username" required>
                    </div>

                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password" placeholder="Password" required>
                        <svg class="input-icon-right" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
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
    </div>

    <script src="../assets/js/app.js"></script>
</body>
</html>
