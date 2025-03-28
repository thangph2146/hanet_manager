<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('table') ?>
<?= page_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH CHECK-OUT SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách check-out sự kiện',
	'dashboard_url' => site_url($module_name),
	'breadcrumbs' => [
		['title' => 'Quản lý check-out sự kiện', 'url' => site_url($module_name)],
		['title' => 'Danh sách', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/' . $module_name . '/new'), 'title' => 'Thêm mới', 'icon' => 'bx bx-plus-circle']
	]
]) ?>
<?= $this->endSection() ?>  

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách check-out sự kiện</h5>
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
                    <li><a class="dropdown-item" href="#" id="export-pdf">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
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
                        <div class="d-flex flex-column gap-2">
                            <div class="input-group search-box">
                                <input type="text" class="form-control form-control-sm" id="table-search" name="keyword" placeholder="Tìm kiếm..." value="<?= $keyword ?? '' ?>">
                                <button class="btn btn-outline-secondary btn-sm" type="submit">
                                    <i class='bx bx-search'></i>
                                </button>
                                <?php if (!empty($keyword) || isset($_GET['status']) || isset($_GET['sukien_id']) || isset($_GET['checkout_type']) || isset($_GET['hinh_thuc_tham_gia'])): ?>
                                <a href="<?= site_url($module_name) ?>" class="btn btn-outline-danger btn-sm">
                                    <i class='bx bx-x'></i>
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <!-- Bộ lọc sự kiện -->
                                <select name="sukien_id" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Tất cả sự kiện</option>
                                    <?php foreach ($suKienList as $suKien): ?>
                                    <option value="<?= $suKien->su_kien_id ?>" <?= (isset($_GET['sukien_id']) && $_GET['sukien_id'] == $suKien->su_kien_id) ? 'selected' : '' ?>><?= esc($suKien->ten_su_kien) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <!-- Bộ lọc loại check-out -->
                                <select name="checkout_type" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Tất cả loại check-out</option>
                                    <option value="face_id" <?= (isset($_GET['checkout_type']) && $_GET['checkout_type'] === 'face_id') ? 'selected' : '' ?>>Nhận diện khuôn mặt</option>
                                    <option value="manual" <?= (isset($_GET['checkout_type']) && $_GET['checkout_type'] === 'manual') ? 'selected' : '' ?>>Thủ công</option>
                                    <option value="qr_code" <?= (isset($_GET['checkout_type']) && $_GET['checkout_type'] === 'qr_code') ? 'selected' : '' ?>>Mã QR</option>
                                    <option value="auto" <?= (isset($_GET['checkout_type']) && $_GET['checkout_type'] === 'auto') ? 'selected' : '' ?>>Tự động</option>
                                    <option value="online" <?= (isset($_GET['checkout_type']) && $_GET['checkout_type'] === 'online') ? 'selected' : '' ?>>Trực tuyến</option>
                                </select>
                                
                                <!-- Bộ lọc trạng thái -->
                                <select name="status" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="1" <?= (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : '' ?>>Vô hiệu</option>
                                    <option value="2" <?= (isset($_GET['status']) && $_GET['status'] === '2') ? 'selected' : '' ?>>Đang xử lý</option>
                                </select>
                                
                                <!-- Bộ lọc hình thức tham gia -->
                                <select name="hinh_thuc_tham_gia" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Tất cả hình thức</option>
                                    <option value="offline" <?= (isset($_GET['hinh_thuc_tham_gia']) && $_GET['hinh_thuc_tham_gia'] === 'offline') ? 'selected' : '' ?>>Trực tiếp</option>
                                    <option value="online" <?= (isset($_GET['hinh_thuc_tham_gia']) && $_GET['hinh_thuc_tham_gia'] === 'online') ? 'selected' : '' ?>>Trực tuyến</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->has('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($keyword) || isset($_GET['status']) || isset($_GET['sukien_id']) || isset($_GET['checkout_type']) || isset($_GET['hinh_thuc_tham_gia'])): ?>
            <div class="alert alert-info m-3">
                <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
                <div class="small">
                    <?php if (!empty($keyword)): ?>
                        <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
                    <?php endif; ?>
                    <?php if (isset($_GET['sukien_id']) && $_GET['sukien_id'] !== ''): ?>
                        <span class="badge bg-primary me-2">Sự kiện: 
                            <?php 
                                $ten_su_kien = '';
                                foreach ($suKienList as $suKien) {
                                    if ($suKien->su_kien_id == $_GET['sukien_id']) {
                                        $ten_su_kien = $suKien->ten_su_kien;
                                        break;
                                    }
                                }
                                echo esc($ten_su_kien);
                            ?>
                        </span>
                    <?php endif; ?>
                    <?php if (isset($_GET['checkout_type']) && $_GET['checkout_type'] !== ''): ?>
                        <span class="badge bg-primary me-2">Loại check-out: 
                            <?php 
                                $loai_map = [
                                    'face_id' => 'Nhận diện khuôn mặt',
                                    'manual' => 'Thủ công',
                                    'qr_code' => 'Mã QR',
                                    'auto' => 'Tự động',
                                    'online' => 'Trực tuyến'
                                ];
                                echo $loai_map[$_GET['checkout_type']] ?? $_GET['checkout_type'];
                            ?>
                        </span>
                    <?php endif; ?>
                    <?php if (isset($_GET['status']) && $_GET['status'] !== ''): ?>
                        <span class="badge bg-primary me-2">Trạng thái: 
                            <?php 
                                $status_map = [
                                    '1' => 'Hoạt động',
                                    '0' => 'Vô hiệu',
                                    '2' => 'Đang xử lý'
                                ];
                                echo $status_map[$_GET['status']] ?? $_GET['status'];
                            ?>
                        </span>
                    <?php endif; ?>
                    <?php if (isset($_GET['hinh_thuc_tham_gia']) && $_GET['hinh_thuc_tham_gia'] !== ''): ?>
                        <span class="badge bg-primary me-2">Hình thức: 
                            <?php 
                                $hinh_thuc_map = [
                                    'offline' => 'Trực tiếp',
                                    'online' => 'Trực tuyến'
                                ];
                                echo $hinh_thuc_map[$_GET['hinh_thuc_tham_gia']] ?? $_GET['hinh_thuc_tham_gia'];
                            ?>
                        </span>
                    <?php endif; ?>
                    <a href="<?= site_url($module_name) ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
                </div>
            </div>
        <?php endif; ?>
        <div class="table-responsive">
            <div class="table-container">
                <table id="dataTable" class="table table-striped table-hover m-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th width="5%" class="text-center align-middle">
                                <div class="form-check">
                                    <input type="checkbox" id="select-all" class="form-check-input cursor-pointer">
                                </div>
                            </th>
                            <th width="5%" class="align-middle">ID</th>
                            <th width="15%" class="align-middle">Sự kiện</th>
                            <th width="15%" class="align-middle">Họ tên</th>
                            <th width="15%" class="align-middle">Email</th>
                            <th width="10%" class="align-middle">Thời gian check-out</th>
                            <th width="10%" class="align-middle">Loại check-out</th>
                            <th width="10%" class="align-middle">Hình thức</th>
                            <th width="10%" class="align-middle">Trạng thái</th>
                            <th width="10%" class="text-center align-middle">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($processedData)) : ?>
                            <?php foreach ($processedData as $item) : ?>
                                <?php 
                                    // Tìm tên sự kiện từ danh sách 
                                    $suKienName = '';
                                    
                                    foreach ($suKienList as $suKien) {
                                        if ($suKien->su_kien_id == $item->getSuKienId()) {
                                            $suKienName = $suKien->ten_su_kien;
                                            break;
                                        }
                                    }
                                ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input checkbox-item cursor-pointer" 
                                            type="checkbox" name="selected_items[]" 
                                            value="<?= $item->getId() ?>">
                                        </div>
                                    </td>
                                    <td><?= esc($item->getId()) ?></td>  
                                    <td><?= esc($suKienName) ?></td> 
                                    <td><?= esc($item->getHoTen()) ?></td>
                                    <td><?= esc($item->getEmail()) ?></td>
                                    <td><?= esc($item->getThoiGianCheckOutFormatted()) ?></td>
                                    <td><?= esc($item->getCheckoutTypeText()) ?></td>
                                    <td><?= esc($item->getHinhThucThamGiaText()) ?></td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle 
                                                <?php
                                                    $status = $item->getStatus();
                                                    if ($status == 1) echo 'btn-success';
                                                    elseif ($status == 0) echo 'btn-danger';
                                                    elseif ($status == 2) echo 'btn-warning';
                                                    else echo 'btn-secondary';
                                                ?>"
                                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <?= $item->getStatusText() ?>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item <?= $status == 1 ? 'active' : '' ?>" 
                                                    href="<?= site_url($module_name . '/updateStatus/' . $item->getId() . '/1?return_url=' . current_url()) ?>">
                                                    Hoạt động
                                                </a></li>
                                                <li><a class="dropdown-item <?= $status == 0 ? 'active' : '' ?>"
                                                    href="<?= site_url($module_name . '/updateStatus/' . $item->getId() . '/0?return_url=' . current_url()) ?>">
                                                    Vô hiệu
                                                </a></li>
                                                <li><a class="dropdown-item <?= $status == 2 ? 'active' : '' ?>"
                                                    href="<?= site_url($module_name . '/updateStatus/' . $item->getId() . '/2?return_url=' . current_url()) ?>">
                                                    Đang xử lý
                                                </a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 action-btn-group">
                                            <a href="<?= site_url($module_name . "/view/{$item->getId()}") ?>" class="btn btn-info btn-sm w-100 h-100" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="bx bx-info-circle text-white"></i>
                                            </a>
                                            <a href="<?= site_url($module_name . "/edit/{$item->getId()}") ?>" class="btn btn-primary btn-sm w-100 h-100" data-bs-toggle="tooltip" title="Sửa">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete w-100 h-100" 
                                                    data-id="<?= $item->getId() ?>" 
                                                    data-name="check-out của <?= esc($item->getHoTen()) ?> cho sự kiện <?= esc($suKienName) ?>"
                                                    data-bs-toggle="tooltip" title="Xóa">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="10" class="text-center py-3">
                                    <div class="empty-state">
                                        <i class="bx bx-folder-open"></i>
                                        <p>Không có dữ liệu</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php if (!empty($processedData)): ?>
            <div class="card-footer d-flex flex-wrap justify-content-between align-items-center py-2">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info">
                        Hiển thị từ <?= (($pager->getCurrentPage() - 1) * $perPage + 1) ?> đến <?= min(($pager->getCurrentPage() - 1) * $perPage + $perPage, $total) ?> trong số <?= $total ?> bản ghi
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="d-flex justify-content-end align-items-center">
                        <div class="me-2">  
                            <select id="perPageSelect" class="form-select form-select-sm d-inline-block" style="width: auto;">
                                <option value="5" <?= $perPage == 5 ? 'selected' : '' ?>>5</option>
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                                <option value="15" <?= $perPage == 15 ? 'selected' : '' ?>>15</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                            </select>
                            <span class="ms-1">bản ghi/trang</span>
                        </div>
                        <div>
                            <?= $pager->render() ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa:</p>
                <p class="text-center fw-bold" id="delete-item-name"></p>
                <div class="alert alert-warning mt-3">
                    <i class="bx bx-info-circle me-1"></i> Dữ liệu sẽ được chuyển vào thùng rác và có thể khôi phục.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open('', ['id' => 'delete-form']) ?>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa nhiều -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa nhiều</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa <span id="selected-count" class="fw-bold"></span> bản ghi đã chọn?</p>
                <div class="alert alert-warning mt-3">
                    <i class="bx bx-info-circle me-1"></i> Dữ liệu sẽ được chuyển vào thùng rác và có thể khôi phục.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-delete-multiple" class="btn btn-danger">Xóa</button>
            </div>
        </div>
    </div>
</div>

<script>
    var base_url = '<?= site_url() ?>';
</script>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= page_js('table', $module_name) ?>
<?= page_section_js('table', $module_name) ?>
<?= page_table_js($module_name) ?>
<?= $this->endSection() ?> 