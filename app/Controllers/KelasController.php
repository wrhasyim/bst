<?php
// app/Controllers/KelasController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class KelasController {
    private $db;

    public function __construct() {
        // 🛡️ Satpam URL: Hanya Admin yang bisa mengelola kelas
        Security::requireRole(['admin']);
        $this->db = Database::getInstance()->getConnection();
    }

    // =================================================================
    // 1. TAMPILKAN DATA KELAS
    // =================================================================
    public function index() {
        $data = []; // Wadah untuk data View

        // Query Cerdas: Ambil data kelas, nama wali kelas, dan hitung total siswa
        $sql = "SELECT k.*, u.nama as nama_walikelas, 
                (SELECT COUNT(id) FROM users WHERE kelas_id = k.id AND role = 'siswa') as total_siswa
                FROM kelas k 
                LEFT JOIN users u ON k.walikelas_id = u.id 
                ORDER BY k.nama_kelas ASC";
        $data['kelas'] = $this->db->query($sql)->fetchAll();
        
        // Ambil daftar guru untuk dropdown pilihan Wali Kelas
        $data['guru'] = $this->db->query("SELECT id, nama FROM users WHERE role IN ('guru', 'admin') AND is_active = 1 ORDER BY nama ASC")->fetchAll();

        // RENDER TAMPILAN DENGAN LAYOUT UNIVERSAL
        extract($data);
        $title = "Manajemen Data Kelas";
        $content = __DIR__ . '/../../views/admin/kelas/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =================================================================
    // 2. SIMPAN DATA KELAS BARU (DENGAN PROTEKSI 1 WALAS 1 KELAS)
    // =================================================================
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Validasi Token Form

            $nama_kelas = htmlspecialchars(trim($_POST['nama_kelas']));
            $walikelas_id = !empty($_POST['walikelas_id']) ? $_POST['walikelas_id'] : null;

            // 🛡️ VALIDASI BACKEND: Pastikan guru yang ditunjuk belum menjadi Walas di kelas lain
            if ($walikelas_id) {
                $cek = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE walikelas_id = ? LIMIT 1");
                $cek->execute([$walikelas_id]);
                $konflik = $cek->fetch();
                
                if ($konflik) {
                    $_SESSION['error'] = "Gagal! Guru tersebut sudah menjabat sebagai wali kelas di kelas " . $konflik['nama_kelas'] . ".";
                    header('Location: ' . BASE_URL . '/kelas');
                    exit;
                }
            }

            try {
                $sql = "INSERT INTO kelas (nama_kelas, walikelas_id) VALUES (?, ?)";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute([$nama_kelas, $walikelas_id])) {
                    $_SESSION['success'] = "Kelas berhasil ditambahkan!";
                } else {
                    $_SESSION['error'] = "Gagal menambahkan data kelas.";
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Terjadi kesalahan database saat menambah kelas.";
            }
            header('Location: ' . BASE_URL . '/kelas');
            exit;
        }
    }

    // =================================================================
    // 3. UBAH DATA KELAS (DENGAN PROTEKSI MULTI-CLAIM WALAS)
    // =================================================================
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Validasi Token Form

            $id = $_POST['id'];
            $nama_kelas = htmlspecialchars(trim($_POST['nama_kelas']));
            $walikelas_id = !empty($_POST['walikelas_id']) ? $_POST['walikelas_id'] : null;

            // 🛡️ VALIDASI BACKEND: Pastikan guru tersebut tidak dipakai kelas lain
            if ($walikelas_id) {
                $cek = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE walikelas_id = ? AND id != ? LIMIT 1");
                $cek->execute([$walikelas_id, $id]);
                $konflik = $cek->fetch();
                
                if ($konflik) {
                    $_SESSION['error'] = "Gagal! Guru tersebut sudah menjabat sebagai wali kelas di kelas " . $konflik['nama_kelas'] . ".";
                    header('Location: ' . BASE_URL . '/kelas');
                    exit;
                }
            }

            try {
                $sql = "UPDATE kelas SET nama_kelas=?, walikelas_id=? WHERE id=?";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute([$nama_kelas, $walikelas_id, $id])) {
                    $_SESSION['success'] = "Data Kelas berhasil diperbarui!";
                } else {
                    $_SESSION['error'] = "Gagal memperbarui data kelas.";
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Terjadi kesalahan database saat memperbarui kelas.";
            }
            header('Location: ' . BASE_URL . '/kelas');
            exit;
        }
    }

    // =================================================================
    // 4. HAPUS DATA KELAS (INTEGRITAS SINKRONISASI NASABAH & SALDO)
    // =================================================================
    public function delete() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            try {
                // 1. Ambil nama kelas untuk pengecekan KAS KELAS
                $stmtNama = $this->db->prepare("SELECT nama_kelas FROM kelas WHERE id = ?");
                $stmtNama->execute([$id]);
                $nama_kelas = $stmtNama->fetchColumn();

                if (!$nama_kelas) {
                    $_SESSION['error'] = "Kelas tidak ditemukan.";
                    header('Location: ' . BASE_URL . '/kelas');
                    exit;
                }

                // 2. 🛡️ DATA INTEGRITY CHECK: Cek apakah masih ada siswa reguler aktif di kelas ini
                $cekSiswa = $this->db->prepare("SELECT COUNT(*) FROM users WHERE kelas_id = ? AND nama NOT LIKE 'KAS KELAS - %'");
                $cekSiswa->execute([$id]);
                
                if ($cekSiswa->fetchColumn() > 0) {
                    $_SESSION['error'] = "Gagal! Kelas ini tidak bisa dihapus karena masih ada siswa di dalamnya. Pindahkan atau luluskan siswa terlebih dahulu di menu Akademik.";
                    header('Location: ' . BASE_URL . '/kelas');
                    exit;
                }

                // 3. 🌟 FIX POIN 16: PENCEGAHAN ORPHAN ACCOUNT PADA KAS KELAS
                $nama_akun_virtual = "KAS KELAS - " . strtoupper($nama_kelas);
                $stmtCariKas = $this->db->prepare("SELECT id FROM users WHERE nama = ? AND role = 'siswa'");
                $stmtCariKas->execute([$nama_akun_virtual]);
                $akun_kas = $stmtCariKas->fetch();

                if ($akun_kas) {
                    $uid_kas = $akun_kas['id'];
                    $masuk_kas = (float) $this->db->query("SELECT IFNULL(SUM(total_harga),0) FROM setoran WHERE user_id = $uid_kas AND status = 'valid'")->fetchColumn();
                    $keluar_kas = (float) $this->db->query("SELECT IFNULL(SUM(jumlah),0) FROM penarikan WHERE user_id = $uid_kas")->fetchColumn();
                    $saldo_tersisa = $masuk_kas - $keluar_kas;

                    if ($saldo_tersisa > 0) {
                        $_SESSION['error'] = "Gagal! Kelas tidak bisa dihapus karena Tabungan $nama_akun_virtual masih memiliki saldo sebesar Rp " . number_format($saldo_tersisa, 0, ',', '.') . ". Harap cairkan saldo tersebut terlebih dahulu sebelum membubarkan kelas.";
                        header('Location: ' . BASE_URL . '/kelas');
                        exit;
                    } else {
                        // Jika saldo sudah nol, Hapus/Soft-delete akun KAS KELAS tersebut
                        $stmtHapusKas = $this->db->prepare("DELETE FROM users WHERE id = ?");
                        $stmtHapusKas->execute([$uid_kas]);
                    }
                }

                // 4. Jika lolos semua validasi, eksekusi hapus kelas
                $stmt = $this->db->prepare("DELETE FROM kelas WHERE id = ?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Kelas berhasil dihapus.";

            } catch (Exception $e) {
                $_SESSION['error'] = "Terjadi kesalahan sistem saat menghapus kelas.";
            }
        }
        header('Location: ' . BASE_URL . '/kelas');
        exit;
    }
}
?>