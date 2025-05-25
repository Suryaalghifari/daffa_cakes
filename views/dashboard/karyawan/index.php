<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'karyawan') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Query jumlah produksi hari ini
$produksi_hari_ini = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT SUM(jumlah_dibuat) AS total 
    FROM produksi 
    WHERE user_id = $user_id AND tanggal = CURDATE()
"))['total'] ?? 0;

// Query produk stok hampir habis (<=5)
$produk_kritis = mysqli_query($conn, "
    SELECT * FROM produk 
    WHERE stok <= 5 
    ORDER BY stok ASC 
    LIMIT 5
");

// Query semua produk
$produk = mysqli_query($conn, "
    SELECT * FROM produk 
    ORDER BY nama_produk ASC
");

// Query 5 produksi terakhir
$riwayat = mysqli_query($conn, "
    SELECT p.nama_produk, pr.jumlah_dibuat, pr.tanggal 
    FROM produksi pr 
    JOIN produk p ON p.produk_id = pr.produk_id 
    WHERE pr.user_id = $user_id 
    ORDER BY pr.tanggal DESC 
    LIMIT 5
");

include_once '../../layouts/header.php';
include_once '../../layouts/sidebar.php';
?>

<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800">Dashboard Karyawan</h1>

  <!-- Card Produksi Hari Ini -->
  <div class="row mb-4">
    <div class="col-md-4">
      <div class="card border-left-success shadow py-3 px-4">
        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Produksi Hari Ini</div>
        <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $produksi_hari_ini ?> kue</div>
      </div>
    </div>
  </div>

  <!-- Produk Stok Hampir Habis -->
  <div class="card shadow mb-4">
    <div class="card-header">
      <h6 class="m-0 font-weight-bold text-danger">Produk Hampir Habis (≤ 5)</h6>
    </div>
    <div class="card-body">
      <?php if (mysqli_num_rows($produk_kritis) > 0): ?>
        <ul class="list-group">
          <?php while ($p = mysqli_fetch_assoc($produk_kritis)) : ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?= htmlspecialchars($p['nama_produk']) ?>
              <span class="badge badge-warning badge-pill">Stok: <?= $p['stok'] ?></span>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <div class="text-muted">Semua produk dalam stok aman.</div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Daftar Produk -->
  <div class="card shadow mb-4">
    <div class="card-header"><h6 class="m-0 font-weight-bold text-primary">Daftar Produk</h6></div>
    <div class="card-body">
      <div class="row">
        <?php while ($p = mysqli_fetch_assoc($produk)) : ?>
          <div class="col-md-3 mb-3">
            <div class="card h-100 shadow-sm border border-secondary">
              <img src="/daffa_cakes/assets/img/produk/<?= $p['gambar'] ?: 'default.png' ?>" 
                   class="card-img-top" style="height:150px; object-fit:cover;">
              <div class="card-body p-3">
                <h6 class="card-title mb-1"><?= htmlspecialchars($p['nama_produk']) ?></h6>
                <p class="mb-2 fw-bold">Rp <?= number_format($p['harga']) ?></p>
                <span class="badge badge-info">Stok: <?= $p['stok'] ?></span>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>

  <!-- Riwayat Produksi -->
  <div class="card shadow mb-4">
    <div class="card-header"><h6 class="m-0 font-weight-bold text-secondary">Riwayat Produksi Anda</h6></div>
    <div class="card-body">
      <?php if (mysqli_num_rows($riwayat) > 0): ?>
        <table class="table table-sm table-bordered">
          <thead class="thead-light">
            <tr>
              <th>#</th>
              <th>Nama Produk</th>
              <th>Jumlah</th>
              <th>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; while ($r = mysqli_fetch_assoc($riwayat)) : ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($r['nama_produk']) ?></td>
                <td><?= $r['jumlah_dibuat'] ?></td>
                <td><?= $r['tanggal'] ?></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="text-muted">Belum ada aktivitas produksi.</p>
      <?php endif; ?>
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
