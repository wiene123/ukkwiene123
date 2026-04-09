<?php
// Model: Aspirasi
// Author: Antigravity

class Aspirasi {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Create a new complaint
    public function create($nisn, $id_kategori, $isi, $foto = null, $is_urgent = 0) {
        try {
            $this->db->beginTransaction();
            
            // Step 1: Insert into input_aspirasi
            $stmt = $this->db->prepare("INSERT INTO input_aspirasi (nisn, id_kategori, isi_aspirasi, foto, is_urgent) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nisn, $id_kategori, $isi, $foto, $is_urgent]);
            $id_pelaporan = $this->db->lastInsertId();

            if (!$id_pelaporan) {
                throw new Exception("Failed to insert input_aspirasi");
            }

            // Step 2: Initialize aspirasi with status 'menunggu'
            $stmt2 = $this->db->prepare("INSERT INTO aspirasi (id_pelaporan, status) VALUES (?, 'menunggu')");
            $stmt2->execute([$id_pelaporan]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Get all complaints with filters (for admin)
    public function getAll($filters = []) {
        $sql = "SELECT a.*, ia.isi_aspirasi, ia.foto, ia.is_urgent, ia.tgl_input, k.nama_kategori, s.nama AS nama_siswa, s.kelas
                FROM aspirasi a
                JOIN input_aspirasi ia ON a.id_pelaporan = ia.id_pelaporan
                JOIN kategori k ON ia.id_kategori = k.id_kategori
                JOIN siswa s ON ia.nisn = s.nisn
                WHERE 1=1";
        
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND a.status = ?";
            $params[] = $filters['status'];
        }

        // Additional filters can be added here (date, category, etc.)
        if (isset($filters['search']) && $filters['search'] !== '') {
            $sql .= " AND (ia.isi_aspirasi LIKE ? OR s.nama LIKE ?)";
            $params[] = "%" . $filters['search'] . "%";
            $params[] = "%" . $filters['search'] . "%";
        }
        if (isset($filters['kategori']) && $filters['kategori'] !== '') {
            $sql .= " AND ia.id_kategori = ?";
            $params[] = $filters['kategori'];
        }
        if (isset($filters['urgent']) && $filters['urgent'] == 1) {
            $sql .= " AND ia.is_urgent = 1";
        }

        $sql .= " ORDER BY ia.is_urgent DESC, ia.tgl_input DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Get complaints by specific student (history)
    public function getByNisn($nisn) {
        $sql = "SELECT a.*, ia.isi_aspirasi, ia.foto, ia.is_urgent, ia.tgl_input, k.nama_kategori
                FROM aspirasi a
                JOIN input_aspirasi ia ON a.id_pelaporan = ia.id_pelaporan
                JOIN kategori k ON ia.id_kategori = k.id_kategori
                WHERE ia.nisn = ?
                ORDER BY ia.is_urgent DESC, ia.tgl_input DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nisn]);
        return $stmt->fetchAll();
    }

    // Get single complaint detail
    public function getById($id_aspirasi) {
        $sql = "SELECT a.*, ia.isi_aspirasi, ia.foto, ia.is_urgent, ia.tgl_input, k.nama_kategori, s.nama AS nama_siswa, s.nisn, s.kelas
                FROM aspirasi a
                JOIN input_aspirasi ia ON a.id_pelaporan = ia.id_pelaporan
                JOIN kategori k ON ia.id_kategori = k.id_kategori
                JOIN siswa s ON ia.nisn = s.nisn
                WHERE a.id_aspirasi = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_aspirasi]);
        return $stmt->fetch();
    }

    // Update status and feedback
    public function updateStatus($id_aspirasi, $status, $feedback) {
        $stmt = $this->db->prepare("UPDATE aspirasi SET status = ?, feedback = ?, tgl_feedback = NOW() WHERE id_aspirasi = ?");
        return $stmt->execute([$status, $feedback, $id_aspirasi]);
    }

    // Get statistics for specific student
    public function getStatsByNisn($nisn) {
        $stats = [];
        // Total
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM input_aspirasi WHERE nisn = ?");
        $stmt->execute([$nisn]);
        $stats['total'] = $stmt->fetchColumn();

        // Status specific (Validasi JOIN aspirasi)
        $statuses = ['menunggu', 'proses', 'selesai'];
        foreach ($statuses as $status) {
            $sql = "SELECT COUNT(*) FROM aspirasi a 
                    JOIN input_aspirasi ia ON a.id_pelaporan = ia.id_pelaporan 
                    WHERE ia.nisn = ? AND a.status = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nisn, $status]);
            $stats[$status] = $stmt->fetchColumn();
        }

        return $stats;
    }
    // Get recent activity for specific student
    public function getRecentActivityByNisn($nisn, $limit = 5) {
        $sql = "SELECT a.status, ia.isi_aspirasi, ia.tgl_input, ia.is_urgent, k.nama_kategori 
                FROM input_aspirasi ia
                JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
                JOIN kategori k ON ia.id_kategori = k.id_kategori
                WHERE ia.nisn = :nisn
                ORDER BY ia.tgl_input DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nisn', $nisn);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get reports waiting > 24 hours
    public function getDelayedReports($limit = 3) {
        $sql = "SELECT ia.*, s.nama as nama_siswa, k.nama_kategori 
                FROM input_aspirasi ia
                JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
                JOIN siswa s ON ia.nisn = s.nisn
                JOIN kategori k ON ia.id_kategori = k.id_kategori
                WHERE a.status = 'menunggu' 
                AND ia.tgl_input < (NOW() - INTERVAL 1 DAY)
                ORDER BY ia.tgl_input ASC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get count of new reports today
    public function getDailyCount() {
        $sql = "SELECT COUNT(*) FROM input_aspirasi WHERE DATE(tgl_input) = CURDATE()";
        return $this->db->query($sql)->fetchColumn();
    }

    // Get recent activity by any admin
    public function getRecentAdminActivity($limit = 3) {
        $sql = "SELECT ia.isi_aspirasi, a.status, a.tgl_feedback, k.nama_kategori 
                FROM aspirasi a 
                JOIN input_aspirasi ia ON a.id_pelaporan = ia.id_pelaporan
                JOIN kategori k ON ia.id_kategori = k.id_kategori
                WHERE a.status != 'menunggu' 
                ORDER BY a.tgl_feedback DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
