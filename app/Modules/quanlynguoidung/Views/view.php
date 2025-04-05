<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('view', $module_name) ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT NGƯỜI DÙNG<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết người dùng',
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => 'Quản lý người dùng', 'url' => site_url($module_name)],
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
        <h5 class="card-title mb-0">Chi tiết người dùng ID: <?= esc($data->nguoi_dung_id) ?></h5>
        <div class="d-flex gap-2">
            <a href="<?= site_url($module_name . '/edit/' . $data->nguoi_dung_id) ?>" class="btn btn-sm btn-primary">
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
        
        <div class="row">
            <div class="col-md-3 text-center mb-4">
                <div class="card">
                    <div class="card-body">
                        <img src="<?= $data->getAvatarUrl() ?>" alt="Avatar" class="img-fluid rounded-circle mb-3" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                        <h5 class="mb-0"><?= esc($data->getFullName()) ?></h5>
                        <p class="text-muted small"><?= esc($data->getAccountId()) ?></p>
                        <?= $data->getStatusLabel() ?>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">ID</th>
                                <td><?= esc($data->nguoi_dung_id) ?></td>
                            </tr>
                            <tr>
                                <th>Tài khoản</th>
                                <td>
                                    <?php if (!empty($data->AccountId)): ?>
                                        <?= esc($data->AccountId) ?>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Họ và tên</th>
                                <td>
                                    <?php if (!empty($data->FullName)): ?>
                                        <?= esc($data->FullName) ?>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>
                                    <?php if (!empty($data->Email)): ?>
                                        <a href="mailto:<?= esc($data->Email) ?>"><?= esc($data->Email) ?></a>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Số điện thoại</th>
                                <td>
                                    <?php if (!empty($data->MobilePhone)): ?>
                                        <a href="tel:<?= esc($data->MobilePhone) ?>"><?= esc($data->MobilePhone) ?></a>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Loại người dùng</th>
                                <td>
                                    <?php if (!empty($data->getLoaiNguoiDungDisplay())): ?>
                                        <?= esc($data->getLoaiNguoiDungDisplay()) ?>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Phòng/Khoa</th>
                                <td>
                                    <?php if (!empty($data->getPhongKhoaDisplay())): ?>
                                        <?= esc($data->getPhongKhoaDisplay()) ?>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Avatar</th>
                                <td>
                                    <?php if (!empty($data->avatar)): ?>
                                        <?= esc($data->avatar) ?>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Sử dụng avatar mặc định</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td><?= $data->getStatusLabel() ?></td>
                            </tr>
                            <tr>
                                <th>Lần đăng nhập cuối</th>
                                <td>
                                    <?php if (!empty($data->getLastLoginFormatted())): ?>
                                        <?= $data->getLastLoginFormatted() ?>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Chưa đăng nhập</span>
                                    <?php endif; ?>
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
                Bạn có chắc chắn muốn xóa người dùng <strong><?= esc($data->FullName) ?></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="<?= site_url($module_name . '/delete/' . $data->nguoi_dung_id) ?>" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script_ext') ?>
<?= page_js('view', $module_name) ?>
<?= $this->endSection() ?>
