<?php
class AbsensiModel {
    private $db;

    // Menerima koneksi PDO dari Controller/Entry Point
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    
    //READ: Mengambil daftar murid berdasarkan kelas tertentu.
    public function getMuridByKelas($kelas) {
        $stmt = $this->db->prepare("SELECT * FROM murid WHERE kelas = ?");
        $stmt->execute([$kelas]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRekapBulan($bulan, $tahun, $mata_pelajaran) {
        $stmt = $this->db->prepare("
            SELECT id_murid, DAY(tanggal) as hari, status 
            FROM absensi 
            WHERE MONTH(tanggal) = ? AND YEAR(tanggal) = ? AND mata_pelajaran = ?
        ");
        $stmt->execute([$bulan, $tahun, $mata_pelajaran]);
        
        $rekap = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rekap[$row['id_murid']][$row['hari']] = $row['status'];
        }
        return $rekap;
    }

    // --- FITUR CREATE BARU ---

    // 1. Tambah Murid Baru
    public function insertMurid($nama_murid, $kelas) {
        $stmt = $this->db->prepare("INSERT INTO murid (nama_murid, kelas) VALUES (?, ?)");
        return $stmt->execute([$nama_murid, $kelas]);
    }

    // 2. Simpan / Update Status Absensi (Izin, Sakit, Hadir, Alpha)
    public function saveOrUpdateAbsensi($id_murid, $mata_pelajaran, $nama_guru, $tanggal, $status) {
        // Cek apakah data absensi pada tanggal tersebut sudah ada
        $check = $this->db->prepare("SELECT id FROM absensi WHERE id_murid = ? AND tanggal = ? AND mata_pelajaran = ?");
        $check->execute([$id_murid, $tanggal, $mata_pelajaran]);

        if ($check->rowCount() > 0) {
            // Jika sudah ada, lakukan UPDATE
            $stmt = $this->db->prepare("UPDATE absensi SET status = ?, nama_guru = ? WHERE id_murid = ? AND tanggal = ? AND mata_pelajaran = ?");
            return $stmt->execute([$status, $nama_guru, $id_murid, $tanggal, $mata_pelajaran]);
        } else {
            // Jika belum ada, lakukan INSERT
            $stmt = $this->db->prepare("INSERT INTO absensi (id_murid, mata_pelajaran, nama_guru, tanggal, status) VALUES (?, ?, ?, ?, ?)");
            return $stmt->execute([$id_murid, $mata_pelajaran, $nama_guru, $tanggal, $status]);
        }
    }
}
?>