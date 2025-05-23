<?php
session_start();
require_once '../../config/koneksi.php';
require_once '../../config/base_url.php';

// Hanya admin yang boleh akses
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Ambil data Transfer & QRIS
$transfer = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM metode_pembayaran WHERE metode = 'Transfer' LIMIT 1"));
$qris = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM metode_pembayaran WHERE metode = 'QRIS' LIMIT 1"));

$success = $_SESSION['success'] ?? null;
$error = $_SESSION['error'] ?? null;
unset($_SESSION['success'], $_SESSION['error']);

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Kelola Metode Pembayaran</h1>

  <form action="proses_update_pembayaran.php" method="POST" enctype="multipart/form-data" class="row">
    <!-- TRANSFER -->
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header bg-primary text-white">Transfer Bank</div>
        <div class="card-body">
          <input type="hidden" name="jenis[]" value="Transfer">
          <div class="form-group">
            <label>Nama Bank</label>
            <input type="text" name="nama_bank" class="form-control" value="<?= htmlspecialchars($transfer['nama_bank'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>No. Rekening</label>
            <input type="text" name="no_rekening" class="form-control" value="<?= htmlspecialchars($transfer['no_rekening'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label>Atas Nama</label>
            <input type="text" name="atas_nama" class="form-control" value="<?= htmlspecialchars($transfer['atas_nama'] ?? '') ?>">
          </div>
          <div class="mt-3 p-3 bg-light border rounded">
            <strong>Preview:</strong>
            <p><?= $transfer['nama_bank'] ?? '-' ?> - <?= $transfer['no_rekening'] ?? '-' ?></p>
            <p>a.n. <?= $transfer['atas_nama'] ?? '-' ?></p>
          </div>
        </div>
      </div>
    </div>

    <!-- QRIS -->
    <div class="col-md-6 mb-4">
      <div class="card">
        <div class="card-header bg-success text-white">QRIS</div>
        <div class="card-body">
          <input type="hidden" name="jenis[]" value="QRIS">
          <div class="form-group">
            <label>Upload QRIS (jpg/png)</label>
            <input type="file" name="gambar_qris" accept="image/*" class="form-control">
            <?php if (!empty($qris['gambar_qris']) && file_exists("../../assets/img/pembayaran/" . $qris['gambar_qris'])): ?>
              <div class="mt-3 text-center">
                <img src="<?= BASE_URL ?>assets/img/pembayaran/<?= $qris['gambar_qris'] ?>" width="200">
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- TOMBOL -->
    <div class="col-12 text-end">
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
  </form>
</div>

<?php include_once '../layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if ($success): ?>
Swal.fire({
  icon: 'success',
  title: 'Berhasil',
  text: '<?= addslashes($success) ?>',
  confirmButtonText: 'Oke'
});
<?php elseif ($error): ?>
Swal.fire({
  icon: 'error',
  title: 'Gagal',
  text: '<?= addslashes($error) ?>',
  confirmButtonText: 'Tutup'
});
<?php endif; ?>
</script>