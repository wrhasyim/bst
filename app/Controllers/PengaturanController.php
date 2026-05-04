<?php
// app/Controllers/PengaturanController.php
require_once __DIR__ . '/../Core/Database.php';

class PengaturanController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $stmt = $this->db->query("SELECT kunci, nilai FROM pengaturan");
        $data = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $title = "Pengaturan Sistem";
        $content = __DIR__ . '/../../views/admin/pengaturan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function maintenance() {
        $title = "Pemeliharaan Data";
        $content = __DIR__ . '/../../views/admin/pengaturan/maintenance.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update_identitas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $total = $_POST['persen_kas_bst'] + $_POST['persen_kas_sekolah'] + 
                     $_POST['persen_honor_pengelola'] + $_POST['persen_honor_walikelas'];

            if ($total != 100) {
                $_SESSION['error'] = "Gagal! Total alokasi honor harus tepat 100% (Input Anda: $total%)";
                header('Location: ' . BASE_URL . '/pengaturan');
                exit;
            }

            foreach ($_POST as $kunci => $nilai) {
                $stmt = $this->db->prepare("UPDATE pengaturan SET nilai = :v WHERE kunci = :k");
                $stmt->execute(['v' => $nilai, 'k' => $kunci]);
            }
            $_SESSION['success'] = "Pengaturan & Kebijakan Honor berhasil diperbarui!";
        }
        header('Location: ' . BASE_URL . '/pengaturan');
    }

    // =================================================================
    // 3. FITUR BACKUP DATABASE (PHP NATIVE PURE SQL DUMP)
    // =================================================================
    public function backup() {
        if ($_SESSION['role'] !== 'admin') { header('Location: ' . BASE_URL . '/dashboard'); exit; }

        $tables = [];
        $query = $this->db->query("SHOW TABLES");
        while ($row = $query->fetch(PDO::FETCH_NUM)) { $tables[] = $row[0]; }

        $sqlScript = "-- BST SYSTEM DATABASE BACKUP\n";
        $sqlScript .= "-- Tanggal: " . date('Y-m-d H:i:s') . "\n\n";
        $sqlScript .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

        foreach ($tables as $table) {
            $query = $this->db->query("SHOW CREATE TABLE `$table`");
            $row = $query->fetch(PDO::FETCH_NUM);
            $sqlScript .= "\n\nDROP TABLE IF EXISTS `$table`;\n";
            $sqlScript .= $row[1] . ";\n\n";

            $query = $this->db->query("SELECT * FROM `$table`");
            $columnCount = $query->columnCount();

            while ($row = $query->fetch(PDO::FETCH_NUM)) {
                $sqlScript .= "INSERT INTO `$table` VALUES(";
                for ($j = 0; $j < $columnCount; $j++) {
                    if (isset($row[$j])) {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = str_replace("\n", "\\n", $row[$j]);
                        $sqlScript .= '"' . $row[$j] . '"';
                    } else {
                        $sqlScript .= 'NULL';
                    }
                    if ($j < ($columnCount - 1)) { $sqlScript .= ','; }
                }
                $sqlScript .= ");\n";
            }
        }
        $sqlScript .= "\nSET FOREIGN_KEY_CHECKS = 1;\n";

        $backup_file_name = 'Backup_BST_System_' . date('Y-m-d_H-i-s') . '.sql';
        header('Content-Type: application/x-sql');
        header('Content-Disposition: attachment; filename=' . $backup_file_name);
        echo $sqlScript;
        exit;
    }

    // =================================================================
    // 4. FITUR RESTORE DATABASE (IMPORT DARI FILE SQL)
    // =================================================================
    public function restore() {
        if ($_SESSION['role'] !== 'admin') { header('Location: ' . BASE_URL . '/dashboard'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['backup_file'])) {
            $file = $_FILES['backup_file'];
            if ($file['error'] == UPLOAD_ERR_OK && is_uploaded_file($file['tmp_name'])) {
                $sql = file_get_contents($file['tmp_name']);
                try {
                    // Eksekusi seluruh script SQL dari file
                    $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
                    $this->db->exec($sql);
                    $_SESSION['success'] = "Database berhasil di-restore dengan sempurna! Sistem kembali ke titik backup.";
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Gagal restore database: Format SQL tidak valid. " . $e->getMessage();
                }
            } else {
                $_SESSION['error'] = "Gagal mengunggah file backup.";
            }
        }
        header('Location: ' . BASE_URL . '/pengaturan/maintenance');
        exit;
    }

    // =================================================================
    // 1. RESET DATA TRANSAKSI SAJA
    // =================================================================
    public function reset_transaksi() {
        if ($_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        try {
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0;");
            
            $this->db->exec("TRUNCATE TABLE setoran;");
            $this->db->exec("TRUNCATE TABLE penarikan;");
            $this->db->exec("TRUNCATE TABLE penjualan;");
            $this->db->exec("TRUNCATE TABLE pencairan_honor;");
            $this->db->exec("TRUNCATE TABLE kas_manual;");
            
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1;");

            $_SESSION['success'] = "Berhasil! Seluruh riwayat transaksi telah dihapus. Data Master Pengguna & Sampah tetap aman.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Gagal mereset transaksi: " . $e->getMessage();
        }

        // FIX TYPO ROUTING: Kembali ke 'maintenance' yang benar
        header('Location: ' . BASE_URL . '/pengaturan/maintenance'); 
        exit;
    }

    // =================================================================
    // 2. RESET TOTAL SISTEM (SAFEGUARD LENGKAP)
    // =================================================================
    public function reset_total() {
        if ($_SESSION['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }

        try {
            $this->db->exec("SET FOREIGN_KEY_CHECKS = 0;");
            
            // 1. Kosongkan Transaksi
            $this->db->exec("TRUNCATE TABLE setoran;");
            $this->db->exec("TRUNCATE TABLE penarikan;");
            $this->db->exec("TRUNCATE TABLE penjualan;");
            $this->db->exec("TRUNCATE TABLE pencairan_honor;");
            $this->db->exec("TRUNCATE TABLE kas_manual;");

            // 2. Bersihkan Kategori (Lindungi Reward Prestasi)
            $this->db->exec("DELETE FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI';");

            // 3. Bersihkan User (Lindungi Admin & Akun Kesiswaan)
            $this->db->exec("DELETE FROM users WHERE role != 'admin' AND nama NOT LIKE '%KESISWAAN%';");

            // 4. Bersihkan Kelas (Lindungi Kelas Kesiswaan & Kelas yang masih dipakai)
            $this->db->exec("DELETE FROM kelas WHERE id NOT IN (SELECT IFNULL(kelas_id, 0) FROM users) AND nama_kelas NOT LIKE '%KESISWAAN%';");

            $this->db->exec("SET FOREIGN_KEY_CHECKS = 1;");

            $_SESSION['success'] = "Sistem berhasil di-reset total! Admin, Kas Kesiswaan, dan Kategori Reward dipastikan aman.";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Gagal melakukan reset total: " . $e->getMessage();
        }

        // FIX TYPO ROUTING: Kembali ke 'maintenance' yang benar
        header('Location: ' . BASE_URL . '/pengaturan/maintenance'); 
        exit;
    }
}