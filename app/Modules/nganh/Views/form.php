<?php
/**
 * Form component for creating and updating nganh
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var array $nganh Nganh entity data for editing (optional)
 * @var array $phongkhoas Array of available phong_khoa options (optional)
 */

// Set default values if editing
$ten_nganh = isset($nganh) ? $nganh->ten_nganh : '';
$ma_nganh = isset($nganh) ? $nganh->ma_nganh : '';
$phong_khoa_id = isset($nganh) ? $nganh->phong_khoa_id : '';
$status = isset($nganh) ? (string)$nganh->status : '1';
$id = isset($nganh) ? $nganh->nganh_id : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('nganh/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$formTitle = isset($is_new) && $is_new ? 'Thêm mới ngành' : 'Cập nhật ngành';
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="nganhForm" class="needs-validation" novalidate>
    <?php if (isset($nganh->nganh_id)): ?>
        <input type="hidden" name="nganh_id" value="<?= $id ?>">
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
    <?php if (session('errors') && is_array(session('errors'))): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-start border-danger border-4" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class='bx bx-error-circle fs-3'></i>
                </div>
                <div>
                    <strong>Lỗi nhập liệu:</strong>
                    <ul class="mb-0 ps-3 mt-1">
                        <?php foreach (session('errors') as $error): ?>
                            <li><?= $error ?></li>
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
                <!-- ma_nganh -->
                <div class="col-md-6">
                    <label for="ma_nganh" class="form-label fw-semibold">
                        Mã ngành <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" class="form-control <?= session('errors.ma_nganh') ? 'is-invalid' : '' ?>" 
                                id="ma_nganh" name="ma_nganh" 
                                value="<?= old('ma_nganh', $ma_nganh) ?>" 
                                placeholder="Nhập mã ngành"
                                required maxlength="20">
                        <?php if (session('errors.ma_nganh')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.ma_nganh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập mã ngành</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã ngành phải là duy nhất trong hệ thống, tối đa 20 ký tự
                    </div>
                </div>

                <!-- ten_nganh -->
                <div class="col-md-6">
                    <label for="ten_nganh" class="form-label fw-semibold">
                        Tên ngành <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-book-alt'></i></span>
                        <input type="text" class="form-control <?= session('errors.ten_nganh') ? 'is-invalid' : '' ?>" 
                                id="ten_nganh" name="ten_nganh" 
                                value="<?= old('ten_nganh', $ten_nganh) ?>" 
                                placeholder="Nhập tên ngành"
                                required minlength="3" maxlength="100">
                        <?php if (session('errors.ten_nganh')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.ten_nganh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên ngành (tối thiểu 3 ký tự)</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên ngành phải có ít nhất 3 ký tự và không trùng với các ngành khác
                    </div>
                </div>

                <!-- phong_khoa_id -->
                <div class="col-md-6">
                    <label for="phong_khoa_id" class="form-label fw-semibold">
                        Phòng/Khoa quản lý
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-building'></i></span>
                        <select class="form-select select <?= session('errors.phong_khoa_id') ? 'is-invalid' : '' ?>" 
                                id="phong_khoa_id" name="phong_khoa_id"
                                data-placeholder="-- Chọn phòng/khoa --">
                            <option value="">-- Chọn phòng/khoa --</option>
                            <?php if (!empty($phongkhoas)): ?>
                                <?php foreach ($phongkhoas as $pk): ?>
                                    <option value="<?= $pk->phong_khoa_id ?>" 
                                        <?= old('phong_khoa_id', $phong_khoa_id) == $pk->phong_khoa_id ? 'selected' : '' ?>>
                                        <?= esc($pk->ten_phong_khoa) ?> (<?= esc($pk->ma_phong_khoa) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (session('errors.phong_khoa_id')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.phong_khoa_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Chọn phòng/khoa quản lý ngành này (không bắt buộc)
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
                        Ngành không hoạt động sẽ không hiển thị trong các danh sách chọn
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
                    <a href="<?= site_url('nganh') ?>" class="btn btn-light">
                        <i class='bx bx-arrow-back me-1'></i> Quay lại
                    </a>
                    <button class="btn btn-primary px-4" type="submit">
                        <i class='bx bx-save me-1'></i>
                        <?= isset($nganh->nganh_id) && $nganh->nganh_id ? 'Cập nhật' : 'Thêm mới' ?>
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