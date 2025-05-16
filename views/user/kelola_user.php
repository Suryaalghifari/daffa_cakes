<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

// Hanya untuk owner
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';

// Ambil semua user dari database
$query = mysqli_query($conn, "SELECT * FROM user ORDER BY user_id ASC");
?>

<!-- Konten Halaman -->
<div class="container-fluid">
    <h2 class="mb-4">Kelola User</h2>
    <!-- Form Tambah User -->
    <form action="tambah_user.php" method="POST" class="mb-4">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required class="form-control">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required class="form-control">
        </div>
        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="">-- Pilih Role --</option>
                <option value="admin">Admin</option>
                <option value="kasir">Kasir</option>
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Tambah User</button>
    </form>

    <!-- Daftar User -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
           <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?= $row['user_id'] ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= $row['role'] ?></td>
                    <td><?= $row['created_at'] ?></td>
                    <td>
                        <a href="edit_user.php?id=<?= $row['user_id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <button onclick="hapusUser(<?= $row['user_id'] ?>)" class="btn btn-sm btn-danger">Hapus</button>
                    </td>
                </tr>
            <?php endwhile; ?>

        </tbody>
    </table>
</div>

<?php include_once '../layouts/footer.php'; ?>
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Konfirmasi Hapus -->
<script>
function hapusUser(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus user ini?',
        text: 'Data akan terhapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'hapus_user.php?id=' + id;
        }
    });
}
</script>

<!-- SweetAlert: Success -->
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

<!-- SweetAlert: Error -->
<?php if (isset($_SESSION['error'])): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Gagal!',
    text: '<?= $_SESSION['error'] ?>',
});
</script>
<?php unset($_SESSION['error']); endif; ?>
