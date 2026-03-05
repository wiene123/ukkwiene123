<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="header">
    <h2 style="margin-bottom: 25px;">Riwayat Laporan Saya</h2>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Daftar Laporan yang Pernah Dikirim</h4>
    </div>
    <div class="card-body">
        
        <?php if(isset($history)): ?>
            <?php foreach($history as $h): ?>
                <div class="card" style="box-shadow: none; border: 1px solid #eee; margin-bottom: 15px;">
                    <div class="card-header stack-mobile" style="border-bottom: none; display: flex; justify-content: space-between; align-items: start;">
                        <div>
                            <span class="badge badge-secondary" style="margin-bottom: 5px;"><?= h($h['nama_kategori']) ?></span>
                            <h5 style="margin: 0; font-size: 1rem; color: #333; line-height: 1.4;">
                                <?= substr(h($h['isi_aspirasi']), 0, 100) . '...' ?>
                            </h5>
                            <small style="color: #999; margin-top: 5px; display: block;">
                                <i data-feather="calendar" style="width: 12px;"></i> <?= date('d M Y, H:i', strtotime($h['tgl_input'])) ?>
                            </small>
                        </div>
                        <div style="text-align: right;">
                            <?= get_status_badge($h['status']) ?>
                            <br>
                            <a href="?page=siswa_detail&id=<?= $h['id_aspirasi'] ?>" class="btn btn-sm btn-secondary" style="margin-top: 10px;">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p style="color: #888; text-align: center;">Belum ada riwayat laporan.</p>
        <?php endif; ?>

    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
