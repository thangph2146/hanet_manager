<?php
/**
 * Form component for creating and updating loại người dùng
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var LoaiNguoiDung $loaiNguoiDung LoaiNguoiDung entity data for editing (optional)
 */

// Set default values if editing
$id = isset($data) ? $data->loai_nguoi_dung_id : '';
$ten_loai = isset($data) ? $data->ten_loai : '';
$mo_ta = isset($data) ? $data->mo_ta : '';
$status = isset($data) ? (string)$data->status : '1';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->loai_nguoi_dung_id > 0;
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="loai_nguoi_dung_id" value="<?= $id ?>">
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
                Thông tin loại người dùng
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_loai -->
                <div class="col-md-6">
                    <label for="ten_loai" class="form-label fw-semibold">
                        Tên loại người dùng <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user-pin'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_loai') ? 'is-invalid' : '' ?>" 
                            id="ten_loai" name="ten_loai" 
                            value="<?= old('ten_loai', $ten_loai) ?>" 
                            placeholder="Nhập tên loại người dùng"
                            required>
                        <?php if (isset($validation) && $validation->hasError('ten_loai')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_loai') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên loại người dùng</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên loại người dùng trong hệ thống
                    </div>
                </div>

                <!-- mo_ta -->
                <div class="col-md-6">
                    <label for="mo_ta" class="form-label fw-semibold">
                        Mô tả
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-text'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('mo_ta') ? 'is-invalid' : '' ?>" 
                            id="mo_ta" name="mo_ta" 
                            value="<?= old('mo_ta', $mo_ta) ?>" 
                            placeholder="Nhập mô tả">
                        <?php if (isset($validation) && $validation->hasError('mo_ta')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mo_ta') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mô tả chi tiết về loại người dùng
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
        document.getElementById('ten_loai').focus();
    });
</script> 