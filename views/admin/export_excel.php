<?php
// Export to CSV (Excel Compatible)
$filename = "rekap_aspirasi_" . date('Ymd_His') . ".csv";

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=\"$filename\"");

$output = fopen("php://output", "w");

// Add UTF-8 BOM for Excel compatibility
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Statistics Recap
$total = count($data);
$urgent = 0;
$selesai = 0;
foreach($data as $r) {
    if($r['is_urgent']) $urgent++;
    if($r['status'] == 'selesai') $selesai++;
}

// --- Header Summary Section (Ensuring consistent 10 columns) ---
fputcsv($output, ['SMK TERPADU BINA INSAN', '', '', '', '', '', '', '', '', '']);
fputcsv($output, ['LAPORAN REKAPITULASI ASPIRASI', '', '', '', '', '', '', '', '', '']);
fputcsv($output, ['Dicetak Tanggal:', date('Y-m-d H:i'), '', '', '', '', '', '', '', '']);
fputcsv($output, ['Status:', 'Total: '.$total, 'Urgent: '.$urgent, 'Selesai: '.$selesai, '', '', '', '', '', '']);
fputcsv($output, ['', '', '', '', '', '', '', '', '', '']); // Spacer row

// --- Detailed Data Table ---
fputcsv($output, ['No', 'Tanggal Laporan', 'Nama Pelapor', 'Kelas', 'Kategori', 'Isi Laporan', 'Urgent', 'Status', 'Kelompok Feedback', 'Tanggal Ditanggapi']);

foreach ($data as $index => $row) {
    fputcsv($output, [
        $index + 1,
        date('Y-m-d H:i', strtotime($row['tgl_input'])),
        $row['nama_siswa'],
        $row['kelas'],
        $row['nama_kategori'],
        str_replace(["\r", "\n", ","], " ", $row['isi_aspirasi']),
        $row['is_urgent'] ? 'YA' : 'TIDAK',
        strtoupper($row['status']),
        str_replace(["\r", "\n", ","], " ", $row['feedback'] ?? '-'),
        $row['tgl_feedback'] ? date('Y-m-d H:i', strtotime($row['tgl_feedback'])) : '-'
    ]);
}

fclose($output);
exit;
