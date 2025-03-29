<?php
/**
 * Form component for creating and updating check-in sự kiện
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var CheckInSuKien $data CheckInSuKien entity data for editing (optional)
 * @var array $suKienList List of all events
 */

// Set default values if editing
$id = isset($id) ? $id : 0;
$su_kien_id = isset($su_kien_id) ? $su_kien_id : '';
$dangky_sukien_id = isset($dangky_sukien_id) ? $dangky_sukien_id : '';
$ho_ten = isset($ho_ten) ? $ho_ten : '';
$email = isset($email) ? $email : '';
$thoi_gian_check_in = isset($thoi_gian_check_in) ? $thoi_gian_check_in : date('Y-m-d H:i:s');
$checkin_type = isset($checkin_type) ? $checkin_type : 'manual';
$face_verified = isset($face_verified) ? $face_verified : 0;
$face_match_score = isset($face_match_score) ? $face_match_score : '';
$ma_xac_nhan = isset($ma_xac_nhan) ? $ma_xac_nhan : '';
$hinh_thuc_tham_gia = isset($hinh_thuc_tham_gia) ? $hinh_thuc_tham_gia : 'offline';
$ghi_chu = isset($ghi_chu) ? $ghi_chu : '';
$status = isset($status) ? $status : 1;

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$su_kien_id = old('su_kien_id', $su_kien_id);
$dangky_sukien_id = old('dangky_sukien_id', $dangky_sukien_id);
$ho_ten = old('ho_ten', $ho_ten);
$email = old('email', $email);
$thoi_gian_check_in = old('thoi_gian_check_in', $thoi_gian_check_in);
$checkin_type = old('checkin_type', $checkin_type);
$face_verified = old('face_verified', $face_verified);
$face_match_score = old('face_match_score', $face_match_score);
$ma_xac_nhan = old('ma_xac_nhan', $ma_xac_nhan);
$hinh_thuc_tham_gia = old('hinh_thuc_tham_gia', $hinh_thuc_tham_gia);
$ghi_chu = old('ghi_chu', $ghi_chu);
$status = old('status', $status);

// Kiểm tra biến validation tồn tại
$validation = $validation ?? [];
$errorClass = 'is-invalid';
$feedbackClass = 'invalid-feedback';

