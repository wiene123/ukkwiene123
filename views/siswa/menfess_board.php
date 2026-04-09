<?php require_once 'views/layouts/sidebar.php'; ?>

<div class="header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
    <div>
        <h2 style="margin: 0; font-size: 1.8rem; color: #333;">Bimenfess Board 💖</h2>
        <p style="color: #666; margin: 5px 0 0;">Ruang curhat, salam-salam, dan pesan rahasia warga sekolah.</p>
    </div>
    <button onclick="document.getElementById('modal-buat-menfess').style.display='flex'" class="btn btn-primary" style="padding: 12px 25px; border-radius: 50px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);">
        <i data-feather="plus-circle"></i> Kirim Menfess
    </button>
</div>

<?php if(!empty($my_posts)): ?>
    <div class="card" style="margin-bottom: 30px; border-left: 5px solid #6c5ce7;">
        <div class="card-header" style="background: #f8fafc; cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="document.getElementById('my-menfess-list').classList.toggle('hidden')">
            <h5 style="margin: 0; font-size: 0.9rem; color: #6c5ce7;"><i data-feather="user"></i> Status Kiriman Saya (<?= count($my_posts) ?>)</h5>
            <i data-feather="chevron-down" style="width: 16px;"></i>
        </div>
        <div id="my-menfess-list" class="card-body hidden" style="padding: 15px;">
            <div class="table-responsive">
                <table class="table" style="font-size: 0.85rem; margin: 0;">
                    <thead>
                        <tr style="background: transparent; border-bottom: 1px solid #eee;">
                            <th>Isi Pesan</th>
                            <th style="text-align: center;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($my_posts as $mp): ?>
                            <tr>
                                <td style="opacity: 0.8;"><?= (strlen($mp['isi']) > 60) ? h(substr($mp['isi'], 0, 60)) . '...' : h($mp['isi']) ?></td>
                                <td style="text-align: center;">
                                    <?php if($mp['status'] == 'approved'): ?>
                                        <span class="badge" style="background: #d1fae5; color: #065f46; font-size: 0.7rem;">Publik</span>
                                    <?php elseif($mp['status'] == 'pending'): ?>
                                        <span class="badge" style="background: #fff9db; color: #92400e; font-size: 0.7rem;">Moderasi</span>
                                    <?php else: ?>
                                        <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 0.7rem;">Ditolak</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(isset($_SESSION['flash'])): ?>
    <div class="alert alert-<?= $_SESSION['flash']['type'] == 'success' ? 'success' : 'danger' ?>" style="margin-bottom: 30px;">
        <?= $_SESSION['flash']['message'] ?>
    </div>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Masonry Grid for Menfess -->
<div class="menfess-grid" id="menfessBoard">
    <?php if(empty($posts)): ?>
        <div style="grid-column: 1/-1; text-align: center; padding: 100px; color: #aaa;">
            <i data-feather="message-circle" style="width: 64px; height: 64px; opacity: 0.2; margin-bottom: 15px;"></i>
            <h3>Belum ada curhat yang dipublish.</h3>
            <p>Jadilah yang pertama mengirimkan menfess!</p>
        </div>
    <?php else: ?>
        <?php foreach($posts as $post): ?>
            <?php 
                // Determine text color based on background brightness (simple check)
                $bg = $post['warna'];
                $is_dark = (hexdec(substr($bg, 1, 2)) + hexdec(substr($bg, 3, 2)) + hexdec(substr($bg, 5, 2))) < 400;
            ?>
            <div class="menfess-card" style="background: <?= $bg ?>; color: <?= $is_dark ? '#fff' : '#333' ?>; animation: fadeIn 0.5s ease backwards;">
                <div class="menfess-content">
                    <?= nl2br(h($post['isi'])) ?>
                </div>
                <div class="menfess-footer" style="border-top: 1px solid <?= $is_dark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)' ?>;">
                    <div style="font-size: 0.75rem; opacity: 0.7;">
                        <i data-feather="calendar" style="width: 12px; height: 12px;"></i> <?= date('d M Y', strtotime($post['tgl_input'])) ?>
                    </div>
                    <div class="like-container">
                        <button class="like-btn <?= $post['has_liked'] ? 'liked' : '' ?>" onclick="toggleLike(<?= $post['id'] ?>, this)">
                            <i data-feather="heart" style="width: 16px; height: 16px;"></i>
                            <span class="like-count"><?= $post['likes'] ?></span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Buat Menfess -->
