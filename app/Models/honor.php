<?php
// app/Models/Honor.php
require_once __DIR__ . '/../Core/Database.php';

class Honor {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Fungsi untuk Pencairan (Sisa Saldo)
    public function getHonorWaliKelas() {
        $stmtPersen = $this->db->query("SELECT nilai FROM pengaturan WHERE kunci = 'persen_honor_walikelas'");
        $persen = (float)($stmtPersen->fetchColumn() ?? 0) / 100;

        $sql = "SELECT 
                    u.id as user_id, u.nama as nama_guru, k.nama_kelas,
                    SUM(s.total_pengepul - s.total_harga) * :persen as total_jatah
                FROM setoran s
                JOIN users u ON s.walikelas_id = u.id
                JOIN kelas k ON u.id = k.walikelas_id
                WHERE s.status = 'valid' AND s.is_sold = 1
                GROUP BY u.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['persen' => $persen]);
        return $stmt->fetchAll();
    }

    // --- FITUR BARU: Method untuk Laporan Honor (Fix Error) ---
    public function getRekapHonorWaliKelas($persen_decimal) {
        $sql = "SELECT 
                    u.nama, k.nama_kelas, 
                    SUM(s.total_pengepul - s.total_harga) as margin_kelas,
                    SUM(s.total_pengepul - s.total_harga) * :persen as jatah_honor
                FROM setoran s
                JOIN users u ON s.walikelas_id = u.id
                JOIN kelas k ON u.id = k.walikelas_id
                WHERE s.status = 'valid' AND s.is_sold = 1
                GROUP BY u.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['persen' => $persen_decimal]);
        return $stmt->fetchAll();
    }

    public function getSudahCair($user_id) {
        $stmt = $this->db->prepare("SELECT SUM(jumlah) FROM pencairan_honor WHERE user_id = :uid");
        $stmt->execute(['uid' => $user_id]);
        return $stmt->fetchColumn() ?? 0;
    }

    public function simpanPencairan($data) {
        $sql = "INSERT INTO pencairan_honor (user_id, jumlah, jenis, keterangan) VALUES (:user_id, :jumlah, :jenis, :keterangan)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function getRiwayat() {
        $sql = "SELECT ph.*, u.nama FROM pencairan_honor ph JOIN users u ON ph.user_id = u.id ORDER BY ph.tanggal_cair DESC LIMIT 50";
        return $this->db->query($sql)->fetchAll();
    }
}