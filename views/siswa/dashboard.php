<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="header">
    <h2 style="margin-bottom: 25px;">Halo, <?= h($_SESSION['nama']) ?> 👋</h2>
    <p style="color: #888; margin-top: -15px; margin-bottom: 30px;">Selamat datang di dashboard pelaporan siswa.</p>
</div>

<!-- Stats for Student -->
<div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px;">
    <!-- Total Laporan -->
    <div class="card" style="display: flex; gap: 20px; align-items: center; border-left: 5px solid #6c757d; padding: 20px;">
        <div style="background: #e9ecef; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #6c757d;">
            <i data-feather="file-text" style="width: 24px; height: 24px;"></i>
        </div>
        <div>
            <h4 style="color: #666; font-size: 0.85rem; margin-bottom: 5px;">Total Laporan Saya</h4>
            <span style="font-size: 1.5rem; font-weight: 800; color: #333;"><?= $stats['total'] ?? 0 ?></span>
        </div>
    </div>

    <!-- Menunggu -->
    <div class="card" style="display: flex; gap: 20px; align-items: center; border-left: 5px solid var(--warning); padding: 20px;">
        <div style="background: #fff8e1; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--warning);">
            <i data-feather="clock" style="width: 24px; height: 24px;"></i>
        </div>
        <div>
            <h4 style="color: #666; font-size: 0.85rem; margin-bottom: 5px;">Menunggu</h4>
            <span style="font-size: 1.5rem; font-weight: 800; color: #333;"><?= $stats['menunggu'] ?? 0 ?></span>
        </div>
    </div>

    <!-- Proses -->
    <div class="card" style="display: flex; gap: 20px; align-items: center; border-left: 5px solid #42a5f5; padding: 20px;">
        <div style="background: #e3f2fd; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #42a5f5;">
            <i data-feather="settings" style="width: 24px; height: 24px;"></i>
        </div>
        <div>
            <h4 style="color: #666; font-size: 0.85rem; margin-bottom: 5px;">Proses</h4>
            <span style="font-size: 1.5rem; font-weight: 800; color: #333;"><?= $stats['proses'] ?? 0 ?></span>
        </div>
    </div>

    <!-- Selesai -->
    <div class="card" style="display: flex; gap: 20px; align-items: center; border-left: 5px solid var(--success); padding: 20px;">
        <div style="background: #e8f5e9; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--success);">
            <i data-feather="check-circle" style="width: 24px; height: 24px;"></i>
        </div>
        <div>
            <h4 style="color: #666; font-size: 0.85rem; margin-bottom: 5px;">Selesai</h4>
            <span style="font-size: 1.5rem; font-weight: 800; color: #333;"><?= $stats['selesai'] ?? 0 ?></span>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="card" style="margin-top: 30px;">
    <div class="card-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <h4 class="card-title">Aktivitas Terbaru Saya 🔔</h4>
        <a href="<?= base_url('index.php?page=siswa_riwayat') ?>" style="font-size: 0.85rem; color: var(--primary); text-decoration: none; font-weight: 600;">Lihat Riwayat</a>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if(!empty($recent_activity)): ?>
            <div style="display: flex; flex-direction: column;">
                <?php foreach($recent_activity as $act): ?>
                <div class="stack-mobile" style="padding: 15px 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; gap: 15px; align-items: center;">
                        <div style="background: #f1f5f9; width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #64748b;">
                            <i data-feather="message-circle" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h5 style="margin: 0; font-size: 0.95rem; color: #334155;">
                                <?php if($act['is_urgent']): ?><span class="badge" style="background: var(--danger); color: white; padding: 1px 6px; font-size: 0.65rem; margin-right: 5px;">🚨 URGENT</span> <?php endif; ?>
                                Laporan kategori <span style="color: var(--primary);"><?= h($act['nama_kategori']) ?></span>
                            </h5>
                            <p style="margin: 3px 0 0; font-size: 0.85rem; color: #64748b; font-style: italic;">"<?= h(substr($act['isi_aspirasi'], 0, 80)) ?>..."</p>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 5px;"><?= time_ago($act['tgl_input']) ?></div>
                        <?= get_status_badge($act['status']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div style="padding: 40px; text-align: center; color: #94a3b8;">
                <i data-feather="inbox" style="width: 48px; height: 48px; margin-bottom: 10px; opacity: 0.5;"></i>
                <p>Anda belum mengirim laporan apapun.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quick Action Card -->
<div class="card" style="margin-top: 30px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
    <div class="card-body" style="padding: 40px; text-align: center;">
        <h3 style="margin-bottom: 15px;">Punya keluhan atau saran?</h3>
        <p style="margin-bottom: 25px; opacity: 0.9;">Sampaikan aspirasi Anda untuk sekolah yang lebih baik.</p>
        <a href="<?= base_url('index.php?page=siswa_kirim') ?>" class="btn" style="background: white; color: #764ba2; font-weight: 700; padding: 12px 30px; border-radius: 30px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px;">
            <i data-feather="plus-circle" style="width: 20px;"></i> Kirim Aspirasi Sekarang
        </a>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
