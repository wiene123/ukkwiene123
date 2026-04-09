<?php
// Model: Admin
// Author: Antigravity

class Admin {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Login for Admin
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === md5($password)) {
            return $user;
        }
        return false;
    }

    // Get statistics for dashboard
    public function getStats() {
        $stats = [];
        
        $stats['total'] = $this->db->query("SELECT COUNT(*) FROM aspirasi")->fetchColumn();
        $stats['menunggu'] = $this->db->query("SELECT COUNT(*) FROM aspirasi WHERE status='menunggu'")->fetchColumn();
        $stats['proses'] = $this->db->query("SELECT COUNT(*) FROM aspirasi WHERE status='proses'")->fetchColumn();
        $stats['selesai'] = $this->db->query("SELECT COUNT(*) FROM aspirasi WHERE status='selesai'")->fetchColumn();

        return $stats;
    }

    // Get stats per category
    public function getCategoryStats() {
        $sql = "SELECT k.nama_kategori, COUNT(ia.id_pelaporan) as total 
                FROM kategori k 
                LEFT JOIN input_aspirasi ia ON k.id_kategori = ia.id_kategori 
                GROUP BY k.id_kategori";
        return $this->db->query($sql)->fetchAll();
    }

    // Get advanced performance metrics
    public function getPerformanceMetrics() {
        $metrics = [];
        
        // Completion Rate
        $total = $this->db->query("SELECT COUNT(*) FROM aspirasi")->fetchColumn();
        $selesai = $this->db->query("SELECT COUNT(*) FROM aspirasi WHERE status='selesai'")->fetchColumn();
        $metrics['completion_rate'] = $total > 0 ? round(($selesai / $total) * 100, 1) : 0;
        
        // Urgent Waiting
        $metrics['urgent_waiting'] = $this->db->query("SELECT COUNT(*) FROM input_aspirasi ia JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan WHERE ia.is_urgent=1 AND a.status='menunggu'")->fetchColumn();
        
        // Avg Response Time (Dummy logic or simplified for UKK)
        $metrics['avg_response'] = " < 24 Jam";
        
        return $metrics;
    }

    // List all admins
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM admin ORDER BY username ASC");
        return $stmt->fetchAll();
    }

    // Register new admin
    public function register($username, $password) {
        try {
            $stmt = $this->db->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            return $stmt->execute([$username, md5($password)]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Delete admin
    public function delete($username) {
        $stmt = $this->db->prepare("DELETE FROM admin WHERE username = ?");
        return $stmt->execute([$username]);
    }

    // Update admin
    public function update($old_username, $new_username, $password = null) {
        try {
            if ($password) {
                $stmt = $this->db->prepare("UPDATE admin SET username = ?, password = ? WHERE username = ?");
                return $stmt->execute([$new_username, md5($password), $old_username]);
            } else {
                $stmt = $this->db->prepare("UPDATE admin SET username = ? WHERE username = ?");
                return $stmt->execute([$new_username, $old_username]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get recent activity (newest complaints)
    public function getRecentActivity($limit = 5) {
        $sql = "SELECT a.status, ia.isi_aspirasi, ia.tgl_input, s.nama as nama_siswa 
                FROM input_aspirasi ia
                JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
                JOIN siswa s ON ia.nisn = s.nisn
                ORDER BY ia.tgl_input DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
