
<?php if (!empty($_SESSION['flash'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: '<?= $_SESSION['flash']['type'] ?>',
                title: '<?= $_SESSION['flash']['message'] ?>',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        });
    </script>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<script>
    // Konfirmasi hapus memakai SweetAlert (berlaku untuk semua link ber-atribut data-confirm)
    document.addEventListener('click', function (e) {
        const el = e.target.closest('[data-confirm]');
        if (!el) return;
        e.preventDefault();
        const url = el.getAttribute('href');
        const pesan = el.getAttribute('data-confirm') || 'Data ini akan dihapus secara permanen.';
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: pesan,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then(function (result) {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
</script>

</html>