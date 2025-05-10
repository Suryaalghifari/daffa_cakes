<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'kasir') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

include_once '../../layouts/header.php';
include_once '../../layouts/sidebar.php';
?>

<!-- Konten Dashboard Kasir -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Kasir</h1>

    <div class="alert alert-info">
        Selamat datang, <strong><?= htmlspecialchars($_SESSION['user']['nama_lengkap']); ?></strong>!
    </div>

    <div class="row">
        <!-- Tombol Aksi -->
        <div class="col-md-4">
            <a href="/daffa_cakes/views/transaksi/kelola_pesanan.php" class="btn btn-success btn-lg btn-block">
                <i class="fas fa-cash-register"></i> Transaksi Baru
            </a>
        </div>
        <div class="col-md-4">
            <a href="/daffa_cakes/views/transaksi/riwayat_penjualan.php" class="btn btn-secondary btn-lg btn-block">
                <i class="fas fa-history"></i> Riwayat Penjualan
            </a>
        </div>
    </div>
</div>

<?php include_once '../../layouts/footer.php'; ?>
