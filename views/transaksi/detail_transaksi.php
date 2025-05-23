<?php
session_start();
require_once '../../config/koneksi.php';
require_once '../../config/base_url.php';

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

$data = mysqli_query($conn, "
    SELECT t.*, u.nama_lengkap AS kasir, pl.nama_lengkap AS pelanggan
    FROM transaksi t
    LEFT JOIN user u ON t.kasir_id = u.user_id
    LEFT JOIN pelanggan pl ON t.pelanggan_id = pl.pelanggan_id
    WHERE t.transaksi_id = $id
");
$transaksi = mysqli_fetch_assoc($data);
if (!$transaksi) {
    $_SESSION['error'] = "Transaksi tidak ditemukan.";
    header("Location: riwayat_penjualan.php");
    exit;
}

$produk = mysqli_query($conn, "
    SELECT td.*, p.nama_produk
    FROM transaksi_detail td
    JOIN produk p ON td.produk_id = p.produk_id
    WHERE td.transaksi_id = $id
");

$pembayaran = mysqli_query($conn, "SELECT * FROM pembayaran WHERE transaksi_id = $id");
$bayar = mysqli_fetch_assoc($pembayaran);

include_once '../layouts/header.php';
include_once '../layouts/sidebar.php';
?>

<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Detail Transaksi #<?= $transaksi['transaksi_id'] ?></h1>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Waktu:</strong> <?= date('d/m/Y H:i', strtotime($transaksi['waktu'])) ?></p>

            <p><strong>Kasir:</strong>
                <?php if ($transaksi['kasir']) : ?>
                    <?= htmlspecialchars($transaksi['kasir']) ?>
                <?php else : ?>
                    <em>Tidak diketahui</em>
                <?php endif; ?>
            </p>

            <?php if ($transaksi['pelanggan']) : ?>
                <p><strong>Pelanggan:</strong> <?= htmlspecialchars($transaksi['pelanggan']) ?></p>
            <?php endif; ?>
            </p>
            <p><strong>Status:</strong>
                <?= $transaksi['status'] === 'valid'
                    ? '<span class="text-success">Valid</span>'
                    : '<span class="text-warning">Pending</span>' ?>
            </p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">Produk</div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($p = mysqli_fetch_assoc($produk)) : ?>
                        <tr>
                            <td><?= htmlspecialchars($p['nama_produk']) ?></td>
                            <td>Rp <?= number_format($p['harga_saat_ini']) ?></td>
                            <td><?= $p['jumlah'] ?></td>
                            <td>Rp <?= number_format($p['harga_saat_ini'] * $p['jumlah']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Pembayaran</div>
        <div class="card-body px-3 py-4">
            <?php if ($bayar): ?>
                <table class="table table-borderless mb-0" style="font-size: 15px;">
                    <tbody>
                        <tr>
                            <th style="width: 30%;">Metode</th>
                            <td>: <?= htmlspecialchars($bayar['metode']) ?></td>
                        </tr>
                        <tr>
                            <th>Jumlah Dibayar</th>
                            <td>: Rp <?= number_format($bayar['jumlah_dibayar']) ?></td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td>: Rp <?= number_format($transaksi['total_harga']) ?></td>
                        </tr>
                        <tr>
                            <th>Kembalian</th>
                            <td>: Rp <?= number_format($bayar['jumlah_dibayar'] - $transaksi['total_harga']) ?></td>
                        </tr>
                        <?php if (!empty($bayar['alamat'])) : ?>
                        <tr>
                            <th>Alamat Pengiriman</th>
                            <td>: <?= nl2br(htmlspecialchars($bayar['alamat'])) ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (!empty($bayar['bukti']) && file_exists(__DIR__ . '/../../assets/img/bukti/' . $bayar['bukti'])) : ?>
                        <tr>
                            <th style="vertical-align: top;">Bukti Pembayaran</th>
                            <td>
                                <a href="<?= BASE_URL ?>assets/img/bukti/<?= htmlspecialchars($bayar['bukti']) ?>" target="_blank">
                                    <img src="<?= BASE_URL ?>assets/img/bukti/<?= htmlspecialchars($bayar['bukti']) ?>" 
                                         alt="Bukti" style="max-width: 180px; border: 1px solid #ccc; border-radius: 6px;">
                                </a><br>
                                <small><?= htmlspecialchars($bayar['bukti']) ?></small>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-danger">❗ Data pembayaran belum tersedia</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once '../layouts/footer.php'; ?>
