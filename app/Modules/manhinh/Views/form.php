<?php
/**
 * Form component for creating and updating màn hình
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var ManHinh $manhinh ManHinh entity data for editing (optional)
 */

// Set default values if editing
$ten_man_hinh = isset($manhinh) ? $manhinh->ten_man_hinh : '';
$ma_man_hinh = isset($manhinh) ? $manhinh->ma_man_hinh : '';
$camera_id = isset($manhinh) ? $manhinh->camera_id : '';
$template_id = isset($manhinh) ? $manhinh->template_id : '';
$status = isset($manhinh) ? (string)$manhinh->status : '1';
$bin = isset($manhinh) ? (string)$manhinh->bin : '0';
$id = isset($manhinh) ? $manhinh->man_hinh_id : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('manhinh/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$formTitle = isset($is_new) && $is_new ? 'Thêm mới màn hình' : 'Cập nhật màn hình';
$isUpdate = isset($manhinh) && isset($manhinh->man_hinh_id);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="manhinhForm" class="needs-validation" novalidate>
    <?php if ($isUpdate): ?>
        <input type="hidden" name="man_hinh_id" value="<?= $id ?>">
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
                <!-- ma_man_hinh -->
                <div class="col-md-6">
                    <label for="ma_man_hinh" class="form-label fw-semibold">
                        Mã màn hình <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_man_hinh') ? 'is-invalid' : '' ?>" 
                                id="ma_man_hinh" name="ma_man_hinh" 
                                value="<?= old('ma_man_hinh', $ma_man_hinh) ?>" 
                                placeholder="Nhập mã màn hình"
                                required maxlength="20">
                        <?php if (isset($validation) && $validation->hasError('ma_man_hinh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_man_hinh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập mã màn hình</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mã màn hình phải là duy nhất trong hệ thống, tối đa 20 ký tự
                    </div>
                </div>

                <!-- ten_man_hinh -->
                <div class="col-md-6">
                    <label for="ten_man_hinh" class="form-label fw-semibold">
                        Tên màn hình <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-desktop'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_man_hinh') ? 'is-invalid' : '' ?>" 
                                id="ten_man_hinh" name="ten_man_hinh" 
                                value="<?= old('ten_man_hinh', $ten_man_hinh) ?>" 
                                placeholder="Nhập tên màn hình"
                                required minlength="3" maxlength="255" autocomplete="off"
                                oninput="this.value = this.value.trim()">
                        <?php if (isset($validation) && $validation->hasError('ten_man_hinh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_man_hinh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên màn hình (tối thiểu 3 ký tự)</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên màn hình phải có ít nhất 3 ký tự và không trùng với bất kỳ màn hình nào trong hệ thống
                    </div>
                </div>

                <!-- camera_id -->
                <div class="col-md-6">
                    <label for="camera_id" class="form-label fw-semibold">
                        Camera <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-camera'></i></span>
                        <select class="form-select select <?= session('errors.camera_id') ? 'is-invalid' : '' ?>" 
                                id="camera_id" name="camera_id" required
                                data-placeholder="-- Chọn camera --">
                            <option value="">-- Chọn camera --</option>
                            <?php foreach ($cameras as $camera): ?>
                                <option value="<?= (int)$camera->camera_id ?>" 
                                        <?= (old('camera_id', $camera_id) == $camera->camera_id) ? 'selected' : '' ?>>
                                    <?= esc($camera->ten_camera) ?> (<?= esc($camera->ma_camera) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (session('errors.camera_id')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.camera_id') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng chọn camera</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- template_id -->
                <div class="col-md-6">
                    <label for="template_id" class="form-label fw-semibold">
                        Template
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-layout'></i></span>
                        <select class="form-select select" 
                                id="template_id" name="template_id"
                                data-placeholder="-- Chọn template --">
                            <option value="">-- Chọn template --</option>
                            <?php foreach ($templates as $template): ?>
                                <option value="<?= (int)$template->template_id ?>" 
                                        <?= (old('template_id', $template_id) == $template->template_id) ? 'selected' : '' ?>>
                                    <?= esc($template->ten_template) ?> (<?= esc($template->ma_template) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Chọn template cho màn hình (không bắt buộc)
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
                            <option value="0" <?= old('staus', $status) == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Màn hình không hoạt động sẽ không hiển thị trong các danh sách chọn
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
                    <a href="<?= site_url('manhinh') ?>" class="btn btn-light">
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
        // Khởi tạo Select
        if ($.fn.select) {
            $('.select').select({
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
        document.getElementById('ten_man_hinh').focus();
    });
</script>

<?php $this->section('styles') ?>
<style>
    /* Select Bootstrap 5 theme integration */
    .select-container--bootstrap-5 .select-selection {
        min-height: 38px;        padding: 0.375re 0.75rem;
        font-size: 1rem        font-weight: 400;
        line-height: 1.5;
        border: 1px solid #dee2e6;
    }
    
    .select-container--bootstrap-5 .select-selection--single {
        padding: 0.375rem 2.25rem 0.375rem 0.75rem;
    }
    
    .select-container--bootstrap-5 .select-selection--single .select-selection__rendered {
        padding: 0;
        font-weight: 400;
    }
    
    .select-container--bootstrap-5 .select-selection--multiple .select-selection__rendered {
        padding: 0;
    }
    
    .select-container--bootstrap-5 .select-selection--multiple .select-selection__choice {
        margin: 0.25rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
<?php $this->endSection() ?> 