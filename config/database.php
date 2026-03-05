<?php
// Config Database Connection
// Author: Antigravity
// Date: 2026-03-05 (Updated for Vercel & Aiven)

// Use environment variables if available (for Vercel/Aiven), otherwise use defaults
define('DB_HOST', getenv('MYSQLHOST') ?: '127.0.0.1');
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'ukk_wiene2');
define('DB_PORT', getenv('MYSQLPORT') ?: '3306');

try {
    $dsn = "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Handle SSL for Aiven or other managed DBs
    if (getenv('MYSQL_ATTR_SSL_CA')) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = getenv('MYSQL_ATTR_SSL_CA');
        // If providing the content of CA instead of file path, 
        // they might need to save it to a temp file first.
    }
    
    // Create connection
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
    
} catch (PDOException $e) {
    // Log error securely in production
    die("Database Connection Error: " . $e->getMessage());
}

// Global base URL helper
function base_url($path = '') {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $script = dirname($_SERVER['SCRIPT_NAME']);
    // Remove backslashes on Windows
    $script = str_replace('\\', '/', $script);
    // Ensure trailing slash
    $base = rtrim($protocol . "://" . $host . $script, '/');
    return $base . '/' . ltrim($path, '/');
}

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
