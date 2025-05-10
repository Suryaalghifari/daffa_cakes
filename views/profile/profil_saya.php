<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$query = mysqli_query($conn, "SELECT * FROM user WHERE user_id = '$user_id'");
$data = mysqli_fetch_assoc($query);

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Profil Saya</h1>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="proses_update_profil.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $data['user_id'] ?>">

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?= $data['username'] ?>" required>
        </div>

        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($data['nama_lengkap'] ?? '') ?>" required>
        </div>

        <div class="form-group">
            <label>Foto Profil</label><br>
            <img src="/daffa_cakes/assets/img/user/<?= $data['foto'] ?? 'default.png' ?>" width="100"><br><br>
            <input type="file" name="foto" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

</div>

<?php include_once '../layouts/footer.php'; ?>
