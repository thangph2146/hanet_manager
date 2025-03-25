<?php
/**
 * Form component for creating and updating face recognition data
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var object $item Face data entity for editing (optional)
 * @var array $nguoidungs Array of available nguoi_dung options (optional)
 */

// Set default values if editing
$face_nguoi_dung_id = isset($item) ? $item->face_nguoi_dung_id : '';
$nguoi_dung_id = isset($item) ? $item->nguoi_dung_id : '';
$duong_dan_anh = isset($item) ? $item->duong_dan_anh : '';
$status = isset($item) ? (string)$item->status : '1';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('facenguoidung/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$formTitle = isset($is_new) && $is_new ? 'Thêm mới khuôn mặt người dùng' : 'Cập nhật khuôn mặt người dùng';
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="faceForm" class="needs-validation" enctype="multipart/form-data" novalidate>
    <?php if (isset($item->face_nguoi_dung_id)): ?>
        <input type="hidden" name="face_nguoi_dung_id" value="<?= $face_nguoi_dung_id ?>">
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
                <i class='bx bx-face text-primary me-2'></i>
                Thông tin khuôn mặt
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
                        <select class="form-select select2 <?= session('errors.nguoi_dung_id') ? 'is-invalid' : '' ?>" 
                                id="nguoi_dung_id" name="nguoi_dung_id"
                                data-placeholder="-- Chọn người dùng --"
                                required>
                            <option value="">-- Chọn người dùng --</option>
                            <?php 
                            $nguoidungData = isset($nguoidungs) ? $nguoidungs : [];
                            if (!empty($nguoidungData)): ?>
                                <?php foreach ($nguoidungData as $nd): ?>
                                    <option value="<?= isset($nd->id) ? $nd->id : $nd->nguoi_dung_id ?>" 
                                        <?= old('nguoi_dung_id', $nguoi_dung_id) == (isset($nd->id) ? $nd->id : $nd->nguoi_dung_id) ? 'selected' : '' ?>>
                                        <?= esc($nd->FullName ?? $nd->ho_ten) ?> (<?= esc($nd->Email ?? $nd->email) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (session('errors.nguoi_dung_id')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.nguoi_dung_id') ?>
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
                        Khuôn mặt không hoạt động sẽ không sử dụng trong hệ thống nhận diện
                    </div>
                </div>

                <!-- duong_dan_anh -->
                <div class="col-md-12">
                    <label for="duong_dan_anh" class="form-label fw-semibold">
                        Ảnh khuôn mặt <span class="text-danger">*</span>
                    </label>
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class='bx bx-image'></i></span>
                                <input type="file" class="form-control <?= session('errors.duong_dan_anh') ? 'is-invalid' : '' ?>" 
                                        id="duong_dan_anh" name="duong_dan_anh" 
                                        accept="image/jpeg,image/png,image/jpg"
                                        <?= isset($item) ? '' : 'required' ?>>
                                <?php if (session('errors.duong_dan_anh')): ?>
                                    <div class="invalid-feedback">
                                        <?= session('errors.duong_dan_anh') ?>
                                    </div>
                                <?php else: ?>
                                    <div class="invalid-feedback">Vui lòng chọn ảnh khuôn mặt</div>
                                <?php endif; ?>
                            </div>
                            <div class="form-text text-muted">
                                <i class='bx bx-info-circle me-1'></i>
                                Chấp nhận các định dạng: JPG, JPEG, PNG. Kích thước tối đa: 5MB. Ảnh sẽ được tự động nén trước khi lưu.
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Container cho preview ảnh -->
                            <div id="image-preview-container">
                                <?php if (isset($item) && !empty($item->duong_dan_anh)): ?>
                                <div class="mt-2">
                                    <img src="<?= base_url($item->duong_dan_anh) ?>" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                    <p class="small text-muted mt-1">Ảnh hiện tại</p>
                                </div>
                                <?php else: ?>
                                <div class="text-center p-3 border rounded bg-light">
                                    <i class='bx bx-camera fs-2 text-muted'></i>
                                    <p class="text-muted mb-0">Xem trước ảnh</p>
                                </div>
                                <?php endif; ?>
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
                    <a href="<?= site_url('facenguoidung') ?>" class="btn btn-light">
                        <i class='bx bx-arrow-back me-1'></i> Quay lại
                    </a>
                    <button class="btn btn-primary px-4" type="submit">
                        <i class='bx bx-save me-1'></i>
                        <?= isset($item->face_nguoi_dung_id) && $item->face_nguoi_dung_id ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Khởi tạo Select2
        if ($.fn.select2) {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
        
        // Preview ảnh khi chọn file
        const fileInput = document.getElementById('duong_dan_anh');
        const previewContainer = document.getElementById('image-preview-container');
        
        if (fileInput && previewContainer) {
            fileInput.addEventListener('change', function() {
                // Xóa preview cũ
                previewContainer.innerHTML = '';
                
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewContainer.innerHTML = `
                            <div class="mt-2">
                                <img src="${e.target.result}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                <p class="small text-muted mt-1">Ảnh đã chọn</p>
                            </div>
                        `;
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                } else {
                    previewContainer.innerHTML = `
                        <div class="text-center p-3 border rounded bg-light">
                            <i class='bx bx-camera fs-2 text-muted'></i>
                            <p class="text-muted mb-0">Xem trước ảnh</p>
                        </div>
                    `;
                }
            });
        }
        
        // Form validation
        const form = document.getElementById('faceForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                // Kiểm tra người dùng đã chọn chưa
                const nguoiDungSelect = document.getElementById('nguoi_dung_id');
                if (nguoiDungSelect && !nguoiDungSelect.value) {
                    event.preventDefault();
                    alert('Vui lòng chọn người dùng');
                    nguoiDungSelect.focus();
                    return false;
                }
                
                // Kiểm tra file đã chọn chưa (chỉ khi thêm mới)
                const isNewForm = <?= isset($is_new) && $is_new ? 'true' : 'false' ?>;
                if (isNewForm) {
                    const fileInput = document.getElementById('duong_dan_anh');
                    if (fileInput && (!fileInput.files || fileInput.files.length === 0)) {
                        event.preventDefault();
                        alert('Vui lòng chọn ảnh khuôn mặt');
                        fileInput.focus();
                        return false;
                    }
                }
                
                // Thêm class was-validated để hiển thị feedback
                form.classList.add('was-validated');
            });
        }
        
        // Tự động focus vào trường đầu tiên
        setTimeout(() => {
            const firstField = document.getElementById('nguoi_dung_id');
            if (firstField) firstField.focus();
        }, 100);
    });
</script> 