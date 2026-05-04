-- BST SYSTEM DATABASE BACKUP
-- Tanggal: 2026-05-04 09:51:28

SET FOREIGN_KEY_CHECKS = 0;



DROP TABLE IF EXISTS `detail_setoran`;
CREATE TABLE `detail_setoran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setoran_id` int(11) NOT NULL,
  `kategori_sampah_id` int(11) NOT NULL,
  `berat` decimal(8,2) NOT NULL,
  `harga_saat_ini` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `kas_manual`;
CREATE TABLE `kas_manual` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'Admin yang menginput',
  `tanggal` date NOT NULL,
  `jenis` enum('pemasukan','pengeluaran') NOT NULL,
  `nominal` double NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `kategori_sampah`;
CREATE TABLE `kategori_sampah` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_sampah` varchar(100) NOT NULL,
  `harga_dasar` decimal(15,2) NOT NULL DEFAULT 0.00,
  `harga_guru` decimal(10,2) DEFAULT 0.00,
  `harga_pengepul` decimal(15,2) NOT NULL DEFAULT 0.00,
  `satuan` varchar(20) DEFAULT 'Pcs',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kategori_sampah` VALUES("5","🌟 REWARD PRESTASI","0.00","0.00","0.00","Bonus");


DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) NOT NULL,
  `walikelas_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kelas` VALUES("3","KAS KESISWAAN",NULL);


DROP TABLE IF EXISTS `penarikan`;
CREATE TABLE `penarikan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `tanggal_tarik` timestamp NOT NULL DEFAULT current_timestamp(),
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `pencairan_honor`;
CREATE TABLE `pencairan_honor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `jumlah` decimal(15,2) NOT NULL,
  `jenis` enum('walikelas','pengelola') NOT NULL,
  `tanggal_cair` timestamp NOT NULL DEFAULT current_timestamp(),
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `pengaturan`;
CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kunci` varchar(50) NOT NULL,
  `nilai` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kunci` (`kunci`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pengaturan` VALUES("1","persen_kas_sekolah","10");
INSERT INTO `pengaturan` VALUES("2","persen_honor_pengelola","20");
INSERT INTO `pengaturan` VALUES("3","persen_honor_walikelas","10");
INSERT INTO `pengaturan` VALUES("4","nama_sekolah","SMK Taruna Karya Mandiri");


DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE `penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `total_berat` decimal(10,2) NOT NULL,
  `harga_per_kg` decimal(15,2) NOT NULL,
  `total_pendapatan` decimal(15,2) NOT NULL,
  `tanggal_jual` timestamp NOT NULL DEFAULT current_timestamp(),
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `setoran`;
CREATE TABLE `setoran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `kategori_id` int(11) NOT NULL,
  `berat` decimal(10,2) NOT NULL,
  `total_harga` decimal(15,2) NOT NULL,
  `total_pengepul` decimal(15,2) NOT NULL,
  `walikelas_id` int(11) DEFAULT NULL,
  `status` enum('pending','valid') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_sold` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','siswa','staff','alumni') NOT NULL,
  `kelas_id` int(11) DEFAULT NULL,
  `angkatan` varchar(10) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES("1","Administrator","admin","$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi","admin",NULL,NULL,"1");
INSERT INTO `users` VALUES("19","KAS KESISWAAN (DENDA)","kesiswaan","$2y$10$J8NFTtxScANV6OZLO6nhjO6ZG9V2XZrBmyrqw6wGSkwQA0.UolFIy","siswa","3","","1");

SET FOREIGN_KEY_CHECKS = 1;
