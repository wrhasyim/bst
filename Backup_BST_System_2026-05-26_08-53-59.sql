-- BST SYSTEM DATABASE BACKUP
-- Tanggal: 2026-05-26 08:53:59

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
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
INSERT INTO `activity_logs` VALUES("66","1","Reset Total Sistem","CRITICAL DANGER: Admin melakukan Reset Total Sistem (Wipe Out)!","::1","2026-05-05 16:34:35");
INSERT INTO `activity_logs` VALUES("67","1","Backup Database","Admin mengunduh file backup SQL sistem.","::1","2026-05-05 16:34:43");
INSERT INTO `activity_logs` VALUES("68","1","Login","User berhasil masuk ke dalam sistem","::1","2026-05-26 12:39:36");
INSERT INTO `activity_logs` VALUES("69","1","Backup Database","Admin mengunduh file backup SQL sistem.","::1","2026-05-26 13:16:26");
INSERT INTO `activity_logs` VALUES("70","1","Restore Database","Admin memulihkan database dari file SQL.","::1","2026-05-26 13:19:39");
INSERT INTO `activity_logs` VALUES("71","1","Backup Database","Admin mengunduh file backup SQL sistem.","::1","2026-05-26 13:21:18");
INSERT INTO `activity_logs` VALUES("72","1","Backup Database","Admin mengunduh file backup SQL sistem.","::1","2026-05-26 13:30:20");
INSERT INTO `activity_logs` VALUES("73","1","Backup Database","Admin mengunduh file backup SQL sistem.","::1","2026-05-26 13:53:59");


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
) ENGINE=InnoDB AUTO_INCREMENT=1002 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kategori_sampah` VALUES("5","🌟 REWARD PRESTASI","0.00","0.00","0.00","Bonus");


DROP TABLE IF EXISTS `kelas`;
CREATE TABLE `kelas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(50) NOT NULL,
  `walikelas_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1027 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kelas` VALUES("3","KAS KESISWAAN",NULL);
