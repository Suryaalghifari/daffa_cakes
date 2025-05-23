<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'owner') {
    header("Location: ../auth/login.php");
    exit;
}


// Proses jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_toko = trim($_POST['nama_toko']);
    $alamat_toko = trim($_POST['alamat_toko']);
    $no_telp = trim($_POST['no_telp']);

    $update = mysqli_query($conn, "
        UPDATE toko 
        SET nama_toko = '$nama_toko',
            alamat_toko = '$alamat_toko',
            no_telp = '$no_telp'
        WHERE id = 1
    ");

    $_SESSION['success'] = $update ? "Data toko berhasil diperbarui." : "Gagal memperbarui data toko.";
    header("Location: kelola_toko.php");
    exit;
}


$toko = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM toko WHERE id = 1"));

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pengaturan Toko</h1>

    <?php
    $successMessage = $_SESSION['success'] ?? null;
    unset($_SESSION['success']);
    $errorMessage = $_SESSION['error'] ?? null;
    unset($_SESSION['error']);
    ?>


    <form method="POST">
        <div class="form-group">
            <label>Nama Toko</label>
            <input type="text" name="nama_toko" class="form-control" value="<?= htmlspecialchars($toko['nama_toko']) ?>" required>
        </div>
        <div class="form-group">
            <label>Alamat Toko</label>
            <textarea name="alamat_toko" class="form-control" required><?= htmlspecialchars($toko['alamat_toko']) ?></textarea>
        </div>
        <div class="form-group">
            <label>No. Telepon</label>
            <input type="text" name="no_telp" class="form-control" value="<?= htmlspecialchars($toko['no_telp']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<?php include_once '../layouts/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if ($successMessage): ?>
  Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: '<?= addslashes($successMessage) ?>',
    confirmButtonText: 'OK'
  });
<?php elseif ($errorMessage): ?>
  Swal.fire({
    icon: 'error',
    title: 'Gagal',
    text: '<?= addslashes($errorMessage) ?>',
    confirmButtonText: 'Coba Lagi'
  });
<?php endif; ?>
</script>
