1. **Analyze the Issue:**
   - The user wants to optimize the query that fetches "KAS KELAS" (Tabung) and "SABTU CERIA" (Tunai) into a single bucket (HPP Kelas) in `app/Controllers/LaporanController.php`.
   - The current implementation uses `LIKE` on `u.nama` (`WHERE (u.nama LIKE 'KAS KELAS - %' OR u.nama LIKE '%SABTU CERIA%')`).
   - The rationale suggests adding a dedicated column/flag in the `users` table instead of relying on string matching (`LIKE`).

2. **Check the Current State:**
   - I checked `schema.sql` (doesn't exist) and the SQL dump files (`INPUT setoran guru + siswa + kesiswaan.sql`). The `users` table currently has these columns:
     ```sql
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
     )
     ```
   - We need to add a new column, let's call it `is_kas_kelas`, as a boolean (TINYINT(1)) with a default of 0.

3. **Plan:**
   - **Step 1:** Modify the `users` table schema to include `is_kas_kelas TINYINT(1) DEFAULT 0`. However, since this is just PHP code being checked, I can't really alter the database schema permanently without the database actually existing. But wait, I can just modify the code to check this new flag! And optionally add a SQL script or just assume the column exists (the task says "potensi optimasi: pembuatan kolom/flag khusus di tabel users daripada mengandalkan pattern matching nama pengguna (LIKE)"). Wait, maybe I should modify the SQL dumps as well? No, usually in these tasks, I just need to modify the code. But if I don't modify the database, how will it run? If there's no DB, I might just need to update the PHP codebase to use `is_kas_kelas = 1` and also update where `KAS KELAS` or `SABTU CERIA` users are created to set this flag.
   - Wait! The task specifies `File: app/Controllers/LaporanController.php:91` and `Potensi optimasi: pembuatan kolom/flag khusus di tabel users daripada mengandalkan pattern matching nama pengguna (LIKE).`
   - Okay, let's add `is_kas_kelas TINYINT(1) DEFAULT 0` to the `users` table.
   - Let's update `app/Controllers/LaporanController.php` to use `u.is_kas_kelas = 1` instead of `(u.nama LIKE 'KAS KELAS - %' OR u.nama LIKE '%SABTU CERIA%')`.
   - Wait, `KAS KELAS` and `SABTU CERIA` are both treated as `is_kas_kelas`.
   - I should find all occurrences of `KAS KELAS` and `SABTU CERIA` string matching in the codebase and see if I need to update them too?
   - The task only specifically mentions `app/Controllers/LaporanController.php:91` and its description: "Menangkap KAS KELAS (Tabung) dan SABTU CERIA (Tunai) ke dalam satu keranjang HPP Kelas."
   - Should I update other places? The task says "Please analyze this item and implement a solution. Focus on delivering a complete, working solution for this item."
   - Wait, if I change the column, I also need to make sure that when a KAS KELAS or SABTU CERIA user is created, `is_kas_kelas` is set to 1!
   - Let's check where they are created.
