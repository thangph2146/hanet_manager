<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
 
$module_name = isset($module_name) ? $module_name : 'quanlybachoc';

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\quanlybachoc\Libraries\MasterScript($module_name);
?>
<?= $masterScript->pageCss('view') ?>
<?= $masterScript->pageSectionCss('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết bậc học',
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => 'Quản lý Bậc Học', 'url' => site_url($module_name)],
        ['title' => 'Chi tiết', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url($module_name . '/edit/' . $data->bac_hoc_id), 'title' => 'Chỉnh sửa', 'icon' => 'bx bx-edit'],
        ['url' => site_url($module_name), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Chi tiết bậc học ID: <?= esc($data->bac_hoc_id) ?></h5>
        <div class="d-flex gap-2">  
            <a href="<?= site_url($module_name . '/edit/' . $data->bac_hoc_id) ?>" class="btn btn-sm btn-primary">
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
                        <td><?= esc($data->bac_hoc_id) ?></td>
                    </tr>
                    <tr>
                        <th>Tên bậc học</th>
                        <td>
                            <?php if (!empty($data->ten_bac_hoc)): ?>
                                <?= esc($data->ten_bac_hoc) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Mã bậc học</th>
                        <td>
                            <?php if (!empty($data->ma_bac_hoc)): ?>
                                <?= esc($data->ma_bac_hoc) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <?= $data->getStatusLabel() ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td><?= $data->getCreatedAtFormatted() ?></td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối</th>
                        <td><?= $data->getUpdatedAtFormatted() ?></td>
                    </tr>
                    <tr>
                        <th>Ngày xóa</th>
                        <td><?= $data->getDeletedAtFormatted() ?></td>
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
                Bạn có chắc chắn muốn xóa bậc học <strong><?= esc($data->ten_bac_hoc) ?></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="<?= site_url($module_name . '/delete/' . $data->bac_hoc_id) ?>" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script_ext') ?>
<?= $masterScript->pageJs('view') ?>
<?= $this->endSection() ?>
