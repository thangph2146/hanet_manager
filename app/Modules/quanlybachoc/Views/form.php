<?php
/**
 * Form component for creating and updating bậc học
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var Bachoc $data Bachoc entity data for editing (optional)
 */

// Set default values if editing
$ten_bac_hoc = isset($data) ? $data->ten_bac_hoc : '';
$ma_bac_hoc = isset($data) ? $data->ma_bac_hoc : '';
$status = isset($data) ? (string)$data->status : '1';
$id = isset($data) ? $data->bac_hoc_id : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->bac_hoc_id > 0;

// Lấy giá trị từ old() nếu có
$ten_bac_hoc = old('ten_bac_hoc', $ten_bac_hoc);
$ma_bac_hoc = old('ma_bac_hoc', $ma_bac_hoc);
$status = old('status', $status);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="bac_hoc_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="bac_hoc_id" value="0">
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
                <i class='bx bx-book-open text-primary me-2'></i>
                Thông tin bậc học
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_bac_hoc -->
                <div class="col-md-12">
                    <label for="ten_bac_hoc" class="form-label fw-semibold">
                        Tên bậc học <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-book'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_bac_hoc') ? 'is-invalid' : '' ?>" 
                            id="ten_bac_hoc" name="ten_bac_hoc" 
                            value="<?= esc($ten_bac_hoc) ?>" 
                            placeholder="Nhập tên bậc học"
                            required maxlength="100">
                        <?php if (isset($validation) && $validation->hasError('ten_bac_hoc')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_bac_hoc') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên bậc học</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên bậc học là duy nhất, tối đa 100 ký tự
                    </div>
                </div>

                <!-- ma_bac_hoc -->
                <div class="col-md-12">
                    <label for="ma_bac_hoc" class="form-label fw-semibold">
                        Mã bậc học
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('ma_bac_hoc') ? 'is-invalid' : '' ?>" 
                               id="ma_bac_hoc" name="ma_bac_hoc"
                               value="<?= esc($ma_bac_hoc) ?>"
                               placeholder="Nhập mã bậc học"
                               maxlength="20">
                        <?php if (isset($validation) && $validation->hasError('ma_bac_hoc')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_bac_hoc') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã bậc học tối đa 20 ký tự (không bắt buộc)
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-12">
                    <label for="status" class="form-label fw-semibold">
                        Trạng thái <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-toggle-left'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                               id="status" name="status" required>
                            <option value="">-- Chọn trạng thái --</option>
                            <option value="1" <?= $status == '1' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= $status == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng chọn trạng thái</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Trạng thái bậc học trong hệ thống
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
        document.getElementById('ten_bac_hoc').focus();
    });
</script> 