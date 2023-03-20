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

 Date: 20/03/2023 03:50:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pengembalian_pipa_detail
-- ----------------------------
DROP TABLE IF EXISTS `pengembalian_pipa_detail`;
CREATE TABLE `pengembalian_pipa_detail`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pengembalian_pipa` int NULL DEFAULT NULL,
  `id_produk` int NULL DEFAULT NULL,
  `id_satuan` int NULL DEFAULT NULL,
  `satuan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `qty_bawa` int NULL DEFAULT NULL,
  `qty_kembali` int NULL DEFAULT NULL,
  `row_status` tinyint NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp,
  `created_by` int NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `ppd_id_pengembalian_pipa`(`id_pengembalian_pipa`) USING BTREE,
  INDEX `ppd_id_produk`(`id_produk`) USING BTREE,
  INDEX `ppd_id_satuan`(`id_satuan`) USING BTREE,
  CONSTRAINT `fk_ppd_id_produk` FOREIGN KEY (`id_produk`) REFERENCES `ref_produk` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_ppd_id_pengembalian_pipa` FOREIGN KEY (`id_pengembalian_pipa`) REFERENCES `pengembalian_pipa` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 33 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
