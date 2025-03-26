<?php
/**
 * Form component for creating and updating khuôn mặt người dùng
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var FaceNguoiDung $data FaceNguoiDung entity data for editing (optional)
 * @var array $nguoiDungList Danh sách người dùng
 */

// Set default values if editing
$nguoi_dung_id = isset($data) ? $data->getNguoiDungId() : '';
$duong_dan_anh = isset($data) ? $data->getDuongDanAnh() : '';
$status = isset($data) ? (string)$data->isActive() : '1';
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$nguoi_dung_id = old('nguoi_dung_id', $nguoi_dung_id);
$duong_dan_anh = old('duong_dan_anh', $duong_dan_anh);
$status = old('status', $status);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="face_nguoi_dung_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="face_nguoi_dung_id" value="0">
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
                <i class='bx bx-user-circle text-primary me-2'></i>
                Thông tin khuôn mặt người dùng
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- nguoi_dung_id -->
                <div class="col-md-6">
                    <label for="nguoi_dung_id" class="form-label fw-semibold">
                        Người dùng <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('nguoi_dung_id') ? 'is-invalid' : '' ?>" 
                               id="nguoi_dung_id" name="nguoi_dung_id" required>
                            <option value="">-- Chọn người dùng --</option>
                            <?php if (isset($nguoiDungList) && is_array($nguoiDungList)): ?>
                                <?php foreach ($nguoiDungList as $nd): ?>
                                    <option value="<?= $nd->getId() ?>" <?= $nguoi_dung_id == $nd->getId() ? 'selected' : '' ?>>
                                        <?= esc($nd->getFullName()) ?>
                                        <?php if (!empty($nd->getEmail())): ?>
                                            (<?= esc($nd->getEmail()) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('nguoi_dung_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('nguoi_dung_id') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng chọn người dùng</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Chọn người dùng cần thêm khuôn mặt
                    </div>
                </div>

                <!-- duong_dan_anh -->
                <div class="col-md-6">
                    <label for="duong_dan_anh" class="form-label fw-semibold">
                        Ảnh khuôn mặt <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-image'></i></span>
                        <input type="file" class="form-control <?= isset($validation) && $validation->hasError('duong_dan_anh') ? 'is-invalid' : '' ?>" 
                            id="duong_dan_anh" name="duong_dan_anh" 
                            accept="image/*"
                            <?= !$isUpdate ? 'required' : '' ?>>
                        <?php if (isset($validation) && $validation->hasError('duong_dan_anh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('duong_dan_anh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng chọn ảnh khuôn mặt</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Chọn ảnh khuôn mặt người dùng (jpg, jpeg, png)
                    </div>
                    <!-- Thêm div để hiển thị ảnh xem trước -->
                    <div id="imagePreview" class="mt-2" style="display: none;">
                        <div class="border rounded p-2 bg-light">
                            <img id="previewImage" src="" alt="Ảnh xem trước" class="img-thumbnail" style="max-width: 200px; max-height: 200px; object-fit: cover;">
                        </div>
                    </div>
                    <?php if ($isUpdate && !empty($duong_dan_anh)): ?>
                        <div class="mt-2">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="text-muted small">
                                    <i class='bx bx-link-alt me-1'></i>
                                    Đường dẫn hiện tại:
                                </span>
                                <span class="text-primary small">
                                    <?= esc($duong_dan_anh) ?>
                                </span>
                            </div>
                            <div class="border rounded p-2 bg-light">
                                <img src="<?= base_url($duong_dan_anh) ?>" 
                                     alt="Ảnh khuôn mặt hiện tại" 
                                     class="img-thumbnail"
                                     style="max-width: 200px; max-height: 200px; object-fit: cover;">
                            </div>
                            <input type="hidden" name="duong_dan_anh" value="<?= esc($duong_dan_anh) ?>">
                        </div>
                    <?php endif; ?>
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
                        Trạng thái khuôn mặt người dùng trong hệ thống
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
        document.getElementById('nguoi_dung_id').focus();

        // Xử lý hiển thị ảnh xem trước
        const imageInput = document.getElementById('duong_dan_anh');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');

        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            });
        }
    });
</script> 