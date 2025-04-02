<?php
// Kiểm tra xem $data có phải là đối tượng hay không
if (is_object($data)) {
    $checkout_sukien_id = $data->getId() ?? '';
    $su_kien_id = $data->getSuKienId() ?? '';
    $ho_ten = $data->getHoTen() ?? '';
    $email = $data->getEmail() ?? '';
    $thoi_gian_check_out = $data->getThoiGianCheckOutFormatted('Y-m-d\TH:i') ?? date('Y-m-d\TH:i');
    $checkout_type = $data->getCheckoutType() ?? 'manual';
    $hinh_thuc_tham_gia = $data->getHinhThucThamGia() ?? 'offline';
    $status = $data->getStatus() ?? 1;
    $face_verified = $data->isFaceVerified() ? 1 : 0;
    $ma_xac_nhan = $data->getMaXacNhan() ?? '';
    $ghi_chu = $data->getGhiChu() ?? '';
    
    // Xử lý thông tin bổ sung đúng định dạng
    $thong_tin_bo_sung_data = $data->getThongTinBoSungJson();
    $thong_tin_bo_sung = '';
    if (is_array($thong_tin_bo_sung_data)) {
        $thong_tin_bo_sung = json_encode($thong_tin_bo_sung_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } elseif (is_string($thong_tin_bo_sung_data)) {
        $thong_tin_bo_sung = $thong_tin_bo_sung_data;
    }
    
    $danh_gia = $data->getDanhGia() ?? '';
    $noi_dung_danh_gia = $data->getNoiDungDanhGia() ?? '';
    $feedback = $data->getFeedback() ?? '';
    $attendance_duration_minutes = $data->getAttendanceDurationMinutes() ?? '';
} else {
    $checkout_sukien_id = '';
    $su_kien_id = '';
    $ho_ten = '';
    $email = '';
    $thoi_gian_check_out = date('Y-m-d\TH:i');
    $checkout_type = 'manual';
    $hinh_thuc_tham_gia = 'offline';
    $status = 1;
    $face_verified = 0;
    $ma_xac_nhan = '';
    $ghi_chu = '';
    $thong_tin_bo_sung = '';
    $danh_gia = '';
    $noi_dung_danh_gia = '';
    $feedback = '';
    $attendance_duration_minutes = '';
}

// Icon theo loại check-out
$checkout_icons = [
    'manual' => 'bx-edit',
    'face_id' => 'bx-face',
    'qr_code' => 'bx-qr-scan',
    'auto' => 'bx-timer',
    'online' => 'bx-globe'
];

// Màu theo trạng thái
$status_colors = [
    0 => 'danger',
    1 => 'success',
    2 => 'warning'
];

// Text theo trạng thái
$status_text = [
    0 => 'Vô hiệu',
    1 => 'Hoạt động',
    2 => 'Đang xử lý'
];
?>

<!-- Thông tin cơ bản -->
<div class="card mb-4 shadow-sm border-0 rounded-3">
    <div class="card-header bg-primary py-3 text-white">
        <h5 class="mb-0 text-white d-flex align-items-center"><i class="bx bx-info-circle me-2"></i>Thông tin cơ bản</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <label for="su_kien_id" class="form-label fw-medium">
                    <i class="bx bx-calendar-event text-primary me-1"></i>
                    Sự kiện <span class="text-danger">*</span>
                </label>
                <select class="form-select shadow-sm rounded-3 <?= isset($validation) && $validation->hasError('su_kien_id') ? 'is-invalid' : '' ?>" id="su_kien_id" name="su_kien_id" required>
                    <option value="">Chọn sự kiện</option>
                    <?php 
                    // Lấy danh sách sự kiện từ controller
                    $suKienModel = model('App\Modules\quanlysukien\Models\SuKienModel');
                    $suKienList = $suKienModel->findAll();
                    foreach ($suKienList as $suKien): 
                    ?>
                        <option value="<?= $suKien->su_kien_id ?>" <?= $su_kien_id == $suKien->su_kien_id ? 'selected' : '' ?>><?= esc($suKien->ten_su_kien) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($validation) && $validation->hasError('su_kien_id')) : ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('su_kien_id') ?>
                    </div>
                <?php endif ?>
            </div>

            <div class="col-md-6">
                <label for="status" class="form-label fw-medium">
                    <i class="bx bx-toggle-left text-primary me-1"></i>
                    Trạng thái
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-<?= $status_colors[$status] ?> text-white">
                        <i class="bx bx-check-circle"></i>
                    </span>
                    <select class="form-select border-start-0" id="status" name="status">
                        <option value="1" <?= $status == 1 ? 'selected' : '' ?> data-color="success">Hoạt động</option>
                        <option value="0" <?= $status == 0 ? 'selected' : '' ?> data-color="danger">Vô hiệu</option>
                        <option value="2" <?= $status == 2 ? 'selected' : '' ?> data-color="warning">Đang xử lý</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <label for="ho_ten" class="form-label fw-medium">
                    <i class="bx bx-user text-primary me-1"></i>
                    Họ tên <span class="text-danger">*</span>
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light">
                        <i class="bx bx-user"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 <?= isset($validation) && $validation->hasError('ho_ten') ? 'is-invalid' : '' ?>" id="ho_ten" name="ho_ten" value="<?= $ho_ten ?>" placeholder="Nhập họ tên người tham dự" required>
                </div>
                <?php if (isset($validation) && $validation->hasError('ho_ten')) : ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('ho_ten') ?>
                    </div>
                <?php endif ?>
            </div>

            <div class="col-md-6">
                <label for="email" class="form-label fw-medium">
                    <i class="bx bx-envelope text-primary me-1"></i>
                    Email <span class="text-danger">*</span>
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light">
                        <i class="bx bx-envelope"></i>
                    </span>
                    <input type="email" class="form-control border-start-0 <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= $email ?>" placeholder="email@example.com" required>
                </div>
                <?php if (isset($validation) && $validation->hasError('email')) : ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('email') ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>

