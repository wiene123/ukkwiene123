<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="header">
    <h2 style="margin-bottom: 25px;">Kirim Aspirasi Baru 📝</h2>
    <p style="color: #888; margin-top: -15px; margin-bottom: 30px;">Sampaikan aspirasi, keluhan, atau saran Anda dengan mengisi form di bawah ini.</p>
</div>

<div class="card" style="margin-top: 10px; max-width: 800px;">
    <div class="card-header">
        <h4 class="card-title">Form Aspirasi</h4>
    </div>
    <div class="card-body">
        
        <?php if(isset($_SESSION['flash'])): ?>
            <div class="alert alert-<?= $_SESSION['flash']['type'] == 'success' ? 'success' : 'danger' ?>">
                <?= $_SESSION['flash']['message'] ?>
            </div>
            <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <form action="<?= base_url('index.php?page=siswa_kirim') ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">Kategori Laporan</label>
                <select name="id_kategori" class="form-control" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                    <option value="">-- Pilih Kategori --</option>
                    <?php foreach ($kategori as $k): ?>
                        <option value="<?= $k['id_kategori'] ?>"><?= h($k['nama_kategori']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">Isi Aspirasi</label>
                <textarea name="isi_aspirasi" class="form-control" rows="8" placeholder="Tuliskan keluhan atau saran Anda di sini secara detail..." required style="width: 100%; padding: 15px; border-radius: 8px; border: 1px solid #ddd; font-family: inherit;"></textarea>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 600;">Lampiran Gambar (Opsional) 📷</label>
                <div style="position: relative; border: 2px dashed #ddd; border-radius: 8px; padding: 20px; text-align: center; cursor: pointer; transition: 0.3s;" id="upload-box">
                    <input type="file" name="foto" id="foto-input" class="form-control" accept="image/*" style="opacity: 0; position: absolute; top: 0; left: 0; width: 100%; height: 100%; cursor: pointer;">
                    <div id="upload-placeholder">
                        <i data-feather="upload-cloud" style="width: 48px; height: 48px; color: #888; margin-bottom: 10px;"></i>
                        <p style="margin: 0; color: #666; font-size: 0.9rem;">Klik atau seret gambar ke sini</p>
                    </div>
                    <img id="image-preview" src="" alt="Preview" style="max-width: 100%; max-height: 250px; display: none; margin: 0 auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                </div>
            </div>

            <script>
                const uploadBox = document.getElementById('upload-box');
                const fotoInput = document.getElementById('foto-input');
                const imagePreview = document.getElementById('image-preview');
                const uploadPlaceholder = document.getElementById('upload-placeholder');

                fotoInput.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                            uploadPlaceholder.style.display = 'none';
                            uploadBox.style.borderStyle = 'solid';
                            uploadBox.style.borderColor = 'var(--primary)';
                        }
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.src = '';
                        imagePreview.style.display = 'none';
                        uploadPlaceholder.style.display = 'block';
                        uploadBox.style.borderStyle = 'dashed';
                        uploadBox.style.borderColor = '#ddd';
                    }
                });
            </script>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; background: #fff5f5; padding: 15px; border-radius: 8px; border: 1px solid #fed7d7;">
                    <input type="checkbox" name="is_urgent" value="1" style="width: 20px; height: 20px; accent-color: #e53e3e;">
                    <div>
                        <strong style="color: #e53e3e; display: block;">Tandai sebagai URGENT 🚨</strong>
                        <span style="font-size: 0.85rem; color: #718096; font-weight: normal;">Hanya gunakan jika memerlukan penanganan segera.</span>
                    </div>
                </label>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; background: #f0f4ff; padding: 15px; border-radius: 8px; border: 1px solid #d1d9ff;">
                    <input type="checkbox" name="is_anonymous" value="1" style="width: 20px; height: 20px; accent-color: var(--primary);">
                    <div>
                        <strong style="color: var(--primary); display: block;">Kirim sebagai ANONIM 👤</strong>
                        <span style="font-size: 0.85rem; color: #718096; font-weight: normal;">Identitas Nama & Kelas Anda akan disembunyikan dari pihak sekolah/admin.</span>
                    </div>
                </label>
            </div>

            <div class="stack-mobile" style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary" style="padding: 12px 25px; border-radius: 8px; display: flex; align-items: center; gap: 8px;">
                    <i data-feather="send" style="width:18px;"></i> Kirim Laporan
                </button>
                <a href="<?= base_url('index.php?page=siswa_dashboard') ?>" class="btn btn-secondary" style="padding: 12px 25px; border-radius: 8px; background: #6c757d; color: white; text-decoration: none;">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
