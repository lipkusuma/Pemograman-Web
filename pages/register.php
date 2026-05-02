<?php
session_start();

// Ambil pesan error/success dari session flash
$error = $_SESSION['auth_error'] ?? '';
$success = $_SESSION['auth_success'] ?? '';
$form_data = $_SESSION['form_data'] ?? [];
// Hapus pesan setelah diambil
unset($_SESSION['auth_error'], $_SESSION['auth_success'], $_SESSION['form_data']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
            <div class="auth-sidebar-content">
                <h1>Get Started!</h1>
                <p>Please enter your name, e-mail, and password for sign up!</p>
            </div>
        </div>

        <!-- Main Form Area -->
        <div class="auth-main">
            <!-- Mobile Decorative Waves -->
            <svg class="mobile-wave-top" preserveAspectRatio="none" viewBox="0 0 500 150" height="150" style="fill: #38bdf8;">
                <path d="M0,150 C150,50 350,150 500,50 L500,0 L0,0 Z" />
            </svg>
            
            <div class="auth-form-container">
                <h2 class="auth-title">Register</h2>
                <p class="mobile-subtitle">Please enter your name, e-mail, and password for sign up!</p>

                <?php if(!empty($error)): ?>
                    <div class="alert alert-error">⚠️ <?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <?php if(!empty($success)): ?>
                    <div class="alert alert-success">✅ <?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <form action="../auth/register.php" method="POST">
                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input type="text" name="username" placeholder="Username" pattern="^[a-zA-Z0-9_]{3,20}$" title="Username hanya boleh huruf, angka, underscore (3-20 karakter)" value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>" required>
                    </div>

                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" placeholder="Email" pattern=".*@gmail\.com$" title="Gunakan email berakhiran @gmail.com" value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
                    </div>

                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <input type="tel" name="phone" placeholder="Nomor Handphone" pattern="^[0-9]{10,15}$" title="Nomor handphone harus berupa angka (10-15 digit)" value="<?php echo htmlspecialchars($form_data['phone'] ?? ''); ?>" required>
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
                    Sudah memiliki akun? <a href="login.php">Login</a>
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
