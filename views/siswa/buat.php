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

        <form action="<?= base_url('index.php?page=siswa_kirim') ?>" method="POST">
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
