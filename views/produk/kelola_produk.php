<?php
session_start();
require_once '../../config/koneksi.php';

// Hanya admin yang boleh akses
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'chef'])) {
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
                    <button onclick="hapusProduk(<?= $row['produk_id'] ?>)" class="btn btn-danger btn-sm">Hapus</button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include_once '../layouts/footer.php'; ?>

<!-- SweetAlert Notification -->
<?php if (isset($_SESSION['success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Sukses!',
    text: '<?= $_SESSION['success'] ?>',
    timer: 2000,
    showConfirmButton: false
});
</script>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops!',
    text: '<?= $_SESSION['error'] ?>',
});
</script>
<?php unset($_SESSION['error']); endif; ?>

<script>
// SweetAlert untuk konfirmasi hapus
function hapusProduk(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data produk akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'hapus_produk.php?id=' + id;
        }
    });
}
</script>
