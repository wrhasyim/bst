ALTER TABLE `users` ADD COLUMN `is_kas_kelas` TINYINT(1) NOT NULL DEFAULT 0;
UPDATE `users` SET `is_kas_kelas` = 1 WHERE `nama` LIKE 'KAS KELAS - %' OR `nama` LIKE '%SABTU CERIA%';