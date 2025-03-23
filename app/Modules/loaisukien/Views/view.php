<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= loaisukien_css('view') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT LOẠI SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết loại sự kiện',
    'dashboard_url' => site_url('loaisukien/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Loại Sự Kiện', 'url' => site_url('loaisukien')],
        ['title' => 'Chi tiết', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/loaisukien'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Chi tiết loại sự kiện <?= esc($loaisukien->ten_loai_su_kien) ?></h5>
        <div class="d-flex gap-2">
            <a href="<?= site_url("loaisukien/edit/{$loaisukien->loai_su_kien_id}") ?>" class="btn btn-sm btn-primary">
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
                        <th style="width: 200px;">Mã loại sự kiện</th>
                        <td><?= esc($loaisukien->ma_loai_su_kien) ?></td>
                    </tr>
                    <tr>
                        <th>Tên loại sự kiện</th>
                        <td><?= esc($loaisukien->ten_loai_su_kien) ?></td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <?php if ($loaisukien->status == 1) : ?>
                                <span class="badge bg-success">Hoạt động</span>
                            <?php else : ?>
                                <span class="badge bg-danger">Không hoạt động</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td><?= date('d/m/Y H:i:s', strtotime($loaisukien->created_at)) ?></td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối</th>
                        <td>
                            <?php if (!empty($loaisukien->updated_at)) : ?>
                                <?= date('d/m/Y H:i:s', strtotime($loaisukien->updated_at)) ?>
                            <?php else : ?>
                                <span class="text-muted">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
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
                Bạn có chắc chắn muốn xóa loại sự kiện <strong><?= esc($loaisukien->ten_loai_su_kien) ?></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="<?= site_url("loaisukien/delete/{$loaisukien->loai_su_kien_id}") ?>" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script_ext') ?>
<?= loaisukien_js('view') ?>
<?= $this->endSection() ?>
