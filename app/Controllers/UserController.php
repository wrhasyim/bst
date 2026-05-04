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

    // --- 1. TAMPILKAN DATA (HANYA USER AKTIF) ---
    public function index() {
        $sql = "SELECT u.*, k.nama_kelas 
                FROM users u 
                LEFT JOIN kelas k ON u.kelas_id = k.id 
                WHERE u.deleted_at IS NULL
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
            $nama = htmlspecialchars(trim($_POST['nama']));
            $username = strtolower(trim($_POST['username']));
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'];
            $kelas_id = ($role === 'siswa') ? $_POST['kelas_id'] : null;
            $angkatan = ($role === 'siswa') ? $_POST['angkatan'] : null;
            $is_active = $_POST['is_active'];

            // VALIDASI: Cek apakah username sudah dipakai (termasuk yang sudah di-soft delete)
            $cek = $this->db->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
            $cek->execute([$username]);
            if ($cek->fetch()) {
                $_SESSION['error'] = "Gagal! Username sudah digunakan. Gunakan username lain.";
                header('Location: ' . BASE_URL . '/user');
                exit;
            }

            try {
                $sql = "INSERT INTO users (username, password, nama, role, kelas_id, angkatan, is_active) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$username, $password, $nama, $role, $kelas_id, $angkatan, $is_active]);
                $_SESSION['success'] = "Pengguna berhasil ditambahkan!";
            } catch (PDOException $e) {
                $_SESSION['error'] = "Terjadi kesalahan database.";
            }
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }

    // --- 3. UPDATE USER ---
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $nama = htmlspecialchars(trim($_POST['nama']));
            $username = strtolower(trim($_POST['username']));
            $role = $_POST['role'];
            $kelas_id = ($role === 'siswa') ? $_POST['kelas_id'] : null;
            $angkatan = ($role === 'siswa') ? $_POST['angkatan'] : null;
            $is_active = $_POST['is_active'];

            // VALIDASI: Cek username milik orang lain
            $cek = $this->db->prepare("SELECT id FROM users WHERE username = ? AND id != ? LIMIT 1");
            $cek->execute([$username, $id]);
            if ($cek->fetch()) {
                $_SESSION['error'] = "Gagal! Username ini sudah dimiliki user lain.";
                header('Location: ' . BASE_URL . '/user');
                exit;
            }

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
                $_SESSION['error'] = "Gagal memperbarui data.";
            }
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }

    // --- 4. HAPUS USER (SOFT DELETE) ---
    // Diubah ke POST agar lebih aman dari serangan CSRF
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            
            if ($id) {
                if ($id == $_SESSION['user_id']) {
                    $_SESSION['error'] = "Gagal! Anda tidak bisa menghapus akun sendiri.";
                    header('Location: ' . BASE_URL . '/user');
                    exit;
                }

                try {
                    // Logic Soft Delete: Menjaga integritas laporan keuangan
                    $stmt = $this->db->prepare("UPDATE users SET deleted_at = NOW(), is_active = 0 WHERE id = ?");
                    $stmt->execute([$id]);
                    $_SESSION['success'] = "Pengguna berhasil dinonaktifkan (Soft Delete).";
                } catch (Exception $e) {
                    $_SESSION['error'] = "Terjadi kesalahan sistem.";
                }
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
            // Password default menggunakan password_hash untuk keamanan
            $password_default = password_hash('123456', PASSWORD_DEFAULT);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $baris++;
                if ($baris == 1 && (strtolower(trim($data[0])) == 'nama' || strtolower(trim($data[0])) == 'nama lengkap')) continue;

                $nama = htmlspecialchars(trim($data[0]));
                $username = strtolower(str_replace(' ', '', trim($data[1])));

                if (!empty($nama) && !empty($username)) {
                    // Cek duplikat username sebelum insert untuk menghindari PDO Exception
                    $cek = $this->db->prepare("SELECT id FROM users WHERE username = ?");
                    $cek->execute([$username]);
                    
                    if (!$cek->fetch()) {
                        try {
                            $sql = "INSERT INTO users (username, password, nama, role, kelas_id, angkatan, is_active) VALUES (?, ?, ?, 'siswa', ?, ?, 1)";
                            $stmt = $this->db->prepare($sql);
                            if ($stmt->execute([$username, $password_default, $nama, $kelas_id, $angkatan])) {
                                $sukses++;
                            }
                        } catch (PDOException $e) {
                            $gagal++;
                        }
                    } else {
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

    // --- 7. DOWNLOAD TEMPLATE IMPORT CSV ---
    public function download_template() {
        $filename = "Template_Import_Siswa_BST.csv";
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");
        fputcsv($output, ['Nama Lengkap', 'Username', 'Angkatan']);
        fputcsv($output, ['Budi Santoso', 'budi.s', '2023']);
        fputcsv($output, ['Siti Aminah', 'siti.a', '2023']);
        
        fclose($output);
        exit;
    }
}