<!-- Thông tin check-out -->
<div class="card mb-4 shadow-sm border-0 rounded-3">
    <div class="card-header bg-info py-3 text-white">
        <h5 class="mb-0 text-white d-flex align-items-center"><i class="bx bx-exit me-2"></i>Thông tin check-out</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-6">
                <label for="thoi_gian_check_out" class="form-label fw-medium">
                    <i class="bx bx-time text-primary me-1"></i>
                    Thời gian check-out <span class="text-danger">*</span>
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light">
                        <i class="bx bx-calendar"></i>
                    </span>
                    <input type="datetime-local" class="form-control border-start-0 <?= isset($validation) && $validation->hasError('thoi_gian_check_out') ? 'is-invalid' : '' ?>" id="thoi_gian_check_out" name="thoi_gian_check_out" value="<?= $thoi_gian_check_out ?>" required>
                </div>
                <?php if (isset($validation) && $validation->hasError('thoi_gian_check_out')) : ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('thoi_gian_check_out') ?>
                    </div>
                <?php endif ?>
            </div>

            <div class="col-md-6">
                <label for="checkout_type" class="form-label fw-medium">
                    <i class="bx bx-log-out-circle text-primary me-1"></i>
                    Loại check-out <span class="text-danger">*</span>
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light checkout-type-icon">
                        <i class="bx <?= $checkout_icons[$checkout_type] ?>"></i>
                    </span>
                    <select class="form-select border-start-0 <?= isset($validation) && $validation->hasError('checkout_type') ? 'is-invalid' : '' ?>" id="checkout_type" name="checkout_type" required>
                        <option value="manual" <?= $checkout_type == 'manual' ? 'selected' : '' ?> data-icon="<?= $checkout_icons['manual'] ?>">Thủ công</option>
                        <option value="face_id" <?= $checkout_type == 'face_id' ? 'selected' : '' ?> data-icon="<?= $checkout_icons['face_id'] ?>">Nhận diện khuôn mặt</option>
                        <option value="qr_code" <?= $checkout_type == 'qr_code' ? 'selected' : '' ?> data-icon="<?= $checkout_icons['qr_code'] ?>">Mã QR</option>
                        <option value="auto" <?= $checkout_type == 'auto' ? 'selected' : '' ?> data-icon="<?= $checkout_icons['auto'] ?>">Tự động</option>
                        <option value="online" <?= $checkout_type == 'online' ? 'selected' : '' ?> data-icon="<?= $checkout_icons['online'] ?>">Trực tuyến</option>
                    </select>
                </div>
                <?php if (isset($validation) && $validation->hasError('checkout_type')) : ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('checkout_type') ?>
                    </div>
                <?php endif ?>
            </div>

            <div class="col-md-6">
                <label for="hinh_thuc_tham_gia" class="form-label fw-medium">
                    <i class="bx <?= $hinh_thuc_tham_gia == 'offline' ? 'bx-building' : 'bx-globe' ?> text-primary me-1"></i>
                    Hình thức tham gia <span class="text-danger">*</span>
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light hinh-thuc-icon">
                        <i class="bx <?= $hinh_thuc_tham_gia == 'offline' ? 'bx-building' : 'bx-globe' ?>"></i>
                    </span>
                    <select class="form-select border-start-0 <?= isset($validation) && $validation->hasError('hinh_thuc_tham_gia') ? 'is-invalid' : '' ?>" id="hinh_thuc_tham_gia" name="hinh_thuc_tham_gia" required>
                        <option value="offline" <?= $hinh_thuc_tham_gia == 'offline' ? 'selected' : '' ?> data-icon="bx-building">Trực tiếp</option>
                        <option value="online" <?= $hinh_thuc_tham_gia == 'online' ? 'selected' : '' ?> data-icon="bx-globe">Trực tuyến</option>
                    </select>
                </div>
                <?php if (isset($validation) && $validation->hasError('hinh_thuc_tham_gia')) : ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('hinh_thuc_tham_gia') ?>
                    </div>
                <?php endif ?>
            </div>

            <div class="col-md-6">
                <label for="ma_xac_nhan" class="form-label fw-medium">
                    <i class="bx bx-code text-primary me-1"></i>
                    Mã xác nhận
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light">
                        <i class="bx bx-barcode"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 <?= isset($validation) && $validation->hasError('ma_xac_nhan') ? 'is-invalid' : '' ?>" id="ma_xac_nhan" name="ma_xac_nhan" value="<?= $ma_xac_nhan ?>" placeholder="Nhập mã xác nhận">
                </div>
                <?php if (isset($validation) && $validation->hasError('ma_xac_nhan')) : ?>
                    <div class="invalid-feedback">
                        <?= $validation->getError('ma_xac_nhan') ?>
                    </div>
                <?php endif ?>
            </div>
            
            <div class="col-md-6">
                <label for="attendance_duration_minutes" class="form-label fw-medium">
                    <i class="bx bx-time-five text-primary me-1"></i>
                    Thời gian tham dự (phút)
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light">
                        <i class="bx bx-stopwatch"></i>
                    </span>
                    <input type="number" class="form-control border-start-0" id="attendance_duration_minutes" name="attendance_duration_minutes" value="<?= $attendance_duration_minutes ?>" min="0" placeholder="Nhập số phút">
                </div>
                <div class="form-text mt-2">
                    <i class="bx bx-info-circle"></i>
                    Thời gian tham dự sự kiện tính bằng phút
                </div>
            </div>

            <div class="col-md-6 face-verified-section" <?= $checkout_type != 'face_id' ? 'style="display:none"' : '' ?>>
                <label for="face_verified" class="form-label fw-medium">
                    <i class="bx bx-check-shield text-primary me-1"></i>
                    Xác minh khuôn mặt
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-<?= $face_verified ? 'success' : 'danger' ?>">
                        <i class="bx <?= $face_verified ? 'bx-check' : 'bx-x' ?> text-white"></i>
                    </span>
                    <select class="form-select border-start-0" id="face_verified" name="face_verified">
                        <option value="1" <?= $face_verified == 1 ? 'selected' : '' ?> data-icon="bx-check" data-color="success">Đã xác minh</option>
                        <option value="0" <?= $face_verified == 0 ? 'selected' : '' ?> data-icon="bx-x" data-color="danger">Chưa xác minh</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Đánh giá và phản hồi -->
