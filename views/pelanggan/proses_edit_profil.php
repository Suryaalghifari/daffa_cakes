<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['pelanggan_id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama_lengkap']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];
$updated_at = date('Y-m-d H:i:s'); // 🆕 Waktu update

$foto_baru = null;
if (isset($_FILES['foto']['name']) && $_FILES['foto']['name'] !== '') {
    $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

    if (in_array(strtolower($ext), $allowed)) {
        $foto_baru = uniqid('pelanggan_') . '.' . $ext;
        $target_dir = __DIR__ . '/../halamanweb/assets/img/profile/';
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $foto_baru)) {
            $foto_baru = null;
        }
    }
}

$sql = "UPDATE pelanggan SET 
    nama_lengkap = '$nama',
    email = '$email',
    no_hp = '$no_hp',
    username = '$username',
    updated_at = '$updated_at'";

if (!empty($password)) {
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $sql .= ", password = '$password_hash'";
}
if ($foto_baru) {
    $sql .= ", foto = '$foto_baru'";
}

$sql .= " WHERE pelanggan_id = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: edit_profil.php?success=1");
} else {
    header("Location: edit_profil.php?error=1");
}
exit;
