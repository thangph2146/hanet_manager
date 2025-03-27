<?php
/**
 * Form component for creating and updating diễn giả
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var DienGia $data DienGia entity data for editing (optional)
 */

// Set default values if editing
$ten_dien_gia = isset($data) ? $data->getTenDienGia() : '';
$chuc_danh = isset($data) ? $data->getChucDanh() : '';
$to_chuc = isset($data) ? $data->getToChuc() : '';
$gioi_thieu = isset($data) ? $data->getGioiThieu() : '';
$avatar = isset($data) ? $data->getAvatar() : '';
$thu_tu = isset($data) ? $data->getThuTu() : 0;
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$ten_dien_gia = old('ten_dien_gia', $ten_dien_gia);
$chuc_danh = old('chuc_danh', $chuc_danh);
$to_chuc = old('to_chuc', $to_chuc);
$gioi_thieu = old('gioi_thieu', $gioi_thieu);
$avatar = old('avatar', $avatar);
$thu_tu = old('thu_tu', $thu_tu);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="dien_gia_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="dien_gia_id" value="0">
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
                <i class='bx bx-user-voice text-primary me-2'></i>
                Thông tin diễn giả
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_dien_gia -->
                <div class="col-md-12">
                    <label for="ten_dien_gia" class="form-label fw-semibold">
                        Tên diễn giả <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_dien_gia') ? 'is-invalid' : '' ?>" 
                            id="ten_dien_gia" name="ten_dien_gia" 
                            value="<?= esc($ten_dien_gia) ?>" 
                            placeholder="Nhập tên diễn giả"
                            required maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('ten_dien_gia')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_dien_gia') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên diễn giả</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên diễn giả là duy nhất, tối đa 255 ký tự
                    </div>
                </div>

                <!-- chuc_danh -->
                <div class="col-md-6">
                    <label for="chuc_danh" class="form-label fw-semibold">
                        Chức danh
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-id-card'></i></span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('chuc_danh') ? 'is-invalid' : '' ?>" 
                               id="chuc_danh" name="chuc_danh"
                               value="<?= esc($chuc_danh) ?>"
                               placeholder="Nhập chức danh"
                               maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('chuc_danh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('chuc_danh') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Chức danh tối đa 255 ký tự (không bắt buộc)
                    </div>
                </div>

                <!-- to_chuc -->
                <div class="col-md-6">
                    <label for="to_chuc" class="form-label fw-semibold">
                        Tổ chức
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-building'></i></span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('to_chuc') ? 'is-invalid' : '' ?>" 
                               id="to_chuc" name="to_chuc"
                               value="<?= esc($to_chuc) ?>"
                               placeholder="Nhập tên tổ chức"
                               maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('to_chuc')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('to_chuc') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tổ chức tối đa 255 ký tự (không bắt buộc)
                    </div>
                </div>

                <!-- gioi_thieu -->
                <div class="col-md-12">
                    <label for="gioi_thieu" class="form-label fw-semibold">
                        Giới thiệu
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-info-circle'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('gioi_thieu') ? 'is-invalid' : '' ?>" 
                               id="gioi_thieu" name="gioi_thieu"
                               placeholder="Nhập giới thiệu về diễn giả"
                               rows="4"><?= esc($gioi_thieu) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('gioi_thieu')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('gioi_thieu') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Thông tin giới thiệu về diễn giả (không bắt buộc)
                    </div>
                </div>

                <!-- avatar -->
                <div class="col-md-6">
                    <label for="avatar" class="form-label fw-semibold">
                        Ảnh đại diện
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-image'></i></span>
                        <input type="file" 
                               class="form-control <?= isset($validation) && $validation->hasError('avatar') ? 'is-invalid' : '' ?>" 
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
                        Ảnh đại diện diễn giả (không bắt buộc)
                    </div>
                    <?php if(!empty($avatar)): ?>
                    <div class="mt-2">
                        <img src="<?= base_url('uploads/diengia/' . $avatar) ?>" alt="Avatar" class="img-thumbnail" style="max-height: 100px;">
                    </div>
                    <?php endif; ?>
                </div>

                <!-- thu_tu -->
                <div class="col-md-6">
                    <label for="thu_tu" class="form-label fw-semibold">
                        Thứ tự
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-sort-up'></i></span>
                        <input type="number" 
                               class="form-control <?= isset($validation) && $validation->hasError('thu_tu') ? 'is-invalid' : '' ?>" 
                               id="thu_tu" name="thu_tu"
                               value="<?= esc($thu_tu) ?>"
                               placeholder="Nhập thứ tự hiển thị"
                               min="0">
                        <?php if (isset($validation) && $validation->hasError('thu_tu')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thu_tu') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Thứ tự sắp xếp diễn giả (mặc định 0)
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
        document.getElementById('ten_dien_gia').focus();
    });
</script> 