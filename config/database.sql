-- Database: ukk_wiene2
-- Author: Antigravity for UKK RPL 2025/2026
-- Date: 2026-02-25 (Updated: Removed dynamic roles, removed created_at, removed id_admin)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";

-- Table: admin
CREATE TABLE IF NOT EXISTS `admin` (
  `username` VARCHAR(50) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed: admin (password: user123 -> md5)
-- MD5: 6ad14ba9986e3615423dfca256d04e3f
INSERT INTO `admin` (`username`, `password`) VALUES
('admin', '6ad14ba9986e3615423dfca256d04e3f');

-- Table: siswa
CREATE TABLE IF NOT EXISTS `siswa` (
  `nisn` CHAR(10) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `kelas` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`nisn`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: kategori
CREATE TABLE IF NOT EXISTS `kategori` (
  `id_kategori` INT(11) NOT NULL AUTO_INCREMENT,
  `nama_kategori` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed: kategori
INSERT INTO `kategori` (`nama_kategori`) VALUES
('Sarana & Prasarana'),
('Kebersihan'),
('Keamanan'),
('Kantin & Makanan');

-- Table: input_aspirasi
CREATE TABLE IF NOT EXISTS `input_aspirasi` (
  `id_pelaporan` INT(11) NOT NULL AUTO_INCREMENT,
  `nisn` CHAR(10) NOT NULL,
  `id_kategori` INT(11) NOT NULL,
  `isi_aspirasi` TEXT NOT NULL,
  `tgl_input` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pelaporan`),
  KEY `idx_aspirasi_nisn` (`nisn`),
  KEY `idx_aspirasi_kategori` (`id_kategori`),
  CONSTRAINT `fk_aspirasi_siswa` FOREIGN KEY (`nisn`) REFERENCES `siswa` (`nisn`) ON DELETE CASCADE,
  CONSTRAINT `fk_aspirasi_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: aspirasi
CREATE TABLE IF NOT EXISTS `aspirasi` (
  `id_aspirasi` INT(11) NOT NULL AUTO_INCREMENT,
  `id_pelaporan` INT(11) NOT NULL,
  `feedback` TEXT,
  `status` ENUM('menunggu','proses','selesai') NOT NULL DEFAULT 'menunggu',
  `tgl_feedback` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_aspirasi`),
  KEY `idx_status` (`status`),
  KEY `idx_feedback_pelaporan` (`id_pelaporan`),
  CONSTRAINT `fk_feedback_aspirasi` FOREIGN KEY (`id_pelaporan`) REFERENCES `input_aspirasi` (`id_pelaporan`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;

INSERT INTO siswa (nisn, nama, kelas, password) VALUES ('12345', 'Budi Santoso', 'XII RPL 1', '6ad14ba9986e3615423dfca256d04e3f');
