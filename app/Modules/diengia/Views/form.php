<?php
/**
 * Form component for creating and updating diễn giả
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var DienGia $diengia Diễn giả entity data for editing (optional)
 */

// Set default values if editing
$dien_gia_id = isset($diengia) ? $diengia->getId() : '';
$ten_dien_gia = isset($diengia) ? $diengia->getTenDienGia() : '';
$chuc_danh = isset($diengia) ? $diengia->getChucDanh() : '';
$to_chuc = isset($diengia) ? $diengia->getToChuc() : '';
$gioi_thieu = isset($diengia) ? $diengia->getGioiThieu() : '';
$avatar = isset($diengia) ? $diengia->getAvatar() : '';
$avatarUrl = isset($diengia) ? $diengia->getAvatarUrl() : '';
$thu_tu = isset($diengia) ? $diengia->getThuTu() : 0;
$isDeleted = isset($diengia) ? $diengia->isDeleted() : false;

// Set default values for form action and method
$action = isset($action) ? $action : site_url('diengia/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode và trạng thái cập nhật
$formTitle = isset($is_new) && $is_new ? 'Thêm mới diễn giả' : 'Cập nhật diễn giả';
$isUpdate = isset($diengia) && $dien_gia_id > 0;

// Thêm debug info để kiểm tra
$debug_info = [
    'isUpdate' => $isUpdate,
    'id' => $dien_gia_id,
    'is_new' => $is_new ?? false,
    'form_action' => $action
];

// Ghi log debug info
log_message('debug', 'Form variables: ' . json_encode($debug_info));
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-diengia" class="needs-validation" novalidate enctype="multipart/form-data">
    <?php if ($isUpdate && $dien_gia_id > 0): ?>
        <input type="hidden" name="dien_gia_id" value="<?= $dien_gia_id ?>">
    <?php endif; ?>
    
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
                <!-- ten_dien_gia -->
                <div class="col-md-6">
                    <label for="ten_dien_gia" class="form-label fw-semibold">
                        Tên diễn giả <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_dien_gia') ? 'is-invalid' : '' ?>" 
                                id="ten_dien_gia" name="ten_dien_gia" 
                                value="<?= old('ten_dien_gia', $ten_dien_gia) ?>" 
                                placeholder="Nhập tên diễn giả"
                                required minlength="3" maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('ten_dien_gia')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_dien_gia') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên diễn giả (tối thiểu 3 ký tự)</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên diễn giả phải có ít nhất 3 ký tự và không trùng với bất kỳ diễn giả nào trong hệ thống
                    </div>
                </div>

                <!-- thu_tu -->
                <div class="col-md-6">
                    <label for="thu_tu" class="form-label fw-semibold">
                        Thứ tự hiển thị
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-sort-up'></i></span>
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('thu_tu') ? 'is-invalid' : '' ?>" 
                                id="thu_tu" name="thu_tu" 
                                value="<?= old('thu_tu', $thu_tu) ?>" 
                                placeholder="Nhập thứ tự hiển thị"
                                min="0">
                        <?php if (isset($validation) && $validation->hasError('thu_tu')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thu_tu') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Thứ tự phải là số nguyên không âm</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Thứ tự càng nhỏ thì được hiển thị càng đầu tiên
                    </div>
                </div>

                <!-- chuc_danh -->
                <div class="col-md-6">
                    <label for="chuc_danh" class="form-label fw-semibold">
                        Chức danh
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-briefcase'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('chuc_danh') ? 'is-invalid' : '' ?>" 
                                id="chuc_danh" name="chuc_danh" 
                                value="<?= old('chuc_danh', $chuc_danh) ?>" 
                                placeholder="Nhập chức danh của diễn giả"
                                maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('chuc_danh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('chuc_danh') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Nhập chức danh hiện tại của diễn giả
                    </div>
                </div>

                <!-- to_chuc -->
                <div class="col-md-6">
                    <label for="to_chuc" class="form-label fw-semibold">
                        Tổ chức
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-building'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('to_chuc') ? 'is-invalid' : '' ?>" 
                                id="to_chuc" name="to_chuc" 
                                value="<?= old('to_chuc', $to_chuc) ?>" 
                                placeholder="Nhập tổ chức của diễn giả"
                                maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('to_chuc')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('to_chuc') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Nhập tên tổ chức nơi diễn giả công tác
                    </div>
                </div>

                <!-- gioi_thieu -->
                <div class="col-md-12">
                    <label for="gioi_thieu" class="form-label fw-semibold">
                        Giới thiệu
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-text'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('gioi_thieu') ? 'is-invalid' : '' ?>" 
                                id="gioi_thieu" name="gioi_thieu" 
                                placeholder="Nhập thông tin giới thiệu về diễn giả"
                                rows="4"><?= old('gioi_thieu', $gioi_thieu) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('gioi_thieu')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('gioi_thieu') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Thông tin giới thiệu chi tiết về diễn giả
                    </div>
                </div>

                <!-- avatar -->
                <div class="col-md-12">
                    <label for="avatar" class="form-label fw-semibold">
                        Ảnh đại diện
                    </label>
                    <div class="row g-3">
                        <div class="col-md-9">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class='bx bx-image'></i></span>
                                <input type="file" class="form-control <?= isset($validation) && $validation->hasError('avatar') ? 'is-invalid' : '' ?>" 
                                        id="avatar" name="avatar" 
                                        accept="image/*">
                                <?php if (isset($validation) && $validation->hasError('avatar')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('avatar') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text text-muted">
                                <i class='bx bx-info-circle me-1'></i>
                                Chọn file ảnh để tải lên. Định dạng hỗ trợ: JPG, JPEG, PNG. <?= $isUpdate ? 'Để trống nếu không muốn thay đổi ảnh hiện tại.' : '' ?>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="image-preview-container">
                                <?php if (!empty($avatar)): ?>
                                <div class="current-image border rounded p-2 mb-2">
                                    <p class="text-center mb-1 small text-muted">Ảnh hiện tại</p>
                                    <img src="<?= $avatarUrl ?>" 
                                         alt="Ảnh hiện tại" 
                                         class="img-thumbnail" style="width: 100%; max-height: 150px; object-fit: contain;">
                                </div>
                                <?php endif; ?>
                                <div class="preview-image border rounded p-2 <?= empty($avatar) ? 'mt-0' : 'mt-3' ?>" style="display: none;">
                                    <p class="text-center mb-1 small text-muted">Ảnh mới</p>
                                    <img id="avatar-preview" src="#" alt="Preview" 
                                         class="img-thumbnail" style="width: 100%; max-height: 150px; object-fit: contain;">
                                </div>
                            </div>
                        </div>
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
                    <a href="<?= site_url('diengia') ?>" class="btn btn-light">
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
        
        // Image preview functionality
        const avatarInput = document.getElementById('avatar');
        const avatarPreview = document.getElementById('avatar-preview');
        const previewContainer = document.querySelector('.preview-image');
        
        if (avatarInput && avatarPreview) {
            avatarInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                } else {
                    previewContainer.style.display = 'none';
                }
            });
        }
        
        // Tự động focus vào trường đầu tiên
        document.getElementById('ten_dien_gia').focus();
    });
</script> 