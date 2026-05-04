<?php
// app/Controllers/PengaturanController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Logger.php';    // 🛡️ Load Audit Trail
require_once __DIR__ . '/../Core/Security.php';  // 🛡️ Load CSRF Protection

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
            Security::validate_csrf(); // 🛡️ Cegah serangan manipulasi form

            $total = $_POST['persen_kas_bst'] + $_POST['persen_kas_sekolah'] + 
                     $_POST['persen_honor_pengelola'] + $_POST['persen_honor_walikelas'];

            if ($total != 100) {
                $_SESSION['error'] = "Gagal! Total alokasi honor harus tepat 100% (Input Anda: $total%)";
                header('Location: ' . BASE_URL . '/pengaturan');
                exit;
            }

            foreach ($_POST as $kunci => $nilai) {
                if ($kunci !== 'csrf_token') { // Abaikan token CSRF saat update ke DB
                    $stmt = $this->db->prepare("UPDATE pengaturan SET nilai = :v WHERE kunci = :k");
                    $stmt->execute(['v' => $nilai, 'k' => $kunci]);
                }
            }
            
            Logger::log("Update Pengaturan", "Admin mengubah persentase bagi hasil / identitas sistem.");
            $_SESSION['success'] = "Pengaturan & Kebijakan Honor berhasil diperbarui!";
        }
        header('Location: ' . BASE_URL . '/pengaturan');
        exit;
    }

    // =================================================================
    // 3. FITUR BACKUP DATABASE
    // =================================================================
    public function backup() {
        if ($_SESSION['role'] !== 'admin') { header('Location: ' . BASE_URL . '/dashboard'); exit; }

        Logger::log("Backup Database", "Admin mengunduh file backup SQL sistem.");

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
    // 4. FITUR RESTORE DATABASE
    // =================================================================
    public function restore() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['backup_file'])) {
            Security::validate_csrf(); // 🛡️ Cegah Hacker menunggah DB palsu

            $file = $_FILES['backup_file'];
            if ($file['error'] == UPLOAD_ERR_OK && is_uploaded_file($file['tmp_name'])) {
                $sql = file_get_contents($file['tmp_name']);
                try {
                    $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, 0);
                    $this->db->exec($sql);
                    
                    Logger::log("Restore Database", "Admin memulihkan database dari file SQL.");
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
        // 🛡️ WAJIB POST untuk fungsi destruktif
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); 

            try {
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 0;");
                
                $this->db->exec("TRUNCATE TABLE setoran;");
                $this->db->exec("TRUNCATE TABLE penarikan;");
                $this->db->exec("TRUNCATE TABLE penjualan;");
                $this->db->exec("TRUNCATE TABLE pencairan_honor;");
                $this->db->exec("TRUNCATE TABLE kas_manual;");
                
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 1;");

                Logger::log("Reset Transaksi", "DANGER: Admin mengosongkan seluruh riwayat transaksi!");
                $_SESSION['success'] = "Berhasil! Seluruh riwayat transaksi telah dihapus. Data Master Pengguna & Sampah tetap aman.";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal mereset transaksi: " . $e->getMessage();
            }
        }
        header('Location: ' . BASE_URL . '/pengaturan/maintenance'); 
        exit;
    }

    // =================================================================
    // 2. RESET TOTAL SISTEM
    // =================================================================
    public function reset_total() {
        // 🛡️ WAJIB POST untuk fungsi destruktif
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf();

            try {
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 0;");
                
                $this->db->exec("TRUNCATE TABLE setoran;");
                $this->db->exec("TRUNCATE TABLE penarikan;");
                $this->db->exec("TRUNCATE TABLE penjualan;");
                $this->db->exec("TRUNCATE TABLE pencairan_honor;");
                $this->db->exec("TRUNCATE TABLE kas_manual;");

                $this->db->exec("DELETE FROM kategori_sampah WHERE nama_sampah != '🌟 REWARD PRESTASI';");
                $this->db->exec("DELETE FROM users WHERE role != 'admin' AND nama NOT LIKE '%KESISWAAN%';");
                $this->db->exec("DELETE FROM kelas WHERE id NOT IN (SELECT IFNULL(kelas_id, 0) FROM users) AND nama_kelas NOT LIKE '%KESISWAAN%';");

                $this->db->exec("SET FOREIGN_KEY_CHECKS = 1;");

                Logger::log("Reset Total Sistem", "CRITICAL DANGER: Admin melakukan Reset Total Sistem (Wipe Out)!");
                $_SESSION['success'] = "Sistem berhasil di-reset total! Admin, Kas Kesiswaan, dan Kategori Reward dipastikan aman.";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal melakukan reset total: " . $e->getMessage();
            }
        }
        header('Location: ' . BASE_URL . '/pengaturan/maintenance'); 
        exit;
    }

    public function logs() {
        if ($_SESSION['role'] !== 'admin') { header('Location: ' . BASE_URL . '/dashboard'); exit; }

        $sql = "SELECT l.*, u.nama 
                FROM activity_logs l 
                JOIN users u ON l.user_id = u.id 
                ORDER BY l.created_at DESC LIMIT 100";
        $logs = $this->db->query($sql)->fetchAll();

        $title = "Audit Trail / Log Aktivitas";
        $content = __DIR__ . '/../../views/admin/pengaturan/logs.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }
}