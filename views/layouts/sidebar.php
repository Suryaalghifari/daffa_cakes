<?php
// Start session jika belum
if (session_status() === PHP_SESSION_NONE) session_start();

// Ambil koneksi database jika belum ada
if (!isset($conn)) {
    require_once __DIR__ . '/../../config/koneksi.php';
}

// Ambil user dari session
if (!isset($_SESSION['user'])) {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}
$user = $_SESSION['user']; // 💡 Pastikan ini array
$role = $user['role'];

// Ambil nama toko dari DB
$result = mysqli_query($conn, "SELECT nama_toko FROM toko WHERE id = 1");
$data_toko = mysqli_fetch_assoc($result);
$nama_toko = $data_toko['nama_toko'] ?? 'Toko Belum Diatur';
?>



<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Kueku.id</div>
    </a>

    <hr class="sidebar-divider">

    <?php if ($role === 'owner'): ?>
        <?php if ($role === 'owner'): ?>
            <li class="nav-item">
                <a class="nav-link" href="/daffa_cakes/views/dashboard/owner/index.php">
                    <i class="fas fa-home"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/daffa_cakes/views/toko/kelola_toko.php">
                    <i class="fas fa-cogs"></i><span>Pengaturan Toko</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/daffa_cakes/views/laporan/kelola_laporan.php">
                    <i class="fas fa-wrench"></i><span>Kelola Laporan</span>
                </a>
            </li>
<?php endif; ?>


    <?php elseif ($role === 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link" href="/daffa_cakes/views/dashboard/admin/index.php">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/daffa_cakes/views/produk/kelola_produk.php">
                <i class="fas fa-box-open"></i><span>Kelola Produk</span>
            </a>
        </li>
        <li class="nav-item">
                <a class="nav-link" href="/daffa_cakes/views/user/kelola_user.php">
                    <i class="fas fa-users-cog"></i><span>Kelola User</span>
                </a>
        </li>
        <li class="nav-item">
                <a class="nav-link" href="/daffa_cakes/views/metode_bayar/kelola_metode_pembayaran.php">
                    <i class="fas fa-solid fa-credit-card"></i><span>Metode Pembayaran</span>
                </a>
        </li>
        <li class="nav-item">
                <a class="nav-link" href="/daffa_cakes/views/laporan/kelola_laporan.php">
                    <i class="fas fa-wrench"></i><span>Kelola Laporan</span>
                </a>
            </li>

    <?php endif; ?>
    
    <?php if ($role === 'kasir'): ?>
        <li class="nav-item">
            <a class="nav-link" href="/daffa_cakes/views/dashboard/kasir/index.php">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/daffa_cakes/views/transaksi/kelola_pesanan.php">
                <i class="fas fa-cash-register"></i><span>Transaksi Baru</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="/daffa_cakes/views/transaksi/riwayat_penjualan.php">
                <i class="fas fa-history"></i><span>Riwayat Penjualan</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if ($role === 'chef'): ?>
        <li class="nav-item">
            <a class="nav-link" href="/daffa_cakes/views/dashboard/chef/index.php">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/daffa_cakes/views/produk/kelola_produk.php">
                <i class="fas fa-box-open"></i><span>Kelola Produk</span>
            </a>
        </li>
    <?php endif; ?>
    <hr class="sidebar-divider d-none d-md-block">
    <li class="nav-item">
        <a class="nav-link" href="/daffa_cakes/logout.php">
            <i class="fas fa-sign-out-alt"></i><span>Logout</span>
        </a>
    </li>

</ul>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Tombol toggle untuk desktop -->
            <button id="sidebarToggle" class="btn btn-link d-none d-md-inline rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Tombol toggle untuk mobile -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>


            <!-- User info -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                            <strong><?= htmlspecialchars($nama_toko) ?></strong> &nbsp;|&nbsp; 
                            ✨ <?= htmlspecialchars($user['nama_lengkap']) ?> (<?= ucfirst($user['role']) ?>)
                        </span>


                        <img class="img-profile rounded-circle"
                             src="/daffa_cakes/assets/img/user/<?= $user['foto'] ?? 'default.png'; ?>" width="30" height="30">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                         aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="/daffa_cakes/views/profile/profil_saya.php">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profil Saya
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/daffa_cakes/logout.php">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
