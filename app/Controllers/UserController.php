<?php
// app/Controllers/UserController.php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Kelas.php';

class UserController {
    private $userModel;
    private $kelasModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak! Khusus Administrator.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->userModel = new User();
        $this->kelasModel = new Kelas();
    }

    public function index() {
        $users = $this->userModel->getAll();
        $title = "Data Pengguna";
        $content = __DIR__ . '/../../views/admin/user/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function create() {
        $kelas = $this->kelasModel->getAll(); // Ambil data kelas untuk pilihan Dropdown
        $title = "Tambah Pengguna Baru";
        $content = __DIR__ . '/../../views/admin/user/create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama' => trim($_POST['nama']),
                'username' => trim($_POST['username']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => $_POST['role'],
                'kelas_id' => ($_POST['role'] === 'siswa') ? $_POST['kelas_id'] : null,
                'angkatan' => ($_POST['role'] === 'siswa') ? $_POST['angkatan'] : null,
                'is_active' => $_POST['is_active']
            ];

            try {
                if ($this->userModel->create($data)) {
                    $_SESSION['success'] = "Pengguna berhasil ditambahkan!";
                }
            } catch (PDOException $e) {
                // Tangkap error jika username duplikat (asumsi field username unique di DB, atau dicek manual)
                $_SESSION['error'] = "Gagal! Username mungkin sudah digunakan.";
            }
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }

    public function edit($id) {
        $user = $this->userModel->getById($id);
        $kelas = $this->kelasModel->getAll();

        if (!$user) {
            $_SESSION['error'] = "Data pengguna tidak ditemukan!";
            header('Location: ' . BASE_URL . '/user');
            exit;
        }

        $title = "Edit Data Pengguna";
        $content = __DIR__ . '/../../views/admin/user/edit.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nama' => trim($_POST['nama']),
                'username' => trim($_POST['username']),
                'role' => $_POST['role'],
                'kelas_id' => ($_POST['role'] === 'siswa') ? $_POST['kelas_id'] : null,
                'angkatan' => ($_POST['role'] === 'siswa') ? $_POST['angkatan'] : null,
                'is_active' => $_POST['is_active']
            ];

            // Jika password diisi, kita hash. Jika kosong, biarkan array tetap kosong
            if (!empty($_POST['password'])) {
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            }

            try {
                if ($this->userModel->update($id, $data)) {
                    $_SESSION['success'] = "Data pengguna berhasil diperbarui!";
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal! Username mungkin sudah digunakan orang lain.";
            }
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }

    public function delete($id) {
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "Anda tidak bisa menghapus akun Anda sendiri yang sedang aktif!";
        } else {
            if ($this->userModel->delete($id)) {
                $_SESSION['success'] = "Pengguna berhasil dihapus!";
            } else {
                $_SESSION['error'] = "Gagal menghapus pengguna. Data terkait (seperti setoran) mungkin masih ada.";
            }
        }
        header('Location: ' . BASE_URL . '/user');
        exit;
    }
    // --- MENAMPILKAN FORM IMPORT CSV ---
    public function import() {
        $kelas = $this->kelasModel->getAll(); // Ambil data kelas untuk dropdown
        $title = "Import Data Siswa";
        $content = __DIR__ . '/../../views/admin/user/import.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- MEMPROSES FILE CSV ---
    public function processImport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kelas_id = $_POST['kelas_id'];
            $angkatan = trim($_POST['angkatan']);
            $file = $_FILES['file_csv']['tmp_name'];

            if (empty($file)) {
                $_SESSION['error'] = "Pilih file CSV terlebih dahulu!";
                header('Location: ' . BASE_URL . '/user/import');
                exit;
            }

            // Buka file CSV
            $handle = fopen($file, "r");
            $sukses = 0;
            $gagal = 0;
            $baris = 0;

            // Baca baris per baris
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $baris++;
                
                // Lewati baris pertama jika itu adalah judul kolom (Header: Nama, Username)
                if ($baris == 1 && strtolower(trim($data[0])) == 'nama') continue;

                $nama = trim($data[0]);
                $username = trim($data[1]);
                $password = password_hash('123456', PASSWORD_DEFAULT); // Password default

                if (!empty($nama) && !empty($username)) {
                    $userData = [
                        'nama' => $nama,
                        'username' => strtolower(str_replace(' ', '', $username)), // Pastikan username tanpa spasi
                        'password' => $password,
                        'role' => 'siswa',
                        'kelas_id' => $kelas_id,
                        'angkatan' => $angkatan,
                        'is_active' => 1
                    ];

                    try {
                        if ($this->userModel->create($userData)) {
                            $sukses++;
                        }
                    } catch (PDOException $e) {
                        $gagal++; // Gagal biasanya karena Username sudah ada di database (Duplikat)
                    }
                }
            }
            fclose($handle);

            $_SESSION['success'] = "Proses Import Selesai! Berhasil: $sukses siswa. Gagal/Duplikat: $gagal siswa.";
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }
}