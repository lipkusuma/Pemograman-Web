<?php
$error = "";
$success = "";

// Cek apakah form telah di-submit dengan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil nilai dari form dan menghapus spasi berlebih
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi input
    if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $error = "Semua kolom wajib diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan Konfirmasi Password tidak cocok!";
    } elseif (!preg_match('/@gmail\.com$/i', $email)) {
        $error = "Format email harus menggunakan @gmail.com!";
    } elseif (!preg_match('/^[a-zA-Z0-9\_]{3,20}$/', $username)) {
        $error = "Username hanya boleh berisi huruf, angka, dan underscore (3-20 karakter)!";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $error = "Nomor handphone harus berupa angka (10-15 digit)!";
    } else {
        $success = "Pendaftaran berhasil! Silakan ke halaman Profil untuk melengkapi foto.";
        
        // Di dunia nyata, di sini biasanya kita menjalankan logika MySQL:
        // INSERT INTO users (username, email, phone, password) VALUES (...)
        
        // Kosongkan form karena sudah berhasil
        $username = $email = $phone = "";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
    <style>
        /* Styling untuk Pesan Error & Success */
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: 500; font-size: 0.9rem;}
        .alert-error { background-color: #fee2e2; color: #b91c1c; border: 1px solid #f87171; }
        .alert-success { background-color: #dcfce7; color: #15803d; border: 1px solid #4ade80; }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Desktop Sidebar -->
        <div class="auth-sidebar">
            <svg class="auth-sidebar-wave" preserveAspectRatio="none" viewBox="0 0 500 1000">
                <path d="M0,300 C150,400 350,200 500,300 L500,1000 L0,1000 Z" />
                <path d="M0,600 C200,800 300,500 500,700 L500,1000 L0,1000 Z" />
            </svg>
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

                <!-- Area Menampilkan Error / Success Messages dari PHP -->
                <?php if(!empty($error)): ?>
                    <div class="alert alert-error"> âš ï¸ <?php echo htmlspecialchars($error); ?> </div>
                <?php endif; ?>

                <?php if(!empty($success)): ?>
                    <div class="alert alert-success"> âœ… <?php echo htmlspecialchars($success); ?> </div>
                <?php endif; ?>

                <!-- Form sekarang mengarah ke dirinya sendiri (register.php) -->
                <form action="register.php" method="POST">
                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <!-- Menambahkan Atribut name="" dan mempertahankan value jika ada error -->
                        <input type="text" name="username" placeholder="Username" pattern="^[a-zA-Z0-9_]{3,20}$" title="Username hanya boleh huruf, angka, underscore (3-20 karakter)" value="<?php echo htmlspecialchars($username ?? ''); ?>" required>
                    </div>

                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input type="email" name="email" placeholder="Email" pattern=".*@gmail\.com$" title="Gunakan email berakhiran @gmail.com" value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                    </div>

                    <div class="input-group">
                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <input type="tel" name="phone" placeholder="Nomor Handphone" pattern="^[0-9]{10,15}$" title="Nomor handphone harus berupa angka (10-15 digit)" value="<?php echo htmlspecialchars($phone ?? ''); ?>" required>
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
</body>
</html>
