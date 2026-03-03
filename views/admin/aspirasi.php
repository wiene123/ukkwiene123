<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title">Daftar Aspirasi Masuk</h4>
        <div class="card-actions">
            <a href="?page=admin_aspirasi&status=menunggu" class="btn btn-sm" style="background: #FFFde7; color: #FBC02D; border: 1px solid #FBC02D;">Menunggu</a>
            <a href="?page=admin_aspirasi&status=proses" class="btn btn-sm" style="background: #E3F2FD; color: #1976D2; border: 1px solid #1976D2;">Proses</a>
            <a href="?page=admin_aspirasi&status=selesai" class="btn btn-sm" style="background: #E8F5E9; color: #388E3C; border: 1px solid #388E3C;">Selesai</a>
            <a href="?page=admin_aspirasi" class="btn btn-sm btn-secondary">Semua</a>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="15%">Pelapor</th>
                    <th width="15%">Kategori</th>
                    <th width="30%">Isi Laporan</th>
                    <th width="15%">Tanggal</th>
                    <th width="10%">Status</th>
                    <th width="10%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php foreach ($data as $index => $row): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <b><?= h($row['nama_siswa']) ?></b><br>
                            <span style="font-size:0.8rem; color:#888;"><?= h($row['kelas']) ?></span>
                        </td>
                        <td><?= h($row['nama_kategori']) ?></td>
                        <td><?= substr(h($row['isi_aspirasi']), 0, 50) . '...' ?></td>
                        <td><?= date('d M Y H:i', strtotime($row['tgl_input'])) ?></td>
                        <td><?= get_status_badge($row['status']) ?></td>
                        <td>
                            <a href="?page=admin_aspirasi_detail&id=<?= $row['id_aspirasi'] ?>" class="btn btn-sm btn-primary">
                                <i data-feather="eye" style="width:14px;"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" align="center" style="padding: 30px; color: #888;">Belum ada data aspirasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
