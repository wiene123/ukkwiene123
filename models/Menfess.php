<?php
// Model: Menfess
// Author: Antigravity

class Menfess {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Create new menfess
    public function create($nisn, $isi, $warna = '#ffffff') {
        try {
            $stmt = $this->db->prepare("INSERT INTO menfess (nisn, isi, warna, status) VALUES (?, ?, ?, 'pending')");
            return $stmt->execute([$nisn, $isi, $warna]);
        } catch (PDOException $e) {
            $_SESSION['db_error'] = $e->getMessage();
            return false;
        }
    }

    // Get all approved menfess with like counts
    public function getAllApproved($current_nisn = null) {
        $sql = "SELECT m.*, 
                (SELECT COUNT(*) FROM menfess_likes WHERE id_menfess = m.id) as likes,
                (SELECT COUNT(*) FROM menfess_likes WHERE id_menfess = m.id AND nisn = :nisn) as has_liked
                FROM menfess m 
                WHERE m.status = 'approved' 
                ORDER BY m.tgl_input DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nisn', $current_nisn);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get all pending for admin
    public function getPending() {
        $stmt = $this->db->prepare("SELECT * FROM menfess WHERE status = 'pending' ORDER BY tgl_input ASC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // NEW: Get moderated history (last 20 approved or rejected)
    public function getHistory($limit = 20) {
        $sql = "SELECT * FROM menfess WHERE status != 'pending' ORDER BY tgl_input DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // NEW: Get posts by NISN (to show status to student)
    public function getUserPosts($nisn) {
        $stmt = $this->db->prepare("SELECT * FROM menfess WHERE nisn = ? ORDER BY tgl_input DESC");
        $stmt->execute([$nisn]);
        return $stmt->fetchAll();
    }

    // Update status
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE menfess SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    // Delete
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM menfess WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Toggle Like
    public function toggleLike($id_menfess, $nisn) {
        // Check if liked
        $stmt = $this->db->prepare("SELECT id FROM menfess_likes WHERE id_menfess = ? AND nisn = ?");
        $stmt->execute([$id_menfess, $nisn]);
        if ($stmt->fetch()) {
            // Unlike
            $stmt = $this->db->prepare("DELETE FROM menfess_likes WHERE id_menfess = ? AND nisn = ?");
            return $stmt->execute([$id_menfess, $nisn]);
        } else {
            // Like
            $stmt = $this->db->prepare("INSERT INTO menfess_likes (id_menfess, nisn) VALUES (?, ?)");
            return $stmt->execute([$id_menfess, $nisn]);
        }
    }
}
