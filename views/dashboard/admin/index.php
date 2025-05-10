<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

include_once '../../layouts/header.php';
include_once '../../layouts/sidebar.php';

// Query total
$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk"))['total'];
$total_kategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kategori"))['total'];
$stok_rendah = mysqli_query($conn, "SELECT * FROM produk WHERE stok <= 3");

// Produk terbaru
$produk_baru = mysqli_query($conn, "SELECT * FROM produk ORDER BY produk_id DESC LIMIT 5");
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>

    <div class="row">
        <!-- Total Produk -->
        <div class="col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 p-3">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Produk</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_produk ?></div>
            </div>
        </div>

        <!-- Total Kategori -->
        <div class="col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 p-3">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kategori</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_kategori ?></div>
            </div>
        </div>

        <!-- Stok Rendah -->
        <div class="col-md-4 mb-3">
            <div class="card border-left-danger shadow h-100 p-3">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stok Hampir Habis</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= mysqli_num_rows($stok_rendah) ?> produk</div>
            </div>
        </div>
    </div>

    <!-- Produk Terbaru -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Produk Terbaru</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <?php while ($p = mysqli_fetch_assoc($produk_baru)) : ?>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <?php if ($p['gambar']) : ?>
                                <img src="/daffa_cakes/assets/img/produk/<?= $p['gambar'] ?>" class="card-img-top" style="height:150px; object-fit:cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h6 class="card-title mb-1"><?= htmlspecialchars($p['nama_produk']) ?></h6>
                                <p class="card-text">Rp <?= number_format($p['harga']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once '../../layouts/footer.php'; ?>
