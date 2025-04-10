<?php
$options = [
    'pagination' => [10, 25, 50, 100]
];

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$perPage = isset($_GET['perPage']) ? $_GET['perPage'] : 10;
?>

<div class="card-header bg-white">
    <form action="<?= site_url($module_name) ?>" method="GET" class="form-horizontal">
        <div class="row g-3 align-items-center">
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-search'></i></span>
                    <input type="text" class="form-control" name="keyword" value="<?= $keyword ?>" placeholder="Tìm kiếm...">
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text">Từ</span>
                    <input type="datetime-local" class="form-control" name="start_date" value="<?= $start_date ?>">
                </div>
            </div>

            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text">Đến</span>
                    <input type="datetime-local" class="form-control" name="end_date" value="<?= $end_date ?>">
                </div>
            </div>

            <div class="col-12 col-md-2">
                <select name="perPage" class="form-select">
                    <?php foreach ($options['pagination'] as $value): ?>
                        <option value="<?= $value ?>" <?= $perPage == $value ? 'selected' : '' ?>>
                            <?= $value ?> bản ghi
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-filter-alt"></i> Lọc
                    </button>
                    <a href="<?= site_url($module_name) ?>" class="btn btn-danger">
                        <i class="bx bx-reset"></i> Đặt lại
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($keyword) || !empty($start_date) || !empty($end_date)): ?>
    <div class="alert alert-info m-3">
        <h6 class="alert-heading fw-bold mb-1">Kết quả tìm kiếm:</h6>
        <div class="d-flex flex-wrap gap-2">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary">Từ khóa: <?= $keyword ?></span>
            <?php endif; ?>
            
            <?php if (!empty($start_date)): ?>
                <span class="badge bg-primary">Từ ngày: <?= date('d/m/Y H:i', strtotime($start_date)) ?></span>
            <?php endif; ?>
            
            <?php if (!empty($end_date)): ?>
                <span class="badge bg-primary">Đến ngày: <?= date('d/m/Y H:i', strtotime($end_date)) ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?> 