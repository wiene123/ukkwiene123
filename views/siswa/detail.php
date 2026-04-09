<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="row" style="display: flex; gap: 30px;">
    <!-- Detail Aspirasi -->
    <div style="flex: 2;">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Detail Aspirasi</h4>
                <a href="?page=siswa_riwayat" class="btn btn-sm btn-secondary">
                    <i data-feather="arrow-left" style="width:14px;"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 20px;">
                    <label style="color:#666; font-size:0.85rem;">Status Laporan:</label>
                    <div style="margin-top: 5px;">
                        <?= get_status_badge($detail['status']) ?>
                        <?php if($detail['is_urgent']): ?>
                            <span class="badge" style="background: var(--danger); color: white; margin-left:10px;">🚨 URGENT</span>
                        <?php endif; ?>
                        <span style="margin-left: 10px; color: #888; font-size: 0.9rem;">
                            Terakhir diupdate: <?= $detail['tgl_feedback'] ? time_ago($detail['tgl_feedback']) : time_ago($detail['tgl_input']) ?>
                        </span>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="color:#666; font-size:0.85rem;">Kategori:</label>
                    <p><b><?= h($detail['nama_kategori']) ?></b></p>
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

                <?php if ($detail['feedback']): ?>
                <div style="margin-top: 30px; background: #e8f5e9; padding: 20px; border-radius: 8px; border-top: 4px solid #66bb6a;">
                    <h5 style="margin-bottom: 10px; color: #2e7d32;"> <i data-feather="message-circle" style="width: 16px;"></i> Tanggapan Petugas:</h5>
                    <p style="line-height: 1.6; color: #333;"><?= nl2br(h($detail['feedback'])) ?></p>
                </div>
                <?php else: ?>
                <div style="margin-top: 30px; padding: 20px; background: #fff3e0; border-radius: 8px; color: #ef6c00;">
                    <i data-feather="clock" style="width: 16px;"></i> Belum ada tanggapan dari petugas. Mohon menunggu.
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
