<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

$id = $_POST['user_id'];
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$role     = $_POST['role'];

// Validasi kosong
if (empty($username) || empty($role)) {
    $_SESSION['error'] = "Username dan Role tidak boleh kosong!";
    header("Location: edit_user.php?id=$id");
    exit;
}

// Cek apakah user benar-benar ada
$cekUser = mysqli_prepare($conn, "SELECT user_id FROM user WHERE user_id = ?");
mysqli_stmt_bind_param($cekUser, "i", $id);
mysqli_stmt_execute($cekUser);
mysqli_stmt_store_result($cekUser);
if (mysqli_stmt_num_rows($cekUser) === 0) {
    $_SESSION['error'] = "User tidak ditemukan!";
    header("Location: kelola_user.php");
    exit;
}

// Update
if ($password !== '') {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $query = mysqli_prepare($conn, "UPDATE user SET username = ?, password = ?, role = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($query, "sssi", $username, $hashed, $role, $id);
} else {
    $query = mysqli_prepare($conn, "UPDATE user SET username = ?, role = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($query, "ssi", $username, $role, $id);
}

if (mysqli_stmt_execute($query)) {
    $_SESSION['success'] = "User berhasil diperbarui!";
} else {
    $_SESSION['error'] = "Gagal memperbarui user. Silakan coba lagi.";
}

header("Location: kelola_user.php");
exit;
