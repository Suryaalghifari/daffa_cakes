<?php
session_start();
require_once '../../config/koneksi.php';

// Hanya admin yang boleh akses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';

// Ambil data produk + join kategori
$query = mysqli_query($conn, "
    SELECT produk.*, kategori.nama_kategori 
    FROM produk 
    LEFT JOIN kategori ON produk.kategori_id = kategori.kategori_id 
    ORDER BY produk_id DESC
");
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kelola Produk</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <a href="tambah_produk.php" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Produk
    </a>

    <table class="table table-bordered table-hover">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
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
                <td>
                    <a href="edit_produk.php?id=<?= $row['produk_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="hapus_produk.php?id=<?= $row['produk_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus produk ini?')">Hapus</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include_once '../layouts/footer.php'; ?>
