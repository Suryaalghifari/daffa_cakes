<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'chef') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

include_once '../../layouts/header.php';
include_once '../../layouts/sidebar.php';

$total_produk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM produk"))['total'];
$produk_terbaru = mysqli_query($conn, "
    SELECT p.*, k.nama_kategori 
    FROM produk p
    LEFT JOIN kategori k ON p.kategori_id = k.kategori_id
    ORDER BY p.created_at DESC
    LIMIT 5
");

?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Dashboard Chef</h1>

  <div class="row">
    <div class="col-md-4 mb-4">
      <div class="card border-left-info shadow py-3 px-4">
        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Produk</div>
        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_produk ?></div>
      </div>
    </div>
  </div>

<!-- Produk Terbaru -->
<div class="card shadow mb-4">
  <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Produk Terbaru</h6></div>
  <div class="card-body">
    <div class="row">
      <?php while ($p = mysqli_fetch_assoc($produk_terbaru)) : ?>
        <div class="col-md-3 mb-3">
          <div class="card h-100 shadow-sm border border-secondary">
            <?php if (!empty($p['gambar']) && file_exists("../../../assets/img/produk/" . $p['gambar'])): ?>
              <img src="/daffa_cakes/assets/img/produk/<?= $p['gambar'] ?>" class="card-img-top" style="height:150px; object-fit:cover;">
            <?php else: ?>
              <img src="/daffa_cakes/assets/img/produk/default.png" class="card-img-top" style="height:150px; object-fit:cover;">
            <?php endif; ?>
            <div class="card-body p-3">
              <h6 class="card-title mb-1"><?= htmlspecialchars($p['nama_produk']) ?></h6>
              <p class="mb-1 text-muted" style="font-size: 0.85rem;"><?= htmlspecialchars($p['nama_kategori'] ?? '-') ?></p>
              <p class="mb-2 fw-bold">Rp <?= number_format($p['harga']) ?></p>

              <!-- Badge stok diperjelas -->
              <div class="text-end">
                <span class="badge px-3 py-2" style="background-color: #ffc107; color: #212529; font-weight: 600; font-size: 0.85rem;">
                  Stok: <?= $p['stok'] ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>




<?php include_once '../../layouts/footer.php'; ?>

<!-- SweetAlert Welcome -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['success'])): ?>
<script>
  Swal.fire({
    icon: 'success',
    title: 'Login Berhasil!',
    text: '<?= $_SESSION['success']; ?>',
    timer: 3000,
    showConfirmButton: false
  });
</script>
<?php unset($_SESSION['success']); endif; ?>
