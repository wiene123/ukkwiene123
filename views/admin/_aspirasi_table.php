<?php if (!empty($data)): ?>
    <?php foreach ($data as $index => $row): ?>
    <tr style="<?= $row['is_urgent'] ? 'border-left: 4px solid #ff7675; background: #fff8f8;' : '' ?>">
        <td><?= $index + 1 ?></td>
        <td>
            <b><?= h($row['nama_siswa']) ?></b><br>
            <span style="font-size:0.8rem; color:#888;"><?= h($row['kelas']) ?></span>
        </td>
        <td><?= h($row['nama_kategori']) ?></td>
        <td>
            <?php if ($row['is_urgent']): ?>
                <span class="badge" style="background: #ff7675; color: white; padding: 2px 8px; font-size: 0.7rem; margin-bottom: 5px;">🚨 URGENT</span><br>
            <?php endif; ?>
            <?= substr(h($row['isi_aspirasi']), 0, 80) . '...' ?>
        </td>
        <td>
            <span style="font-size: 0.85rem; color: #555;"><?= date('d/m/Y', strtotime($row['tgl_input'])) ?></span><br>
            <span style="font-size: 0.75rem; color: #999;"><?= time_ago($row['tgl_input']) ?></span>
        </td>
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
        <td colspan="7" align="center" style="padding: 40px; color: #888;">
            <i data-feather="search" style="width: 48px; height: 48px; opacity: 0.2; margin-bottom: 10px;"></i><br>
            Data tidak ditemukan.
        </td>
    </tr>
<?php endif; ?>

<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
