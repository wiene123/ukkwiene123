<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="header" style="margin-bottom: 30px;">
    <h2>Manajemen Kategori 📂</h2>
    <p style="color: #888;">Kelola kategori pengaduan untuk memudahkan pengelompokan laporan.</p>
</div>

<!-- Flash Message -->
<?php if(isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] == 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 25px;">
        <?= $_SESSION['flash']['message'] ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="row" style="display: flex; gap: 25px;">
    <!-- Form Tambah -->
    <div style="flex: 1; min-width: 300px;">
        <div class="card" style="border-top: 5px solid var(--primary);">
            <div class="card-header">
                <h4 class="card-title">Tambah Kategori Baru</h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('index.php?page=admin_kategori_store') ?>" method="POST">
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Sarana & Prasarana" required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i data-feather="plus-circle" style="width:18px;"></i> Simpan Kategori
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Data -->
    <div style="flex: 2; min-width: 400px;">
        <div class="card">
            <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                <h4 class="card-title">Daftar Kategori Tersedia</h4>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="10%">No</th>
                            <th>Nama Kategori</th>
                            <th width="25%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><strong><?= h($row['nama_kategori']) ?></strong></td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <button onclick='editKategori(<?= json_encode($row) ?>)' class="btn btn-sm btn-info" style="background: #42a5f5; color: white; border: none; padding: 6px 10px; border-radius: 4px; display: inline-flex; align-items: center; gap: 5px;">
                                        <i data-feather="edit-2" style="width:14px;"></i> Edit
                                    </button>
                                    <a href="<?= base_url('index.php?page=admin_kategori_delete&id=' . $row['id_kategori']) ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Yakin hapus kategori ini? Seluruh laporan terkait kategori ini mungkin akan terpengaruh.')"
                                       style="background: var(--danger); color: white; border: none; padding: 6px 10px; border-radius: 4px; display: inline-flex; align-items: center; gap: 5px;">
                                        <i data-feather="trash-2" style="width:14px;"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php if(empty($data)): ?>
                            <tr>
                                <td colspan="3" style="text-align: center; color: #888; padding: 20px;">Belum ada kategori data.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Kategori Modal -->
<div id="editKategoriModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
    <div class="card" style="width: 90%; max-width: 450px; margin: auto;">
        <div class="card-header">
            <h4 class="card-title">Edit Nama Kategori</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('index.php?page=admin_kategori_update') ?>" method="POST">
                <input type="hidden" name="id_kategori" id="edit_id_kategori">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label>Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control" required>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="button" onclick="closeModal()" class="btn btn-secondary" style="background: #6c757d; color: white;">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editKategori(data) {
    document.getElementById('edit_id_kategori').value = data.id_kategori;
    document.getElementById('edit_nama_kategori').value = data.nama_kategori;
    document.getElementById('editKategoriModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('editKategoriModal').style.display = 'none';
}

// Close modal if clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('editKategoriModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>
