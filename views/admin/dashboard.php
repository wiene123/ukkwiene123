<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="header">
    <h2 style="margin-bottom: 25px;">Dashboard Admin</h2>
</div>

<!-- Stats -->
<div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <!-- Card Total -->
    <div class="card" style="display: flex; gap: 20px; align-items: center; border-left: 5px solid #6c757d;">
        <div style="background: #e9ecef; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #6c757d;">
            <i data-feather="file-text" style="width: 30px; height: 30px;"></i>
        </div>
        <div>
            <h4 style="color: #666; font-size: 0.9rem;">Total Laporan</h4>
            <span style="font-size: 1.8rem; font-weight: 700; color: #333;"><?= $stats['total'] ?></span>
        </div>
    </div>

    <!-- Card Menunggu -->
    <div class="card" style="display: flex; gap: 20px; align-items: center; border-left: 5px solid #FBC02D;">
        <div style="background: #FFFde7; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #FBC02D;">
            <i data-feather="clock" style="width: 30px; height: 30px;"></i>
        </div>
        <div>
            <h4 style="color: #666; font-size: 0.9rem;">Menunggu</h4>
            <span style="font-size: 1.8rem; font-weight: 700; color: #333;"><?= $stats['menunggu'] ?></span>
        </div>
    </div>

    <!-- Card Proses -->
    <div class="card" style="display: flex; gap: 20px; align-items: center; border-left: 5px solid #1976D2;">
        <div style="background: #E3F2FD; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #1976D2;">
            <i data-feather="settings" style="width: 30px; height: 30px;"></i>
        </div>
        <div>
            <h4 style="color: #666; font-size: 0.9rem;">Proses</h4>
            <span style="font-size: 1.8rem; font-weight: 700; color: #333;"><?= $stats['proses'] ?></span>
        </div>
    </div>

    <!-- Card Selesai -->
    <div class="card" style="display: flex; gap: 20px; align-items: center; border-left: 5px solid #388E3C;">
        <div style="background: #E8F5E9; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #388E3C;">
            <i data-feather="check-circle" style="width: 30px; height: 30px;"></i>
        </div>
        <div>
            <h4 style="color: #666; font-size: 0.9rem;">Selesai</h4>
            <span style="font-size: 1.8rem; font-weight: 700; color: #333;"><?= $stats['selesai'] ?></span>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card">
    <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <h4 class="card-title">Aktivitas Terbaru 🔔</h4>
        <a href="<?= base_url('index.php?page=admin_aspirasi') ?>" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">Lihat Semua</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if(!empty($recent_activity)): ?>
            <div style="display: flex; flex-direction: column;">
                <?php foreach($recent_activity as $act): ?>
                <div class="stack-mobile" style="padding: 15px 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="background: #f1f5f9; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b;">
                            <i data-feather="user" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h5 style="margin: 0; font-size: 0.95rem; color: #334155;"><?= h($act['nama_siswa']) ?> <span style="font-weight: 400; color: #64748b; font-size: 0.85rem;">mengirim laporan</span></h5>
                            <p style="margin: 3px 0 0; font-size: 0.85rem; color: #64748b; font-style: italic;">"<?= h(substr($act['isi_aspirasi'], 0, 80)) ?>..."</p>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 5px;"><?= date('d M, H:i', strtotime($act['tgl_input'])) ?></div>
                        <?= get_status_badge($act['status']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="padding: 40px; text-align: center; color: #94a3b8;">
                <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 10px; opacity: 0.5;"></i>
                <p>Belum ada laporan masuk saat ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
