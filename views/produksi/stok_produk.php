<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'karyawan') {
    header("Location: ../auth/login.php");
    exit;
}

$produk = mysqli_query($conn, "
    SELECT p.*, k.nama_kategori 
    FROM produk p
    LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
    ORDER BY p.nama_produk ASC
");
?>

<?php include_once '../layouts/header.php'; ?>
<?php include_once '../layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Stok Produk</h1>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h6 class="m-0">Daftar Stok Produk di Etalase</h6>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Gambar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; while ($row = mysqli_fetch_assoc($produk)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                            <td><?= htmlspecialchars($row['nama_kategori'] ?? '-') ?></td>
                            <td>Rp <?= number_format($row['harga']) ?></td>
                            <td><?= $row['stok'] ?></td>
                            <td>
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="/daffa_cakes/assets/img/produk/<?= $row['gambar'] ?>" width="60">
                                <?php else: ?>
                                    <span class="text-muted">Tidak ada</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once '../layouts/footer.php'; ?>
