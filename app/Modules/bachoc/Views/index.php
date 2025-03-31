<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị route_url từ controller hoặc sử dụng giá trị mặc định
$route_url = isset($route_url) ? $route_url : 'admin/bachoc';
$module_name = isset($module_name) ? $module_name : 'bachoc';
$route_url_php = $route_url;

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\bachoc\Libraries\MasterScript($route_url, $module_name);
?>
<?= $masterScript->pageCss('table') ?>
<?= $masterScript->pageSectionCss('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách bậc học',
	'dashboard_url' => site_url($route_url),
	'breadcrumbs' => [
		['title' => 'Quản lý Bậc Học', 'url' => site_url($route_url)],
		['title' => 'Danh sách', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/' . $route_url . '/new'), 'title' => 'Thêm mới', 'icon' => 'bx bx-plus-circle']
	]
]) ?>
<?= $this->endSection() ?>  

<?= $this->section('content') ?>
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"><?= $title ?></h1>
    <?= session()->getFlashdata('message'); ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách</h6>
            <div class="d-flex gap-1">
                 <!-- Nút thêm mới -->
                 <a href="<?= base_url('admin/' . $module_name . '/new') ?>" class="btn btn-primary btn-sm">
                     <i class="fas fa-plus"></i> Thêm mới
                 </a>
                 <!-- Nút Export -->
                 <div class="btn-group">
                     <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                         <i class="fas fa-file-export"></i> Export
                     </button>
                     <ul class="dropdown-menu dropdown-menu-end">
                         <li><a class="dropdown-item" href="<?= base_url('admin/' . $module_name . '/exportPdf') . '?' . http_build_query($searchData ?? []) ?>"><i class="fas fa-file-pdf me-2"></i> PDF</a></li>
                         <li><a class="dropdown-item" href="<?= base_url('admin/' . $module_name . '/exportExcel') . '?' . http_build_query($searchData ?? []) ?>"><i class="fas fa-file-excel me-2"></i> Excel</a></li>
                     </ul>
                 </div>
                 <!-- Nút Thùng rác -->
                 <a href="<?= base_url('admin/' . $module_name . '/listdeleted') ?>" class="btn btn-secondary btn-sm">
                     <i class="fas fa-trash"></i> Thùng rác
                 </a>
            </div>
        </div>
        <div class="card-body">
            <?php 
                // Khởi tạo thư viện FilterForm
                $filterForm = new \App\Modules\bachoc\Libraries\FilterForm();
                // Dữ liệu cần truyền vào form: chỉ cần cấu hình các trường
                $filterData = [
                    // 'search' và 'filters' sẽ được thư viện tự động lấy từ request
                    'searchFields' => $searchFields ?? [], // Các trường có thể tìm kiếm
                    'filterFields' => $filterFields ?? [], // Các trường có thể lọc
                    'moduleName' => $moduleName ?? 'bachoc' // Tên module (nếu cần)
                ];
            ?>
            <!-- Render form bằng thư viện -->
            <?= $filterForm->render($filterData) ?>
            
            <!-- Bảng dữ liệu -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 30px;">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th>
                                <?php 
                                $sortParam = ($sort === 'ten_bac_hoc ASC') ? 'ten_bac_hoc DESC' : 'ten_bac_hoc ASC';
                                $sortIcon = getSortIcon($sort, 'ten_bac_hoc');
                                ?>
                                <a href="<?= getCurrentUrlWithSort('ten_bac_hoc', $sortParam) ?>">
                                    Tên Bậc học <?= $sortIcon ?>
                                </a>
                            </th>
                            <th>
                                <?php 
                                $sortParam = ($sort === 'ma_bac_hoc ASC') ? 'ma_bac_hoc DESC' : 'ma_bac_hoc ASC';
                                $sortIcon = getSortIcon($sort, 'ma_bac_hoc');
                                ?>
                                <a href="<?= getCurrentUrlWithSort('ma_bac_hoc', $sortParam) ?>">
                                    Mã Bậc học <?= $sortIcon ?>
                                </a>
                            </th>
                            <th class="text-center">
                                <?php 
                                $sortParam = ($sort === 'status ASC') ? 'status DESC' : 'status ASC';
                                $sortIcon = getSortIcon($sort, 'status');
                                ?>
                                <a href="<?= getCurrentUrlWithSort('status', $sortParam) ?>">
                                    Trạng thái <?= $sortIcon ?>
                                </a>
                            </th>
                            <th class="text-center">
                                <?php 
                                $sortParam = ($sort === 'created_at ASC') ? 'created_at DESC' : 'created_at ASC';
                                $sortIcon = getSortIcon($sort, 'created_at');
                                ?>
                                <a href="<?= getCurrentUrlWithSort('created_at', $sortParam) ?>">
                                    Ngày tạo <?= $sortIcon ?>
                                </a>
                            </th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($items)) : ?>
                            <?php foreach ($items as $item) : ?>
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="ids[]" class="checkbox-item" value="<?= $item->bac_hoc_id ?>">
                                    </td>
                                    <td><?= esc($item->ten_bac_hoc) ?></td>
                                    <td><?= esc($item->ma_bac_hoc) ?></td>
                                    <td class="text-center"><?= $item->getStatusLabel() ?></td>
                                    <td class="text-center"><?= $item->getCreatedAtFormatted('d/m/Y H:i') ?></td>
                                    <td class="text-center">
                                        <a href="<?= base_url('admin/' . $module_name . '/show/' . $item->bac_hoc_id) ?>" class="btn btn-info btn-sm text-white">Xem</a>
                                        <a href="<?= base_url('admin/' . $module_name . '/edit/' . $item->bac_hoc_id) ?>" class="btn btn-primary btn-sm">Sửa</a>
                                        <button class="btn btn-danger btn-sm btn-delete" data-id="<?= $item->bac_hoc_id ?>" data-name="<?= esc($item->ten_bac_hoc) ?>" data-url="<?= base_url('admin/' . $module_name . '/delete/' . $item->bac_hoc_id) ?>">Xóa</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">Không tìm thấy dữ liệu.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Thanh điều hướng và phân trang -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="d-flex gap-1">
                    <button class="btn btn-danger btn-sm" id="delete-selected-multiple" data-url="<?= base_url('admin/' . $module_name . '/deleteMultiple') ?>" style="display: none;">
                        <i class="bx bx-trash"></i> Xóa mục đã chọn
                    </button>
                    <button class="btn btn-primary btn-sm" id="status-selected-multiple" data-url="<?= base_url('admin/' . $module_name . '/statusMultiple') ?>" style="display: none;">
                        <i class="bx bx-toggle-right"></i> Đổi trạng thái mục đã chọn
                    </button>
                </div>
                <!-- Hiển thị phân trang -->
                <div class="pagination-container">
                    <?= $pager // Hiển thị links phân trang ?>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Scripts -->
