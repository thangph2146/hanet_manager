<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('view', $module_name) ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT CHECK-IN SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết check-in sự kiện',
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => 'Quản lý check-in sự kiện', 'url' => site_url($module_name)],
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
        <h5 class="card-title mb-0">Chi tiết check-in sự kiện ID: <?= esc($data->getId()) ?></h5>
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
                            <?php if ($data->getSuKien()): ?>
                                <?= esc($data->getSuKien()->getTenSuKien()) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Không tìm thấy sự kiện</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Họ tên</th>
                        <td>
                            <?php if (!empty($data->getHoTen())): ?>
                                <?= esc($data->getHoTen()) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>
                            <?php if (!empty($data->getEmail())): ?>
                                <?= esc($data->getEmail()) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Thời gian check-in</th>
                        <td>
                            <?php if ($data->getThoiGianCheckIn()): ?>
                                <?= $data->getThoiGianCheckInFormatted() ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Loại check-in</th>
                        <td>
                            <?php if (!empty($data->getCheckinType())): ?>
                                <span class="badge bg-info"><?= esc($data->getCheckinTypeText()) ?></span>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Hình thức tham gia</th>
                        <td>
                            <?php if (!empty($data->getHinhThucThamGia())): ?>
                                <span class="badge bg-primary"><?= esc($data->getHinhThucThamGiaText()) ?></span>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if ($data->getCheckinType() === 'face_id'): ?>
                    <tr>
                        <th>Xác minh khuôn mặt</th>
                        <td>
                            <?php if ($data->isFaceVerified()): ?>
                                <span class="badge bg-success">Đã xác minh</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Chưa xác minh</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ảnh khuôn mặt</th>
                        <td>
                            <?php if (!empty($data->getFaceImagePath())): ?>
                                <img src="<?= base_url('uploads/faces/' . $data->getFaceImagePath()) ?>" alt="Ảnh khuôn mặt" class="img-thumbnail" style="max-width: 200px;">
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Điểm khớp khuôn mặt</th>
                        <td>
                            <?php if ($data->getFaceMatchScore() !== null): ?>
                                <?= esc($data->getFaceMatchScore()) ?>
                                <div class="progress mt-1" style="height: 5px; width: 200px;">
                                    <div class="progress-bar <?= $data->getFaceMatchScore() >= 0.7 ? 'bg-success' : 'bg-warning' ?>" role="progressbar" style="width: <?= $data->getFaceMatchScore() * 100 ?>%" aria-valuenow="<?= $data->getFaceMatchScore() * 100 ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Mã xác nhận</th>
                        <td>
                            <?php if (!empty($data->getMaXacNhan())): ?>
                                <code><?= esc($data->getMaXacNhan()) ?></code>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ghi chú</th>
                        <td>
                            <?php if (!empty($data->getGhiChu())): ?>
                                <?= nl2br(esc($data->getGhiChu())) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <?php if ($data->getStatus() == 1): ?>
                                <span class="badge bg-success">Hoạt động</span>
                            <?php elseif ($data->getStatus() == 2): ?>
                                <span class="badge bg-warning text-dark">Đang xử lý</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Vô hiệu</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>
                            <?php if ($data->getCreatedAt()): ?>
                                <?= $data->getCreatedAt()->format('d/m/Y H:i:s') ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Cập nhật lần cuối</th>
                        <td>
                            <?php if ($data->getUpdatedAt()): ?>
                                <?= $data->getUpdatedAt()->format('d/m/Y H:i:s') ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày xóa</th>
                        <td>
                            <?php if ($data->getDeletedAt()): ?>
                                <?= $data->getDeletedAt()->format('d/m/Y H:i:s') ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa xóa</span>
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
                Bạn có chắc chắn muốn xóa check-in sự kiện của <strong><?= esc($data->getHoTen()) ?></strong> không?
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
