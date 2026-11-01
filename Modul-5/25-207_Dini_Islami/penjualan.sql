-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 01, 2026 at 03:00 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penjualan`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` int NOT NULL,
  `kode_barang` varchar(50) DEFAULT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `stok` int DEFAULT NULL,
  `supplier_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `kode_barang`, `nama_barang`, `harga`, `stok`, `supplier_id`) VALUES
(1, 'BRG001', 'Buku Tulis', 5000, 100, 1),
(2, 'BRG002', 'Pulpen', 3000, 150, 2),
(3, 'BRG003', 'Pensil', 2000, 200, 3),
(4, 'BRG004', 'Penghapus', 1500, 100, 4),
(5, 'BRG005', 'Penggaris', 4000, 80, 5),
(6, 'BRG006', 'Spidol', 7000, 60, 6),
(7, 'BRG007', 'Map Plastik', 2500, 120, 7),
(8, 'BRG008', 'Buku Gambar', 8000, 50, 8),
(9, 'BRG009', 'Stabilo', 6000, 70, 9),
(10, 'BRG010', 'Tempat Pensil', 15000, 40, 10);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `telp` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama`, `jenis_kelamin`, `telp`, `alamat`) VALUES
(1, 'Andi', 'L', '081234567801', 'Surabaya'),
(2, 'Budi', 'L', '081234567802', 'Gresik'),
(3, 'Citra', 'P', '081234567803', 'Bangkalan'),
(4, 'Dina', 'P', '081234567804', 'Madura'),
(5, 'Eko', 'L', '081234567805', 'Sidoarjo'),
(6, 'Fitri', 'P', '081234567806', 'Lamongan'),
(7, 'Gilang', 'L', '081234567807', 'Bojonegoro'),
(8, 'Hana', 'P', '081234567808', 'Mojokerto'),
(9, 'Indra', 'L', '081234567809', 'Jombang'),
(10, 'Jihan', 'P', '081234567810', 'Pamekasan');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int NOT NULL,
  `waktu_bayar` datetime DEFAULT NULL,
  `total` int DEFAULT NULL,
  `metode` varchar(30) DEFAULT NULL,
  `transaksi_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `waktu_bayar`, `total`, `metode`, `transaksi_id`) VALUES
(1, '2026-10-01 09:05:00', 10000, 'Tunai', 1),
(2, '2026-10-01 10:05:00', 13000, 'Transfer', 2),
(3, '2026-10-02 11:05:00', 12000, 'EDC', 3),
(4, '2026-10-02 13:05:00', 15000, 'Tunai', 4),
(5, '2026-10-03 09:35:00', 16000, 'Transfer', 5),
(6, '2026-10-03 14:05:00', 14000, 'EDC', 6),
(7, '2026-10-04 10:05:00', 12500, 'Tunai', 7),
(8, '2026-10-04 12:05:00', 10000, 'Transfer', 8),
(9, '2026-10-05 09:05:00', 12000, 'EDC', 9),
(10, '2026-10-05 15:05:00', 30000, 'Tunai', 10);

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `id_supplier` int NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `telp` varchar(15) DEFAULT NULL,
  `alamat` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`id_supplier`, `nama`, `telp`, `alamat`) VALUES
(1, 'PT Sumber Makmur', '081111111111', 'Surabaya'),
(2, 'CV Berkah Jaya', '081111111112', 'Gresik'),
(3, 'PT Maju Bersama', '0811186113', 'Sidoarjo'),
(4, 'CV Sinar Abadi', '081111111114', 'Madura'),
(5, 'PT Indo Supply', '081111111115', 'Malang'),
(6, 'CV Mitra Usaha', '081111111116', 'Lamongan'),
(7, 'PT Jaya Sentosa', '081111111117', 'Bangkalan'),
(8, 'CV Karya Mandiri', '081111111118', 'Mojokerto'),
(9, 'PT Bintang Terang', '081111111119', 'Jombang'),
(10, 'CV Sukses Selalu', '081111111120', 'Pamekasan');

-- --------------------------------------------------------

--
-- Table structure for table `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `waktu_transaksi` datetime DEFAULT NULL,
  `total` int DEFAULT NULL,
  `alamat_transaksi` text,
  `id_pelanggan` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `keterangan`, `waktu_transaksi`, `total`, `alamat_transaksi`, `id_pelanggan`) VALUES
