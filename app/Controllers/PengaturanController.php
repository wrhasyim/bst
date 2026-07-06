<?php
// app/Controllers/PengaturanController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Logger.php';    // 🛡️ Load Audit Trail
require_once __DIR__ . '/../Core/Security.php';  // 🛡️ Load CSRF Protection

class PengaturanController {
    private $db;

    public function __construct() {
        // 🛡️ Satpam URL: Halaman pengaturan SANGAT SENSITIF, hanya untuk Admin
        Security::requireRole(['admin']);
        $this->db = Database::getInstance()->getConnection();
    }

    // =================================================================
    // 1. TAMPILKAN HALAMAN PENGATURAN
    // =================================================================
    public function index() {
        $data = [];
        $stmt = $this->db->query("SELECT kunci, nilai FROM pengaturan");
        $data['pengaturan'] = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        
        extract($data);
        $title = "Pengaturan Sistem";
        $content = __DIR__ . '/../../views/admin/pengaturan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function maintenance() {
        $data = [];
        extract($data);
        $title = "Pemeliharaan Data";
        $content = __DIR__ . '/../../views/admin/pengaturan/maintenance.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. PROSES UPDATE IDENTITAS, PERSENTASE & PARAMETER LAINNYA
    // =================================================================
    public function update_identitas() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Cegah serangan manipulasi form

            // 1. Ambil & Validasi Data Angka (Casting ke Float)
            $kas_bst     = (float)($_POST['persen_kas_bst'] ?? 0);
            $kas_sekolah = (float)($_POST['persen_kas_sekolah'] ?? 0);
            $pengelola   = (float)($_POST['persen_honor_pengelola'] ?? 0);
            $walikelas   = (float)($_POST['persen_honor_walikelas'] ?? 0);
            $piket       = (float)($_POST['persen_honor_piket'] ?? 0); 

            // 2. Kalkulasi Total Persentase
            $total = $kas_bst + $kas_sekolah + $pengelola + $walikelas + $piket;

            // 3. Validasi Ketat 100%
            if ($total != 100) {
                $_SESSION['error'] = "Gagal! Total alokasi distribusi harus tepat 100% (Input Anda saat ini: $total%)";
                header('Location: ' . BASE_URL . '/pengaturan');
                exit;
            }

            try {
                // 🔐 START TRANSACTION
                $this->db->beginTransaction();
                
                // 4. Logika Cerdas UPSERT (Update or Insert)
                // Catatan: Karena menggunakan looping dinamis, input baru seperti 'tanggal_mulai_reward'
                // akan otomatis ditangkap dan disimpan tanpa perlu hardcode variabelnya di sini.
                foreach ($_POST as $kunci => $nilai) {
                    if ($kunci !== 'csrf_token') {
                        // Cek apakah kunci sudah ada di database?
                        $cek = $this->db->prepare("SELECT id FROM pengaturan WHERE kunci = ?");
                        $cek->execute([$kunci]);
                        
                        if ($cek->rowCount() > 0) {
                            // Jika ADA -> Lakukan UPDATE
                            $stmt = $this->db->prepare("UPDATE pengaturan SET nilai = :v WHERE kunci = :k");
                            $stmt->execute(['v' => $nilai, 'k' => $kunci]);
                        } else {
                            // Jika BELUM ADA -> Lakukan INSERT Otomatis
                            $stmt = $this->db->prepare("INSERT INTO pengaturan (kunci, nilai) VALUES (:k, :v)");
                            $stmt->execute(['k' => $kunci, 'v' => $nilai]);
                        }
                    }
                }
                
                // ✅ COMMIT: Simpan permanen
                $this->db->commit();
                
                // 🛡️ Catat aktivitas ke Audit Trail (Pesan log diperbarui)
                Logger::log("Update Pengaturan", "Admin memperbarui profil institusi, distribusi margin, dan parameter reward.");
                $_SESSION['success'] = "Pengaturan Sistem berhasil diperbarui secara permanen!";

            } catch (Exception $e) {
                // ❌ ROLLBACK: Jika database bermasalah
                $this->db->rollBack();
                $_SESSION['error'] = "Terjadi Kesalahan Sistem Database: " . $e->getMessage();
            }
        }
        
        header('Location: ' . BASE_URL . '/pengaturan');
        exit;
    }

    // =================================================================
    // 3. FITUR BACKUP DATABASE
    // =================================================================
    public function backup() {
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
    // 5. RESET DATA TRANSAKSI SAJA
    // =================================================================
    public function reset_transaksi() {
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
    // 6. RESET TOTAL SISTEM
    // =================================================================
    public function reset_total() {
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

    // =================================================================
    // 7. LOGS AUDIT TRAIL
    // =================================================================
    public function logs() {
        $data = [];
        $sql = "SELECT l.*, u.nama 
                FROM activity_logs l 
                JOIN users u ON l.user_id = u.id 
                ORDER BY l.created_at DESC LIMIT 100";
        $data['logs'] = $this->db->query($sql)->fetchAll();

        extract($data);
        $title = "Audit Trail / Log Aktivitas";
        $content = __DIR__ . '/../../views/admin/pengaturan/logs.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 8. SYSTEM UPDATER (OTA PATCHING)
    // =================================================================
    public function apply_patch() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Keamanan ekstra

            // Validasi keberadaan file
            if (!isset($_FILES['file_patch']) || $_FILES['file_patch']['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['error'] = "Gagal! File patch tidak ditemukan atau terjadi kesalahan saat mengunggah.";
                header('Location: ' . BASE_URL . '/pengaturan');
                exit;
            }

            $file = $_FILES['file_patch']['tmp_name'];
            $nama_file = $_FILES['file_patch']['name'];

            // 1. Validasi Ekstensi (Wajib ZIP)
            $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
            if (strtolower($ext) !== 'zip') {
                $_SESSION['error'] = "Gagal! File patch harus berformat .zip";
                header('Location: ' . BASE_URL . '/pengaturan');
                exit;
            }

            // 2. Proses Ekstraksi & Overwrite (Menimpa File)
            $zip = new ZipArchive;
            if ($zip->open($file) === TRUE) {
                // Tentukan lokasi root aplikasi (mundur 2 folder dari lokasi controller ini)
                $extract_path = realpath(__DIR__ . '/../../'); 
                
                // Ekstrak dan timpa file secara paksa
                $zip->extractTo($extract_path);
                $zip->close();
                
                // 🛡️ Catat aktivitas ke Audit Trail
                Logger::log("System Patch", "Admin menginstal pembaruan sistem melalui file patch: $nama_file");
                
                $_SESSION['success'] = "🚀 Patch Sistem Berhasil Diterapkan! Aplikasi Anda kini berada di versi terbaru.";
            } else {
                $_SESSION['error'] = "Sistem gagal membaca atau mengekstrak file ZIP patch. Pastikan file tidak rusak (corrupt).";
            }
            
            header('Location: ' . BASE_URL . '/pengaturan');
            exit;
        }
    }
}
?>