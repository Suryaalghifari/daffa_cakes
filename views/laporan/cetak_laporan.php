<?php
session_start();
require_once '../../config/koneksi.php';

$type = $_GET['type'] ?? '';
$date = $_GET['date'] ?? '';

// Locale ID
setlocale(LC_TIME, 'id_ID.utf8');
if (!function_exists('strftime')) {
    function strftime($format, $timestamp = null) {
        return date($format, $timestamp ?? time());
    }
}

if ($type === 'harian') {
    $query = "
        SELECT SUM(total_harga) AS total, COUNT(*) AS total_transaksi 
        FROM transaksi 
        WHERE DATE(waktu) = '$date'
    ";
    $judul = "Laporan Harian";
    $tanggal = strftime('%A, %d %B %Y', strtotime($date));
} elseif ($type === 'bulanan') {
    $query = "
        SELECT SUM(total_harga) AS total, COUNT(*) AS total_transaksi 
        FROM transaksi 
        WHERE DATE_FORMAT(waktu, '%Y-%m') = '$date'
    ";
    $judul = "Laporan Bulanan";
    $tanggal = strftime('%B %Y', strtotime($date));
} else {
    die("Jenis laporan tidak valid.");
}

$laporan = mysqli_query($conn, $query);
$laporan_data = mysqli_fetch_assoc($laporan);

$pendapatan = number_format($laporan_data['total'] ?? 0);
$total_transaksi = $laporan_data['total_transaksi'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $judul ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; text-align: center; }
        h1, h3 { margin: 0; }
        .tanggal { margin: 10px 0 30px; font-size: 18px; font-weight: normal; color: #555; }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 70%;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 12px 15px;
            font-size: 16px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .btn-print {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 25px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-print:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 50px;
            color: #777;
            font-size: 14px;
        }

        @media print {
            .btn-print, .footer {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <h1><?= $judul ?></h1>
    <div class="tanggal"><?= $tanggal ?></div>

    <table>
        <thead>
            <tr>
                <th>Total Pendapatan</th>
                <th>Total Transaksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Rp <?= $pendapatan ?></td>
                <td><?= $total_transaksi ?></td>
            </tr>
        </tbody>
    </table>

    <a href="#" onclick="window.print()" class="btn-print">🖨 Cetak Laporan</a>

    <div class="footer">
        &copy; <?= date('Y') ?> Daffa Cakes | Laporan otomatis
    </div>

</body>
</html>
