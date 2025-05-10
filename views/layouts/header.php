<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user'])) {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daffa Cakes - Dashboard</title>
    <link rel="icon" type="image/png" href="/daffa_cakes/assets/img/logo/daffa_logo.png">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SB Admin & FontAwesome -->
    <link href="/daffa_cakes/sb-admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/daffa_cakes/sb-admin/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/daffa_cakes/sb-admin/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Override font -->
    <style>
        body, .navbar, .sidebar, .card, .form-control, .btn, h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>
<body id="page-top">
<div id="wrapper">
