<?php
// Controller: Siswa
// Author: Antigravity

class SiswaController {
    
    // Dashboard & Submit Complaint Form
    public function dashboard($kategoriModel, $aspirasiModel) {
        $kategori = $kategoriModel->getAll();
        $nisn = $_SESSION['nisn'];
        $stats = $aspirasiModel->getStatsByNisn($nisn);
        $recent_activity = $aspirasiModel->getRecentActivityByNisn($nisn);
        require 'views/siswa/dashboard.php';
    }

    // Show creation form
    public function create($kategoriModel) {
        $kategori = $kategoriModel->getAll();
        require 'views/siswa/buat.php';
    }

    // Process new complaint
    public function store($aspirasiModel) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate inputs
            $nisn = $_SESSION['nisn'];
            $id_kategori = $_POST['id_kategori'];
            $isi_aspirasi = $_POST['isi_aspirasi'];
            $foto = null;

            // Handle file upload (Base64 for Vercel Serverless Compatibility)
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['foto']['tmp_name'];
                if(is_uploaded_file($tmpName)) {
                    $fileData = file_get_contents($tmpName);
                    // Check if it really is an image
                    $finfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($finfo, $tmpName);
                    finfo_close($finfo);
                    
                    if(strpos($mimeType, 'image/') === 0) {
                        $foto = 'data:' . $mimeType . ';base64,' . base64_encode($fileData);
                    }
                }
            }
            
            // Simple validation
            if (empty($id_kategori) || empty($isi_aspirasi)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Semua bidang wajib diisi.'];
                header('Location: ' . base_url('index.php?page=siswa_kirim'));
                exit;
            }

            // Create complaint
            if ($aspirasiModel->create($nisn, $id_kategori, $isi_aspirasi, $foto)) {
                // Success
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Laporan berhasil dikirim!'];
                header('Location: ' . base_url('index.php?page=siswa_riwayat'));
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal mengirim laporan.'];
                header('Location: ' . base_url('index.php?page=siswa_kirim'));
            }
            exit;
        }
    }

    // View History
    public function history($aspirasiModel) {
        $nisn = $_SESSION['nisn'];
        $history = $aspirasiModel->getByNisn($nisn);
        require 'views/siswa/riwayat.php';
    }

    // View Detail
    public function detail($id, $aspirasiModel) {
        // Ensure user owns this report or is admin (but this is SiswaController)
        $detail = $aspirasiModel->getById($id);
        if ($detail['nisn'] !== $_SESSION['nisn']) {
            // Unauthorized check
            header('Location: ' . base_url('index.php?page=unauthorized'));
            exit;
        }
        require 'views/siswa/detail.php';
    }
}
