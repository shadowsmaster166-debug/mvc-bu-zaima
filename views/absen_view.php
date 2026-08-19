<?php
require_once "controllers/Absencontroller.php";

$data = array_merge([
    'mata_pelajaran' => '',
    'kelas' => '',
    'nama_guru' => '',
    'bulan' => '',
    'murid' => [],
    'rekap' => []
], $data ?? []);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Hadir Murid</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #000; }
        .header-info { margin-bottom: 15px; font-size: 14px; line-height: 1.6; }
        
        /* Form Tambah Murid */
        .box-form { border: 1px solid #000; padding: 10px; margin-bottom: 15px; display: inline-block; }
        .box-form input, .box-form button { padding: 4px 8px; font-size: 13px; }

        /* Tabel Sederhana */
        table { width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 10px; }
        th, td { border: 1px solid #000; text-align: center; padding: 4px 2px; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; padding-left: 5px; }

        select { border: none; background: transparent; font-size: 11px; cursor: pointer; text-align-last: center; }
        
        .btn-simpan { padding: 6px 15px; font-weight: bold; cursor: pointer; }
    </style>
</head>
<body>

    <h2>Daftar Hadir Murid</h2>

    <div class="header-info">
        <b>Mata Pelajaran:</b> <?= htmlspecialchars($data['mata_pelajaran']) ?><br>
        <b>Kelas:</b> <?= htmlspecialchars($data['kelas']) ?><br>
        <b>Nama Guru:</b> <?= htmlspecialchars($data['nama_guru']) ?><br>
        <b>Bulan:</b> <?= htmlspecialchars($data['bulan']) ?>
    </div>

    <!-- 1. FORM CREATE MURID BARU -->
    <div class="box-form">
        <form action="index.php" method="POST">
            <input type="hidden" name="action" value="tambah_murid">
            <label><b>Tambah Murid:</b> </label>
            <input type="text" name="nama_murid" placeholder="Nama Murid" required>
            <button type="submit">Tambah</button>
        </form>
    </div>

    <!-- 2. FORM CREATE / UPDATE ABSENSI & IZIN -->
    <form action="index.php" method="POST">
        <input type="hidden" name="action" value="simpan_absensi">
        
        <table>
            <thead>
                <tr>
                    <th rowspan="2" width="30">No</th>
                    <th rowspan="2" width="150">Nama Murid</th>
                    <th colspan="30">Tanggal</th>
                </tr>
                <tr>
                    <?php for($i = 1; $i <= 30; $i++): ?>
                        <th><?= $i ?></th>
                    <?php endfor; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['murid'] as $index => $m): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td class="text-left"><?= htmlspecialchars($m['nama_murid']) ?></td>
                    
                    <?php for($hari = 1; $hari <= 30; $hari++): ?>
                        <?php $status = $data['rekap'][$m['id']][$hari] ?? ''; ?>
                        <td>
                            <select name="status[<?= $m['id'] ?>][<?= $hari ?>]">
                                <option value="" <?= $status == '' ? 'selected' : '' ?>></option>
                                <option value="Hadir" <?= $status == 'Hadir' ? 'selected' : '' ?>>✓</option>
                                <option value="Ijin" <?= $status == 'Ijin' ? 'selected' : '' ?>>Ijin</option>
                                <option value="Sakit" <?= $status == 'Sakit' ? 'selected' : '' ?>>Sakit</option>
                                <option value="Alpha" <?= $status == 'Alpha' ? 'selected' : '' ?>>Alpha</option>
                            </select>
                        </td>
                    <?php endfor; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <button type="submit" class="btn-simpan">Simpan Data Absensi</button>
    </form>

</body>
</html>