<?php
$perPageOptions = [10, 25, 50, 100];
$statusOptions = [
    '' => 'Tất cả trạng thái',
    '1' => 'Hoạt động',
    '0' => 'Vô hiệu'
];
?>

<div class="card-header bg-white">
    <form action="<?= site_url($module_name) ?>" method="get" class="row g-3">
        <div class="col-12 col-sm-6 col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" 
                       placeholder="Tìm kiếm theo tên, mã hoặc mô tả..." 
                       name="keyword" value="<?= $keyword ?>">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="bx bx-search"></i>
                </button>
            </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <select class="form-select" name="status" onchange="this.form.submit()">
                <?php foreach ($statusOptions as $value => $label) : ?>
                    <option value="<?= $value ?>" <?= isset($status) && $status === $value ? 'selected' : '' ?>>
                        <?= $label ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-2">
            <select class="form-select" name="perPage" id="perPage" onchange="this.form.submit()">
                <?php foreach ($perPageOptions as $option) : ?>
                    <option value="<?= $option ?>" <?= (string)$perPage === (string)$option ? 'selected' : '' ?>>
                        <?= $option ?> mục
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
            <a href="<?= site_url($module_name)?>" class="btn btn-danger">Xóa lọc</a>
        </div>
    </form>
</div>

<?php if (!empty($keyword) || (isset($status) && $status !== '')): ?>
    <div class="alert alert-info m-3">
        <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
        <div class="small">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            
            <?php if (isset($status) && $status !== ''): ?>
                <span class="badge bg-warning text-dark me-2">Trạng thái: <?= $statusOptions[$status] ?></span>
            <?php endif; ?>
            
            <a href="<?= site_url($module_name) ?>" class="text-decoration-none">
                <i class="bx bx-x"></i> Xóa bộ lọc
            </a>
        </div>
    </div>
<?php endif; ?> 