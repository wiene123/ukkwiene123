<?php
// Export to CSV (Excel Compatible)
$filename = "rekap_aspirasi_" . date('Ymd_His') . ".csv";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = fopen("php://output", "w");

// Header Column
fputcsv($output, ['No', 'Tanggal', 'Nama Pelapor', 'Kelas', 'Kategori', 'Isi Laporan', 'Urgent', 'Status', 'Feedback', 'Tgl Ditanggapi']);

foreach ($data as $index => $row) {
    fputcsv($output, [
        $index + 1,
        date('d/m/Y H:i', strtotime($row['tgl_input'])),
        $row['nama_siswa'],
        $row['kelas'],
        $row['nama_kategori'],
        str_replace(["\r", "\n"], " ", $row['isi_aspirasi']),
        $row['is_urgent'] ? 'YA' : 'TIDAK',
        strtoupper($row['status']),
        str_replace(["\r", "\n"], " ", $row['feedback'] ?? '-'),
        $row['tgl_feedback'] ? date('d/m/Y H:i', strtotime($row['tgl_feedback'])) : '-'
    ]);
}

fclose($output);
exit;
