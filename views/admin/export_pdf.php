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
    <!-- Kop Surat -->
    <div style="display: flex; align-items: center; border-bottom: 3px double #333; padding-bottom: 20px; margin-bottom: 30px;">
        <div style="flex: 1; text-align: center;">
            <h2 style="margin: 0; font-size: 1.6rem; text-transform: uppercase; color: #000;">SMK TERPADU BINA INSAN</h2>
            <p style="margin: 5px 0; font-size: 0.9rem; color: #555;">Jl. Pendidikan No. 123, Kota Pendidikan - Jawa Barat</p>
            <p style="margin: 0; font-size: 0.85rem; color: #777;">Email: info@smkterpadubinaisnan.sch.id | Telp: (021) 1234567</p>
        </div>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <h3 style="margin: 0; text-decoration: underline; text-transform: uppercase;">LAPORAN REKAPITULASI ASPIRASI SISWA</h3>
        <p style="margin: 5px 0; font-size: 0.9rem; color: #666;">Periode Cetak: <?= date('d F Y, H:i') ?></p>
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
                <td>
                    <?php if ($row['is_anonymous']): ?>
                        ANONIM
                    <?php else: ?>
                        <?= $row['nama_siswa'] ?> (<?= $row['kelas'] ?>)
                    <?php endif; ?>
                </td>
                <td><?= $row['nama_kategori'] ?></td>
                <td><?= $row['isi_aspirasi'] ?></td>
                <td><?= $row['is_urgent'] ? 'YA' : 'TIDAK' ?></td>
                <td><?= strtoupper($row['status']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Tanda Tangan -->
    <div style="margin-top: 60px; display: flex; justify-content: flex-end;">
        <div style="text-align: center; width: 250px;">
            <p style="margin-bottom: 80px;">Dicetak Oleh Admin,<br><br>Tanggal: <?= date('d/m/Y') ?></p>
            <p style="font-weight: bold; text-decoration: underline;">( ........................................... )</p>
            <p style="font-size: 0.85rem; color: #777;">NIP/Username: <?= h($_SESSION['username']) ?></p>
        </div>
    </div>

    <div class="no-print" style="margin-top: 50px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">Cetak Laporan</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer;">Tutup</button>
    </div>
</body>
</html>
