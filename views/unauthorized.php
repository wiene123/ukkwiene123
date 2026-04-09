<?php require_once 'views/layouts/sidebar.php'; ?>

<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 70vh; text-align: center; animation: fadeIn 0.5s ease;">
    <div style="background: #fff5f5; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #f56565; margin-bottom: 25px;">
        <i data-feather="slash" style="width: 50px; height: 50px;"></i>
    </div>
    
    <h1 style="font-size: 2.5rem; color: #2d3748; margin-bottom: 10px;">Akses Ditolak!</h1>
    <p style="color: #718096; font-size: 1.1rem; max-width: 500px; line-height: 1.6;">
        Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Halaman ini khusus untuk level akun tertentu.
    </p>

    <div style="margin-top: 30px; display: flex; gap: 15px;">
        <button onclick="history.back()" class="btn btn-secondary" style="padding: 10px 30px;">Kembali</button>
        <a href="<?= base_url('index.php') ?>" class="btn btn-primary" style="padding: 10px 30px;">Dashboard Utama</a>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>
