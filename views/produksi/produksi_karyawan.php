<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'karyawan') {
    header("Location: ../auth/login.php");
    exit;
}

$produk = mysqli_query($conn, "SELECT * FROM produk");
$produk_array = [];
while ($row = mysqli_fetch_assoc($produk)) {
    $produk_array[] = $row;
}
?>

<?php include_once '../layouts/header.php'; ?>
<?php include_once '../layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Input Produksi Kue (Multi Produk)</h1>

    <form id="formProduksi" method="POST" action="simpan_produksi.php">
        <div class="form-group">
            <label for="tanggal">Tanggal Produksi</label>
            <input type="date" class="form-control" name="tanggal" required>
        </div>

        <div id="produk-wrapper">
            <div class="row mb-2 produk-row">
                <div class="col-md-6">
                    <label>Jenis Kue</label>
                    <select name="produk_id[]" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php foreach ($produk_array as $p) : ?>
                            <option value="<?= $p['produk_id'] ?>">
                                <?= htmlspecialchars($p['nama_produk']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah[]" class="form-control" required min="1">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-info mb-3" onclick="tambahProduk()">+ Tambah Produk</button>
        <br>
        <button type="submit" class="btn btn-success">Simpan Produksi</button>
    </form>
</div>

<?php include_once '../layouts/footer.php'; ?>

<script>
const produkData = <?= json_encode($produk_array) ?>;

function tambahProduk() {
    const wrapper = document.getElementById('produk-wrapper');

    const newRow = document.createElement('div');
    newRow.classList.add('row', 'mb-2', 'produk-row');

    newRow.innerHTML = `
        <div class="col-md-6">
            <select name="produk_id[]" class="form-control" required>
                <option value="">-- Pilih Produk --</option>
                ${produkData.map(p => `<option value="${p.produk_id}">${p.nama_produk}</option>`).join('')}
            </select>
        </div>
        <div class="col-md-4">
            <input type="number" name="jumlah[]" class="form-control" required min="1">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm remove-row">Hapus</button>
        </div>
    `;

    wrapper.appendChild(newRow);
}

document.addEventListener("click", function(e) {
    if (e.target && e.target.classList.contains('remove-row')) {
        e.target.closest('.produk-row').remove();
    }
});
</script>

<!-- SweetAlert Notifikasi -->
<?php if (isset($_SESSION['success'])): ?>
<script>
Swal.fire({
  icon: 'success',
  title: 'Berhasil!',
  text: '<?= $_SESSION['success'] ?>',
  timer: 3000,
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
