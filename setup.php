<?php
// Auto Setup Script for UKK Wiene2
// This script will detect your environment (Local or Cloud) and setup the database.

require_once 'config/database.php';

echo "<style>body { font-family: sans-serif; line-height: 1.6; padding: 20px; color: #333; } .success { color: green; } .info { color: blue; } .error { color: red; }</style>";
echo "<h1>🚀 UKK SIPESKU Auto-Setup</h1>";
echo "<p>Environment: <b>" . (env('MYSQLHOST') ? 'Cloud (Vercel/Aiven)' : 'Localhost') . "</b></p>";
echo "<p>Host: <b>" . DB_HOST . "</b></p>";

// Helper to check if column exists
function columnExists($db, $table, $column) {
    try {
        $stmt = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
        return $stmt->fetch() !== false;
    } catch (Exception $e) { return false; }
}

try {
    // Note: $db is already instantiated in config/database.php
    echo "<p class='success'>✅ 1. Connected to Database Server.</p>";

    // 2. Import SQL if tables missing
    $stmt = $db->query("SHOW TABLES LIKE 'admin'");
    if (!$stmt->fetchColumn()) {
        echo "<p>Tables missing. Importing structure...</p>";
        
        $sql_file = __DIR__ . '/config/database.sql';
        if (file_exists($sql_file)) {
            $sql = file_get_contents($sql_file);
            
            // Clean up the SQL to avoid common errors on Aiven
            // Remove START TRANSACTION and COMMIT if they cause issues, and split queries
            $queries = explode(";", $sql);
            foreach ($queries as $query) {
                $query = trim($query);
                if (empty($query)) continue;
                try {
                    $db->exec($query);
                } catch (Exception $e) {
                    // Ignore errors for some commands that might fail on Aiven
                    if (strpos($query, 'SET time_zone') === false && strpos($query, 'START TRANSACTION') === false) {
                        echo "<p class='error'>WARN: Query failed: " . substr($query, 0, 50) . "... Error: " . $e->getMessage() . "</p>";
                    }
                }
            }
            echo "<p class='success'>✅ 2. Database structure imported.</p>";
        } else {
             echo "<p class='error'>❌ ERROR: 'config/database.sql' file not found!</p>";
             exit;
        }
    } else {
        echo "<p class='info'>ℹ️ 2. Tables already exist (Skipping import).</p>";
        
        // 1. Column migrations for input_aspirasi
        $cols = [
            'foto' => "ALTER TABLE input_aspirasi ADD COLUMN foto LONGTEXT DEFAULT NULL",
            'is_urgent' => "ALTER TABLE input_aspirasi ADD COLUMN is_urgent TINYINT(1) DEFAULT 0",
            'is_anonymous' => "ALTER TABLE input_aspirasi ADD COLUMN is_anonymous TINYINT(1) DEFAULT 0"
        ];
        foreach($cols as $col => $sql) {
            if (!columnExists($db, 'input_aspirasi', $col)) {
                try {
                    $db->exec($sql);
                    echo "<p class='success'>✅ Migration: Added '$col' column to input_aspirasi.</p>";
                } catch (Exception $e) {
                    echo "<p class='error'>❌ Migration failed for '$col': " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p class='info'>ℹ️ Column '$col' already exists.</p>";
            }
        }

        // 2. Migration for pengumuman table
        $stmt = $db->query("SHOW TABLES LIKE 'pengumuman'");
        if (!$stmt->fetchColumn()) {
            try {
                $db->exec("CREATE TABLE pengumuman (id INT AUTO_INCREMENT PRIMARY KEY, judul VARCHAR(255), isi TEXT, tgl_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
                echo "<p class='success'>✅ Migration: Created 'pengumuman' table.</p>";
            } catch (Exception $e) { echo "<p class='error'>❌ Create table pengumuman failed: " . $e->getMessage() . "</p>"; }
        } else {
            echo "<p class='info'>ℹ️ Table 'pengumuman' already exists.</p>";
        }

        // 3. Migration for siswa table (tgl_daftar)
        if (!columnExists($db, 'siswa', 'tgl_daftar')) {
            try {
                $db->exec("ALTER TABLE siswa ADD COLUMN tgl_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
                echo "<p class='success'>✅ Migration: Added 'tgl_daftar' column to siswa.</p>";
            } catch (Exception $e) { echo "<p class='error'>❌ Migration failed for 'tgl_daftar': " . $e->getMessage() . "</p>"; }
        } else {
            echo "<p class='info'>ℹ️ Column 'tgl_daftar' already exists.</p>";
        }

        // 4. Bimenfess Tables
        $stmt = $db->query("SHOW TABLES LIKE 'menfess'");
        if (!$stmt->fetchColumn()) {
            try {
                $db->exec("CREATE TABLE menfess (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    isi TEXT NOT NULL,
                    warna VARCHAR(20) DEFAULT '#ffffff',
                    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                    nisn CHAR(10),
                    tgl_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )");
                echo "<p class='success'>✅ Migration: Created 'menfess' table.</p>";
            } catch (Exception $e) { echo "<p class='error'>❌ Create table menfess failed: " . $e->getMessage() . "</p>"; }
        } else {
            echo "<p class='info'>ℹ️ Table 'menfess' already exists.</p>";
        }

        $stmt = $db->query("SHOW TABLES LIKE 'menfess_likes'");
        if (!$stmt->fetchColumn()) {
            try {
                $db->exec("CREATE TABLE menfess_likes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    id_menfess INT,
                    nisn CHAR(10),
                    UNIQUE KEY (id_menfess, nisn)
                )");
                echo "<p class='success'>✅ Migration: Created 'menfess_likes' table.</p>";
            } catch (Exception $e) { echo "<p class='error'>❌ Create table menfess_likes failed: " . $e->getMessage() . "</p>"; }
        } else {
            echo "<p class='info'>ℹ️ Table 'menfess_likes' already exists.</p>";
        }
    }

    // 3. Sync Admin Accounts (from Localhost)
    $admins_to_sync = [
        ['admincalista', '202cb962ac59075b964b07152d234b70'],
        ['adminsopia', '202cb962ac59075b964b07152d234b70'],
        ['adminwiene', '202cb962ac59075b964b07152d234b70'],
        ['admin', '6ad14ba9986e3615423dfca256d04e3f'] // default ukk
    ];

    foreach ($admins_to_sync as $adm) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM admin WHERE username=?");
        $stmt->execute([$adm[0]]);
        if (!$stmt->fetchColumn()) {
            $stmt = $db->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
            $stmt->execute([$adm[0], $adm[1]]);
            echo "<p class='success'>✅ Admin '{$adm[0]}' synced.</p>";
        }
    }

    // 4. Sync Student Accounts (from Localhost)
    $siswas_to_sync = [
        ['12345', 'Budi Santoso', 'XII RPL 1', '202cb962ac59075b964b07152d234b70'],
        ['123456', 'Yanti Jubaedah', 'XII RPL 2', '202cb962ac59075b964b07152d234b70'],
        ['123457', 'Sadam Permana', 'X TKRO 1', '202cb962ac59075b964b07152d234b70']
    ];

    foreach ($siswas_to_sync as $s) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM siswa WHERE nisn=?");
        $stmt->execute([$s[0]]);
        if (!$stmt->fetchColumn()) {
            $stmt = $db->prepare("INSERT INTO siswa (nisn, nama, kelas, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$s[0], $s[1], $s[2], $s[3]]);
            echo "<p class='success'>✅ Siswa '{$s[1]}' synced.</p>";
        }
    }

    echo "<h2>🏆 Setup & Sync Complete!</h2>";
    echo "<p><a href='index.php'>Go to Login Page</a></p>";

} catch (PDOException $e) {
    echo "<h3 class='error'>❌ Setup Failed: " . $e->getMessage() . "</h3>";
    echo "<p>Check your Environment Variables in Vercel if this is a cloud deployment.</p>";
}
echo "<hr><p><small>Generated by Antigravity</small></p>";
