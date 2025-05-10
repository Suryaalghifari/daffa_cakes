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
        Selamat datang, <strong><?= htmlspecialchars($_SESSION['user']['nama_lengkap']); ?></strong>! Anda login sebagai <strong>Kasir</strong>.
    </div>

    <div class="card shadow">
        <div class="card-body">
            <p>Silakan gunakan menu sidebar untuk mengelola transaksi dan melihat riwayat penjualan.</p>
        </div>
    </div>
</div>

<?php include_once '../../layouts/footer.php'; ?>
