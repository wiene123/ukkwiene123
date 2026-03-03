<?php
// Model: Kategori
// Author: Antigravity

class Kategori {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Get all categories
    public function getAll() {
        return $this->db->query("SELECT * FROM kategori")->fetchAll();
    }

    // Add new category
    public function create($nama_kategori) {
        $stmt = $this->db->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        return $stmt->execute([$nama_kategori]);
    }

    // Update category
    public function update($id, $nama_kategori) {
        $stmt = $this->db->prepare("UPDATE kategori SET nama_kategori = ? WHERE id_kategori = ?");
        return $stmt->execute([$nama_kategori, $id]);
    }

    // Delete category
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM kategori WHERE id_kategori = ?");
        return $stmt->execute([$id]);
    }
}
