<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="header" style="margin-bottom: 30px;">
    <h2 style="margin: 0; font-size: 1.8rem; color: #333;">Moderasi Bimenfess 🛡️</h2>
    <p style="color: #666; margin: 5px 0 0;">Verifikasi curhatan siswa sebelum ditampilkan di dinding publik.</p>
</div>

<?php if(isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] == 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 30px;">
        <?= $_SESSION['flash']['message'] ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Antrian Moderasi (Pending)</h4>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 80px;">No</th>
                        <th style="width: 150px;">Tanggal</th>
                        <th>Isi Curhatan</th>
                        <th style="width: 100px;">Warna</th>
                        <th style="width: 200px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($pending)): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 50px; color: #999;">
                                <i data-feather="check-circle" style="width: 48px; height: 48px; margin-bottom: 10px; opacity: 0.3;"></i>
                                <p>Tidak ada curhatan yang menunggu moderasi. Kerja bagus!</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($pending as $index => $p): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td>
                                    <span style="font-size: 0.85rem; color: #666;">
                                        <?= date('d/m/Y', strtotime($p['tgl_input'])) ?>
                                        <br><?= date('H:i', strtotime($p['tgl_input'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="max-width: 500px; word-wrap: break-word; line-height: 1.5;">
                                        <?= nl2br(h($p['isi'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <div style="width: 25px; height: 25px; border-radius: 4px; border: 1px solid #ddd; background: <?= $p['warna'] ?>;"></div>
                                </td>
                                <td>
                                    <div style="display: flex; gap: 10px; justify-content: center;">
                                        <a href="<?= base_url('index.php?page=admin_menfess_moderate&action=approve&id='.$p['id']) ?>" 
                                           class="btn btn-success" style="padding: 5px 15px; font-size: 0.8rem; display: flex; align-items: center; gap: 5px;">
                                            <i data-feather="check" style="width: 14px;"></i> Setujui
                                        </a>
                                        <a href="<?= base_url('index.php?page=admin_menfess_moderate&action=reject&id='.$p['id']) ?>" 
                                           class="btn btn-danger" style="padding: 5px 15px; font-size: 0.8rem; display: flex; align-items: center; gap: 5px;"
                                           onclick="return confirm('Yakin ingin menolak curhatan ini?')">
                                            <i data-feather="x" style="width: 14px;"></i> Tolak
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.table th { background: #f8fafc; color: #64748b; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.05em; }
.btn-success { background: #10b981; border: none; }
.btn-success:hover { background: #059669; }
</style>

<?php require_once 'views/layouts/footer.php'; ?>
