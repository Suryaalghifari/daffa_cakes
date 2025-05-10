<?php
session_start();
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = trim($_POST['username']);
    $nama_lengkap = trim($_POST['nama_lengkap']);

    $foto = $_FILES['foto']['name'];
    if ($foto) {
        $ext = pathinfo($foto, PATHINFO_EXTENSION);
        $nama_file = uniqid() . '.' . $ext;
        $path = "../../assets/img/user/" . $nama_file;
        move_uploaded_file($_FILES['foto']['tmp_name'], $path);

        // Query update dengan foto
        $sql = "UPDATE user SET username = ?, nama_lengkap = ?, foto = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $username, $nama_lengkap, $nama_file, $id);
    } else {
        // Query update tanpa ganti foto
        $sql = "UPDATE user SET username = ?, nama_lengkap = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $nama_lengkap, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        // Update session
        $_SESSION['user']['username'] = $username;
        $_SESSION['user']['nama_lengkap'] = $nama_lengkap;
        if ($foto) {
            $_SESSION['user']['foto'] = $nama_file;
        }

        $_SESSION['success'] = "Profil berhasil diperbarui.";
    } else {
        $_SESSION['error'] = "Gagal memperbarui profil.";
    }

    header("Location: profil_saya.php");
    exit;
}
