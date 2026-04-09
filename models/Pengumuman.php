<?php
// Model: Pengumuman
// Author: Antigravity

class Pengumuman {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    public function getAll() {
        $stmt = $this->db->prepare("SELECT * FROM pengumuman ORDER BY tgl_input DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function create($judul, $isi) {
        $stmt = $this->db->prepare("INSERT INTO pengumuman (judul, isi) VALUES (?, ?)");
        return $stmt->execute([$judul, $isi]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM pengumuman WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getLatest($limit = 5) {
        $stmt = $this->db->prepare("SELECT * FROM pengumuman ORDER BY tgl_input DESC LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
