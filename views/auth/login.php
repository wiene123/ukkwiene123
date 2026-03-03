<?php require_once 'views/layouts/header.php'; ?>
<style>
/* Override default wrapper for login */
body { background: #f0f2f5; display: flex; align-items: center; justify-content: center; height: 100vh; }
.auth-wrapper { margin-top: -50px; }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="auth-icon">
                <i data-feather="monitor"></i>
            </div>
        </div>
        <p style="text-align: center; font-size: 2.5rem; font-weight:900; color: #8280EB;">SIPESKU</p>
        <h4 style="margin-bottom: 25px; color: #555;">Login Pengguna</h4>
        
        <?php if (isset($error)): ?>
            <div style="color: red; margin-bottom: 15px; font-size: 0.9rem; background: #ffebee; padding: 10px; border-radius: 5px;">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group" style="text-align: left;">
                <label>Username / NISN</label>
                <input type="text" name="username" class="form-control" placeholder="Masukkan ID Anda" required autofocus>
            </div>
            <div class="form-group" style="text-align: left;">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;">
                Masuk Sekarang <i data-feather="arrow-right"></i>
            </button>
        </form>

        <div style="margin-top: 20px; font-size: 0.85rem; color: #888;">
            <p>Belum punya akun? Hubungi Administrator.</p>
        </div>
    </div>
</div>

<div class="toast-container" id="toastContainer"></div>

<!-- Feather Icons -->
<script>
    feather.replace();
</script>

<!-- Flash Message Handler -->
<?php if (isset($_SESSION['flash'])): ?>
<script>
    const type = "<?= $_SESSION['flash']['type']; ?>";
    const msg = "<?= $_SESSION['flash']['message']; ?>";
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerText = msg;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => { toast.remove(); }, 3000);
</script>
<?php unset($_SESSION['flash']); ?>
<?php endif; ?>

</body>
</html>
