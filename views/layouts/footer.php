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
    // Improved Sidebar Toggle
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const toggleBtn = document.querySelector('.toggle-sidebar');

    function toggleSidebar() {
        if (sidebar && backdrop) {
            sidebar.classList.toggle('active');
            backdrop.classList.toggle('active');
        }
    }

    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }

    if (backdrop) {
        backdrop.addEventListener('click', toggleSidebar);
    }
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
