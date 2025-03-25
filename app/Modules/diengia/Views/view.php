<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('view') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT DIỄN GIẢ<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết diễn giả',
    'dashboard_url' => site_url('diengia/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Diễn giả', 'url' => site_url('diengia')],
        ['title' => 'Chi tiết', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/diengia'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Chi tiết diễn giả <?= esc($diengia->ten_dien_gia) ?></h5>
        <div class="d-flex gap-2">
            <a href="<?= site_url("diengia/edit/{$diengia->dien_gia_id}") ?>" class="btn btn-sm btn-primary">
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
                    <?php if (!empty($diengia->avatar)) : ?>
                    <tr>
                        <th style="width: 200px;">Ảnh đại diện</th>
                        <td>
                            <img src="<?= $diengia->getAvatarUrl() ?>" alt="<?= esc($diengia->ten_dien_gia) ?>" 
                                 class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th style="width: 200px;">ID diễn giả</th>
                        <td><?= esc($diengia->dien_gia_id) ?></td>
                    </tr>
                    <tr>
                        <th>Tên diễn giả</th>
                        <td><?= esc($diengia->ten_dien_gia) ?></td>
                    </tr>
                    <tr>
                        <th>Chức danh</th>
                        <td><?= esc($diengia->chuc_danh) ?></td>
                    </tr>
                    <tr>
                        <th>Tổ chức</th>
                        <td><?= esc($diengia->to_chuc) ?></td>
                    </tr>
                    <tr>
                        <th>Giới thiệu</th>
                        <td><?= nl2br(esc($diengia->gioi_thieu)) ?></td>
                    </tr>
                    <tr>
                        <th>Thứ tự hiển thị</th>
                        <td><?= esc($diengia->thu_tu) ?></td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td><?= date('d/m/Y H:i:s', strtotime($diengia->created_at)) ?></td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối</th>
                        <td>
                            <?php if (!empty($diengia->updated_at)) : ?>
                                <?= date('d/m/Y H:i:s', strtotime($diengia->updated_at)) ?>
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
                Bạn có chắc chắn muốn xóa diễn giả <strong><?= esc($diengia->ten_dien_gia) ?></strong> không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="<?= site_url("diengia/delete/{$diengia->dien_gia_id}") ?>" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script_ext') ?>
<?= page_js('view') ?>
<?= $this->endSection() ?>
