<div class="container py-5">
  <div class="row">
    <?php while ($p = mysqli_fetch_assoc($produk)) : ?>
      <div class="col-md-4 mb-4">
        <div class="card h-100 border-0 shadow-sm">
          <img src="<?= BASE_URL ?>assets/img/produk/<?= $p['gambar'] ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="<?= htmlspecialchars($p['nama_produk']) ?>">
          <div class="card-body">
            <small class="text-uppercase text-muted">
              <?= isset($p['kategori']) ? htmlspecialchars($p['kategori']) : 'Produk' ?>
            </small>
            <h5 class="card-title mt-1"><?= htmlspecialchars($p['nama_produk']) ?></h5>
            <div class="mb-2">
              <span class="text-warning">★★★★★</span>
              <span class="text-muted small">(5.0)</span>
            </div>
            <p class="fw-bold text-primary">Rp <?= number_format($p['harga']) ?></p>
            <?php if (isset($_SESSION['pelanggan_id'])): ?>
              <a href="<?= BASE_URL ?>views/pelanggan/checkout.php?id=<?= $p['produk_id'] ?>" class="btn btn-outline-primary btn-sm">Pesan Sekarang</a>
            <?php else: ?>
              <button onclick="redirectLogin()" class="btn btn-outline-secondary btn-sm">Pesan</button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<script>
function redirectLogin() {
  Swal.fire({
    title: 'Login Dulu Yuk!',
    text: 'Untuk memesan produk, kamu harus login sebagai pelanggan.',
    icon: 'info',
    confirmButtonText: 'Login Sekarang',
    showCancelButton: true,
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "<?= BASE_URL ?>views/pelanggan/login.php";
    }
  });
}
</script>
