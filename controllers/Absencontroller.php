<?php
require_once 'models/Absenmodel.php';

class Absensicontroller {
    private $model;

    public function __construct($dbConnection) {
        // Inisialisasi Model dengan koneksi DB yang diberikan
        $this->model = new AbsensiModel($dbConnection);
    }

    public function index() {
        // Parameter halaman / filter default
        $kelas = '12 RPL 3';
        $mata_pelajaran = 'Mapel Pilihan RPL';
        $nama_guru = 'Bu Zaima';
        $bulan = 8;
        $tahun = 2026;

        // Prosess POST: Tambah Murid Baru
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'tambah_murid') {
            $nama = trim($_POST['nama_murid']);
            if (!empty($nama)) {
                $this->model->insertMurid($nama, $kelas);
            }
            // Pattern Post/Redirect/Get (PRG) untuk mencegah submit ganda
            header("Location: index.php");
            exit;
        }

        // Proses POST: Simpan Absensi / Izin
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'simpan_absensi') {
            if (isset($_POST['status']) && is_array($_POST['status'])) {
                foreach ($_POST['status'] as $id_murid => $tanggal_list) {
                    foreach ($tanggal_list as $hari => $status) {
                        if (!empty($status)) {
                            $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);
                            $this->model->saveOrUpdateAbsensi($id_murid, $mata_pelajaran, $nama_guru, $tanggal, $status);
                        }
                    }
                }
            }
            header("Location: index.php");
            exit;
        }

        // Ambil Data untuk Tampilan View
        $daftar_murid = $this->model->getMuridByKelas($kelas);
        $rekap_absensi = $this->model->getRekapBulan($bulan, $tahun, $mata_pelajaran);

        $data = [
            'mata_pelajaran' => $mata_pelajaran,
            'kelas' => $kelas,
            'nama_guru' => $nama_guru,
            'bulan' => 'Agustus ' . $tahun,
            'murid' => $daftar_murid,
            'rekap' => $rekap_absensi
        ];

        require_once __DIR__ . '/../views/absen_view.php';
    }
}
?>