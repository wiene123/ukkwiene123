<?php
// Model: Siswa
// Author: Antigravity

class Siswa {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Get new registrations in the last 24 hours
    public function getNewRegistrations($limit = 3) {
        $sql = "SELECT * FROM siswa 
                WHERE tgl_daftar > (NOW() - INTERVAL 1 DAY)
                ORDER BY tgl_daftar DESC 
                LIMIT :limit";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // If column doesn't exist yet, return empty
            return [];
        }
    }

    // Get total count of siswa
    public function getCount() {
        return $this->db->query("SELECT COUNT(*) FROM siswa")->fetchColumn();
    }
}
