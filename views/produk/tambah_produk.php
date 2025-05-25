<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

$kategori = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Tambah Produk</h1>

    <form action="proses_tambah_produk.php" method="POST" enctype="multipart/form-data" autocomplete="off">
        <div class="form-group">
            <label>Nama Produk</label>
            <input type="text" name="nama_produk" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Kategori</label>
            <select name="kategori_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($row = mysqli_fetch_assoc($kategori)) : ?>
                    <option value="<?= $row['kategori_id'] ?>"><?= htmlspecialchars($row['nama_kategori']) ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="text" name="harga" id="harga" class="form-control" required oninput="formatRupiah(this)">
        </div>
        <div class="form-group">
            <label>Stok</label>
            <input type="number" name="stok" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Gambar Produk</label>
            <input type="file" name="gambar" class="form-control-file" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success" id="submitBtn">
            <i class="fas fa-save"></i> Simpan
        </button>
        <a href="kelola_produk.php" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
function formatRupiah(input) {
    let value = input.value.replace(/[^\d]/g, '');
    input.value = value ? 'Rp ' + new Intl.NumberFormat('id-ID').format(value) : '';
}

document.getElementById('submitBtn')?.addEventListener('click', function () {
    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
});
</script>

<?php include_once '../layouts/footer.php'; ?>

<?php if (isset($_SESSION['success'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
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

