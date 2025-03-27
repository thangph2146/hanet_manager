<?php
/**
 * Form component for creating and updating camera
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var Camera $data Camera entity data for editing (optional)
 */

// Set default values if editing
$ten_camera = isset($data) ? $data->getTenCamera() : '';
$ma_camera = isset($data) ? $data->getMaCamera() : '';
$ip_camera = isset($data) ? $data->getIpCamera() : '';
$port = isset($data) ? $data->getPort() : '';
$status = isset($data) ? (string)$data->status : '1';
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$ten_camera = old('ten_camera', $ten_camera);
$ma_camera = old('ma_camera', $ma_camera);
$ip_camera = old('ip_camera', $ip_camera);
$port = old('port', $port);
$status = old('status', $status);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="camera_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="camera_id" value="0">
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
                <i class='bx bx-camera text-primary me-2'></i>
                Thông tin camera
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_camera -->
                <div class="col-md-12">
                    <label for="ten_camera" class="form-label fw-semibold">
                        Tên camera <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-camera'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_camera') ? 'is-invalid' : '' ?>" 
                            id="ten_camera" name="ten_camera" 
                            value="<?= esc($ten_camera) ?>" 
                            placeholder="Nhập tên camera"
                            required maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('ten_camera')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_camera') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên camera</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên camera là duy nhất, tối đa 255 ký tự
                    </div>
                </div>

                <!-- ma_camera -->
                <div class="col-md-12">
                    <label for="ma_camera" class="form-label fw-semibold">
                        Mã camera
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('ma_camera') ? 'is-invalid' : '' ?>" 
                               id="ma_camera" name="ma_camera"
                               value="<?= esc($ma_camera) ?>"
                               placeholder="Nhập mã camera"
                               maxlength="20">
                        <?php if (isset($validation) && $validation->hasError('ma_camera')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_camera') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã camera tối đa 20 ký tự (không bắt buộc)
                    </div>
                </div>

                <!-- ip_camera -->
                <div class="col-md-6">
                    <label for="ip_camera" class="form-label fw-semibold">
                        IP camera
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-network-chart'></i></span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('ip_camera') ? 'is-invalid' : '' ?>" 
                               id="ip_camera" name="ip_camera"
                               value="<?= esc($ip_camera) ?>"
                               placeholder="Nhập IP camera"
                               maxlength="100">
                        <?php if (isset($validation) && $validation->hasError('ip_camera')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ip_camera') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        IP camera tối đa 100 ký tự (không bắt buộc)
                    </div>
                </div>

                <!-- port -->
                <div class="col-md-6">
                    <label for="port" class="form-label fw-semibold">
                        Port
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-transfer'></i></span>
                        <input type="number" 
                               class="form-control <?= isset($validation) && $validation->hasError('port') ? 'is-invalid' : '' ?>" 
                               id="port" name="port"
                               value="<?= esc($port) ?>"
                               placeholder="Nhập port"
                               min="1" max="65535">
                        <?php if (isset($validation) && $validation->hasError('port')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('port') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Port phải là số nguyên từ 1-65535 (không bắt buộc)
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
                        Trạng thái camera trong hệ thống
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
        document.getElementById('ten_camera').focus();
    });
</script> 