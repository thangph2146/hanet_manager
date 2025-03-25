<?php
/**
 * Form component for creating and updating template
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var Template $template Template entity data for editing (optional)
 */

// Set default values if editing
$ten_template = isset($template) ? $template->ten_template : '';
$ma_template = isset($template) ? $template->ma_template : '';
$status = isset($template) ? (string)$template->status : '1';
$bin = isset($template) ? (string)$template->bin : '0';
$id = isset($template) ? $template->template_id : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('template/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$formTitle = isset($is_new) && $is_new ? 'Thêm mới template' : 'Cập nhật template';
$isUpdate = isset($template) && isset($template->template_id);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="templateForm" class="needs-validation" novalidate>
    <?php if ($isUpdate): ?>
        <input type="hidden" name="template_id" value="<?= $id ?>">
    <?php endif; ?>
    
    <!-- Trường bin ẩn -->
    <input type="hidden" name="bin" value="<?= $bin ?>">

    <h4 class="mb-3"><?= $formTitle ?></h4>

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
                <!-- ma_template -->
                <div class="col-md-6">
                    <label for="ma_template" class="form-label fw-semibold">
                        Mã template <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_template') ? 'is-invalid' : '' ?>" 
                                id="ma_template" name="ma_template" 
                                value="<?= old('ma_template', $ma_template) ?>" 
                                placeholder="Nhập mã template"
                                required maxlength="20">
                        <?php if (isset($validation) && $validation->hasError('ma_template')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_template') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập mã template</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã template phải là duy nhất trong hệ thống, tối đa 20 ký tự
                    </div>
                </div>

                <!-- ten_template -->
                <div class="col-md-6">
                    <label for="ten_template" class="form-label fw-semibold">
                        Tên template <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-file'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_template') ? 'is-invalid' : '' ?>" 
                                id="ten_template" name="ten_template" 
                                value="<?= old('ten_template', $ten_template) ?>" 
                                placeholder="Nhập tên template"
                                required minlength="3" maxlength="255" autocomplete="off"
                                oninput="this.value = this.value.trim()">
                        <?php if (isset($validation) && $validation->hasError('ten_template')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_template') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên template (tối thiểu 3 ký tự)</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên template phải có ít nhất 3 ký tự và không trùng với bất kỳ template nào trong hệ thống
                    </div>
                </div>

                <!-- Status -->
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">Trạng thái</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-toggle-left'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                               id="status" name="status">
                            <option value="1" <?= old('status', $status) == '1' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= old('status', $status) == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Template không hoạt động sẽ không hiển thị trong các danh sách chọn
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
                    <a href="<?= site_url('template') ?>" class="btn btn-light">
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
        document.getElementById('ten_template').focus();
    });
</script> 