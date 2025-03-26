<?php
/**
 * Form component for creating and updating phòng khoa
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var PhongKhoa $data PhongKhoa entity data for editing (optional)
 */

// Set default values if editing
$id = isset($data) ? $data->phong_khoa_id : '';
$ma_phong_khoa = isset($data) ? $data->ma_phong_khoa : '';
$ten_phong_khoa = isset($data) ? $data->ten_phong_khoa : '';
$ghi_chu = isset($data) ? $data->ghi_chu : '';
$status = isset($data) ? (string)$data->status : '1';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->phong_khoa_id > 0;
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="phong_khoa_id" value="<?= $id ?>">
    <?php endif; ?>

    <!-- Hiển thị lỗi validation -->
    <?php if (isset($errors) && (is_array($errors) || is_object($errors)) && count($errors) > 0): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-start border-danger border-4" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class='bx bx-error-circle fs-3'></i>
                </div>
                <div>
                    <strong>Lỗi nhập liệu:</strong>
                    <ul class="mb-0 ps-3 mt-1">
                        <?php foreach ($errors as $field => $error): ?>
                            <li><?= is_array($error) ? implode(', ', $error) : $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Hiển thị thông báo thành công -->
    <?php if (session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-start border-success border-4" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class='bx bx-check-circle fs-3'></i>
                </div>
                <div>
                    <strong>Thành công!</strong> <?= session('success') ?>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class='bx bx-info-circle text-primary me-2'></i>
                Thông tin phòng khoa
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ma_phong_khoa -->
                <div class="col-md-6">
                    <label for="ma_phong_khoa" class="form-label fw-semibold">
                        Mã phòng khoa <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-code-alt'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_phong_khoa') ? 'is-invalid' : '' ?>" 
                            id="ma_phong_khoa" name="ma_phong_khoa" 
                            value="<?= old('ma_phong_khoa', $ma_phong_khoa) ?>" 
                            placeholder="Nhập mã phòng khoa"
                            required>
                        <?php if (isset($validation) && $validation->hasError('ma_phong_khoa')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_phong_khoa') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập mã phòng khoa</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã phòng khoa duy nhất trong hệ thống
                    </div>
                </div>

                <!-- ten_phong_khoa -->
                <div class="col-md-6">
                    <label for="ten_phong_khoa" class="form-label fw-semibold">
                        Tên phòng khoa <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-buildings'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_phong_khoa') ? 'is-invalid' : '' ?>" 
                            id="ten_phong_khoa" name="ten_phong_khoa" 
                            value="<?= old('ten_phong_khoa', $ten_phong_khoa) ?>" 
                            placeholder="Nhập tên phòng khoa"
                            required>
                        <?php if (isset($validation) && $validation->hasError('ten_phong_khoa')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_phong_khoa') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên phòng khoa</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên phòng khoa trong hệ thống
                    </div>
                </div>

                <!-- ghi_chu -->
                <div class="col-md-6">
                    <label for="ghi_chu" class="form-label fw-semibold">
                        Ghi chú
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-text'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('ghi_chu') ? 'is-invalid' : '' ?>" 
                            id="ghi_chu" name="ghi_chu" 
                            placeholder="Nhập ghi chú" rows="3"><?= old('ghi_chu', $ghi_chu) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('ghi_chu')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ghi_chu') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Ghi chú chi tiết về phòng khoa
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">
                        Trạng thái <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-toggle-left'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                               id="status" name="status" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="1" <?= old('status', $status) == '1' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= old('status', $status) == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng chọn trạng thái</div>
                        <?php endif; ?>
                    </div>
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
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
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
        
        // Tự động focus vào trường đầu tiên
        document.getElementById('ma_phong_khoa').focus();
    });
</script> 