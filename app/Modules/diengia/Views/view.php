<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('view', $module_name) ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT DIỄN GIẢ<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết diễn giả',
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => 'Quản lý Diễn giả', 'url' => site_url($module_name)],
        ['title' => 'Chi tiết', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url($module_name), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Chi tiết diễn giả ID: <?= esc($data->getId()) ?></h5>
        <div class="d-flex gap-2">  
            <a href="<?= site_url($module_name . '/edit/' . $data->getId()) ?>" class="btn btn-sm btn-primary">
                <i class="bx bx-edit me-1"></i> Chỉnh sửa
            </a>
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" 
            data-bs-target="#deleteModal" data-id="<?= $data->getId() ?>" 
            data-name="<?= esc($data->getTenDienGia()) ?>">
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
                        <td><?= esc($data->getId()) ?></td>
                    </tr>
                    <tr>
                        <th>Tên diễn giả</th>
                        <td>
                            <?php if (!empty($data->getTenDienGia())): ?>
                                <?= esc($data->getTenDienGia()) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Chức danh</th>
                        <td>
                            <?php if (!empty($data->getChucDanh())): ?>
                                <?= esc($data->getChucDanh()) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tổ chức</th>
                        <td>
                            <?php if (!empty($data->getToChuc())): ?>
                                <?= esc($data->getToChuc()) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Giới thiệu</th>
                        <td>
                            <?php if (!empty($data->getGioiThieu())): ?>
                                <?= esc($data->getGioiThieu()) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if (!empty($data->getAvatar())): ?>
                    <tr>
                        <th>Ảnh đại diện</th>
                        <td>
                            <img src="<?= base_url('uploads/diengia/' . $data->getAvatar()) ?>" class="img-thumbnail" style="max-height: 150px;" alt="Ảnh đại diện">
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Thứ tự</th>
                        <td><?= esc($data->getThuTu()) ?></td>
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
                Bạn có chắc chắn muốn xóa diễn giả <strong id="delete-name"><?= esc($data->getTenDienGia()) ?></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="<?= site_url($module_name . '/delete/' . $data->getId()) ?>" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script_ext') ?>
<?= page_js('view', $module_name) ?>
<?= $this->endSection() ?>
