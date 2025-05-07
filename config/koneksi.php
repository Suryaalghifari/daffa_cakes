<?php
// config/koneksi.php

$host     = 'localhost';
$dbname   = 'daffa_cakes'; // Pastikan nama database sesuai dengan yang kamu import
$username = 'root';
$password = ''; // Kosong jika default XAMPP

try {
    $config = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $config->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