<?php 
    // Tải HTML helper để sử dụng script_tag()
    helper('html');

    // Cách mới: Sử dụng moduleName làm namespace
// Truyền URL và namespace vào Javascript
$scriptUrl = base_url($module_name . '/master_scripts.php?module=' . $module_name);
// Truyền URL vào Javascript
echo script_tag($route_url_php . '/master_scripts.php?module=' . $module_name);
?>

<?php 
// Hàm helper để tạo URL sắp xếp
function getCurrentUrlWithSort($field, $direction) {
    $currentUrl = current_url();
    $queryParams = service('request')->getGet();
    $queryParams['sort'] = $field . ' ' . $direction;
    // Loại bỏ page để quay về trang đầu khi sắp xếp
    unset($queryParams['page']); 
    return $currentUrl . '?' . http_build_query($queryParams);
}

// Hàm helper để lấy icon sắp xếp
function getSortIcon($currentSort, $field) {
    if ($currentSort === $field . ' ASC') {
        return '<i class="fas fa-sort-up"></i>';
    } elseif ($currentSort === $field . ' DESC') {
        return '<i class="fas fa-sort-down"></i>';
    } else {
        return '<i class="fas fa-sort text-muted"></i>'; // Icon mặc định
    }
}

// Chuẩn bị dữ liệu tìm kiếm/lọc/sắp xếp cho link export
$searchData = [
    'search' => $search ?? '',
    'filters' => $filters ?? [],
    'sort' => $sort ?? ''
];
// Loại bỏ các giá trị rỗng khỏi mảng filter
if (isset($searchData['filters']) && is_array($searchData['filters'])) {
    $searchData['filters'] = array_filter($searchData['filters'], function($value) {
        return $value !== '' && $value !== null;
    });
}
?>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa bậc học: <span id="delete-item-name" class="fw-bold"></span>?</p>
                <p class="text-danger mb-0"><i class="bx bx-info-circle"></i> Lưu ý: Hành động này có thể ảnh hưởng đến dữ liệu liên quan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Hủy</button>
                <form id="delete-form" action="" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa nhiều -->
<div class="modal fade" id="deleteMultipleModal" tabindex="-1" aria-labelledby="deleteMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteMultipleModalLabel">Xác nhận xóa nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa <span id="selected-count" class="fw-bold"></span> mục đã chọn?</p>
                <p class="text-danger mb-0"><i class="bx bx-info-circle"></i> Lưu ý: Hành động này có thể ảnh hưởng đến dữ liệu liên quan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x"></i> Hủy</button>
                <form id="form-delete-multiple" action="<?= base_url('admin/' . $module_name . '/deleteMultiple') ?>" method="post" style="display: inline;">
                    <?= csrf_field() ?>
                </form>
                <button type="button" id="confirm-delete-multiple" class="btn btn-danger"><i class="bx bx-trash"></i> Xóa</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận đổi trạng thái nhiều -->
<div class="modal fade" id="statusMultipleModal" tabindex="-1" aria-labelledby="statusMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusMultipleModalLabel">Xác nhận đổi trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn thay đổi trạng thái của <span id="status-count" class="fw-bold"></span> mục đã chọn?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x"></i> Hủy</button>
                <form id="form-status-multiple" action="<?= base_url('admin/' . $module_name . '/statusMultiple') ?>" method="post" style="display: inline;">
                    <?= csrf_field() ?>
                </form>
                <button type="button" id="confirm-status-multiple" class="btn btn-primary"><i class="bx bx-check"></i> Xác nhận</button>
            </div>
        </div>
    </div>
</div>

<!-- Loading indicator -->
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