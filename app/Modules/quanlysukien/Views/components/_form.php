<?php
// Kiểm tra xem $data có phải là đối tượng hay không
if (is_object($data)) {
    $su_kien_id = $data->getId() ?? '';
    $ten_su_kien = $data->getTenSuKien() ?? '';
    $ma_su_kien = $data->getMaSuKien() ?? '';
    $mo_ta = $data->getMoTa() ?? '';
    $thoi_gian_bat_dau = $data->getThoiGianBatDau() ?? '';
    $thoi_gian_ket_thuc = $data->getThoiGianKetThuc() ?? '';
    $dia_diem = $data->getDiaDiem() ?? '';
    $dia_chi_cu_the = $data->getDiaChiCuThe() ?? '';
    $loai_su_kien_id = $data->getLoaiSuKienId() ?? '';
    $hinh_thuc = $data->getHinhThuc() ?? 'offline';
    $status = $data->getStatus() ?? 1;
} else {
    $su_kien_id = '';
    $ten_su_kien = '';
    $ma_su_kien = '';
    $mo_ta = '';
    $thoi_gian_bat_dau = '';
    $thoi_gian_ket_thuc = '';
    $dia_diem = '';
    $dia_chi_cu_the = '';
    $loai_su_kien_id = '';
    $hinh_thuc = 'offline';
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
                    <i class="fas fa-info-circle me-2"></i> Thông tin sự kiện
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="ten_su_kien" class="form-label fw-bold">
                            <i class="fas fa-tag text-primary me-1"></i> Tên sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ten_su_kien') ? 'is-invalid' : '' ?>" 
                               id="ten_su_kien" name="ten_su_kien" 
                               value="<?= $ten_su_kien ?>" 
                               placeholder="Nhập tên sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('ten_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="ma_su_kien" class="form-label fw-bold">
                            <i class="fas fa-code text-primary me-1"></i> Mã sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ma_su_kien') ? 'is-invalid' : '' ?>" 
                               id="ma_su_kien" name="ma_su_kien" 
                               value="<?= $ma_su_kien ?>" 
                               placeholder="Nhập mã sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('ma_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thoi_gian_bat_dau" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt text-primary me-1"></i> Thời gian bắt đầu <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('thoi_gian_bat_dau') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" 
                               value="<?= $thoi_gian_bat_dau ?>" required>
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_bat_dau')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_bat_dau') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thoi_gian_ket_thuc" class="form-label fw-bold">
                            <i class="fas fa-calendar-check text-primary me-1"></i> Thời gian kết thúc <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('thoi_gian_ket_thuc') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc" 
                               value="<?= $thoi_gian_ket_thuc ?>" required>
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_ket_thuc')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_ket_thuc') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="dia_diem" class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i> Địa điểm <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('dia_diem') ? 'is-invalid' : '' ?>" 
                               id="dia_diem" name="dia_diem" 
                               value="<?= $dia_diem ?>" 
                               placeholder="Nhập địa điểm sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('dia_diem')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dia_diem') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="dia_chi_cu_the" class="form-label fw-bold">
                            <i class="fas fa-location-arrow text-primary me-1"></i> Địa chỉ cụ thể
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('dia_chi_cu_the') ? 'is-invalid' : '' ?>" 
                               id="dia_chi_cu_the" name="dia_chi_cu_the" 
                               value="<?= $dia_chi_cu_the ?>" 
                               placeholder="Nhập địa chỉ cụ thể">
                        <?php if (isset($validation) && $validation->hasError('dia_chi_cu_the')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dia_chi_cu_the') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="loai_su_kien_id" class="form-label fw-bold">
                            <i class="fas fa-list-alt text-primary me-1"></i> Loại sự kiện <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('loai_su_kien_id') ? 'is-invalid' : '' ?>" 
                                id="loai_su_kien_id" name="loai_su_kien_id" required>
                            <option value="" disabled selected>-- Chọn loại sự kiện --</option>
                            <?php 
                            // Sử dụng dịch vụ model để lấy danh sách loại sự kiện
                            $loaiSuKienModel = new \App\Modules\quanlyloaisukien\Models\LoaiSuKienModel();
                            $danhSachLoaiSuKien = $loaiSuKienModel->findAll();
                            
                            foreach ($danhSachLoaiSuKien as $loai) : 
                                $selected = ($loai_su_kien_id == $loai->getId()) ? 'selected' : '';
                            ?>
                                <option value="<?= $loai->getId() ?>" <?= $selected ?>><?= esc($loai->getTenLoaiSuKien()) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('loai_su_kien_id')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('loai_su_kien_id') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="hinh_thuc" class="form-label fw-bold">
                            <i class="fas fa-video text-primary me-1"></i> Hình thức <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('hinh_thuc') ? 'is-invalid' : '' ?>" 
                                id="hinh_thuc" name="hinh_thuc" required>
                            <option value="offline" <?= $hinh_thuc == 'offline' ? 'selected' : '' ?>>Offline</option>
                            <option value="online" <?= $hinh_thuc == 'online' ? 'selected' : '' ?>>Online</option>
                            <option value="hybrid" <?= $hinh_thuc == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('hinh_thuc')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('hinh_thuc') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-12">
                        <label for="mo_ta" class="form-label fw-bold">
                            <i class="fas fa-align-left text-primary me-1"></i> Mô tả
                        </label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('mo_ta') ? 'is-invalid' : '' ?>" 
                                  id="mo_ta" name="mo_ta" rows="3" 
                                  placeholder="Nhập mô tả sự kiện"><?= $mo_ta ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('mo_ta')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mo_ta') ?>
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
    
    // Kiểm tra thời gian kết thúc phải sau thời gian bắt đầu
    const thoiGianBatDau = document.getElementById('thoi_gian_bat_dau');
    const thoiGianKetThuc = document.getElementById('thoi_gian_ket_thuc');
    
    thoiGianKetThuc.addEventListener('change', function() {
        if (thoiGianBatDau.value && thoiGianKetThuc.value) {
            if (new Date(thoiGianKetThuc.value) <= new Date(thoiGianBatDau.value)) {
                thoiGianKetThuc.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu');
            } else {
                thoiGianKetThuc.setCustomValidity('');
            }
        }
    });
    
    thoiGianBatDau.addEventListener('change', function() {
        if (thoiGianBatDau.value && thoiGianKetThuc.value) {
            if (new Date(thoiGianKetThuc.value) <= new Date(thoiGianBatDau.value)) {
                thoiGianKetThuc.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu');
            } else {
                thoiGianKetThuc.setCustomValidity('');
            }
        }
    });
});
</script> 