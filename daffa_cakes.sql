-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Bulan Mei 2025 pada 09.15
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `daffa_cakes`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `nama_kategori`) VALUES
(1, 'Kue Basah'),
(2, 'Kue Kering');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pelanggan`
--

CREATE TABLE `pelanggan` (
  `pelanggan_id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `foto` varchar(100) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pelanggan`
--

INSERT INTO `pelanggan` (`pelanggan_id`, `nama_lengkap`, `email`, `password`, `alamat`, `no_hp`, `created_at`, `updated_at`, `username`, `foto`) VALUES
(1, 'UyaSky', 'lily@rifkiidr.id', '$2y$10$76L4m0C1nPABgN0AANEti.qZ4NrmLxiqzv0ieKCDZ1TK9JzYd57rG', 'Jalan Dayung No 33', '085792438608', '2025-05-10 01:23:14', '2025-05-19 09:14:31', 'Genjor Ganteng', 'pelanggan_681f33b3a1789.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `pembayaran_id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `metode` enum('Tunai','Transfer','QRIS') NOT NULL,
  `jumlah_dibayar` int(11) NOT NULL,
  `kembalian` int(11) DEFAULT 0,
  `bukti` varchar(255) DEFAULT NULL,
  `alamat` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pembayaran`
--

INSERT INTO `pembayaran` (`pembayaran_id`, `transaksi_id`, `metode`, `jumlah_dibayar`, `kembalian`, `bukti`, `alamat`) VALUES
(16, 34, 'Transfer', 12000, 7000, NULL, NULL),
(23, 47, 'Transfer', 6500, 0, 'bukti_681f3ace672e1.png', 'Jalan Dayung No 33'),
(26, 50, 'QRIS', 3000, 0, 'bukti_681f4b2b89edf.png', 'Jalan Dayung No 33'),
(27, 51, 'Tunai', 3000, 500, NULL, NULL),
(28, 52, 'Tunai', 50000, 35000, NULL, NULL),
(29, 53, '', 3500, 0, NULL, 'Jalan Dayung No 33'),
(30, 54, 'Transfer', 12000, 10000, NULL, NULL),
(31, 55, 'Tunai', 30000, 10000, NULL, NULL),
(32, 56, 'Tunai', 3500, 500, NULL, NULL),
(33, 57, 'Tunai', 12000, 7000, NULL, NULL),
(35, 59, 'Transfer', 6500, 0, 'bukti_681fe5d0f29e2.png', 'Jalan Dayung No 33'),
(36, 60, 'Transfer', 3000, 0, 'bukti_681feb2c77e41.png', 'Jalan Dayung No 33'),
(37, 61, 'Transfer', 11000, 0, 'bukti_681fec74c65cb.png', 'Jalan Dayung No 33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `produk_id` int(11) NOT NULL,
  `nama_produk` varchar(100) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `kategori_id` int(11) DEFAULT NULL,
  `gambar` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`produk_id`, `nama_produk`, `harga`, `stok`, `kategori_id`, `gambar`, `created_at`) VALUES
(4, 'Kue Lemper', 3500, 0, 1, '681cec2aca2ae.png', '2025-05-09 00:38:50'),
(5, 'Kue Sus', 3000, 2, 1, '681d90ae39072.png', '2025-05-09 12:20:46'),
(6, 'Kue Balok Lumer', 2500, 0, 1, '681d92bb0a4c5.JPG', '2025-05-09 12:29:31'),
(7, 'Paket 1', 15000, 0, 1, '681dc4676fb6d.png', '2025-05-09 16:01:27'),
(8, 'Selendang Mayang', 2000, 11, 1, '681dc4e7a132b.png', '2025-05-09 16:03:35');

-- --------------------------------------------------------

--
-- Struktur dari tabel `toko`
--

CREATE TABLE `toko` (
  `id` int(11) NOT NULL,
  `nama_toko` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `toko`
--

INSERT INTO `toko` (`id`, `nama_toko`) VALUES
(1, 'Toto Cakes');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `transaksi_id` int(11) NOT NULL,
  `pelanggan_id` int(11) DEFAULT NULL,
  `kasir_id` int(11) DEFAULT NULL,
  `waktu` datetime DEFAULT current_timestamp(),
  `total_harga` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status` enum('pending','valid') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`transaksi_id`, `pelanggan_id`, `kasir_id`, `waktu`, `total_harga`, `keterangan`, `status`) VALUES
