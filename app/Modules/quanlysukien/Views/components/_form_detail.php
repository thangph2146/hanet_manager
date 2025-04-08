<?php
/**
 * Component hiển thị chi tiết sự kiện
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu sự kiện cần hiển thị
 * @var string $module_name Tên module
 */

// Sử dụng getter method từ SuKien entity
$su_kien_id = isset($data) ? $data->getId() : '';
$ten_su_kien = isset($data) ? $data->getTenSuKien() : '';
$mo_ta = isset($data) ? $data->getMoTa() : '';
$mo_ta_su_kien = isset($data) ? $data->getMoTaSuKien() : '';
$chi_tiet_su_kien = isset($data) ? $data->getChiTietSuKien() : '';
$thoi_gian_bat_dau = isset($data) ? $data->getThoiGianBatDauFormatted() : '';
$thoi_gian_ket_thuc = isset($data) ? $data->getThoiGianKetThucFormatted() : '';
$dia_diem = isset($data) ? $data->getDiaDiem() : '';
$dia_chi_cu_the = isset($data) ? $data->getDiaChiCuThe() : '';
$toa_do_gps = isset($data) ? $data->getToaDoGPS() : '';
$loai_su_kien_id = isset($data) ? $data->getLoaiSuKienId() : '';
$ma_qr_code = isset($data) ? $data->getMaQRCode() : '';
$status = isset($data) ? $data->getStatus() : '';
$tong_dang_ky = isset($data) ? $data->getTongDangKy() : 0;
$tong_check_in = isset($data) ? $data->getTongCheckIn() : 0;
$tong_check_out = isset($data) ? $data->getTongCheckOut() : 0;
$cho_phep_check_in = isset($data) ? $data->isAllowCheckIn() : 0;
$cho_phep_check_out = isset($data) ? $data->isAllowCheckOut() : 0;
$yeu_cau_face_id = isset($data) ? $data->isRequireFaceId() : 0;
$cho_phep_checkin_thu_cong = isset($data) ? $data->isAllowManualCheckin() : 0;
$bat_dau_dang_ky = isset($data) ? $data->getThoiGianBatDauDangKyFormatted() : '';
$ket_thuc_dang_ky = isset($data) ? $data->getThoiGianKetThucDangKyFormatted() : '';
$so_luong_tham_gia = isset($data) ? $data->getSoLuongThamGia() : 0;
$so_luong_dien_gia = isset($data) ? $data->getSoLuongDienGia() : 0;
$gioi_han_loai_nguoi_dung = isset($data) ? $data->getGioiHanLoaiNguoiDung() : '';
$hinh_thuc = isset($data) ? $data->getHinhThuc() : '';
$link_online = isset($data) ? $data->getLinkOnline() : '';
$mat_khau_online = isset($data) ? $data->getMatKhauOnline() : '';
$tu_khoa_su_kien = isset($data) ? $data->getTuKhoaSuKien() : '';
$hashtag = isset($data) ? $data->getHashtag() : '';
$slug = isset($data) ? $data->getSlug() : '';
$lich_trinh = isset($data) ? $data->getLichTrinh() : '';
$so_luot_xem = isset($data) ? $data->getSoLuotXem() : 0;
$ten_loai_su_kien = isset($data) ? $data->getTenLoaiSuKien() : '';
$created_at = isset($data) ? $data->getCreatedAtFormatted() : '';
$updated_at = isset($data) ? $data->getUpdatedAtFormatted() : '';
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

/* Thêm CSS cho timeline */
.timeline-container {
    padding: 1rem 0;
}

.timeline {
    position: relative;
    padding-left: 3rem;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e0e0e0;
}

