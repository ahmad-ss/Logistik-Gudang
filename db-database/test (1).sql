-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2022 at 08:58 AM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `logistik_item`
--

CREATE TABLE `logistik_item` (
  `id_item` bigint(20) NOT NULL,
  `nama_item` varchar(200) DEFAULT NULL,
  `kd_item` varchar(8) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `kd_satuan` varchar(100) DEFAULT NULL,
  `nama_satuan` varchar(100) DEFAULT NULL,
  `jumlah` double NOT NULL,
  `input_id_user` bigint(20) NOT NULL,
  `tanggal_input` datetime NOT NULL,
  `update_id_user` bigint(20) NOT NULL,
  `tanggal_update` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logistik_log_trs`
--

CREATE TABLE `logistik_log_trs` (
  `id_lgs_log` bigint(20) NOT NULL,
  `no_lgs` varchar(20) NOT NULL,
  `tanggal` date NOT NULL,
  `qty_masuk` double NOT NULL,
  `qty_keluar` double NOT NULL,
  `id_user` bigint(20) NOT NULL,
  `sts_aksi` char(1) NOT NULL,
  `tanggalwaktu` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logistik_saldoawal`
--

CREATE TABLE `logistik_saldoawal` (
  `id_saldoawal` bigint(20) NOT NULL,
  `id_item` bigint(20) DEFAULT NULL,
  `nama_item` varchar(255) DEFAULT NULL,
  `kd_item` varchar(100) DEFAULT NULL,
  `kd_satuan` varchar(25) DEFAULT NULL,
  `nama_satuan` varchar(100) DEFAULT NULL,
  `jumlah` double DEFAULT '0',
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logistik_satuan`
--

CREATE TABLE `logistik_satuan` (
  `id_satuan` int(11) NOT NULL,
  `kd_satuan` varchar(10) DEFAULT NULL,
  `nama_satuan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logistik_temp_trs`
--

CREATE TABLE `logistik_temp_trs` (
  `id_temp` bigint(20) NOT NULL,
  `no_lgs` varchar(10) NOT NULL,
  `no_bukti` varchar(100) DEFAULT NULL,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `team` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `logistik_transaksi`
--

CREATE TABLE `logistik_transaksi` (
  `id_transaksi` bigint(20) NOT NULL,
  `no_lgs` varchar(10) NOT NULL,
  `id_item` bigint(20) DEFAULT NULL,
  `kd_item` varchar(100) DEFAULT NULL,
  `nama_item` varchar(255) DEFAULT NULL,
  `kd_satuan` varchar(100) DEFAULT NULL,
  `id_satuan` int(11) DEFAULT NULL,
  `nama_satuan` varchar(100) DEFAULT NULL,
  `qty_masuk` double DEFAULT '0',
  `qty_keluar` double DEFAULT '0',
  `no_bukti` varchar(100) DEFAULT NULL,
  `foto_bukti` varchar(255) DEFAULT NULL,
  `team` varchar(255) DEFAULT NULL,
  `keterangan` text,
  `tanggal` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(4) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `level` int(1) NOT NULL COMMENT '1="Super Admin",2=""',
  `lb` char(5) DEFAULT 'ba',
  `ip` varchar(25) DEFAULT NULL,
  `hak_akses` char(10) DEFAULT 'su'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama`, `username`, `password`, `level`, `lb`, `ip`, `hak_akses`) VALUES
(1, 'admin', 'admin', 'admin', 1, 'ba', NULL, 'su');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logistik_item`
--
ALTER TABLE `logistik_item`
  ADD PRIMARY KEY (`id_item`);

--
-- Indexes for table `logistik_log_trs`
--
ALTER TABLE `logistik_log_trs`
  ADD PRIMARY KEY (`id_lgs_log`);

--
-- Indexes for table `logistik_saldoawal`
--
ALTER TABLE `logistik_saldoawal`
  ADD PRIMARY KEY (`id_saldoawal`),
  ADD UNIQUE KEY `id_item` (`id_item`);

--
-- Indexes for table `logistik_satuan`
--
ALTER TABLE `logistik_satuan`
  ADD PRIMARY KEY (`id_satuan`);

--
-- Indexes for table `logistik_temp_trs`
--
ALTER TABLE `logistik_temp_trs`
  ADD PRIMARY KEY (`id_temp`);

--
-- Indexes for table `logistik_transaksi`
--
ALTER TABLE `logistik_transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logistik_item`
--
ALTER TABLE `logistik_item`
  MODIFY `id_item` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logistik_log_trs`
--
ALTER TABLE `logistik_log_trs`
  MODIFY `id_lgs_log` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logistik_saldoawal`
--
ALTER TABLE `logistik_saldoawal`
  MODIFY `id_saldoawal` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logistik_satuan`
--
ALTER TABLE `logistik_satuan`
  MODIFY `id_satuan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logistik_temp_trs`
--
ALTER TABLE `logistik_temp_trs`
  MODIFY `id_temp` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logistik_transaksi`
--
ALTER TABLE `logistik_transaksi`
  MODIFY `id_transaksi` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
