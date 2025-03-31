<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị route_url từ controller hoặc sử dụng giá trị mặc định
$route_url = isset($route_url) ? $route_url : 'admin/bachoc';
$module_name = isset($module_name) ? $module_name : 'bachoc';

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\bachoc\Libraries\MasterScript($route_url, $module_name);
?>
<?= $masterScript->pageCss('table') ?>
<?= $masterScript->pageSectionCss('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÙNG RÁC - BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thùng rác - Bậc học',
	'dashboard_url' => site_url($route_url),
	'breadcrumbs' => [
		['title' => 'Quản lý Bậc học', 'url' => site_url($route_url)],
		['title' => 'Thùng rác', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/' . $route_url), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Danh sách bậc học đã xóa</h5>
        <div>
            <button type="button" class="btn btn-sm btn-outline-primary me-2" id="refresh-table">
                <i class='bx bx-refresh'></i> Làm mới
            </button>
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class='bx bx-export'></i> Xuất
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="<?= site_url($route_url . '/exportDeletedExcel' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" id="export-excel">Excel</a></li>
                    <li><a class="dropdown-item" href="<?= site_url($route_url . '/exportDeletedPdf' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')) ?>" id="export-pdf">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="p-3 bg-light border-bottom">
            <div class="row">
                <div class="col-12 col-md-6 mb-2 mb-md-0">
                    <a href="<?= site_url($route_url) ?>" class="btn btn-outline-primary btn-sm">
                        <i class='bx bx-arrow-back'></i> Danh sách bậc học
                    </a>
                    <form id="form-restore-multiple" action="<?= site_url($route_url . '/restoreMultiple') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="button" id="restore-selected" class="btn btn-success btn-sm me-2" disabled>
                            <i class='bx bx-revision'></i> Khôi phục mục đã chọn
                        </button>
                    </form>
                    
                    <form id="form-delete-multiple" action="<?= site_url($route_url . '/permanentDeleteMultiple') ?>" method="post" class="d-inline">
                        <?= csrf_field() ?>
                        <button type="button" id="delete-permanent-multiple" class="btn btn-danger btn-sm" disabled>
                            <i class='bx bx-trash'></i> Xóa vĩnh viễn
                        </button>
                    </form>

                   
                </div>
                <div class="col-12 col-md-6">
                    <form action="<?= site_url($route_url . '/listdeleted') ?>" method="get" id="search-form">
                        <input type="hidden" name="page" value="1">
                        <input type="hidden" name="perPage" value="<?= $perPage ?>">
                        <div class="input-group search-box">
                            <input type="text" class="form-control form-control-sm" id="table-search" name="keyword" placeholder="Tìm kiếm..." value="<?= $keyword ?? '' ?>">
                            <button class="btn btn-outline-secondary btn-sm" type="submit">
                                <i class='bx bx-search'></i>
                            </button>
                            <?php if (!empty($keyword)): ?>
                            <a href="<?= site_url($route_url . '/listdeleted') ?>" class="btn btn-outline-danger btn-sm">
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
        
        <?php if (!empty($keyword)): ?>
            <div class="alert alert-info m-3">
                <h6 class="mb-1"><i class="bx bx-filter-alt me-1"></i> Kết quả tìm kiếm:</h6>
                <div class="small">
                    <?php if (!empty($keyword)): ?>
                        <span class="badge bg-primary me-2">Từ khóa: <?= esc($keyword) ?></span>
                    <?php endif; ?>
                    <a href="<?= site_url($route_url . '/listdeleted') ?>" class="text-decoration-none"><i class="bx bx-x"></i> Xóa bộ lọc</a>
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
                            <th width="20%" class="align-middle">Bậc học</th>
                            <th width="10%" class="align-middle">Mã bậc học</th>
                            <th width="10%" class="text-center align-middle">Trạng thái</th>
                            <th width="15%" class="text-center align-middle">Ngày xóa</th>
                            <th width="20%" class="text-center align-middle">Thao tác</th>
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
                                            value="<?= $item->bac_hoc_id ?>">
                                        </div>
                                    </td>
                                    <td><?= esc($item->bac_hoc_id) ?></td>  
                                    <td><?= esc($item->ten_bac_hoc) ?></td> 
                                    <td><?= esc($item->ma_bac_hoc) ?></td>
                                    <td class="text-center">
                                        <?= $item->getStatusLabel() ?>
                                    </td>
                                    <td class="text-center">
                                        <?= $item->getDeletedAtFormatted() ?>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1 action-btn-group">
                                            <button type="button" class="btn btn-success btn-sm btn-restore w-100 h-100" 
                                                    data-id="<?= $item->bac_hoc_id ?>" 
                                                    data-name="<?= esc($item->ten_bac_hoc) ?>"
                                                    data-bs-toggle="tooltip" title="Khôi phục">
                                                <i class="bx bx-revision"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm btn-delete w-100 h-100" 
                                                    data-id="<?= $item->bac_hoc_id ?>" 
                                                    data-name="<?= esc($item->ten_bac_hoc) ?>"
                                                    data-bs-toggle="tooltip" title="Xóa vĩnh viễn">
                                                <i class="bx bx-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center py-3">
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
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>
                        <div class="pagination-container">
                            <?php if ($pager) : ?>
                                <?= $pager->links() ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Xác nhận khôi phục -->
<div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn khôi phục bậc học: <span id="restore-item-name" class="fw-bold"></span>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="restore-form" action="" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-success">Khôi phục</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận khôi phục nhiều -->
<div class="modal fade" id="restoreMultipleModal" tabindex="-1" aria-labelledby="restoreMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="restoreMultipleModalLabel">Xác nhận khôi phục nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn khôi phục <span id="restore-count" class="fw-bold"></span> mục đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-restore-multiple" class="btn btn-success">Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn bậc học: <span id="delete-item-name" class="fw-bold"></span>?</p>
                <p class="text-danger mb-0"><i class="bx bx-error-circle"></i> Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="delete-form" action="" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn nhiều -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-labelledBy="deleteMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMultipleModalLabel">Xác nhận xóa vĩnh viễn nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn <span id="selected-count" class="fw-bold"></span> mục đã chọn?</p>
                <p class="text-danger mb-0"><i class="bx bx-error-circle"></i> Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" id="confirm-delete-multiple" class="btn btn-danger">Xóa vĩnh viễn</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading indicator (hidden by default) -->
<div id="loading-indicator" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div class="spinner-border text-light" role="status">
        <span class="visually-hidden">Đang tải...</span>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $masterScript->pageJs('table') ?>
<?= $masterScript->pageSectionJs('table') ?>
<?= $masterScript->pageTableJs() ?>
<?= $this->endSection() ?> 