<?php
// Controller: Menfess
// Author: Antigravity

class MenfessController {

    // Board view
    public function board($menfessModel) {
        $nisn = $_SESSION['nisn'] ?? null;
        $posts = $menfessModel->getAllApproved($nisn);
        
        $my_posts = [];
        if ($nisn) {
            $my_posts = $menfessModel->getUserPosts($nisn);
        }
        
        require 'views/siswa/menfess_board.php';
    }

    // Submit new menfess
    public function store($menfessModel) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nisn = $_SESSION['nisn'];
            $isi = $_POST['isi'];
            $warna = $_POST['warna'] ?? '#ffffff';

            if (empty($isi)) {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Isi curhat tidak boleh kosong.'];
            } else {
                if ($menfessModel->create($nisn, $isi, $warna)) {
                    $_SESSION['flash'] = ['type' => 'success', 'message' => 'Curhat berhasil dikirim! Menunggu persetujuan admin.'];
                } else {
                    $err = $_SESSION['db_error'] ?? 'Gagal menyimpan ke database.';
                    $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal mengirim curhat: ' . $err];
                    unset($_SESSION['db_error']);
                }
            }
            header('Location: ' . base_url('index.php?page=menfess'));
            exit;
        }
    }

    // Admin moderation view
    public function admin_view($menfessModel) {
        $pending = $menfessModel->getPending();
        $history = $menfessModel->getHistory(15); // Show last 15 actions
        require 'views/admin/menfess.php';
    }

    // Handle approval/rejection
    public function moderate($menfessModel) {
        $id = $_GET['id'] ?? 0;
        $action = $_GET['action'] ?? '';
        
        if ($action === 'approve') {
            $menfessModel->updateStatus($id, 'approved');
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Curhat disetujui!'];
        } elseif ($action === 'reject') {
            $menfessModel->updateStatus($id, 'rejected');
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Curhat ditolak.'];
        } elseif ($action === 'delete') {
            $menfessModel->delete($id);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Curhat dihapus.'];
        }

        header('Location: ' . base_url('index.php?page=admin_menfess'));
        exit;
    }

    // Like API
    public function like($menfessModel) {
        header('Content-Type: application/json');
        if (!isset($_SESSION['nisn'])) {
            echo json_encode(['success' => false, 'message' => 'Login required']);
            exit;
        }
        $id = $_GET['id'] ?? 0;
        $nisn = $_SESSION['nisn'];
        
        if ($menfessModel->toggleLike($id, $nisn)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }
}
