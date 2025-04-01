<?php
/**
 * Component chứa header của card
 * 
 * Các biến cần truyền vào:
 * @var string $module_name Tên module
 */
?>

<div class="card-header py-3 d-flex justify-content-between align-items-center">
    <h5 class="card-title mb-0">Danh sách bậc học</h5>
    <div>
        <button type="button" class="btn btn-sm btn-outline-primary me-2" id="refresh-table">
            <i class='bx bx-refresh'></i> Làm mới
        </button>
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <i class='bx bx-export'></i> Xuất
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?= site_url($module_name . '/exportExcel' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" id="export-excel">Excel</a></li>
                <li><a class="dropdown-item" href="<?= site_url($module_name . '/exportPdf' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" id="export-pdf">PDF</a></li>
            </ul>
        </div>
    </div>
</div> 