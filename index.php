<?php
// Entry Point & Router
// Author: Antigravity

// Enable Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define Root Path
define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);

// Add Root to Include Path so require 'views/...' always works
set_include_path(get_include_path() . PATH_SEPARATOR . ROOT_PATH);

require_once ROOT_PATH . 'config/database.php';
require_once ROOT_PATH . 'helpers/auth_helper.php';
require_once ROOT_PATH . 'helpers/role_helper.php';

// Models
require_once ROOT_PATH . 'models/Admin.php';
require_once ROOT_PATH . 'models/Siswa.php';
require_once ROOT_PATH . 'models/Kategori.php';
require_once ROOT_PATH . 'models/Aspirasi.php';
require_once ROOT_PATH . 'models/Pengumuman.php';
require_once ROOT_PATH . 'models/Menfess.php';

// Controllers
require_once ROOT_PATH . 'controllers/AdminController.php';
require_once ROOT_PATH . 'controllers/SiswaController.php';
require_once ROOT_PATH . 'controllers/MenfessController.php';

// Instantiate Models
$adminModel = new Admin();
$siswaModel = new Siswa();
$kategoriModel = new Kategori();
$aspirasiModel = new Aspirasi();
$pengumumanModel = new Pengumuman();
$menfessModel = new Menfess();

// Instantiate Controllers
$adminController = new AdminController();
$siswaController = new SiswaController();
$menfessController = new MenfessController();

// Routing
$page = $_GET['page'] ?? 'login';

