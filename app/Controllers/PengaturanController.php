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

    public function backup() {
        $tables = array();
        $result = $this->db->query("SHOW TABLES");
        while ($row = $result->fetch(PDO::FETCH_NUM)) { $tables[] = $row[0]; }
        $return = "-- Backup BST SYSTEM \n-- Date: " . date('Y-m-d H:i:s') . "\n\n";
        foreach ($tables as $table) {
            $result = $this->db->query("SELECT * FROM $table");
            $num_fields = $result->columnCount();
            $return .= "DROP TABLE IF EXISTS $table;";
            $row2 = $this->db->query("SHOW CREATE TABLE $table")->fetch(PDO::FETCH_NUM);
            $return .= "\n\n" . $row2[1] . ";\n\n";
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $return .= "INSERT INTO $table VALUES(";
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $return .= isset($row[$j]) ? '"'.$row[$j].'"' : '""';
                    if ($j < ($num_fields - 1)) $return .= ',';
                }
                $return .= ");\n";
            }
            $return .= "\n\n\n";
        }
        header('Content-Type: application/octet-stream');
        header("Content-disposition: attachment; filename=\"backup-bst-".date('Y-m-d').".sql\"");
        echo $return; exit;
    }

    public function restore() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['sql_file'])) {
            $sql = file_get_contents($_FILES['sql_file']['tmp_name']);
            try { 
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 0;");
                $this->db->exec($sql); 
                $this->db->exec("SET FOREIGN_KEY_CHECKS = 1;");
                $_SESSION['success'] = "Sistem Berhasil Dipulihkan!"; 
            } catch (Exception $e) { 
                $_SESSION['error'] = "Gagal Restore Database: " . $e->getMessage(); 
            }
        }
        header('Location: ' . BASE_URL . '/pengaturan/maintenance');
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