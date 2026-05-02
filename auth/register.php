<?php
session_start();

// Hanya proses jika method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validasi input
    if (empty($username) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $_SESSION['auth_error'] = "Semua kolom wajib diisi!";
    } elseif ($password !== $confirm_password) {
        $_SESSION['auth_error'] = "Password dan Konfirmasi Password tidak cocok!";
    } elseif (!preg_match('/@gmail\.com$/i', $email)) {
        $_SESSION['auth_error'] = "Format email harus menggunakan @gmail.com!";
    } elseif (!preg_match('/^[a-zA-Z0-9\_]{3,20}$/', $username)) {
        $_SESSION['auth_error'] = "Username hanya boleh berisi huruf, angka, dan underscore (3-20 karakter)!";
    } elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) {
        $_SESSION['auth_error'] = "Nomor handphone harus berupa angka (10-15 digit)!";
    } else {
        // Di dunia nyata, di sini biasanya kita menjalankan logika MySQL:
        // INSERT INTO users (username, email, phone, password) VALUES (...)
        
        $_SESSION['auth_success'] = "Pendaftaran berhasil! Silakan login untuk melanjutkan.";
        header("Location: ../pages/login.php");
        exit();
    }

    // Simpan input agar form tidak kosong saat redirect kembali
    if (isset($_SESSION['auth_error'])) {
        $_SESSION['form_data'] = [
            'username' => $username,
            'email' => $email,
            'phone' => $phone
        ];
        header("Location: ../pages/register.php");
        exit();
    }
} else {
    // Jika bukan POST, redirect ke halaman register
    header("Location: ../pages/register.php");
    exit();
}
?>
