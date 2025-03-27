<?php
/**
 * Form component for creating and updating liên kết sự kiện - diễn giả
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var SuKienDienGia $data SuKienDienGia entity data for editing (optional)
 * @var array $sukienList Danh sách sự kiện
 * @var array $dienGiaList Danh sách diễn giả
 */

// Set default values if editing
$su_kien_id = isset($data) ? $data->getSuKienId() : '';
$dien_gia_id = isset($data) ? $data->getDienGiaId() : '';
$thu_tu = isset($data) ? $data->getThuTu() : 0;
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$su_kien_id = old('su_kien_id', $su_kien_id);
$dien_gia_id = old('dien_gia_id', $dien_gia_id);
$thu_tu = old('thu_tu', $thu_tu);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="su_kien_dien_gia_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="su_kien_dien_gia_id" value="0">
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
                <i class='bx bx-link text-primary me-2'></i>
                Thông tin liên kết sự kiện - diễn giả
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- su_kien_id -->
                <div class="col-md-6">
                    <label for="su_kien_id" class="form-label fw-semibold">
                        Sự kiện <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar-event'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('su_kien_id') ? 'is-invalid' : '' ?>" 
                               id="su_kien_id" name="su_kien_id" required>
                            <option value="">-- Chọn sự kiện --</option>
                            <?php if (isset($sukienList) && is_array($sukienList)): ?>
                                <?php foreach ($sukienList as $sk): ?>
                                    <?php 
                                    // Xác định ID sự kiện dựa trên kiểu dữ liệu
                                    $skId = is_object($sk) ? $sk->getId() : ($sk['su_kien_id'] ?? '');
                                    $skName = is_object($sk) ? ($sk->ten_su_kien ?? '') : ($sk['ten_su_kien'] ?? '');
                                    $skTime = is_object($sk) ? ($sk->thoi_gian_bat_dau ?? '') : ($sk['thoi_gian_bat_dau'] ?? '');
                                    ?>
                                    <option value="<?= $skId ?>" <?= $su_kien_id == $skId ? 'selected' : '' ?>>
                                        <?= esc($skName) ?>
                                        <?php if (!empty($skTime)): ?>
                                            (<?= esc($skTime) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('su_kien_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('su_kien_id') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng chọn sự kiện</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Sự kiện muốn liên kết với diễn giả
                    </div>
                </div>

                <!-- dien_gia_id -->
                <div class="col-md-6">
                    <label for="dien_gia_id" class="form-label fw-semibold">
                        Diễn giả <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('dien_gia_id') ? 'is-invalid' : '' ?>" 
                               id="dien_gia_id" name="dien_gia_id" required>
                            <option value="">-- Chọn diễn giả --</option>
                            <?php if (isset($dienGiaList) && is_array($dienGiaList)): ?>
                                <?php foreach ($dienGiaList as $dg): ?>
                                    <?php 
                                    // Xác định ID diễn giả dựa trên kiểu dữ liệu
                                    $dgId = is_object($dg) ? $dg->getId() : ($dg['dien_gia_id'] ?? '');
                                    $dgName = is_object($dg) ? ($dg->ten_dien_gia ?? '') : ($dg['ten_dien_gia'] ?? '');
                                    $dgTitle = is_object($dg) ? ($dg->chuc_danh ?? '') : ($dg['chuc_danh'] ?? '');
                                    ?>
                                    <option value="<?= $dgId ?>" <?= $dien_gia_id == $dgId ? 'selected' : '' ?>>
                                        <?= esc($dgName) ?>
                                        <?php if (!empty($dgTitle)): ?>
                                            (<?= esc($dgTitle) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('dien_gia_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dien_gia_id') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng chọn diễn giả</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Diễn giả muốn liên kết với sự kiện
                    </div>
                </div>
                
                <!-- thu_tu -->
                <div class="col-md-6">
                    <label for="thu_tu" class="form-label fw-semibold">
                        Thứ tự
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-sort-up'></i></span>
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('thu_tu') ? 'is-invalid' : '' ?>" 
                            id="thu_tu" name="thu_tu" 
                            value="<?= esc($thu_tu) ?>" 
                            placeholder="Nhập thứ tự"
                            min="0" max="9999">
                        <?php if (isset($validation) && $validation->hasError('thu_tu')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thu_tu') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Thứ tự hiển thị của diễn giả trong sự kiện (nhỏ hơn sẽ hiển thị trước)
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
        document.getElementById('su_kien_id').focus();
    });
</script> 