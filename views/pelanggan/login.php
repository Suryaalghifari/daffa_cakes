<?php
session_start();
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = mysqli_query($conn, "SELECT * FROM pelanggan WHERE email = '$email'");
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        // Simpan hanya ID pelanggan di session
        $_SESSION['pelanggan_id'] = $user['pelanggan_id'];
        $_SESSION['login_success'] = "Selamat datang, {$user['nama_lengkap']}";
        header("Location: /daffa_cakes/views/halamanweb/index.php");
        exit;
    } else {
        $_SESSION['login_error'] = "Email atau password salah!";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Pelanggan - Daffa Cakes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="/daffa_cakes/assets/img/logo/daffa_logo.png">
    <link href="/daffa_cakes/sb-admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/daffa_cakes/sb-admin/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-primary">

<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-7 col-md-9">
            <div class="card shadow border-0 my-5">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="/daffa_cakes/assets/img/logo/daffa_logo.png" alt="Logo Daffa Cakes" style="width: 60px; height: 60px;">
                        <h1 class="h4 text-gray-900 mt-2">Login Pelanggan</h1>
                        <p>Silakan masuk untuk melakukan pemesanan</p>
                    </div>
                    <form method="POST">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control form-control-user" placeholder="Masukkan Email..." required autofocus>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control form-control-user" placeholder="Masukkan Password..." required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                        <hr>
                        <p class="text-center mb-0">
                            Belum punya akun? <a href="register.php">Daftar Sekarang</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="/daffa_cakes/sb-admin/vendor/jquery/jquery.min.js"></script>
<script src="/daffa_cakes/sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/daffa_cakes/sb-admin/js/sb-admin-2.min.js"></script>

<!-- SweetAlert Error -->
<?php if (isset($_SESSION['login_error'])) : ?>
<script>
    Swal.fire("Gagal", <?= json_encode($_SESSION['login_error']) ?>, "error");
</script>
<?php unset($_SESSION['login_error']); endif; ?>

</body>
</html>
