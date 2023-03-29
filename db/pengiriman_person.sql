CREATE TABLE `pengiriman_person` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengiriman` int(11) NOT NULL,
  `id_pengguna` int(11) NOT NULL,
  `tipe` varchar(100) NOT null COMMENT 'supir , kenek , teknisi',
  `keterangan` varchar(200) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;