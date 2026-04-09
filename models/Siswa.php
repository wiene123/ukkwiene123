<?php
// Model: Siswa
// Author: Antigravity

class Siswa {
    private $db;

    public function __construct() {
        global $db;
        $this->db = $db;
    }

    // Login for Siswa
    public function login($nisn, $password) {
        $stmt = $this->db->prepare("SELECT * FROM siswa WHERE nisn = ?");
        $stmt->execute([$nisn]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === md5($password)) {
            return $user;
        }
        return false;
    }

    // Register for Siswa
    public function register($nisn, $nama, $kelas, $password) {
        try {
            $stmt = $this->db->prepare("INSERT INTO siswa (nisn, nama, kelas, password) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$nisn, $nama, $kelas, md5($password)]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get single siswa by NISN
    public function getByNisn($nisn) {
        $stmt = $this->db->prepare("SELECT * FROM siswa WHERE nisn = ?");
        $stmt->execute([$nisn]);
        return $stmt->fetch();
    }

    // Get all siswa
    public function getAll() {
        return $this->db->query("SELECT * FROM siswa ORDER BY nama ASC")->fetchAll();
    }

    // Update siswa
    public function update($nisn, $nama, $kelas, $password = null) {
        if ($password) {
            $stmt = $this->db->prepare("UPDATE siswa SET nama = ?, kelas = ?, password = ? WHERE nisn = ?");
            return $stmt->execute([$nama, $kelas, md5($password), $nisn]);
        } else {
            $stmt = $this->db->prepare("UPDATE siswa SET nama = ?, kelas = ? WHERE nisn = ?");
            return $stmt->execute([$nama, $kelas, $nisn]);
        }
    }

    // Delete siswa
    public function delete($nisn) {
        $stmt = $this->db->prepare("DELETE FROM siswa WHERE nisn = ?");
        return $stmt->execute([$nisn]);
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
