<?php
$options = [
    'pagination' => [10, 25, 50, 100]
];

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$perPage = isset($_GET['perPage']) ? $_GET['perPage'] : 10;
?>

<!-- Filter Form -->
<form action="<?= site_url($module_name) ?>" method="get" class="mb-4 mx-4">
    <div class="row">
        <!-- Keyword Search -->
        <div class="col-md-3 mb-3">
            <label for="keyword" class="form-label">Từ khóa</label>
            <input type="text" class="form-control" id="keyword" name="keyword" value="<?= $keyword ?? '' ?>" placeholder="Nhập từ khóa tìm kiếm...">
        </div>

        <!-- Start Date -->
        <div class="col-md-3 mb-3">
            <label for="start_date" class="form-label">Từ ngày</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?? '' ?>">
        </div>

        <!-- End Date -->
        <div class="col-md-3 mb-3">
            <label for="end_date" class="form-label">Đến ngày</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?? '' ?>">
        </div>

        <!-- Per Page -->
        <div class="col-md-3 mb-3">
            <label for="perPage" class="form-label">Số dòng hiển thị</label>
            <select class="form-select" id="perPage" name="perPage">
                <?php foreach ([10, 25, 50, 100] as $value): ?>
                    <option value="<?= $value ?>" <?= ($perPage ?? 10) == $value ? 'selected' : '' ?>><?= $value ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <!-- Hidden page field to reset pagination when applying filters -->
    <input type="hidden" name="page" value="1">

    <!-- Filter Actions -->
    <div class="row">
        <div class="col-12">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Tìm kiếm
            </button>
            <a href="<?= site_url($module_name) ?>" class="btn btn-secondary">
                <i class="fas fa-redo"></i> Đặt lại
            </a>
        </div>
    </div>

    <!-- Active Filters Display -->
    <?php if (!empty($keyword) || !empty($start_date) || !empty($end_date)): ?>
    <div class="mt-3">
        <h6>Bộ lọc đang áp dụng:</h6>
        <div class="d-flex flex-wrap gap-2">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-info">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            <?php if (!empty($start_date)): ?>
                <span class="badge bg-info">Từ ngày: <?= esc($start_date) ?></span>
            <?php endif; ?>
            <?php if (!empty($end_date)): ?>
                <span class="badge bg-info">Đến ngày: <?= esc($end_date) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</form> 