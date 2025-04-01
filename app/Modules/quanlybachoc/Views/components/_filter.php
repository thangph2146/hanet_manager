<?php
/**
 * Component hiển thị bộ lọc và tìm kiếm
 * 
 * Các biến cần truyền vào:
 * @var string $module_name Tên module
 * @var string $keyword Từ khóa tìm kiếm
 * @var string $status Trạng thái lọc
 * @var int $perPage Số bản ghi trên mỗi trang
 */
?>

<div class="p-3 bg-light border-bottom">
    <div class="row">
        <div class="col-12 col-md-6 mb-2 mb-md-0">
            <form id="form-delete-multiple" action="<?= site_url($module_name . '/deleteMultiple') ?>" method="post" class="d-inline">
                <?= csrf_field() ?>
                <button type="button" id="delete-selected-multiple" class="btn btn-danger btn-sm me-2" disabled>
                    <i class='bx bx-trash'></i> Xóa mục đã chọn
                </button>
            </form>
            <a href="<?= site_url($module_name . '/listdeleted') ?>" class="btn btn-outline-danger btn-sm">
                <i class='bx bx-trash'></i> Danh sách đã xóa
            </a>
        </div>
        <div class="col-12 col-md-6">
            <form action="<?= site_url($module_name) ?>" method="get" id="search-form">
                <input type="hidden" name="page" value="1">
                <input type="hidden" name="perPage" value="<?= $perPage ?>">
                <div class="input-group search-box">
                    <input type="text" class="form-control form-control-sm" id="table-search" name="keyword" placeholder="Tìm kiếm..." value="<?= $keyword ?? '' ?>">
                    <select name="status" class="form-select form-select-sm" style="max-width: 140px;">
                        <option value="">-- Trạng thái --</option>
                        <option value="1" <?= (isset($status) && $status == '1') ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= (isset($status) && $status == '0') ? 'selected' : '' ?>>Không hoạt động</option>
                    </select>
                    <button class="btn btn-outline-secondary btn-sm" type="submit">
                        <i class='bx bx-search'></i>
                    </button>
                    <?php if (!empty($keyword) || (isset($status) && $status !== '')): ?>
                    <a href="<?= site_url($module_name) ?>" class="btn btn-outline-danger btn-sm">
                        <i class='bx bx-x'></i>
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if (!empty($keyword) || (isset($status) && $status !== '')): ?>
    <div class="alert alert-info m-3">
        <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
        <div class="small">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            <?php if (isset($status) && $status !== ''): ?>
                <span class="badge bg-secondary me-2">Trạng thái: <?= $status == 1 ? 'Hoạt động' : 'Không hoạt động' ?></span>
            <?php endif; ?>
            <a href="<?= site_url($module_name) ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
        </div>
    </div>
<?php endif; ?> 