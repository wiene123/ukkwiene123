<?php
// Role Helper: Badge and Role Formatting

// Define badges for roles
function get_role_badge($role) {
    if ($role === 'admin') {
        return '<span class="badge badge-admin">Admin</span>';
    }
    return '<span class="badge badge-siswa">Siswa</span>';
}

function get_role_class($role) {
    return $role === 'admin' ? 'badge-admin' : 'badge-siswa';
}

function get_role_label($role) {
    return $role === 'admin' ? 'Administrator' : 'Siswa';
}

// Format status complaint
function get_status_badge($status) {
    // waiting -> kuning, process -> biru, done -> hijau
    switch ($status) {
        case 'menunggu':
            return '<span class="status-badge status-waiting">Menunggu</span>';
        case 'proses':
            return '<span class="status-badge status-process">Proses</span>';
        case 'selesai':
            return '<span class="status-badge status-done">Selesai</span>';
        default:
            return '<span class="status-badge">Unknown</span>';
    }
}
