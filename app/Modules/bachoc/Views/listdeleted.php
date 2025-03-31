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
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800"><?= $title ?></h1>
    <?= session()->getFlashdata('message'); ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách đã xóa</h6>
            <div class="d-flex gap-1">
                 <!-- Nút quay lại -->
                 <a href="<?= base_url('admin/' . $module_name) ?>" class="btn btn-secondary btn-sm">
                     <i class="bx bx-arrow-back"></i> Quay lại
                 </a>
                 <!-- Nút Export -->
                 <div class="btn-group">
                     <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                         <i class="bx bx-export"></i> Export Đã Xóa
                     </button>
                     <ul class="dropdown-menu dropdown-menu-end">
                         <li><a class="dropdown-item" href="<?= base_url('admin/' . $module_name . '/exportDeletedPdf') . '?' . http_build_query($searchData ?? []) ?>"><i class="bx bxs-file-pdf me-2"></i> PDF</a></li>
                         <li><a class="dropdown-item" href="<?= base_url('admin/' . $module_name . '/exportDeletedExcel') . '?' . http_build_query($searchData ?? []) ?>"><i class="bx bxs-file-excel me-2"></i> Excel</a></li>
                     </ul>
                 </div>
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
                                <a href="<?= getCurrentUrlWithSort('ten_bac_hoc', $sortParam, true) ?>">
                                    Tên Bậc học <?= $sortIcon ?>
                                </a>
                            </th>
                            <th>
                                <?php 
                                $sortParam = ($sort === 'ma_bac_hoc ASC') ? 'ma_bac_hoc DESC' : 'ma_bac_hoc ASC';
                                $sortIcon = getSortIcon($sort, 'ma_bac_hoc');
                                ?>
                                <a href="<?= getCurrentUrlWithSort('ma_bac_hoc', $sortParam, true) ?>">
                                    Mã Bậc học <?= $sortIcon ?>
                                </a>
                            </th>
                             <th class="text-center">
                                <?php 
                                $sortParam = ($sort === 'deleted_at ASC') ? 'deleted_at DESC' : 'deleted_at ASC';
                                $sortIcon = getSortIcon($sort, 'deleted_at');
                                ?>
                                <a href="<?= getCurrentUrlWithSort('deleted_at', $sortParam, true) ?>">
                                    Ngày xóa <?= $sortIcon ?>
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
                                     <td class="text-center"><?= $item->getDeletedAtFormatted('d/m/Y H:i') ?></td>
                                    <td class="text-center">
                                         <button class="btn btn-primary btn-sm btn-restore" data-id="<?= $item->bac_hoc_id ?>" data-name="<?= esc($item->ten_bac_hoc) ?>"><i class="bx bx-revision"></i> Khôi phục</button>
                                         <button class="btn btn-danger btn-sm btn-delete-permanent" data-id="<?= $item->bac_hoc_id ?>" data-name="<?= esc($item->ten_bac_hoc) ?>"><i class="bx bx-trash"></i> Xóa vĩnh viễn</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="text-center">Thùng rác trống.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Thanh điều hướng và phân trang -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                 <div class="d-flex gap-1">
                     <button class="btn btn-primary btn-sm" id="restore-selected" data-url="<?= base_url('admin/' . $moduleName . '/restoreMultiple') ?>" style="display: none;"><i class="bx bx-revision"></i> Khôi phục mục đã chọn</button>
                     <button class="btn btn-danger btn-sm" id="delete-permanent-multiple" data-url="<?= base_url('admin/' . $moduleName . '/deletePermanentMultiple') ?>" style="display: none;"><i class="bx bx-trash"></i> Xóa vĩnh viễn mục đã chọn</button>
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
    $scriptUrl = base_url($module_name . '/master_scripts.php?module=' . $module_name);
    // Truyền URL vào Javascript
    echo script_tag($route_url_php . '/master_scripts.php?module=' . $module_name);
?>

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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x"></i> Hủy</button>
                <form id="restore-form" action="" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary"><i class="bx bx-revision"></i> Khôi phục</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn -->
<div class="modal fade" id="deletePermanentModal" tabindex="-1" aria-labelledby="deletePermanentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePermanentModalLabel">Xác nhận xóa vĩnh viễn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn bậc học: <span id="delete-permanent-item-name" class="fw-bold"></span>?</p>
                <p class="text-danger mb-0"><i class="bx bx-info-circle"></i> Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x"></i> Hủy</button>
                <form id="delete-permanent-form" action="" method="post">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger"><i class="bx bx-trash"></i> Xóa vĩnh viễn</button>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x"></i> Hủy</button>
                <form id="form-restore-multiple" action="<?= base_url('admin/' . $module_name . '/restoreMultiple') ?>" method="post" style="display: inline;">
                    <?= csrf_field() ?>
                </form>
                <button type="button" id="confirm-restore-multiple" class="btn btn-primary"><i class="bx bx-revision"></i> Khôi phục</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa vĩnh viễn nhiều -->
<div class="modal fade" id="deletePermanentMultipleModal" tabindex="-1" aria-labelledby="deletePermanentMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deletePermanentMultipleModalLabel">Xác nhận xóa vĩnh viễn nhiều mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa vĩnh viễn <span id="delete-permanent-count" class="fw-bold"></span> mục đã chọn?</p>
                <p class="text-danger mb-0"><i class="bx bx-info-circle"></i> Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bx bx-x"></i> Hủy</button>
                <form id="form-delete-permanent-multiple" action="<?= base_url('admin/' . $module_name . '/deletePermanentMultiple') ?>" method="post" style="display: inline;">
                    <?= csrf_field() ?>
                </form>
                <button type="button" id="confirm-delete-permanent-multiple" class="btn btn-danger"><i class="bx bx-trash"></i> Xóa vĩnh viễn</button>
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

<?php $this->endSection() ?>

<?= $this->section('script') ?>
<?= $masterScript->pageJs('table') ?>
<?= $masterScript->pageSectionJs('table') ?>
<?= $masterScript->pageTableJs() ?>
<?= $this->endSection() ?>

<?php 
// Hàm helper để tạo URL sắp xếp (cập nhật để hoạt động trên trang listdeleted)
function getCurrentUrlWithSort($field, $direction, $isDeletedList = false) {
    $request = service('request');
    $baseUrl = $isDeletedList ? base_url($request->getUri()->getPath()) : current_url();
    $queryParams = $request->getGet();
    $queryParams['sort'] = $field . ' ' . $direction;
    unset($queryParams['page']); 
    return $baseUrl . '?' . http_build_query($queryParams);
}

// Hàm helper để lấy icon sắp xếp
function getSortIcon($currentSort, $field) {
    if ($currentSort === $field . ' ASC') {
        return '<i class="bx bx-sort-up"></i>';
    } elseif ($currentSort === $field . ' DESC') {
        return '<i class="bx bx-sort-down"></i>';
    } else {
        return '<i class="bx bx-sort"></i>'; // Icon mặc định
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