<?php
session_start();
require_once '../../config/koneksi.php';
require_once '../../config/base_url.php';

if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['pelanggan_id'];
$data = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id = $id");
$pelanggan = mysqli_fetch_assoc($data);

// Path gambar profil
$foto = $pelanggan['foto'] ?? '';
$fotoPath = file_exists(__DIR__ . '/../halamanweb/assets/img/profile/' . $foto)
    ? BASE_URL . 'views/halamanweb/assets/img/profile/' . $foto
    : BASE_URL . 'views/halamanweb/assets/img/profile/default.png';

include '../halamanweb/templates/header.php';
?>

<main class="main">
  <section class="section py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <h3 class="mb-4">Edit Profil Saya</h3>
          <a href="<?= BASE_URL ?>views/halamanweb/index.php" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
          </a>

          <form method="POST" action="proses_edit_profil.php" enctype="multipart/form-data" class="bg-white p-4 shadow rounded">
            <div class="mb-3">
              <label class="form-label">Nama Lengkap</label>
              <input type="text" name="nama_lengkap" class="form-control" value="<?= htmlspecialchars($pelanggan['nama_lengkap']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($pelanggan['email']) ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Nomor HP</label>
              <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($pelanggan['no_hp']) ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($pelanggan['username']) ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
              <input type="password" name="password" class="form-control">
            </div>
            <div class="mb-3">
              <label class="form-label">Foto Profil</label><br>
              <img src="<?= $fotoPath ?>?v=<?= time() ?>" width="100" class="mb-2 rounded-circle shadow-sm">
              <input type="file" name="foto" accept="image/*" class="form-control mt-2">
            </div>
            <div class="text-end">
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include '../halamanweb/templates/footer.php'; ?>

<?php if (isset($_GET['success'])): ?>
<script>
Swal.fire({
  title: "Berhasil",
  text: "Profil berhasil diperbarui.",
  icon: "success",
  confirmButtonText: "Oke"
});
</script>
<?php endif; ?>
