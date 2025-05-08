<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

include_once '../../layouts/header.php';
include_once '../../layouts/sidebar.php';
?>

<!-- Konten khusus halaman dashboard admin -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>

    <div class="alert alert-success">
        Selamat datang, <strong><?= htmlspecialchars($_SESSION['user']['username']); ?></strong>! Anda login sebagai <strong>Admin</strong>.
    </div>

    <div class="card shadow">
        <div class="card-body">
            <p>Silakan gunakan menu sidebar untuk mengelola produk dan transaksi.</p>
        </div>
    </div>
</div>

<?php include_once '../../layouts/footer.php'; ?>
