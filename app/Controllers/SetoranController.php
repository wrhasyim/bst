<?php
// app/Controllers/SetoranController.php
require_once __DIR__ . '/../Models/Setoran.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/KategoriSampah.php';
require_once __DIR__ . '/../Models/Kelas.php';

class SetoranController {
    private $setoranModel;
    private $userModel;
    private $sampahModel;
    private $kelasModel;
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'staff'])) {
            $_SESSION['error'] = 'Akses ditolak!';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->setoranModel = new Setoran();
        $this->userModel = new User();
        $this->sampahModel = new KategoriSampah();
        $this->kelasModel = new Kelas();
        $this->db = Database::getInstance()->getConnection();
    }

    // --- MENAMPILKAN RIWAYAT SETORAN SISWA ---
    // Method ini dipanggil saat URL: /setoran/siswa
    public function siswa() {
        $setoran = $this->setoranModel->getSetoranSiswa();
        $title = "Riwayat Setoran Siswa";
        $content = __DIR__ . '/../../views/admin/setoran/siswa_index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- MENAMPILKAN FORM SETORAN SISWA ---
    // Method ini dipanggil saat URL: /setoran/siswa_create
    public function siswa_create() {
        $stmtSiswa = $this->db->query("SELECT id, nama FROM users WHERE role = 'siswa' AND is_active = 1 ORDER BY nama ASC");
        $siswa = $stmtSiswa->fetchAll();

        $sampah = $this->sampahModel->getAll();

        $title = "Input Setoran Siswa";
        $content = __DIR__ . '/../../views/admin/setoran/siswa_create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- MEMPROSES INPUT SETORAN SISWA ---
    // Method ini dipanggil saat URL: /setoran/siswa_store
    public function siswa_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $_POST['user_id'];
            $kategori_id = $_POST['kategori_id'];
            $berat = (float)$_POST['berat'];

            if ($berat <= 0) {
                $_SESSION['error'] = "Berat sampah harus lebih dari 0!";
                header('Location: ' . BASE_URL . '/setoran/siswa_create');
                exit;
            }

            $sampah = $this->sampahModel->getById($kategori_id);
            if (!$sampah) {
                $_SESSION['error'] = "Kategori sampah tidak valid!";
                header('Location: ' . BASE_URL . '/setoran/siswa_create');
                exit;
            }

            $total_harga = $berat * $sampah['harga_siswa']; 
            $total_pengepul = $berat * $sampah['harga_pengepul']; 

            $siswa = $this->userModel->getById($user_id);
            $walikelas_id = null;
            if ($siswa && $siswa['kelas_id']) {
                $kelas = $this->kelasModel->getById($siswa['kelas_id']);
                $walikelas_id = $kelas['walikelas_id'] ?? null;
            }

            $data = [
                'user_id' => $user_id,
                'kategori_id' => $kategori_id,
                'berat' => $berat,
                'total_harga' => $total_harga,
                'total_pengepul' => $total_pengepul,
                'walikelas_id' => $walikelas_id,
                'status' => 'pending' 
            ];

            if ($this->setoranModel->create($data)) {
                $_SESSION['success'] = "Berhasil! Setoran sebesar Rp " . number_format($total_harga, 0, ',', '.') . " masuk ke tabungan.";
            } else {
                $_SESSION['error'] = "Gagal menyimpan data setoran.";
            }
            
            header('Location: ' . BASE_URL . '/setoran/siswa');
            exit;
        }
    }
}