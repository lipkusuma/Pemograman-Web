<?php
session_start();

// Hanya proses jika method POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // Simulasi Database Sederhana
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header("Location: ../pages/dashboard.php");
        exit();
    } elseif ($username !== '' && $password !== '') {
        // Untuk user biasa/pembeli
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
        header("Location: ../pages/katalog.php");
        exit();
    } else {
        $_SESSION['auth_error'] = "Username dan Password wajib diisi!";
        header("Location: ../pages/login.php");
        exit();
    }
} else {
    // Jika bukan POST, redirect ke halaman login
    header("Location: ../pages/login.php");
    exit();
}
?>