// Lấy giá trị từ dữ liệu record hoặc post data
function getValue($field, $record, $post) {
    if (isset($post[$field])) {
        return $post[$field];
    } elseif (isset($record) && method_exists($record, 'get' . ucfirst($field))) {
        $method = 'get' . ucfirst($field);
        return $record->$method();
    } elseif (isset($record) && isset($record->$field)) {
        return $record->$field;
    }
    return '';
}
?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0">
            <i class='bx bx-log-in-circle text-primary me-2'></i>
            Thông tin check-in sự kiện
        </h5>
    </div>
    
    <div class="card-body">
        <div class="row g-3">
            <!-- ID -->
            <?php if ($id): ?>
            <input type="hidden" name="checkin_sukien_id" value="<?= $id ?>">
            <?php endif; ?>
            
            <!-- su_kien_id -->
            <div class="col-md-6">
                <label for="su_kien_id" class="form-label fw-semibold">
                    Sự kiện <span class="text-danger">*</span>
                </label>
                <select class="form-select <?= isset($validation) && isset($validation['su_kien_id']) ? 'is-invalid' : '' ?>" 
                        id="su_kien_id" name="su_kien_id" required>
                    <option value="">Chọn sự kiện</option>
                    <?php foreach ($suKienList as $suKien): ?>
                    <option value="<?= $suKien->su_kien_id ?>" <?= $su_kien_id == $suKien->su_kien_id ? 'selected' : '' ?>>
                        <?= esc($suKien->ten_su_kien) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($validation) && isset($validation['su_kien_id'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['su_kien_id'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- ho_ten -->
            <div class="col-md-6">
                <label for="ho_ten" class="form-label fw-semibold">
                    Họ tên <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       class="form-control <?= isset($validation) && isset($validation['ho_ten']) ? 'is-invalid' : '' ?>" 
                       id="ho_ten" name="ho_ten"
                       value="<?= esc($ho_ten) ?>"
                       required>
                <?php if (isset($validation) && isset($validation['ho_ten'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['ho_ten'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- email -->
            <div class="col-md-6">
                <label for="email" class="form-label fw-semibold">
                    Email <span class="text-danger">*</span>
                </label>
                <input type="email" 
                       class="form-control <?= isset($validation) && isset($validation['email']) ? 'is-invalid' : '' ?>" 
                       id="email" name="email"
                       value="<?= esc($email) ?>"
                       required>
                <?php if (isset($validation) && isset($validation['email'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['email'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- dangky_sukien_id -->
            <div class="col-md-6">
                <label for="dangky_sukien_id" class="form-label fw-semibold">
                    ID đăng ký sự kiện
                </label>
                <input type="number" 
                       class="form-control <?= isset($validation) && isset($validation['dangky_sukien_id']) ? 'is-invalid' : '' ?>" 
                       id="dangky_sukien_id" name="dangky_sukien_id"
                       value="<?= esc($dangky_sukien_id) ?>">
                <?php if (isset($validation) && isset($validation['dangky_sukien_id'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['dangky_sukien_id'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- thoi_gian_check_in -->
            <div class="col-md-6">
                <label for="thoi_gian_check_in" class="form-label fw-semibold">
                    Thời gian check-in
                </label>
                <input type="datetime-local" 
                       class="form-control <?= isset($validation) && isset($validation['thoi_gian_check_in']) ? 'is-invalid' : '' ?>" 
                       id="thoi_gian_check_in" name="thoi_gian_check_in"
                       value="<?= date('Y-m-d\TH:i', strtotime($thoi_gian_check_in)) ?>">
                <?php if (isset($validation) && isset($validation['thoi_gian_check_in'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['thoi_gian_check_in'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- checkin_type -->
            <div class="col-md-6">
                <label for="checkin_type" class="form-label fw-semibold">
                    Loại check-in <span class="text-danger">*</span>
                </label>
                <select class="form-select <?= isset($validation) && isset($validation['checkin_type']) ? 'is-invalid' : '' ?>" 
                        id="checkin_type" name="checkin_type" required>
                    <option value="manual" <?= $checkin_type == 'manual' ? 'selected' : '' ?>>Thủ công</option>
                    <option value="qr_code" <?= $checkin_type == 'qr_code' ? 'selected' : '' ?>>QR Code</option>
                    <option value="face_id" <?= $checkin_type == 'face_id' ? 'selected' : '' ?>>Face ID</option>
                </select>
                <?php if (isset($validation) && isset($validation['checkin_type'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['checkin_type'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- hinh_thuc_tham_gia -->
            <div class="col-md-6">
                <label for="hinh_thuc_tham_gia" class="form-label fw-semibold">
                    Hình thức tham gia <span class="text-danger">*</span>
                </label>
                <select class="form-select <?= isset($validation) && isset($validation['hinh_thuc_tham_gia']) ? 'is-invalid' : '' ?>" 
                        id="hinh_thuc_tham_gia" name="hinh_thuc_tham_gia" required>
                    <option value="offline" <?= $hinh_thuc_tham_gia == 'offline' ? 'selected' : '' ?>>Trực tiếp</option>
                    <option value="online" <?= $hinh_thuc_tham_gia == 'online' ? 'selected' : '' ?>>Trực tuyến</option>
                </select>
                <?php if (isset($validation) && isset($validation['hinh_thuc_tham_gia'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['hinh_thuc_tham_gia'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Phần dành cho face_id -->
            <div id="face_id_section" class="col-12 <?= $checkin_type !== 'face_id' ? 'd-none' : '' ?>">
                <div class="row g-3">
                    <!-- face_image -->
                    <div class="col-md-6">
                        <label for="face_image" class="form-label fw-semibold">
                            Ảnh khuôn mặt
                        </label>
                        <input type="file" 
                               class="form-control <?= isset($validation) && isset($validation['face_image']) ? 'is-invalid' : '' ?>" 
                               id="face_image" name="face_image"
                               accept="image/*">
                        <?php if (isset($validation) && isset($validation['face_image'])): ?>
                        <div class="invalid-feedback">
                            <?= $validation['face_image'] ?>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($data) && !empty($data->getFaceImagePath())): ?>
                        <div class="mt-2">
                            <img src="<?= base_url('uploads/faces/' . $data->getFaceImagePath()) ?>" alt="Ảnh khuôn mặt" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- face_match_score -->
                    <div class="col-md-6">
                        <label for="face_match_score" class="form-label fw-semibold">
                            Điểm khớp khuôn mặt
                        </label>
                        <input type="number" 
                               class="form-control <?= isset($validation) && isset($validation['face_match_score']) ? 'is-invalid' : '' ?>" 
                               id="face_match_score" name="face_match_score"
                               value="<?= esc($face_match_score) ?>"
                               min="0" max="1" step="0.01">
                        <?php if (isset($validation) && isset($validation['face_match_score'])): ?>
                        <div class="invalid-feedback">
                            <?= $validation['face_match_score'] ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- face_verified -->
                    <div class="col-md-6">
                        <label for="face_verified" class="form-label fw-semibold">
                            Xác minh khuôn mặt
                        </label>
                        <select class="form-select <?= isset($validation) && isset($validation['face_verified']) ? 'is-invalid' : '' ?>" 
                                id="face_verified" name="face_verified">
                            <option value="0" <?= $face_verified == 0 ? 'selected' : '' ?>>Chưa xác minh</option>
                            <option value="1" <?= $face_verified == 1 ? 'selected' : '' ?>>Đã xác minh</option>
                        </select>
                        <?php if (isset($validation) && isset($validation['face_verified'])): ?>
                        <div class="invalid-feedback">
                            <?= $validation['face_verified'] ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ma_xac_nhan -->
            <div class="col-md-6">
                <label for="ma_xac_nhan" class="form-label fw-semibold">
                    Mã xác nhận
                </label>
                <input type="text" 
                       class="form-control <?= isset($validation) && isset($validation['ma_xac_nhan']) ? 'is-invalid' : '' ?>" 
                       id="ma_xac_nhan" name="ma_xac_nhan"
                       value="<?= esc($ma_xac_nhan) ?>"
                       placeholder="Để trống để tự động tạo mã">
                <?php if (isset($validation) && isset($validation['ma_xac_nhan'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['ma_xac_nhan'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- status -->
            <div class="col-md-6">
                <label for="status" class="form-label fw-semibold">
                    Trạng thái
                </label>
                <select class="form-select <?= isset($validation) && isset($validation['status']) ? 'is-invalid' : '' ?>" 
                        id="status" name="status">
                    <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="2" <?= $status == 2 ? 'selected' : '' ?>>Đang xử lý</option>
                    <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Vô hiệu</option>
                </select>
                <?php if (isset($validation) && isset($validation['status'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['status'] ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- ghi_chu -->
            <div class="col-md-12">
                <label for="ghi_chu" class="form-label fw-semibold">
                    Ghi chú
                </label>
                <textarea class="form-control <?= isset($validation) && isset($validation['ghi_chu']) ? 'is-invalid' : '' ?>" 
                         id="ghi_chu" name="ghi_chu"
                         rows="3"><?= esc($ghi_chu) ?></textarea>
                <?php if (isset($validation) && isset($validation['ghi_chu'])): ?>
                <div class="invalid-feedback">
                    <?= $validation['ghi_chu'] ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="card-footer bg-light py-3">
        <div class="d-flex justify-content-between align-items-center">
            <span class="text-muted small">
                <i class='bx bx-info-circle me-1'></i>
                Các trường có dấu <span class="text-danger">*</span> là bắt buộc
            </span>
            
            <div class="d-flex gap-2">
                <a href="<?= site_url($module_name) ?>" class="btn btn-light">
                    <i class='bx bx-arrow-back me-1'></i> Quay lại
                </a>
                <button class="btn btn-primary px-4" type="submit">
                    <i class='bx bx-save me-1'></i>
                    <?= $isUpdate ? 'Cập nhật' : 'Thêm mới' ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkinTypeSelect = document.getElementById('checkin_type');
    const faceIdSection = document.getElementById('face_id_section');
    
    // Hiển thị/ẩn phần Face ID dựa trên loại check-in
    function toggleFaceIdSection() {
        if (checkinTypeSelect.value === 'face_id') {
            faceIdSection.classList.remove('d-none');
        } else {
            faceIdSection.classList.add('d-none');
        }
    }
    
    // Gọi hàm ban đầu
    toggleFaceIdSection();
    
    // Thêm sự kiện khi thay đổi loại check-in
    checkinTypeSelect.addEventListener('change', toggleFaceIdSection);
    
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>