<?php
session_start();
// Menghancurkan semua data session
session_unset();
session_destroy();
// Redirect ke halaman login
header("Location: ../pages/login.php");
exit();
?>
