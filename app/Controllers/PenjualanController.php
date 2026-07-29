<?php
// app/Controllers/PenjualanController.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Core/Security.php'; // 🛡️ Load Security Global
require_once __DIR__ . '/../Core/Logger.php';   // 🛡️ Load Audit Trail

class PenjualanController {
    private $db;

    public function __construct() {
        // 🛡️ Menerapkan "Satpam URL": Hanya Admin dan Staf yang boleh mengakses
        Security::requireRole(['admin', 'staff']);
        $this->db = Database::getInstance()->getConnection();
    }

    public function index() {
        $data = []; // Wadah untuk data yang akan dikirim ke View

        try {
            $sql = "SELECT p.*, k.nama_sampah, k.satuan, k.konversi_kg
                    FROM penjualan p JOIN kategori_sampah k ON p.kategori_id = k.id
                    ORDER BY p.tanggal_jual DESC";
            $data['penjualan'] = $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            $sql = "SELECT p.*, k.nama_sampah, k.satuan, 1 as konversi_kg
                    FROM penjualan p JOIN kategori_sampah k ON p.kategori_id = k.id
                    ORDER BY p.tanggal_jual DESC";
            $data['penjualan'] = $this->db->query($sql)->fetchAll();
        }

        // RENDER TAMPILAN
        extract($data);
        $title = "Data Penjualan Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/index.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function create() {
        $data = [];

        try {
            $sql = "SELECT k.id, k.nama_sampah, k.harga_pengepul, k.satuan, k.konversi_kg,
                           (SELECT IFNULL(SUM(berat), 0) FROM setoran s WHERE s.kategori_id = k.id AND s.status = 'valid' AND (s.is_sold = 0 OR s.is_sold IS NULL)) as stok_tersedia
                    FROM kategori_sampah k WHERE k.nama_sampah != '🌟 REWARD PRESTASI' HAVING stok_tersedia > 0";
            $data['kategori_ready'] = $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            $sql = "SELECT k.id, k.nama_sampah, k.harga_pengepul, k.satuan, 1 as konversi_kg,
                           (SELECT IFNULL(SUM(berat), 0) FROM setoran s WHERE s.kategori_id = k.id AND s.status = 'valid' AND (s.is_sold = 0 OR s.is_sold IS NULL)) as stok_tersedia
                    FROM kategori_sampah k WHERE k.nama_sampah != '🌟 REWARD PRESTASI' HAVING stok_tersedia > 0";
            $data['kategori_ready'] = $this->db->query($sql)->fetchAll();
        }

        // RENDER TAMPILAN
        extract($data);
        $title = "Input Penjualan Pengepul";
        $content = __DIR__ . '/../../views/admin/penjualan/create.php';
        require_once __DIR__ . '/../../views/layouts/admin.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array($_SESSION['role'], ['admin', 'staff'])) {
            
            Security::validate_csrf(); // 🛡️ Keamanan CSRF

            // Menangkap input sebagai ARRAY
            $kategori_ids  = $_POST['kategori_id'] ?? [];
            $harga_per_kgs = $_POST['harga_per_kg'] ?? [];
            $total_pcss    = $_POST['total_pcs'] ?? [];
            $total_kgs     = $_POST['total_kg'] ?? [];
            
            // Input global untuk satu kali submit
            $keterangan         = !empty($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : 'Penjualan Gabungan';
            $kas_tutup_botol_rp = (float)($_POST['kas_tutup_botol_rp'] ?? 0);

            // Validasi: Pastikan data barang ada
            if (!is_array($kategori_ids) || count($kategori_ids) === 0) {
                $_SESSION['error'] = "Data barang kosong atau tidak valid.";
                header('Location: ' . BASE_URL . '/penjualan/create');
                exit;
            }

            try {
                $this->db->beginTransaction();

                // Ambil Konfigurasi Persentase (Diluar loop agar lebih cepat & hemat kueri)
                $stmtConfig = $this->db->query("SELECT kunci, nilai FROM pengaturan WHERE kunci LIKE 'persen_%'");
                $config = $stmtConfig->fetchAll(PDO::FETCH_KEY_PAIR);
                $p_pengelola = (float)($config['persen_honor_pengelola'] ?? 0) / 100;
                $p_sekolah   = (float)($config['persen_kas_sekolah'] ?? 0) / 100;
                $p_piket     = (float)($config['persen_honor_piket'] ?? 0) / 100;

                $tutup_botol_tercatat = false;
                $jumlah_barang_berhasil = 0;
                $total_semua_pendapatan = 0;

                // MELAKUKAN PERULANGAN UNTUK SETIAP BARANG YANG DIJUAL
                for ($i = 0; $i < count($kategori_ids); $i++) {
                    $kategori_id  = (int)($kategori_ids[$i] ?? 0);
                    $harga_per_kg = (float)($harga_per_kgs[$i] ?? 0);
                    $pcs_jual     = (float)($total_pcss[$i] ?? 0);
                    $total_kg     = (float)($total_kgs[$i] ?? 0);

                    // Skip jika baris ini kosong
                    if ($kategori_id <= 0 || $pcs_jual <= 0) continue;

                    // Tambahkan 'user_id' ke dalam list SELECT agar kepemilikan sisa setoran tidak hilang
                    $stmtRows = $this->db->prepare("SELECT id, user_id, berat, total_harga, honor_walas_rp FROM setoran WHERE kategori_id = ? AND status = 'valid' AND is_sold = 0 ORDER BY id ASC FOR UPDATE");
                    $stmtRows->execute([$kategori_id]);
                    $rows = $stmtRows->fetchAll();

                    $total_tersedia = 0;
                    foreach ($rows as $row) { $total_tersedia += (float)$row['berat']; }

                    if ($pcs_jual > $total_tersedia) {
                        throw new Exception("Stok untuk Barang ID {$kategori_id} tidak cukup! Diminta: {$pcs_jual} Pcs. Tersedia: {$total_tersedia} Pcs.");
                    }

                    $sisa_diminta = $pcs_jual;
                    $hpp_terpakai = 0;
                    $walas_terpakai = 0;

                    foreach ($rows as $row) {
                        if ($sisa_diminta <= 0) break;
                        
                        $id_setoran = $row['id'];
                        $berat_row  = (float)$row['berat'];
                        
                        if ($berat_row <= $sisa_diminta) {
                            // Baris ini habis terjual
                            $this->db->prepare("UPDATE setoran SET is_sold = 1 WHERE id = ?")->execute([$id_setoran]);
                            $hpp_terpakai += (float)$row['total_harga'];
                            $walas_terpakai += (float)$row['honor_walas_rp'];
                            $sisa_diminta -= $berat_row;
                        } else {
                            // Baris ini hanya terpakai sebagian, buat baris "Sisa" baru
                            $sisa_berat = $berat_row - $sisa_diminta;
                            $proporsi = $sisa_diminta / $berat_row;
                            
                            // Tandai baris lama sebagai terjual
                            $this->db->prepare("UPDATE setoran SET is_sold = 1 WHERE id = ?")->execute([$id_setoran]);
                            
                            // Hapus kolom kelas_id dari query INSERT karena tidak ada di database
                            $stmtNew = $this->db->prepare("INSERT INTO setoran (user_id, kategori_id, berat, total_harga, honor_walas_rp, status, is_sold, created_at) VALUES (?, ?, ?, ?, ?, 'valid', 0, NOW())");
                            $stmtNew->execute([
                                $row['user_id'], // Mengambil user_id yang berhasil di SELECT di atas
                                $kategori_id,
                                $sisa_berat,
                                $row['total_harga'] * ($sisa_berat / $berat_row),
                                $row['honor_walas_rp'] * ($sisa_berat / $berat_row)
                            ]);
                            
                            $hpp_terpakai += ($row['total_harga'] * $proporsi);
                            $walas_terpakai += ($row['honor_walas_rp'] * $proporsi);
                            $sisa_diminta = 0;
                        }
                    }

                    $beban_nasabah = $hpp_terpakai;
                    $total_walas_setoran = $walas_terpakai;

                    // Kalkulasi Pendapatan
                    $total_pendapatan = $total_kg * $harga_per_kg;
                    $margin_total     = $total_pendapatan - $beban_nasabah;
                    
                    $kas_sekolah_rp      = $margin_total * $p_sekolah;
                    $honor_pengelola_rp  = $margin_total * $p_pengelola;
                    $honor_piket_rp      = $margin_total * $p_piket;
                    $kas_bst_rp = $margin_total - ($kas_sekolah_rp + $honor_pengelola_rp + $honor_piket_rp + $total_walas_setoran);

                    // Mencegah Nilai Kas Tutup Botol Berganda!
                    // Uang ekstra hanya dicatat pada barang PERTAMA yang berhasil diproses.
                    $current_tutup_botol = 0;
                    if (!$tutup_botol_tercatat) {
                        $current_tutup_botol = $kas_tutup_botol_rp;
                        $tutup_botol_tercatat = true;
                    }

                    // Insert Data Penjualan
                    $sqlInsert = "INSERT INTO penjualan (
                                    kategori_id, total_pcs, harga_per_pcs, total_pendapatan, 
                                    beban_nasabah_rp, margin_total_rp, kas_sekolah_rp, 
                                    honor_pengelola_rp, honor_piket_rp, kas_bst_rp, kas_tutup_botol_rp,
                                    tanggal_jual, keterangan
                                  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
                    $stmtInsert = $this->db->prepare($sqlInsert);
                    $stmtInsert->execute([
                        $kategori_id, $pcs_jual, $harga_per_kg, $total_pendapatan,
                        $beban_nasabah, $margin_total, $kas_sekolah_rp,
                        $honor_pengelola_rp, $honor_piket_rp, $kas_bst_rp, $current_tutup_botol,
                        $keterangan
                    ]);

                    $jumlah_barang_berhasil++;
                    $total_semua_pendapatan += $total_pendapatan;
                }

                if ($jumlah_barang_berhasil === 0) {
                    throw new Exception("Tidak ada data penjualan yang valid untuk diproses.");
                }

                Logger::log("Proses Penjualan", "Mencatat penjualan $jumlah_barang_berhasil jenis barang sekaligus. Pendapatan Penjualan: Rp " . number_format($total_semua_pendapatan, 0, ',', '.') . " | Total Tutup Botol: Rp " . number_format($kas_tutup_botol_rp, 0, ',', '.'));
                
                $this->db->commit();
                $_SESSION['success'] = "Berhasil! Sebanyak $jumlah_barang_berhasil jenis barang terjual sekaligus.";
            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Gagal: " . $e->getMessage();
            }
            header('Location: ' . BASE_URL . '/penjualan');
            exit;
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
            
            Security::validate_csrf(); // 🛡️ Keamanan CSRF

            $id = (int)$_POST['id'];

            try {
                $this->db->beginTransaction();

                $stmtCek = $this->db->prepare("SELECT kategori_id, tanggal_jual FROM penjualan WHERE id = ? FOR UPDATE");
                $stmtCek->execute([$id]);
                $penjualan = $stmtCek->fetch();

                if (!$penjualan) {
                    throw new Exception("Data penjualan tidak ditemukan.");
                }

                $stmtDel = $this->db->prepare("DELETE FROM penjualan WHERE id = ?");
                $stmtDel->execute([$id]);

                $stmtRestore = $this->db->prepare("UPDATE setoran SET is_sold = 0 WHERE kategori_id = ? AND is_sold = 1 AND created_at <= ?");
                $stmtRestore->execute([$penjualan['kategori_id'], $penjualan['tanggal_jual']]);

                Logger::log("Batal Penjualan", "Admin membatalkan transaksi penjualan ID #$id dan mengembalikan stok ke gudang.");

                $this->db->commit();
                $_SESSION['success'] = "Penjualan dibatalkan. Stok dikembalikan ke gudang.";

            } catch (Exception $e) {
                $this->db->rollBack();
                $_SESSION['error'] = "Gagal membatalkan penjualan: " . $e->getMessage();
            }
            
            header('Location: ' . BASE_URL . '/penjualan');
            exit;
        }
    }
}
?>