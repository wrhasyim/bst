<?php
// app/Controllers/UserController.php
require_once __DIR__ . '/../Core/Database.php';

class UserController {
    private $db;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Akses ditolak! Khusus Administrator.';
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        $this->db = Database::getInstance()->getConnection();
    }

    // --- 1. TAMPILKAN DATA (DENGAN MODAL) ---
    public function index() {
        $sql = "SELECT u.*, k.nama_kelas 
                FROM users u 
                LEFT JOIN kelas k ON u.kelas_id = k.id 
                ORDER BY u.role ASC, u.nama ASC";
        $users = $this->db->query($sql)->fetchAll();
        $kelas = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();

        $title = "Manajemen Data Pengguna";
        $content = __DIR__ . '/../../views/admin/user/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- 2. TAMBAH USER ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama']);
            $username = trim($_POST['username']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'];
            $kelas_id = ($role === 'siswa') ? $_POST['kelas_id'] : null;
            $angkatan = ($role === 'siswa') ? $_POST['angkatan'] : null;
            $is_active = $_POST['is_active'];

            try {
                $sql = "INSERT INTO users (username, password, nama, role, kelas_id, angkatan, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$username, $password, $nama, $role, $kelas_id, $angkatan, $is_active]);
                $_SESSION['success'] = "Pengguna berhasil ditambahkan!";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal! Username mungkin sudah digunakan.";
            }
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }

    // --- 3. UPDATE USER ---
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nama = trim($_POST['nama']);
            $username = trim($_POST['username']);
            $role = $_POST['role'];
            $kelas_id = ($role === 'siswa') ? $_POST['kelas_id'] : null;
            $angkatan = ($role === 'siswa') ? $_POST['angkatan'] : null;
            $is_active = $_POST['is_active'];

            try {
                if (!empty($_POST['password'])) {
                    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                    $sql = "UPDATE users SET username=?, password=?, nama=?, role=?, kelas_id=?, angkatan=?, is_active=? WHERE id=?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$username, $password, $nama, $role, $kelas_id, $angkatan, $is_active, $id]);
                } else {
                    $sql = "UPDATE users SET username=?, nama=?, role=?, kelas_id=?, angkatan=?, is_active=? WHERE id=?";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$username, $nama, $role, $kelas_id, $angkatan, $is_active, $id]);
                }
                $_SESSION['success'] = "Data pengguna berhasil diperbarui!";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Gagal! Username mungkin sudah digunakan orang lain.";
            }
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }

    // --- 4. HAPUS USER ---
    public function delete() {
        $id = $_GET['id'] ?? 0;
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error'] = "Anda tidak bisa menghapus akun Anda sendiri yang sedang aktif!";
        } else {
            try {
                $stmt = $this->db->prepare("DELETE FROM users WHERE id=?");
                $stmt->execute([$id]);
                $_SESSION['success'] = "Pengguna berhasil dihapus!";
            } catch (Exception $e) {
                $_SESSION['error'] = "Gagal menghapus pengguna. Data terkait (setoran) masih ada.";
            }
        }
        header('Location: ' . BASE_URL . '/user');
        exit;
    }

    // --- 5. TAMPILKAN FORM IMPORT CSV ---
    public function import() {
        $kelas = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();
        $title = "Import Data Siswa";
        $content = __DIR__ . '/../../views/admin/user/import.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- 6. PROSES FILE CSV ---
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

            $handle = fopen($file, "r");
            $sukses = 0; $gagal = 0; $baris = 0;
            $password = password_hash('123456', PASSWORD_DEFAULT);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $baris++;
                if ($baris == 1 && strtolower(trim($data[0])) == 'nama') continue;

                $nama = trim($data[0]);
                $username = strtolower(str_replace(' ', '', trim($data[1])));

                if (!empty($nama) && !empty($username)) {
                    try {
                        $sql = "INSERT INTO users (username, password, nama, role, kelas_id, angkatan, is_active) VALUES (?, ?, ?, 'siswa', ?, ?, 1)";
                        $stmt = $this->db->prepare($sql);
                        if ($stmt->execute([$username, $password, $nama, $kelas_id, $angkatan])) {
                            $sukses++;
                        }
                    } catch (PDOException $e) {
                        $gagal++;
                    }
                }
            }
            fclose($handle);
            $_SESSION['success'] = "Import Selesai! Berhasil: $sukses. Gagal/Duplikat: $gagal.";
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }
}