<div class="card mb-4 shadow-sm border-0 rounded-3">
    <div class="card-header bg-warning py-3 text-white">
        <h5 class="mb-0 text-white d-flex align-items-center"><i class="bx bx-star me-2"></i>Đánh giá & Phản hồi</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-12">
                <label class="form-label fw-medium">
                    <i class="bx bx-star text-warning me-1"></i>
                    Đánh giá
                </label>
                <div class="bg-light p-3 rounded-3 mb-3 d-flex align-items-center">
                    <div class="rating-stars mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <input type="radio" id="star<?= $i ?>" name="danh_gia" value="<?= $i ?>" <?= $danh_gia == $i ? 'checked' : '' ?> class="d-none">
                        <label for="star<?= $i ?>" class="star-label bx <?= $i <= $danh_gia ? 'bxs-star' : 'bx-star' ?> fs-1 text-warning me-2" data-value="<?= $i ?>" style="cursor: pointer; transition: all 0.2s;"></label>
                        <?php endfor; ?>
                    </div>
                    <input type="hidden" id="danh_gia_value" name="danh_gia" value="<?= $danh_gia ?>">
                    <div class="ms-auto">
                        <?php
                        $rating_labels = [
                            1 => '<span class="badge bg-danger py-2 px-3 fs-6">Rất không hài lòng</span>',
                            2 => '<span class="badge bg-warning text-dark py-2 px-3 fs-6">Không hài lòng</span>',
                            3 => '<span class="badge bg-secondary py-2 px-3 fs-6">Bình thường</span>',
                            4 => '<span class="badge bg-info py-2 px-3 fs-6">Hài lòng</span>',
                            5 => '<span class="badge bg-success py-2 px-3 fs-6">Rất hài lòng</span>'
                        ];
                        ?>
                        <div id="rating_label">
                            <?= $danh_gia ? $rating_labels[$danh_gia] : '<span class="badge bg-light text-dark py-2 px-3 fs-6">Chưa đánh giá</span>' ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-12">
                <label for="noi_dung_danh_gia" class="form-label fw-medium">
                    <i class="bx bx-message-detail text-primary me-1"></i>
                    Nội dung đánh giá
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light align-items-start" style="height: auto; padding-top: 10px;">
                        <i class="bx bx-comment-detail"></i>
                    </span>
                    <textarea class="form-control border-start-0" id="noi_dung_danh_gia" name="noi_dung_danh_gia" rows="3" placeholder="Nhập nội dung đánh giá từ người tham dự..."><?= $noi_dung_danh_gia ?></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <label for="feedback" class="form-label fw-medium">
                    <i class="bx bx-chat text-primary me-1"></i>
                    Phản hồi
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light align-items-start" style="height: auto; padding-top: 10px;">
                        <i class="bx bx-message"></i>
                    </span>
                    <textarea class="form-control border-start-0" id="feedback" name="feedback" rows="3" placeholder="Nhập phản hồi từ ban tổ chức..."><?= $feedback ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thông tin bổ sung -->
