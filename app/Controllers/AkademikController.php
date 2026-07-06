<?php
// app/Controllers/AkademikController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global
require_once __DIR__ . '/../Core/Logger.php';   // 🛡️ Load Audit Trail

class AkademikController {
    private $db;

    public function __construct() {
        // 🛡️ Satpam URL: Mutasi akademik mutlak hanya boleh dilakukan oleh Admin
        Security::requireRole(['admin']);
        $this->db = Database::getInstance()->getConnection();
    }

    // =================================================================
    // 1. HALAMAN KENAIKAN KELAS
    // =================================================================
    public function kenaikan() {
        $data = []; // Wadah untuk data View
        $data['kelas_list'] = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        // RENDER TAMPILAN DENGAN LAYOUT UNIVERSAL
        extract($data);
        $title = "Kenaikan Kelas Massal";
        $content = __DIR__ . '/../../views/admin/akademik/kenaikan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function proses_kenaikan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Proteksi CSRF

            $dari_kelas = $_POST['dari_kelas'];
            $ke_kelas = $_POST['ke_kelas'];

            if ($dari_kelas === $ke_kelas) {
                $_SESSION['error'] = "Kelas asal dan tujuan tidak boleh sama!";
            } else {
                try {
                    // 🛡️ Mulai Transaksi Database (Aman dari data korup)
                    $this->db->beginTransaction();

                    // 1. Pindahkan seluruh siswa aktif ke kelas tujuan
                    $stmtSiswa = $this->db->prepare("UPDATE users SET kelas_id = ? WHERE kelas_id = ? AND role = 'siswa' AND is_active = 1");
                    $stmtSiswa->execute([$ke_kelas, $dari_kelas]);
                    $jml_siswa = $stmtSiswa->rowCount();

                    // 2. Logika Auto-Move Wali Kelas (Sistem Kohort)
                    $stmtGetWalas = $this->db->prepare("SELECT walikelas_id FROM kelas WHERE id = ?");
                    $stmtGetWalas->execute([$dari_kelas]);
                    $walasAsal = $stmtGetWalas->fetchColumn();

                    if ($walasAsal) {
                        // Promosikan/pindahkan Walas ke kelas tujuan (Menimpa Walas di kelas tujuan jika ada)
                        $stmtUpdateTujuan = $this->db->prepare("UPDATE kelas SET walikelas_id = ? WHERE id = ?");
                        $stmtUpdateTujuan->execute([$walasAsal, $ke_kelas]);

                        // Kosongkan jabatan Walas di kelas asal
                        $stmtKosongkanAsal = $this->db->prepare("UPDATE kelas SET walikelas_id = NULL WHERE id = ?");
                        $stmtKosongkanAsal->execute([$dari_kelas]);
                    }

                    // 🛡️ Simpan semua perubahan secara permanen
                    $this->db->commit();
                    
                    Logger::log("Akademik Kenaikan", "Admin memindahkan $jml_siswa siswa dari ID Kelas $dari_kelas ke ID Kelas $ke_kelas.");
                    $_SESSION['success'] = "Berhasil! $jml_siswa siswa dipindahkan. Wali kelas juga otomatis mengikuti rombel barunya.";
                } catch (Exception $e) {
                    $this->db->rollBack(); // Batalkan semua jika ada error
                    $_SESSION['error'] = "Terjadi kesalahan sistem saat memproses kenaikan kelas.";
                }
            }
            header('Location: ' . BASE_URL . '/akademik/kenaikan');
            exit;
        }
    }

    // =================================================================
    // 2. HALAMAN KELULUSAN (ALUMNI)
    // =================================================================
    public function kelulusan() {
        $data = [];
        $data['kelas_list'] = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        
        extract($data);
        $title = "Kelulusan Alumni";
        $content = __DIR__ . '/../../views/admin/akademik/kelulusan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function proses_kelulusan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Proteksi CSRF

            $kelas_id = $_POST['kelas_id'];

            try {
                // 🛡️ Mulai Transaksi Database
                $this->db->beginTransaction();

                // 1. Proses Kelulusan Siswa (Cabut ID Kelas & Non-aktifkan)
                $stmt = $this->db->prepare("UPDATE users SET kelas_id = NULL, is_active = 0 WHERE kelas_id = ? AND role = 'siswa'");
                $stmt->execute([$kelas_id]);
                $count = $stmt->rowCount();

                // 2. Logika Pembebasan Jabatan Wali Kelas (Otomatis Menganggur)
                $stmtKosongkanWalas = $this->db->prepare("UPDATE kelas SET walikelas_id = NULL WHERE id = ?");
                $stmtKosongkanWalas->execute([$kelas_id]);

                // 🛡️ Simpan perubahan
                $this->db->commit();

                if ($count > 0) {
                    Logger::log("Akademik Kelulusan", "Admin memproses kelulusan $count siswa dari ID Kelas $kelas_id menjadi Alumni.");
                    $_SESSION['success'] = "Berhasil meluluskan $count siswa. Status siswa kini menjadi Alumni, dan Guru yang mengampu kini berstatus bebas tugas (bisa dipilih kembali menjadi Wali Kelas).";
                } else {
                    $_SESSION['error'] = "Gagal. Tidak ada siswa yang terdaftar di kelas tersebut.";
                }
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Terjadi kesalahan sistem saat memproses kelulusan.";
            }
            
            header('Location: ' . BASE_URL . '/akademik/kelulusan');
            exit;
        }
    }
}
?>