<?php
session_start();
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = trim($_POST['nama_produk']);
    $kategori_id = $_POST['kategori_id'];
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];

    $gambar_baru = $_FILES['gambar']['name'] ?? null;
    $gambar_lama = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar FROM produk WHERE produk_id = $id"))['gambar'];
    $gambar_name = $gambar_lama;

    // Cek apakah upload gambar baru
    if (!empty($gambar_baru)) {
        $ext = pathinfo($gambar_baru, PATHINFO_EXTENSION);
        $gambar_name = uniqid() . '.' . $ext;
        $path = "../../assets/img/produk/" . $gambar_name;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $path);

        // Hapus file lama jika ada
        if ($gambar_lama && file_exists("../../assets/img/produk/$gambar_lama")) {
            unlink("../../assets/img/produk/$gambar_lama");
        }
    }

    $stmt = mysqli_prepare($conn, "UPDATE produk SET nama_produk = ?, kategori_id = ?, harga = ?, stok = ?, gambar = ? WHERE produk_id = ?");
    mysqli_stmt_bind_param($stmt, "siiisi", $nama, $kategori_id, $harga, $stok, $gambar_name, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Produk berhasil diperbarui.";
    } else {
        $_SESSION['error'] = "Gagal memperbarui produk.";
    }

    header("Location: kelola_produk.php");
    exit;
}
