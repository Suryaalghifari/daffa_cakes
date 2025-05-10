<?php
session_start();
$nama = $_SESSION['user']['nama_lengkap'] ?? '';
$role = ucfirst($_SESSION['user']['role'] ?? '');
session_destroy();

session_start(); // mulai ulang agar bisa set session baru
$_SESSION['logout_success'] = "Sampai jumpa kembali, $nama ($role)";
header("Location: /daffa_cakes/views/auth/login.php");
exit;
