<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="card">
    <div class="card-header" style="flex-wrap: wrap; gap: 15px;">
        <h4 class="card-title">Daftar Aspirasi Masuk</h4>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
             <a href="?page=admin_export_pdf" target="_blank" class="btn btn-sm btn-secondary" style="background: #fff; border: 1px solid #ddd;">
                <i data-feather="printer" style="width:14px;"></i> PDF
            </a>
            <a href="?page=admin_export_excel" class="btn btn-sm btn-secondary" style="background: #fff; border: 1px solid #ddd;">
                <i data-feather="file-text" style="width:14px;"></i> Excel
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div style="padding: 20px; border-bottom: 1px solid #f5f5f5; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; background: #fafafa;">
        <div style="flex: 1; min-width: 250px; position: relative;">
            <i data-feather="search" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); width: 16px; color: #aaa;"></i>
            <input type="text" id="search-input" placeholder="Cari nama pelapor atau isi laporan..." style="padding-left: 45px;" class="form-control">
        </div>
        
        <select id="filter-status" class="form-control" style="width: auto;">
            <option value="">-- Semua Status --</option>
            <option value="menunggu">Menunggu</option>
            <option value="proses">Proses</option>
            <option value="selesai">Selesai</option>
        </select>

        <select id="filter-kategori" class="form-control" style="width: auto;">
            <option value="">-- Semua Kategori --</option>
            <?php foreach($kategoriList as $k): ?>
                <option value="<?= $k['id_kategori'] ?>"><?= h($k['nama_kategori']) ?></option>
            <?php endforeach; ?>
        </select>

        <label style="display: flex; align-items: center; gap: 5px; margin: 0; cursor: pointer; background: #fff; padding: 10px 15px; border-radius: 12px; border: 2px solid #f0f0f5; font-size: 0.9rem;">
            <input type="checkbox" id="filter-urgent" value="1" style="accent-color: var(--danger);"> Urgent 🚨
        </label>
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
            <tbody id="aspirasi-tbody">
                <?php require 'views/admin/_aspirasi_table.php'; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search-input');
    const filterStatus = document.getElementById('filter-status');
    const filterKategori = document.getElementById('filter-kategori');
    const filterUrgent = document.getElementById('filter-urgent');
    const tbody = document.getElementById('aspirasi-tbody');

    function updateTable() {
        const search = searchInput.value;
        const status = filterStatus.value;
        const kategori = filterKategori.value;
        const urgent = filterUrgent.checked ? 1 : 0;

        const url = `index.php?page=admin_aspirasi&search=${search}&status=${status}&kategori=${kategori}&urgent=${urgent}`;
        
        // Show loading state
        tbody.style.opacity = '0.5';

        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            tbody.innerHTML = html;
            tbody.style.opacity = '1';
            // Also update export links
            document.querySelectorAll('[href*="admin_export_"]').forEach(a => {
                const base = a.href.split('&')[0];
                a.href = `${base}&search=${search}&status=${status}&kategori=${kategori}&urgent=${urgent}`;
            });
        });
    }

    searchInput.addEventListener('input', updateTable);
    filterStatus.addEventListener('change', updateTable);
    filterKategori.addEventListener('change', updateTable);
    filterUrgent.addEventListener('change', updateTable);
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>
