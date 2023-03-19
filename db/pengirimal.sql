-- mpe.pengiriman definition

CREATE TABLE `pengiriman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `no_transaksi` varchar(50) NOT NULL,
  `tgl` date NOT NULL,
  `id_pelanggan` int(11) NOT NULL,
  `id_faktur` int(11) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `qty_pesan` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;


-- mpe.pengiriman_detail definition

CREATE TABLE `pengiriman_detail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_pengiriman` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `id_satuan` int(11) NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `qty` int(11) NOT NULL,
  `harga_satuan` int(11) NOT NULL,
  `uraian` varchar(200) NOT NULL,
  `sub_total` int(11) NOT NULL,
  `row_status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;