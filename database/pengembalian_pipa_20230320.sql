/*
 Navicat Premium Data Transfer

 Source Server         : mpe_pos
 Source Server Type    : MySQL
 Source Server Version : 100420
 Source Host           : localhost:3306
 Source Schema         : makmurpermai

 Target Server Type    : MySQL
 Target Server Version : 100420
 File Encoding         : 65001

 Date: 20/03/2023 03:50:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pengembalian_pipa
-- ----------------------------
DROP TABLE IF EXISTS `pengembalian_pipa`;
CREATE TABLE `pengembalian_pipa`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tgl` date NOT NULL,
  `id_pengiriman` int NULL DEFAULT NULL,
  `qty` int NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `row_status` tinyint NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `created_by` int NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
