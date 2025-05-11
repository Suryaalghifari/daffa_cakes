<?php
session_start();
require_once '../../config/koneksi.php';
require_once '../../config/base_url.php';

header('Content-Type: application/json');

// Cek login
if (!isset($_SESSION['pelanggan_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Harap login terlebih dahulu.']);
    exit;
}

$keranjang = json_decode($_POST['keranjang'] ?? '[]', true);
$metode = mysqli_real_escape_string($conn, $_POST['metode'] ?? '');
$alamat = mysqli_real_escape_string($conn, $_POST['alamat'] ?? '');
$total = (int)($_POST['total_harga'] ?? 0);
$pelanggan_id = $_SESSION['pelanggan_id'];
$status = 'pending';

// Validasi awal
if (empty($keranjang) || !$metode || !$alamat || $total <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    exit;
}

// Upload bukti bayar
$bukti_filename = null;
if (($metode === 'Transfer' || $metode === 'QRIS') && isset($_FILES['bukti'])) {
    $dir = __DIR__ . '/../../assets/img/bukti/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $ext = strtolower(pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        echo json_encode(['status' => 'error', 'message' => 'Format bukti pembayaran tidak valid.']);
        exit;
    }

    $bukti_filename = uniqid('bukti_') . '.' . $ext;
    if (!move_uploaded_file($_FILES['bukti']['tmp_name'], $dir . $bukti_filename)) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal mengunggah bukti pembayaran.']);
        exit;
    }
}

mysqli_begin_transaction($conn);
try {
    $waktu = date('Y-m-d H:i:s');

    // Simpan transaksi
    $stmt = mysqli_prepare($conn, "INSERT INTO transaksi (pelanggan_id, waktu, total_harga, status) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "isis", $pelanggan_id, $waktu, $total, $status);
    mysqli_stmt_execute($stmt);
    $trx_id = mysqli_insert_id($conn);

    // Simpan detail & update stok
    $stmtDetail = mysqli_prepare($conn, "INSERT INTO transaksi_detail (transaksi_id, produk_id, jumlah, harga_saat_ini) VALUES (?, ?, ?, ?)");
    $stmtUpdateStok = mysqli_prepare($conn, "UPDATE produk SET stok = stok - ? WHERE produk_id = ? AND stok >= ?");

    foreach ($keranjang as $item) {
        $produk_id = (int)$item['produk_id'];
        $jumlah = (int)$item['jumlah'];
        $harga = (int)$item['harga'];

        // Validasi stok di backend
        $cek = mysqli_fetch_assoc(mysqli_query($conn, "SELECT stok FROM produk WHERE produk_id = $produk_id"));
        if (!$cek || $cek['stok'] < $jumlah) {
            throw new Exception("Stok tidak mencukupi untuk produk ID: $produk_id");
        }

        // Simpan detail
        mysqli_stmt_bind_param($stmtDetail, "iiii", $trx_id, $produk_id, $jumlah, $harga);
        mysqli_stmt_execute($stmtDetail);

        // Kurangi stok
        mysqli_stmt_bind_param($stmtUpdateStok, "iii", $jumlah, $produk_id, $jumlah);
        mysqli_stmt_execute($stmtUpdateStok);
    }

    // Simpan pembayaran
    $stmtBayar = mysqli_prepare($conn, "
        INSERT INTO pembayaran (transaksi_id, metode, jumlah_dibayar, kembalian, bukti, alamat)
        VALUES (?, ?, ?, 0, ?, ?)
    ");
    mysqli_stmt_bind_param($stmtBayar, "issss", $trx_id, $metode, $total, $bukti_filename, $alamat);
    mysqli_stmt_execute($stmtBayar);

    mysqli_commit($conn);
    echo json_encode(['status' => 'success', 'message' => 'Pesanan berhasil dikirim.']);

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan pesanan: ' . $e->getMessage()]);
}
exit;
