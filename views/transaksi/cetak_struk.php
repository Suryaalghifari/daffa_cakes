<?php
session_start();
require_once '../../config/koneksi.php';

// Autentikasi kasir
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'kasir') {
    header("Location: /daffa_cakes/views/auth/login.php");
    exit;
}

if (!isset($_GET['id'])) {
    $_SESSION['error'] = "ID transaksi tidak ditemukan.";
    header("Location: riwayat_penjualan.php");
    exit;
}

$id = (int) $_GET['id'];

// Ambil data transaksi
$transaksi = mysqli_query($conn, "
    SELECT t.*, u.nama_lengkap AS kasir
    FROM transaksi t
    LEFT JOIN user u ON t.kasir_id = u.user_id
    WHERE t.transaksi_id = $id
");
$data = mysqli_fetch_assoc($transaksi);

if (!$data) {
    $_SESSION['error'] = "Transaksi tidak ditemukan.";
    header("Location: riwayat_penjualan.php");
    exit;
}

// ⛔ Hanya transaksi valid yang bisa dicetak
if ($data['status'] !== 'valid') {
    $_SESSION['error'] = "Transaksi belum divalidasi, tidak dapat mencetak struk.";
    header("Location: riwayat_penjualan.php");
    exit;
}

// Ambil data detail produk
$produk = mysqli_query($conn, "
    SELECT td.*, p.nama_produk
    FROM transaksi_detail td
    JOIN produk p ON td.produk_id = p.produk_id
    WHERE td.transaksi_id = $id
");

// Pembayaran
$pembayaran = mysqli_query($conn, "SELECT * FROM pembayaran WHERE transaksi_id = $id");
$bayar = mysqli_fetch_assoc($pembayaran);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Struk</title>
    <style>
        body { font-family: monospace; max-width: 320px; margin: auto; font-size: 14px; }
        .center { text-align: center; }
        hr { border: none; border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; }
        td { vertical-align: top; }
    </style>
</head>
<body>

<div class="center">
    <h3>Daffa Cakes</h3>
    <small><?= date('d/m/Y H:i', strtotime($data['waktu'])) ?></small>
</div>
<hr>

<table>
    <tr><td><strong>ID</strong></td><td>: <?= $data['transaksi_id'] ?></td></tr>
    <?php if ($data['kasir']): ?>
    <tr><td><strong>Kasir</strong></td><td>: <?= $data['kasir'] ?></td></tr>
    <?php endif; ?>
</table>
<hr>

<?php
$total = 0;
while ($p = mysqli_fetch_assoc($produk)) :
    $sub = $p['jumlah'] * $p['harga_saat_ini'];
    $total += $sub;
?>
<p><?= $p['nama_produk'] ?> x<?= $p['jumlah'] ?>  
<span style="float:right">Rp <?= number_format($sub) ?></span></p>
<?php endwhile; ?>

<hr>
<p><strong>Total</strong> <span style="float:right">Rp <?= number_format($data['total_harga']) ?></span></p>

<?php if ($bayar): ?>
    <p><strong>Bayar</strong> <span style="float:right">Rp <?= number_format($bayar['jumlah_dibayar']) ?></span></p>
    <p><strong>Kembali</strong> <span style="float:right">Rp <?= number_format($bayar['kembalian']) ?></span></p>
<?php else: ?>
    <p class="text-danger">❗ Data pembayaran belum tersedia</p>
<?php endif; ?>

<hr>
<div class="center">
    <p>Terima Kasih 💖</p>
    <p>~ Daffa Cakes ~</p>
    <br>
    <button onclick="window.print()">🖨 Cetak</button>
</div>

</body>
</html>
