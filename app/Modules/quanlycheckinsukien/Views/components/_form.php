<?php
// Kiểm tra xem $data có phải là đối tượng hay không
if (is_object($data)) {
    $checkin_sukien_id = $data->getId() ?? '';
    $su_kien_id = $data->getSuKienId() ?? '';
    $ho_ten = $data->getHoTen() ?? '';
    $email = $data->getEmail() ?? '';
    $thoi_gian_check_in = $data->getThoiGianCheckInFormatted('Y-m-d\TH:i') ?? date('Y-m-d\TH:i');
    $checkin_type = $data->getCheckinType() ?? 'manual';
    $hinh_thuc_tham_gia = $data->getHinhThucThamGia() ?? 'offline';
    $status = $data->getStatus() ?? 1;
    $face_verified = $data->isFaceVerified() ? 1 : 0;
    $ma_xac_nhan = $data->getMaXacNhan() ?? '';
    $ghi_chu = $data->getGhiChu() ?? '';
    $thong_tin_bo_sung = $data->getThongTinBoSungJson() ?? '';
} else {
    $checkin_sukien_id = '';
    $su_kien_id = '';
    $ho_ten = '';
    $email = '';
    $thoi_gian_check_in = date('Y-m-d\TH:i');
    $checkin_type = 'manual';
    $hinh_thuc_tham_gia = 'offline';
    $status = 1;
    $face_verified = 0;
    $ma_xac_nhan = '';
    $ghi_chu = '';
    $thong_tin_bo_sung = '';
}
?>

<style>
.form-card {
    transition: all 0.3s ease;
    border: none;
}

.form-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    transform: translateY(-2px);
}

