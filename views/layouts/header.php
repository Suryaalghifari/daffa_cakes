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
    <link href="/daffa_cakes/sb-admin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="/daffa_cakes/sb-admin/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/daffa_cakes/sb-admin/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">
<div id="wrapper">
