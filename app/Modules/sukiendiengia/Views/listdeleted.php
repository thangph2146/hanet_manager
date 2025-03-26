<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('table') ?>
<?= page_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÙNG RÁC - ĐIỂM DANH SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thùng rác - Tham gia sự kiện',
	'dashboard_url' => site_url($module_name),
	'breadcrumbs' => [
		['title' => 'Quản lý Tham Gia Sự Kiện', 'url' => site_url($module_name)],
		['title' => 'Thùng rác', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/' . $module_name), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách tham gia sự kiện đã xóa</h5>
        <div>
            <button type="button" class="btn btn-sm btn-outline-primary me-2" id="refresh-table">
                <i class='bx bx-refresh'></i> Làm mới
            </button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bx-export'></i> Xuất
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= site_url($module_name . '/exportDeletedExcel' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" id="export-excel">Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="export-pdf">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-3 bg-light border-bottom">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <a href="<?= site_url($module_name) ?>" class="btn btn-outline-primary btn-sm">
                        <i class='bx bx-arrow-back'></i> Danh sách tham gia sự kiện
                    </a>
                    <form id="form-restore-multiple" action="<?= site_url($module_name . '/restoreMultiple') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="button" id="restore-selected" class="btn btn-success btn-sm me-2" disabled>
                            <i class='bx bx-revision'></i> Khôi phục mục đã chọn
                        </button>
                    </form>
                    
                    <form id="form-delete-multiple" action="<?= site_url($module_name . '/permanentDeleteMultiple') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="button" id="delete-permanent-multiple" class="btn btn-danger btn-sm" disabled>
                            <i class='bx bx-trash'></i> Xóa vĩnh viễn
                        </button>
                    </form>

                   
                </div>
                <div class="col-12 col-md-6">
                    <form action="<?= site_url($module_name . '/listdeleted') ?>" method="get" id="search-form">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="perPage" value="<?= $perPage ?>">
                        <div class="input-group search-box">
                            <input type="text" class="form-control form-control-sm" id="table-search" name="keyword" placeholder="Tìm kiếm..." value="<?= $keyword ?? '' ?>">
                            <select name="phuong_thuc_diem_danh" class="form-select form-select-sm" style="max-width: 150px;">
                                <option value="">-- Phương thức --</option>
                                <option value="qr_code" <?= (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh == 'qr_code') ? 'selected' : '' ?>>QR Code</option>
                                <option value="face_id" <?= (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh == 'face_id') ? 'selected' : '' ?>>Face ID</option>
                                <option value="manual" <?= (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh == 'manual') ? 'selected' : '' ?>>Thủ công</option>
                            </select>
                            <button class="btn btn-outline-secondary btn-sm" type="submit">
                                <i class='bx bx-search'></i>
                            </button>
                            <?php if (!empty($keyword) || (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh !== '')): ?>
                            <a href="<?= site_url($module_name . '/listdeleted') ?>" class="btn btn-outline-danger btn-sm">
                                <i class='bx bx-x'></i>
                            </a>
                            <?php endif; ?>
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
        
        <?php if (!empty($keyword) || (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh !== '')): ?>
            <div class="alert alert-info m-3">
                <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
                <div class="small">
                    <?php if (!empty($keyword)): ?>
                        <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
                    <?php endif; ?>
                    <?php if (isset($phuong_thuc_diem_danh) && $phuong_thuc_diem_danh !== ''): ?>
                        <span class="badge bg-info me-2">Phương thức: 
                            <?php 
                                if ($phuong_thuc_diem_danh == 'qr_code') echo 'QR Code';
                                elseif ($phuong_thuc_diem_danh == 'face_id') echo 'Face ID';
                                else echo 'Thủ công';
                            ?>
                        </span>
                    <?php endif; ?>
                    <a href="<?= site_url($module_name . '/listdeleted') ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
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
                            <th width="10%" class="align-middle">ID</th>
                            <th width="15%" class="align-middle">Người dùng</th>
                            <th width="15%" class="align-middle">Sự kiện</th>
                            <th width="15%" class="align-middle">Thời gian điểm danh</th>
                            <th width="10%" class="text-center align-middle">Phương thức</th>
                            <th width="15%" class="text-center align-middle">Ngày xóa</th>
                            <th width="15%" class="text-center align-middle">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data)) : ?>
                            <?php foreach ($data as $item) : ?>
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input checkbox-item cursor-pointer" type="checkbox" name="selected_items[]" value="<?= $item->tham_gia_su_kien_id ?>">
                                        </div>
                                    </td>
                                    <td><?= esc($item->tham_gia_su_kien_id) ?></td>
                                    <td>
                                        <?php if (isset($item->nguoi_dung) && !empty($item->nguoi_dung)): ?>
                                            <span class="d-block fw-medium"><?= esc($item->nguoi_dung->ho_ten ?? '(Không có tên)') ?></span>
                                            <?php if (!empty($item->nguoi_dung->email)): ?>
                                                <small class="text-muted"><?= esc($item->nguoi_dung->email) ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">ID: <?= esc($item->nguoi_dung_id) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (isset($item->su_kien) && !empty($item->su_kien)): ?>
                                            <span class="d-block fw-medium"><?= esc($item->su_kien->ten_su_kien ?? '(Không có tên)') ?></span>
                                            <?php if (!empty($item->su_kien->mo_ta_su_kien)): ?>
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 150px;"><?= esc($item->su_kien->mo_ta_su_kien) ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="text-muted">ID: <?= esc($item->su_kien_id) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= !empty($item->thoi_gian_diem_danh) ? date('d/m/Y H:i:s', strtotime($item->thoi_gian_diem_danh)) : 'Chưa điểm danh' ?></td>
                                    <td class="text-center">
                                        <?php if ($item->phuong_thuc_diem_danh == 'qr_code'): ?>
                                            <span class="badge bg-info">QR Code</span>
                                        <?php elseif ($item->phuong_thuc_diem_danh == 'face_id'): ?>
                                            <span class="badge bg-primary">Face ID</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Thủ công</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <?= !empty($item->deleted_at) ? date('d/m/Y H:i:s', strtotime($item->deleted_at)) : 'N/A' ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 action-btn-group">
                                            <form action="<?= site_url($module_name . '/restore/' . $item->tham_gia_su_kien_id) ?>" method="post">
                                                <?= csrf_field() ?>
                                                <button type="submit" class="btn btn-success btn-sm btn-restore w-100 h-100" 
                                                        data-id="<?= $item->tham_gia_su_kien_id ?>" 
                                                    data-name="ID: <?= esc($item->tham_gia_su_kien_id) ?>"
                                                    data-bs-toggle="tooltip" title="Khôi phục">
                                                <i class="bx bx-revision"></i>
                                            </button>
                                            </form>
                                            <form action="<?= site_url($module_name . '/restore/' . $item->tham_gia_su_kien_id) ?>" method="post" style="display:inline;">
                                                <input type="hidden" name="return_url" value="<?= current_url() . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '') ?>">
                                                <button type="submit" class="btn btn-success btn-sm btn-restore w-100 h-100" 
                                                    data-id="<?= $item->tham_gia_su_kien_id ?>" 
                                                    data-name="ID: <?= esc($item->tham_gia_su_kien_id) ?>"
                                                    data-bs-toggle="tooltip" title="Khôi phục">
                                                    <i class="bx bx-revision mr-0"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete w-100 h-100" 
                                                    data-id="<?= $item->tham_gia_su_kien_id ?>" 
                                                    data-name="ID: <?= esc($item->tham_gia_su_kien_id) ?>"
                                                    data-bs-toggle="tooltip" title="Xóa vĩnh viễn">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="8" class="text-center py-3">
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
        <?php if (!empty($data)): ?>
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

<!-- Modal xác nhận khôi phục -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-revision text-success" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn khôi phục bản ghi tham gia sự kiện:</p>
                <p class="text-center fw-bold" id="restore-item-name"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open('', ['id' => 'restore-form']) ?>
                    <button type="submit" class="btn btn-success">Khôi phục</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa vĩnh viễn -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn bản ghi tham gia sự kiện:</p>
                <p class="text-center fw-bold" id="delete-item-name"></p>
                <div class="alert alert-danger mt-3">
                    <i class="bx bx-info-circle me-1"></i> Cảnh báo: Dữ liệu sẽ bị xóa vĩnh viễn và không thể khôi phục!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <?= form_open('', ['id' => 'delete-form']) ?>
                    <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận khôi phục nhiều -->
<div class="modal fade" id="restoreMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận khôi phục nhiều</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-revision text-success" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn khôi phục <span id="restore-count" class="fw-bold"></span> bản ghi đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-restore-multiple" class="btn btn-success">Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa vĩnh viễn nhiều -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center icon-wrapper mb-3">
                    <i class="bx bx-error-circle text-danger" style="font-size: 4rem;"></i>
                </div>
                <p class="text-center">Bạn có chắc chắn muốn xóa vĩnh viễn <span id="delete-count" class="fw-bold"></span> bản ghi đã chọn?</p>
                <div class="alert alert-danger mt-3">
                    <i class="bx bx-info-circle me-1"></i> Cảnh báo: Dữ liệu sẽ bị xóa vĩnh viễn và không thể khôi phục!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-delete-multiple" class="btn btn-danger">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>


<script>
    var base_url = '<?= site_url() ?>';
</script>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= page_js('table') ?>
<?= page_section_js('table') ?>
<?= page_table_js() ?>
<?= $this->endSection() ?> 