<div class="card mb-4 shadow-sm border-0 rounded-3">
    <div class="card-header bg-secondary py-3 text-white">
        <h5 class="mb-0 text-white d-flex align-items-center"><i class="bx bx-detail me-2 text-white"></i>Thông tin bổ sung</h5>
    </div>
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-12">
                <label for="ghi_chu" class="form-label fw-medium">
                    <i class="bx bx-note text-primary me-1"></i>
                    Ghi chú
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light align-items-start" style="height: auto; padding-top: 10px;">
                        <i class="bx bx-notepad"></i>
                    </span>
                    <textarea class="form-control border-start-0" id="ghi_chu" name="ghi_chu" rows="3" placeholder="Nhập các ghi chú liên quan đến việc check-out..."><?= $ghi_chu ?></textarea>
                </div>
            </div>

            <div class="col-md-12">
                <label for="thong_tin_bo_sung" class="form-label fw-medium">
                    <i class="bx bx-list-plus text-primary me-1"></i>
                    Thông tin bổ sung (JSON)
                </label>
                <div class="input-group shadow-sm rounded-3 overflow-hidden">
                    <span class="input-group-text bg-light align-items-start" style="height: auto; padding-top: 10px;">
                        <i class="bx bx-code-block"></i>
                    </span>
                    <textarea class="form-control border-start-0 font-monospace" id="thong_tin_bo_sung" name="thong_tin_bo_sung" rows="4" placeholder='{"dien_thoai":"0123456789","dia_chi":"Hà Nội"}'><?= $thong_tin_bo_sung ?></textarea>
                </div>
                <div class="form-text mt-2">
                    <i class="bx bx-info-circle me-1"></i>
                    Định dạng JSON, ví dụ: {"dien_thoai":"0123456789","dia_chi":"Hà Nội"}
                </div>
                <div id="json_validation" class="invalid-feedback d-none">
                    Định dạng JSON không hợp lệ. Vui lòng kiểm tra lại.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Nút điều khiển -->
