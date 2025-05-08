<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'owner') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

$id = $_POST['user_id'];
$username = trim($_POST['username']);
$password = trim($_POST['password']);
$role     = $_POST['role'];

// Jika password dikosongkan, jangan ubah
if ($password !== '') {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $query = mysqli_prepare($conn, "UPDATE user SET username = ?, password = ?, role = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($query, "sssi", $username, $hashed, $role, $id);
} else {
    $query = mysqli_prepare($conn, "UPDATE user SET username = ?, role = ? WHERE user_id = ?");
    mysqli_stmt_bind_param($query, "ssi", $username, $role, $id);
}

mysqli_stmt_execute($query);

$_SESSION['success'] = "User berhasil diperbarui!";
header("Location: kelola_user.php");
exit;
