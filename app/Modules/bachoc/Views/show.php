<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị route_url từ controller hoặc sử dụng giá trị mặc định
$route_url = isset($route_url) ? $route_url : 'admin/bachoc';
$module_name = isset($module_name) ? $module_name : 'bachoc';

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\bachoc\Libraries\MasterScript($route_url, $module_name);
?>
<?= $masterScript->pageCss('view') ?>
<?= $masterScript->pageSectionCss('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?php
$actions = [
    ['url' => site_url($route_url), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
];

// Chỉ thêm nút chỉnh sửa nếu có dữ liệu
if (isset($item) && isset($item->bac_hoc_id)) {
    $actions[] = ['url' => site_url($route_url . '/edit/' . $item->bac_hoc_id), 'title' => 'Chỉnh sửa', 'icon' => 'bx bx-edit'];
}
?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết bậc học',
    'dashboard_url' => site_url($route_url),
    'breadcrumbs' => [
        ['title' => 'Quản lý Bậc Học', 'url' => site_url($route_url)],
        ['title' => 'Chi tiết', 'active' => true]
    ],
    'actions' => $actions
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<?php if (!isset($item)): ?>
    <div class="alert alert-danger">
        <i class="bx bx-error-circle me-2"></i>Không tìm thấy thông tin bậc học.
    </div>
<?php else: ?>
    <div class="card shadow-sm">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Chi tiết bậc học ID: <?= esc($item->bac_hoc_id) ?></h5>
            <div class="d-flex gap-2">  
                <a href="<?= site_url($route_url . '/edit/' . $item->bac_hoc_id) ?>" class="btn btn-sm btn-primary">
                    <i class="bx bx-edit me-1"></i> Chỉnh sửa
                </a>
                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bx bx-trash me-1"></i> Xóa
                </button>
            </div>
        </div>
        <div class="card-body">
            <?php if (session()->has('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 200px;">ID</th>
                            <td><?= esc($item->bac_hoc_id) ?></td>
                        </tr>
                        <tr>
                            <th>Tên bậc học</th>
                            <td>
                                <?php if (!empty($item->ten_bac_hoc)): ?>
                                    <?= esc($item->ten_bac_hoc) ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Chưa cập nhật</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Mã bậc học</th>
                            <td>
                                <?php if (!empty($item->ma_bac_hoc)): ?>
                                    <?= esc($item->ma_bac_hoc) ?>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">Chưa cập nhật</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Trạng thái</th>
                            <td>
                                <?= $item->getStatusLabel() ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Ngày tạo</th>
                            <td><?= $item->getCreatedAtFormatted() ?></td>
                        </tr>
                        <tr>
                            <th>Cập nhật lần cuối</th>
                            <td><?= $item->getUpdatedAtFormatted() ?></td>
                        </tr>
                        <tr>
                            <th>Ngày xóa</th>
                            <td><?= $item->getDeletedAtFormatted() ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Xác nhận xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa bậc học <strong><?= esc($item->ten_bac_hoc) ?></strong> không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <a href="<?= site_url($route_url . '/delete/' . $item->bac_hoc_id) ?>" class="btn btn-danger">Xóa</a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('script_ext') ?>
<?= $masterScript->pageJs('view') ?>
<?= $this->endSection() ?>
