<?php
/**
 * Component hiển thị chi tiết đăng ký sự kiện
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu đăng ký sự kiện cần hiển thị
 * @var string $module_name Tên module
 */

// Khởi tạo các biến từ dữ liệu đầu vào
if (is_object($data)) {
    $dangky_sukien_id = $data->getId() ?? '';
    $su_kien_id = $data->getSuKienId() ?? '';
    $email = $data->getEmail() ?? '';
    $ho_ten = $data->getHoTen() ?? '';
    $dien_thoai = $data->getDienThoai() ?? '';
    $loai_nguoi_dang_ky = $data->getLoaiNguoiDangKy() ?? 'khach';
    $status = $data->getStatus() ?? 0;
    $noi_dung_gop_y = $data->getNoiDungGopY() ?? '';
    $nguon_gioi_thieu = $data->getNguonGioiThieu() ?? '';
    $don_vi_to_chuc = $data->getDonViToChuc() ?? '';
    $hinh_thuc_tham_gia = $data->getHinhThucThamGia() ?? 'offline';
    $ly_do_tham_du = $data->getLyDoThamDu() ?? '';
    $ly_do_huy = $data->getLyDoHuy() ?? '';
    $attendance_status = $data->getAttendanceStatus() ?? 'not_attended';
    $attendance_minutes = $data->getAttendanceMinutes() ?? 0;
    $diem_danh_bang = $data->getDiemDanhBang() ?? 'none';
    $face_image_path = $data->getFaceImagePath() ?? '';
    $face_verified = $data->isFaceVerified() ?? false;
    $da_check_in = $data->isDaCheckIn() ?? false;
    $da_check_out = $data->isDaCheckOut() ?? false;
    $created_at = $data->getCreatedAt();
    $updated_at = $data->getUpdatedAt();
    $deleted_at = $data->getDeletedAt();
    $suKien = $data->getSuKien();
} else {
    // Khởi tạo giá trị mặc định nếu không có dữ liệu
    $dangky_sukien_id = '';
    $su_kien_id = '';
    $email = '';
    $ho_ten = '';
    $dien_thoai = '';
    $loai_nguoi_dang_ky = 'khach';
    $status = 0;
    $noi_dung_gop_y = '';
    $nguon_gioi_thieu = '';
    $don_vi_to_chuc = '';
    $hinh_thuc_tham_gia = 'offline';
    $ly_do_tham_du = '';
    $ly_do_huy = '';
    $attendance_status = 'not_attended';
    $attendance_minutes = 0;
    $diem_danh_bang = 'none';
    $face_verified = false;
    $da_check_in = false;
    $da_check_out = false;
    $created_at = null;
    $updated_at = null;
    $deleted_at = null;
    $suKien = null;
}
?>

<style>
.detail-card {
    transition: all 0.3s ease;
    border-radius: 8px;
    overflow: hidden;
    border: none;
}

.detail-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    transform: translateY(-3px);
}

