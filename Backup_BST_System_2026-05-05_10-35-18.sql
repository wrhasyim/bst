-- BST SYSTEM DATABASE BACKUP
-- Tanggal: 2026-05-05 10:35:18

SET FOREIGN_KEY_CHECKS = 0;



DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `activity_logs` VALUES("1","1","Logout","User keluar dari sistem","::1","2026-05-04 16:46:33");
INSERT INTO `activity_logs` VALUES("2","1","Login","User berhasil masuk ke dalam sistem","::1","2026-05-04 16:47:23");
INSERT INTO `activity_logs` VALUES("3","1","Logout","User keluar dari sistem","::1","2026-05-04 16:47:25");
INSERT INTO `activity_logs` VALUES("4","1","Login","User berhasil masuk ke dalam sistem","::1","2026-05-04 16:47:27");
INSERT INTO `activity_logs` VALUES("5","1","Login","User berhasil masuk ke dalam sistem","::1","2026-05-04 16:52:43");
INSERT INTO `activity_logs` VALUES("6","1","Input Setoran Kelas","Petugas menginput setoran batch sebanyak 10000 Pcs (Status: Pending)","::1","2026-05-04 16:54:29");
INSERT INTO `activity_logs` VALUES("7","1","Validasi Setoran","Petugas memvalidasi setoran ID #1 sebesar Rp 10,000","::1","2026-05-04 16:54:38");
INSERT INTO `activity_logs` VALUES("8","1","Denda Kesiswaan","Petugas mencatat denda pelanggaran sebesar 15 Pcs ke Kas Kesiswaan","::1","2026-05-04 16:54:46");
INSERT INTO `activity_logs` VALUES("9","1","Input Setoran Guru","Petugas menginput setoran guru sebesar 13514 Pcs (Status: Pending)","::1","2026-05-04 16:55:13");
INSERT INTO `activity_logs` VALUES("10","1","Input Setoran Guru","Petugas menginput setoran guru sebesar 1000 Pcs (Status: Pending)","::1","2026-05-04 16:55:21");
INSERT INTO `activity_logs` VALUES("11","1","Validasi Setoran","Petugas memvalidasi setoran ID #3 sebesar Rp 13,514","::1","2026-05-04 16:56:14");
INSERT INTO `activity_logs` VALUES("12","1","Validasi Setoran","Petugas memvalidasi setoran ID #4 sebesar Rp 1,000","::1","2026-05-04 16:56:15");
INSERT INTO `activity_logs` VALUES("13","1","Input Setoran Guru","Petugas menginput setoran guru sebesar 12 Pcs (Status: Pending)","::1","2026-05-04 17:00:24");
INSERT INTO `activity_logs` VALUES("14","1","Validasi Setoran","Petugas memvalidasi setoran ID #5 sebesar Rp 12","::1","2026-05-04 17:00:30");
INSERT INTO `activity_logs` VALUES("15","1","Penarikan Saldo","Kasir mencairkan total dana kelas x sebesar Rp10.000 untuk 1 siswa.","::1","2026-05-04 17:05:55");
INSERT INTO `activity_logs` VALUES("16","1","Login","User berhasil masuk ke dalam sistem","::1","2026-05-05 07:21:53");
INSERT INTO `activity_logs` VALUES("17","1","Update Pengaturan","Admin mengubah persentase bagi hasil (Menambahkan porsi Siswa Piket).","::1","2026-05-05 07:50:56");
INSERT INTO `activity_logs` VALUES("18","1","Update Pengaturan","Admin mengubah persentase bagi hasil (Menambahkan porsi Siswa Piket).","::1","2026-05-05 07:51:17");
INSERT INTO `activity_logs` VALUES("19","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 08:12:12");
INSERT INTO `activity_logs` VALUES("20","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 08:12:26");
INSERT INTO `activity_logs` VALUES("21","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 08:12:50");
INSERT INTO `activity_logs` VALUES("22","1","Reset Transaksi","DANGER: Admin mengosongkan seluruh riwayat transaksi!","::1","2026-05-05 08:15:14");
INSERT INTO `activity_logs` VALUES("23","1","Reset Total Sistem","CRITICAL DANGER: Admin melakukan Reset Total Sistem (Wipe Out)!","::1","2026-05-05 08:15:27");
INSERT INTO `activity_logs` VALUES("24","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 08:15:54");
INSERT INTO `activity_logs` VALUES("25","1","Backup Database","Admin mengunduh file backup SQL sistem.","::1","2026-05-05 08:16:15");
INSERT INTO `activity_logs` VALUES("26","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 08:33:49");
INSERT INTO `activity_logs` VALUES("27","1","Login","User berhasil masuk ke dalam sistem","::1","2026-05-05 11:31:37");
INSERT INTO `activity_logs` VALUES("28","1","Input Setoran Guru","Petugas menginput setoran guru sebesar 50 Pcs (Status: Pending)","::1","2026-05-05 11:31:47");
INSERT INTO `activity_logs` VALUES("29","1","Input Setoran Guru","Petugas menginput setoran guru sebesar 50 Pcs (Status: Pending)","::1","2026-05-05 11:34:50");
INSERT INTO `activity_logs` VALUES("30","1","Validasi Setoran","Petugas memvalidasi setoran ID #11 sebesar Rp 2.500.000","::1","2026-05-05 12:41:25");
INSERT INTO `activity_logs` VALUES("31","1","Validasi Setoran","Petugas memvalidasi setoran ID #12 sebesar Rp 2.500.000","::1","2026-05-05 12:41:26");
INSERT INTO `activity_logs` VALUES("32","1","Input Setoran Kelas","Petugas menginput setoran batch sebanyak 1600 Pcs (Status: Pending)","::1","2026-05-05 12:41:55");
INSERT INTO `activity_logs` VALUES("33","1","Validasi Setoran","Petugas memvalidasi setoran ID #13 sebesar Rp 25.000.000","::1","2026-05-05 12:41:58");
INSERT INTO `activity_logs` VALUES("34","1","Validasi Setoran","Petugas memvalidasi setoran ID #14 sebesar Rp 30.000.000","::1","2026-05-05 12:41:59");
INSERT INTO `activity_logs` VALUES("35","1","Validasi Setoran","Petugas memvalidasi setoran ID #15 sebesar Rp 25.000.000","::1","2026-05-05 12:42:00");
INSERT INTO `activity_logs` VALUES("36","1","Input Setoran Kelas","Petugas menginput setoran batch sebanyak 9000 Pcs (Status: Pending)","::1","2026-05-05 12:43:38");
INSERT INTO `activity_logs` VALUES("37","1","Validasi Setoran","Petugas memvalidasi setoran ID #16 sebesar Rp 450.000.000","::1","2026-05-05 12:43:43");
INSERT INTO `activity_logs` VALUES("38","1","Denda Kesiswaan","Petugas mencatat denda pelanggaran sebesar 500 Pcs ke Kas Kesiswaan","::1","2026-05-05 12:45:24");
INSERT INTO `activity_logs` VALUES("39","1","Reset Total Sistem","CRITICAL DANGER: Admin melakukan Reset Total Sistem (Wipe Out)!","::1","2026-05-05 13:13:06");
INSERT INTO `activity_logs` VALUES("40","1","Denda Kesiswaan","Petugas mencatat denda pelanggaran sebesar 50 Pcs ke Kas Kesiswaan","::1","2026-05-05 13:38:25");
INSERT INTO `activity_logs` VALUES("41","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 13:42:05");
INSERT INTO `activity_logs` VALUES("42","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 13:42:26");
INSERT INTO `activity_logs` VALUES("43","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 13:44:28");
INSERT INTO `activity_logs` VALUES("44","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 13:44:52");
INSERT INTO `activity_logs` VALUES("45","1","Input Setoran Kelas","Petugas menginput setoran batch sebanyak 1000 Pcs (Status: Pending)","::1","2026-05-05 14:02:27");
INSERT INTO `activity_logs` VALUES("46","1","Validasi Setoran","Petugas memvalidasi setoran ID #4 sebesar Rp 25.000.000","::1","2026-05-05 14:02:30");
INSERT INTO `activity_logs` VALUES("47","1","Validasi Setoran","Petugas memvalidasi setoran ID #5 sebesar Rp 25.000.000","::1","2026-05-05 14:02:30");
INSERT INTO `activity_logs` VALUES("48","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 14:03:44");
INSERT INTO `activity_logs` VALUES("49","1","Input Setoran Kelas","Petugas menginput setoran batch sebanyak 1000 Pcs (Status: Pending)","::1","2026-05-05 14:03:56");
INSERT INTO `activity_logs` VALUES("50","1","Validasi Setoran","Petugas memvalidasi setoran ID #6 sebesar Rp 25.000.000","::1","2026-05-05 14:03:58");
INSERT INTO `activity_logs` VALUES("51","1","Validasi Setoran","Petugas memvalidasi setoran ID #7 sebesar Rp 25.000.000","::1","2026-05-05 14:03:59");
INSERT INTO `activity_logs` VALUES("52","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 14:04:42");
INSERT INTO `activity_logs` VALUES("53","1","Input Setoran Kelas","Petugas menginput setoran batch sebanyak 1000 Pcs (Status: Pending)","::1","2026-05-05 14:04:53");
INSERT INTO `activity_logs` VALUES("54","1","Validasi Setoran","Petugas memvalidasi setoran ID #8 sebesar Rp 25.000.000","::1","2026-05-05 14:04:56");
INSERT INTO `activity_logs` VALUES("55","1","Validasi Setoran","Petugas memvalidasi setoran ID #9 sebesar Rp 25.000.000","::1","2026-05-05 14:04:57");
INSERT INTO `activity_logs` VALUES("56","1","Input Setoran Kelas","Petugas menginput setoran batch sebanyak 5000 Pcs (Status: Pending)","::1","2026-05-05 14:13:23");
INSERT INTO `activity_logs` VALUES("57","1","Validasi Setoran","Petugas memvalidasi setoran ID #10 sebesar Rp 250.000.000","::1","2026-05-05 14:13:26");
INSERT INTO `activity_logs` VALUES("58","1","Input Setoran Kelas","Petugas menginput setoran batch sebanyak 9999 Pcs (Status: Pending)","::1","2026-05-05 14:23:38");
INSERT INTO `activity_logs` VALUES("59","1","Validasi Setoran","Petugas memvalidasi setoran ID #11 sebesar Rp 499.950.000","::1","2026-05-05 14:23:40");
INSERT INTO `activity_logs` VALUES("60","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 15:11:37");
INSERT INTO `activity_logs` VALUES("61","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 15:23:28");
INSERT INTO `activity_logs` VALUES("62","1","Update Pengaturan","Admin memperbarui profil institusi dan distribusi margin honor (5 Entitas).","::1","2026-05-05 15:25:40");
INSERT INTO `activity_logs` VALUES("63","1","Reset Transaksi","DANGER: Admin mengosongkan seluruh riwayat transaksi!","::1","2026-05-05 15:31:04");
INSERT INTO `activity_logs` VALUES("64","1","Reset Total Sistem","CRITICAL DANGER: Admin melakukan Reset Total Sistem (Wipe Out)!","::1","2026-05-05 15:31:06");
INSERT INTO `activity_logs` VALUES("65","1","Backup Database","Admin mengunduh file backup SQL sistem.","::1","2026-05-05 15:35:18");


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
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kategori_sampah` VALUES("5","🌟 REWARD PRESTASI","0.00","0.00","0.00","Bonus");


DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) NOT NULL,
  `walikelas_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pengaturan` VALUES("1","persen_kas_sekolah","10");
INSERT INTO `pengaturan` VALUES("2","persen_honor_pengelola","30");
INSERT INTO `pengaturan` VALUES("3","persen_honor_walikelas","10");
INSERT INTO `pengaturan` VALUES("4","nama_sekolah","SMK Taruna Karya Mandiri");
INSERT INTO `pengaturan` VALUES("5","persen_honor_piket","20");
INSERT INTO `pengaturan` VALUES("6","persen_kas_bst","30");
INSERT INTO `pengaturan` VALUES("7","alamat_sekolah","Jl. Purwajaya Dusun Banir RT.01/04, Desa Purwajaya, Kecamatan Tempuran, Kabupaten Karawang, Jawa Barat");


DROP TABLE IF EXISTS `penjualan`;
CREATE TABLE `penjualan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `total_pcs` double NOT NULL,
  `harga_per_pcs` double NOT NULL,
  `total_pendapatan` decimal(15,2) NOT NULL,
  `beban_nasabah_rp` double DEFAULT 0,
  `margin_total_rp` double DEFAULT 0,
  `kas_sekolah_rp` double DEFAULT 0,
  `honor_pengelola_rp` double DEFAULT 0,
  `honor_piket_rp` double DEFAULT 0,
  `kas_bst_rp` double DEFAULT 0,
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
  `honor_walas_rp` double DEFAULT 0,
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
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1004 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES("1","Administrator","admin","$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi","admin",NULL,NULL,"1",NULL);
INSERT INTO `users` VALUES("19","KAS KESISWAAN (DENDA)","kesiswaan","$2y$10$J8NFTtxScANV6OZLO6nhjO6ZG9V2XZrBmyrqw6wGSkwQA0.UolFIy","siswa","3","","1",NULL);

SET FOREIGN_KEY_CHECKS = 1;
