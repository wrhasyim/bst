<?php
// app/Controllers/UserController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global

class UserController {
    private $db;

    public function __construct() {
        // 🛡️ Satpam URL: Hanya Admin yang bisa mengelola Pengguna
        Security::requireRole(['admin']);
        $this->db = Database::getInstance()->getConnection();
    }

    // --- 1. TAMPILKAN DATA (HANYA USER AKTIF) ---
    public function index() {
        $data = []; // Wadah untuk data View

        $sql = "SELECT u.*, k.nama_kelas 
                FROM users u 
                LEFT JOIN kelas k ON u.kelas_id = k.id 
                WHERE u.deleted_at IS NULL
                ORDER BY u.role ASC, u.nama ASC";
        $data['users'] = $this->db->query($sql)->fetchAll();
        $data['kelas'] = $this->db->query("SELECT * FROM kelas ORDER BY nama_kelas ASC")->fetchAll();

        // RENDER TAMPILAN
        extract($data);
        $title = "Manajemen Data Pengguna";
        $content = __DIR__ . '/../../views/admin/user/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- 2. TAMBAH USER ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Validasi Token Form

            $nama = htmlspecialchars(trim($_POST['nama']));
            $username = strtolower(trim($_POST['username']));
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $role = $_POST['role'];
            $kelas_id = ($role === 'siswa') ? $_POST['kelas_id'] : null;
            $angkatan = ($role === 'siswa') ? $_POST['angkatan'] : null;
            $is_active = $_POST['is_active'];

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
            Security::validate_csrf(); // 🛡️ Validasi Token Form

            $id = $_POST['id'];
            $nama = htmlspecialchars(trim($_POST['nama']));
            $username = strtolower(trim($_POST['username']));
            $role = $_POST['role'];
            $kelas_id = ($role === 'siswa') ? $_POST['kelas_id'] : null;
            $angkatan = ($role === 'siswa') ? $_POST['angkatan'] : null;
            $is_active = $_POST['is_active'];

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
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Validasi Token Form

            $id = $_POST['id'] ?? null;
            
            if ($id) {
                if ($id == $_SESSION['user_id']) {
                    $_SESSION['error'] = "Gagal! Anda tidak bisa menghapus akun sendiri.";
                    header('Location: ' . BASE_URL . '/user');
                    exit;
                }

                try {
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
        // Halaman ini tidak melempar data variabel apa pun selain judul, jadi langsung dirender
        $title = "Import Data Siswa Terintegrasi";
        $content = __DIR__ . '/../../views/admin/user/import.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    // --- 6. 🚀 PROSES FILE CSV (DYNAMIC AUTO-MAPPING) ---
    public function proses_import() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::validate_csrf(); // 🛡️ Validasi Token Form

            $file = $_FILES['file_csv']['tmp_name'];

            if (empty($file)) {
                $_SESSION['error'] = "Pilih file CSV terlebih dahulu!";
                header('Location: ' . BASE_URL . '/user/import');
                exit;
            }

            $handle = fopen($file, "r");
            $sukses = 0; $gagal = 0; $kelas_baru = 0; $baris = 0;
            $password_default = password_hash('123456', PASSWORD_DEFAULT);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $baris++;
                
                // Skip Header CSV (Baris pertama)
                if ($baris == 1) continue;

                $nama = htmlspecialchars(trim($data[0] ?? ''));
                $username = strtolower(str_replace(' ', '', trim($data[1] ?? '')));
                
                // Kolom 3 adalah Kelas, Kolom 4 adalah Angkatan
                $nama_kelas = strtoupper(trim($data[2] ?? ''));
                $angkatan = trim($data[3] ?? date('Y')); // Default tahun ini jika dikosongkan

                if (!empty($nama) && !empty($username) && !empty($nama_kelas)) {
                    
                    // 1. CEK KELAS: Jika ada, ambil ID. Jika tidak ada, BUAT BARU OTOMATIS!
                    $stmtKelas = $this->db->prepare("SELECT id FROM kelas WHERE nama_kelas = ? LIMIT 1");
                    $stmtKelas->execute([$nama_kelas]);
                    $kelasRow = $stmtKelas->fetch();
                    
                    if ($kelasRow) {
                        $kelas_id = $kelasRow['id'];
                    } else {
                        // Bikin kelas baru secara ajaib di background
                        $stmtNewKelas = $this->db->prepare("INSERT INTO kelas (nama_kelas) VALUES (?)");
                        $stmtNewKelas->execute([$nama_kelas]);
                        $kelas_id = $this->db->lastInsertId();
                        $kelas_baru++;
                    }

                    // 2. CEK USERNAME & INSERT (Pastikan tidak ada username kembar)
                    $cek = $this->db->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
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
                        $gagal++; // Username duplikat dilewati
                    }
                }
            }
            fclose($handle);
            $_SESSION['success'] = "Import Selesai! Berhasil: $sukses Siswa. Kelas Baru Dibuat: $kelas_baru. Gagal/Duplikat: $gagal.";
            header('Location: ' . BASE_URL . '/user');
            exit;
        }
    }

    // --- 7. 📥 DOWNLOAD TEMPLATE IMPORT CSV BARU ---
    public function download_template() {
        $filename = "Template_Import_Nasabah_BST.csv";
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");
        // Header CSV diubah menjadi 4 Kolom
        fputcsv($output, ['Nama Lengkap', 'Username', 'Kelas', 'Angkatan']);
        fputcsv($output, ['Siswa Dummy Satu', 'siswa.01', 'X-RPL-1', '2023']);
        fputcsv($output, ['Siswa Dummy Dua', 'siswa.02', 'X-RPL-2', '2023']);
        fputcsv($output, ['Siswa Dummy Tiga', 'siswa.03', 'XI-TKJ-1', '2022']);
        
        fclose($output);
        exit;
    }
}
?>