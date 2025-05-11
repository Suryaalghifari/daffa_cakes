<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'owner') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

include_once '../../layouts/header.php';
include_once '../../layouts/sidebar.php';

// Tanggal
$tanggal_hari_ini = date('Y-m-d');
$tanggal_bulan_ini = date('Y-m');

// Query Ringkasan
$pendapatan_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) AS total FROM transaksi WHERE DATE(waktu) = '$tanggal_hari_ini'"))['total'] ?? 0;
$pendapatan_bulan_ini = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(total_harga) AS total FROM transaksi WHERE DATE_FORMAT(waktu, '%Y-%m') = '$tanggal_bulan_ini'"))['total'] ?? 0;

$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk WHERE stok > 0"))['total'];
$total_user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM user WHERE role IN ('admin','kasir')"))['total'];

$daftar_pelanggan = mysqli_query($conn, "SELECT pelanggan_id, nama_lengkap, email, created_at, username FROM pelanggan ORDER BY created_at DESC LIMIT 5");

// Transaksi terakhir
$transaksi_terakhir = mysqli_query($conn, "SELECT * FROM transaksi ORDER BY waktu DESC LIMIT 5");

// Notifikasi validasi
$menunggu_validasi = mysqli_query($conn, "
    SELECT * FROM transaksi 
    WHERE metode IN ('transfer', 'qris') AND status = 'menunggu_validasi'
");

// Grafik penjualan bulanan
$penjualan_bulanan = mysqli_query($conn, "
    SELECT DATE_FORMAT(waktu, '%Y-%m') AS bulan, SUM(total_harga) AS total 
    FROM transaksi 
    WHERE waktu >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
    GROUP BY bulan
    ORDER BY bulan ASC
");

$bulan_array = [];
$total_array = [];
while ($row = mysqli_fetch_assoc($penjualan_bulanan)) {
    $bulan_array[] = $row['bulan'];
    $total_array[] = $row['total'];
}
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Owner</h1>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card border-left-primary shadow p-3">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pendapatan Hari Ini</div>
                <div class="h5 font-weight-bold text-gray-800">Rp <?= number_format($pendapatan_hari_ini) ?></div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-success shadow p-3">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pendapatan Bulan Ini</div>
                <div class="h5 font-weight-bold text-gray-800">Rp <?= number_format($pendapatan_bulan_ini) ?></div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-warning shadow p-3">
                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Produk Tersedia</div>
                <div class="h5 font-weight-bold text-gray-800"><?= $total_produk ?></div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-left-danger shadow p-3">
                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Admin & Kasir Aktif</div>
                <div class="h5 font-weight-bold text-gray-800"><?= $total_user ?></div>
            </div>
        </div>
    </div>

    <!-- Daftar Pelanggan -->
    <div class="card shadow mb-4">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-secondary">Pelanggan Terdaftar</h6>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered m-0">
                <thead class="thead-light">
                    <tr>
                        <th>Nama Pelanggan</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Ditambahkan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($pelanggan = mysqli_fetch_assoc($daftar_pelanggan)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($pelanggan['nama_lengkap']) ?></td>
                            <td><?= htmlspecialchars($pelanggan['email']) ?></td>
                            <td><?= htmlspecialchars($pelanggan['username']) ?></td>
                            <td><?= date('d M Y', strtotime($pelanggan['created_at'])) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grafik Penjualan Bulanan -->
    <div class="card shadow mb-4">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-info">Grafik Penjualan (12 Bulan)</h6></div>
        <div class="card-body">
        <canvas id="grafikPenjualan" 
            data-labels='<?= json_encode($bulan_array) ?>' 
            data-data='<?= json_encode($total_array) ?>'></canvas>

        </div>
    </div>

    <!-- Transaksi Terakhir -->
    <div class="card shadow mb-4">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">5 Transaksi Terakhir</h6></div>
        <div class="card-body">
            <ul class="list-group">
                <?php while ($t = mysqli_fetch_assoc($transaksi_terakhir)) : ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= date('d M Y H:i', strtotime($t['waktu'])) ?> - Rp <?= number_format($t['total_harga']) ?>
                        <span class="badge badge-pill badge-<?= $t['status'] == 'selesai' ? 'success' : 'warning' ?>">
                            <?= ucfirst($t['status']) ?>
                        </span>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div>

<?php include_once '../../layouts/footer.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/daffa_cakes/sb-admin/js/demo/chart-owner.js"></script>

<!-- SweetAlert Login -->
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
