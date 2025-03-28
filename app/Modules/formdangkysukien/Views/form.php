<?php
/**
 * Form component for creating and updating form đăng ký sự kiện
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var FormDangKySuKien $data FormDangKySuKien entity data for editing (optional)
 * @var array $suKienList List of all events
 */

// Thiết lập các biến mặc định
$id = isset($data) ? $data->getFormId() : '';
$ten_form = isset($data) ? $data->getTenForm() : '';
$mo_ta = isset($data) ? $data->getMoTa() : '';
$su_kien_id = isset($data) ? $data->getSuKienId() : '';
$cau_truc_form = isset($data) ? $data->getCauTrucFormJson() : '[]';
$bat_buoc_dien = isset($data) ? $data->getBatBuocDien() : '';
$hien_thi_cong_khai = isset($data) ? $data->getHienThiCongKhai() : '';
$so_lan_su_dung = isset($data) ? $data->getSoLanSuDung() : '';
$status = isset($data) ? $data->getStatus() : 1;

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$su_kien_id = old('su_kien_id', $su_kien_id);
$mo_ta = old('mo_ta', $mo_ta);
$hien_thi_cong_khai = old('hien_thi_cong_khai', $hien_thi_cong_khai);
$bat_buoc_dien = old('bat_buoc_dien', $bat_buoc_dien);
$cau_truc_form = old('cau_truc_form', $cau_truc_form);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="form_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="form_id" value="0">
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
                <i class='bx bx-user text-primary me-2'></i>
                Thông tin form đăng ký sự kiện
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_form -->
                <div class="col-md-6">
                    <label for="ten_form" class="form-label fw-semibold">
                        Tên form <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($validation) && $validation->hasError('ten_form') ? 'is-invalid' : '' ?>" 
                           id="ten_form" name="ten_form"
                           value="<?= esc($ten_form) ?>"
                           required
                           placeholder="Nhập tên form">
                    <?php if (isset($validation) && $validation->hasError('ten_form')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('ten_form') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- su_kien_id -->
                <div class="col-md-6">
                    <label for="su_kien_id" class="form-label fw-semibold">
                        Sự kiện <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('su_kien_id') ? 'is-invalid' : '' ?>" 
                            id="su_kien_id" name="su_kien_id" required>
                        <option value="">Chọn sự kiện</option>
                        <?php foreach ($suKienList as $suKien): ?>
                            <option value="<?= $suKien->su_kien_id ?>" <?= $su_kien_id == $suKien->su_kien_id ? 'selected' : '' ?>>
                                <?= esc($suKien->ten_su_kien) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('su_kien_id')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('su_kien_id') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- mo_ta -->
                <div class="col-md-12">
                    <label for="mo_ta" class="form-label fw-semibold">
                        Mô tả
                    </label>
                    <textarea class="form-control <?= isset($validation) && $validation->hasError('mo_ta') ? 'is-invalid' : '' ?>" 
                              id="mo_ta" name="mo_ta"
                              rows="4"
                              placeholder="Nhập mô tả"><?= esc($mo_ta) ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('mo_ta')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('mo_ta') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- cau_truc_form -->
                <div class="col-md-12">
                    <label for="cau_truc_form" class="form-label fw-semibold">
                        Cấu trúc form <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control <?= isset($validation) && $validation->hasError('cau_truc_form') ? 'is-invalid' : '' ?>" 
                              id="cau_truc_form" name="cau_truc_form"
                              rows="10"
                              required
                              placeholder="Nhập cấu trúc form dạng JSON"><?= esc($cau_truc_form) ?></textarea>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Định dạng JSON chứa cấu trúc các trường của form
                    </div>
                    <?php if (isset($validation) && $validation->hasError('cau_truc_form')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('cau_truc_form') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- bat_buoc_dien -->
                <div class="col-md-6">
                    <label for="bat_buoc_dien" class="form-label fw-semibold">
                        Bắt buộc điền
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('bat_buoc_dien') ? 'is-invalid' : '' ?>" 
                            id="bat_buoc_dien" name="bat_buoc_dien">
                        <option value="1" <?= $bat_buoc_dien ? 'selected' : '' ?>>Có</option>
                        <option value="0" <?= !$bat_buoc_dien ? 'selected' : '' ?>>Không</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('bat_buoc_dien')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('bat_buoc_dien') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- hien_thi_cong_khai -->
                <div class="col-md-6">
                    <label for="hien_thi_cong_khai" class="form-label fw-semibold">
                        Hiển thị công khai
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('hien_thi_cong_khai') ? 'is-invalid' : '' ?>" 
                            id="hien_thi_cong_khai" name="hien_thi_cong_khai">
                        <option value="1" <?= $hien_thi_cong_khai ? 'selected' : '' ?>>Có</option>
                        <option value="0" <?= !$hien_thi_cong_khai ? 'selected' : '' ?>>Không</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('hien_thi_cong_khai')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('hien_thi_cong_khai') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- status -->
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">
                        Trạng thái
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                            id="status" name="status">
                        <option value="1" <?= $status ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= !$status ? 'selected' : '' ?>>Không hoạt động</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('status')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('status') ?>
                        </div>
                    <?php endif; ?>
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
                
                // Validate JSON format for cau_truc_form
                const cauTrucForm = document.getElementById('cau_truc_form').value;
                try {
                    if (cauTrucForm) {
                        JSON.parse(cauTrucForm);
                    }
                } catch (e) {
                    event.preventDefault();
                    alert('Cấu trúc form không đúng định dạng JSON');
                    document.getElementById('cau_truc_form').classList.add('is-invalid');
                }
                
                form.classList.add('was-validated');
            }, false);
        });
        
        // Tự động focus vào trường đầu tiên
        document.getElementById('ten_form').focus();
    });
</script>