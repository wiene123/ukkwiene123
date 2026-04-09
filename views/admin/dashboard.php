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

<!-- Premium Metrics -->
<div class="row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
    <div class="card" style="background: linear-gradient(135deg, #8c82eb 0%, #6e64d2 100%); color: white; border: none; overflow: hidden; position: relative;">
        <div style="z-index: 1; position: relative;">
            <p style="margin: 0; opacity: 0.8; font-size: 0.9rem;">Tingkat Penyelesaian Laporan</p>
            <h3 style="margin: 10px 0; font-size: 2.5rem; font-weight: 800;"><?= $metrics['completion_rate'] ?>%</h3>
            <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.2); border-radius: 10px; margin-top: 15px;">
                <div style="width: <?= $metrics['completion_rate'] ?>%; height: 100%; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(255,255,255,0.5);"></div>
            </div>
        </div>
        <i data-feather="trending-up" style="position: absolute; right: -20px; bottom: -20px; width: 120px; height: 120px; opacity: 0.1;"></i>
    </div>

    <div class="card" style="background: linear-gradient(135deg, #ff7675 0%, #d63031 100%); color: white; border: none; overflow: hidden; position: relative;">
        <div style="z-index: 1; position: relative;">
            <p style="margin: 0; opacity: 0.8; font-size: 0.9rem;">Aspirasi Urgent Aktif</p>
            <h3 style="margin: 10px 0; font-size: 2.5rem; font-weight: 800;"><?= $metrics['urgent_waiting'] ?> <small style="font-size: 1rem; font-weight: 400;">Butuh Respon</small></h3>
            <p style="margin: 0; font-size: 0.85rem; background: rgba(0,0,0,0.1); padding: 5px 12px; border-radius: 20px; display: inline-block;">Target Respon: <?= $metrics['avg_response'] ?></p>
        </div>
        <i data-feather="alert-circle" style="position: absolute; right: -20px; bottom: -20px; width: 120px; height: 120px; opacity: 0.1;"></i>
    </div>
</div>

<!-- Charts Section -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 20px; margin-bottom: 30px;">
    <!-- Chart Status -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Laporan Berdasarkan Status</h4>
        </div>
        <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>

    <!-- Chart Kategori -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Laporan Berdasarkan Kategori</h4>
        </div>
        <div style="height: 300px; display: flex; align-items: center; justify-content: center;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Status Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu', 'Proses', 'Selesai'],
            datasets: [{
                data: [<?= $stats['menunggu'] ?>, <?= $stats['proses'] ?>, <?= $stats['selesai'] ?>],
                backgroundColor: ['#FBC02D', '#1976D2', '#388E3C'],
                borderWidth: 5,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            },
            cutout: '70%'
        }
    });

    // 2. Category Chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    const catLabels = [<?php foreach($cat_stats as $c) echo "'".h($c['nama_kategori'])."',"; ?>];
    const catData = [<?php foreach($cat_stats as $c) echo $c['total'].","; ?>];

    new Chart(catCtx, {
        type: 'bar',
        data: {
            labels: catLabels,
            datasets: [{
                label: 'Jumlah Laporan',
                data: catData,
                backgroundColor: 'rgba(140, 130, 235, 0.6)',
                borderColor: 'var(--primary)',
                borderWidth: 1,
                borderRadius: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, grid: { display: false } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>

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
