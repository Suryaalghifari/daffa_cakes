<?php
session_start();
require_once '../../config/koneksi.php';
require_once '../../config/base_url.php';

if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../pelanggan/login.php");
    exit;
}

$pelanggan_id = $_SESSION['pelanggan_id'];
$pelanggan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id = $pelanggan_id"));

$foto = $pelanggan['foto'] ?? '';
$fotoPath = file_exists(__DIR__ . '/../halamanweb/assets/img/profile/' . $foto)
    ? BASE_URL . 'views/halamanweb/assets/img/profile/' . $foto
    : BASE_URL . 'views/halamanweb/assets/img/profile/default.png';

$transaksi = mysqli_query($conn, "SELECT * FROM transaksi WHERE pelanggan_id = $pelanggan_id ORDER BY waktu DESC");

include '../halamanweb/templates/header.php';
?>
<style>
html, body {
  height: 100%;
  display: flex;
  flex-direction: column;
}
main {
  flex: 1;
}
</style>
<main class="main">
  <section class="section py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <h3 class="mb-4">Riwayat Pesanan Saya</h3>
          <a href="<?= BASE_URL ?>views/halamanweb/index.php" class="btn btn-sm btn-outline-secondary mb-3">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
          </a>

          <p class="text-muted">Berikut adalah daftar semua transaksi pesanan kamu di Daffa Cakes. Silakan pantau status validasi dari kasir.</p>

          <?php if (mysqli_num_rows($transaksi) === 0): ?>
            <div class="alert alert-info">Kamu belum memiliki transaksi.</div>
          <?php else: ?>
            <p class="mb-3">Total pesanan: <strong><?= mysqli_num_rows($transaksi) ?></strong></p>
            <div class="table-responsive">
              <table class="table table-bordered table-striped text-center">
                <thead class="table-light">
                  <tr>
                    <th>ID</th>
                    <th>Waktu</th>
                    <th>Total</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($t = mysqli_fetch_assoc($transaksi)) : ?>
                    <tr>
                      <td><?= $t['transaksi_id'] ?></td>
                      <td><?= date('d/m/Y H:i', strtotime($t['waktu'])) ?></td>
                      <td>Rp <?= number_format($t['total_harga']) ?></td>
                      <td>
                        <?php if ($t['status'] === 'valid'): ?>
                          <span class="badge bg-success badge-status"><i class="fa fa-check-circle me-1"></i> Valid</span>
                        <?php else: ?>
                          <span class="badge bg-warning text-dark badge-status"><i class="fa fa-clock me-1"></i> Pending</span>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </section>
</main>

<?php include '../halamanweb/templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