.timeline-item {
    position: relative;
    margin-bottom: 2rem;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-dot {
    position: absolute;
    left: -3rem;
    width: 1.5rem;
    height: 1.5rem;
    border-radius: 50%;
    background: #007bff;
    top: 0;
    border: 3px solid #ffffff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.3);
}

.timeline-date {
    display: inline-block;
    padding: 0.3rem 0.7rem;
    background: #f8f9fa;
    border-radius: 0.25rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #495057;
}

.timeline-content {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.timeline-title {
    margin-top: 0;
    color: #343a40;
}
</style>

<div class="container-fluid px-0">
    <!-- Card header -->
    <div class="card shadow-sm mb-4 detail-card" style="--animation-order: 1">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="card-title mb-0 text-white">
                <i class="fas fa-info-circle me-2"></i> Chi tiết sự kiện
            </h4>
            <div class="d-flex gap-2">
                <a href="<?= base_url($module_name) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
                <a href="<?= base_url($module_name . '/edit/' . $su_kien_id) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
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
                            <label class="text-muted mb-1 fw-medium">ID sự kiện</label>
                            <div class="h5">
                                <span class="badge bg-primary px-3 py-2">#<?= $su_kien_id ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Tên sự kiện</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-tag me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($ten_su_kien) ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Mã sự kiện</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-code me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($ma_qr_code) ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Loại sự kiện</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-list-alt me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($ten_loai_su_kien) ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Hình thức</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-video me-2 text-primary"></i>
                                <?php if ($hinh_thuc == 'offline'): ?>
                                    <span class="badge bg-info">Offline</span>
                                <?php elseif ($hinh_thuc == 'online'): ?>
                                    <span class="badge bg-primary">Online</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Hybrid</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($hinh_thuc != 'offline'): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Link tham gia trực tuyến</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-link me-2 text-primary"></i>
                                <a href="<?= esc($link_online) ?>" target="_blank" class="fw-bold"><?= esc($link_online) ?></a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($slug)): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Slug</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-link me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($slug) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($hashtag)): ?>
                        <div class="mb-3">
                            <label class="text-muted mb-1 fw-medium">Hashtag</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-hashtag me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($hashtag) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin thời gian và địa điểm -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card info-section" style="--animation-order: 3">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-calendar-alt me-2"></i> Thời gian và địa điểm
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Thời gian bắt đầu</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                <span class="fw-bold"><?= $thoi_gian_bat_dau ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Thời gian kết thúc</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-calendar-check me-2 text-primary"></i>
                                <span class="fw-bold"><?= $thoi_gian_ket_thuc ?></span>
                            </div>
                        </div>

                        <?php if (!empty($bat_dau_dang_ky)): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Thời gian bắt đầu đăng ký</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-calendar-plus me-2 text-primary"></i>
                                <span class="fw-bold"><?= $bat_dau_dang_ky ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($ket_thuc_dang_ky)): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Thời gian kết thúc đăng ký</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-calendar-times me-2 text-primary"></i>
                                <span class="fw-bold"><?= $ket_thuc_dang_ky ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Địa điểm</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($dia_diem) ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1 fw-medium">Địa chỉ cụ thể</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-location-arrow me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($dia_chi_cu_the) ?></span>
                            </div>
                        </div>

                        <?php if (!empty($toa_do_gps)): ?>
                        <div class="mb-3">
                            <label class="text-muted mb-1 fw-medium">Tọa độ GPS</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-map me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($toa_do_gps) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin check-in và giới hạn tham gia -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card info-section" style="--animation-order: 4">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-users-cog me-2"></i> Cấu hình tham gia
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Cho phép check-in</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-sign-in-alt me-2 text-primary"></i>
                                <?php if ($cho_phep_check_in): ?>
                                    <span class="badge bg-success">Cho phép</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Không cho phép</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Cho phép check-out</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-sign-out-alt me-2 text-primary"></i>
                                <?php if ($cho_phep_check_out): ?>
                                    <span class="badge bg-success">Cho phép</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Không cho phép</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Yêu cầu xác thực khuôn mặt</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-user-check me-2 text-primary"></i>
                                <?php if ($yeu_cau_face_id): ?>
                                    <span class="badge bg-success">Yêu cầu</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Không yêu cầu</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Số lượng tham gia tối đa</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-users me-2 text-primary"></i>
                                <span class="fw-bold"><?= $so_luong_tham_gia ?> người</span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Số lượng diễn giả</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-user-tie me-2 text-primary"></i>
                                <span class="fw-bold"><?= $so_luong_dien_gia ?> người</span>
                            </div>
                        </div>

                        <?php if (!empty($gioi_han_loai_nguoi_dung)): ?>
                        <div class="mb-3">
                            <label class="text-muted mb-1 fw-medium">Giới hạn loại người dùng</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-user-shield me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($gioi_han_loai_nguoi_dung) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin trạng thái -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card info-section" style="--animation-order: 5">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-clipboard-list me-2"></i> Trạng thái
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Trạng thái</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-toggle-on me-2 text-primary"></i>
                                <?php if ($status == 1): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Vô hiệu</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Tổng số đăng ký</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-clipboard-list me-2 text-primary"></i>
                                <span class="fw-bold"><?= $tong_dang_ky ?> người</span>
                                <?php if (isset($statistics) && $statistics['tong_dang_ky'] > 0): ?>
                                    <div class="progress mt-2" style="height: 5px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: <?= min(100, $tong_dang_ky / max(1, $so_luong_tham_gia) * 100) ?>%" aria-valuenow="<?= $tong_dang_ky ?>" aria-valuemin="0" aria-valuemax="<?= $so_luong_tham_gia ?>"></div>
                                    </div>
                                    <small class="text-muted"><?= round(min(100, $tong_dang_ky / max(1, $so_luong_tham_gia) * 100), 1) ?>% công suất</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Tổng số check-in</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-sign-in-alt me-2 text-primary"></i>
                                <span class="fw-bold"><?= $tong_check_in ?> người</span>
                                <?php if (isset($statistics) && $statistics['tong_dang_ky'] > 0): ?>
                                    <div class="progress mt-2" style="height: 5px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= min(100, $tong_check_in / max(1, $tong_dang_ky) * 100) ?>%" aria-valuenow="<?= $tong_check_in ?>" aria-valuemin="0" aria-valuemax="<?= $tong_dang_ky ?>"></div>
                                    </div>
                                    <small class="text-muted"><?= isset($statistics['ty_le_check_in']) ? number_format($statistics['ty_le_check_in'], 1) : round(min(100, $tong_check_in / max(1, $tong_dang_ky) * 100), 1) ?>% tỷ lệ check-in</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Tổng số check-out</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-sign-out-alt me-2 text-primary"></i>
                                <span class="fw-bold"><?= $tong_check_out ?> người</span>
                                <?php if (isset($statistics) && $statistics['tong_check_in'] > 0): ?>
                                    <div class="progress mt-2" style="height: 5px;">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: <?= min(100, $tong_check_out / max(1, $tong_check_in) * 100) ?>%" aria-valuenow="<?= $tong_check_out ?>" aria-valuemin="0" aria-valuemax="<?= $tong_check_in ?>"></div>
                                    </div>
                                    <small class="text-muted"><?= isset($statistics['ty_le_check_out']) ? number_format($statistics['ty_le_check_out'], 1) : round(min(100, $tong_check_out / max(1, $tong_check_in) * 100), 1) ?>% tỷ lệ check-out</small>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Số lượt xem</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-eye me-2 text-primary"></i>
                                <span class="fw-bold"><?= $so_luot_xem ?> lượt</span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Ngày tạo</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-calendar-plus me-2 text-primary"></i>
                                <span class="fw-bold"><?= $created_at ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1 fw-medium">Ngày cập nhật</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-calendar-check me-2 text-primary"></i>
                                <span class="fw-bold"><?= $updated_at ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mô tả -->
        <div class="col-12">
            <div class="card shadow-sm detail-card info-section" style="--animation-order: 6">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-align-left me-2"></i> Mô tả
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="h5">
                        <?= empty($mo_ta) ? '<em class="text-muted">Không có mô tả</em>' : nl2br(esc($mo_ta)) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mô tả chi tiết -->
        <?php if (!empty($mo_ta_su_kien)): ?>
        <div class="col-12 mt-4">
            <div class="card shadow-sm detail-card info-section" style="--animation-order: 7">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-file-alt me-2"></i> Mô tả chi tiết
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="h5">
                        <?= nl2br(esc($mo_ta_su_kien)) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Chi tiết sự kiện -->
        <?php if (!empty($chi_tiet_su_kien)): ?>
        <div class="col-12 mt-4">
            <div class="card shadow-sm detail-card info-section" style="--animation-order: 8">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-info-circle me-2"></i> Chi tiết sự kiện
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="content-html">
                        <?= $chi_tiet_su_kien ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Chi tiết lịch trình sự kiện -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-calendar me-2"></i> Lịch trình sự kiện
                </h5>
            </div>
            <div class="card-body">
                <?php 
                $lichTrinh = [];
                if (!empty($data->lich_trinh)) {
                    if (is_string($data->lich_trinh)) {
                        $lichTrinh = json_decode($data->lich_trinh, true);
                    } else {
                        $lichTrinh = $data->lich_trinh;
                    }
                }
                
                if (empty($lichTrinh)): 
                ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Chưa có thông tin lịch trình cho sự kiện này.
                    </div>
                <?php else: ?>
                    <div class="timeline-container">
                        <div class="timeline">
                            <?php foreach ($lichTrinh as $index => $session): ?>
                                <div class="timeline-item">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-date">
                                        <?php if (!empty($session['thoi_gian_bat_dau'])): ?>
                                            <?= date('H:i', strtotime($session['thoi_gian_bat_dau'])) ?>
                                            <?php if (!empty($session['thoi_gian_ket_thuc'])): ?>
                                                - <?= date('H:i', strtotime($session['thoi_gian_ket_thuc'])) ?>
                                            <?php endif; ?>
                                            <div class="small text-muted">
                                                <?= date('d/m/Y', strtotime($session['thoi_gian_bat_dau'])) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="timeline-content">
                                        <h5 class="timeline-title"><?= esc($session['tieu_de']) ?></h5>
                                        <?php if (!empty($session['mo_ta'])): ?>
                                            <p class="mb-2"><?= esc($session['mo_ta']) ?></p>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($session['nguoi_phu_trach'])): ?>
                                            <div class="small text-primary">
                                                <i class="fas fa-user me-1"></i> <?= esc($session['nguoi_phu_trach']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Thông tin người tham gia -->
        <?php if (isset($participants) && is_array($participants) && count($participants) > 0): ?>
        <div class="col-12 mt-4">
            <div class="card shadow-sm detail-card info-section" style="--animation-order: 9">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-users me-2"></i> Danh sách người tham gia (<?= isset($totalParticipants) ? $totalParticipants : count($participants) ?>)
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Loại</th>
                                    <th>Trạng thái</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Ngày đăng ký</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($participants as $index => $participant): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= esc($participant['ho_ten']) ?></td>
                                    <td><?= esc($participant['email']) ?></td>
                                    <td>
                                        <?php 
                                        $loaiText = '';
                                        switch ($participant['loai_nguoi_dang_ky']) {
                                            case 'sinh_vien': $loaiText = '<span class="badge bg-info">Sinh viên</span>'; break;
                                            case 'giang_vien': $loaiText = '<span class="badge bg-primary">Giảng viên</span>'; break;
                                            case 'khach': $loaiText = '<span class="badge bg-secondary">Khách</span>'; break;
                                            default: $loaiText = '<span class="badge bg-secondary">Khác</span>';
                                        }
                                        echo $loaiText;
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $statusText = '';
                                        switch ($participant['status']) {
                                            case 1: $statusText = '<span class="badge bg-success">Đã xác nhận</span>'; break;
                                            case 0: $statusText = '<span class="badge bg-warning">Chờ xác nhận</span>'; break;
                                            case -1: $statusText = '<span class="badge bg-danger">Đã hủy</span>'; break;
                                            default: $statusText = '<span class="badge bg-secondary">Không xác định</span>';
                                        }
                                        echo $statusText;
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($participant['da_check_in']): ?>
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i> Đã check-in</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Chưa check-in</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($participant['da_check_out']): ?>
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i> Đã check-out</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Chưa check-out</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y H:i', strtotime($participant['created_at'])) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if (isset($totalParticipants) && $totalParticipants > count($participants)): ?>
                        <div class="text-center mt-3">
                            <a href="<?= base_url('quanlydangkysukien/index?su_kien_id='.$su_kien_id) ?>" class="btn btn-primary">
                                <i class="fas fa-list me-1"></i> Xem tất cả người tham gia
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
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
});
</script> 