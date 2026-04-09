<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aspirasi SIPESKU</title>
    <style>
        body { font-family: sans-serif; padding: 30px; color: #333; }
        .header { text-align: center; margin-bottom: 40px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .logo { font-size: 24px; font-weight: bold; color: #8c82eb; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; font-size: 12px; }
        th { background-color: #f5f5f5; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .urgent { color: white; background: red; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div class="logo">SIPESKU - Sistem Pengaduan Sekolah</div>
        <p>Laporan Rekapitulasi Aspirasi & Pengaduan Siswa</p>
        <p style="font-size: 14px; color: #666;">Dicetak pada: <?= date('d F Y, H:i') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelapor</th>
                <th>Kategori</th>
                <th>Isi Aspirasi</th>
                <th>Urgent</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($data as $index => $row): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= date('d/m/Y H:i', strtotime($row['tgl_input'])) ?></td>
                <td><?= $row['nama_siswa'] ?> (<?= $row['kelas'] ?>)</td>
                <td><?= $row['nama_kategori'] ?></td>
                <td><?= $row['isi_aspirasi'] ?></td>
                <td><?= $row['is_urgent'] ? 'YA' : 'TIDAK' ?></td>
                <td><?= strtoupper($row['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="no-print" style="margin-top: 50px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Laporan</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>
</body>
</html>
