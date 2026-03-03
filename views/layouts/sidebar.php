<?php
// Sidebar layout
require_once 'views/layouts/header.php';
$is_admin = ($_SESSION['role'] === 'admin'); // Simplified role check
?>

<div class="wrapper">
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i data-feather="monitor"></i>
            <span>SIPESKU</span>
        </div>
    </div>
    <ul class="sidebar-nav">
        <?php $page = $_GET['page'] ?? 'siswa_dashboard'; ?>
        <!-- SISWA MENU -->
        <?php if ($_SESSION['role'] == 'siswa'): ?>
            <li class="sidebar-label">SISWA</li>
            <li class="<?= ($page == 'siswa_dashboard') ? 'active' : '' ?>">
                <a href="<?= base_url('index.php?page=siswa_dashboard') ?>"><i data-feather="home"></i> Dashboard</a>
            </li>
            <li class="<?= ($page == 'kirim_aspirasi' || $page == 'siswa_kirim') ? 'active' : '' ?>">
                <a href="<?= base_url('index.php?page=kirim_aspirasi') ?>"><i data-feather="send"></i> Kirim Aspirasi</a>
            </li>
            <li class="<?= ($page == 'riwayat' || $page == 'siswa_riwayat' || $page == 'siswa_detail') ? 'active' : '' ?>">
                <a href="<?= base_url('index.php?page=riwayat') ?>"><i data-feather="clock"></i> Riwayat</a>
            </li>
        <?php endif; ?>

        <!-- ADMIN MENU -->
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <li class="sidebar-label">ADMIN</li>
            <li class="<?= ($page == 'admin_dashboard') ? 'active' : '' ?>">
                <a href="<?= base_url('index.php?page=admin_dashboard') ?>"><i data-feather="activity"></i> Dashboard</a>
            </li>
            <li class="<?= ($page == 'admin_kategori') ? 'active' : '' ?>">
                <a href="<?= base_url('index.php?page=admin_kategori') ?>"><i data-feather="tag"></i> Kategori</a>
            </li>
            <li class="<?= ($page == 'admin_aspirasi' || $page == 'admin_aspirasi_detail') ? 'active' : '' ?>">
                <a href="<?= base_url('index.php?page=admin_aspirasi') ?>"><i data-feather="message-square"></i> Aspirasi</a>
            </li>
            <li class="<?= ($page == 'admin_users') ? 'active' : '' ?>">
                <a href="<?= base_url('index.php?page=admin_users') ?>"><i data-feather="users"></i> Pengguna</a>
            </li>
        <?php endif; ?>

        <li class="sidebar-label">AKUN</li>
        <li><a href="<?= base_url('index.php?page=logout') ?>" class="text-danger"><i data-feather="log-out"></i> Logout</a></li>
    </ul>
</aside>

<div class="main-content">
    <header class="topbar">
        <div class="toggle-sidebar">
            <i data-feather="menu"></i>
        </div>
        <div class="user-info">
            <span class="user-role"><?= $_SESSION['role'] == 'admin' ? 'Administrator' : 'Siswa'; ?></span>
            <span class="user-name"><?= $_SESSION['nama'] ?? $_SESSION['username']; ?></span>
        </div>
    </header>
    <div class="content">
