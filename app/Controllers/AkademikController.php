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
        
        // 🛠️ FILTER LIST: Menyaring daftar kelas. Mengecualikan Kas Kesiswaan.
        $data['kelas_list'] = $this->db->query("SELECT * FROM kelas WHERE nama_kelas NOT LIKE '%KESISWAAN%' ORDER BY nama_kelas ASC")->fetchAll();

        // RENDER TAMPILAN
        extract($data);
        $title = "Kenaikan Kelas Massal";
        $content = __DIR__ . '/../../views/admin/akademik/kenaikan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function proses_kenaikan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Proteksi CSRF

            $dari_kelas = (int) $_POST['dari_kelas'];
            $ke_kelas = (int) $_POST['ke_kelas'];

            // Keamanan Dasar
            if (empty($dari_kelas) || empty($ke_kelas)) {
                 $_SESSION['error'] = "Harap pastikan Kelas Asal dan Tujuan dipilih dengan benar!";
                 header('Location: ' . BASE_URL . '/akademik/kenaikan');
                 exit;
            }

            if ($dari_kelas === $ke_kelas) {
                $_SESSION['error'] = "Kelas asal dan tujuan tidak boleh sama!";
            } else {
                // 🛠️ VALIDASI BARU: Cegah Kenaikan Jika Kelas Tujuan Belum Kosong
                $stmtCekTujuan = $this->db->prepare("SELECT COUNT(id) FROM users WHERE kelas_id = ? AND role = 'siswa' AND deleted_at IS NULL");
                $stmtCekTujuan->execute([$ke_kelas]);
                $jmlSiswaTujuan = (int) $stmtCekTujuan->fetchColumn();

                if ($jmlSiswaTujuan > 0) {
                    // Ambil nama kelas tujuan untuk pesan error yang informatif
                    $nm_tuj = $this->db->query("SELECT nama_kelas FROM kelas WHERE id = $ke_kelas")->fetchColumn();
                    $_SESSION['error'] = "Kenaikan ditolak! Kelas tujuan ($nm_tuj) masih berisi $jmlSiswaTujuan siswa. Harap luluskan atau pindahkan mereka terlebih dahulu.";
                    header('Location: ' . BASE_URL . '/akademik/kenaikan');
                    exit;
                }

                try {
                    // 🛡️ Mulai Transaksi Database (Aman dari data korup)
                    $this->db->beginTransaction();

                    // 1. Pindahkan seluruh siswa aktif ke kelas tujuan
                    $stmtSiswa = $this->db->prepare("UPDATE users SET kelas_id = ? WHERE kelas_id = ? AND role = 'siswa' AND deleted_at IS NULL");
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

                    // Ambil nama kelas untuk catatan Log Audit dan Pesan Sukses
                    $nm_asal = $this->db->query("SELECT nama_kelas FROM kelas WHERE id = $dari_kelas")->fetchColumn();
                    $nm_tuj = $this->db->query("SELECT nama_kelas FROM kelas WHERE id = $ke_kelas")->fetchColumn();

                    // 🛡️ Simpan semua perubahan secara permanen
                    $this->db->commit();
                    
                    if($jml_siswa > 0) {
                        Logger::log("Akademik Kenaikan", "Admin memindahkan $jml_siswa siswa dari $nm_asal ke $nm_tuj.");
                        $_SESSION['success'] = "Berhasil! $jml_siswa siswa dipindahkan dari $nm_asal ke $nm_tuj. Wali kelas juga otomatis mengikuti rombel barunya.";
                    } else {
                        $_SESSION['error'] = "Tidak ada siswa yang dipindahkan. Mungkin kelas asal tersebut sudah kosong.";
                    }

                } catch (Exception $e) {
                    $this->db->rollBack(); // Batalkan semua jika ada error
                    $_SESSION['error'] = "Terjadi kesalahan sistem saat memproses kenaikan kelas: " . $e->getMessage();
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
        // Memanggil semua data kelas untuk dikirim ke kelulusan.php
        $data['kelas_list'] = $this->db->query("SELECT * FROM kelas WHERE nama_kelas NOT LIKE '%KESISWAAN%' ORDER BY nama_kelas ASC")->fetchAll();
        
        // Memanggil daftar siswa tingkat akhir untuk preview
        $sql = "SELECT u.id, u.nama, u.angkatan, k.nama_kelas 
                FROM users u 
                JOIN kelas k ON u.kelas_id = k.id 
                WHERE u.role = 'siswa' AND (k.nama_kelas LIKE 'XII %' OR k.nama_kelas LIKE 'IX %') AND u.deleted_at IS NULL
                ORDER BY k.nama_kelas ASC, u.nama ASC";
        $data['siswa_xii'] = $this->db->query($sql)->fetchAll();

        extract($data);
        $title = "Kelulusan Alumni";
        $content = __DIR__ . '/../../views/admin/akademik/kelulusan.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function proses_kelulusan() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Proteksi CSRF

            // Mengambil ID Kelas dari form kelulusan.php
            $kelas_id = $_POST['kelas_id'] ?? null;

            if (!$kelas_id) {
                 $_SESSION['error'] = "Harap pilih kelas yang ingin diluluskan!";
                 header('Location: ' . BASE_URL . '/akademik/kelulusan');
                 exit;
            }

            try {
                // 🛡️ Mulai Transaksi Database
                $this->db->beginTransaction();

                // 1. Proses Kelulusan Siswa (Ubah Role & Cabut ID Kelas)
                $stmt = $this->db->prepare("UPDATE users SET kelas_id = NULL, role = 'alumni' WHERE kelas_id = ? AND role = 'siswa'");
                $stmt->execute([$kelas_id]);
                $count = $stmt->rowCount();

                // 2. Logika Pembebasan Jabatan Wali Kelas (Otomatis Menganggur)
                $stmtKosongkanWalas = $this->db->prepare("UPDATE kelas SET walikelas_id = NULL WHERE id = ?");
                $stmtKosongkanWalas->execute([$kelas_id]);

                // 🛡️ Simpan perubahan
                $this->db->commit();

                if ($count > 0) {
                    Logger::log("Akademik Kelulusan", "Admin memproses kelulusan $count siswa menjadi Alumni dari Kelas ID: $kelas_id.");
                    $_SESSION['success'] = "Berhasil meluluskan $count siswa! Mereka kini berstatus Alumni, dan Wali Kelas sebelumnya telah dibebastugaskan dari kelas tersebut.";
                } else {
                    $_SESSION['error'] = "Gagal memproses kelulusan. Kelas tersebut kosong atau tidak ada siswa berstatus reguler.";
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