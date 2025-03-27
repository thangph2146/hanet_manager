<?php
/**
 * Form component for creating and updating màn hình
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var ManHinh $data ManHinh entity data for editing (optional)
 * @var array $cameraList Danh sách camera
 * @var array $templateList Danh sách template
 */

// Set default values if editing
$ten_man_hinh = isset($data) ? $data->getTenManHinh() : '';
$ma_man_hinh = isset($data) ? $data->getMaManHinh() : '';
$camera_id = isset($data) ? $data->getCameraId() : '';
$template_id = isset($data) ? $data->getTemplateId() : '';
$status = isset($data) ? (string)$data->isActive() : '1';
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$ten_man_hinh = old('ten_man_hinh', $ten_man_hinh);
$ma_man_hinh = old('ma_man_hinh', $ma_man_hinh);
$camera_id = old('camera_id', $camera_id);
$template_id = old('template_id', $template_id);
$status = old('status', $status);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="man_hinh_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="man_hinh_id" value="0">
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
                <i class='bx bx-desktop text-primary me-2'></i>
                Thông tin màn hình
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_man_hinh -->
                <div class="col-md-6">
                    <label for="ten_man_hinh" class="form-label fw-semibold">
                        Tên màn hình <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-desktop'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_man_hinh') ? 'is-invalid' : '' ?>" 
                            id="ten_man_hinh" name="ten_man_hinh" 
                            value="<?= esc($ten_man_hinh) ?>" 
                            placeholder="Nhập tên màn hình"
                            required maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('ten_man_hinh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_man_hinh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên màn hình</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên màn hình tối đa 255 ký tự
                    </div>
                </div>

                <!-- ma_man_hinh -->
                <div class="col-md-6">
                    <label for="ma_man_hinh" class="form-label fw-semibold">
                        Mã màn hình
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_man_hinh') ? 'is-invalid' : '' ?>" 
                            id="ma_man_hinh" name="ma_man_hinh" 
                            value="<?= esc($ma_man_hinh) ?>" 
                            placeholder="Nhập mã màn hình"
                            maxlength="20">
                        <?php if (isset($validation) && $validation->hasError('ma_man_hinh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_man_hinh') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã màn hình tối đa 20 ký tự
                    </div>
                </div>
                
                <!-- camera_id -->
                <div class="col-md-6">
                    <label for="camera_id" class="form-label fw-semibold">
                        Camera
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-camera'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('camera_id') ? 'is-invalid' : '' ?>" 
                               id="camera_id" name="camera_id">
                            <option value="">-- Chọn camera --</option>
                            <?php if (isset($cameraList) && is_array($cameraList)): ?>
                                <?php foreach ($cameraList as $camera): ?>
                                    <option value="<?= $camera->getId() ?>" <?= $camera_id == $camera->getId() ? 'selected' : '' ?>>
                                        <?= esc($camera->getTenCamera()) ?>
                                        <?php if (!empty($camera->getMaCamera())): ?>
                                            (<?= esc($camera->getMaCamera()) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('camera_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('camera_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Camera sử dụng cho màn hình
                    </div>
                </div>
                
                <!-- template_id -->
                <div class="col-md-6">
                    <label for="template_id" class="form-label fw-semibold">
                        Template
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-layout'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('template_id') ? 'is-invalid' : '' ?>" 
                               id="template_id" name="template_id">
                            <option value="">-- Chọn template --</option>
                            <?php if (isset($templateList) && is_array($templateList)): ?>
                                <?php foreach ($templateList as $template): ?>
                                    <option value="<?= $template->getId() ?>" <?= $template_id == $template->getId() ? 'selected' : '' ?>>
                                        <?= esc($template->getTenTemplate()) ?>
                                        <?php if (!empty($template->getMaTemplate())): ?>
                                            (<?= esc($template->getMaTemplate()) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('template_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('template_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Template sử dụng cho màn hình
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
                        Trạng thái màn hình trong hệ thống
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
        document.getElementById('ten_man_hinh').focus();
    });
</script> 