<?php
$options = [
    'pagination' => [10, 25, 50, 100]
];

// Lấy giá trị hiện tại từ request
$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$perPage = isset($_GET['perPage']) ? $_GET['perPage'] : 10;
?>

<div class="card-header bg-white">
    <form action="<?= site_url($module_name) ?>" method="GET" class="form-horizontal">
        <div class="row g-3 align-items-center">
            <!-- Tìm kiếm theo tên sự kiện -->
            <div class="col-12 col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-search'></i></span>
                    <input type="text" class="form-control" name="keyword" value="<?= $keyword ?>" placeholder="Tìm kiếm theo tên sự kiện...">
                </div>
            </div>

            <!-- Thời gian bắt đầu sự kiện -->
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text">Từ ngày</span>
                    <input type="date" class="form-control" name="start_date" value="<?= $start_date ?>">
                </div>
            </div>

            <!-- Thời gian kết thúc sự kiện -->
            <div class="col-12 col-md-3">
                <div class="input-group">
                    <span class="input-group-text">Đến ngày</span>
                    <input type="date" class="form-control" name="end_date" value="<?= $end_date ?>">
                </div>
            </div>

            <!-- Số bản ghi mỗi trang -->
            <div class="col-12 col-md-2">
                <select name="perPage" class="form-select">
                    <?php foreach ($options['pagination'] as $value): ?>
                        <option value="<?= $value ?>" <?= ($perPage == $value) ? 'selected' : '' ?>>
                            <?= $value ?> bản ghi
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Các nút chức năng -->
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
                <span class="badge bg-primary">Từ khóa: <?= esc($keyword) ?></span>
            <?php endif; ?>
            
            <?php if (!empty($start_date)): ?>
                <span class="badge bg-primary">Từ ngày: <?= date('d/m/Y', strtotime($start_date)) ?></span>
            <?php endif; ?>
            
            <?php if (!empty($end_date)): ?>
                <span class="badge bg-primary">Đến ngày: <?= date('d/m/Y', strtotime($end_date)) ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php
// Hiển thị các filter đã chọn (chỉ trong môi trường development)
if (ENVIRONMENT === 'development' && (!empty($keyword) || !empty($start_date) || !empty($end_date))):
?>
<div class="mt-2 p-2 border rounded bg-light">
    <div class="small fw-bold mb-1">Đã lọc theo:</div>
    <div class="d-flex flex-wrap gap-1">
        <?php if (!empty($keyword)): ?>
        <span class="badge bg-info">Tên: <?= esc($keyword) ?></span>
        <?php endif; ?>
        <?php if (!empty($start_date)): ?>
        <span class="badge bg-info">Từ ngày: <?= esc($start_date) ?></span>
        <?php endif; ?>
        <?php if (!empty($end_date)): ?>
        <span class="badge bg-info">Đến ngày: <?= esc($end_date) ?></span>
        <?php endif; ?>
    </div>
    <div class="mt-2 small">
        <a class="text-decoration-none" data-bs-toggle="collapse" href="#debug-params">
            <i class="fas fa-bug"></i> Xem GET params
        </a>
        <div class="collapse mt-1" id="debug-params">
            <pre class="small bg-dark text-white p-2 rounded"><?= print_r($_GET, true) ?></pre>
            <button class="btn btn-sm btn-outline-secondary copy-btn" 
                    onclick="navigator.clipboard.writeText('<?= http_build_query($_GET) ?>')">
                Copy query
            </button>
        </div>
        <div class="mt-1">
            <a class="text-decoration-none" data-bs-toggle="collapse" href="#debug-module">
                <i class="fas fa-info-circle"></i> Module info
            </a>
            <div class="collapse mt-1" id="debug-module">
                <pre class="small bg-dark text-white p-2 rounded"><?= 'Module: ' . ($module_name ?? 'không xác định') ?></pre>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Script khởi tạo datepicker -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Kiểm tra nếu trình duyệt chưa hỗ trợ input type="date"
        var dateInputs = document.querySelectorAll('input[type="date"]');
        var needsDatepicker = false;
        
        dateInputs.forEach(function(input) {
            var test = document.createElement('input');
            test.type = 'date';
            // Kiểm tra xem trình duyệt có hỗ trợ input type="date" không
            if (test.type === 'text') {
                needsDatepicker = true;
            }
        });
        
        // Nếu trình duyệt không hỗ trợ, thêm datepicker
        if (needsDatepicker && typeof $.fn.datepicker === 'function') {
            dateInputs.forEach(function(input) {
                // Chuyển đổi thành input text và áp dụng datepicker
                input.type = 'text';
                $(input).datepicker({
                    format: 'yyyy-mm-dd',
                    autoclose: true,
                    todayHighlight: true,
                    language: 'vi'
                });
            });
        }
    });
</script>