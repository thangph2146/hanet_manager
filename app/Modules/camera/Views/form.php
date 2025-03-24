<?php
/**
 * Form component for creating and updating camera
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var Camera $camera Camera entity data for editing (optional)
 */

// Set default values if editing
$ten_camera = isset($camera) ? $camera->ten_camera : '';
$ma_camera = isset($camera) ? $camera->ma_camera : '';
$ip_camera = isset($camera) ? $camera->ip_camera : '';
$port = isset($camera) ? $camera->port : '';
$username = isset($camera) ? $camera->username : '';
$password = isset($camera) ? $camera->password : '';
$status = isset($camera) ? (string)$camera->status : '1';
$bin = isset($camera) ? (string)$camera->bin : '0';
$id = isset($camera) ? $camera->camera_id : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('camera/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$formTitle = isset($is_new) && $is_new ? 'Thêm mới camera' : 'Cập nhật camera';
$isUpdate = isset($camera) && isset($camera->camera_id);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="cameraForm" class="needs-validation" novalidate>
    <?php if ($isUpdate): ?>
        <input type="hidden" name="camera_id" value="<?= $id ?>">
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
                <!-- ma_camera -->
                <div class="col-md-6">
                    <label for="ma_camera" class="form-label fw-semibold">
                        Mã camera <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_camera') ? 'is-invalid' : '' ?>" 
                                id="ma_camera" name="ma_camera" 
                                value="<?= old('ma_camera', $ma_camera) ?>" 
                                placeholder="Nhập mã camera"
                                required maxlength="20">
                        <?php if (isset($validation) && $validation->hasError('ma_camera')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_camera') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập mã camera</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã camera phải là duy nhất trong hệ thống, tối đa 20 ký tự
                    </div>
                </div>

                <!-- ten_camera -->
                <div class="col-md-6">
                    <label for="ten_camera" class="form-label fw-semibold">
                        Tên camera <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-camera'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_camera') ? 'is-invalid' : '' ?>" 
                                id="ten_camera" name="ten_camera" 
                                value="<?= old('ten_camera', $ten_camera) ?>" 
                                placeholder="Nhập tên camera"
                                required minlength="3" maxlength="255" autocomplete="off"
                                oninput="this.value = this.value.trim()">
                        <?php if (isset($validation) && $validation->hasError('ten_camera')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_camera') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên camera (tối thiểu 3 ký tự)</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên camera phải có ít nhất 3 ký tự và không trùng với bất kỳ camera nào trong hệ thống
                    </div>
                </div>

                <!-- ip_camera -->
                <div class="col-md-6">
                    <label for="ip_camera" class="form-label fw-semibold">
                        Địa chỉ IP <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-globe'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ip_camera') ? 'is-invalid' : '' ?>" 
                                id="ip_camera" name="ip_camera" 
                                value="<?= old('ip_camera', $ip_camera) ?>" 
                                placeholder="Nhập địa chỉ IP camera"
                                required pattern="^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$|^[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?(\.[a-zA-Z0-9]([a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?)*$">
                        <?php if (isset($validation) && $validation->hasError('ip_camera')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ip_camera') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập địa chỉ IP hoặc tên miền hợp lệ</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Địa chỉ IP hoặc tên miền của camera
                    </div>
                </div>

                <!-- port -->
                <div class="col-md-6">
                    <label for="port" class="form-label fw-semibold">
                        Port <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-dialpad'></i></span>
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('port') ? 'is-invalid' : '' ?>" 
                                id="port" name="port" 
                                value="<?= old('port', $port) ?>" 
                                placeholder="Nhập port camera"
                                required min="1" max="65535">
                        <?php if (isset($validation) && $validation->hasError('port')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('port') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập port hợp lệ (1-65535)</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Cổng kết nối camera (1-65535)
                    </div>
                </div>

                <!-- username -->
                <div class="col-md-6">
                    <label for="username" class="form-label fw-semibold">
                        Tên đăng nhập <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('username') ? 'is-invalid' : '' ?>" 
                                id="username" name="username" 
                                value="<?= old('username', $username) ?>" 
                                placeholder="Nhập tên đăng nhập"
                                required>
                        <?php if (isset($validation) && $validation->hasError('username')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('username') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên đăng nhập</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên đăng nhập để truy cập camera
                    </div>
                </div>

                <!-- password -->
                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold">
                        Mật khẩu <span class="text-danger"><?= $isUpdate ? '' : '*' ?></span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-lock-alt'></i></span>
                        <input type="password" class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                                id="password" name="password" 
                                value="<?= old('password', '') ?>" 
                                placeholder="<?= $isUpdate ? 'Để trống nếu không đổi mật khẩu' : 'Nhập mật khẩu' ?>"
                                <?= $isUpdate ? '' : 'required' ?>>
                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                            <i class='bx bx-hide'></i>
                        </button>
                        <?php if (isset($validation) && $validation->hasError('password')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('password') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập mật khẩu</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        <?= $isUpdate ? 'Để trống nếu không muốn thay đổi mật khẩu hiện tại' : 'Mật khẩu để truy cập camera' ?>
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
                        Camera không hoạt động sẽ không hiển thị trong các danh sách chọn
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
                    <a href="<?= site_url('camera') ?>" class="btn btn-light">
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
        
        // Xử lý nút hiện/ẩn mật khẩu
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.querySelector('i').classList.replace('bx-hide', 'bx-show');
                } else {
                    passwordInput.type = 'password';
                    this.querySelector('i').classList.replace('bx-show', 'bx-hide');
                }
            });
        });
        
        // Tự động focus vào trường đầu tiên
        document.getElementById('ten_camera').focus();
    });
</script> 