<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.min.js"></script>
    
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Core Scripts - Thứ tự quan trọng để tránh xung đột -->
<script src="<?= base_url('assets/js/nguoidung/scripts.js') ?>"></script>
<script src="<?= base_url('assets/js/nguoidung/layouts/nguoidung_layout.js') ?>"></script>

<!-- Không nạp components JS ở đây vì có thể gây xung đột, sẽ được nạp trong các components -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ẩn loader sau khi trang đã tải xong
    setTimeout(function() {
        const loader = document.querySelector('.page-loader');
        if (loader) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
            }, 300);
        }
    }, 500);
});
</script>
