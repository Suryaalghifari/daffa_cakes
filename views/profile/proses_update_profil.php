<?php
session_start();
require_once '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = trim($_POST['username']);

    // Upload foto
    $foto = $_FILES['foto']['name'];
    if ($foto) {
        $ext = pathinfo($foto, PATHINFO_EXTENSION);
        $nama_file = uniqid() . '.' . $ext;
        $path = "../../assets/img/user/" . $nama_file;
        move_uploaded_file($_FILES['foto']['tmp_name'], $path);

        // Simpan dengan foto
        $sql = "UPDATE user SET username = ?, foto = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $username, $nama_file, $id);
    } else {
        // Tanpa ganti foto
        $sql = "UPDATE user SET username = ? WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "si", $username, $id);
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['user']['username'] = $username;
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
