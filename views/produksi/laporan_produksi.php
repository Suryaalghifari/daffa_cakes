<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data semua laporan produksi
$query = mysqli_query($conn, "
    SELECT pr.tanggal, u.nama_lengkap AS karyawan, p.nama_produk, pr.jumlah_dibuat
    FROM produksi pr
    JOIN user u ON u.user_id = pr.user_id
    JOIN produk p ON p.produk_id = pr.produk_id
    ORDER BY pr.tanggal DESC
");
?>

<?php include_once '../layouts/header.php'; ?>
<?php include_once '../layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Laporan Produksi Karyawan</h1>

    <div class="card shadow mb-4">
        <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Data Produksi</h6></div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Nama Karyawan</th>
                        <th>Nama Produk</th>
                        <th>Jumlah Diproduksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['tanggal'] ?></td>
                        <td><?= htmlspecialchars($row['karyawan']) ?></td>
                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                        <td><?= $row['jumlah_dibuat'] ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once '../layouts/footer.php'; ?>
