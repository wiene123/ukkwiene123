<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="row" style="display: flex; gap: 30px;">
    <!-- Detail Aspirasi -->
    <div style="flex: 2;">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Aspirasi #<?= $detail['id_aspirasi'] ?></h4>
                <a href="?page=admin_aspirasi" class="btn btn-sm btn-secondary">
                    <i data-feather="arrow-left" style="width:14px;"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 20px;">
                    <label style="color:#666; font-size:0.85rem;">Pelapor:</label>
                    <h5 style="font-size:1.1rem;">
                        <?php if ($detail['is_anonymous']): ?>
                            <span style="color: #666;">👤 ANONIM</span>
                        <?php else: ?>
                            <?= h($detail['nama_siswa']) ?> <span class="badge badge-secondary"><?= h($detail['kelas']) ?></span>
                        <?php endif; ?>
                    </h5>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="color:#666; font-size:0.85rem;">Kategori:</label>
                    <p><b><?= h($detail['nama_kategori']) ?></b></p>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="color:#666; font-size:0.85rem;">Tanggal:</label>
                    <p><?= time_ago($detail['tgl_input']) ?> (<?= date('d F Y, H:i', strtotime($detail['tgl_input'])) ?>)</p>
                    <?php if($detail['is_urgent']): ?>
                        <span class="badge" style="background: var(--danger); color: white;">🚨 URGENT</span>
                    <?php endif; ?>
                </div>

                <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; border-left: 4px solid var(--primary);">
                    <h5 style="margin-bottom: 10px; color: var(--primary);">Isi Laporan:</h5>
                    <p style="line-height: 1.6;"><?= nl2br(h($detail['isi_aspirasi'])) ?></p>
                </div>

                <?php if (!empty($detail['foto'])): ?>
                <div style="margin-top: 20px;">
                    <label style="color:#666; font-size:0.85rem; display: block; margin-bottom: 10px;">Lampiran Gambar:</label>
                    <img src="<?= (strpos($detail['foto'], 'data:image') === 0) ? $detail['foto'] : base_url($detail['foto']) ?>" alt="Lampiran Laporan" style="max-width: 100%; border-radius: 8px; border: 1px solid #ddd; max-height: 400px; object-fit: contain;">
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Form Feedback -->
    <div style="flex: 1;">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Tindak Lanjut</h4>
            </div>
            <div class="card-body">
                <form action="?page=admin_aspirasi_update&id=<?= $detail['id_aspirasi'] ?>" method="POST">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="menunggu" <?= $detail['status'] == 'menunggu' ? 'selected' : '' ?>>Menunggu</option>
                            <option value="proses" <?= $detail['status'] == 'proses' ? 'selected' : '' ?>>Proses</option>
                            <option value="selesai" <?= $detail['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Feedback / Tanggapan</label>
                        <textarea name="feedback" class="form-control" rows="5" placeholder="Berikan tanggapan untuk siswa..." required><?= h($detail['feedback']) ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i data-feather="save" style="width:16px;"></i> Simpan & Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
