<?php
/**
 * Component hiển thị form lọc dữ liệu sự kiện đã xóa
 */

$perPageOptions = [10, 25, 50, 100];
?>

<div class="card-header p-0 border-0">
    <form action="<?= site_url($module_name . '/listdeleted') ?>" method="get" class="form-horizontal" id="filterForm">
        <div class="p-0">
            <div class="row mx-0 py-3">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control" name="keyword" id="keyword" placeholder="Tìm kiếm theo tên sự kiện..." value="<?= $keyword ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="start_date" id="start_date" placeholder="Từ ngày" value="<?= $start_date ?? '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-2">
                            <div class="form-group mb-0">
                                <input type="text" class="form-control datepicker" name="end_date" id="end_date" placeholder="Đến ngày" value="<?= $end_date ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-2 text-right">
                    <button type="submit" class="btn btn-primary btn-block mb-2">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                    <a href="<?= site_url($module_name . '/listdeleted') ?>" class="btn btn-secondary btn-block">
                        <i class="fas fa-sync"></i> Làm mới
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>

<?php if (!empty($keyword) || 
          (isset($start_date) && $start_date !== '') ||
          (isset($end_date) && $end_date !== '')): ?>
    <div class="alert alert-info m-3">
        <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm (đã xóa):</h6>
        <div class="small">
            <?php if (!empty($keyword)): ?>
                <span class="badge bg-primary me-2">Tên sự kiện: <?= esc($keyword) ?></span>
            <?php endif; ?>
            
            <?php if (isset($start_date) && $start_date !== ''): ?>
                <span class="badge bg-info me-2">Từ ngày: <?= esc($start_date) ?></span>
            <?php endif; ?>
            
            <?php if (isset($end_date) && $end_date !== ''): ?>
                <span class="badge bg-warning me-2">Đến ngày: <?= esc($end_date) ?></span>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<script>
$(document).ready(function() {
    // DatePicker
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'vi'
    });
});
</script> 