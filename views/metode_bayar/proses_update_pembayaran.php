<?php
session_start();
require_once '../../config/koneksi.php';
require_once '../../config/base_url.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Handle Transfer
$nama_bank = mysqli_real_escape_string($conn, $_POST['nama_bank'] ?? '');
$no_rekening = mysqli_real_escape_string($conn, $_POST['no_rekening'] ?? '');
$atas_nama = mysqli_real_escape_string($conn, $_POST['atas_nama'] ?? '');

// Cek apakah sudah ada data Transfer
$cek = mysqli_query($conn, "SELECT id FROM metode_pembayaran WHERE metode = 'Transfer'");
if (mysqli_num_rows($cek) > 0) {
    // Update
    mysqli_query($conn, "UPDATE metode_pembayaran SET 
        nama_bank = '$nama_bank',
        no_rekening = '$no_rekening',
        atas_nama = '$atas_nama',
        updated_at = NOW()
        WHERE metode = 'Transfer'");
} else {
    // Insert
    mysqli_query($conn, "INSERT INTO metode_pembayaran (metode, nama_bank, no_rekening, atas_nama) VALUES 
        ('Transfer', '$nama_bank', '$no_rekening', '$atas_nama')");
}

// Handle QRIS
$gambar_qris = '';
$upload_dir = '../../assets/img/pembayaran/';

if (isset($_FILES['gambar_qris']) && $_FILES['gambar_qris']['error'] === 0) {
    $ext = strtolower(pathinfo($_FILES['gambar_qris']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (in_array($ext, $allowed)) {
        $gambar_qris = 'qris_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['gambar_qris']['tmp_name'], $upload_dir . $gambar_qris);

        // Update atau insert QRIS
        $cekQRIS = mysqli_query($conn, "SELECT id FROM metode_pembayaran WHERE metode = 'QRIS'");
        if (mysqli_num_rows($cekQRIS) > 0) {
            mysqli_query($conn, "UPDATE metode_pembayaran SET 
                gambar_qris = '$gambar_qris',
                updated_at = NOW()
                WHERE metode = 'QRIS'");
        } else {
            mysqli_query($conn, "INSERT INTO metode_pembayaran (metode, gambar_qris) VALUES 
                ('QRIS', '$gambar_qris')");
        }
    } else {
        $_SESSION['error'] = "Format file QRIS tidak didukung (jpg/png/webp).";
        header("Location: kelola_metode_pembayaran.php");
        exit;
    }
}

$_SESSION['success'] = "Metode pembayaran berhasil diperbarui.";
header("Location: kelola_metode_pembayaran.php");
exit;
