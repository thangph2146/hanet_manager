<?php
$perPageOptions = [10, 25, 50, 100];
?>

<div class="card-header bg-white">
    <form action="<?= site_url($module_name . '/listdeleted') ?>" method="get" class="row g-3">
        <div class="col-12 col-sm-6 col-md-6">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Tìm kiếm theo tên hoặc mã loại sự kiện..." name="keyword" value="<?= $keyword ?>">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <select class="form-select" name="perPage" id="perPage" onchange="this.form.submit()">
                <?php foreach ($perPageOptions as $option) : ?>
                    <option value="<?= $option ?>" <?= (string)$perPage === (string)$option ? 'selected' : '' ?>>
                        <?= $option ?> mục
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <button type="submit" class="btn btn-primary me-2">Lọc</button>
            <a href="<?= site_url($module_name . '/listdeleted') ?>" class="btn btn-danger">Xóa lọc</a>
        </div>
    </form>
</div> 

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý sự kiện thay đổi perPage
    document.getElementById('perPage').addEventListener('change', function(e) {
        e.preventDefault();
        let form = this.closest('form');
        // Reset về trang 1 khi thay đổi số lượng hiển thị
        let pageInput = document.createElement('input');
        pageInput.type = 'hidden';
        pageInput.name = 'page';
        pageInput.value = '1';
        form.appendChild(pageInput);
        form.submit();
    });
});
</script>

<?php if (!empty($keyword)): ?>
    <div class="alert alert-info m-3">
        <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
        <div class="small">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            
            <a href="<?= site_url($module_name . '/listdeleted') ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
        </div>
    </div>
<?php endif; ?> 