INSERT INTO `kelas` VALUES("1003","X MPLB 1",NULL);
INSERT INTO `kelas` VALUES("1004","X MPLB 2",NULL);
INSERT INTO `kelas` VALUES("1005","X TM 1",NULL);
INSERT INTO `kelas` VALUES("1006","X TM 2",NULL);
INSERT INTO `kelas` VALUES("1007","X TM 3",NULL);
INSERT INTO `kelas` VALUES("1008","X TM 4",NULL);
INSERT INTO `kelas` VALUES("1009","X TM 5",NULL);
INSERT INTO `kelas` VALUES("1010","XI MPLB 1",NULL);
INSERT INTO `kelas` VALUES("1011","XI MPLB 2",NULL);
INSERT INTO `kelas` VALUES("1012","XI TM 1",NULL);
INSERT INTO `kelas` VALUES("1013","XI TM 2",NULL);
INSERT INTO `kelas` VALUES("1014","XI TM 3",NULL);
INSERT INTO `kelas` VALUES("1015","XI TM 4",NULL);
INSERT INTO `kelas` VALUES("1016","XI TM 5",NULL);
INSERT INTO `kelas` VALUES("1017","XI TM 6",NULL);
INSERT INTO `kelas` VALUES("1018","XII MPLB 1",NULL);
INSERT INTO `kelas` VALUES("1019","XII MPLB 2",NULL);
INSERT INTO `kelas` VALUES("1020","XII TM 1",NULL);
INSERT INTO `kelas` VALUES("1021","XII TM 2",NULL);
INSERT INTO `kelas` VALUES("1022","XII TM 5",NULL);
INSERT INTO `kelas` VALUES("1023","XII TM 6",NULL);
INSERT INTO `kelas` VALUES("1024","XII TM 3",NULL);
INSERT INTO `kelas` VALUES("1025","XII TM 4",NULL);
INSERT INTO `kelas` VALUES("1026","XII TM 7",NULL);


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
) ENGINE=InnoDB AUTO_INCREMENT=1857 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` VALUES("1","Administrator","admin","$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi","admin",NULL,NULL,"1",NULL);
INSERT INTO `users` VALUES("19","KAS KESISWAAN (DENDA)","kesiswaan","$2y$10$J8NFTtxScANV6OZLO6nhjO6ZG9V2XZrBmyrqw6wGSkwQA0.UolFIy","siswa","3","","1",NULL);
INSERT INTO `users` VALUES("1062","ALLEEISYA DAVINA KAMANDHANIKA","2425028","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1063","ALYA HANDAYANI","2425029","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1064","ANI SUHAENI","2425030","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1065","ANITA","2425031","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1066","APRILLIA ANATASYA","2425032","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1067","AULIA ISHAQUE","2425033","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1068","AVRIL AVPRIDA HARIYANA","2425034","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1069","CITRA LESTARI ROHIMAH","2425035","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1070","DINA","2425036","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1071","ENENG JUMARIYAH","2425037","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1072","ENGKOM KOMARIAH","2425038","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1073","HENA ALI","2425039","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1074","JIHAN ANNASTASYA","2425040","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1075","KURNIA HANAPIAH ISWANDI","2425041","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1076","LATIFAH","2425042","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1077","NASYA MAHARANI","2425043","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1078","NUR ALBIAH SALWA","2425044","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1079","NUR SIFA ANGGAENI","2425045","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1080","PUTRI SASKIA","2425046","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1081","RAHMA YANTI","2425047","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1082","RANI AMELIA","2425048","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1083","SAHAR SYAFITRI","2425049","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1084","SALSA BILA AZZAHRA","2425050","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1085","SHIFA ADELIA MAWAR","2425051","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1086","SINTA SUTIA","2425052","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1087","SITI NURZAHRA","2425053","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1088","SITI ROMLAH","2425054","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1089","TANIA OKTAVIANI","2425055","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1090","WINDI ALTIYAH","2425056","$2y$10$Y4iYe627RfCzwTUBEhCgk.NZguBRD0Y9a454E8qZAXid06D0C77k2","siswa","1004","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1091","ADILAH","2425001","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1092","ADINDA OKTAVIANA","2425002","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1093","ATIK CANTIKA","2425003","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1094","AYU FADILLAH","2425004","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1095","BINTANG SAMUDRA SATYABUDIE","2425005","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1096","DESFA NAILATUL NAFISA","2425006","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1097","DONI RAMADHANI","2425007","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1098","FITRIA ZAHRATUNNAJA","2425008","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1099","INDAH AFIFA","2425009","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1100","INDAH LISNAWATI","2425010","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1101","INDRI PUSPITASARI","2425011","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1102","IQBAL MAULANA","2425012","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1103","JIHAN KHOLILAH","2425013","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1104","KARLINA","2425014","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1105","KHOLIFAH RIZKA NURSAADAH","2425015","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1106","KOMARIAH","2425016","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1107","NURUL HABIBAH","2425017","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1108","RISMA NURMALA","2425018","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1109","SALWA ANUROH","2425019","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1110","SHIPA","2425020","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1111","SHOFIA AISYAH MUSAFAAH","2425021","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1112","SITI ALISA","2425022","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1113","SITI AMINAH","2425023","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1114","SITI FATIMAH","2425024","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1115","WARNIH MELINDA","2425025","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1116","WINA","2425026","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1117","ZASKIA PUTRI","2425027","$2y$10$XznL3RofoqBkQC1k6FkZquJ8g5rv9OpywflMX7bhx/YOB7taNAbXO","siswa","1003","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1118","ABU HAMAD MASKURI","2425057","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1119","ADE IRWANSYAH","2425058","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1120","ALDO SUTISNA","2425059","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1121","ANDIKA PAJAR MAHDI","2425060","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1122","ANDINI ANGGRAENI","2425061","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1123","BAGAS ARDIWINATA","2425062","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1124","CHIKA SIPA ANGGITA","2425063","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1125","DANI SUTISNA","2425064","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1126","DANIA OKTAVIA","2425065","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1127","DIMAS AHMAD RAMADAN","2425066","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1128","DIRLY ANGGADITA","2425067","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1129","EDI SETIA GUNAWAN","2425068","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1130","EKA BAMBANG SAPUTRA","2425069","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1131","ENDRIK PURNAMA","2425070","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1132","HERMANSYAH","2425071","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1133","HIDAYATULLAH","2425072","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1134","JAEN","2425073","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1135","JULIYANTO","2425074","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1136","MIFTAHUL ARIFIN","2425075","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1137","MIT F FELIX GUSNA","2425076","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1138","MUHAMAD MUHIBIN","2425077","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1139","MUHAMAD RADITIYA","2425078","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1140","MUHAMAD RIZKI BAEHAKI","2425079","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1141","MUHAMAD RIZKY HABIBI","2425080","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1142","MUHAMMAD AL ISRA FIRMANSYAH","2425081","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1143","MUHAMMAD FARHAN ADI PUTRA","2425082","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1144","RIFALDI JULIANSYAH","2425083","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1145","RINDI","2425084","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1146","RISFI HIDAYAT","2425085","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1147","TAOPIK HIDAYAH","2425086","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1148","WISNU HASLAN FARID","2425087","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1149","YOMI ABDUL MANAH","2425088","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1150","ZAKI IBNU KHAIRI","2425089","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1151","AHMAD RANDIKA","2425090","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1152","CANDRA AKBAR","2425091","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1153","DIMAS SETO","2425092","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1154","FAIQ SUGILAR","2425093","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1155","NUAF DHANU WINATA","2425094","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1156","RITA FITRIA","2425095","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1157","WINDY OCTOVIYA","2425096","$2y$10$jPWher0/RxKxQf9h7FQJSOi7q.FL1mZg0x5h4Cgzzrpq8YTnt0qgi","siswa","1005","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1158","ABDUL LATIF","2425097","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1159","ADE IRFAN","2425098","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1160","ADE KRISYANTO","2425099","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1161","ADITIA SIMATUPANG","2425100","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1162","AHMAD FAUZAN","2425101","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1163","AHMAD FAUZI","2425102","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1164","AKBAR JUNIARDI","2425103","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1165","ALDI","2425104","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1166","BUNGA IRMA REFALIA","2425105","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1167","DENIS","2425106","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1168","DIDI FIRJATULLOH FADIB","2425107","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1169","DIMAS HAFIDDUDIN","2425108","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1170","DINI AMELIA PUTRI","2425109","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1171","ENJELIKA APRIDA","2425110","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1172","FAISAL AKBAR","2425111","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1173","HAFID JULIANA","2425112","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1174","IQBAL ALBIANSYAH","2425113","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1175","IRWAN SAPUTRA","2425114","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1176","MUHAMAD FAZRI","2425115","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1177","MUHAMAD HUSAIN HAFIZHULLAH","2425116","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1178","MUHAMAD SOLEH ALFIKRI","2425117","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1179","MUHAMMAD REFAN REPIS","2425118","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1180","NIZAR NASIHUDIN","2425119","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1181","RAHMAT MURTI UMAY PUTRA","2425120","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1182","REYHAN ABIDIN","2425121","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1183","REZA ARDIANSAH","2425122","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1184","RHEDY AFRILIANS NUGRAHA","2425123","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1185","ROSALINDA","2425124","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1186","SITI MARYAM","2425125","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1187","SITI PAIDAH","2425126","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1188","SRI LESTARI","2425127","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1189","TRYO ABDULGONI","2425128","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1190","TUBAGUS NAWAWI","2425129","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1191","ANDHIKA RAMADHAN","2425130","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1192","FAJRUL MA ANI","2425131","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1193","FUJA SAEPULLOH","2425132","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1194","NENENG HABIBAH","2425133","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1195","ROFIKOH ALIYATUL SUNIAH","2425134","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1196","SETIYAJI AHMAD FAUZI","2425135","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1197","YUDI ADITIA","2425136","$2y$10$I4qzen1aFGvMMJBG5mC/2uJGyyVBANmPH4QcmxRAtQdRycY6OAqFS","siswa","1006","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1198","AHMAD FAUZI","2425137","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1199","AHMAD RIDWAN","2425138","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1200","AHMAD RIFAI","2425139","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1201","AHMAD SAEPUDIN","2425140","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1202","AKBAR MAULIANSYACH","2425141","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1203","ANDINI","2425142","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1204","ARIYA DARMAWANSYAH","2425143","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1205","ARSYA NUR HARLY HASAN","2425144","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1206","AYU ADIRA","2425145","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1207","CANDRA MAULANA","2425146","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1208","CIKAL ERLANGGA","2425147","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1209","DENI RIZKY PRATAMA","2425148","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1210","GALANG RAMDANI","2425149","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1211","HADID SWARA GUMILANG","2425150","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1212","KARNATA JAYADI NINGRAT","2425151","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1213","KARTIKA SARI","2425152","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1214","MARSHALL MILANIZT STILL Z","2425153","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1215","MEISYA EUIS SADIAH","2425154","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1216","MOHAMMAD ZEYAD","2425155","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1217","MUHAMAD FARHAN","2425156","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1218","MUHAMAD IRFAN MAULANA","2425157","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1219","MUHAMAD JIHAD ILMA ATTAMIMI","2425158","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1220","MUHAMAD RIZKI","2425159","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1221","MUHAMMAD DANIAL ALPIKRI","2425160","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1222","MUHAMMAD ZAELANI","2425161","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1223","NOVAL JUNIAN SALEH","2425162","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1224","NURJATI","2425163","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1225","RAMADAN","2425164","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1226","RIFQI QHAIRUL HASAN","2425165","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1227","RIZKI NUR AL FAHRI","2425166","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1228","SAEFUL GOFAR","2425167","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1229","SAEPUL MAULANA","2425168","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1230","SALSA NABILA","2425169","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1231","SITI HASANAH","2425170","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1232","SUHENDRA","2425171","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1233","SUTISNA PRATAMA","2425172","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1234","TEDI MAULANA","2425173","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1235","WAHYU SUPRIATNA","2425174","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1236","WALID FAJAR RAMADHAN","2425175","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1237","YUSUP","2425176","$2y$10$NpFSo/hXYtNlNH2NIgyK7O1Dd0rA3r3avJ4Fin.IUJez9fLO9bmyC","siswa","1007","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1238","ADIL ALZULFA","2425177","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1239","AGAM MADANI","2425178","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1240","AGUS SAPUTRA","2425179","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1241","AHMAD AZMI KHOERUNNIZAR","2425180","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1242","AHMAD RIZKY KURNIAWAN","2425181","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1243","AHMAD SOFWAN ISMAIL","2425182","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1244","AHMAD SYAFIQ","2425183","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1245","ANGGA MAULANA","2425184","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1246","CINTIA","2425185","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1247","DENI KUSMANA","2425186","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1248","DEWI CUT NADIN","2425187","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1249","DIANA RAHAYU","2425188","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1250","DIKA BAEHAKI","2425189","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1251","EPITASARI","2425190","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1252","FARHANNABIL","2425191","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1253","HABIL NUR ALDIN","2425192","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1254","HENDI SETIAWAN","2425193","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1255","KARDA PUTRA ADIANSYAH","2425194","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1256","MARYONO","2425195","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1257","MASITOH NURUL SYIFA","2425196","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1258","MOHAMAD FAJAR ALFAZRY","2425197","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1259","MUHAMAD KHAIRUL INIESTA","2425198","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1260","MUHAMAD KHOLBY SAHIH","2425199","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1261","MUHAMAD RIDWAN SAPII","2425200","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1262","MUHAMMAD ALDIANSYAH","2425201","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1263","MUHAMMAD RIDWAN AL KHADAFI","2425202","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1264","NOVAL MALIKI","2425203","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1265","NURWULAN","2425204","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1266","OBAY SOBARNA","2425205","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1267","PATONAH","2425206","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1268","RAGIL GOJALI","2425207","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1269","RAIHAN ABDUL YAFIDZ","2425208","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1270","RAJIF JULIAWAN","2425209","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1271","RIO SUSANTO","2425210","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1272","RIZKY RAMDANI","2425211","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1273","RUSLAN","2425212","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1274","RYAN HIDAYAT","2425213","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1275","TAUPIK UROHMAN","2425214","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1276","WILLIYANA ADISTIA","2425215","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1277","YUSUF RAMADAN","2425216","$2y$10$mBwPVhnS8y0N9Z5DsHVJ9u3u8CmIdB2MV0B5LWveQon1vWBlRgY2q","siswa","1008","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1278","AHMAD GUNAWAN","2425217","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1279","AKBAR ALVIAN","2425218","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1280","AL AZIS MUBAROK","2425219","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1281","ALIF QIZWINI","2425220","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1282","ANDIH SAEPUL BAHRI","2425221","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1283","ANDIKA PRATAMA","2425222","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1284","BENI KURNIA","2425223","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1285","CARSADI","2425224","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1286","CHIKA RAHMAWATI","2425225","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1287","DEFNE MAULLANA EL SYARIF","2425226","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1288","DEVI DESVITASARI","2425227","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1289","DIKA NANDA","2425228","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1290","EKA FEBRIANA","2425229","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1291","GALANG FIRMANSYAH","2425230","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1292","HALIMATU ZAHRA","2425231","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1293","HASBI ASHIDIQI","2425232","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1294","HERDIYAN NUGRAHA","2425233","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1295","JEJE","2425234","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1296","MARINA F SIMATUPANG","2425235","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1297","MUHAMAD IHSAN MUBAROK","2425236","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1298","MUHAMAD IQBAL ALFAQIH","2425237","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1299","MUHAMAD KAMAL","2425238","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1300","MUHAMAD REHAN","2425239","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1301","MUHAMMAD WAROSATUL AMBIYA","2425240","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1302","NURFRIAN SYAHRONY","2425241","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1303","RAELAN MUTABAROQ","2425242","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1304","RAMLI","2425243","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1305","REIHAN FIRMANSYAH","2425244","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1306","RIFQIANSAH","2425245","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1307","RINTO","2425246","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1308","RISMA","2425247","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1309","ROSAM CAKRA WINATA","2425248","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1310","SIVA YULYANTI","2425249","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1311","SUHERMAN","2425250","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1312","SUNANTA","2425251","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1313","SURYANI WIDYA PUTRI","2425252","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1314","ULFIA ANGGIA PUTRI","2425253","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1315","WARDI","2425254","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1316","WARDIMAN","2425255","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1317","ZAHRAN MAHESWARI","2425256","$2y$10$lnOPNBO.1LspzltT0ns2jeiqjg9BQ3FGPbZ63uqdbUDOMSXlV/6r.","siswa","1009","2025/2026","1",NULL);
INSERT INTO `users` VALUES("1318","AMELIA","2324001","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1319","ANISA RHAMADANI","2324002","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1320","AYU NINGRAT","2324003","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1321","CINDI LESTARI","2324004","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1322","CITRA SHABILA IRAWAN","2324005","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1323","DENITA SARI","2324006","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1324","EVI NURAENDI","2324007","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1325","INTAN LESTARI","2324008","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1326","INTAN RASJI RAMADHANY","2324009","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1327","KARLINA","2324010","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1328","KURNIA","2324011","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1329","MAHENDRA","2324012","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1330","MARPUAH","2324013","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1331","MAULANA ANSORI SURYANA","2324014","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1332","MAYLANI","2324015","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1333","MUHAMAD ADHI SATRIO","2324016","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1334","NUR SILVA OCTAVIA","2324017","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1335","NURMALA PUSPITASARI","2324018","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1336","QORIATUL FITRIYAH","2324019","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1337","RAHMA PUSPITASARI","2324020","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1338","RISKA APRILIA","2324021","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1339","RISMAYANTI","2324022","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1340","SARAH INDRIYANTI","2324023","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1341","SITI JAHROTULSYITTA","2324024","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1342","SITI NURHASANAH","2324025","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1343","SITI NURPADILAH","2324026","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1344","SITI SALAMAH","2324027","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1345","SITI ZENAB","2324028","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1346","SYAVIRA DWI NOVIANTI","2324029","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1347","SYIFAUDZIHNI","2324030","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1348","TASYA ANANDA","2324031","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1349","TIKA DEWI","2324032","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1350","WILDA CAHYANI","2324033","$2y$10$Cs7e/wtwqzuZ6a8i0GWG2u/JFQwtdRt4su74PKyRnbfiS2EEKmLb2","siswa","1010","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1351","AISAH WALINAYAH","2324034","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1352","ANGGUN DARA CANTIKA","2324035","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1353","APRILIANI","2324036","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1354","ARUM","2324037","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1355","CITRA NURY RAHMAN","2324038","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1356","DEDEH FAUZIYAH","2324039","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1357","DEVI YULIANI KUSMANTO","2324040","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1358","DINDA LESTARI","2324041","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1359","ERNAWATI","2324042","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1360","INAYAH","2324043","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1361","LAILA","2324044","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1362","LUTIKA SUNDAWA","2324045","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1363","MAULIDDIVA NAWWAROH","2324046","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1364","MAYA MAESAROH","2324047","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1365","MELYANI","2324048","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1366","MIA BIATUL QOYA","2324049","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1367","MUHAMMAD RAFFA RODIYANA","2324050","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1368","NAJWA FITRIA AZZAHRA","2324051","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1369","NINING NURFALAH","2324052","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1370","NORMAN HIDAYATULLAH","2324053","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1371","NOVI SEPTIANA RAHMADANI","2324054","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1372","NURMALA","2324055","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1373","PUTRI SALSABELA","2324056","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1374","RANI","2324057","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1375","RIRIN EKAWATI","2324058","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1376","SARASWATI","2324059","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1377","SITI NURJAMILAH","2324060","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1378","WIWIN NURMAULIDA","2324061","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1379","WULAN SARI","2324062","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1380","ZAHRA AYUMI","2324063","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1381","ZAHWA AENURROHMAH","2324064","$2y$10$/guQJEdKdDAzIjDrUVuzmOwL4t65EiOWTGVZ.4aUL9YaB7j0dmhRC","siswa","1011","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1382","ABDUL SODIK","2324065","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1383","AGNAN HAMBALI","2324066","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1384","AGUS MULYANA","2324067","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1385","AKBAR DHANI","2324068","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1386","ALDI FAUZAN","2324069","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1387","ALDIEN AKBAR","2324070","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1388","ALPI SAEPUL AKBAR","2324071","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1389","ANDIKA SYAPUTRA","2324072","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1390","AYOM PRAYOGA","2324073","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1391","CAHYONO MAULANA","2324074","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1392","DIMAS PRADIPTA FUDHOLI","2324075","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1393","EKA RAMDANI JUNAEDI","2324076","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1394","EVA NURAENDI","2324077","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1395","GENTA TENGGARA BADAY","2324078","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1396","INTAN NURJANAH","2324079","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1397","IRPAN HERMAWAN","2324080","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1398","MUHAMAD YAHYA","2324081","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1399","NABILA JULIANY","2324082","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1400","RAFI MAULANA LILAH","2324083","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1401","RAMA ALDIANSYAH","2324084","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1402","RAMA HERDIANSYAH","2324085","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1403","REDI","2324086","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1404","REGIA NANDA FEBRIATAMA","2324087","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1405","RIFKI MULYANA","2324088","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1406","RIZKI MAULANA","2324089","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1407","RIZKY ADITIYA","2324090","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1408","RIZKY AZIZ MAULANA","2324091","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1409","SITI MARIYAM","2324092","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1410","SUPRIYATNA RAMADAN PRASTIYO","2324093","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1411","SYAHRILLA PERMATA NUSANTARA","2324094","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1412","WIDIYA ANISA PUTRI","2324095","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1413","WINDY DANUARTA","2324096","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1414","YUDI MULYANA","2324097","$2y$10$mJ2IgVXYTxlNLoUbojiisub5TmaULbWnQH6VPj7r7lm/VBwEpa0eS","siswa","1012","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1415","ADITTIYA SAPUTRA","2324098","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1416","AFDAL ZIQRI RAMADAN","2324099","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1417","ANDIKA EKA SAPUTRA","2324100","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1418","ANGGUN RIANA PUTRI","2324101","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1419","APRIZAL SUGANDA","2324102","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1420","ARDIANSYAH","2324103","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1421","ARIF JUNAEDI ABDILAH","2324104","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1422","ASIAH NURAZIZAH","2324105","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1423","DIMAS","2324106","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1424","DIO PRATAMA","2324107","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1425","FACHRI MUHAMAD SOFYAN","2324108","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1426","FAHRI HAMDANI","2324109","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1427","FERRY SEM JULIANTO","2324110","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1428","GUNAWAN GUNTUR","2324111","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1429","IRMAWATI","2324112","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1430","ISMAIL NURJAYA","2324113","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1431","LUTPIAH","2324114","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1432","MUHAMAD FAIS","2324115","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1433","MUHAMAD MUMIN","2324116","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1434","MUHAMAD NUSHA BADARI","2324117","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1435","MUHAMMAD PALESTIN","2324118","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1436","MUHAMMAD RAFFI ALFARIS","2324119","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1437","MUHIDIN","2324120","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1438","NURHAYATI","2324121","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1439","NURSIAH","2324122","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1440","ROHMANA","2324123","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1441","SYARIP NASRULLOH","2324124","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1442","TAMAMI","2324125","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1443","ANDRA PURNAMA","2324126","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1444","DALAL IRWANSYAH","2324127","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1445","MAESAROH","2324128","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1446","WANA MAULANA","2324129","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1447","WIRA ATMAJA","2324130","$2y$10$7C2AcerPxCcdeXMcbgob0./Xpyo1QHhWWx6Qg3W8rrJaOaUI6udWm","siswa","1013","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1448","ABDUL AZIS","2324131","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1449","ABDUL GANI","2324132","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1450","ABDUL RAHMAN WAHYUDIN","2324133","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1451","AHMAD DANU","2324134","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1452","ALMASUL ANAM","2324135","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1453","APRIANSYAH KARIM","2324136","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1454","APRIYANSYAH","2324137","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1455","ARINO ARDAN ARUBETH","2324138","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1456","ARYO SAPUTRA","2324139","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1457","BELA CANTIKA","2324140","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1458","DAVI BAHTIAR","2324141","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1459","DELIMA","2324142","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1460","EKO ADI SUCIPTO","2324143","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1461","ELI HAYATI","2324144","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1462","IBRAHIM SIDIK JAELANI","2324145","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1463","ICANA","2324146","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1464","INDRA","2324147","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1465","IRPAN","2324148","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1466","KHAILA SABINA","2324149","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1467","MUHANAD ABDULHADI","2324150","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1468","NURLELA APRIYANTI","2324151","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1469","PARIDHAH","2324152","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1470","PASA NUR PADILAH","2324153","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1471","RAISYA ADITIA","2324154","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1472","RANATA","2324155","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1473","RAPI RIPANSYAH","2324156","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1474","RIPAT","2324157","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1475","RIZIQ ABDUL WAHID","2324158","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1476","SUCIKOH MAHARANI","2324159","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1477","SUKARDI","2324160","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1478","TIANI","2324161","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1479","VICRY APRIANSYAH","2324162","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1480","WAHAB TAJUDIN","2324163","$2y$10$2xPQY8kHContdu0I79hrsOrKQEbLfXg9vgacidmIOhk.c3VS4b5uu","siswa","1014","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1481","AHMAD FANANI","2324164","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1482","AHMAD RIFKI RIFAI","2324165","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1483","ALDO PRASETYO ADAM","2324166","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1484","ALFA NURHIKMAH","2324167","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1485","ARIS MUKTIA","2324168","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1486","ARMAN RAMADHAN","2324169","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1487","DARMAWAN","2324170","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1488","DENI HARUN","2324171","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1489","DIMAS","2324172","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1490","FAISAL ABDUL AZIS","2324173","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1491","GALIH WARDANA","2324174","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1492","GILANG PERMANA","2324175","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1493","HOTIMAH","2324176","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1494","ILYAS SUPANDI","2324177","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1495","IRKI","2324178","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1496","IRWAN","2324179","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1497","KARDIANSYAH","2324180","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1498","MEYFHA AKILAH","2324181","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1499","MOHAMAD AKBAR","2324182","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1500","MUHAMAD RASYA RAMADHAN","2324183","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1501","MUHAMMAD RIDO RIDWANULAH","2324184","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1502","MUTIARA","2324185","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1503","NAJRIL ILHAM","2324186","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1504","NANANG ALDIANSYAH","2324187","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1505","NIA RAMADHANI","2324188","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1506","PAHRUDIN","2324189","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1507","SAPRUDIN","2324190","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1508","SIFAH FAUZIAH","2324191","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1509","TANIA LAURA","2324192","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1510","TEGAR ANANDA PRATAMA","2324193","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1511","WIRANATA","2324194","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1512","YUDA PRATAMA","2324195","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1513","YUSUP HABIBI","2324196","$2y$10$dzF9qIg5jxrJ5BFk1bSO7ugsr79G7FGRcxob1oCT1/7STnw1NxTwW","siswa","1015","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1514","AHMAD NURACA","2324197","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1515","ALVIAN NURFADILAH AGUSTIN","2324198","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1516","CARTIKA AYU","2324199","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1517","DIMAS","2324200","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1518","DIRLY KUSUMAH","2324201","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1519","FAISAL SETYO NUGROHO","2324202","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1520","FARID RIZKY RAMDANI","2324203","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1521","IIN DWI DIKA","2324204","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1522","ILYAS EFENDI","2324205","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1523","IRWAN","2324206","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1524","JAMAL MAULANA","2324207","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1525","LUTHFI YANSYAH","2324208","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1526","MUHAMAD ANDRE ALPHIANSYAH","2324209","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1527","MUHAMAD BOBY FRAYUDA","2324210","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1528","MUHAMAD FAISAL","2324211","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1529","MUHAMAD RAIHAN","2324212","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1530","MUHAMAD RIKI ALFATUR RIZKI","2324213","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1531","MUHAMAD SAUD","2324214","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1532","MUHAMAD TIRTA RUKMANA","2324215","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1533","MUHAMMAD FARHAN MIFTAHUL ROJAK","2324216","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1534","MUHAMMAD HASAN BASRI","2324217","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1535","NAJRIL ILHAM","2324218","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1536","NANANG KOSIM","2324219","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1537","RANGGA ARDIAN","2324220","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1538","RIPA HAMDANI","2324221","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1539","RIZKI AHMAD HAMBALI","2324222","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1540","SAEPUDIN","2324223","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1541","SAKTI GENTA AIRLANGGA","2324224","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1542","SASKIA MURNI ATI JUANDI","2324225","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1543","SITI HALIMAH SADIYAH","2324226","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1544","SODIK RIFAI","2324227","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1545","WIDIA RAHMA SARI","2324228","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1546","WILDATUL RAHMATUL UMMAH","2324229","$2y$10$hO6N82sJ97dR2KTIs02WA.IBBHYXbjpSx2B7xiBk2OFjWRNdQi4Je","siswa","1016","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1547","AGNA FATHURROHMAN","2324230","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1548","ALI AHMAD DINEZAD","2324231","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1549","ALNAZRIL ANASSYAH","2324232","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1550","ANGGA","2324233","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1551","CARSIYAH","2324234","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1552","CHICI NURSELA","2324235","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1553","DERI SAEPUDIN","2324236","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1554","DIKI PURNAMA","2324237","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1555","DODO WIJAYA","2324238","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1556","FAUZI RIDWAN","2324239","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1557","GILANG ANGGA KUSUMAH","2324240","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1558","IMARRUL UMMARA","2324241","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1559","KARTOBI","2324242","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1560","LAILA SAFITRI","2324243","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1561","MALIK IBRAHIM","2324244","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1562","MARSELA","2324245","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1563","MUHAMAD FARIS DZULFIKAR","2324246","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1564","MUHAMAD REHAN BAROKAH","2324247","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1565","MUHAMAD WAKAB","2324248","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1566","MUHAMAD WAKUB","2324249","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1567","MUHAMMAD RAIHAN FEBRIAN","2324250","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1568","RADIT RAMDANI","2324251","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1569","RAMDANI","2324252","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1570","REHAN MAULANA RIZKY","2324253","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1571","RENO APRIAN","2324254","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1572","REYSSA PRIYUNITHA IVANA","2324255","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1573","RIZKI","2324256","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1574","SOPIAN GUNAWAN","2324257","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1575","SRI ANGGRAENI PEBRIANTI","2324258","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1576","TASYA","2324259","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1577","TAUFIQ HIDAYATULAH","2324260","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1578","TRISTANTO","2324261","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1579","WHILDAN SYAHRIZAL WAHYUDI","2324262","$2y$10$bCY/rouy2ADZ8v94ssKL0O1pfLgjNJFghF/yHqAedg2Ac/4FUiNMm","siswa","1017","2024/2025","1",NULL);
INSERT INTO `users` VALUES("1580","AISAH","2223001","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1581","AISAH FITRIYAH","2223002","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1582","ALDI REPALDI","2223003","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1583","ANISA TRI WAHYUNI","2223004","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1584","AYU ARISKA","2223005","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1585","DEA AULIYA","2223006","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1586","Dedeh","2223007","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1587","Entin Julyani","2223008","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1588","HALISA HUMAIRA","2223009","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1589","HANIPAH PAUJIAH","2223010","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1590","HASBY MAHEZA","2223011","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1591","INTAN AOLIA","2223012","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1592","INTAN NURAINI","2223013","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1593","IRMA SULISTIA","2223014","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1594","JAHRATUSSYITA","2223015","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1595","JIHAN","2223016","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1596","KURNIA FEBRIANTI","2223017","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1597","LALA PADILAH","2223018","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1598","NOVITASARI","2223019","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1599","NURLELAH","2223020","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1600","NURUL FARHAH","2223021","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1601","RAHMA AULIA AGUSTIN","2223022","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1602","RIYAN","2223023","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1603","SINDI ARISKA","2223024","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1604","SITI NURAENI","2223025","$2y$10$EfEcw/pjCD8oWqRMvXR0JOM.iLBcZ4KW/UxZwOItt0vFAB4xP4I8e","siswa","1018","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1605","ALVIANI","2223026","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1606","DECA LESMANASARI","2223027","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1607","GINA HERAWATI","2223028","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1608","INDI LESTARI","2223029","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1609","JENY SAFIRA","2223030","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1610","KHILDA SYAFA`AH","2223031","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1611","LAELA","2223032","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1612","NAILA ADZKYA MAULA","2223033","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1613","NAJWA","2223034","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1614","NARSIH","2223035","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1615","NIKEN RIZKIA NINTIAS","2223036","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1616","PUTRI JULIANTI LESTARI","2223037","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1617","RAHMAWATI","2223038","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1618","RATNA DEWI WULAN SARI","2223039","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1619","REFFA AMELIA","2223040","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1620","SAWI","2223041","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1621","SEPTIANI","2223042","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1622","SITI HILDA MAESAROH","2223043","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1623","SITI SOVIYAH","2223044","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1624","SITRA ADILA","2223045","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1625","SRI NURHAYATI","2223046","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1626","WARI","2223047","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1627","WULAN NURUL ARIFAH","2223048","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1628","YUSTIKA DEWI","2223049","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1629","ZAHRA NURI SURYA","2223050","$2y$10$hxqofNxVB9ueVd7SS35zm.5R9zGF4lU1DLM0Pjaibr.loNeJKMIkG","siswa","1019","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1630","ADAM PRIAMUGA","2223051","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1631","ADARSONO","2223052","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1632","AFAHRI SATYA ABDUL","2223053","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1633","AHMAD RIZKI ADITIA","2223054","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1634","AHMAD RIZKY AL MAHMOD","2223055","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1635","AJI PANGESTU","2223056","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1636","AKMAL KUSRORI","2223057","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1637","ANGGA ADITIA","2223058","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1638","ANNISA TUL AULIA","2223059","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1639","AYAN SUPRIATNA","2223060","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1640","CACA AULIANA","2223061","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1641","DIKI SOMANTRI","2223062","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1642","DIRGA RAGIL PUTRA RUKMANA","2223063","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1643","EVA ALEXA","2223064","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1644","FAIRUZ FIKHAR FUADI","2223065","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1645","FITRIYANI","2223066","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1646","HIDAYAT AHMAD SIDIK","2223067","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1647","IRWAN ISKANDAR","2223068","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1648","KHOERUL APANDI","2223069","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1649","KUSNATA","2223070","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1650","MUHAMAD EGA OKTAVIAN","2223071","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1651","MUHAMAD YUNUS","2223072","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1652","MUHAMMAD RAMDANI","2223073","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1653","MUHAMMAD RAYHAN RAMDHAN","2223074","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1654","RANIA NURDIAN","2223075","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1655","ROMLAH","2223076","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1656","SAAD APRIANSYAH","2223077","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1657","SANTANA WIDI BAHARI","2223078","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1658","SANTI","2223079","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1659","SITI PATIMAH","2223080","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1660","TAHRI RAHMADANI","2223081","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1661","TANGGUH AZIZI","2223082","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1662","TARVIN","2223083","$2y$10$/VblLMdMYr4VLdiUJUjvYO0QGRJD0oagWw85YygEN4TzVBwWfkQse","siswa","1020","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1663","AGUSTIAN ABDUR ROFIQ","2223084","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1664","AHMAD REKSA","2223085","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1665","AKBAR RAMADANI","2223086","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1666","ANDREYAN SURYANA SAPUTRA","2223087","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1667","ARIF PERMANA SIDIK","2223088","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1668","AYU ARYANAH","2223089","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1669","BUSYAERI MAJID","2223090","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1670","CARTIWAN","2223091","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1671","DAHLIA SRI RAHAYU","2223092","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1672","DERLY PRASETYO","2223093","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1673","DETIYA KUSMAYADI","2223094","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1674","DIMAS ROHMAN SAPUTRA","2223095","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1675","FAJAR FADILLAH","2223096","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1676","FIKRI AHMAD MUBAROK","2223097","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1677","GUNAWAN","2223098","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1678","HADI HOERUDIN","2223099","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1679","IBNU QODA","2223100","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1680","IHYA ULUMUDIN","2223101","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1681","INTAN DAHLIA","2223102","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1682","INTAN NURAENI","2223103","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1683","LISNAWATI","2223104","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1684","MAMAN ABDUL RAHMAN","2223105","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1685","MEILANI PUSPITA SARI","2223106","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1686","MUHAMAD AL NAZIB","2223107","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1687","NADIA ASMIRANDAH","2223108","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1688","NATA ADITIYA","2223109","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1689","NOFITA DEWAN TARI","2223110","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1690","NOPAL BUKHORI","2223111","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1691","NOVIANTI","2223112","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1692","RIDWAN FADILAH","2223113","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1693","SISKA AULIA","2223114","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1694","SUHENDAR","2223115","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1695","TOMI","2223116","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1696","WARTA","2223117","$2y$10$ibU49sjcigcZr6nkBwM/M.bJ94vXqtr6kgozVlApT3FQVis8pkwKW","siswa","1021","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1697","ADAM MARIANSYAH","2223118","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1698","AJIJAH LESTARI","2223119","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1699","ANDRIAN","2223120","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1700","DENDI PRASETYO","2223121","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1701","DIANA PUTRI AWALIAYAH","2223122","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1702","DIFA HARIKURNIA","2223123","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1703","ENAH MAEMUNAH","2223124","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1704","FAIQ AHMAD NAUFAL","2223125","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1705","FERI SOPANDI","2223126","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1706","IKBAL MAULANA IBROHIM","2223127","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1707","KASMINAH","2223128","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1708","KATIMAH","2223129","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1709","LIGAR","2223130","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1710","MAYA","2223131","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1711","MOH THOLIB RIVKI","2223132","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1712","MOHAMAD FEBRY AULIA","2223133","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1713","MOHAMAD RIDWAN","2223134","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1714","MUHAMAD HAIDAR RIFAI","2223135","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1715","MUHAMMAD WAHYUDI","2223136","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1716","NADILA LIESHARTANTI","2223137","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1717","NARMAN","2223138","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1718","NOVA YANTO","2223139","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1719","OGI WIRAGUNA NAMRAIH","2223140","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1720","PEPEN FAISAL AGUSTIAN","2223141","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1721","RAHIL TRISNA","2223142","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1722","RAMDANI","2223143","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1723","RANGGA MUHAMAD FARHAN","2223144","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1724","RATU AYU SARI","2223145","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1725","RENDI SUJAYA","2223146","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1726","RENO AL FAUZI","2223147","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1727","RIDWAN IBNU AL FARIJI","2223148","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1728","RIFA AHMAD SOFYAN","2223149","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1729","SULTAN PERMANA PUTRA","2223150","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1730","SUPARDI","2223151","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1731","WIDIA","2223152","$2y$10$201s5RBZk7KnT3yAj2EKm.e.nYPhgL9foKzOIA3q4XSk6QTtmU6O2","siswa","1024","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1732","ABDUL ROSID","2223153","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1733","AGUS","2223154","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1734","AHMAD BAGIR","2223155","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1735","AKBAR BAIDILLAH SATORIK","2223156","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1736","AMIRAH YULYANTI","2223157","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1737","ANGGUN LESTARI","2223158","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1738","DAHVA DERIANSAH","2223159","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1739","DANA MAULANA","2223160","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1740","DEA NOVITA","2223161","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1741","ELVIRA SELOMITHA ALENA","2223162","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1742","FACHRY HABIBURROHMAN","2223163","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1743","FEBRIAN","2223164","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1744","JANE DIKE PUTRI ARIAWAN","2223165","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1745","KOMALASARI","2223166","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1746","LUVITA RAHMA SARI","2223167","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1747","MAHMUD MUNANDAR","2223168","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1748","MARISA LINDA LESTARI","2223169","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1749","MILA SARI","2223170","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1750","MUHAMAD RAIHAN GUMILAR","2223171","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1751","MUHAMAD YUGA","2223172","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1752","RAFI ALHABSY","2223173","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1753","RAHMAN","2223174","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1754","RAZAN MUHAMMAD IHSAN","2223175","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1755","RIAN ADRIANSYAH","2223176","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1756","RIAN FIKRIANSAH","2223177","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1757","RIF AN RIZIQ","2223178","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1758","ROHMAT HIDAYAT","2223179","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1759","RUDITA PRATAMA","2223180","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1760","SOPAN SOPIAN","2223181","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1761","SRI WULANDARI","2223182","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1762","TONI JAYA SAPUTRA","2223183","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1763","USUP","2223184","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1764","WAHYU REKSA DIFA","2223185","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1765","YAYAN HERMANTO","2223186","$2y$10$T141eGlC5YrYEeIvxKRoH.tZ/mf4nADPZOAFVbE8zBBkJA2ZduwYW","siswa","1025","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1766","AHMAD FAUZAN","2223187","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1767","AKBAR NANDA RUKMANA","2223188","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1768","ANGELLYA","2223189","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1769","APID","2223190","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1770","DIAN MUHLISOTUL ULFIAH","2223191","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1771","DIAN PERMATASARI","2223192","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1772","EKA PURWANA","2223193","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1773","FAISHAL FAKHRI","2223194","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1774","HADI RAHMAN","2223195","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1775","INTAN","2223196","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1776","IRSAN TICKNO","2223197","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1777","JAJANG KOSWARA","2223198","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1778","JIHAN HOLIYANA","2223199","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1779","MARYADI","2223200","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1780","MUHAMAD ALIN","2223201","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1781","MUHAMAD RONI ADITIA","2223202","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1782","MUHAMMAD REIVANDI","2223203","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1783","MUKHAMAD MARUF","2223204","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1784","NURI AGUSTINA","2223205","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1785","RAHMAT HIDAYAT","2223206","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1786","RAMDANI","2223207","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1787","REYJA","2223208","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1788","RIYAN ARYANTO","2223209","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1789","RIZZKI AKBAR ALFATIRI","2223210","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1790","ROPI LORO SOPANJI","2223211","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1791","SUYUTI AHMAD BUSAERI","2223212","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1792","TITI NURJANAH","2223213","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1793","WIRANATA","2223214","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1794","YAZID RAHMAT HIDAYAT","2223215","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1795","YUDIS TIRA ROMANSAH","2223216","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1796","YULI DEWIYANTI","2223217","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1797","YULITA","2223218","$2y$10$aef9AmLtH6pRRPhEn9Aicurv1V48mTN.joifujhdDoX/dPsG1FLxW","siswa","1022","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1798","ABDUL RONI","2223219","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1799","ABDUL SIDIK","2223220","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1800","AEL GHAZALI","2223221","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1801","AHMAD FAHRI YUNMAR","2223222","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1802","AHMAD ZAKKY JAELANI","2223223","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1803","AHMAL","2223224","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1804","AKBAR SUGIHARTONO","2223225","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1805","AMELDA FIKRIA","2223226","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1806","ARHAM SODIK","2223227","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1807","ATISAH","2223228","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1808","CAHYA KOMARA","2223229","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1809","DEDEN","2223230","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1810","FITRIA ANGGRAENI","2223231","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1811","FITRIA RAMADANI","2223232","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1812","GITA MELINDA SARI","2223233","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1813","IBNU HASAN","2223234","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1814","JAJANG SUTEJA","2223235","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1815","KARNOM","2223236","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1816","KARYA MULYANA","2223237","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1817","KHAIRUL IKHWAN ABBASY","2223238","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1818","MAULIDDANI AHMAD NURHANDIKA","2223239","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1819","MELI IMELDA","2223240","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1820","MUHAMAD ROSANDI","2223241","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1821","MUHAMMAD RAFA FAUZAN","2223242","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1822","MUHAMMAD RAIHAN","2223243","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1823","NOVIANI","2223244","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1824","PIRMANSAH","2223245","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1825","RADO SAPERO","2223246","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1826","SAEPUL PIRDAUS","2223247","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1827","SUHENDAR","2223248","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1828","WIDHI BAGASKORO","2223249","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1829","YULI","2223250","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1830","ZEN ANFASA HIDAYAT","2223251","$2y$10$eMPX.de62NH/xf3dnrAZpuTjCpykIxI6QJTjlkXQt9KEsOGei1vam","siswa","1023","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1831","ABDUL ROHMAN","2223252","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1832","ASEP SUJANA","2223253","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1833","DEDE RAFII","2223254","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1834","DEDI WAHIDIN","2223255","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1835","DETI DIAN TINI","2223256","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1836","ERNA WIDIAWATI S","2223257","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1837","ERNI WIDIAWATI S","2223258","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1838","HUSEN SOBARNA","2223259","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1839","IBNI MUHAJIR","2223260","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1840","IIP IVANKA","2223261","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1841","IRFAN HIDAYAT","2223262","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1842","KARDIANTO","2223263","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1843","KISAR","2223264","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1844","KORIYAH","2223265","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1845","LINDA","2223266","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1846","MAYA ANJELINA FRATAMA","2223267","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1847","MUHAMMAD ABDUL FAQIH","2223268","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1848","MUHAMMAD AHDA AL MUSYAROF","2223269","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1849","MUHAMMAD FADLAN","2223270","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1850","MUTIARA AZIZAH","2223271","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1851","NURAENI","2223272","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1852","RAKA","2223273","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1853","RIKI","2223274","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1854","SAEPUL ANWAR","2223275","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1855","WARMAN","2223276","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);
INSERT INTO `users` VALUES("1856","YASMIN","2223277","$2y$10$5T01kohtXeVNj9MZYuvPV.T.xyOjr2P6CK7p76XaUfhlO6bbmTAdy","siswa","1026","2023/2024","1",NULL);

SET FOREIGN_KEY_CHECKS = 1;
