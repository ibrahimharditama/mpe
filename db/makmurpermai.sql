/*
 Navicat Premium Data Transfer

 Source Server         : MySQL Localhost
 Source Server Type    : MySQL
 Source Server Version : 100406
 Source Host           : localhost:3306
 Source Schema         : mpe

 Target Server Type    : MySQL
 Target Server Version : 100406
 File Encoding         : 65001

 Date: 06/03/2023 14:24:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for faktur
-- ----------------------------
DROP TABLE IF EXISTS `faktur`;
CREATE TABLE `faktur`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `id_penjualan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `qty_kirim` int(11) NULL DEFAULT NULL,
  `total` int(11) NOT NULL,
  `diskon_faktur` int(11) NOT NULL,
  `biaya_lain` int(11) NOT NULL,
  `grand_total` int(11) NOT NULL,
  `dp` int(11) NOT NULL,
  `sisa_tagihan` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of faktur
-- ----------------------------
INSERT INTO `faktur` VALUES (6, 'A-23001', '2023-02-16', 3, '4', NULL, 0, 2250000, 100000, 200000, 2350000, 0, 0, 1, '2023-02-16 19:23:21', 1, NULL, NULL);
INSERT INTO `faktur` VALUES (7, 'A2302-011', '2023-02-28', 1, '2', '', 3, 2560000, 0, 0, 2560000, 0, 0, 1, '2023-02-28 21:45:59', 1, NULL, NULL);
INSERT INTO `faktur` VALUES (8, 'A2302-012', '2023-02-28', 2, '8', '', 1, 3500000, 0, 0, 3500000, 0, 0, 1, '2023-02-28 21:48:37', 1, NULL, NULL);
INSERT INTO `faktur` VALUES (9, 'A2302-013', '2023-02-28', 3, '4', '', 2, 2280000, 0, 0, 2280000, 0, 0, 1, '2023-02-28 21:53:15', 1, NULL, NULL);
INSERT INTO `faktur` VALUES (10, 'A2302-014', '2023-02-28', 4, '9', '', 27, 1485000, 0, 0, 1485000, 0, 0, 1, '2023-02-28 22:10:41', 1, '2023-02-28 22:11:07', 1);

-- ----------------------------
-- Table structure for faktur_detail
-- ----------------------------
DROP TABLE IF EXISTS `faktur_detail`;
CREATE TABLE `faktur_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_faktur` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `uraian` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `satuan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of faktur_detail
-- ----------------------------
INSERT INTO `faktur_detail` VALUES (7, 7, 12, 'Tes Produk 001', 0, '', 1, 2000000, 0, 2000000, 1, '2023-02-28 21:46:00', NULL, NULL, NULL);
INSERT INTO `faktur_detail` VALUES (8, 7, 13, 'Inventory x Jasa 001', 0, '', 2, 280000, 0, 560000, 1, '2023-02-28 21:46:00', NULL, NULL, NULL);
INSERT INTO `faktur_detail` VALUES (9, 8, 1, 'AC SPLIT DAIKIN 2PK', 16, 'unit', 1, 3500000, 0, 3500000, 1, '2023-02-28 21:48:37', NULL, NULL, NULL);
INSERT INTO `faktur_detail` VALUES (10, 9, 12, 'Tes Produk 001', 9, '', 1, 2000000, 0, 2000000, 1, '2023-02-28 21:53:15', NULL, NULL, NULL);
INSERT INTO `faktur_detail` VALUES (11, 9, 13, 'Inventory x Jasa 001', 9, '', 1, 280000, 0, 280000, 1, '2023-02-28 21:53:15', NULL, NULL, NULL);
INSERT INTO `faktur_detail` VALUES (13, 10, 2, 'PIPA FREON 2.5-3PK', 1, 'meter', 27, 55000, 0, 1485000, 1, '2023-02-28 22:11:07', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for jstok
-- ----------------------------
DROP TABLE IF EXISTS `jstok`;
CREATE TABLE `jstok`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_referensi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `jenis_trx` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_produk` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `id_header` int(11) NOT NULL,
  `id_detail` int(11) NOT NULL DEFAULT 1,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of jstok
-- ----------------------------
INSERT INTO `jstok` VALUES (5, 'BILL2302-009', '2023-02-28', 'pembelian', 178, 0, 0, 17, 1, '2023-02-28 19:34:14', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (6, 'BILL2302-009', '2023-02-28', 'pembelian', 5, 0, 0, 18, 1, '2023-02-28 19:34:14', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (7, 'BILL2302-010', '2023-02-28', 'pembelian', 4, 40, 0, 19, 1, '2023-02-28 19:35:34', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (8, 'BILL2302-012', '2023-02-28', 'pembelian', 4, 20, 8, 23, 1, '2023-02-28 19:49:49', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (9, 'BILL2302-012', '2023-02-28', 'pembelian', 5, 20, 8, 24, 1, '2023-02-28 19:49:49', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (10, 'BILL2302-012', '2023-02-28', 'pembelian', 6, 20, 8, 25, 1, '2023-02-28 19:49:49', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (16, 'BILL2302-013', '2023-02-28', 'pembelian', 2, 25, 9, 32, 1, '2023-02-28 20:05:12', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (17, 'A2302-011', '2023-02-28', 'penjualan', 12, 1, 7, 7, 1, '2023-02-28 21:46:00', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (18, 'A2302-011', '2023-02-28', 'penjualan', 13, 2, 7, 8, 1, '2023-02-28 21:46:00', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (19, 'A2302-012', '2023-02-28', 'penjualan', 1, 1, 8, 9, 1, '2023-02-28 21:48:37', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (20, 'A2302-013', '2023-02-28', 'penjualan', 12, -1, 9, 10, 1, '2023-02-28 21:53:15', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (21, 'A2302-013', '2023-02-28', 'penjualan', 13, -1, 9, 11, 1, '2023-02-28 21:53:15', 1, NULL, NULL);
INSERT INTO `jstok` VALUES (23, 'A2302-014', '2023-02-28', 'penjualan', 2, -27, 10, 13, 1, '2023-02-28 22:11:07', 1, NULL, NULL);

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `ikon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `teks` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `uri` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_induk` int(11) NULL DEFAULT NULL,
  `urutan` int(11) NOT NULL,
  `actions` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_induk`(`id_induk`) USING BTREE,
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_induk`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, NULL, 'harddrives', 'Data Master', '#', NULL, 100, 'r', 1, '2023-01-30 13:20:50', NULL, '2023-01-30 13:38:36', NULL);
INSERT INTO `menu` VALUES (2, NULL, 'shopping-cart-full', 'Pembelian', '#', NULL, 200, 'r', 1, '2023-01-30 13:20:50', NULL, '2023-01-30 13:38:37', NULL);
INSERT INTO `menu` VALUES (3, NULL, 'receipt', 'Penjualan', '#', NULL, 300, 'r', 1, '2023-01-30 13:22:23', NULL, '2023-01-30 13:38:39', NULL);
INSERT INTO `menu` VALUES (4, NULL, 'bookmark-alt', 'Laporan', '#', NULL, 400, 'r', 1, '2023-01-30 13:23:05', NULL, '2023-01-30 13:38:49', NULL);
INSERT INTO `menu` VALUES (5, NULL, 'settings', 'Pengaturan', '#', NULL, 500, 'r', 1, '2023-01-30 13:23:28', NULL, '2023-01-30 13:38:50', NULL);
INSERT INTO `menu` VALUES (6, 'produk', NULL, 'Daftar Item Inventory', '/master/produk', 1, 101, 'c,r,u,d', 1, '2023-01-30 13:23:44', NULL, '2023-02-21 20:52:05', NULL);
INSERT INTO `menu` VALUES (7, 'stok', NULL, 'Kartu Stok', '/master/stok', 1, 103, 'c,r,u,d', 1, '2023-01-30 13:26:13', NULL, '2023-02-21 20:52:47', NULL);
INSERT INTO `menu` VALUES (8, 'supplier', NULL, 'Daftar Supplier', '/master/supplier', 1, 104, 'c,r,u,d', 1, '2023-01-30 13:26:54', NULL, '2023-02-21 20:52:49', NULL);
INSERT INTO `menu` VALUES (9, 'pelanggan', NULL, 'Daftar Pelanggan', '/master/pelanggan', 1, 105, 'c,r,u,d', 1, '2023-01-30 13:27:12', NULL, '2023-02-21 20:52:52', NULL);
INSERT INTO `menu` VALUES (10, 'satuan', NULL, 'Satuan', '/master/satuan', 1, 106, 'c,r,u,d', 1, '2023-01-30 13:27:30', NULL, '2023-02-21 20:52:54', NULL);
INSERT INTO `menu` VALUES (11, 'jenis', NULL, 'Jenis Item', '/master/jenis', 1, 107, 'c,r,u,d', 1, '2023-01-30 13:27:45', NULL, '2023-02-21 20:52:56', NULL);
INSERT INTO `menu` VALUES (12, 'merek', NULL, 'Merek', '/master/merek', 1, 108, 'c,r,u,d', 1, '2023-01-30 13:28:01', NULL, '2023-02-21 20:52:58', NULL);
INSERT INTO `menu` VALUES (13, 'pembelian', NULL, 'Pesanan Pembelian', '/pembelian/pesanan', 2, 201, 'c,r,u,d', 1, '2023-01-30 13:28:36', NULL, '2023-01-30 13:39:28', NULL);
INSERT INTO `menu` VALUES (14, 'penerimaan', NULL, 'Tagihan Pembelian', '/pembelian/penerimaan', 2, 202, 'c,r,u,d', 1, '2023-01-30 13:28:54', NULL, '2023-02-21 21:22:02', NULL);
INSERT INTO `menu` VALUES (15, 'penjualan', NULL, 'Pesanan Penjualan', '/penjualan/pesanan', 3, 301, 'c,r,u,d', 1, '2023-01-30 13:29:12', NULL, '2023-01-30 13:39:44', NULL);
INSERT INTO `menu` VALUES (16, 'invoice', NULL, 'Nota Penjualan', '/penjualan/faktur', 3, 302, 'c,r,u,d', 1, '2023-01-30 13:29:38', NULL, '2023-02-16 19:29:59', NULL);
INSERT INTO `menu` VALUES (17, 'hutang', NULL, 'Laporan Hutang', '/laporan/hutang', 4, 401, 'c,r,u,d', 1, '2023-01-30 13:30:01', NULL, '2023-01-30 13:39:55', NULL);
INSERT INTO `menu` VALUES (18, 'piutang', NULL, 'Laporan Piutang', '/laporan/piutang', 4, 402, 'c,r,u,d', 1, '2023-01-30 13:30:19', NULL, '2023-01-30 13:39:57', NULL);
INSERT INTO `menu` VALUES (19, 'user', NULL, 'Pegawai & Pengguna', '/pengaturan/pengguna', 5, 501, 'c,r,u,d', 1, '2023-01-30 13:30:35', NULL, '2023-02-17 22:10:25', NULL);
INSERT INTO `menu` VALUES (20, 'perusahaan', NULL, 'Data Perusahaan', '/pengaturan/perusahaan', 5, 502, 'c,r,u,d', 1, '2023-01-30 13:30:52', NULL, '2023-01-30 13:40:01', NULL);
INSERT INTO `menu` VALUES (21, 'notransaksi', NULL, 'Setting No. Transaksi', '/pengaturan/no-transaksi', 5, 503, 'c,r,u,d', 1, '2023-01-30 13:31:08', NULL, '2023-01-30 13:40:11', NULL);
INSERT INTO `menu` VALUES (22, 'pengiriman', NULL, 'Pengiriman', '/penjualan/pengiriman', 3, 303, 'c,r,u,d', 1, '2023-02-09 13:19:29', NULL, NULL, NULL);
INSERT INTO `menu` VALUES (23, 'aset', 'archive', 'Aset', '/aset', NULL, 350, 'c,r,u,d', 1, '2023-02-09 13:20:00', NULL, '2023-02-09 13:22:48', NULL);
INSERT INTO `menu` VALUES (24, 'pipa', NULL, 'Daftar Item Pipa', '/master/pipa', 1, 102, 'c,r,u,d', 1, '2023-02-21 20:52:40', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for no_transaksi
-- ----------------------------
DROP TABLE IF EXISTS `no_transaksi`;
CREATE TABLE `no_transaksi`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `format` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `digit_serial` smallint(6) NOT NULL,
  `is_reset_serial` tinyint(4) NOT NULL DEFAULT 1,
  `reset_serial` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tahun_sekarang` char(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `bulan_sekarang` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `serial_berikutnya` int(11) NOT NULL DEFAULT 1,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of no_transaksi
-- ----------------------------
INSERT INTO `no_transaksi` VALUES (1, 'supplier', 'Supplier', 'S#SERIAL#', 4, 0, 'tahunan', '2023', '02', 14, 1, '2022-12-27 20:17:17', NULL, '2023-02-17 19:45:26', 1);
INSERT INTO `no_transaksi` VALUES (2, 'pelanggan', 'Pelanggan', 'P#SERIAL#', 4, 0, 'tahunan', '2023', '02', 4, 1, '2022-12-28 08:03:12', NULL, '2023-02-13 00:26:44', NULL);
INSERT INTO `no_transaksi` VALUES (3, 'produk', 'Item', 'ITM#SERIAL#', 4, 0, 'tahunan', '2023', '02', 3, 1, '2022-12-28 08:04:00', NULL, '2023-02-15 09:38:30', NULL);
INSERT INTO `no_transaksi` VALUES (4, 'penjualan', 'Pesanan Penjualan', 'SO#Y2##SERIAL#', 4, 1, 'tahunan', '2023', '02', 9, 1, '2022-12-28 08:17:51', NULL, '2023-02-28 22:07:38', NULL);
INSERT INTO `no_transaksi` VALUES (5, 'pembelian', 'Pesanan Pembelian', 'PO#Y2##M#-#SERIAL#', 3, 1, 'bulanan', '2023', '02', 6, 1, '2023-01-25 10:04:21', NULL, '2023-02-28 19:58:42', NULL);
INSERT INTO `no_transaksi` VALUES (6, 'tagihan', 'Tagihan Pembelian', 'BILL#Y2##M#-#SERIAL#', 3, 1, 'bulanan', '2023', '02', 14, 1, '2023-02-21 21:52:58', NULL, '2023-02-28 19:59:10', NULL);
INSERT INTO `no_transaksi` VALUES (7, 'faktur', 'Nota Penjualan', 'A#Y2##M#-#SERIAL#', 3, 1, 'bulanan', '2023', '02', 15, 1, '2023-02-28 21:43:25', NULL, '2023-02-28 22:10:41', NULL);

-- ----------------------------
-- Table structure for no_transaksi_prefiks
-- ----------------------------
DROP TABLE IF EXISTS `no_transaksi_prefiks`;
CREATE TABLE `no_transaksi_prefiks`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prefiks_baru` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `prefiks_lama` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of no_transaksi_prefiks
-- ----------------------------
INSERT INTO `no_transaksi_prefiks` VALUES (6, 'penerimaan', 'Penerimaan Pembelian', 'RO-', 'PO', 1, '2023-02-16 16:35:40', 1, '2023-02-16 16:37:00', NULL);

-- ----------------------------
-- Table structure for pelanggan
-- ----------------------------
DROP TABLE IF EXISTS `pelanggan`;
CREATE TABLE `pelanggan`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `kota` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `provinsi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kode_pos` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_telp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_hp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kontak` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `bank` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_rekening` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pemilik_rekening` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `npwp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 13 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pelanggan
-- ----------------------------
INSERT INTO `pelanggan` VALUES (1, 'PL0001', 'SALIMUN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:03', NULL, NULL, NULL);
INSERT INTO `pelanggan` VALUES (2, 'PL0002', 'KUSMO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, NULL, NULL);
INSERT INTO `pelanggan` VALUES (3, 'PL0007', 'HERWAN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'DBCA HR 3807\r\n', 1, '2023-02-21 20:50:04', NULL, '2023-02-21 20:50:52', NULL);
INSERT INTO `pelanggan` VALUES (4, 'PL0013', 'RAHMAT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, NULL, NULL);
INSERT INTO `pelanggan` VALUES (5, 'PL0014', 'CASH', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, NULL, NULL);
INSERT INTO `pelanggan` VALUES (6, 'PL0018', 'PT. KAWASAKI MOTOR INDONESIA', 'Jalan Madura Blok L 11, Kawasan Industri MM2100 Cikedokan Cikarang Barat Bekasi 17530', NULL, NULL, NULL, '2957.7888', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, '2023-02-21 20:50:46', NULL);
INSERT INTO `pelanggan` VALUES (7, 'PL0019', 'PT. Morita Tjokro Gearindo', 'Jl. Rawa Terate I No. 9 Kawasan Industri Pulo Gadung Jakarta 13920', NULL, NULL, NULL, '460.9011', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, '2023-02-21 20:50:46', NULL);
INSERT INTO `pelanggan` VALUES (8, 'PL0020', 'Fransisca', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, NULL, NULL);
INSERT INTO `pelanggan` VALUES (9, 'PL0027', 'CHARUDI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, NULL, NULL);
INSERT INTO `pelanggan` VALUES (10, 'PL0030', 'Perbaikan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, NULL, NULL);
INSERT INTO `pelanggan` VALUES (11, 'PL0034', 'Nino', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, NULL, NULL);
INSERT INTO `pelanggan` VALUES (12, 'PL0035', 'Hanny', 'Jl. Ubi No. 61', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:50:04', NULL, '2023-02-21 20:51:48', NULL);

-- ----------------------------
-- Table structure for pembelian
-- ----------------------------
DROP TABLE IF EXISTS `pembelian`;
CREATE TABLE `pembelian`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `tgl_kirim` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `qty_pesan` int(11) NOT NULL,
  `qty_kirim` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `diskon_faktur` int(11) NOT NULL,
  `biaya_lain` int(11) NOT NULL,
  `grand_total` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pembelian
-- ----------------------------
INSERT INTO `pembelian` VALUES (1, 'PO2302-002', '2023-02-21', '2023-02-21', 2, NULL, 150, 150, 8000100, 100, 0, 8000000, 1, '2023-02-21 22:56:02', 1, '2023-02-21 23:01:47', NULL);
INSERT INTO `pembelian` VALUES (2, 'PO2302-003', '2023-02-21', '2023-02-21', 2, '', 30, 40, 3000000, 0, 0, 3000000, 1, '2023-02-21 23:37:59', 1, '2023-02-28 19:34:14', NULL);
INSERT INTO `pembelian` VALUES (3, 'PO2302-004', '2023-02-28', '2023-02-28', 8, '', 2, 0, 7000000, 0, 0, 7000000, 1, '2023-02-28 19:57:59', 1, '2023-02-28 20:03:47', NULL);
INSERT INTO `pembelian` VALUES (4, 'PO2302-005', '2023-02-28', '2023-02-28', 8, '', 30, 25, 2250000, 0, 0, 2250000, 1, '2023-02-28 19:58:42', 1, '2023-02-28 20:05:12', NULL);

-- ----------------------------
-- Table structure for pembelian_detail
-- ----------------------------
DROP TABLE IF EXISTS `pembelian_detail`;
CREATE TABLE `pembelian_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pembelian` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `uraian` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `satuan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pembelian_detail
-- ----------------------------
INSERT INTO `pembelian_detail` VALUES (1, 1, 91, '6702', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 22:56:02', NULL, NULL, NULL);
INSERT INTO `pembelian_detail` VALUES (2, 1, 92, '6703', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 22:56:02', NULL, NULL, NULL);
INSERT INTO `pembelian_detail` VALUES (3, 1, 95, '6706', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 22:56:02', NULL, NULL, NULL);
INSERT INTO `pembelian_detail` VALUES (4, 1, 96, '6707', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 22:56:02', NULL, NULL, NULL);
INSERT INTO `pembelian_detail` VALUES (5, 1, 98, '6709', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 22:56:02', NULL, NULL, NULL);
INSERT INTO `pembelian_detail` VALUES (6, 2, 178, '8501', 1, 'meter', 30, 100000, 0, 3000000, 1, '2023-02-21 23:37:59', NULL, NULL, NULL);
INSERT INTO `pembelian_detail` VALUES (7, 3, 1, 'AC SPLIT DAIKIN 2PK', 16, 'unit', 2, 3500000, 0, 7000000, 1, '2023-02-28 19:57:59', NULL, NULL, NULL);
INSERT INTO `pembelian_detail` VALUES (8, 4, 2, 'PIPA FREON 2.5-3PK', 1, 'meter', 30, 75000, 0, 2250000, 1, '2023-02-28 19:58:42', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for penerimaan
-- ----------------------------
DROP TABLE IF EXISTS `penerimaan`;
CREATE TABLE `penerimaan`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_pembelian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `qty_terima` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `diskon_faktur` int(11) NOT NULL,
  `biaya_lain` int(11) NOT NULL,
  `grand_total` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of penerimaan
-- ----------------------------
INSERT INTO `penerimaan` VALUES (1, 'BILL2302-005', '2023-02-21', 2, '1', NULL, 120, 6400080, 100, 0, 6399980, 1, '2023-02-21 22:56:48', 1, NULL, NULL);
INSERT INTO `penerimaan` VALUES (3, 'BILL2302-007', '2023-02-21', 2, '1', NULL, 30, 1600020, 0, 0, 1600020, 1, '2023-02-21 23:01:47', 1, NULL, NULL);
INSERT INTO `penerimaan` VALUES (4, 'BILL2302-008', '2023-02-21', 3, '', '', 300, 20025150, 150, 0, 20025000, 1, '2023-02-21 23:06:47', 1, NULL, NULL);
INSERT INTO `penerimaan` VALUES (5, 'BILL2302-009', '2023-02-28', 2, '2', '', 40, 1288000, 0, 0, 1288000, 1, '2023-02-28 19:34:14', 1, NULL, NULL);
INSERT INTO `penerimaan` VALUES (6, 'BILL2302-010', '2023-02-28', 5, '', '', 40, 384000, 0, 0, 384000, 1, '2023-02-28 19:35:34', 1, NULL, NULL);
INSERT INTO `penerimaan` VALUES (7, 'BILL2302-011', '2023-02-28', 6, '', '', 60, 576000, 0, 0, 576000, 1, '2023-02-28 19:48:35', 1, NULL, NULL);
INSERT INTO `penerimaan` VALUES (8, 'BILL2302-012', '2023-02-28', 6, '', '', 60, 576000, 0, 0, 576000, 1, '2023-02-28 19:49:49', 1, NULL, NULL);
INSERT INTO `penerimaan` VALUES (9, 'BILL2302-013', '2023-02-28', 8, '4', '', 25, 1875000, 0, 0, 1875000, 1, '2023-02-28 19:59:10', 1, '2023-02-28 20:05:12', 1);

-- ----------------------------
-- Table structure for penerimaan_detail
-- ----------------------------
DROP TABLE IF EXISTS `penerimaan_detail`;
CREATE TABLE `penerimaan_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penerimaan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `uraian` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `satuan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of penerimaan_detail
-- ----------------------------
INSERT INTO `penerimaan_detail` VALUES (1, 1, 91, '6702', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 22:56:49', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (2, 1, 92, '6703', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 22:56:49', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (3, 1, 95, '6706', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 22:56:49', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (4, 1, 96, '6707', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 22:56:49', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (6, 3, 98, '6709', 1, 'meter', 30, 53334, 0, 1600020, 1, '2023-02-21 23:01:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (7, 4, 147, '7720', 1, 'meter', 30, 74334, 0, 2230020, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (8, 4, 148, '7721', 1, 'meter', 30, 74334, 0, 2230020, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (9, 4, 149, '7722', 1, 'meter', 30, 74334, 0, 2230020, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (10, 4, 150, '7723', 1, 'meter', 30, 74334, 0, 2230020, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (11, 4, 151, '7724', 1, 'meter', 30, 74334, 0, 2230020, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (12, 4, 108, '6801', 1, 'meter', 30, 59167, 0, 1775010, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (13, 4, 109, '6802', 1, 'meter', 30, 59167, 0, 1775010, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (14, 4, 110, '6803', 1, 'meter', 30, 59167, 0, 1775010, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (15, 4, 111, '6804', 1, 'meter', 30, 59167, 0, 1775010, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (16, 4, 112, '6805', 1, 'meter', 30, 59167, 0, 1775010, 1, '2023-02-21 23:06:47', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (17, 5, 178, '8501', 1, 'meter', 10, 100000, 0, 1000000, 1, '2023-02-28 19:34:14', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (18, 5, 5, '1002', 1, 'meter', 30, 9600, 0, 288000, 1, '2023-02-28 19:34:14', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (19, 6, 4, '1001', 1, 'meter', 40, 9600, 0, 384000, 1, '2023-02-28 19:35:34', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (20, 7, 4, '1001', 1, 'meter', 20, 9600, 0, 192000, 1, '2023-02-28 19:48:35', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (21, 7, 5, '1002', 1, 'meter', 20, 9600, 0, 192000, 1, '2023-02-28 19:48:35', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (22, 7, 6, '1003', 1, 'meter', 20, 9600, 0, 192000, 1, '2023-02-28 19:48:35', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (23, 8, 4, '1001', 1, 'meter', 20, 9600, 0, 192000, 1, '2023-02-28 19:49:49', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (24, 8, 5, '1002', 1, 'meter', 20, 9600, 0, 192000, 1, '2023-02-28 19:49:49', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (25, 8, 6, '1003', 1, 'meter', 20, 9600, 0, 192000, 1, '2023-02-28 19:49:49', NULL, NULL, NULL);
INSERT INTO `penerimaan_detail` VALUES (32, 9, 2, 'PIPA FREON 2.5-3PK', 1, 'meter', 25, 75000, 0, 1875000, 1, '2023-02-28 20:05:12', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for pengguna
-- ----------------------------
DROP TABLE IF EXISTS `pengguna`;
CREATE TABLE `pengguna`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_pengguna_grup` int(11) NOT NULL,
  `is_teknisi` tinyint(4) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_pengguna_grup`(`id_pengguna_grup`) USING BTREE,
  CONSTRAINT `pengguna_ibfk_1` FOREIGN KEY (`id_pengguna_grup`) REFERENCES `pengguna_grup` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pengguna
-- ----------------------------
INSERT INTO `pengguna` VALUES (1, 'Pollux Integra', 26, 'admin@polluxintegra.co.id', '07993b173fec29307790ce6916540f08', 'pollux', 1, 0, 1, '2022-09-05 11:58:06', NULL, '2023-02-17 22:20:14', NULL);
INSERT INTO `pengguna` VALUES (2, 'FRANSISCA', 23, 'sisca@makmurpermai.co.id', '07993b173fec29307790ce6916540f08', 'sisca', 1, 0, 1, '2023-01-25 09:41:26', NULL, '2023-02-17 22:20:20', NULL);
INSERT INTO `pengguna` VALUES (3, 'BUDI', 24, '', '', 'budi', 1, 1, 1, '2023-02-09 13:34:56', NULL, '2023-02-17 22:20:28', NULL);
INSERT INTO `pengguna` VALUES (4, 'JOKO', 24, '', '', 'joko', 1, 1, 1, '2023-02-09 13:35:05', NULL, '2023-02-17 22:20:29', NULL);
INSERT INTO `pengguna` VALUES (5, 'SATRIO', 25, '', '', 'satrio', 1, 1, 1, '2023-02-09 13:35:15', NULL, '2023-02-17 22:20:31', NULL);

-- ----------------------------
-- Table structure for pengguna_cookie
-- ----------------------------
DROP TABLE IF EXISTS `pengguna_cookie`;
CREATE TABLE `pengguna_cookie`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengguna` int(11) NOT NULL,
  `cookie` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl_kadaluarsa` date NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_pengguna`(`id_pengguna`) USING BTREE,
  CONSTRAINT `pengguna_cookie_ibfk_1` FOREIGN KEY (`id_pengguna`) REFERENCES `pengguna` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pengguna_cookie
-- ----------------------------
INSERT INTO `pengguna_cookie` VALUES (1, 1, '4aa4c7697562f7d7c316ebb93fece043', '127.0.0.1', '2023-12-22', 1, '2022-12-27 16:53:32', NULL, NULL, NULL);
INSERT INTO `pengguna_cookie` VALUES (2, 1, 'bbbe15caba73af9dca880a53ffbaff56', '127.0.0.1', '2023-12-23', 1, '2022-12-28 08:01:20', NULL, NULL, NULL);
INSERT INTO `pengguna_cookie` VALUES (3, 1, '4210a7a51b21266d4907deb69384b015', '127.0.0.1', '2024-01-27', 1, '2023-02-01 15:20:49', NULL, NULL, NULL);
INSERT INTO `pengguna_cookie` VALUES (4, 1, 'dc5ad072ed8bb8e1655c32f4a3b04642', '127.0.0.1', '2024-02-03', 1, '2023-02-08 14:10:08', NULL, NULL, NULL);
INSERT INTO `pengguna_cookie` VALUES (5, 1, '6a16cde450c0237d0f9882c5c1384a8f', '127.0.0.1', '2024-02-10', 1, '2023-02-15 08:42:59', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for pengguna_grup
-- ----------------------------
DROP TABLE IF EXISTS `pengguna_grup`;
CREATE TABLE `pengguna_grup`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `urutan` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pengguna_grup
-- ----------------------------
INSERT INTO `pengguna_grup` VALUES (1, 'Superadmin', 1, 1, '2022-09-05 11:57:20', NULL, '2022-12-27 15:23:35', 1);

-- ----------------------------
-- Table structure for pengguna_grup_menu
-- ----------------------------
DROP TABLE IF EXISTS `pengguna_grup_menu`;
CREATE TABLE `pengguna_grup_menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengguna_grup` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `permissions` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `id_pengguna_grup`(`id_pengguna_grup`) USING BTREE,
  INDEX `id_menu`(`id_menu`) USING BTREE,
  CONSTRAINT `pengguna_grup_menu_ibfk_1` FOREIGN KEY (`id_pengguna_grup`) REFERENCES `pengguna_grup` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pengguna_grup_menu_ibfk_2` FOREIGN KEY (`id_menu`) REFERENCES `menu` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 25 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pengguna_grup_menu
-- ----------------------------
INSERT INTO `pengguna_grup_menu` VALUES (1, 1, 1, 'r', 1, '2023-01-30 13:31:24', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (2, 1, 2, 'r', 1, '2023-01-30 13:31:28', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (3, 1, 3, 'r', 1, '2023-01-30 13:31:32', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (4, 1, 4, 'r', 1, '2023-01-30 13:31:35', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (5, 1, 5, 'r', 1, '2023-01-30 13:31:39', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (6, 1, 6, 'c,r,u,d', 1, '2023-01-30 13:31:43', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (7, 1, 7, 'c,r,u,d', 1, '2023-01-30 13:31:46', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (8, 1, 8, 'c,r,u,d', 1, '2023-01-30 13:31:48', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (9, 1, 9, 'c,r,u,d', 1, '2023-01-30 13:31:51', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (10, 1, 10, 'c,r,u,d', 1, '2023-01-30 13:31:54', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (11, 1, 11, 'c,r,u,d', 1, '2023-01-30 13:31:56', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (12, 1, 12, 'c,r,u,d', 1, '2023-01-30 13:32:00', NULL, '2023-01-30 13:32:01', NULL);
INSERT INTO `pengguna_grup_menu` VALUES (13, 1, 13, 'c,r,u,d', 1, '2023-01-30 13:32:04', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (14, 1, 14, 'c,r,u,d', 1, '2023-01-30 13:32:08', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (15, 1, 15, 'c,r,u,d', 1, '2023-01-30 13:32:11', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (16, 1, 16, 'c,r,u,d', 1, '2023-01-30 13:32:13', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (17, 1, 17, 'c,r,u,d', 1, '2023-01-30 13:32:16', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (18, 1, 18, 'c,r,u,d', 1, '2023-01-30 13:32:19', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (19, 1, 19, 'c,r,u,d', 1, '2023-01-30 13:32:22', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (20, 1, 20, 'c,r,u,d', 1, '2023-01-30 13:32:25', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (21, 1, 21, 'c,r,u,d', 1, '2023-01-30 13:32:28', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (22, 1, 22, 'c,r,u,d', 1, '2023-02-09 13:21:37', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (23, 1, 23, 'c,r,u,d', 1, '2023-02-09 13:21:43', NULL, NULL, NULL);
INSERT INTO `pengguna_grup_menu` VALUES (24, 1, 24, 'c,r,u,d', 1, '2023-02-21 20:53:24', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for penjualan
-- ----------------------------
DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE `penjualan`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `tgl_kirim` date NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `qty_pesan` int(11) NOT NULL,
  `qty_kirim` int(11) NOT NULL,
  `total` int(11) NOT NULL,
  `diskon_faktur` int(11) NOT NULL,
  `biaya_lain` int(11) NOT NULL,
  `grand_total` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of penjualan
-- ----------------------------
INSERT INTO `penjualan` VALUES (1, 'SO230001', '2023-02-01', '2023-02-01', 1, NULL, 2, 1, 15000000, 0, 0, 15000000, 1, '2023-02-08 21:53:07', 1, '2023-02-16 21:06:26', NULL);
INSERT INTO `penjualan` VALUES (2, 'SO230002', '2023-02-15', '2023-02-15', 1, 'Tes Pesanan Penjualan 001', 0, 3, 0, 0, 0, 0, 1, '2023-02-15 22:17:11', NULL, '2023-02-28 21:46:00', NULL);
INSERT INTO `penjualan` VALUES (3, 'SO230003', '2023-02-15', '2023-02-15', 1, 'Tes penjualan 003', 2, 0, 2170000, 170000, 150000, 2150000, 1, '2023-02-15 22:53:09', 1, '2023-02-16 21:06:30', NULL);
INSERT INTO `penjualan` VALUES (4, 'SO230004', '2023-02-15', '2023-02-15', 3, 'Tes penjualan 004', 2, 2, 2250000, 100000, 200000, 2350000, 1, '2023-02-15 22:56:20', 1, '2023-02-28 21:53:15', NULL);
INSERT INTO `penjualan` VALUES (5, 'SO230004', '2023-02-28', '2023-02-28', 2, 'KUSMO 001', 4, 0, 3725000, 0, 0, 3725000, 1, '2023-02-28 20:58:56', 1, NULL, NULL);
INSERT INTO `penjualan` VALUES (6, 'SO230005', '2023-02-28', '2023-02-28', 7, 'Morita', 3, 0, 1050000, 0, 0, 1050000, 1, '2023-02-28 21:02:15', 1, NULL, NULL);
INSERT INTO `penjualan` VALUES (7, 'SO230006', '2023-02-28', '2023-02-28', 1, 'Salimun', 5, 0, 275000, 0, 0, 275000, 1, '2023-02-28 21:04:06', 1, '2023-02-28 21:16:18', 1);
INSERT INTO `penjualan` VALUES (8, 'SO230007', '2023-02-28', '2023-02-28', 2, '', 1, 1, 3500000, 0, 0, 3500000, 1, '2023-02-28 21:48:11', 1, '2023-02-28 21:48:37', NULL);
INSERT INTO `penjualan` VALUES (9, 'SO230008', '2023-02-28', '2023-02-28', 4, '', 35, 27, 1925000, 0, 0, 1925000, 1, '2023-02-28 22:07:38', 1, '2023-02-28 22:11:07', NULL);

-- ----------------------------
-- Table structure for penjualan_detail
-- ----------------------------
DROP TABLE IF EXISTS `penjualan_detail`;
CREATE TABLE `penjualan_detail`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_penjualan` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `uraian` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `satuan` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `diskon` int(11) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of penjualan_detail
-- ----------------------------
INSERT INTO `penjualan_detail` VALUES (1, 2, 12, 'Tes Produk 001', 0, '', 1, 2000000, 0, 2000000, 1, '2023-02-15 22:17:11', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (2, 2, 13, 'Inventory x Jasa 001', 0, '', 2, 280000, 0, 560000, 1, '2023-02-15 22:17:11', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (3, 3, 12, 'Tes Produk 001 x', 0, '', 1, 2000000, 100000, 1900000, 1, '2023-02-15 22:53:09', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (4, 3, 13, 'Inventory x Jasa 001', 0, '', 1, 280000, 10000, 270000, 1, '2023-02-15 22:53:09', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (5, 4, 12, 'Tes Produk 001', 9, '', 1, 2000000, 10000, 1990000, 1, '2023-02-15 22:56:20', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (6, 4, 13, 'Inventory x Jasa 001', 9, '', 1, 280000, 20000, 260000, 1, '2023-02-15 22:56:20', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (7, 5, 1, 'AC SPLIT DAIKIN 2PK', 16, '', 1, 3500000, 0, 3500000, 1, '2023-02-28 20:58:56', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (8, 5, 2, 'PIPA FREON 2.5-3PK', 1, '', 3, 75000, 0, 225000, 1, '2023-02-28 20:58:56', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (9, 6, 3, 'UCHIDA 2PK', 16, 'unit', 3, 350000, 0, 1050000, 1, '2023-02-28 21:02:15', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (13, 7, 2, 'PIPA FREON 2.5-3PK', 1, 'meter', 5, 55000, 0, 275000, 1, '2023-02-28 21:16:19', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (14, 8, 1, 'AC SPLIT DAIKIN 2PK', 16, 'unit', 1, 3500000, 0, 3500000, 1, '2023-02-28 21:48:11', NULL, NULL, NULL);
INSERT INTO `penjualan_detail` VALUES (15, 9, 2, 'PIPA FREON 2.5-3PK', 1, 'meter', 35, 55000, 0, 1925000, 1, '2023-02-28 22:07:38', NULL, NULL, NULL);

-- ----------------------------
-- Table structure for profil
-- ----------------------------
DROP TABLE IF EXISTS `profil`;
CREATE TABLE `profil`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `no_telp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `website` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of profil
-- ----------------------------
INSERT INTO `profil` VALUES (1, 'MAKMUR PERMAI ELEKTRONIK', 'Jl. Boulevard Timur Raya Blok NE1 No. 40\r\nKel. Pegangsaan Dua, Kec. Kelapa Gading\r\nKota Jakarta Utara, DKI Jakarta', '0821-1002-0020', 'info@yasacatering.co.id', 'https://toko-makmur-permai.business.site', 1, '2022-12-27 16:38:14', NULL, '2023-01-25 09:44:45', NULL);

-- ----------------------------
-- Table structure for ref_lookup
-- ----------------------------
DROP TABLE IF EXISTS `ref_lookup`;
CREATE TABLE `ref_lookup`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ref_lookup
-- ----------------------------
INSERT INTO `ref_lookup` VALUES (1, 'satuan', 'meter', 1, '2023-02-08 14:59:16', 1, '2023-02-09 10:31:18', NULL);
INSERT INTO `ref_lookup` VALUES (2, 'satuan', 'pcs', 1, '2023-02-08 14:59:21', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (3, 'jenis', 'AC', 1, '2023-02-08 14:59:31', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (4, 'jenis', 'Kulkas', 1, '2023-02-08 14:59:38', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (5, 'jenis', 'Rice Cooker', 1, '2023-02-08 14:59:43', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (6, 'merek', 'Daikin', 1, '2023-02-08 15:00:22', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (7, 'merek', 'Sharp', 1, '2023-02-08 15:00:25', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (8, 'merek', 'Panasonic', 1, '2023-02-08 15:00:31', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (9, 'satuan', 'set', 1, '2023-02-08 21:33:04', 1, '2023-02-21 20:28:02', NULL);
INSERT INTO `ref_lookup` VALUES (10, 'jenis', 'TV', 1, '2023-02-08 21:44:54', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (11, 'merek', 'Toshiba', 1, '2023-02-08 21:45:23', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (12, 'merek', 'LG', 1, '2023-02-08 21:46:09', 1, '2023-02-09 10:31:21', NULL);
INSERT INTO `ref_lookup` VALUES (16, 'satuan', 'unit', 1, '2023-02-13 00:53:03', 1, '2023-02-21 20:27:26', 1);
INSERT INTO `ref_lookup` VALUES (17, 'satuan', 'roll', 1, '2023-02-13 00:58:22', 1, '2023-02-21 20:27:55', 1);
INSERT INTO `ref_lookup` VALUES (18, 'jenis', 'Kipas Angin x', 1, '2023-02-13 00:59:59', 1, '2023-02-13 01:00:06', 1);
INSERT INTO `ref_lookup` VALUES (19, 'merek', 'Sanyo x', 1, '2023-02-13 01:00:16', 1, '2023-02-13 01:00:24', 1);
INSERT INTO `ref_lookup` VALUES (20, 'tipe', 'Inventory', 1, '2023-02-15 08:47:16', 1, '2023-02-15 08:47:34', NULL);
INSERT INTO `ref_lookup` VALUES (21, 'tipe', 'Jasa', 1, '2023-02-15 08:47:20', 1, '2023-02-15 08:47:35', NULL);
INSERT INTO `ref_lookup` VALUES (22, 'tipe', 'Pipa', 1, '2023-02-15 08:47:26', 1, '2023-02-15 08:47:36', NULL);
INSERT INTO `ref_lookup` VALUES (23, 'jabatan', 'Manajemen', 1, '2023-02-17 22:13:01', 1, '2023-02-17 22:13:04', NULL);
INSERT INTO `ref_lookup` VALUES (24, 'jabatan', 'Teknisi', 1, '2023-02-17 22:13:11', 1, '2023-02-17 22:13:19', NULL);
INSERT INTO `ref_lookup` VALUES (25, 'jabatan', 'Supir', 1, '2023-02-17 22:13:17', 1, '2023-02-17 22:13:20', NULL);
INSERT INTO `ref_lookup` VALUES (26, 'jabatan', 'SysAdmin', 1, '2023-02-17 22:19:29', 1, NULL, NULL);
INSERT INTO `ref_lookup` VALUES (27, 'jenis', '1/4*3/8', 1, '2023-02-21 20:10:21', 1, '2023-02-21 20:10:36', NULL);
INSERT INTO `ref_lookup` VALUES (28, 'jenis', '1/4*1/2', 1, '2023-02-21 20:10:32', 1, '2023-02-21 20:10:37', NULL);
INSERT INTO `ref_lookup` VALUES (29, 'jenis', '3*1.5', 1, '2023-02-21 20:10:50', 1, '2023-02-21 20:11:45', NULL);
INSERT INTO `ref_lookup` VALUES (30, 'jenis', '3*2.5', 1, '2023-02-21 20:11:42', 1, '2023-02-21 20:11:44', NULL);
INSERT INTO `ref_lookup` VALUES (31, 'jenis', '4*2.5', 1, '2023-02-21 20:12:08', 1, NULL, NULL);
INSERT INTO `ref_lookup` VALUES (32, 'jenis', '1/4*5/8', 1, '2023-02-21 20:12:29', 1, '2023-02-21 20:14:02', NULL);
INSERT INTO `ref_lookup` VALUES (33, 'jenis', '3/4*5/8', 1, '2023-02-21 20:12:59', 1, '2023-02-21 20:17:28', NULL);
INSERT INTO `ref_lookup` VALUES (34, 'jenis', 'WH', 1, '2023-02-21 20:15:01', 1, '2023-02-21 20:17:28', NULL);
INSERT INTO `ref_lookup` VALUES (35, 'jenis', 'BAUT', 1, '2023-02-21 20:15:20', 1, '2023-02-21 20:17:28', NULL);
INSERT INTO `ref_lookup` VALUES (36, 'jenis', 'BRACKET', 1, '2023-02-21 20:15:35', 1, '2023-02-21 20:17:28', NULL);
INSERT INTO `ref_lookup` VALUES (37, 'jenis', 'DUCTTAPE', 1, '2023-02-21 20:15:50', 1, '2023-02-21 20:17:28', NULL);
INSERT INTO `ref_lookup` VALUES (38, 'jenis', 'FLEXIBLE', 1, '2023-02-21 20:16:02', 1, '2023-02-21 20:17:28', NULL);
INSERT INTO `ref_lookup` VALUES (39, 'jenis', 'Hamaflex', 1, '2023-02-21 20:16:13', 1, '2023-02-21 20:17:28', NULL);
INSERT INTO `ref_lookup` VALUES (40, 'merek', '', 1, '2023-02-21 20:18:29', 1, NULL, NULL);
INSERT INTO `ref_lookup` VALUES (41, 'merek', 'ETERNA', 1, '2023-02-21 20:19:37', 1, '2023-02-21 20:39:36', NULL);
INSERT INTO `ref_lookup` VALUES (42, 'merek', 'SAEKINDO', 1, '2023-02-21 20:20:37', 1, '2023-02-21 20:39:39', NULL);
INSERT INTO `ref_lookup` VALUES (43, 'merek', 'KEMBLA', 1, '2023-02-21 20:21:59', 1, '2023-02-21 20:39:39', NULL);
INSERT INTO `ref_lookup` VALUES (44, 'merek', 'HODA', 1, '2023-02-21 20:22:53', 1, '2023-02-21 20:39:39', NULL);
INSERT INTO `ref_lookup` VALUES (45, 'merek', 'INVERTER', 1, '2023-02-21 20:23:56', 1, '2023-02-21 20:39:39', NULL);
INSERT INTO `ref_lookup` VALUES (46, 'merek', 'INABA', 1, '2023-02-21 20:25:06', 1, '2023-02-21 20:39:39', NULL);
INSERT INTO `ref_lookup` VALUES (47, 'merek', 'FUJI', 1, '2023-02-21 20:25:42', 1, '2023-02-21 20:39:39', NULL);
INSERT INTO `ref_lookup` VALUES (48, 'satuan', 'batang', 1, '2023-02-21 20:28:26', 1, '2023-02-21 20:28:28', NULL);

-- ----------------------------
-- Table structure for ref_produk
-- ----------------------------
DROP TABLE IF EXISTS `ref_produk`;
CREATE TABLE `ref_produk`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `id_tipe` int(11) NOT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `id_jenis` int(11) NOT NULL,
  `id_merek` int(11) NOT NULL,
  `harga_beli` int(11) NOT NULL,
  `harga_jual` int(11) NOT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 230 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ref_produk
-- ----------------------------
INSERT INTO `ref_produk` VALUES (1, '003', 20, 'AC SPLIT DAIKIN 2PK', 16, 27, 40, 0, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:39:58', NULL);
INSERT INTO `ref_produk` VALUES (2, '004', 20, 'PIPA FREON 2.5-3PK', 1, 27, 40, 0, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (3, '005', 20, 'UCHIDA 2PK', 16, 28, 40, 0, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (4, '1001', 22, '1001', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (5, '1002', 22, '1002', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (6, '1003', 22, '1003', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (7, '1004', 22, '1004', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (8, '1005', 22, '1005', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (9, '1006', 22, '1006', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (10, '1007', 22, '1007', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (11, '1008', 22, '1008', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (12, '1009', 22, '1009', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (13, '1010', 22, '1010', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:27', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (14, '1011', 22, '1011', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (15, '1012', 22, '1012', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (16, '1013', 22, '1013', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (17, '1014', 22, '1014', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (18, '1015', 22, '1015', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (19, '1016', 22, '1016', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (20, '1017', 22, '1017', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (21, '1018', 22, '1018', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (22, '1019', 22, '1019', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (23, '1020', 22, '1020', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (24, '1021', 22, '1021', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (25, '1022', 22, '1022', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:02', NULL);
INSERT INTO `ref_produk` VALUES (26, '1023', 22, '1023', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (27, '1024', 22, '1024', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (28, '1025', 22, '1025', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (29, '1026', 22, '1026', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (30, '1027', 22, '1027', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (31, '1028', 22, '1028', 1, 29, 42, 9600, 0, NULL, 1, '2023-02-21 20:32:28', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (32, '1029', 22, '1029', 1, 29, 42, 9800, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (33, '1030', 22, '1030', 1, 29, 42, 9800, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (34, '1031', 22, '1031', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (35, '1032', 22, '1032', 1, 29, 41, 9800, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (36, '1033', 22, '1033', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (37, '1034', 22, '1034', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (38, '1035', 22, '1035', 1, 29, 41, 9600, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (39, '3001', 22, '3001', 1, 30, 41, 14200, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (40, '3002', 22, '3002', 1, 30, 41, 14200, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (41, '3003', 22, '3003', 1, 30, 41, 14200, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (42, '3004', 22, '3004', 1, 30, 41, 15200, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (43, '3005', 22, '3005', 1, 30, 41, 15200, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (44, '3006', 22, '3006', 1, 30, 41, 15200, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (45, '3007', 22, '3007', 1, 30, 41, 14900, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (46, '3008', 22, '3008', 1, 30, 41, 15200, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (47, '3009', 22, '3009', 1, 30, 41, 15700, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (48, '3010', 22, '3010', 1, 30, 41, 15700, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (49, '3011', 22, '3011', 1, 30, 41, 9900, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (50, '3012', 22, '3012', 1, 30, 41, 15000, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (51, '3013', 22, '3013', 1, 30, 41, 15000, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (52, '3014', 22, '3014', 1, 30, 41, 10540, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (53, '3015', 22, '3015', 1, 30, 41, 8600, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (54, '3016', 22, '3016', 1, 30, 41, 8600, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (55, '3017', 22, '3017', 1, 30, 41, 15000, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (56, '3018', 22, '3018', 1, 30, 41, 10540, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (57, '4001', 22, '4001', 1, 31, 41, 0, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (58, '4002', 22, '4002', 1, 31, 41, 0, 0, NULL, 1, '2023-02-21 20:32:29', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (59, '4003', 22, '4003', 1, 31, 41, 0, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (60, '4004', 22, '4004', 1, 31, 41, 13400, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (61, '6501', 22, '6501', 1, 27, 42, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (62, '6502', 22, '6502', 1, 27, 42, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (63, '6503', 22, '6503', 1, 27, 42, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (64, '6504', 22, '6504', 1, 27, 42, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (65, '6505', 22, '6505', 1, 27, 42, 51667, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (66, '6506', 22, '6506 KEMBLA', 1, 27, 43, 55000, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:03', NULL);
INSERT INTO `ref_produk` VALUES (67, '6507', 22, '6507', 1, 27, 44, 56666, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (68, '6508', 22, '6508', 1, 27, 44, 53334, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (69, '6509', 22, '6509', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (70, '6510', 22, '6510', 1, 27, 44, 56666, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (71, '6511', 22, '6511', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (72, '6512', 22, '6512', 1, 27, 42, 51667, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (73, '6513', 22, '6513', 1, 27, 44, 38334, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (74, '6514', 22, '6514', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (75, '6515', 22, '6515', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (76, '6516', 22, '6516', 1, 27, 42, 53334, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (77, '6517', 22, '6517', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (78, '6518', 22, '6518', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (79, '6519', 22, '6519', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (80, '6520', 22, '6520', 1, 27, 44, 56666, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (81, '6521', 22, '6521', 1, 27, 44, 0, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (82, '6522', 22, '6522', 1, 27, 44, 0, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (83, '6523', 22, '6523', 1, 27, 44, 0, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (84, '6524', 22, '6524', 1, 27, 44, 0, 0, NULL, 1, '2023-02-21 20:32:30', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (85, '6527', 22, '6527', 1, 27, 44, 75000, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (86, '6532', 22, '6532', 1, 27, 42, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (87, '6539', 22, '6539', 1, 27, 42, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (88, '6542', 22, '6542', 1, 27, 42, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (89, '6543', 22, '6543', 1, 27, 42, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (90, '6701', 22, '6701', 1, 27, 45, 56666, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (91, '6702', 22, '6702', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (92, '6703', 22, '6703', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (93, '6704', 22, '6704', 1, 27, 44, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (94, '6705', 22, '6705', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (95, '6706', 22, '6706', 1, 27, 44, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (96, '6707', 22, '6707', 1, 27, 44, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (97, '6708', 22, '6708', 1, 27, 44, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (98, '6709', 22, '6709', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (99, '6710', 22, '6710', 1, 27, 45, 55000, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (100, '6711', 22, '6711', 1, 27, 45, 53333, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (101, '6712', 22, '6712', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (102, '6713', 22, '6713', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (103, '6714', 22, '6714', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (104, '6715', 22, '6715', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:04', NULL);
INSERT INTO `ref_produk` VALUES (105, '6716', 22, '6716', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (106, '6717', 22, '6717', 1, 27, 45, 53334, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (107, '6718', 22, '6718', 1, 27, 45, 40000, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (108, '6801', 22, '6801', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (109, '6802', 22, '6802', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (110, '6803', 22, '6803', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (111, '6804', 22, '6804', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (112, '6805', 22, '6805', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (113, '6806', 22, '6806', 1, 27, 44, 59167, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (114, '6901', 22, '6901', 1, 27, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:31', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (115, '7501', 22, '7501', 1, 28, 44, 0, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (116, '7502', 22, '7502', 1, 28, 42, 65334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (117, '7503', 22, '7503', 1, 28, 42, 66667, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (118, '7504', 22, '7504', 1, 28, 44, 74334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (119, '7505', 22, '7505', 1, 28, 42, 66667, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (120, '7506', 22, '7506', 1, 28, 44, 57334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (121, '7507', 22, '7507', 1, 28, 42, 65334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (122, '7508', 22, '7508', 1, 28, 42, 65334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (123, '7509', 22, '7509', 1, 28, 42, 63334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (124, '7510', 22, '7510', 1, 28, 44, 63334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (125, '7511', 22, '7511', 1, 28, 43, 74334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (126, '7515', 22, '7515', 1, 28, 45, 68334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (127, '7519', 22, '7519', 1, 28, 42, 68334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (128, '7701', 22, '7701', 1, 28, 45, 65000, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (129, '7702', 22, '7702', 1, 28, 45, 65000, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (130, '7703', 22, '7703', 1, 28, 45, 74334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (131, '7704', 22, '7704', 1, 28, 45, 65334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (132, '7705', 22, '7705', 1, 28, 45, 65334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (133, '7706', 22, '7706', 1, 28, 45, 65000, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (134, '7707', 22, '7707', 1, 28, 45, 65000, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (135, '7708', 22, '7708', 1, 28, 45, 74334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (136, '7709', 22, '7709', 1, 28, 45, 68334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (137, '7710', 22, '7710', 1, 28, 45, 48334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:05', NULL);
INSERT INTO `ref_produk` VALUES (138, '7711', 22, '7711', 1, 28, 45, 0, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (139, '7712', 22, '7712', 1, 28, 45, 50000, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (140, '7713', 22, '7713', 1, 28, 45, 66667, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (141, '7714', 22, '7714', 1, 28, 45, 66667, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (142, '7715', 22, '7715', 1, 28, 45, 74334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (143, '7716', 22, '7716', 1, 28, 45, 65333, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (144, '7717', 22, '7717', 1, 28, 45, 74334, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (145, '7718', 22, '7718', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:32', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (146, '7719', 22, '7719', 1, 28, 45, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (147, '7720', 22, '7720', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (148, '7721', 22, '7721', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (149, '7722', 22, '7722', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (150, '7723', 22, '7723', 1, 28, 44, 74334, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (151, '7724', 22, '7724', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (152, '7725', 22, '7725', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (153, '7726', 22, '7726', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (154, '7727', 22, '7727', 1, 28, 45, 65000, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (155, '7728', 22, '7728', 1, 28, 45, 65000, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (156, '7729', 22, '7729', 1, 28, 45, 65000, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (157, '7730', 22, '7730', 1, 28, 45, 0, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (158, '7731', 22, '7731', 1, 28, 45, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (159, '7732', 22, '7732', 1, 28, 42, 0, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (160, '7801', 22, '7801', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:06', NULL);
INSERT INTO `ref_produk` VALUES (161, '7802', 22, '7802', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (162, '7803', 22, '7803', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (163, '7804', 22, '7804', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (164, '7805', 22, '7805', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (165, '7806', 22, '7806', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (166, '7807', 22, '7807', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (167, '7808', 22, '7808', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (168, '7809', 22, '7809', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (169, '7810', 22, '7810', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (170, '7811', 22, '7811', 1, 28, 46, 71666, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (171, '7901', 22, '7901', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (172, '7902', 22, '7902', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (173, '7903', 22, '7903', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (174, '7904', 22, '7904', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (175, '7905', 22, '7905', 1, 28, 44, 66667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (176, '8148', 22, '8148', 1, 32, 42, 96667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (177, '8165', 22, '8165', 1, 32, 42, 0, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (178, '8501', 22, '8501', 1, 32, 42, 96667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (179, '8502', 22, '8502 HODA', 1, 32, 42, 96667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (180, '8503', 22, '8503', 1, 32, 42, 96667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (181, '8504', 22, '8504', 1, 32, 42, 96667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (182, '8505', 22, '8505', 1, 32, 42, 81667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (183, '8506', 22, '8506', 1, 32, 42, 0, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (184, '8507', 22, '8507', 1, 32, 44, 100000, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (185, '8508', 22, '8508', 1, 32, 42, 81667, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (186, '8509', 22, '8509', 1, 32, 47, 0, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (187, '8510', 22, '8510', 1, 32, 42, 60000, 0, NULL, 1, '2023-02-21 20:32:33', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (188, '8511', 22, '8511', 1, 32, 42, 73334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (189, '8512', 22, '8512', 1, 32, 42, 58334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (190, '8513', 22, '8513', 1, 32, 42, 0, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (191, '8514', 22, '8514', 1, 32, 42, 58334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (192, '8515', 22, '8515', 1, 32, 42, 0, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (193, '8516', 22, '8516', 1, 32, 42, 58334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:07', NULL);
INSERT INTO `ref_produk` VALUES (194, '8517', 22, '8517', 1, 32, 42, 58334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (195, '8518', 22, '8518', 1, 32, 45, 58334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (196, '8519', 22, '8519', 1, 32, 42, 73334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (197, '8520', 22, '8520', 1, 32, 42, 73334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (198, '8521', 22, '8521', 1, 32, 42, 73334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (199, '9501', 22, '9501', 1, 33, 47, 80000, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (200, '9502', 22, '9502', 1, 33, 42, 106667, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (201, '9503', 22, '9503', 1, 33, 47, 0, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (202, '9504', 22, '9504 HODA', 1, 33, 44, 86667, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (203, '9505', 22, '9505', 1, 33, 47, 103334, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (204, '9506', 22, '9506', 1, 33, 42, 115000, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (205, '9507', 22, '9507', 1, 33, 42, 106667, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (206, '9508', 22, '9508', 1, 33, 45, 97667, 0, NULL, 1, '2023-02-21 20:32:34', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (207, '9509', 22, '9509', 1, 33, 45, 73334, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (208, '9601', 22, '9601', 1, 33, 45, 91667, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (209, '9602', 22, '9602', 1, 33, 45, 0, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (210, '9801', 22, '9801', 1, 33, 43, 124734, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (211, '9802', 22, '9802', 1, 33, 43, 124734, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (212, '9803', 22, '9803', 1, 33, 43, 124734, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (213, '9804', 22, '9804', 1, 33, 43, 124734, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (214, 'ACC.KRANMINI', 22, 'KRAN MINI MIXER', 2, 34, 40, 180000, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (215, 'ACC.SELANG', 22, 'Selang Shower', 2, 34, 40, 94000, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (216, 'ACC.Sock', 22, 'Sock WH', 2, 34, 40, 20000, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (217, 'BAUT', 22, 'BAUT', 2, 35, 40, 5500, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (218, 'BRK.PUTIHB', 22, 'BRACKET PUTIH BESAR', 9, 36, 40, 80000, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (219, 'BRK.PUTIHK', 22, 'BRACKET PUTIH KECIL', 9, 36, 40, 32000, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (220, 'BRK.TOYO', 22, 'BRACKET TOYO', 9, 36, 40, 36000, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (221, 'DUCTTAPE', 22, 'DUCTTAPE', 17, 37, 40, 4063, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:08', NULL);
INSERT INTO `ref_produk` VALUES (222, 'DUCTTAPE LEM', 22, 'Duct Tape Lem', 17, 37, 42, 0, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:09', NULL);
INSERT INTO `ref_produk` VALUES (223, 'FLEXIBLE WH', 22, 'FLEXIBLE WH', 2, 38, 40, 0, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:09', NULL);
INSERT INTO `ref_produk` VALUES (224, 'HAMAFLEX', 22, 'HAMAFLEX', 48, 39, 40, 0, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:09', NULL);
INSERT INTO `ref_produk` VALUES (225, 'KABEL HITAM', 22, 'KABEL HITAM', 17, 30, 41, 0, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:09', NULL);
INSERT INTO `ref_produk` VALUES (226, 'KBL.LG', 22, 'KBL.LG', 1, 29, 41, 0, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:09', NULL);
INSERT INTO `ref_produk` VALUES (227, 'Knee', 22, 'Knee WH', 2, 34, 40, 25000, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:09', NULL);
INSERT INTO `ref_produk` VALUES (228, 'PPA.FLEXIBLE', 22, 'PPA FLEXIBLE', 1, 38, 40, 700, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:09', NULL);
INSERT INTO `ref_produk` VALUES (229, 'dryer', 22, 'Dryer Midea', 2, 29, 40, 0, 0, NULL, 1, '2023-02-21 20:32:35', 1, '2023-02-21 20:40:09', NULL);

-- ----------------------------
-- Table structure for supplier
-- ----------------------------
DROP TABLE IF EXISTS `supplier`;
CREATE TABLE `supplier`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `kota` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `provinsi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kode_pos` char(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_telp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_hp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `kontak` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `bank` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `no_rekening` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `pemilik_rekening` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `npwp` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp(0),
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of supplier
-- ----------------------------
INSERT INTO `supplier` VALUES (1, 'SP0001', 'AWAL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:44:59', NULL, NULL, NULL);
INSERT INTO `supplier` VALUES (2, 'SP0002', 'SAEKINDO', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:44:59', NULL, NULL, NULL);
INSERT INTO `supplier` VALUES (3, 'SP0003', 'SEDAYU', NULL, NULL, NULL, NULL, '661.9779 / 662.7570', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1/4*3/8 1425\r\n1/4*1/2 1720\r\n17 FEB 2021', 1, '2023-02-21 20:44:59', NULL, '2023-02-21 20:47:38', NULL);
INSERT INTO `supplier` VALUES (4, 'SP0004', 'Sinar Budi', 'Sunter Kabel', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:44:59', NULL, NULL, NULL);
INSERT INTO `supplier` VALUES (5, 'SP0005', 'Griya Karsa KEMBLA', 'Ruko Mega Grosir Cempaka Mas Blok N No. 7', NULL, NULL, NULL, '0818.0624.9772 (Joy) + 4288.0618', NULL, NULL, NULL, 'BCA Pasar Baru', '0020.7777.11', 'Ivonne Wibisono', NULL, '1/4*3/8  0.6: 785.000  | 0.8: 976.000\r\n1/4*1/2  0.6: 959.000  | 0.8: 1.117.600\r\n1/4*5/8  0.6: 1.417.000\r\n3/8*5/8  0.6:1.575.000\r\nHAMAFLEX\r\n5/8 9MM 8.000\r\n5/8 13MM \r\n3/8*3/4 1.249.400\r\n1/2+3/4 1.456.400', 1, '2023-02-21 20:44:59', NULL, '2023-02-21 20:46:52', NULL);
INSERT INTO `supplier` VALUES (6, 'SP0006', 'UTAMA TEKNIK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:44:59', NULL, NULL, NULL);
INSERT INTO `supplier` VALUES (7, 'SP0007', 'Perbaikan', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:44:59', NULL, NULL, NULL);
INSERT INTO `supplier` VALUES (8, 'SP0011', 'TOKOPEDIA', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2023-02-21 20:44:59', NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
