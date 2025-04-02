<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('view', $module_name) ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CHI TIẾT ĐĂNG KÝ SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chi tiết đăng ký sự kiện',
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => 'Quản lý đăng ký sự kiện', 'url' => site_url($module_name)],
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
        <h5 class="card-title mb-0">Chi tiết đăng ký sự kiện ID: <?= esc($data->getId()) ?></h5>
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
                        <th>Tên sự kiện</th>
                        <td>
                            <?php 
                                $suKien = $data->getSuKien();
                                if ($suKien): 
                            ?>
                                <?= esc($suKien->ten_su_kien) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Không tìm thấy</span>
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
                        <th>Điện thoại</th>
                        <td>
                            <?php if (!empty($data->getDienThoai())): ?>
                                <?= esc($data->getDienThoai()) ?>
                            <?php else: ?>
                                <span class="text-muted fst-italic">Chưa cập nhật</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Loại người đăng ký</th>
                        <td><?= esc($data->getLoaiNguoiDangKyText()) ?></td>
                    </tr>
                    <tr>
                        <th>Hình thức tham gia</th>
                        <td><?= esc($data->getHinhThucThamGiaText()) ?></td>
                    </tr>
                    <tr>
                        <th>Ngày đăng ký</th>
                        <td><?= $data->getNgayDangKyFormatted() ?></td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <?php
                                $status = $data->getStatus();
                                if ($status == 1) echo '<span class="badge bg-success">Đã xác nhận</span>';
                                elseif ($status == 0) echo '<span class="badge bg-warning">Chờ xác nhận</span>';
                                elseif ($status == -1) echo '<span class="badge bg-danger">Đã hủy</span>';
                                else echo '<span class="badge bg-secondary">Không xác định</span>';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái tham dự</th>
                        <td><?= esc($data->getAttendanceStatusText()) ?></td>
                    </tr>
                    <tr>
                        <th>Số phút tham dự</th>
                        <td><?= $data->getAttendanceMinutes() ?> phút</td>
                    </tr>
                    <tr>
                        <th>Phương thức điểm danh</th>
                        <td><?= esc($data->getDiemDanhBangText()) ?></td>
                    </tr>
                    <tr>
                        <th>Đã check-in</th>
                        <td><?= $data->isDaCheckIn() ? '<span class="badge bg-success">Có</span>' : '<span class="badge bg-danger">Không</span>' ?></td>
                    </tr>
                    <tr>
                        <th>Đã check-out</th>
                        <td><?= $data->isDaCheckOut() ? '<span class="badge bg-success">Có</span>' : '<span class="badge bg-danger">Không</span>' ?></td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td><?= $data->getCreatedAt() ? date('d/m/Y H:i:s', strtotime($data->getCreatedAt())) : '' ?></td>
                    </tr>
                    <tr>
                        <th>Ngày cập nhật</th>
                        <td><?= $data->getUpdatedAt() ? date('d/m/Y H:i:s', strtotime($data->getUpdatedAt())) : '' ?></td>
                    </tr>
                    <tr>
                        <th>Ngày xóa</th>
                        <td>
                            <?php if ($data->getDeletedAt()): ?>
                                <span class="text-danger"><?= date('d/m/Y H:i:s', strtotime($data->getDeletedAt())) ?></span>
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
                Bạn có chắc chắn muốn xóa đăng ký sự kiện của <strong><?= esc($data->getHoTen()) ?></strong> không?
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
