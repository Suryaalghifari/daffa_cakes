<?php
session_start();
require_once '../../config/koneksi.php';

$id = $_GET['id'] ?? 0;
$result = mysqli_query($conn, "SELECT gambar FROM produk WHERE produk_id = $id");
$data = mysqli_fetch_assoc($result);

// Hapus gambar jika ada
if ($data && $data['gambar'] && file_exists("../../assets/img/produk/" . $data['gambar'])) {
    unlink("../../assets/img/produk/" . $data['gambar']);
}

mysqli_query($conn, "DELETE FROM produk WHERE produk_id = $id");

$_SESSION['success'] = "Produk berhasil dihapus.";
header("Location: kelola_produk.php");
exit;
