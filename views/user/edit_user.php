<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

$id = $_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM user WHERE user_id = $id");
$data = mysqli_fetch_assoc($result);

if (!$data) {
    $_SESSION['error'] = "User tidak ditemukan!";
    header("Location: kelola_user.php");
    exit;
}
?>

<?php include_once '../layouts/header.php'; include_once '../layouts/sidebar.php'; ?>

<div class="container-fluid">
    <h3 class="mb-4">Edit User</h3>

    <form action="proses_edit_user.php" method="POST">
        <input type="hidden" name="user_id" value="<?= $data['user_id'] ?>">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required value="<?= htmlspecialchars($data['username']) ?>" class="form-control">
        </div>
        <div class="form-group">
            <label>Password (Kosongkan jika tidak diubah)</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="owner" <?= $data['role'] == 'owner' ? 'selected' : '' ?>>Owner</option>
                <option value="admin" <?= $data['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="kasir" <?= $data['role'] == 'kasir' ? 'selected' : '' ?>>Kasir</option>
                <option value="karyawan" <?= $data['role'] == 'karyawan' ? 'selected' : '' ?>>karyawan</option>
            </select>
            </div>
        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="kelola_user.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<?php include_once '../layouts/footer.php'; ?>
