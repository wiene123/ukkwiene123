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
        $stmt = $this->db->prepare("SELECT s.* FROM siswa s WHERE nisn = ?");
        $stmt->execute([$nisn]);
        $user = $stmt->fetch();

        if ($user && $user['password'] === md5($password)) {
            return $user;
        }
        return false;
    }

    // Register Siswa (for seeding or future use)
    public function register($nisn, $nama, $kelas, $password) {
        try {
            $hashed_password = md5($password);
            $stmt = $this->db->prepare("INSERT INTO siswa (nisn, nama, kelas, password) VALUES (?, ?, ?, ?)");
            return $stmt->execute([$nisn, $nama, $kelas, $hashed_password]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Get all students
    public function getAll() {
        return $this->db->query("SELECT s.* FROM siswa s")->fetchAll();
    }

    // Get by NISN
    public function getByNisn($nisn) {
        $stmt = $this->db->prepare("SELECT s.* FROM siswa s WHERE nisn = ?");
        $stmt->execute([$nisn]);
        return $stmt->fetch();
    }

    // Update Student Data
    public function update($nisn, $nama, $kelas, $password = null) {
        try {
            if ($password) {
                $hashed = md5($password);
                $stmt = $this->db->prepare("UPDATE siswa SET nama = ?, kelas = ?, password = ? WHERE nisn = ?");
                return $stmt->execute([$nama, $kelas, $hashed, $nisn]);
            } else {
                $stmt = $this->db->prepare("UPDATE siswa SET nama = ?, kelas = ? WHERE nisn = ?");
                return $stmt->execute([$nama, $kelas, $nisn]);
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    // Delete Student
    public function delete($nisn) {
        // Need to check if student has complaints first due to FK in input_aspirasi
        try {
            $stmt = $this->db->prepare("DELETE FROM siswa WHERE nisn = ?");
            return $stmt->execute([$nisn]);
        } catch (Exception $e) {
            return false;
        }
    }
}
