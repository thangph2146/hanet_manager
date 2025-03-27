<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('view', $module_name) ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT LIÊN KẾT SỰ KIỆN - DIỄN GIẢ<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết liên kết sự kiện - diễn giả',
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => 'Quản lý liên kết sự kiện - diễn giả', 'url' => site_url($module_name)],
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
        <h5 class="card-title mb-0">Chi tiết liên kết sự kiện - diễn giả ID: <?= esc($data->getId()) ?></h5>
        <div class="d-flex gap-2">
            <a href="<?= site_url($module_name . '/edit/' . $data->getId()) ?>" class="btn btn-sm btn-primary">
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
                        <td><?= esc($data->getId()) ?></td>
                    </tr>
                    <tr>
                        <th>Sự kiện</th>
                        <td>
                            <?php if (!empty($data->su_kien)): ?>
                                <?= esc($data->su_kien->ten_su_kien) ?>
                                <?php if (!empty($data->su_kien->thoi_gian_bat_dau)): ?>
                                    <div><small class="text-muted">Thời gian bắt đầu: <?= esc($data->su_kien->thoi_gian_bat_dau) ?></small></div>
                                <?php endif; ?>
                            <?php elseif (!empty($data->ten_su_kien)): ?>
                                <?= esc($data->ten_su_kien) ?>
                            <?php else: ?>
                                <?= esc($data->getTenSuKien()) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Diễn giả</th>
                        <td>
                            <?php if (!empty($data->dien_gia)): ?>
                                <?= esc($data->dien_gia->ten_dien_gia) ?>
                                <?php if (!empty($data->dien_gia->chuc_danh)): ?>
                                    <div><small class="text-muted">Chức danh: <?= esc($data->dien_gia->chuc_danh) ?></small></div>
                                <?php endif; ?>
                                <?php if (!empty($data->dien_gia->to_chuc)): ?>
                                    <div><small class="text-muted">Tổ chức: <?= esc($data->dien_gia->to_chuc) ?></small></div>
                                <?php endif; ?>
                            <?php elseif (!empty($data->ten_dien_gia)): ?>
                                <?= esc($data->ten_dien_gia) ?>
                                <?php if (!empty($data->chuc_danh)): ?>
                                    <div><small class="text-muted">Chức danh: <?= esc($data->chuc_danh) ?></small></div>
                                <?php endif; ?>
                                <?php if (!empty($data->to_chuc)): ?>
                                    <div><small class="text-muted">Tổ chức: <?= esc($data->to_chuc) ?></small></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <?= esc($data->getTenDienGia()) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
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
                Bạn có chắc chắn muốn xóa liên kết sự kiện - diễn giả giữa:
                <div class="mt-2">
                    <div><strong>Sự kiện:</strong> <?= esc($data->getTenSuKien()) ?></div>
                    <div><strong>Diễn giả:</strong> <?= esc($data->getTenDienGia()) ?></div>
                </div>
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
