<?php
// Entry Point & Router
// Author: Antigravity

// Enable Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'config/database.php';
require_once 'helpers/auth_helper.php';
require_once 'helpers/role_helper.php';

// Models
require_once 'models/Admin.php';
require_once 'models/Siswa.php';
require_once 'models/Kategori.php';
require_once 'models/Aspirasi.php';

// Controllers
require_once 'controllers/AdminController.php';
require_once 'controllers/SiswaController.php';

// Instantiate Models
$adminModel = new Admin();
$siswaModel = new Siswa();
$kategoriModel = new Kategori();
$aspirasiModel = new Aspirasi();

// Instantiate Controllers
$adminController = new AdminController();
$siswaController = new SiswaController();

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
            require 'views/auth/login.php';
        } else {
            require 'views/auth/login.php';
        }
        break;

    case 'logout':
        session_start();
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
        $adminController->aspirasi($aspirasiModel);
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

    default:
        echo "404 Not Found";
        break;
}
