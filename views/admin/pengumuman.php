<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="row" style="display: flex; gap: 30px; flex-wrap: wrap;">
    <!-- Form Tambah -->
    <div style="flex: 1; min-width: 350px;">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Terbitkan Pengumuman Baru 📢</h4>
            </div>
            <div class="card-body">
                <form action="index.php?page=admin_pengumuman_store" method="POST">
                    <div class="form-group">
                        <label>Judul Pengumuman</label>
                        <input type="text" name="judul" class="form-control" placeholder="Contoh: Jadwal Perbaikan Fasilitas" required>
                    </div>
                    <div class="form-group">
                        <label>Isi Pengumuman</label>
                        <textarea name="isi" class="form-control" rows="6" placeholder="Tuliskan detail pengumuman di sini..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i data-feather="send" style="width:16px;"></i> Terbitkan Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Daftar Pengumuman -->
    <div style="flex: 2; min-width: 450px;">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Riwayat Pengumuman</h4>
            </div>
            <div class="card-body" style="padding: 0;">
                <?php if(!empty($data)): ?>
                    <div style="display: flex; flex-direction: column;">
                        <?php foreach($data as $p): ?>
                        <div style="padding: 20px; border-bottom: 1px solid #f5f5f5; display: flex; justify-content: space-between; align-items: flex-start;">
                            <div>
                                <h5 style="margin: 0 0 8px 0; color: var(--primary); font-size: 1.1rem;"><?= h($p['judul']) ?></h5>
                                <p style="margin: 0; color: #666; font-size: 0.9rem; line-height: 1.6;"><?= nl2br(h($p['isi'])) ?></p>
                                <span style="display: block; margin-top: 10px; font-size: 0.75rem; color: #aaa;">Diterbitkan: <?= date('d M Y, H:i', strtotime($p['tgl_input'])) ?> (<?= time_ago($p['tgl_input']) ?>)</span>
                            </div>
                            <a href="index.php?page=admin_pengumuman_delete&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus pengumuman ini?')">
                                <i data-feather="trash-2" style="width: 14px;"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div style="padding: 50px; text-align: center; color: #ccc;">
                        <i data-feather="bell-off" style="width: 48px; height: 48px; margin-bottom: 15px; opacity: 0.3;"></i>
                        <p>Belum ada pengumuman yang diterbitkan.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