<div class="d-flex justify-content-between mt-4 mb-2">
    <a href="<?= site_url($module_name) ?>" class="btn btn-outline-secondary px-4 py-2 rounded-3">
        <i class="bx bx-arrow-back me-1"></i>Quay lại
    </a>
    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 fw-medium">
        <i class="bx bx-save me-1"></i>Lưu thông tin
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý đánh giá sao
    const stars = document.querySelectorAll('.star-label');
    const ratingValue = document.getElementById('danh_gia_value');
    const ratingLabel = document.getElementById('rating_label');
    const ratingLabels = {
        1: '<span class="badge bg-danger py-2 px-3 fs-6">Rất không hài lòng</span>',
        2: '<span class="badge bg-warning text-dark py-2 px-3 fs-6">Không hài lòng</span>',
        3: '<span class="badge bg-secondary py-2 px-3 fs-6">Bình thường</span>',
        4: '<span class="badge bg-info py-2 px-3 fs-6">Hài lòng</span>',
        5: '<span class="badge bg-success py-2 px-3 fs-6">Rất hài lòng</span>'
    };
    
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            
            // Cập nhật đánh giá
            ratingValue.value = value;
            
            // Cập nhật hiển thị sao
            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.remove('bx-star');
                    s.classList.add('bxs-star');
                } else {
                    s.classList.remove('bxs-star');
                    s.classList.add('bx-star');
                }
            });
            
            // Cập nhật văn bản đánh giá
            ratingLabel.innerHTML = ratingLabels[value];
        });
        
        // Hiệu ứng hover
        star.addEventListener('mouseenter', function() {
            const value = this.getAttribute('data-value');
            
            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.remove('bx-star');
                    s.classList.add('bxs-star');
                } else {
                    s.classList.remove('bxs-star');
                    s.classList.add('bx-star');
                }
            });
        });
        
        // Khôi phục khi rời chuột
        star.addEventListener('mouseleave', function() {
            const currentValue = ratingValue.value;
            
            stars.forEach((s, index) => {
                if (index < currentValue) {
                    s.classList.remove('bx-star');
                    s.classList.add('bxs-star');
                } else {
                    s.classList.remove('bxs-star');
                    s.classList.add('bx-star');
                }
            });
        });
    });
    
    // Xử lý chọn hình thức tham gia
    const hinhThucSelect = document.getElementById('hinh_thuc_tham_gia');
    const hinhThucIcon = document.querySelector('.hinh-thuc-icon i');
    
    hinhThucSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const iconClass = selectedOption.getAttribute('data-icon');
        
        // Cập nhật icon
        hinhThucIcon.className = 'bx ' + iconClass;
    });
    
    // Xử lý chọn loại check-out
    const checkoutTypeSelect = document.getElementById('checkout_type');
    const checkoutTypeIcon = document.querySelector('.checkout-type-icon i');
    const faceVerifiedSection = document.querySelector('.face-verified-section');
    
    checkoutTypeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const iconClass = selectedOption.getAttribute('data-icon');
        
        // Cập nhật icon
        checkoutTypeIcon.className = 'bx ' + iconClass;
        
        // Khi chọn face_id, hiển thị trường face_verified
        if (this.value === 'face_id') {
            faceVerifiedSection.style.display = 'block';
        } else {
            faceVerifiedSection.style.display = 'none';
        }
    });
    
    // Xử lý chọn trạng thái
    const statusSelect = document.getElementById('status');
    const statusIcon = statusSelect.previousElementSibling;
    
    statusSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const colorClass = selectedOption.getAttribute('data-color');
        
        // Cập nhật màu nền
        statusIcon.className = statusIcon.className.replace(/bg-\w+/, 'bg-' + colorClass);
    });
    
    // Xử lý chọn xác minh khuôn mặt
    const faceVerifiedSelect = document.getElementById('face_verified');
    if (faceVerifiedSelect) {
        const faceVerifiedIcon = faceVerifiedSelect.previousElementSibling;
        
        faceVerifiedSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const iconClass = selectedOption.getAttribute('data-icon');
            const colorClass = selectedOption.getAttribute('data-color');
            
            // Cập nhật icon và màu nền
            faceVerifiedIcon.querySelector('i').className = 'bx ' + iconClass + ' text-white';
            faceVerifiedIcon.className = faceVerifiedIcon.className.replace(/bg-\w+/, 'bg-' + colorClass);
        });
    }
    
    // Kiểm tra định dạng JSON cho thông tin bổ sung
    const thongTinBoSungInput = document.getElementById('thong_tin_bo_sung');
    const jsonValidation = document.getElementById('json_validation');
    
    thongTinBoSungInput.addEventListener('blur', function() {
        const value = this.value.trim();
        
        if (value) {
            try {
                JSON.parse(value);
                // JSON hợp lệ
                jsonValidation.classList.add('d-none');
                this.classList.remove('is-invalid');
            } catch (e) {
                // JSON không hợp lệ
                jsonValidation.classList.remove('d-none');
                this.classList.add('is-invalid');
            }
        } else {
            // Trường trống là hợp lệ
            jsonValidation.classList.add('d-none');
            this.classList.remove('is-invalid');
        }
    });
    
    // Kiểm tra trước khi submit form
    document.querySelector('form').addEventListener('submit', function(e) {
        const thongTinBoSung = thongTinBoSungInput.value.trim();
        
        if (thongTinBoSung) {
            try {
                JSON.parse(thongTinBoSung);
            } catch (error) {
                e.preventDefault();
                jsonValidation.classList.remove('d-none');
                thongTinBoSungInput.classList.add('is-invalid');
                thongTinBoSungInput.focus();
            }
        }
    });
});
</script> 