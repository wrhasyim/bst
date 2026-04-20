<?php
// app/Controllers/SampahController.php
require_once __DIR__ . '/../Models/KategoriSampah.php';

class SampahController {
    private $sampahModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak! Khusus Administrator.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->sampahModel = new KategoriSampah();
    }

    public function index() {
        $sampah = $this->sampahModel->getAll();
        $title = "Master Kategori Sampah";
        $content = __DIR__ . '/../../views/admin/sampah/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // Menampilkan Form Tambah
    public function create() {
        $title = "Tambah Kategori Sampah";
        $content = __DIR__ . '/../../views/admin/sampah/create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // Memproses Data Tambah
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_sampah' => trim($_POST['nama_sampah']),
                'harga_siswa' => $_POST['harga_siswa'],
                'harga_guru' => $_POST['harga_guru'],
                'harga_pengepul' => $_POST['harga_pengepul']
            ];

            if ($this->sampahModel->create($data)) {
                $_SESSION['success'] = "Kategori sampah baru berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan data.";
            }
            header('Location: ' . BASE_URL . '/sampah');
            exit;
        }
    }

    // Menampilkan Form Edit
    public function edit($id) {
        $sampah = $this->sampahModel->getById($id);
        if (!$sampah) {
            $_SESSION['error'] = "Data tidak ditemukan!";
            header('Location: ' . BASE_URL . '/sampah');
            exit;
        }

        $title = "Edit Kategori Sampah";
        $content = __DIR__ . '/../../views/admin/sampah/edit.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // Memproses Data Edit
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama_sampah' => trim($_POST['nama_sampah']),
                'harga_siswa' => $_POST['harga_siswa'],
                'harga_guru' => $_POST['harga_guru'],
                'harga_pengepul' => $_POST['harga_pengepul']
            ];

            if ($this->sampahModel->update($id, $data)) {
                $_SESSION['success'] = "Kategori sampah berhasil diperbarui!";
            } else {
                $_SESSION['error'] = "Gagal memperbarui data.";
            }
            header('Location: ' . BASE_URL . '/sampah');
            exit;
        }
    }

    public function delete($id) {
        if ($this->sampahModel->delete($id)) {
            $_SESSION['success'] = "Kategori sampah berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus data. Mungkin sedang digunakan di riwayat setoran.";
        }
        header('Location: ' . BASE_URL . '/sampah');
        exit;
    }
}