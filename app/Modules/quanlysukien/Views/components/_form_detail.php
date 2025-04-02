<?php
/**
 * Component hiển thị chi tiết sự kiện
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu sự kiện cần hiển thị
 * @var string $module_name Tên module
 */

// Khởi tạo các biến mặc định
$ten_su_kien = isset($data) && isset($data->ten_su_kien) ? $data->ten_su_kien : '';
$mo_ta = isset($data) && isset($data->mo_ta) ? $data->mo_ta : '';
$mo_ta_su_kien = isset($data) && isset($data->mo_ta_su_kien) ? $data->mo_ta_su_kien : '';
$chi_tiet_su_kien = isset($data) && isset($data->chi_tiet_su_kien) ? $data->chi_tiet_su_kien : '';
$thoi_gian_bat_dau = isset($data) && isset($data->thoi_gian_bat_dau) ? $data->thoi_gian_bat_dau : '';
$thoi_gian_ket_thuc = isset($data) && isset($data->thoi_gian_ket_thuc) ? $data->thoi_gian_ket_thuc : '';
$dia_diem = isset($data) && isset($data->dia_diem) ? $data->dia_diem : '';
$dia_chi_cu_the = isset($data) && isset($data->dia_chi_cu_the) ? $data->dia_chi_cu_the : '';
$toa_do_gps = isset($data) && isset($data->toa_do_gps) ? $data->toa_do_gps : '';
$loai_su_kien_id = isset($data) && isset($data->loai_su_kien_id) ? $data->loai_su_kien_id : '';
$ma_qr_code = isset($data) && isset($data->ma_qr_code) ? $data->ma_qr_code : '';
$status = isset($data) && isset($data->status) ? $data->status : '';
$tong_dang_ky = isset($data) && isset($data->tong_dang_ky) ? $data->tong_dang_ky : 0;
$tong_check_in = isset($data) && isset($data->tong_check_in) ? $data->tong_check_in : 0;
$tong_check_out = isset($data) && isset($data->tong_check_out) ? $data->tong_check_out : 0;
$cho_phep_check_in = isset($data) && isset($data->cho_phep_check_in) ? $data->cho_phep_check_in : 0;
$cho_phep_check_out = isset($data) && isset($data->cho_phep_check_out) ? $data->cho_phep_check_out : 0;
$yeu_cau_face_id = isset($data) && isset($data->yeu_cau_face_id) ? $data->yeu_cau_face_id : 0;
$cho_phep_checkin_thu_cong = isset($data) && isset($data->cho_phep_checkin_thu_cong) ? $data->cho_phep_checkin_thu_cong : 0;
$bat_dau_dang_ky = isset($data) && isset($data->bat_dau_dang_ky) ? $data->bat_dau_dang_ky : '';
$ket_thuc_dang_ky = isset($data) && isset($data->ket_thuc_dang_ky) ? $data->ket_thuc_dang_ky : '';
$gio_bat_dau = isset($data) && isset($data->gio_bat_dau) ? $data->gio_bat_dau : '';
$gio_ket_thuc = isset($data) && isset($data->gio_ket_thuc) ? $data->gio_ket_thuc : '';
$so_luong_tham_gia = isset($data) && isset($data->so_luong_tham_gia) ? $data->so_luong_tham_gia : 0;
$so_luong_dien_gia = isset($data) && isset($data->so_luong_dien_gia) ? $data->so_luong_dien_gia : 0;
$gioi_han_loai_nguoi_dung = isset($data) && isset($data->gioi_han_loai_nguoi_dung) ? $data->gioi_han_loai_nguoi_dung : '';
$hinh_thuc = isset($data) && isset($data->hinh_thuc) ? $data->hinh_thuc : '';
$link_online = isset($data) && isset($data->link_online) ? $data->link_online : '';
$mat_khau_online = isset($data) && isset($data->mat_khau_online) ? $data->mat_khau_online : '';
$tu_khoa_su_kien = isset($data) && isset($data->tu_khoa_su_kien) ? $data->tu_khoa_su_kien : '';
$hashtag = isset($data) && isset($data->hashtag) ? $data->hashtag : '';
$slug = isset($data) && isset($data->slug) ? $data->slug : '';
$lich_trinh = isset($data) && isset($data->lich_trinh) ? $data->lich_trinh : '';
$so_luot_xem = isset($data) && isset($data->so_luot_xem) ? $data->so_luot_xem : 0;

// Khởi tạo các biến từ dữ liệu đầu vào
if (is_object($data)) {
    $su_kien_id = $data->getId() ?? '';
    $ten_loai_su_kien = $data->getTenLoaiSuKien() ?? '';
    $created_at = $data->getCreatedAtFormatted() ?? '';
    $updated_at = $data->getUpdatedAtFormatted() ?? '';
} else {
    $su_kien_id = '';
    $ten_loai_su_kien = '';
    $created_at = '';
    $updated_at = '';
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
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Tổng số check-in</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-sign-in-alt me-2 text-primary"></i>
                                <span class="fw-bold"><?= $tong_check_in ?> người</span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Tổng số check-out</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-sign-out-alt me-2 text-primary"></i>
                                <span class="fw-bold"><?= $tong_check_out ?> người</span>
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
                <h5 class="mb-0">
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