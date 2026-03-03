<?php
// Simple Diagnostic Script
// Author: Antigravity

echo "<h2>Diagnostic Check</h2>";

// 1. Check PHP Version
echo "PHP Version: " . phpversion() . "<br>";

// 2. Check Database Connection
echo "Checking Database Connection...<br>";

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ukk_wiene2');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    // Create connection
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "<strong style='color:green;'>SUCCESS: Database Connected!</strong><br>";
    
    // Check tables existed
    $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables found: " . implode(", ", $tables) . "<br>";

} catch (PDOException $e) {
    echo "<strong style='color:red;'>ERROR: Database Connection Failed!</strong><br>";
    echo "Message: " . $e->getMessage() . "<br>";
}

// 3. Check Base URL Idea
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script = dirname($_SERVER['SCRIPT_NAME']);
$script = str_replace('\\', '/', $script);
$base = rtrim($protocol . "://" . $host . $script, '/');
echo "Calculated Base URL: " . $base . "<br>";

echo "<br><a href='index.php'>Go to Index</a>";
