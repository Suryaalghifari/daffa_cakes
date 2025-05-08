<?php
session_start();
include_once '../../layouts/header.php';
include_once '../../layouts/sidebar.php';
?>

<!-- Konten khusus halaman dashboard -->
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Dashboard Owner</h1>

    <div class="alert alert-success">
        Selamat datang, <strong><?= htmlspecialchars($_SESSION['user']['username']); ?></strong>! Anda login sebagai <strong>Owner</strong>.
    </div>

    <div class="card shadow">
        <div class="card-body">
            <p>Gunakan menu di bawah untuk mengelola sistem:</p>
            <a href="/daffa_cakes/views/user/kelola_user.php" class="btn btn-primary">
                <i class="fa fa-users-cog"></i> Kelola User
            </a>
        </div>
    </div>
</div>

<?php include_once '../../layouts/footer.php'; ?>
