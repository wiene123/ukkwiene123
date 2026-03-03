<?php
// Auto Setup Script for UKK Wiene2
// run this file: http://localhost/ukk_wiene2/setup.php

echo "<h1>UKK Wiene2 Auto-Setup</h1>";

$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP password is empty

try {
    // 1. Connect to MySQL Server (no DB selected)
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color:green;'>1. Connected to MySQL Server.</p>";

    // 2. Check Database Existence
    $db_name = 'ukk_wiene2';
    $stmt = $pdo->query("SELECT COUNT(*) FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$db_name'");
    if (!$stmt->fetchColumn()) {
        echo "<p>Database '$db_name' not found. Creating...</p>";
        $pdo->exec("CREATE DATABASE `$db_name`");
        echo "<p style='color:green;'>2. Database '$db_name' created.</p>";
    } else {
        echo "<p style='color:blue;'>2. Database '$db_name' already exists.</p>";
    }

    // 3. Select Database
    $pdo->exec("USE `$db_name`");
    
    // 4. Import SQL if tables missing
    $stmt = $pdo->query("SHOW TABLES LIKE 'admin'");
    if (!$stmt->fetchColumn()) {
        echo "<p>Tables missing. Importing structure...</p>";
        
        $sql_file = __DIR__ . '/config/database.sql';
        if (file_exists($sql_file)) {
            $sql = file_get_contents($sql_file);
            // Split SQL file by semicolons mostly works for simple dumps
            // But better to execute as whole block if possible or use multiple queries
            $pdo->exec($sql);
            echo "<p style='color:green;'>3. Database structure imported from 'config/database.sql'.</p>";
        } else {
             echo "<p style='color:red;'>ERROR: 'config/database.sql' file not found!</p>";
             exit;
        }
    } else {
        echo "<p style='color:blue;'>3. Tables already exist (Skipping import).</p>";
    }

    // 5. Verify Admin Account
    $stmt = $pdo->query("SELECT COUNT(*) FROM admin WHERE username='admin'");
    if (!$stmt->fetchColumn()) {
        $pass = md5('user123');
        $pdo->exec("INSERT INTO admin (username, password) VALUES ('admin', '$pass')");
        echo "<p style='color:green;'>4. Default Admin account created (admin / user123).</p>";
    } else {
        echo "<p style='color:blue;'>4. Admin account exists.</p>";
    }

    echo "<h3>Setup Complete!</h3>";
    echo "<p><a href='index.php'>Go to Landing Page</a></p>";

} catch (PDOException $e) {
    echo "<h3 style='color:red;'>Setup Failed: " . $e->getMessage() . "</h3>";
    echo "<p>Make sure XAMPP (Apache & MySQL) is running and password for 'root' is empty.</p>";
}
