<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

$id = $_GET['id'] ?? 0;
$result = mysqli_query($conn, "SELECT * FROM produk WHERE produk_id = $id");
$produk = mysqli_fetch_assoc($result);

$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Produk</h1>

    <form action="proses_edit_produk.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $produk['produk_id'] ?>">

        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" value="<?= $produk['nama_produk'] ?>" required>
        </div>

        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <?php while ($row = mysqli_fetch_assoc($kategori)) : ?>
                    <option value="<?= $row['kategori_id'] ?>" <?= $row['kategori_id'] == $produk['kategori_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['nama_kategori']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="<?= $produk['harga'] ?>" required>
        </div>

        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $produk['stok'] ?>" required>
        </div>

        <div class="form-group">
            <label>Gambar Sekarang</label><br>
            <?php if ($produk['gambar']) : ?>
                <img src="/daffa_cakes/assets/img/produk/<?= $produk['gambar'] ?>" width="100"><br><br>
            <?php endif; ?>
            <input type="file" name="gambar" class="form-control-file">
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti.</small>
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="kelola_produk.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php include_once '../layouts/footer.php'; ?>

<?php if (isset($_SESSION['success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Perubahan Disimpan!',
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
    title: 'Gagal!',
    text: '<?= $_SESSION['error'] ?>'
});
</script>
<?php unset($_SESSION['error']); endif; ?>

