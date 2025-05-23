<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['owner', 'admin'])) {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}


include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';

// Tanggal
$tanggal_hari_ini = date('Y-m-d');
$tanggal_bulan_ini = date('Y-m');

// Query Laporan Harian
$laporan_harian = mysqli_query($conn, "
    SELECT SUM(total_harga) AS total, COUNT(*) AS total_transaksi 
    FROM transaksi 
    WHERE DATE(waktu) = '$tanggal_hari_ini'
");
$laporan_harian_data = mysqli_fetch_assoc($laporan_harian);

// Query Laporan Bulanan
$laporan_bulanan = mysqli_query($conn, "
    SELECT SUM(total_harga) AS total, COUNT(*) AS total_transaksi 
    FROM transaksi 
    WHERE DATE_FORMAT(waktu, '%Y-%m') = '$tanggal_bulan_ini'
");
$laporan_bulanan_data = mysqli_fetch_assoc($laporan_bulanan);
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kelola Laporan</h1>

    <!-- Laporan Harian -->
   <!-- Laporan Harian -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Laporan Harian</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4"><strong><?= strftime('%A, %d %B %Y', strtotime($tanggal_hari_ini)) ?></strong></p>

        <div class="row text-center mb-3">
            <div class="col-md-6 mb-2">
                <div class="border rounded p-3 bg-light">
                    <h6 class="text-secondary">Total Transaksi</h6>
                    <h4><?= $laporan_harian_data['total_transaksi'] ?? 0 ?></h4>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="border rounded p-3 bg-light">
                    <h6 class="text-secondary">Total Pendapatan</h6>
                    <h4>Rp <?= number_format($laporan_harian_data['total'] ?? 0) ?></h4>
                </div>
            </div>
        </div>

        <div class="text-right">
            <a href="cetak_laporan.php?type=harian&date=<?= $tanggal_hari_ini ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-print"></i> Cetak Laporan Harian
            </a>
        </div>
    </div>
</div>


    <!-- Laporan Bulanan -->
<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Laporan Bulanan</h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4"><strong><?= strftime('%A, %d %B %Y', strtotime($tanggal_bulan_ini)) ?></strong></p>

        <div class="row text-center mb-3">
            <div class="col-md-6 mb-2">
                <div class="border rounded p-3 bg-light">
                    <h6 class="text-secondary">Total Transaksi</h6>
                    <h4><?= $laporan_bulanan_data['total_transaksi'] ?? 0 ?></h4>
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="border rounded p-3 bg-light">
                    <h6 class="text-secondary">Total Pendapatan</h6>
                    <h4>Rp <?= number_format($laporan_bulanan_data['total'] ?? 0) ?></h4>
                </div>
            </div>
        </div>

        <div class="text-right">
                    <a href="cetak_laporan.php?type=bulanan&date=<?= $tanggal_bulan_ini ?>" class="btn btn-sm btn-primary">
                <i class="fas fa-print"></i> Cetak Laporan Bulanan
            </a>
        </div>
    </div>
</div>


<?php include_once '../layouts/footer.php'; ?>
