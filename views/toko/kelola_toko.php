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
    $update = mysqli_query($conn, "UPDATE toko SET nama_toko = '$nama_toko' WHERE id = 1");

    $_SESSION['success'] = $update ? "Nama toko berhasil diperbarui." : "Gagal memperbarui nama toko.";
    header("Location: kelola_toko.php");
    exit;
}

$toko = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM toko WHERE id = 1"));

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Pengaturan Toko</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Nama Toko</label>
            <input type="text" name="nama_toko" class="form-control" value="<?= htmlspecialchars($toko['nama_toko']) ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>

<?php include_once '../layouts/footer.php'; ?>
