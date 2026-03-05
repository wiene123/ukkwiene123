<?php
// Auth Helper: Security & Session Management

// Check if user is logged in
function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . base_url('index.php?page=login'));
        exit;
    }
}

// Check role authorization
function check_role($allowed_roles) {
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        // Log unauthorized access attempt?
        header('Location: ' . base_url('index.php?page=unauthorized'));
        exit;
    }
}

// Redirect if already logged in (for login page)
function redirect_if_logged_in() {
    if (isset($_SESSION['user_id'])) {
        if ($_SESSION['role'] === 'admin') {
            header('Location: ' . base_url('index.php?page=admin_dashboard'));
        } else {
            header('Location: ' . base_url('index.php?page=siswa_dashboard'));
        }
        exit;
    }
}

// Secure hash function (MD5 as requested by UKK spec, otherwise use password_hash)
function hash_password($password) {
    return md5($password);
}

// Sanitize output to prevent XSS
function h($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}
