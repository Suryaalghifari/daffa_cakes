<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

// Cek apakah owner yang akses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'owner') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

// Ambil input dari form
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$role     = $_POST['role'];

// Validasi sederhana
if ($username === '' || $password === '' || $role === '') {
    $_SESSION['error'] = "Semua field wajib diisi!";
    header("Location: kelola_user.php");//locasi untuk menambakan file
    exit;
}

// Cek apakah username sudah ada
$cek = mysqli_prepare($conn, "SELECT * FROM user WHERE username = ?");
mysqli_stmt_bind_param($cek, "s", $username);
mysqli_stmt_execute($cek);
$hasil = mysqli_stmt_get_result($cek);

if (mysqli_num_rows($hasil) > 0) {
    $_SESSION['error'] = "Username sudah digunakan!";
    header("Location: kelola_user.php");
    exit;
}

// Hash password
$hashed = password_hash($password, PASSWORD_DEFAULT);

// Simpan user baru
$stmt = mysqli_prepare($conn, "INSERT INTO user (username, password, role) VALUES (?, ?, ?)");
mysqli_stmt_bind_param($stmt, "sss", $username, $hashed, $role);
mysqli_stmt_execute($stmt);

// Feedback
$_SESSION['success'] = "User berhasil ditambahkan!";
header("Location: kelola_user.php");
exit;