(1, 'Pembelian alat tulis', '2026-10-01 09:00:00', 10000, 'Surabaya', 1),
(2, 'Pembelian buku dan pulpen', '2026-10-01 10:00:00', 13000, 'Gresik', 2),
(3, 'Pembelian perlengkapan sekolah', '2026-10-02 11:00:00', 12000, 'Bangkalan', 3),
(4, 'Pembelian alat tulis', '2026-10-02 13:00:00', 15000, 'Madura', 4),
(5, 'Pembelian buku gambar', '2026-10-03 09:30:00', 16000, 'Sidoarjo', 5),
(6, 'Pembelian spidol', '2026-10-03 14:00:00', 14000, 'Lamongan', 6),
(7, 'Pembelian perlengkapan', '2026-10-04 10:00:00', 12500, 'Bojonegoro', 7),
(8, 'Pembelian buku dan pensil', '2026-10-04 12:00:00', 10000, 'Mojokerto', 8),
(9, 'Pembelian stabilo', '2026-10-05 09:00:00', 12000, 'Jombang', 9),
(10, 'Pembelian tempat pensil', '2026-10-05 15:00:00', 30000, 'Pamekasan', 10);

-- --------------------------------------------------------

--
-- Table structure for table `transaksi_detail`
--

CREATE TABLE `transaksi_detail` (
  `id_transaksi` int NOT NULL,
  `id_barang` int NOT NULL,
  `harga` int DEFAULT NULL,
  `qty` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaksi_detail`
--

INSERT INTO `transaksi_detail` (`id_transaksi`, `id_barang`, `harga`, `qty`) VALUES
(1, 1, 5000, 2),
(2, 1, 5000, 1),
(3, 2, 3000, 2),
(4, 3, 2000, 3),
(5, 8, 8000, 2),
(6, 6, 7000, 2),
(7, 7, 2500, 5),
(8, 3, 2000, 5),
(9, 9, 6000, 2),
(10, 10, 15000, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `alamat` text,
  `hp` varchar(15) DEFAULT NULL,
  `level` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `nama`, `alamat`, `hp`, `level`) VALUES
(1, 'admin1', 'admin123', 'Admin Utama', 'Surabaya', '082111111111', 'admin'),
(2, 'kasir1', 'kasir123', 'Rina', 'Gresik', '082111111112', 'kasir'),
(3, 'kasir2', 'kasir123', 'Doni', 'Bangkalan', '082111111113', 'kasir'),
(4, 'admin2', 'admin123', 'Sinta', 'Madura', '082111111114', 'admin'),
(5, 'kasir3', 'kasir123', 'Rudi', 'Sidoarjo', '082111111115', 'kasir'),
(6, 'kasir4', 'kasir123', 'Nina', 'Lamongan', '082111111116', 'kasir'),
(7, 'admin3', 'admin123', 'Agus', 'Malang', '082111111117', 'admin'),
(8, 'kasir5', 'kasir123', 'Dewi', 'Mojokerto', '082111111118', 'kasir'),
(9, 'kasir6', 'kasir123', 'Fajar', 'Jombang', '082111111119', 'kasir'),
(10, 'kasir7', 'kasir123', 'Putri', 'Pamekasan', '082111111120', 'kasir');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `transaksi_id` (`transaksi_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_pelanggan` (`id_pelanggan`);

--
-- Indexes for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD PRIMARY KEY (`id_transaksi`,`id_barang`),
  ADD KEY `fk_barang` (`id_barang`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `id_barang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `id_supplier` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id_supplier`);

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id_transaksi`);

--
-- Constraints for table `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);

--
-- Constraints for table `transaksi_detail`
--
ALTER TABLE `transaksi_detail`
  ADD CONSTRAINT `fk_barang` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`),
  ADD CONSTRAINT `fk_transaksi` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
