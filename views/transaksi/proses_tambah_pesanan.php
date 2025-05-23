<?php
// Aktifkan error log PHP
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once '../../config/koneksi.php';

header('Content-Type: application/json');

// Autentikasi role kasir
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'kasir') {
    echo json_encode(['status' => 'error', 'message' => 'Akses ditolak.']);
    exit;
}

// Ambil data dari body JSON
$input = json_decode(file_get_contents('php://input'), true);

$kasir_id = $_SESSION['user']['user_id'] ?? $_SESSION['user']['id'];
$keranjang = $input['keranjang'] ?? [];
$metode = mysqli_real_escape_string($conn, $input['metode'] ?? '');
$jumlah_dibayar = (int)($input['jumlah_dibayar'] ?? 0);
$status = 'pending';

// Validasi input awal
if (empty($keranjang) || !$metode || $jumlah_dibayar <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap.']);
    exit;
}

// Hitung total harga
$total_harga = 0;
foreach ($keranjang as $item) {
    if (!isset($item['produk_id'], $item['harga'], $item['jumlah'])) {
        echo json_encode(['status' => 'error', 'message' => 'Item keranjang tidak valid.']);
        exit;
    }
    $total_harga += (int)$item['harga'] * (int)$item['jumlah'];
}

// Simpan ke database dengan transaksi
mysqli_begin_transaction($conn);
try {
    date_default_timezone_set('Asia/Jakarta');
    $waktu = date('Y-m-d H:i:s');

    // Simpan ke tabel transaksi
    $stmtTransaksi = mysqli_prepare($conn, "INSERT INTO transaksi (kasir_id, waktu, total_harga, status) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmtTransaksi, "isis", $kasir_id, $waktu, $total_harga, $status);
    if (!mysqli_stmt_execute($stmtTransaksi)) {
        throw new Exception("Gagal menyimpan transaksi.");
    }

    $transaksi_id = mysqli_insert_id($conn);

    // Simpan detail transaksi
    $stmtDetail = mysqli_prepare($conn, "INSERT INTO transaksi_detail (transaksi_id, produk_id, jumlah, harga_saat_ini) VALUES (?, ?, ?, ?)");
    foreach ($keranjang as $item) {
        $produk_id = (int)$item['produk_id'];
        $jumlah = (int)$item['jumlah'];
        $harga = (int)$item['harga'];

        mysqli_stmt_bind_param($stmtDetail, "iiii", $transaksi_id, $produk_id, $jumlah, $harga);
        if (!mysqli_stmt_execute($stmtDetail)) {
            throw new Exception("Gagal menyimpan detail transaksi.");
        }

        // Kurangi stok produk
        $updateStok = mysqli_query($conn, "UPDATE produk SET stok = stok - $jumlah WHERE produk_id = $produk_id");
        if (!$updateStok) {
            throw new Exception("Gagal mengurangi stok.");
        }
    }

    // Hitung kembalian (opsional jika ingin disimpan)
    $kembalian = $jumlah_dibayar - $total_harga;

    // Simpan pembayaran
    $stmtBayar = mysqli_prepare($conn, "INSERT INTO pembayaran (transaksi_id, metode, jumlah_dibayar, kembalian) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmtBayar, "issi", $transaksi_id, $metode, $jumlah_dibayar, $kembalian);
    if (!mysqli_stmt_execute($stmtBayar)) {
        throw new Exception("Gagal menyimpan pembayaran.");
    }

    // Commit transaksi
    mysqli_commit($conn);
    echo json_encode(['status' => 'success', 'message' => "Transaksi berhasil disimpan."]);

} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['status' => 'error', 'message' => "Terjadi kesalahan: " . $e->getMessage()]);
}
exit;
