    </div> <!-- End .content -->
</div> <!-- End .main-content -->



<div class="toast-container" id="toastContainer">
    <!-- Toasts injected here by JS -->
</div>

</div> <!-- End .wrapper -->

<!-- Feather Icons -->
<script>
    feather.replace();
</script>

<script>
    // Simple Sidebar Toggle
    document.querySelector('.toggle-sidebar').addEventListener('click', function() {
        document.querySelector('.sidebar').classList.toggle('active');
    });
</script>

<!-- Flash Message Handler -->
<?php if (isset($_SESSION['flash'])): ?>
<script>
    const type = "<?= $_SESSION['flash']['type']; ?>";
    const msg = "<?= $_SESSION['flash']['message']; ?>";
    // Show toast (simplified for native PHP context, usually need a proper JS lib)
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
