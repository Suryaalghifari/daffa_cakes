<?php $role = $user['role']; ?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-store"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Daffa Cakes</div>
    </a>

    <hr class="sidebar-divider">

    <?php if ($role === 'owner'): ?>
        <li class="nav-item">
            <a class="nav-link" href="/daffa_cakes/views/dashboard/owner/index.php">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/daffa_cakes/views/user/kelola_user.php">
                <i class="fas fa-users-cog"></i><span>Kelola User</span>
            </a>
        </li>
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

            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- User info -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                            <?= htmlspecialchars($user['username']) ?> (<?= $user['role'] ?>)
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
