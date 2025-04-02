<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('table') ?>
<?= page_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách sự kiện',
	'dashboard_url' => site_url($module_name),
	'breadcrumbs' => [
		['title' => 'Quản lý Sự kiện', 'url' => site_url($module_name)],
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
        <h5 class="card-title mb-0">Danh sách sự kiện</h5>
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
                                <?php if (!empty($keyword) || isset($_GET['trang_thai_tham_gia']) || isset($_GET['su_kien_id']) || isset($_GET['dien_gia_id']) || isset($_GET['hien_thi_cong_khai'])): ?>
                                <a href="<?= site_url($module_name) ?>" class="btn btn-outline-danger btn-sm">
                                    <i class='bx bx-x'></i>
                                </a>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                
                                <!-- Bộ lọc loại sự kiện -->
                                <select name="loai_su_kien_id" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Tất cả loại sự kiện</option>
                                    <?php foreach ($loaiSuKienList as $loai): ?>
                                    <option value="<?= $loai->loai_su_kien_id ?>" <?= (isset($_GET['loai_su_kien_id']) && $_GET['loai_su_kien_id'] == $loai->loai_su_kien_id) ? 'selected' : '' ?>><?= esc($loai->ten_loai_su_kien) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                
                                <!-- Bộ lọc hình thức -->
                                <select name="hinh_thuc" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Tất cả hình thức</option>
                                    <option value="offline" <?= (isset($_GET['hinh_thuc']) && $_GET['hinh_thuc'] === 'offline') ? 'selected' : '' ?>>Trực tiếp</option>
                                    <option value="online" <?= (isset($_GET['hinh_thuc']) && $_GET['hinh_thuc'] === 'online') ? 'selected' : '' ?>>Trực tuyến</option>
                                    <option value="hybrid" <?= (isset($_GET['hinh_thuc']) && $_GET['hinh_thuc'] === 'hybrid') ? 'selected' : '' ?>>Kết hợp</option>
                                </select>
                                
                                <!-- Bộ lọc trạng thái -->
                                <select name="status" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="1" <?= (isset($_GET['status']) && $_GET['status'] === '1') ? 'selected' : '' ?>>Hoạt động</option>
                                    <option value="0" <?= (isset($_GET['status']) && $_GET['status'] === '0') ? 'selected' : '' ?>>Không hoạt động</option>
                                </select>
                                
                                <!-- Bộ lọc trạng thái tham gia -->
                                <select name="trang_thai_tham_gia" class="form-select form-select-sm" style="max-width: 200px;" onchange="this.form.submit()">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="xac_nhan" <?= (isset($_GET['trang_thai_tham_gia']) && $_GET['trang_thai_tham_gia'] === 'xac_nhan') ? 'selected' : '' ?>>Đã xác nhận</option>
                                    <option value="cho_xac_nhan" <?= (isset($_GET['trang_thai_tham_gia']) && $_GET['trang_thai_tham_gia'] === 'cho_xac_nhan') ? 'selected' : '' ?>>Chờ xác nhận</option>
                                    <option value="tu_choi" <?= (isset($_GET['trang_thai_tham_gia']) && $_GET['trang_thai_tham_gia'] === 'tu_choi') ? 'selected' : '' ?>>Từ chối</option>
                                    <option value="khong_lien_he_duoc" <?= (isset($_GET['trang_thai_tham_gia']) && $_GET['trang_thai_tham_gia'] === 'khong_lien_he_duoc') ? 'selected' : '' ?>>Không liên hệ được</option>
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
        
        <?php if (!empty($keyword) || isset($_GET['loai_su_kien_id']) || isset($_GET['hinh_thuc']) || isset($_GET['status'])): ?>
            <div class="alert alert-info m-3">
                <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
                <div class="small">
                    <?php if (!empty($keyword)): ?>
                        <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
                    <?php endif; ?>
                    <?php if (isset($_GET['loai_su_kien_id']) && !empty($_GET['loai_su_kien_id'])): ?>
                        <?php 
                        $loaiSuKienName = '';
                        foreach ($loaiSuKienList as $loai) {
                            if ($loai->loai_su_kien_id == $_GET['loai_su_kien_id']) {
                                $loaiSuKienName = $loai->ten_loai_su_kien;
                                break;
                            }
                        }
                        ?>
                        <span class="badge bg-primary me-2">Loại sự kiện: <?= esc($loaiSuKienName) ?></span>
                    <?php endif; ?>
                    <?php if (isset($_GET['hinh_thuc']) && !empty($_GET['hinh_thuc'])): ?>
                        <?php 
                        $hinhThucText = [
                            'offline' => 'Trực tiếp',
                            'online' => 'Trực tuyến',
                            'hybrid' => 'Kết hợp'
                        ];
                        ?>
                        <span class="badge bg-primary me-2">Hình thức: <?= esc($hinhThucText[$_GET['hinh_thuc']] ?? $_GET['hinh_thuc']) ?></span>
                    <?php endif; ?>
                    <?php if (isset($_GET['status']) && $_GET['status'] !== ''): ?>
                        <span class="badge bg-primary me-2">Trạng thái: <?= $_GET['status'] == '1' ? 'Hoạt động' : 'Không hoạt động' ?></span>
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
                            <th width="20%" class="align-middle">Tên sự kiện</th>
                            <th width="10%" class="align-middle">Thời gian bắt đầu</th>
                            <th width="10%" class="align-middle">Thời gian kết thúc</th>
                            <th width="10%" class="align-middle">Địa điểm</th>
                            <th width="10%" class="align-middle">Loại sự kiện</th>
                            <th width="5%" class="align-middle">Hình thức</th>
                            <th width="5%" class="align-middle">Tổng đăng ký</th>
                            <th width="5%" class="align-middle">Trạng thái</th>
                            <th width="15%" class="text-center align-middle">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($processedData)) : ?>
                            <?php foreach ($processedData as $item) : ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input checkbox-item cursor-pointer" 
                                            type="checkbox" name="selected_items[]" 
                                            value="<?= $item->getId() ?>">
                                        </div>
                                    </td>
                                    <td><?= esc($item->getId()) ?></td>  
                                    <td>
                                        <a href="<?= site_url($module_name . "/view/{$item->getId()}") ?>" class="fw-bold text-primary">
                                            <?= esc($item->getTenSuKien()) ?>
                                        </a>
                                        <?php if (!empty($item->getSlug())): ?>
                                            <div class="small text-muted">
                                                <i class="bx bx-link-alt"></i> <?= esc($item->getSlug()) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?= esc($item->thoi_gian_bat_dau_formatted) ?>
                                    </td>
                                    <td>
                                        <?= esc($item->thoi_gian_ket_thuc_formatted) ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($item->getDiaDiem())): ?>
                                            <?= esc($item->getDiaDiem()) ?>
                                            <?php if (!empty($item->getDiaChiCuThe())): ?>
                                                <div class="small text-muted"><?= esc($item->getDiaChiCuThe()) ?></div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">Chưa cập nhật</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($item->loaiSuKien)): ?>
                                            <?= esc($item->loaiSuKien->ten_loai_su_kien) ?>
                                        <?php else: ?>
                                            <span class="text-muted">Không xác định</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $item->getHinhThuc() === 'offline' ? 'success' : ($item->getHinhThuc() === 'online' ? 'info' : 'warning') ?>">
                                            <?= esc($item->hinh_thuc_text) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary"><?= esc($item->getTongDangKy()) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $item->getStatus() ? 'success' : 'danger' ?>">
                                            <?= esc($item->status_text) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 action-btn-group">
                                            <a href="<?= site_url($module_name . "/view/{$item->getId()}") ?>" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="bx bx-info-circle text-white"></i>
                                            </a>
                                            <a href="<?= site_url($module_name . "/edit/{$item->getId()}") ?>" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Sửa">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                                    data-id="<?= $item->getId() ?>" 
                                                    data-name="<?= esc($item->getTenSuKien()) ?>"
                                                    data-bs-toggle="tooltip" title="Xóa">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="11" class="text-center py-3">
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
        
        <?php if (isset($pager) && $pager !== null) : ?>
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-3 py-2 px-3">
                <div class="d-flex align-items-center">
                    <label for="perPage" class="me-2 mb-0 small">Hiển thị:</label>
                    <select class="form-select form-select-sm me-2" id="perPage" style="width: 70px;">
                        <?php foreach ([10, 25, 50, 100] as $option) : ?>
                            <option value="<?= $option ?>" <?= $perPage == $option ? 'selected' : '' ?>><?= $option ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="small">Tổng số: <strong><?= $total ?></strong> bản ghi</span>
                </div>
                
                <nav>
                    <?= $pager->render() ?>
                </nav>
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