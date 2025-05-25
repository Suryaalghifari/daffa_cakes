<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Session tidak valid. Silakan login ulang.";
    header("Location: ../auth/login.php");
    exit;
}

$tanggal = $_POST['tanggal'];
$produk_ids = $_POST['produk_id'];
$jumlahs = $_POST['jumlah'];
$user_id = $_SESSION['user']['id'];

if (!$tanggal || empty($produk_ids) || empty($jumlahs)) {
    $_SESSION['error'] = "Semua kolom wajib diisi!";
    header("Location: produksi_karyawan.php");
    exit;
}

$error = false;

for ($i = 0; $i < count($produk_ids); $i++) {
    $produk_id = (int)$produk_ids[$i];
    $jumlah = (int)$jumlahs[$i];

    if ($produk_id && $jumlah > 0) {
        // Update stok
        $update = mysqli_query($conn, "UPDATE produk SET stok = stok + $jumlah WHERE produk_id = $produk_id");

        // Insert log produksi
        $insert = mysqli_query($conn, "
            INSERT INTO produksi (produk_id, jumlah_dibuat, user_id, tanggal)
            VALUES ($produk_id, $jumlah, $user_id, '$tanggal')
        ");

        if (!$update || !$insert) {
            $error = true;
        }
    }
}

if ($error) {
    $_SESSION['error'] = "Beberapa data gagal disimpan.";
} else {
    $_SESSION['success'] = "Semua data produksi berhasil disimpan!";
}

header("Location: produksi_karyawan.php");
exit;
