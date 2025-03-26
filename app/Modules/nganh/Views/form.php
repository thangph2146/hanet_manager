<?php
/**
 * Form component for creating and updating ngành
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var Nganh $data Nganh entity data for editing (optional)
 * @var array $phongKhoaList Danh sách phòng khoa
 */

// Set default values if editing
$ten_nganh = isset($data) ? $data->getTenNganh() : '';
$ma_nganh = isset($data) ? $data->getMaNganh() : '';
$phong_khoa_id = isset($data) ? $data->getPhongKhoaId() : '';
$status = isset($data) ? (string)$data->isActive() : '1';
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$ten_nganh = old('ten_nganh', $ten_nganh);
$ma_nganh = old('ma_nganh', $ma_nganh);
$phong_khoa_id = old('phong_khoa_id', $phong_khoa_id);
$status = old('status', $status);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="nganh_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="nganh_id" value="0">
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
                Thông tin ngành
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_nganh -->
                <div class="col-md-6">
                    <label for="ten_nganh" class="form-label fw-semibold">
                        Tên ngành <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-book'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_nganh') ? 'is-invalid' : '' ?>" 
                            id="ten_nganh" name="ten_nganh" 
                            value="<?= esc($ten_nganh) ?>" 
                            placeholder="Nhập tên ngành"
                            required maxlength="200">
                        <?php if (isset($validation) && $validation->hasError('ten_nganh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_nganh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên ngành</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên ngành tối đa 200 ký tự
                    </div>
                </div>

                <!-- ma_nganh -->
                <div class="col-md-6">
                    <label for="ma_nganh" class="form-label fw-semibold">
                        Mã ngành <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_nganh') ? 'is-invalid' : '' ?>" 
                            id="ma_nganh" name="ma_nganh" 
                            value="<?= esc($ma_nganh) ?>" 
                            placeholder="Nhập mã ngành"
                            required maxlength="20">
                        <?php if (isset($validation) && $validation->hasError('ma_nganh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_nganh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập mã ngành</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã ngành là duy nhất, tối đa 20 ký tự
                    </div>
                </div>
                
                <!-- phong_khoa_id -->
                <div class="col-md-6">
                    <label for="phong_khoa_id" class="form-label fw-semibold">
                        Phòng khoa
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-building'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('phong_khoa_id') ? 'is-invalid' : '' ?>" 
                               id="phong_khoa_id" name="phong_khoa_id">
                            <option value="">-- Chọn phòng khoa --</option>
                            <?php if (isset($phongKhoaList) && is_array($phongKhoaList)): ?>
                                <?php foreach ($phongKhoaList as $pk): ?>
                                    <option value="<?= $pk->getId() ?>" <?= $phong_khoa_id == $pk->getId() ? 'selected' : '' ?>>
                                        <?= esc($pk->getTenPhongKhoa()) ?>
                                        <?php if (!empty($pk->getMaPhongKhoa())): ?>
                                            (<?= esc($pk->getMaPhongKhoa()) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('phong_khoa_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('phong_khoa_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Phòng khoa quản lý ngành
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
                        Trạng thái ngành trong hệ thống
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
        document.getElementById('ten_nganh').focus();
    });
</script> 