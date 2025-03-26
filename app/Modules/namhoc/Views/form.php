<?php
/**
 * Form component for creating and updating năm học
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var NamHoc $namHoc NamHoc entity data for editing (optional)
 */

// Set default values if editing
$ten_nam_hoc = isset($namHoc) ? $namHoc->getTenNamHoc() : '';
$ngay_bat_dau = isset($namHoc) ? $namHoc->getNgayBatDau() : '';
$ngay_ket_thuc = isset($namHoc) ? $namHoc->getNgayKetThuc() : '';
$status = isset($namHoc) ? (string)$namHoc->isActive() : '1';
$id = isset($namHoc) ? $namHoc->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($namHoc) && $namHoc->getId() > 0;

// Format ngày bắt đầu cho input date
if (!empty($ngay_bat_dau)) {
    try {
        if ($ngay_bat_dau instanceof \CodeIgniter\I18n\Time) {
            $ngay_bat_dau = $ngay_bat_dau->format('Y-m-d');
        } else {
            $date = new \DateTime($ngay_bat_dau);
            $ngay_bat_dau = $date->format('Y-m-d');
        }
    } catch (\Exception $e) {
        log_message('error', 'Lỗi định dạng ngày bắt đầu: ' . $e->getMessage());
        $ngay_bat_dau = '';
    }
}

// Format ngày kết thúc cho input date
if (!empty($ngay_ket_thuc)) {
    try {
        if ($ngay_ket_thuc instanceof \CodeIgniter\I18n\Time) {
            $ngay_ket_thuc = $ngay_ket_thuc->format('Y-m-d');
        } else {
            $date = new \DateTime($ngay_ket_thuc);
            $ngay_ket_thuc = $date->format('Y-m-d');
        }
    } catch (\Exception $e) {
        log_message('error', 'Lỗi định dạng ngày kết thúc: ' . $e->getMessage());
        $ngay_ket_thuc = '';
    }
}

// Lấy giá trị từ old() nếu có
$ten_nam_hoc = old('ten_nam_hoc', $ten_nam_hoc);
$ngay_bat_dau = old('ngay_bat_dau', $ngay_bat_dau);
$ngay_ket_thuc = old('ngay_ket_thuc', $ngay_ket_thuc);
$status = old('status', $status);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="nam_hoc_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="nam_hoc_id" value="0">
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
                <i class='bx bx-calendar text-primary me-2'></i>
                Thông tin năm học
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_nam_hoc -->
                <div class="col-md-12">
                    <label for="ten_nam_hoc" class="form-label fw-semibold">
                        Tên năm học <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_nam_hoc') ? 'is-invalid' : '' ?>" 
                            id="ten_nam_hoc" name="ten_nam_hoc" 
                            value="<?= esc($ten_nam_hoc) ?>" 
                            placeholder="Nhập tên năm học (VD: 2023-2024)"
                            required maxlength="50">
                        <?php if (isset($validation) && $validation->hasError('ten_nam_hoc')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_nam_hoc') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên năm học</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên năm học là duy nhất, định dạng: YYYY-YYYY (VD: 2023-2024)
                    </div>
                </div>

                <!-- ngay_bat_dau -->
                <div class="col-md-6">
                    <label for="ngay_bat_dau" class="form-label fw-semibold">
                        Ngày bắt đầu
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar-event'></i></span>
                        <input type="text" 
                               class="form-control datepicker <?= isset($validation) && $validation->hasError('ngay_bat_dau') ? 'is-invalid' : '' ?>" 
                               id="ngay_bat_dau" name="ngay_bat_dau"
                               value="<?= esc($ngay_bat_dau) ?>"
                               placeholder="Chọn ngày bắt đầu">
                        <?php if (isset($validation) && $validation->hasError('ngay_bat_dau')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ngay_bat_dau') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Ngày bắt đầu năm học (vd: 01/09/2023)
                    </div>
                </div>

                <!-- ngay_ket_thuc -->
                <div class="col-md-6">
                    <label for="ngay_ket_thuc" class="form-label fw-semibold">
                        Ngày kết thúc
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar-x'></i></span>
                        <input type="text" 
                               class="form-control datepicker <?= isset($validation) && $validation->hasError('ngay_ket_thuc') ? 'is-invalid' : '' ?>" 
                               id="ngay_ket_thuc" name="ngay_ket_thuc"
                               value="<?= esc($ngay_ket_thuc) ?>"
                               placeholder="Chọn ngày kết thúc">
                        <?php if (isset($validation) && $validation->hasError('ngay_ket_thuc')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ngay_ket_thuc') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Ngày kết thúc năm học (vd: 31/05/2024)
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
                        Nếu chọn "Hoạt động", đây sẽ là năm học hiện tại
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
        document.getElementById('ten_nam_hoc').focus();
        
        // Khởi tạo Flatpickr cho các trường ngày tháng
        const dateConfig = {
            dateFormat: "Y-m-d",
            locale: "vn",
            allowInput: true,
            altInput: true,
            altFormat: "d/m/Y",
            disableMobile: true
        };
        
        const ngayBatDauPicker = flatpickr('#ngay_bat_dau', {
            ...dateConfig,
            onChange: function(selectedDates, dateStr) {
                validateDates();
                suggestSchoolYear(selectedDates[0]);
            }
        });
        
        const ngayKetThucPicker = flatpickr('#ngay_ket_thuc', {
            ...dateConfig,
            onChange: function(selectedDates) {
                validateDates();
            }
        });
        
        // Kiểm tra ngày kết thúc phải sau ngày bắt đầu
        function validateDates() {
            const ngayBatDauValue = ngayBatDauPicker.selectedDates[0];
            const ngayKetThucValue = ngayKetThucPicker.selectedDates[0];
            
            if (ngayBatDauValue && ngayKetThucValue) {
                if (ngayKetThucValue < ngayBatDauValue) {
                    Swal.fire({
                        title: 'Cảnh báo',
                        text: 'Ngày kết thúc phải sau ngày bắt đầu',
                        icon: 'warning',
                        confirmButtonText: 'Đã hiểu'
                    });
                    ngayKetThucPicker.clear();
                }
            }
        }
        
        // Đề xuất tên năm học dựa trên ngày bắt đầu
        function suggestSchoolYear(date) {
            const tenNamHocInput = document.getElementById('ten_nam_hoc');
            
            // Chỉ đề xuất tên năm học nếu trường tên đang trống
            if (date && !tenNamHocInput.value.trim()) {
                const year = date.getFullYear();
                const nextYear = year + 1;
                
                tenNamHocInput.value = `${year}-${nextYear}`;
            }
        }
    });
</script> 