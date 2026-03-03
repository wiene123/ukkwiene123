<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <h2>Manajemen Pengguna 👥</h2>
        <p style="color: #888;">Kelola data administrator dan siswa sistem.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <button onclick="toggleForm('addAdminForm')" class="btn btn-primary" style="display: flex; align-items: center; gap: 8px;">
            <i data-feather="shield" style="width: 18px;"></i> Tambah Admin
        </button>
        <button onclick="toggleForm('addUserForm')" class="btn btn-primary" style="display: flex; align-items: center; gap: 8px;">
            <i data-feather="user-plus" style="width: 18px;"></i> Tambah Siswa
        </button>
    </div>
</div>

<!-- Flash Message -->
<?php if(isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] == 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 25px;">
        <?= $_SESSION['flash']['message'] ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Form Tambah Admin (Hidden by default) -->
<div id="addAdminForm" class="card" style="display: none; margin-bottom: 30px; border-top: 5px solid #4b5563;">
    <div class="card-header">
        <h4 class="card-title">Tambah Administrator Baru</h4>
    </div>
    <div class="card-body">
        <form action="<?= base_url('index.php?page=admin_store') ?>" method="POST">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control" placeholder="Username Admin" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password Admin" required>
                </div>
            </div>
            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Simpan Admin</button>
                <button type="button" onclick="toggleForm('addAdminForm')" class="btn btn-light" style="background: #e5e7eb;">Batal</button>
            </div>
        </form>
    </div>
</div>

<!-- Form Tambah Siswa (Hidden by default) -->
<div id="addUserForm" class="card" style="display: none; margin-bottom: 30px; border-top: 5px solid var(--primary);">
    <div class="card-header">
        <h4 class="card-title">Tambah Siswa Baru</h4>
    </div>
    <div class="card-body">
        <form action="<?= base_url('index.php?page=admin_user_store') ?>" method="POST">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div class="form-group">
                    <label>NISN</label>
                    <input type="text" name="nisn" class="form-control" placeholder="Masukkan NISN" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                </div>
                <div class="form-group">
                    <label>Kelas</label>
                    <input type="text" name="kelas" class="form-control" placeholder="Contoh: XII RPL 1" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
            </div>
            <div style="margin-top: 20px; display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Simpan Siswa</button>
                <button type="button" onclick="toggleForm('addUserForm')" class="btn btn-light" style="background: #e5e7eb;">Batal</button>
            </div>
        </form>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr; gap: 30px;">
    <!-- Section Admin -->
    <div class="card">
        <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <h4 class="card-title" style="color: #4b5563;">Daftar Administrator</h4>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $ad): ?>
                    <tr>
                        <td><strong><?= h($ad['username']) ?></strong></td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button onclick='editAdmin(<?= json_encode($ad) ?>)' class="btn btn-sm btn-info" title="Edit" style="background: #42a5f5; color: white; border: none; padding: 5px 8px; border-radius: 4px;">
                                    <i data-feather="edit-2" style="width: 14px;"></i>
                                </button>
                                <?php if ($ad['username'] !== 'admin'): ?>
                                    <a href="<?= base_url('index.php?page=admin_delete&id=' . $ad['username']) ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Apakah Anda yakin ingin menghapus admin ini?')"
                                       style="background: #ef4444; color: white; border: none; padding: 5px 8px; border-radius: 4px; display: inline-flex; align-items: center;">
                                        <i data-feather="trash-2" style="width: 14px;"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="badge" style="background: #e2e8f0; color: #94a3b8; font-size: 0.7rem; padding: 4px 8px;">Primary</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section Siswa -->
    <div class="card">
        <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
            <h4 class="card-title" style="color: #4b5563;">Daftar Siswa</h4>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><strong><?= h($u['nisn']) ?></strong></td>
                        <td><?= h($u['nama']) ?></td>
                        <td><?= h($u['kelas']) ?></td>
                        <td>
                            <div style="display: flex; gap: 5px;">
                                <button onclick='editUser(<?= json_encode($u) ?>)' class="btn btn-sm btn-info" title="Edit" style="background: #42a5f5; color: white; border: none; padding: 5px 8px; border-radius: 4px;">
                                    <i data-feather="edit-2" style="width: 14px;"></i>
                                </button>
                                <a href="<?= base_url('index.php?page=admin_user_delete&nisn=' . $u['nisn']) ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')"
                                   title="Hapus" 
                                   style="background: var(--danger); color: white; border: none; padding: 5px 8px; border-radius: 4px; display: flex; align-items: center;">
                                    <i data-feather="trash-2" style="width: 14px;"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Admin Modal -->
<div id="editAdminModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="card" style="width: 90%; max-width: 500px; margin: auto;">
        <div class="card-header">
            <h4 class="card-title">Edit Administrator</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('index.php?page=admin_update') ?>" method="POST">
                <input type="hidden" name="old_username" id="edit_admin_id">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Username</label>
                    <input type="text" name="username" id="edit_admin_username" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Ganti Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control" placeholder="Password Baru">
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal('editAdminModal')" class="btn btn-secondary" style="background: #6c757d; color: white;">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Admin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="card" style="width: 90%; max-width: 600px; margin: auto;">
        <div class="card-header">
            <h4 class="card-title">Edit Pengguna</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('index.php?page=admin_user_update') ?>" method="POST">
                <input type="hidden" name="nisn" id="edit_nisn">
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" id="edit_nama" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label>Kelas</label>
                    <input type="text" name="kelas" id="edit_kelas" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Ganti Password (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="form-control" placeholder="Password Baru">
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal('editModal')" class="btn btn-secondary" style="background: #6c757d; color: white;">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleForm(formId) {
    const form = document.getElementById(formId);
    const otherFormId = formId === 'addAdminForm' ? 'addUserForm' : 'addAdminForm';
    const otherForm = document.getElementById(otherFormId);
    
    otherForm.style.display = 'none';
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
    
    if (form.style.display === 'block') {
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

function editAdmin(admin) {
    document.getElementById('edit_admin_id').value = admin.username;
    document.getElementById('edit_admin_username').value = admin.username;
    document.getElementById('editAdminModal').style.display = 'flex';
}

function editUser(user) {
    document.getElementById('edit_nisn').value = user.nisn;
    document.getElementById('edit_nama').value = user.nama;
    document.getElementById('edit_kelas').value = user.kelas;
    document.getElementById('editModal').style.display = 'flex';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.id === 'editModal') closeModal('editModal');
    if (event.target.id === 'editAdminModal') closeModal('editAdminModal');
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>
