<?php
/**
 * Form component for creating and updating loai su kien
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var array $data Loaisukien entity data for editing (optional)
 */

// Set default values if editing
$ten_loai_su_kien = isset($data) ? $data->ten_loai_su_kien : '';
$ma_loai_su_kien = isset($data) ? $data->ma_loai_su_kien : '';
$status = isset($data) ? (string)$data->status : '1';
$id = isset($data) ? $data->loai_su_kien_id : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('loaisukien/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$formTitle = isset($is_new) && $is_new ? 'Thêm mới loại sự kiện' : 'Cập nhật loại sự kiện';
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="loaisukienForm" class="needs-validation" novalidate>
    <?php if (isset($data->loai_su_kien_id)): ?>
        <input type="hidden" name="loai_su_kien_id" value="<?= $id ?>">
    <?php endif; ?>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-start border-danger border-4" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class='bx bx-error-circle fs-3'></i>
                </div>
                <div>
                    <strong>Lỗi!</strong> <?= session('error') ?>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
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

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class='bx bx-info-circle text-primary me-2'></i>
                Thông tin cơ bản
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ma_loai_su_kien -->
                <div class="col-md-6">
                    <label for="ma_loai_su_kien" class="form-label fw-semibold">
                        Mã loại sự kiện <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" class="form-control <?= session('errors.ma_loai_su_kien') ? 'is-invalid' : '' ?>" 
                                id="ma_loai_su_kien" name="ma_loai_su_kien" 
                                value="<?= old('ma_loai_su_kien', $ma_loai_su_kien) ?>" 
                                placeholder="Nhập mã loại sự kiện"
                                required maxlength="20">
                        <?php if (session('errors.ma_loai_su_kien')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.ma_loai_su_kien') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập mã loại sự kiện</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã loại sự kiện phải là duy nhất trong hệ thống, tối đa 20 ký tự
                    </div>
                </div>

                <!-- ten_loai_su_kien -->
                <div class="col-md-6">
                    <label for="ten_loai_su_kien" class="form-label fw-semibold">
                        Tên loại sự kiện <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-book-alt'></i></span>
                        <input type="text" class="form-control <?= session('errors.ten_loai_su_kien') ? 'is-invalid' : '' ?>" 
                                id="ten_loai_su_kien" name="ten_loai_su_kien" 
                                value="<?= old('ten_loai_su_kien', $ten_loai_su_kien) ?>" 
                                placeholder="Nhập tên loại sự kiện"
                                required minlength="3" maxlength="100">
                        <?php if (session('errors.ten_loai_su_kien')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.ten_loai_su_kien') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên loại sự kiện (tối thiểu 3 ký tự)</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên loại sự kiện phải có ít nhất 3 ký tự và không trùng với các loại sự kiện khác
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">Trạng thái</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-toggle-left'></i></span>
                        <select class="form-select" id="status" name="status">
                            <option value="1" <?= old('status', $status) == '1' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= old('status', $status) == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Loại sự kiện không hoạt động sẽ không hiển thị trong các danh sách chọn
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
                    <a href="<?= site_url('loaisukien') ?>" class="btn btn-light">
                        <i class='bx bx-arrow-back me-1'></i> Quay lại
                    </a>
                    <button class="btn btn-primary px-4" type="submit">
                        <i class='bx bx-save me-1'></i>
                        <?= isset($data->loai_su_kien_id) && $data->loai_su_kien_id ? 'Cập nhật' : 'Thêm mới' ?>
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
        document.getElementById('ma_loai_su_kien').focus();
    });
</script> 