<?php
// Kiểm tra xem $data có phải là đối tượng hay không
if (is_object($data)) {
    $loai_su_kien_id = $data->getLoaiSuKienId() ?? '';
    $ten_loai_su_kien = $data->getTenLoaiSuKien() ?? '';
    $ma_loai_su_kien = $data->getMaLoaiSuKien() ?? '';
    $mo_ta = $data->getMoTa() ?? '';
    $thu_tu = $data->getThuTu() ?? 0;
    $status = $data->getStatus() ?? 1;
} else {
    $loai_su_kien_id = '';
    $ten_loai_su_kien = '';
    $ma_loai_su_kien = '';
    $mo_ta = '';
    $thu_tu = 0;
    $status = 1;
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
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-info-circle me-2"></i> Thông tin loại sự kiện
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="ten_loai_su_kien" class="form-label fw-bold">
                            <i class="fas fa-tag text-primary me-1"></i> Tên loại sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ten_loai_su_kien') ? 'is-invalid' : '' ?>" 
                               id="ten_loai_su_kien" name="ten_loai_su_kien" 
                               value="<?= $ten_loai_su_kien ?>" 
                               placeholder="Nhập tên loại sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('ten_loai_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_loai_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="ma_loai_su_kien" class="form-label fw-bold">
                            <i class="fas fa-code text-primary me-1"></i> Mã loại sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ma_loai_su_kien') ? 'is-invalid' : '' ?>" 
                               id="ma_loai_su_kien" name="ma_loai_su_kien" 
                               value="<?= $ma_loai_su_kien ?>" 
                               placeholder="Nhập mã loại sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('ma_loai_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_loai_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-12">
                        <label for="mo_ta" class="form-label fw-bold">
                            <i class="fas fa-align-left text-primary me-1"></i> Mô tả
                        </label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('mo_ta') ? 'is-invalid' : '' ?>" 
                                  id="mo_ta" name="mo_ta" rows="3" 
                                  placeholder="Nhập mô tả loại sự kiện"><?= $mo_ta ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('mo_ta')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mo_ta') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thu_tu" class="form-label fw-bold">
                            <i class="fas fa-sort-numeric-down text-primary me-1"></i> Thứ tự
                        </label>
                        <input type="number" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('thu_tu') ? 'is-invalid' : '' ?>" 
                               id="thu_tu" name="thu_tu" 
                               value="<?= $thu_tu ?>" min="0">
                        <?php if (isset($validation) && $validation->hasError('thu_tu')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thu_tu') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">
                            <i class="fas fa-toggle-on text-primary me-1"></i> Trạng thái <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                                id="status" name="status" required>
                            <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Vô hiệu</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
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