<div id="modal-buat-menfess" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div class="card" style="max-width: 500px; width: 90%; animation: popIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <div class="card-header">
            <h4 class="card-title">Kirim Bimenfess Baru 💖</h4>
        </div>
        <form action="<?= base_url('index.php?page=menfess_store') ?>" method="POST">
            <div class="card-body">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600;">Pesan Curhat</label>
                    <textarea name="isi" class="form-control" rows="5" placeholder="Tuliskan curhatanmu di sini..." required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #ddd;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 10px; font-weight: 600;">Pilih Warna Kartu</label>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <?php $colors = ['#ffffff', '#fff5f5', '#fff9db', '#f3f0ff', '#e7f5ff', '#e6fffa', '#f8f9fa']; ?>
                        <?php foreach($colors as $c): ?>
                            <label style="cursor: pointer;">
                                <input type="radio" name="warna" value="<?= $c ?>" style="display: none;" <?= $c == '#ffffff' ? 'checked' : '' ?>>
                                <div class="color-circle" style="background: <?= $c ?>;"></div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <p style="font-size: 0.8rem; color: #888; background: #f8f9fa; padding: 10px; border-radius: 8px;">
                    <i data-feather="info" style="width:14px;"></i> Kiriman Anda akan dimoderasi oleh admin sebelum ditampilkan secara anonim di dinding.
                </p>
            </div>
            <div style="padding: 20px; text-align: right; display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('modal-buat-menfess').style.display='none'" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" style="background: #6c5ce7; border: none; padding: 10px 25px; border-radius: 50px;">Kirim Pesan</button>
            </div>
        </form>
    </div>
</div>

<style>
.menfess-grid {
    column-count: 3;
    column-gap: 20px;
    margin-bottom: 40px;
}
@media (max-width: 992px) { .menfess-grid { column-count: 2; } }
@media (max-width: 576px) { .menfess-grid { column-count: 1; } }

.hidden { display: none; }

.menfess-card {
    break-inside: avoid;
    margin-bottom: 20px;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: 0.3s;
    border: 1px solid rgba(0,0,0,0.05);
}
.menfess-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.menfess-content {
    font-size: 1rem;
    line-height: 1.6;
    margin-bottom: 20px;
}
.menfess-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
}

.color-circle {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: 2px solid #ddd;
    transition: 0.2s;
}
input[type="radio"]:checked + .color-circle {
    border-color: #6c5ce7;
    transform: scale(1.1);
}

.like-btn {
    background: none;
    border: none;
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    color: inherit;
    opacity: 0.7;
    transition: 0.2s;
}
.like-btn:hover { opacity: 1; transform: scale(1.05); }
.like-btn.liked { color: #ff7675; opacity: 1; }
.like-btn.liked i { fill: #ff7675; }

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes popIn {
    0% { transform: scale(0.9); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
</style>

<script>
async function toggleLike(id, btn) {
    try {
        const response = await fetch(`index.php?page=menfess_like&id=${id}`);
        const data = await response.json();
        
        if (data.success) {
            const countSpan = btn.querySelector('.like-count');
            let count = parseInt(countSpan.innerText);
            
            if (btn.classList.contains('liked')) {
                btn.classList.remove('liked');
                countSpan.innerText = count - 1;
            } else {
                btn.classList.add('liked');
                countSpan.innerText = count + 1;
            }
        }
    } catch (err) {
        console.error(err);
    }
}
</script>

<?php require_once 'views/layouts/footer.php'; ?>
