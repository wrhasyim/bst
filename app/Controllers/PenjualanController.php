<?php
// app/Controllers/PenjualanController.php
require_once __DIR__ . '/../Models/Penjualan.php';
require_once __DIR__ . '/../Models/KategoriSampah.php';

class PenjualanController {
    private $penjualanModel;
    private $sampahModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'staff'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->penjualanModel = new Penjualan();
        $this->sampahModel = new KategoriSampah();
    }

    public function index() {
        $penjualan = $this->penjualanModel->getAll();
        $title = "Penjualan Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function create() {
        $stok = $this->penjualanModel->getReadyStock();
        $kategori = $this->sampahModel->getAll();
        
        foreach ($kategori as &$k) {
            $k['stok_pcs'] = 0;
            foreach ($stok as $s) {
                if ($s['kategori_id'] == $k['id']) {
                    $k['stok_pcs'] = (int)$s['total_stok'];
                }
            }
        }

        $title = "Jual ke Pengepul (Pcs)";
        $content = __DIR__ . '/../../views/admin/penjualan/create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kategori_id = $_POST['kategori_id'];
            $jumlah_jual = (int)$_POST['total_berat']; // DB tetap total_berat, logika tetap Pcs
            $harga_deal = (float)$_POST['harga_per_kg'];

            // Proteksi stok agar tidak minus
            $current_stok = $this->penjualanModel->getReadyStock($kategori_id);
            $stok_asli = $current_stok ? (int)$current_stok['total_stok'] : 0;

            if ($jumlah_jual > $stok_asli) {
                $_SESSION['error'] = "Gagal! Jumlah jual ($jumlah_jual Pcs) melebihi stok gudang ($stok_asli Pcs).";
                header('Location: ' . BASE_URL . '/penjualan/create');
                exit;
            }

            $data = [
                'kategori_id' => $kategori_id,
                'total_berat' => $jumlah_jual,
                'harga_per_kg' => $harga_deal,
                'total_pendapatan' => $jumlah_jual * $harga_deal,
                'keterangan' => $_POST['keterangan']
            ];

            if ($this->penjualanModel->create($data)) {
                $_SESSION['success'] = "Penjualan $jumlah_jual Pcs berhasil dicatat.";
            } else {
                $_SESSION['error'] = "Gagal mencatat penjualan.";
            }
            header('Location: ' . BASE_URL . '/penjualan');
            exit;
        }
    }
}