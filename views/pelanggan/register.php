<?php
session_start();
require_once '../../config/koneksi.php';

$reg_success = $reg_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);

    $cek = mysqli_query($conn, "SELECT * FROM pelanggan WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        $reg_error = "Email sudah terdaftar.";
    } else {
        $simpan = mysqli_query($conn, "INSERT INTO pelanggan (nama_lengkap, email, password, alamat, no_hp)
            VALUES ('$nama', '$email', '$password', '$alamat', '$no_hp')");
        if ($simpan) {
            $reg_success = "Pendaftaran berhasil! Silakan login.";
        } else {
            $reg_error = "Gagal mendaftar. Silakan coba lagi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun Pelanggan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="/daffa_cakes/assets/img/logo/daffa_logo.png" type="image/png">
    <link href="/daffa_cakes/sb-admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/daffa_cakes/sb-admin/css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gradient-primary">

<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-xl-7 col-lg-8 col-md-9">
            <div class="card shadow border-0 my-5">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="/daffa_cakes/assets/img/logo/daffa_logo.png" alt="Logo" width="60">
                        <h4 class="text-gray-900 mt-2">Pendaftaran Pelanggan</h4>
                    </div>
                    <form method="POST">
                        <div class="form-group mb-2">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="form-group mb-4">
                            <label>No HP</label>
                            <input type="text" name="no_hp" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Daftar Sekarang</button>
                        <p class="text-center mt-3 mb-0">
                            Sudah punya akun? <a href="login.php">Login di sini</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS SB Admin -->
<script src="/daffa_cakes/sb-admin/vendor/jquery/jquery.min.js"></script>
<script src="/daffa_cakes/sb-admin/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/daffa_cakes/sb-admin/js/sb-admin-2.min.js"></script>

<!-- SweetAlert -->
<?php if ($reg_error) : ?>
<script>
Swal.fire("Gagal", <?= json_encode($reg_error) ?>, "error");
</script>
<?php endif; ?>

<?php if ($reg_success) : ?>
<script>
Swal.fire({
    title: 'Berhasil',
    text: <?= json_encode($reg_success) ?>,
    icon: 'success',
    confirmButtonText: 'Login Sekarang'
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = "login.php";
    }
});
</script>
<?php endif; ?>

</body>
</html>
