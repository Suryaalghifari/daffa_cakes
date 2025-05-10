<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

include_once '../../layouts/header.php';
include_once '../../layouts/sidebar.php';

// Ringkasan data
$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk"))['total'];
$total_kategori = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM kategori"))['total'];
$stok_rendah = mysqli_query($conn, "SELECT * FROM produk WHERE stok <= 3");
$produk_tanpa_gambar = mysqli_query($conn, "SELECT * FROM produk WHERE gambar IS NULL OR gambar = ''");
$produk_kosong = mysqli_query($conn, "SELECT * FROM produk WHERE stok = 0");

// Produk terbaru
$produk_baru = mysqli_query($conn, "SELECT * FROM produk ORDER BY produk_id DESC LIMIT 5");

// Produk terlaris
$produk_terlaris = mysqli_query($conn, "
    SELECT p.nama_produk, SUM(td.jumlah) AS total_terjual
    FROM transaksi_detail td
    JOIN produk p ON td.produk_id = p.produk_id
    GROUP BY td.produk_id
    ORDER BY total_terjual DESC
    LIMIT 5
");

// Untuk ChartJS
$top_produk = mysqli_query($conn, "
    SELECT p.nama_produk, SUM(td.jumlah) AS total
    FROM transaksi_detail td
    JOIN produk p ON td.produk_id = p.produk_id
    GROUP BY td.produk_id
    ORDER BY total DESC
    LIMIT 5
");

$nama_produk = [];
$jumlah_terjual = [];
while ($row = mysqli_fetch_assoc($top_produk)) {
    $nama_produk[] = $row['nama_produk'];
    $jumlah_terjual[] = $row['total'];
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Admin</h1>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow h-100 p-3">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Produk</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_produk ?></div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow h-100 p-3">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Kategori</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_kategori ?></div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow h-100 p-3">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Stok ≤ 3</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= mysqli_num_rows($stok_rendah) ?> produk</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-danger shadow h-100 p-3">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Stok 0 / Tanpa Gambar</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800"><?= mysqli_num_rows($produk_kosong) + mysqli_num_rows($produk_tanpa_gambar) ?> produk</div>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan Produk (Top 5)</h6>
        </div>
        <div class="card-body">
        <canvas id="chartPenjualan" 
            data-labels='<?= json_encode($nama_produk) ?>' 
            data-data='<?= json_encode($jumlah_terjual) ?>'></canvas>

        </div>
    </div>

    <!-- Produk Terlaris -->
    <div class="card shadow mb-4">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-success">Produk Terlaris</h6></div>
        <div class="card-body">
            <ul class="list-group">
                <?php while ($row = mysqli_fetch_assoc($produk_terlaris)) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($row['nama_produk']) ?>
                        <span class="badge badge-success badge-pill"><?= $row['total_terjual'] ?> terjual</span>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <!-- Produk Terbaru -->
    <div class="card shadow mb-4">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-info">Produk Terbaru</h6></div>
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

<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/daffa_cakes/sb-admin/js/demo/chart-penjualan-admin.js"></script>


<!-- SweetAlert (Login Notification) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['success'])): ?>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Login Berhasil!',
        text: '<?= $_SESSION['success']; ?>',
        showConfirmButton: false,
        timer: 3000
    });
</script>
<?php unset($_SESSION['success']); endif; ?>
