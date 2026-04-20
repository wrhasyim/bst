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
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->setoranModel = new Setoran();
        $this->userModel = new User();
        $this->sampahModel = new KategoriSampah();
        $this->kelasModel = new Kelas();
        $this->db = Database::getInstance()->getConnection();
    }

    // =======================================================
    // 1. RIWAYAT TABUNGAN SISWA
    // =======================================================
    public function siswa() {
        $setoran = $this->setoranModel->getSetoranSiswa();
        $title = "Riwayat Tabungan";
        $content = __DIR__ . '/../../views/admin/setoran/siswa_index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // =======================================================
    // 2. INPUT MASSAL PER KELAS (BATCH STORE)
    // =======================================================
    public function siswa_kelas() {
        $all_kelas = $this->kelasModel->getAll();
        $all_sampah = $this->sampahModel->getAll();
        $kelas_id = $_GET['kelas_id'] ?? null;
        $siswa_list = $kelas_id ? $this->db->query("SELECT id, nama FROM users WHERE kelas_id = $kelas_id AND role = 'siswa' AND is_active = 1 ORDER BY nama ASC")->fetchAll() : [];
        $title = "Setoran Per Kelas (Pcs)";
        $content = __DIR__ . '/../../views/admin/setoran/siswa_kelas.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function siswa_batch_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kat = $this->sampahModel->getById($_POST['kategori_id']);
            foreach ($_POST['berat'] as $uid => $jml) {
                if ((float)$jml > 0) {
                    $u = $this->userModel->getById($uid);
                    $kls = ($u['kelas_id']) ? $this->kelasModel->getById($u['kelas_id']) : null;
                    
                    // PERBAIKAN: Menggunakan harga_dasar, bukan harga_siswa
                    $this->setoranModel->create([
                        'user_id' => $uid, 
                        'kategori_id' => $_POST['kategori_id'], 
                        'berat' => $jml,
                        'total_harga' => $jml * $kat['harga_dasar'], 
                        'total_pengepul' => $jml * $kat['harga_pengepul'],
                        'walikelas_id' => $kls['walikelas_id'] ?? null, 
                        'status' => 'pending'
                    ]);
                }
            }
            $_SESSION['success'] = "Setoran Pcs berhasil disimpan.";
            header('Location: ' . BASE_URL . '/setoran/siswa');
            exit;
        }
    }

    // =======================================================
    // 3. BAGIAN GURU
    // =======================================================
    public function guru() {
        $setoran = $this->setoranModel->getSetoranGuru();
        $title = "Setoran Guru (Pcs)";
        $content = __DIR__ . '/../../views/admin/setoran/guru.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function guru_store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kat = $this->sampahModel->getById($_POST['kategori_id']);
            $jml = (float)$_POST['berat'];
            
            // PERBAIKAN: Menggunakan harga_dasar, bukan harga_guru
            $this->setoranModel->create([
                'user_id' => $_POST['user_id'], 
                'kategori_id' => $_POST['kategori_id'], 
                'berat' => $jml,
                'total_harga' => $jml * $kat['harga_dasar'], 
                'total_pengepul' => $jml * $kat['harga_pengepul'],
                'walikelas_id' => null, 
                'status' => 'pending'
            ]);
            $_SESSION['success'] = "Setoran guru ($jml Pcs) disimpan.";
            header('Location: ' . BASE_URL . '/setoran/guru');
            exit;
        }
    }

    // =======================================================
    // 4. VALIDASI SETORAN
    // =======================================================
    public function validasi() {
        $pending = $this->setoranModel->getPending();
        $title = "Validasi Setoran";
        $content = __DIR__ . '/../../views/admin/setoran/validasi.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function edit_pending($id) {
        $setoran = $this->setoranModel->getById($id);
        if (!$setoran || $setoran['status'] != 'pending') {
            header('Location: ' . BASE_URL . '/setoran/validasi');
            exit;
        }
        $sampah = $this->sampahModel->getAll();
        $title = "Koreksi Data (Pcs)";
        $content = __DIR__ . '/../../views/admin/setoran/edit_pending.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update_pending($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kat = $this->sampahModel->getById($_POST['kategori_id']);
            $jml = (float)$_POST['berat'];
            
            // PERBAIKAN: Menyamakan harga satuan menggunakan harga_dasar
            $harga_satuan = $kat['harga_dasar'];

            $this->setoranModel->update($id, [
                'kategori_id' => $_POST['kategori_id'], 
                'berat' => $jml,
                'total_harga' => $jml * $harga_satuan, 
                'total_pengepul' => $jml * $kat['harga_pengepul']
            ]);
            
            $_SESSION['success'] = "Data $jml Pcs diperbarui.";
            header('Location: ' . BASE_URL . '/setoran/validasi');
            exit;
        }
    }

    public function proses_validasi($id) {
        $this->setoranModel->updateStatus($id, 'valid');
        $_SESSION['success'] = "Data divalidasi.";
        header('Location: ' . BASE_URL . '/setoran/validasi');
        exit;
    }

    public function hapus_pending($id) {
        $this->setoranModel->delete($id);
        $_SESSION['success'] = "Data pending berhasil dihapus.";
        header('Location: ' . BASE_URL . '/setoran/validasi');
        exit;
    }
}