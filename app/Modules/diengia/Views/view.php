<?php $this->extend('layouts/default') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>

<?php $this->section('styles') ?>
<?= nganh_css('view') ?>
<?php $this->endSection() ?>

<?php $this->section('title') ?>CHI TIẾT KHUÔN MẶT NGƯỜI DÙNG<?php $this->endSection() ?>

<?php $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết khuôn mặt người dùng',
    'dashboard_url' => site_url('facenguoidung'),
    'breadcrumbs' => [
        ['title' => 'Quản lý khuôn mặt người dùng', 'url' => site_url('facenguoidung')],
        ['title' => 'Chi tiết', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/facenguoidung'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
    ]
]) ?>
<?php $this->endSection() ?>

<?php $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">Chi tiết khuôn mặt người dùng</h5>
        <div class="d-flex gap-2">
            <a href="<?= site_url("facenguoidung/edit/{$item->face_nguoi_dung_id}") ?>" class="btn btn-sm btn-primary">
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
            <div class="col-md-6">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 200px;">ID</th>
                                <td><?= esc($item->face_nguoi_dung_id) ?></td>
                            </tr>
                            <tr>
                                <th>Người dùng</th>
                                <td>
                                    <?php if (isset($item->nguoi_dung) && !empty($item->nguoi_dung)) : ?>
                                        <?= esc($item->nguoi_dung->ho_ten) ?> 
                                        <?php if (!empty($item->nguoi_dung->email)) : ?>
                                            (<?= esc($item->nguoi_dung->email) ?>)
                                        <?php endif; ?>
                                    <?php else : ?>
                                        <span class="text-muted">Không tìm thấy thông tin người dùng</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày cập nhật ảnh</th>
                                <td>
                                    <?php if (!empty($item->ngay_cap_nhat)) : ?>
                                        <?= date('d/m/Y H:i:s', strtotime($item->ngay_cap_nhat)) ?>
                                    <?php else : ?>
                                        <span class="text-muted">Chưa có thông tin</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td>
                                    <?php if ($item->status == 1) : ?>
                                        <span class="badge bg-success">Hoạt động</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Không hoạt động</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngày tạo</th>
                                <td><?= date('d/m/Y H:i:s', strtotime($item->created_at)) ?></td>
                            </tr>
                            <tr>
                                <th>Cập nhật lần cuối</th>
                                <td>
                                    <?php if (!empty($item->updated_at)) : ?>
                                        <?= date('d/m/Y H:i:s', strtotime($item->updated_at)) ?>
                                    <?php else : ?>
                                        <span class="text-muted">Chưa cập nhật</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Ảnh khuôn mặt</h6>
                    </div>
                    <div class="card-body text-center">
                        <?php if (!empty($item->duong_dan_anh)) : ?>
                            <img src="<?= base_url($item->duong_dan_anh) ?>" alt="Ảnh khuôn mặt" class="img-fluid img-thumbnail" style="max-height: 300px;">
                            <p class="mt-2 mb-0 text-muted small">Đường dẫn: <?= esc($item->duong_dan_anh) ?></p>
                        <?php else : ?>
                            <div class="alert alert-info mb-0">
                                <i class="bx bx-info-circle me-1"></i> Không có ảnh khuôn mặt
                            </div>
                        <?php endif; ?>
                    </div>
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
                Bạn có chắc chắn muốn xóa khuôn mặt của 
                <?php if (isset($item->nguoi_dung) && !empty($item->nguoi_dung->ho_ten)) : ?>
                    <strong><?= esc($item->nguoi_dung->ho_ten) ?></strong>
                <?php else : ?>
                    <strong>người dùng này</strong>
                <?php endif; ?> 
                không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <a href="<?= site_url("facenguoidung/delete/{$item->face_nguoi_dung_id}") ?>" class="btn btn-danger">Xóa</a>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection() ?>

<?php $this->section('script_ext') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý form và tương tác
        console.log('Trang chi tiết khuôn mặt người dùng đã được tải');
    });
</script>
<?php $this->endSection() ?>
