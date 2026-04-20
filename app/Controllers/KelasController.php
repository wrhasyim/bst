<?php
// app/Controllers/KelasController.php
require_once __DIR__ . '/../Models/Kelas.php';

class KelasController {
    private $kelasModel;
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak! Khusus Administrator.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->kelasModel = new Kelas();
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $kelas = $this->kelasModel->getAll();
        $title = "Master Data Kelas";
        $content = __DIR__ . '/../../views/admin/kelas/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- MENAMPILKAN FORM TAMBAH KELAS ---
    public function create() {
        // Ambil daftar guru yang aktif untuk dijadikan pilihan Wali Kelas
        $stmt = $this->db->query("SELECT id, nama FROM users WHERE role = 'guru' AND is_active = 1 ORDER BY nama ASC");
        $gurus = $stmt->fetchAll();

        $title = "Tambah Kelas Baru";
        $content = __DIR__ . '/../../views/admin/kelas/create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- MEMPROSES DATA TAMBAH KELAS ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $wali_id = empty($_POST['walikelas_id']) ? null : $_POST['walikelas_id'];
            
            $data = [
                'nama_kelas' => trim($_POST['nama_kelas']),
                'walikelas_id' => $wali_id
            ];

            if ($this->kelasModel->create($data)) {
                $_SESSION['success'] = "Kelas baru berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan kelas.";
            }
            header('Location: ' . BASE_URL . '/kelas');
            exit;
        }
    }

    // --- MENAMPILKAN FORM EDIT KELAS ---
    public function edit($id) {
        $kelas = $this->kelasModel->getById($id);
        if (!$kelas) {
            $_SESSION['error'] = "Data kelas tidak ditemukan!";
            header('Location: ' . BASE_URL . '/kelas');
            exit;
        }

        // Ambil daftar guru yang aktif
        $stmt = $this->db->query("SELECT id, nama FROM users WHERE role = 'guru' AND is_active = 1 ORDER BY nama ASC");
        $gurus = $stmt->fetchAll();

        $title = "Edit Data Kelas";
        $content = __DIR__ . '/../../views/admin/kelas/edit.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- MEMPROSES DATA EDIT KELAS ---
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $wali_id = empty($_POST['walikelas_id']) ? null : $_POST['walikelas_id'];

            $data = [
                'nama_kelas' => trim($_POST['nama_kelas']),
                'walikelas_id' => $wali_id
            ];

            if ($this->kelasModel->update($id, $data)) {
                $_SESSION['success'] = "Data kelas berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data kelas.";
            }
            header('Location: ' . BASE_URL . '/kelas');
            exit;
        }
    }

    public function delete($id) {
        // Cek apakah ada siswa di kelas ini?
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE kelas_id = :id");
        $stmt->execute(['id' => $id]);
        $punyaSiswa = $stmt->fetchColumn();

        if ($punyaSiswa > 0) {
            $_SESSION['error'] = "Gagal! Kelas ini tidak bisa dihapus karena masih ada $punyaSiswa siswa di dalamnya.";
        } else {
            if ($this->kelasModel->delete($id)) {
                $_SESSION['success'] = "Data kelas berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus data kelas.";
            }
        }
        header('Location: ' . BASE_URL . '/kelas');
        exit;
    }
}