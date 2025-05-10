<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'kasir') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID transaksi tidak ditemukan.";
    header("Location: riwayat_penjualan.php");
    exit;
}

$id = (int) $_GET['id'];
$kasir_id = $_SESSION['user']['id'];

// Pastikan transaksi masih pending
$cek = mysqli_query($conn, "SELECT status FROM transaksi WHERE transaksi_id = $id");
$data = mysqli_fetch_assoc($cek);

if (!$data) {
    $_SESSION['error'] = "Transaksi tidak ditemukan.";
} elseif ($data['status'] !== 'pending') {
    $_SESSION['error'] = "Transaksi sudah divalidasi sebelumnya.";
} else {
    // Update status dan set kasir_id
    $update = mysqli_query($conn, "
        UPDATE transaksi 
        SET status = 'valid', kasir_id = $kasir_id 
        WHERE transaksi_id = $id
    ");

    if ($update) {
        $_SESSION['success'] = "Transaksi ID $id berhasil divalidasi oleh kasir.";
    } else {
        $_SESSION['error'] = "Gagal memvalidasi transaksi.";
    }
}

header("Location: riwayat_penjualan.php");
exit;
