<?php
session_start();
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_produk']);
    $kategori_id = $_POST['kategori_id'];
    $stok = (int) $_POST['stok'];

    // Bersihkan harga dari 'Rp' dan titik
    $harga_raw = $_POST['harga'];
    $harga = (int) str_replace(['Rp', '.', ' '], '', $harga_raw);

    // Handle upload gambar
    $gambar = $_FILES['gambar']['name'];
    $gambar_name = null;

    if (!empty($gambar)) {
        $ext = pathinfo($gambar, PATHINFO_EXTENSION);
        $gambar_name = uniqid() . '.' . $ext;
        $upload_path = __DIR__ . '/../../assets/img/produk/' . $gambar_name;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path);
    }

    // Simpan ke DB
    $stmt = mysqli_prepare($conn, "INSERT INTO produk (nama_produk, kategori_id, harga, stok, gambar) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "siiis", $nama, $kategori_id, $harga, $stok, $gambar_name);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Produk berhasil ditambahkan.";
    } else {
        $_SESSION['error'] = "Gagal menambahkan produk.";
    }

    header("Location: kelola_produk.php");
    exit;
}
