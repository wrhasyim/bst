<?php
// app/Controllers/PengaturanController.php
require_once __DIR__ . '/../Models/Pengaturan.php';

class PengaturanController {
    private $pengaturanModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak! Khusus Administrator.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->pengaturanModel = new Pengaturan();
    }

    public function index() {
        $settings = $this->pengaturanModel->getAllSettings();
        $title = "Pengaturan Sistem";
        $content = __DIR__ . '/../../views/admin/pengaturan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $p_pengelola = (float)$_POST['persen_pengelola'];
            $p_wali = (float)$_POST['persen_walikelas'];
            $p_sekolah = (float)$_POST['persen_kas_sekolah'];
            $p_bst = (float)$_POST['persen_kas_banksampah'];

            $totalPersen = $p_pengelola + $p_wali + $p_sekolah + $p_bst;
            
            if ($totalPersen != 100) {
                $_SESSION['error'] = "Total persentase harus 100%. Total Anda: {$totalPersen}%.";
                header('Location: ' . BASE_URL . '/pengaturan');
                exit;
            }

            $dataUpdate = [
                'nama_sekolah' => $_POST['nama_sekolah'],
                'alamat_sekolah' => $_POST['alamat_sekolah'],
                'persen_pengelola' => $p_pengelola,
                'persen_walikelas' => $p_wali,
                'persen_kas_sekolah' => $p_sekolah,
                'persen_kas_banksampah' => $p_bst
            ];

            if ($this->pengaturanModel->updateSettings($dataUpdate)) {
                $_SESSION['success'] = "Pengaturan berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui database.";
            }

            header('Location: ' . BASE_URL . '/pengaturan');
            exit;
        }
    }
}