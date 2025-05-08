<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'owner') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM user WHERE user_id = $id");

$_SESSION['success'] = "User berhasil dihapus.";
header("Location: kelola_user.php");
exit;