.card-header {
    border-bottom: 0;
    border-top-left-radius: 8px !important;
    border-top-right-radius: 8px !important;
    padding: 1rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

.form-control, .form-select {
    transition: all 0.2s ease;
    padding: 0.6rem 1rem;
    border-radius: 6px;
}

.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-control-lg, .form-select-lg {
    height: calc(2.5rem + 2px);
}

.btn {
    border-radius: 6px;
    padding: 0.6rem 1.5rem;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.input-group .btn {
    padding-top: 0.6rem;
    padding-bottom: 0.6rem;
}

.face-verification-section {
    transition: all 0.5s ease;
}

.form-switch .form-check-input {
    height: 1.5rem;
    width: 3rem !important;
    cursor: pointer;
}

.form-label i {
    transition: all 0.3s ease;
}

.form-label:hover i {
    transform: scale(1.2);
}
</style>

<div class="row">
    <!-- Thông tin cơ bản -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-info-circle me-2"></i> Thông tin cơ bản
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="su_kien_id" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt text-primary me-1"></i> Sự kiện <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('su_kien_id') ? 'is-invalid' : '' ?>" id="su_kien_id" name="su_kien_id" required>
                            <option value="">-- Chọn sự kiện --</option>
                            <?php 
                            // Lấy danh sách sự kiện từ controller
                            $suKienModel = model('App\Modules\sukien\Models\SuKienModel');
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
                        <?php else: ?>
                            <div class="form-text">Chọn sự kiện mà người tham gia check-in</div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thoi_gian_check_in" class="form-label fw-bold">
                            <i class="fas fa-clock text-primary me-1"></i> Thời gian check-in <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('thoi_gian_check_in') ? 'is-invalid' : '' ?>" id="thoi_gian_check_in" name="thoi_gian_check_in" value="<?= $thoi_gian_check_in ?>" required>
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_check_in')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_check_in') ?>
                            </div>
                        <?php else: ?>
                            <div class="form-text">Thời điểm người tham gia check-in vào sự kiện</div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin người tham gia -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-user me-2"></i> Thông tin người tham gia
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="ho_ten" class="form-label fw-bold">
                            <i class="fas fa-user-tag text-info me-1"></i> Họ tên <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ho_ten') ? 'is-invalid' : '' ?>" id="ho_ten" name="ho_ten" value="<?= $ho_ten ?>" placeholder="Nhập họ tên đầy đủ" required>
                        <?php if (isset($validation) && $validation->hasError('ho_ten')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ho_ten') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold">
                            <i class="fas fa-envelope text-info me-1"></i> Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= $email ?>" placeholder="example@domain.com" required>
                        <?php if (isset($validation) && $validation->hasError('email')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('email') ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin check-in -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-clipboard-check me-2"></i> Thông tin check-in
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="checkin_type" class="form-label fw-bold">
                            <i class="fas fa-clipboard-list text-success me-1"></i> Loại check-in <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('checkin_type') ? 'is-invalid' : '' ?>" id="checkin_type" name="checkin_type" required>
                            <option value="manual" <?= $checkin_type == 'manual' ? 'selected' : '' ?>>Thủ công</option>
                            <option value="face_id" <?= $checkin_type == 'face_id' ? 'selected' : '' ?>>Nhận diện khuôn mặt</option>
                            <option value="qr_code" <?= $checkin_type == 'qr_code' ? 'selected' : '' ?>>Mã QR</option>
                            <option value="online" <?= $checkin_type == 'online' ? 'selected' : '' ?>>Trực tuyến</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('checkin_type')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('checkin_type') ?>
                            </div>
                        <?php else: ?>
                            <div class="form-text">Phương thức check-in được sử dụng</div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="hinh_thuc_tham_gia" class="form-label fw-bold">
                            <i class="fas fa-users text-success me-1"></i> Hình thức tham gia <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('hinh_thuc_tham_gia') ? 'is-invalid' : '' ?>" id="hinh_thuc_tham_gia" name="hinh_thuc_tham_gia" required>
                            <option value="offline" <?= $hinh_thuc_tham_gia == 'offline' ? 'selected' : '' ?>>Trực tiếp</option>
                            <option value="online" <?= $hinh_thuc_tham_gia == 'online' ? 'selected' : '' ?>>Trực tuyến</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('hinh_thuc_tham_gia')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('hinh_thuc_tham_gia') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="ma_xac_nhan" class="form-label fw-bold">
                            <i class="fas fa-barcode text-success me-1"></i> Mã xác nhận
                        </label>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ma_xac_nhan') ? 'is-invalid' : '' ?>" id="ma_xac_nhan" name="ma_xac_nhan" value="<?= $ma_xac_nhan ?>" placeholder="Nhập hoặc tạo mã tự động">
                            <button class="btn btn-outline-secondary" type="button" id="generate-code"><i class="fas fa-sync-alt"></i> Tạo mã</button>
                        </div>
                        <?php if (isset($validation) && $validation->hasError('ma_xac_nhan')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_xac_nhan') ?>
                            </div>
                        <?php else: ?>
                            <div class="form-text">Mã dùng để xác nhận check-in, để trống sẽ tự động tạo</div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">
                            <i class="fas fa-toggle-on text-success me-1"></i> Trạng thái
                        </label>
                        <select class="form-select form-select-lg" id="status" name="status">
                            <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Vô hiệu</option>
                            <option value="2" <?= $status == 2 ? 'selected' : '' ?>>Đang xử lý</option>
                        </select>
                        <div class="form-text">Trạng thái hoạt động của bản ghi check-in</div>
                    </div>

                    <!-- Face verification section - only show when checkin_type is face_id -->
                    <div class="col-md-12 face-verification-section" style="display: <?= $checkin_type == 'face_id' ? 'block' : 'none' ?>;">
                        <div class="card border-info mt-3">
                            <div class="card-header bg-light bg-gradient">
                                <h6 class="mb-0 text-info"><i class="fas fa-camera me-2"></i> Thông tin xác minh khuôn mặt</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="face_verified" name="face_verified" value="1" <?= $face_verified ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="face_verified">Đã xác minh khuôn mặt</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin bổ sung -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-edit me-2"></i> Thông tin bổ sung
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label for="ghi_chu" class="form-label fw-bold">
                            <i class="fas fa-sticky-note text-secondary me-1"></i> Ghi chú
                        </label>
                        <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="3" placeholder="Nhập ghi chú về check-in (nếu có)"><?= $ghi_chu ?></textarea>
                    </div>

                    <div class="col-md-12">
                        <label for="thong_tin_bo_sung" class="form-label fw-bold">
                            <i class="fas fa-list-alt text-secondary me-1"></i> Thông tin bổ sung (JSON)
                        </label>
                        <textarea class="form-control" id="thong_tin_bo_sung" name="thong_tin_bo_sung" rows="4" placeholder='{"dien_thoai":"0123456789","dia_chi":"Hà Nội"}'><?= $thong_tin_bo_sung ?></textarea>
                        <div class="form-text mt-2">
                            <i class="fas fa-info-circle me-1"></i> Định dạng JSON, ví dụ: {"dien_thoai":"0123456789","dia_chi":"Hà Nội"}
                            <button type="button" class="btn btn-sm btn-outline-info ms-2" id="validate-json">
                                <i class="fas fa-check-circle"></i> Kiểm tra JSON
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="col-12 mt-3">
        <div class="d-flex justify-content-between">
            <a href="<?= site_url($module_name) ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
            <div>
                <button type="reset" class="btn btn-outline-warning me-2">
                    <i class="fas fa-redo me-1"></i> Đặt lại
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Lưu thông tin
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate random code for ma_xac_nhan
    document.getElementById('generate-code').addEventListener('click', function() {
        const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let result = '';
        for (let i = 0; i < 8; i++) {
            result += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        document.getElementById('ma_xac_nhan').value = result;
        
        // Hiệu ứng khi tạo mã
        const input = document.getElementById('ma_xac_nhan');
        input.classList.add('bg-light');
        setTimeout(() => {
            input.classList.remove('bg-light');
        }, 300);
    });

    // Toggle face verification section based on checkin_type
    document.getElementById('checkin_type').addEventListener('change', function() {
        const faceSection = document.querySelector('.face-verification-section');
        if (this.value === 'face_id') {
            faceSection.style.display = 'block';
            setTimeout(() => {
                faceSection.style.opacity = 1;
            }, 50);
        } else {
            faceSection.style.opacity = 0;
            setTimeout(() => {
                faceSection.style.display = 'none';
            }, 300);
        }
    });

    // Validate JSON
    document.getElementById('validate-json').addEventListener('click', function() {
        const jsonInput = document.getElementById('thong_tin_bo_sung').value.trim();
        const jsonElement = document.getElementById('thong_tin_bo_sung');
        
        if (!jsonInput) {
            alert('Vui lòng nhập dữ liệu JSON để kiểm tra');
            return;
        }

        try {
            const parsedJson = JSON.parse(jsonInput);
            jsonElement.classList.add('is-valid');
            jsonElement.classList.remove('is-invalid');
            
            // Tạo thông báo thành công
            const successMsg = document.createElement('div');
            successMsg.classList.add('alert', 'alert-success', 'mt-2', 'p-2');
            successMsg.innerHTML = '<i class="fas fa-check-circle"></i> JSON hợp lệ!';
            
            // Thêm thông báo và xóa sau 3 giây
            jsonElement.parentNode.appendChild(successMsg);
            setTimeout(() => {
                successMsg.remove();
                jsonElement.classList.remove('is-valid');
            }, 3000);
        } catch (e) {
            jsonElement.classList.add('is-invalid');
            jsonElement.classList.remove('is-valid');
            
            // Tạo thông báo lỗi
            const errorMsg = document.createElement('div');
            errorMsg.classList.add('alert', 'alert-danger', 'mt-2', 'p-2');
            errorMsg.innerHTML = '<i class="fas fa-exclamation-circle"></i> JSON không hợp lệ: ' + e.message;
            
            // Thêm thông báo và xóa sau 5 giây
            jsonElement.parentNode.appendChild(errorMsg);
            setTimeout(() => {
                errorMsg.remove();
                jsonElement.classList.remove('is-invalid');
            }, 5000);
        }
    });
    
    // Thêm hiệu ứng cho form inputs
    const inputs = document.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.col-md-6, .col-md-12').classList.add('animate__animated', 'animate__pulse');
        });
        
        input.addEventListener('blur', function() {
            this.closest('.col-md-6, .col-md-12').classList.remove('animate__animated', 'animate__pulse');
        });
    });
});
</script> 