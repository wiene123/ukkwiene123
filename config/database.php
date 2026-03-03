<?php
// Config Database Connection
// Author: Antigravity
// Date: 2026-02-13

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
    
} catch (PDOException $e) {
    // Log error securely in production, simpler for UKK dev
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
