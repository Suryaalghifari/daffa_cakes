<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../base_url.php';
require_once __DIR__ . '/../../../config/koneksi.php';

$pelanggan = null;
if (isset($_SESSION['pelanggan_id'])) {
  $id = $_SESSION['pelanggan_id'];
  $result = mysqli_query($conn, "SELECT * FROM pelanggan WHERE pelanggan_id = $id");
  $pelanggan = mysqli_fetch_assoc($result);
}

$currentPage = basename($_SERVER['PHP_SELF']);
$isIndex = $currentPage === 'index.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Daffa Cakes - Pelanggan</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="<?= ASSET_WEB ?>img/daffa_logo.png">
  
  <!-- Fonts & Styles -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="<?= ASSET_WEB ?>vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= ASSET_WEB ?>vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?= ASSET_WEB ?>vendor/aos/aos.css" rel="stylesheet">
  <link href="<?= ASSET_WEB ?>vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="<?= ASSET_WEB ?>vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
  <link href="<?= ASSET_WEB ?>css/main.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="index-page">

<header id="header" class="header d-flex align-items-center sticky-top">
  <div class="container position-relative d-flex align-items-center justify-content-between">
    <a href="<?= BASE_URL ?>views/halamanweb/index.php" class="logo d-flex align-items-center me-auto me-xl-0">
      <h1 class="sitename"><img src="<?= ASSET_WEB ?>img/daffa_logo.png" alt="Daffa Cakes" style="height: 40px;"></h1>
    </a>

    <nav id="navmenu" class="navmenu">
      <ul class="d-flex align-items-center gap-3">
        <li><a href="<?= $isIndex ? '#hero' : BASE_URL . 'views/halamanweb/index.php#hero' ?>">Home</a></li>
        <li><a href="<?= $isIndex ? '#about' : BASE_URL . 'views/halamanweb/index.php#about' ?>">Tentang</a></li>
        <li><a href="<?= $isIndex ? '#menu' : BASE_URL . 'views/halamanweb/index.php#menu' ?>">Product</a></li>
        <li><a href="<?= $isIndex ? '#contact' : BASE_URL . 'views/halamanweb/index.php#contact' ?>">Kontak</a></li>
        <?php if ($pelanggan): ?>
          <li><a href="<?= BASE_URL ?>views/pelanggan/riwayat_pelanggan.php">Riwayat</a></li>
          <?php
            $foto = $pelanggan['foto'] ?? '';
            $fotoPath = file_exists(__DIR__ . '/../assets/img/profile/' . $foto)
              ? BASE_URL . 'views/halamanweb/assets/img/profile/' . $foto
              : BASE_URL . 'views/halamanweb/assets/img/profile/default.png';
          ?>
          <li class="dropdown">
            <a href="#" class="dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
              <img src="<?= $fotoPath ?>?v=<?= time() ?>" class="rounded-circle me-2" width="30" height="30" alt="Profil">
              <?= htmlspecialchars($pelanggan['nama_lengkap']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="<?= BASE_URL ?>views/pelanggan/edit_profil.php">Edit Profil</a></li>
              <li><a class="dropdown-item" href="<?= BASE_URL ?>views/pelanggan/logout.php">Logout</a></li>
            </ul>
          </li>
        <?php else: ?>
          <li><a href="<?= BASE_URL ?>views/pelanggan/login.php">Login</a></li>
        <?php endif; ?>
      </ul>
      <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
    </nav>
  </div>
</header>