.card-header {
    border-bottom: 0;
    padding: 1rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

.badge {
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

.btn {
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.dropdown-item {
    transition: all 0.2s ease;
    border-radius: 4px;
    margin: 2px 5px;
}

.copy-btn {
    transition: all 0.2s ease;
}

.copy-btn:hover {
    transform: scale(1.1);
}

.progress {
    overflow: hidden;
    border-radius: 10px;
}

.progress-bar {
    transition: width 1s ease;
}

.profile-img {
    transition: all 0.3s ease;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.profile-img:hover {
    transform: scale(1.05);
}

.list-group-item {
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: rgba(0,0,0,0.02);
}

.modal-content {
    border-radius: 10px;
    overflow: hidden;
}

.info-section {
    opacity: 0;
    animation: fadeIn 0.5s ease forwards;
    animation-delay: calc(var(--animation-order) * 0.1s);
}

.text-icon {
    transition: all 0.3s ease;
}

.text-icon:hover .bx {
    transform: scale(1.2);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.detail-item {
    margin-bottom: 1rem;
}

.detail-item label {
    display: block;
    margin-bottom: 0.5rem;
}

.detail-item span {
    display: block;
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}
</style>

<div class="container-fluid px-0">
    <!-- Card header -->
    <div class="card shadow-sm mb-4 detail-card" style="--animation-order: 1">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="card-title mb-0 text-white">
                <i class="fas fa-info-circle me-2"></i> Chi tiết đăng ký sự kiện
            </h4>
            <div class="d-flex gap-2">
                <a href="<?= base_url($module_name) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
                <a href="<?= base_url($module_name . '/edit/' . $dangky_sukien_id) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card info-section" style="--animation-order: 2">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-info-circle me-2"></i> Thông tin cơ bản
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">ID đăng ký</label>
                            <div class="h5">
                                <span class="badge bg-primary px-3 py-2">#<?= $dangky_sukien_id ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Sự kiện</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                <span class="fw-bold"><?= $suKien ? esc($suKien->getTenSuKien()) : 'Không có' ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Email</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-envelope me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($email) ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Họ tên</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-user me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($ho_ten) ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Điện thoại</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-phone me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($dien_thoai) ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Loại người đăng ký</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-user-tag me-2 text-primary"></i>
                                <span class="fw-bold"><?= $data->getLoaiNguoiDangKyText() ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1 fw-medium">Hình thức tham gia</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-video me-2 text-primary"></i>
                                <span class="fw-bold"><?= $data->getHinhThucThamGiaText() ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin tham dự -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card info-section" style="--animation-order: 3">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-clipboard-check me-2"></i> Thông tin tham dự
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Trạng thái đăng ký</label>
                            <div class="h5 text-icon">
                                <?php
                                $statusClass = '';
                                switch ($status) {
                                    case 1:
                                        $statusClass = 'bg-success';
                                        break;
                                    case 0:
                                        $statusClass = 'bg-warning';
                                        break;
                                    case -1:
                                        $statusClass = 'bg-danger';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $statusClass ?> px-3 py-2"><?= $data->getStatusText() ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Trạng thái tham dự</label>
                            <div class="h5 text-icon">
                                <?php
                                $attendanceClass = '';
                                switch ($attendance_status) {
                                    case 'full':
                                        $attendanceClass = 'bg-success';
                                        break;
                                    case 'partial':
                                        $attendanceClass = 'bg-warning';
                                        break;
                                    default:
                                        $attendanceClass = 'bg-secondary';
                                }
                                ?>
                                <span class="badge <?= $attendanceClass ?> px-3 py-2"><?= $data->getAttendanceStatusText() ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Thời gian tham dự</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-clock me-2 text-primary"></i>
                                <span class="fw-bold"><?= $data->getAttendanceTimeFormatted() ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Phương thức điểm danh</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-qrcode me-2 text-primary"></i>
                                <span class="fw-bold"><?= $data->getDiemDanhBangText() ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Trạng thái check-in/out</label>
                            <div class="h5">
                                <span class="badge <?= $da_check_in ? 'bg-success' : 'bg-secondary' ?> me-2">
                                    <i class="fas fa-sign-in-alt me-1"></i> <?= $da_check_in ? 'Đã check-in' : 'Chưa check-in' ?>
                                </span>
                                <span class="badge <?= $da_check_out ? 'bg-success' : 'bg-secondary' ?>">
                                    <i class="fas fa-sign-out-alt me-1"></i> <?= $da_check_out ? 'Đã check-out' : 'Chưa check-out' ?>
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1 fw-medium">Xác minh khuôn mặt</label>
                            <div class="h5">
                                <span class="badge <?= $face_verified ? 'bg-success' : 'bg-secondary' ?>">
                                    <i class="fas fa-user-check me-1"></i> <?= $face_verified ? 'Đã xác minh' : 'Chưa xác minh' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin bổ sung -->
        <div class="col-12">
            <div class="card shadow-sm detail-card info-section" style="--animation-order: 4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-clipboard-list me-2"></i> Thông tin bổ sung
                    </h5>
                </div>
                <div class="card-body p-4">
                    <?php if ($don_vi_to_chuc): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold">Đơn vị tổ chức</h6>
                        <p class="mb-0"><?= esc($don_vi_to_chuc) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($nguon_gioi_thieu): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold">Nguồn giới thiệu</h6>
                        <p class="mb-0"><?= esc($nguon_gioi_thieu) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($ly_do_tham_du): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold">Lý do tham dự</h6>
                        <p class="mb-0"><?= nl2br(esc($ly_do_tham_du)) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($noi_dung_gop_y): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold">Nội dung góp ý</h6>
                        <p class="mb-0"><?= nl2br(esc($noi_dung_gop_y)) ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if ($status == -1 && $ly_do_huy): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold">Lý do hủy</h6>
                        <p class="mb-0"><?= nl2br(esc($ly_do_huy)) ?></p>
                    </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-4">
                            <h6 class="fw-bold">Ngày tạo</h6>
                            <p class="mb-0"><?= $created_at ? $created_at->format('d/m/Y H:i:s') : '' ?></p>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold">Ngày cập nhật</h6>
                            <p class="mb-0"><?= $updated_at ? $updated_at->format('d/m/Y H:i:s') : '' ?></p>
                        </div>
                        <?php if ($deleted_at): ?>
                        <div class="col-md-4">
                            <h6 class="fw-bold">Ngày xóa</h6>
                            <p class="mb-0"><?= $deleted_at->format('d/m/Y H:i:s') ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin khuôn mặt và điểm danh -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm detail-card info-section" style="--animation-order: 5">
                <div class="card-header bg-warning text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-camera me-2"></i> Thông tin điểm danh và xác thực
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <?php if (!empty($face_image_path)): ?>
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <h6 class="fw-bold mb-3">Ảnh khuôn mặt</h6>
                                <div class="position-relative">
                                    <img src="<?= base_url(esc($face_image_path)) ?>" 
                                         alt="Ảnh khuôn mặt" 
                                         class="img-fluid rounded profile-img shadow"
                                         style="max-height: 200px; cursor: pointer;"
                                         title="Click để xem ảnh lớn hơn">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <a href="<?= base_url(esc($face_image_path)) ?>" class="btn btn-sm btn-primary rounded-circle" 
                                           title="Tải xuống ảnh" download>
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <code class="text-muted small"><?= esc($face_image_path) ?></code>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="<?= !empty($face_image_path) ? 'col-md-8' : 'col-md-12' ?>">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Trạng thái xác thực khuôn mặt</h6>
                                    <span class="badge <?= $face_verified ? 'bg-success' : 'bg-secondary' ?> px-3 py-2">
                                        <i class="fas <?= $face_verified ? 'fa-check-circle' : 'fa-times-circle' ?> me-1"></i>
                                        <?= $face_verified ? 'Đã xác thực' : 'Chưa xác thực' ?>
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold">Phương thức điểm danh</h6>
                                    <span class="badge bg-info px-3 py-2">
                                        <i class="fas fa-qrcode me-1"></i>
                                        <?= $data->getDiemDanhBangText() ?>
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold">Trạng thái check-in</h6>
                                    <span class="badge <?= $da_check_in ? 'bg-success' : 'bg-secondary' ?> px-3 py-2">
                                        <i class="fas fa-sign-in-alt me-1"></i>
                                        <?= $da_check_in ? 'Đã check-in' : 'Chưa check-in' ?>
                                    </span>
                                    <?php if ($data->getCheckinSukienId()): ?>
                                        <div class="mt-1">
                                            <small class="text-muted">ID check-in: <?= $data->getCheckinSukienId() ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold">Trạng thái check-out</h6>
                                    <span class="badge <?= $da_check_out ? 'bg-success' : 'bg-secondary' ?> px-3 py-2">
                                        <i class="fas fa-sign-out-alt me-1"></i>
                                        <?= $da_check_out ? 'Đã check-out' : 'Chưa check-out' ?>
                                    </span>
                                    <?php if ($data->getCheckoutSukienId()): ?>
                                        <div class="mt-1">
                                            <small class="text-muted">ID check-out: <?= $data->getCheckoutSukienId() ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold">Trạng thái tham dự</h6>
                                    <?php
                                    $attendanceClass = '';
                                    switch ($attendance_status) {
                                        case 'full':
                                            $attendanceClass = 'bg-success';
                                            break;
                                        case 'partial':
                                            $attendanceClass = 'bg-warning';
                                            break;
                                        default:
                                            $attendanceClass = 'bg-secondary';
                                    }
                                    ?>
                                    <span class="badge <?= $attendanceClass ?> px-3 py-2">
                                        <i class="fas fa-clock me-1"></i>
                                        <?= $data->getAttendanceStatusText() ?>
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="fw-bold">Thời gian tham dự</h6>
                                    <span class="badge bg-info px-3 py-2">
                                        <i class="fas fa-stopwatch me-1"></i>
                                        <?= $data->getAttendanceTimeFormatted() ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin thời gian -->
        <div class="col-12">
            <div class="card shadow-sm detail-card info-section" style="--animation-order: 6">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-clock me-2"></i> Thông tin thời gian
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <h6 class="fw-bold">Ngày đăng ký</h6>
                            <p class="mb-0">
                                <i class="fas fa-calendar-alt text-primary me-1"></i>
                                <?= $created_at ? $created_at->format('d/m/Y H:i:s') : 'Chưa có' ?>
                            </p>
                        </div>

                        <div class="col-md-4">
                            <h6 class="fw-bold">Thời gian duyệt</h6>
                            <p class="mb-0">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                <?= $updated_at ? $updated_at->format('d/m/Y H:i:s') : 'Chưa duyệt' ?>
                            </p>
                        </div>

                        <?php if ($status == -1): ?>
                        <div class="col-md-4">
                            <h6 class="fw-bold">Thời gian hủy</h6>
                            <p class="mb-0">
                                <i class="fas fa-times-circle text-danger me-1"></i>
                                <?= $deleted_at ? $deleted_at->format('d/m/Y H:i:s') : 'Chưa có' ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal hiển thị ảnh khuôn mặt lớn -->
<div class="modal fade" id="faceImageModal" tabindex="-1" aria-labelledby="faceImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faceImageModalLabel">Ảnh khuôn mặt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <?php if (!empty($face_image_path)): ?>
                    <img src="<?= base_url(esc($face_image_path)) ?>" class="img-fluid" alt="Ảnh khuôn mặt" style="max-height: 70vh;">
                <?php else: ?>
                    <div class="alert alert-warning">Không có ảnh khuôn mặt</div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <?php if (!empty($face_image_path)): ?>
                    <a href="<?= base_url(esc($face_image_path)) ?>" class="btn btn-primary" download target="_blank">
                        <i class="fas fa-download"></i> Tải xuống
                    </a>
                <?php endif; ?>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo Animation cho các phần tử
    const infoSections = document.querySelectorAll('.info-section');
    infoSections.forEach(section => {
        setTimeout(() => {
            section.style.opacity = '1';
        }, 100);
    });

    // Khi click vào ảnh khuôn mặt, hiển thị modal
    const faceImages = document.querySelectorAll('.profile-img');
    faceImages.forEach(function(img) {
        img.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('faceImageModal'));
            modal.show();
        });
    });
});
</script> 