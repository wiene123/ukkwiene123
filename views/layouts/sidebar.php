<?php
// Sidebar layout
require_once 'views/layouts/header.php';
$is_admin = ($_SESSION['role'] === 'admin'); // Simplified role check
?>

<div class="wrapper">
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>
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
            <li class="<?= ($page == 'admin_pengumuman') ? 'active' : '' ?>">
                <a href="<?= base_url('index.php?page=admin_pengumuman') ?>"><i data-feather="bell"></i> Pengumuman</a>
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
        
        <div style="display: flex; align-items: center; gap: 20px;">
            <!-- Notif Bell -->
            <div class="notif-wrapper" style="position: relative; cursor: pointer;">
                <div id="notif-bell" class="toggle-sidebar" style="box-shadow: none; border: 1px solid #f0f0f5;">
                    <i data-feather="bell"></i>
                    <span id="notif-badge" style="position: absolute; top: -5px; right: -5px; background: var(--danger); color: white; border-radius: 50%; width: 18px; height: 18px; font-size: 10px; display: none; align-items: center; justify-content: center; font-weight: 800; border: 2px solid white;">0</span>
                </div>
                
                <!-- Notif Dropdown -->
                <div id="notif-dropdown" class="card" style="position: absolute; top: 60px; right: 0; width: 320px; z-index: 1000; padding: 0; display: none; animation: slideIn 0.3s ease; border: 1px solid #eee; overflow: hidden;">
                    <div style="padding: 15px 20px; border-bottom: 1px solid #f5f5f5; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                        <h5 style="margin:0; font-size: 0.9rem;">Notifikasi</h5>
                        <span style="font-size: 0.75rem; color: var(--primary); font-weight: 700;">Tandai baca</span>
                    </div>
                    <div id="notif-list" style="max-height: 400px; overflow-y: auto;">
                        <div style="padding: 30px; text-align: center; color: #999; font-size: 0.85rem;">
                            Tidak ada notifikasi baru.
                        </div>
                    </div>
                    <div style="padding: 12px; text-align: center; border-top: 1px solid #f5f5f5;">
                        <a href="#" style="font-size: 0.8rem; color: #888;">Lihat semua</a>
                    </div>
                </div>
            </div>

            <div class="user-info">
                <span class="user-role"><?= $_SESSION['role'] == 'admin' ? 'Administrator' : 'Siswa'; ?></span>
                <span class="user-name"><?= $_SESSION['nama'] ?? $_SESSION['username']; ?></span>
            </div>
        </div>
    </header>

    <style>
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const bell = document.getElementById('notif-bell');
        const dropdown = document.getElementById('notif-dropdown');
        
        bell.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function() {
            dropdown.style.display = 'none';
        });

        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Simple logic to fetch notifications (Announcements & Recent responses)
        async function fetchNotifs() {
            try {
                const response = await fetch('index.php?page=api_notif');
                const data = await response.json();
                const list = document.getElementById('notif-list');
                const badge = document.getElementById('notif-badge');
                
                if (data.length > 0) {
                    badge.innerText = data.length;
                    badge.style.display = 'flex';
                    list.innerHTML = '';
                    data.forEach(n => {
                        list.innerHTML += `
                            <div style="padding: 15px 20px; border-bottom: 1px solid #f8f8f8; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fcfcff'" onmouseout="this.style.background='transparent'">
                                <div style="display: flex; gap: 12px;">
                                    <div style="background: ${n.type === 'urgent' ? 'var(--danger)' : n.type === 'pengumuman' ? 'var(--primary)' : '#e0e0e0'}; width: 8px; height: 8px; border-radius: 50%; margin-top: 5px; flex-shrink: 0;"></div>
                                    <div>
                                        <p style="margin: 0; font-size: 0.85rem; font-weight: 700; color: #333;">${n.title}</p>
                                        <p style="margin: 4px 0; font-size: 0.8rem; color: #777; line-height: 1.4;">${n.message}</p>
                                        <span style="font-size: 0.7rem; color: #bbb;">${n.time}</span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                }
            } catch (err) {}
        }
        
        // Initial fetch
        fetchNotifs();
    });
    </script>
    <div class="content">
