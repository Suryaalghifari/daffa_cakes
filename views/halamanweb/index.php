<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once 'base_url.php';
include 'templates/header.php';

$produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY nama_produk ASC");
?>

<main class="main">
  <!-- Hero Section -->
  <section id="hero" class="hero section light-background">
    <div class="container">
      <div class="row gy-4 justify-content-center justify-content-lg-between">
        <div class="col-lg-5 order-2 order-lg-1 d-flex flex-column justify-content-center">
          <h1 data-aos="fade-up">Selamat Datang di <span class="highlight">Daffa Cakes</span></h1>
          <p data-aos="fade-up" data-aos-delay="100">
            Nikmati kue basah dan kue kering rumahan dengan rasa premium. Cocok untuk segala suasana.
          </p>
        </div>
        <div class="col-lg-5 order-1 order-lg-2 hero-img" data-aos="zoom-out">
          <img src="<?= ASSET_WEB ?>img/menuPempek.png" class="img-fluid animated" alt="">
        </div>
      </div>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="about section">
    <div class="container section-title" data-aos="fade-up">
      <div class="flex-container">
        <div class="text-content">
          <h2>Tentang Kami</h2>
          <p><span>Daffa Cakes telah melayani pelanggan selama lebih dari 15 tahun dengan beragam pilihan kue tradisional hingga modern.</span></p>
        </div>
        <div class="box-container">
          <div class="why-box box-1">
            <h3>15</h3>
            <p class="first-p">Tahun</p>
          </div>
          <div class="why-box box-2">
            <h4>5+</h4>
            <p class="second-p">Cabang</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Menu Section -->
  <section id="menu" class="menu section">
    <div class="container section-title" data-aos="fade-up">
      <h2>Menu Kami</h2>
      <p class="text-muted">Silakan pilih kue favoritmu dan lakukan pemesanan secara online.</p>
    </div>
    <?php include 'partials/produk_card.php'; ?>
  </section>

  <!-- Contact Section -->
  <!-- Contact Section -->
<section id="contact" class="contact section">
  <div class="container section-title" data-aos="fade-up">
    <h2>Kontak</h2>
    <p><span>Buth Kami??</span> <span class="description-title">Hubungi Kami Segera</span></p>
  </div>

  <div class="container" data-aos="fade-up" data-aos-delay="100">
    <!-- Google Maps Embed -->
    <div class="mb-5">
      <iframe style="width: 100%; height: 400px; border:0;" 
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d253805.8723810032!2d106.64066280975514!3d-6.595038102771843!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69c5c5ccba8e5b%3A0x3030bfbcaf770b0!2sBogor%2C%20Kota%20Bogor%2C%20Jawa%20Barat!5e0!3m2!1sid!2sid!4v1715311123456" 
              allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>

    <!-- Contact Info Boxes -->
   <!-- Contact Info Boxes -->
<div class="row gy-4">
  <div class="col-md-6">
    <div class="info-item d-flex align-items-center shadow-sm p-3 bg-white rounded">
      <div class="fs-3 me-3">📍</div>
      <div>
        <h5 class="mb-1">Alamat</h5>
        <p>Jl. Contoh No.123, Bogor</p>
      </div>
    </div>
  </div>

    <div class="col-md-6">
        <div class="info-item d-flex align-items-center shadow-sm p-3 bg-white rounded">
        <div class="fs-3 me-3">📞</div>
        <div>
            <h5 class="mb-1">Telepon</h5>
            <p>+62 812 3456 7890</p>
        </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-item d-flex align-items-center shadow-sm p-3 bg-white rounded">
        <div class="fs-3 me-3">✉️</div>
        <div>
            <h5 class="mb-1">Email</h5>
            <p>info@daffacakes.id</p>
        </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="info-item d-flex align-items-center shadow-sm p-3 bg-white rounded">
        <div class="fs-3 me-3">⏰</div>
        <div>
            <h5 class="mb-1">Jam Operasional</h5>
            <p><strong>Senin–Sabtu:</strong> 08.00 - 21.00<br><strong>Minggu:</strong> Tutup</p>
        </div>
        </div>
    </div>
    </div>

    </div>
  </div>
</section>

</main>

<?php include 'templates/footer.php'; ?>