switch ($page) {
    case 'login':
        redirect_if_logged_in();
        // Handle Login Logic Inline for simplicity or separate controller
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username']; // or NISN
            $password = $_POST['password'];
            
            // Try Admin Login
            $admin = $adminModel->login($username, $password);
            if ($admin) {
                $_SESSION['user_id'] = $admin['username'];
                $_SESSION['username'] = $admin['username'];
                $_SESSION['role'] = 'admin';
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Login Berhasil! Selamat Datang Admin.'];
                header('Location: ' . base_url('index.php?page=admin_dashboard'));
                exit;
            }
            
            // Try Siswa Login
            $siswa = $siswaModel->login($username, $password);
            if ($siswa) {
                $_SESSION['user_id'] = $siswa['nisn'];
                $_SESSION['nisn'] = $siswa['nisn'];
                $_SESSION['nama'] = $siswa['nama'];
                $_SESSION['role'] = 'siswa'; 
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Login Berhasil! Selamat Datang.'];
                header('Location: ' . base_url('index.php?page=siswa_dashboard'));
                exit;
            }

            $error = "Username/NISN atau Password salah!";
            require ROOT_PATH . 'views/auth/login.php';
        } else {
            require ROOT_PATH . 'views/auth/login.php';
        }
        break;

    case 'logout':
        // Session is already started in database.php
        $_SESSION = [];
        session_destroy();
        session_start();
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Logout Berhasil! Sampai Jumpa.'];
        header('Location: ' . base_url('index.php?page=login'));
        exit;

    // --- ADMIN ROUTES ---
    case 'admin_dashboard':
        check_login(); check_role(['admin']);
        $adminController->dashboard($adminModel);
        break;
    
    case 'admin_aspirasi':
        check_login(); check_role(['admin']);
        $adminController->aspirasi($aspirasiModel, $kategoriModel);
        break;

    case 'admin_export_pdf':
        check_login(); check_role(['admin']);
        $adminController->export($aspirasiModel, 'pdf');
        break;

    case 'admin_export_excel':
        check_login(); check_role(['admin']);
        $adminController->export($aspirasiModel, 'excel');
        break;

    case 'admin_pengumuman':
        check_login(); check_role(['admin']);
        $adminController->pengumuman($pengumumanModel);
        break;

    case 'admin_pengumuman_store':
        check_login(); check_role(['admin']);
        $adminController->store_pengumuman($pengumumanModel);
        break;

    case 'admin_pengumuman_delete':
        check_login(); check_role(['admin']);
        $id = $_GET['id'] ?? 0;
        $adminController->delete_pengumuman($id, $pengumumanModel);
        break;

    case 'admin_aspirasi_detail':
        check_login(); check_role(['admin']);
        $id = $_GET['id'] ?? 0;
        $adminController->detail_aspirasi($id, $aspirasiModel);
        break;

    case 'admin_aspirasi_update':
        check_login(); check_role(['admin']);
        $id = $_GET['id'] ?? 0;
        $adminController->update_aspirasi($id, $aspirasiModel);
        break;

    case 'admin_kategori':
        check_login(); check_role(['admin']);
        $adminController->kategori($kategoriModel);
        break;

    case 'admin_kategori_store':
        check_login(); check_role(['admin']);
        $adminController->store_kategori($kategoriModel);
        break;

    case 'admin_kategori_update':
        check_login(); check_role(['admin']);
        $adminController->update_kategori($kategoriModel);
        break;

    case 'admin_kategori_delete':
        check_login(); check_role(['admin']);
        $id = $_GET['id'] ?? 0;
        $adminController->delete_kategori($id, $kategoriModel);
        break;

    case 'admin_users':
        check_login(); check_role(['admin']);
        $adminController->users($siswaModel, $adminModel);
        break;

    case 'admin_store':
        check_login(); check_role(['admin']);
        $adminController->store_admin($adminModel);
        break;

    case 'admin_delete':
        check_login(); check_role(['admin']);
        $username = $_GET['id'] ?? '';
        $adminController->delete_admin($username, $adminModel);
        break;

    case 'admin_update':
        check_login(); check_role(['admin']);
        $adminController->update_admin($adminModel);
        break;

    case 'admin_user_store':
        check_login(); check_role(['admin']);
        $adminController->store_user($siswaModel);
        break;

    case 'admin_user_update':
        check_login(); check_role(['admin']);
        $adminController->update_user($siswaModel);
        break;

    case 'admin_user_delete':
        check_login(); check_role(['admin']);
        $nisn = $_GET['nisn'] ?? '';
        $adminController->delete_user($nisn, $siswaModel);
        break;

    // --- SISWA ROUTES ---
    case 'siswa_dashboard':
        check_login(); check_role(['siswa']);
        $siswaController->dashboard($kategoriModel, $aspirasiModel);
        break;

    case 'siswa_kirim':
    case 'kirim_aspirasi':
        check_login(); check_role(['siswa']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $siswaController->store($aspirasiModel);
        } else {
            $siswaController->create($kategoriModel);
        }
        break;

    case 'siswa_riwayat':
    case 'riwayat':
        check_login(); check_role(['siswa']);
        $siswaController->history($aspirasiModel);
        break;
    
    case 'siswa_detail':
        check_login(); check_role(['siswa']);
        $id = $_GET['id'] ?? 0;
        $siswaController->detail($id, $aspirasiModel);
        break;

    case 'api_notif':
        check_login();
        $notifs = [];
        
        // 1. Logic for Siswa (Announcements + Responses)
        if ($_SESSION['role'] === 'siswa') {
            // Get Announcements
            $latest_p = $pengumumanModel->getLatest(3);
            foreach($latest_p as $p) {
                $notifs[] = [
                    'type' => 'pengumuman',
                    'title' => '📢 Pengumuman: ' . $p['judul'],
                    'message' => (strlen($p['isi']) > 100) ? substr($p['isi'], 0, 100) . '...' : $p['isi'],
                    'full_message' => $p['isi'],
                    'time' => time_ago($p['tgl_input'])
                ];
            }

            // Get Recent Responses for this student
            $recent = $aspirasiModel->getByNisn($_SESSION['nisn']);
            foreach($recent as $r) {
                if ($r['status'] !== 'menunggu' && $r['tgl_feedback']) {
                    $msg = 'Laporan kategori ' . ($r['nama_kategori'] ?? 'Umum') . ' telah diupdate menjadi ' . strtoupper($r['status']);
                    $full = $msg . ".\n\nTanggapan/Feedback Admin:\n" . ($r['feedback'] ?: 'Tidak ada pesan feedback.');
                    $notifs[] = [
                        'type' => $r['is_urgent'] ? 'urgent' : 'normal',
                        'title' => ($r['is_urgent'] ? '🚨 ' : '') . 'Tanggapan Baru',
                        'message' => $msg,
                        'full_message' => $full,
                        'time' => time_ago($r['tgl_feedback'])
                    ];
                }
            }
        } else {
            // ADMIN LOGIC (5 Points)
            
            // 1. 🚨 Laporan Urgent Baru
            $urgent_waiting = $aspirasiModel->getAll(['status' => 'menunggu', 'urgent' => 1]);
            foreach($urgent_waiting as $u) {
                $notifs[] = [
                    'type' => 'urgent',
                    'title' => '🚨 Laporan Urgent!',
                    'message' => $u['nama_siswa'] . ' mengirim laporan urgent.',
                    'full_message' => "Laporan Urgent dari " . $u['nama_siswa'] . ".\n\nIsi: " . $u['isi_aspirasi'],
                    'time' => time_ago($u['tgl_input'])
                ];
            }

            // 2. ⏰ Laporan Belum Ditanggapi > 24 Jam
            $delayed = $aspirasiModel->getDelayedReports(3);
            foreach($delayed as $d) {
                $pelapor = $d['is_anonymous'] ? 'ANONIM' : $d['nama_siswa'];
                $notifs[] = [
                    'type' => 'normal',
                    'title' => '⏰ Belum Ditanggapi > 24 Jam',
                    'message' => 'Laporan ' . $pelapor . ' ('. $d['nama_kategori'] .') belum diproses.',
                    'full_message' => "Peringatan: Laporan dari " . $pelapor . " sudah menunggu lebih dari 24 jam.\n\nKategori: " . $d['nama_kategori'],
                    'time' => time_ago($d['tgl_input'])
                ];
            }

            // 3. 📊 Rekap Harian
            $daily_count = $aspirasiModel->getDailyCount();
            if ($daily_count > 0) {
                $notifs[] = [
                    'type' => 'pengumuman',
                    'title' => '📊 Rekap Laporan Hari Ini',
                    'message' => "Ada $daily_count laporan baru yang masuk hari ini.",
                    'full_message' => "Statistik Hari Ini: Total $daily_count laporan baru telah dikirim oleh siswa.",
                    'time' => 'Hari ini'
                ];
            }

            // 4. 👨‍💻 Aktivitas Petugas Lain (Recent Responses)
            $recent_act = $aspirasiModel->getRecentAdminActivity(2);
            foreach($recent_act as $ra) {
                $notifs[] = [
                    'type' => 'normal',
                    'title' => '👨‍💻 Update Aktivitas Petugas',
                    'message' => 'Laporan kategori ' . $ra['nama_kategori'] . ' telah ditanggapi.',
                    'full_message' => "Sistem mencatat tanggapan baru pada laporan kategori " . $ra['nama_kategori'] . ".\n\nStatus: " . strtoupper($ra['status']),
                    'time' => time_ago($ra['tgl_feedback'])
                ];
            }

            // 5. 🔒 Notifikasi Keamanan (Registrasi Siswa Baru)
            $new_users = $siswaModel->getNewRegistrations(2);
            foreach($new_users as $nu) {
                $notifs[] = [
                    'type' => 'normal',
                    'title' => '🔒 Registrasi Siswa Baru',
                    'message' => $nu['nama'] . ' baru saja mendaftar.',
                    'full_message' => "Pemberitahuan Sistem: Akun siswa baru telah terdaftar.\n\nNama: " . $nu['nama'] . "\nKelas: " . $nu['kelas'],
                    'time' => time_ago($nu['tgl_daftar'])
                ];
            }
        }
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');
        echo json_encode(array_slice($notifs, 0, 10));
        exit;

    // --- BIMENFESS ROUTES ---
    case 'menfess':
        check_login(); check_role(['siswa', 'admin']);
        $menfessController->board($menfessModel);
        break;

    case 'menfess_store':
        check_login(); check_role(['siswa']);
        $menfessController->store($menfessModel);
        break;

    case 'menfess_like':
        check_login(); 
        $menfessController->like($menfessModel);
        break;

    case 'admin_menfess':
        check_login(); check_role(['admin']);
        $menfessController->admin_view($menfessModel);
        break;

    case 'admin_menfess_moderate':
        check_login(); check_role(['admin']);
        $menfessController->moderate($menfessModel);
        break;

    case 'unauthorized':
        require 'views/unauthorized.php';
        break;

    default:
        echo "404 Not Found";
        break;
}
