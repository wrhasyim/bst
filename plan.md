1. **Create Database Migration Script**
   - Create a `database_migration.sql` file in the root directory.
   - Wait, since there is no automated testing suite (from memory: "The project is a vanilla PHP application... and currently lacks an automated test suite."), I will need to verify by running tests or testing script.

2. **Verify File Creation**
   - Use `read_file` to confirm `database_migration.sql` exists and has the correct contents.

3. **Update `app/Controllers/LaporanController.php`**
   - Change the `$sqlKasKelas` query to use the new column:
     ```php
     <<<<<<< SEARCH
             // 🌟 FIX POIN 11 (REVISI): Menangkap "KAS KELAS" (Tabung) dan "SABTU CERIA" (Tunai) ke dalam satu keranjang HPP Kelas
             $sqlKasKelas = "SELECT SUM(s.total_harga)
                             FROM setoran s
                             JOIN users u ON s.user_id = u.id
                             WHERE (u.nama LIKE 'KAS KELAS - %' OR u.nama LIKE '%SABTU CERIA%') AND s.is_sold = 1 AND s.status = 'valid'";
     =======
             // 🌟 FIX POIN 11 (REVISI): Menangkap "KAS KELAS" (Tabung) dan "SABTU CERIA" (Tunai) ke dalam satu keranjang HPP Kelas
             $sqlKasKelas = "SELECT SUM(s.total_harga)
                             FROM setoran s
                             JOIN users u ON s.user_id = u.id
                             WHERE u.is_kas_kelas = 1 AND s.is_sold = 1 AND s.status = 'valid'";
     >>>>>>> REPLACE
     ```

4. **Update `app/Controllers/SetoranController.php`**
   - Change the SQL to populate `is_kas_kelas = 1` when dynamically creating KAS KELAS users:
     ```php
     <<<<<<< SEARCH
                                     // FIX: Menghapus created_at dari query insert users
                                     $sqlBuat = "INSERT INTO users (nama, username, password, role, kelas_id, is_active) VALUES (?, ?, ?, 'siswa', ?, 1)";
                                     $this->db->prepare($sqlBuat)->execute([$nama_akun_virtual, $username_virtual, $pass_hash, $kelas_id]);
     =======
                                     // FIX: Menghapus created_at dari query insert users
                                     $sqlBuat = "INSERT INTO users (nama, username, password, role, kelas_id, is_active, is_kas_kelas) VALUES (?, ?, ?, 'siswa', ?, 1, 1)";
                                     $this->db->prepare($sqlBuat)->execute([$nama_akun_virtual, $username_virtual, $pass_hash, $kelas_id]);
     >>>>>>> REPLACE
     ```

5. **Update `app/Controllers/UserController.php`**
   - Update the INSERT query to properly check if the user is a KAS KELAS / SABTU CERIA and set the flag:
     ```php
     <<<<<<< SEARCH
                 $sql = "INSERT INTO users (username, password, nama, role, kelas_id, angkatan, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
                 $stmt = $this->db->prepare($sql);
                 $stmt->execute([$username, $password, $nama, $role, $kelas_id, $angkatan, $is_active]);
     =======
                 $is_kas_kelas = (strpos($nama, 'KAS KELAS - ') === 0 || strpos($nama, 'SABTU CERIA') !== false) ? 1 : 0;
                 $sql = "INSERT INTO users (username, password, nama, role, kelas_id, angkatan, is_active, is_kas_kelas) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                 $stmt = $this->db->prepare($sql);
                 $stmt->execute([$username, $password, $nama, $role, $kelas_id, $angkatan, $is_active, $is_kas_kelas]);
     >>>>>>> REPLACE
     ```
     Also update the `proses_import` in `UserController.php`:
     ```php
     <<<<<<< SEARCH
                             $sql = "INSERT INTO users (username, password, nama, role, kelas_id, angkatan, is_active) VALUES (?, ?, ?, 'siswa', ?, ?, 1)";
                             $stmt = $this->db->prepare($sql);
                             if ($stmt->execute([$username, $password_default, $nama, $kelas_id, $angkatan])) {
     =======
                             $is_kas_kelas = (strpos($nama, 'KAS KELAS - ') === 0 || strpos($nama, 'SABTU CERIA') !== false) ? 1 : 0;
                             $sql = "INSERT INTO users (username, password, nama, role, kelas_id, angkatan, is_active, is_kas_kelas) VALUES (?, ?, ?, 'siswa', ?, ?, 1, ?)";
                             $stmt = $this->db->prepare($sql);
                             if ($stmt->execute([$username, $password_default, $nama, $kelas_id, $angkatan, $is_kas_kelas])) {
     >>>>>>> REPLACE
     ```

6. **Verify Syntax of Modified PHP files**
   - Run `php test_script.php` or `php -l` on modified files.

7. **Run Tests**
   - Create a test script `test_query.php` that mocks the `Database` and sets up `LaporanController`, verifying that the `$sqlKasKelas` query uses `u.is_kas_kelas = 1` and not the `LIKE` statement. Since it's a legacy vanilla PHP app, we'll write a simple reflection-based test.

8. **Pre Commit Steps**
   - Complete pre-commit steps to ensure proper testing, verification, review, and reflection are done.

9. **Submit Changes**
   - Commit the changes and submit the branch.
