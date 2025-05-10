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

$transaksi = mysqli_query($conn, "SELECT waktu FROM transaksi WHERE transaksi_id = $id");
$data = mysqli_fetch_assoc($transaksi);
$waktu = date('d/m/Y H:i', strtotime($data['waktu'] ?? ''));

$detail = mysqli_query($conn, "SELECT produk_id, jumlah FROM transaksi_detail WHERE transaksi_id = $id");
while ($d = mysqli_fetch_assoc($detail)) {
    $produk_id = $d['produk_id'];
    $jumlah = $d['jumlah'];
    mysqli_query($conn, "UPDATE produk SET stok = stok + $jumlah WHERE produk_id = $produk_id");
}

mysqli_query($conn, "DELETE FROM pembayaran WHERE transaksi_id = $id");
mysqli_query($conn, "DELETE FROM transaksi_detail WHERE transaksi_id = $id");
$hapus = mysqli_query($conn, "DELETE FROM transaksi WHERE transaksi_id = $id");

if ($hapus) {
    $_SESSION['success'] = "Riwayat pesanan (ID $id, Waktu $waktu) berhasil dihapus dan stok dikembalikan.";
} else {
    $_SESSION['error'] = "Gagal menghapus transaksi.";
}

header("Location: riwayat_penjualan.php");
exit;
