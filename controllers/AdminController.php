<?php
// Controller: Admin
// Author: Antigravity

class AdminController {
    
    // Dashboard Stats
    public function dashboard($adminModel) {
        $stats = $adminModel->getStats();
        $cat_stats = $adminModel->getCategoryStats();
        $metrics = $adminModel->getPerformanceMetrics();
        $recent_activity = $adminModel->getRecentActivity();
        require 'views/admin/dashboard.php';
    }

    // List all complaints with filters
    public function aspirasi($aspirasiModel, $kategoriModel = null) {
        // Simple filter logic
        $filters = [];
        if (isset($_GET['status']) && $_GET['status'] !== '') {
            $filters['status'] = $_GET['status'];
        }
        if (isset($_GET['search']) && $_GET['search'] !== '') {
            $filters['search'] = $_GET['search'];
        }
        if (isset($_GET['kategori']) && $_GET['kategori'] !== '') {
            $filters['kategori'] = $_GET['kategori'];
        }
        if (isset($_GET['urgent']) && $_GET['urgent'] !== '') {
            $filters['urgent'] = $_GET['urgent'];
        }

        $data = $aspirasiModel->getAll($filters);

        // If AJAX request, return partial view
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            require 'views/admin/_aspirasi_table.php';
            exit;
        }

        // Needs kategori for filter dropdown
        $kategoriList = $kategoriModel ? $kategoriModel->getAll() : [];
        require 'views/admin/aspirasi.php';
    }

    // Detail complaints
    public function detail_aspirasi($id, $aspirasiModel) {
        $detail = $aspirasiModel->getById($id);
        require 'views/admin/aspirasi_detail.php';
    }

    // Update status/feedback
    public function update_aspirasi($id, $aspirasiModel) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'];
            $feedback = $_POST['feedback'];
            
            if ($aspirasiModel->updateStatus($id, $status, $feedback)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Status berhasil diperbarui!'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal memperbarui status.'];
            }
            header('Location: ' . base_url('index.php?page=admin_aspirasi'));
            exit;
        }
    }

    // Manage Categories
    public function kategori($kategoriModel) {
        $data = $kategoriModel->getAll();
        require 'views/admin/kategori.php';
    }

    // Store Category
    public function store_kategori($kategoriModel) {
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama_kategori'];
            $kategoriModel->create($nama);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kategori berhasil ditambahkan!'];
            header('Location: ' . base_url('index.php?page=admin_kategori'));
            exit;
        }
    }

    // Update Category
    public function update_kategori($kategoriModel) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id_kategori'];
            $nama = $_POST['nama_kategori'];
            if ($kategoriModel->update($id, $nama)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Kategori berhasil diperbarui!'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal memperbarui kategori.'];
            }
            header('Location: ' . base_url('index.php?page=admin_kategori'));
            exit;
        }
    }

    // Delete Category
    public function delete_kategori($id, $kategoriModel) {
        $kategoriModel->delete($id);
        header('Location: ' . base_url('index.php?page=admin_kategori'));
        exit;
    }

    // List Users
    public function users($siswaModel, $adminModel) {
        $users = $siswaModel->getAll();
        $admins = $adminModel->getAll();
        require 'views/admin/users.php';
    }

    // Store User
    public function store_user($siswaModel) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nisn = $_POST['nisn'];
            $nama = $_POST['nama'];
            $kelas = $_POST['kelas'];
            $password = $_POST['password'];

            if ($siswaModel->register($nisn, $nama, $kelas, $password)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pengguna berhasil ditambahkan!'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menambahkan pengguna. NISN mungkin sudah terdaftar.'];
            }
            header('Location: ' . base_url('index.php?page=admin_users'));
            exit;
        }
    }

    // Store Admin
    public function store_admin($adminModel) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            if ($adminModel->register($username, $password)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Admin berhasil ditambahkan!'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menambahkan admin. Username mungkin sudah digunakan.'];
            }
            header('Location: ' . base_url('index.php?page=admin_users'));
            exit;
        }
    }

    // Update Admin
    public function update_admin($adminModel) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $old_username = $_POST['old_username'] ?? '';
            $username = $_POST['username'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            if ($adminModel->update($old_username, $username, $password)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data admin berhasil diperbarui!'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal memperbarui data admin.'];
            }
            header('Location: ' . base_url('index.php?page=admin_users'));
            exit;
        }
    }

    // Delete Admin
    public function delete_admin($username, $adminModel) {
        if ($adminModel->delete($username)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Admin berhasil dihapus.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menghapus admin.'];
        }
        header('Location: ' . base_url('index.php?page=admin_users'));
        exit;
    }

    // Edit User (Ajax or separate page, let's do separate for now or modal integration)
    public function update_user($siswaModel) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nisn = $_POST['nisn'];
            $nama = $_POST['nama'];
            $kelas = $_POST['kelas'];
            $password = !empty($_POST['password']) ? $_POST['password'] : null;

            if ($siswaModel->update($nisn, $nama, $kelas, $password)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Data pengguna berhasil diperbarui!'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal memperbarui data pengguna.'];
            }
            header('Location: ' . base_url('index.php?page=admin_users'));
            exit;
        }
    }

    // Delete User
    public function delete_user($nisn, $siswaModel) {
        if ($siswaModel->delete($nisn)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pengguna berhasil dihapus.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menghapus pengguna. Mungkin telah mengirim aspirasi/laporan.'];
        }
        header('Location: ' . base_url('index.php?page=admin_users'));
        exit;
    }

    // Export Reports
    public function export($aspirasiModel, $type) {
        $filters = [];
        if (isset($_GET['status'])) $filters['status'] = $_GET['status'];
        if (isset($_GET['kategori'])) $filters['kategori'] = $_GET['kategori'];
        if (isset($_GET['urgent'])) $filters['urgent'] = $_GET['urgent'];
        $data = $aspirasiModel->getAll($filters);

        if ($type === 'pdf') {
            require 'views/admin/export_pdf.php';
        } else {
            require 'views/admin/export_excel.php';
        }
        exit;
    }

    // Announcements List
    public function pengumuman($pengumumanModel) {
        $data = $pengumumanModel->getAll();
        require 'views/admin/pengumuman.php';
    }

    // Store Announcement
    public function store_pengumuman($pengumumanModel) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = $_POST['judul'];
            $isi = $_POST['isi'];
            if ($pengumumanModel->create($judul, $isi)) {
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pengumuman berhasil diterbitkan!'];
            } else {
                $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menerbitkan pengumuman.'];
            }
            header('Location: ' . base_url('index.php?page=admin_pengumuman'));
            exit;
        }
    }

    // Delete Announcement
    public function delete_pengumuman($id, $pengumumanModel) {
        if ($pengumumanModel->delete($id)) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Pengumuman dihapus.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Gagal menghapus pengumuman.'];
        }
        header('Location: ' . base_url('index.php?page=admin_pengumuman'));
        exit;
    }
}