(34, NULL, 3, '2025-05-10 09:16:25', 5000, NULL, 'valid'),
(47, 1, 3, '2025-05-10 13:38:54', 6500, NULL, 'valid'),
(50, 1, 3, '2025-05-10 14:48:43', 3000, NULL, 'valid'),
(51, NULL, 3, '2025-05-10 17:02:21', 2500, NULL, 'valid'),
(52, NULL, 3, '2025-05-10 17:07:50', 15000, NULL, 'valid'),
(53, 1, 3, '2025-05-10 17:08:50', 3500, NULL, 'valid'),
(54, NULL, 3, '2025-05-10 17:32:25', 2000, NULL, 'valid'),
(55, NULL, 3, '2025-05-10 17:59:49', 20000, NULL, 'valid'),
(56, NULL, 3, '2025-05-10 19:34:43', 3000, NULL, 'pending'),
(57, NULL, 3, '2025-05-10 19:44:12', 5000, NULL, 'pending'),
(59, 1, NULL, '2025-05-11 01:48:32', 6500, NULL, 'pending'),
(60, 1, NULL, '2025-05-11 02:11:24', 3000, NULL, 'pending'),
(61, 1, NULL, '2025-05-11 02:16:52', 11000, NULL, 'pending');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `detail_id` int(11) NOT NULL,
  `transaksi_id` int(11) NOT NULL,
  `produk_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga_saat_ini` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`detail_id`, `transaksi_id`, `produk_id`, `jumlah`, `harga_saat_ini`) VALUES
(34, 34, 6, 2, 2500),
(57, 47, 4, 1, 3500),
(58, 47, 5, 1, 3000),
(61, 50, 5, 1, 3000),
(62, 51, 6, 1, 2500),
(63, 52, 7, 1, 15000),
(64, 53, 4, 1, 3500),
(65, 54, 8, 1, 2000),
(66, 55, 6, 8, 2500),
(67, 56, 5, 1, 3000),
(68, 57, 6, 2, 2500),
(71, 59, 5, 1, 3000),
(72, 59, 4, 1, 3500),
(73, 60, 5, 1, 3000),
(74, 61, 6, 2, 2500),
(75, 61, 5, 2, 3000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','admin','kasir') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `foto` varchar(100) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`, `role`, `created_at`, `nama_lengkap`, `foto`) VALUES
(1, 'owner', '$2y$10$73uGfC03kiChbkPLeNwJ/OhwGAopcmkDDv/GXD5X1Rsda5rkvmkAa', 'owner', '2025-05-08 19:24:04', 'Tante Witi', '681cd3dce27ed.png'),
(2, 'Genjor', '$2y$10$4FuKxQnU2ueN9/DysvT3t.8KKnux9F/SmsRfbo7KNc2IC5L1UV85u', 'admin', '2025-05-08 20:34:45', 'Raihan Salman', '681ce3fa2dc1e.png'),
(3, 'siwarto', '$2y$10$JBf0u7zZZyktQZE3TL8CKuLjI53LLAfx3ZXcLXS5f6f630/Vp0q2G', 'kasir', '2025-05-09 19:20:24', 'Rafqi Salman', '681df443cbd3f.png'),
(10, 'uyabandung', '$2y$10$agsFhbsKkTG4kfUEOw.l5eoKz7VGPC4P3VQjCnf1whvDTuiGpa4s2', 'owner', '2025-05-17 18:29:12', 'UyaGanteng', '6828733690110.jpg');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`);

--
-- Indeks untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`pelanggan_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`pembayaran_id`),
  ADD KEY `transaksi_id` (`transaksi_id`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`produk_id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indeks untuk tabel `toko`
--
ALTER TABLE `toko`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`transaksi_id`),
  ADD KEY `kasir_id` (`kasir_id`);

--
-- Indeks untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `transaksi_id` (`transaksi_id`),
  ADD KEY `produk_id` (`produk_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `pelanggan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `pembayaran_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `produk_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `toko`
--
ALTER TABLE `toko`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `transaksi_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`transaksi_id`);

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`kasir_id`) REFERENCES `user` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `transaksi_detail_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`transaksi_id`),
  ADD CONSTRAINT `transaksi_detail_ibfk_2` FOREIGN KEY (`produk_id`) REFERENCES `produk` (`produk_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
