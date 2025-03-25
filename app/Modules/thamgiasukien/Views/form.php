<?php
/**
 * Form component for creating and updating tham gia su kien
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var ThamGiaSuKien $thamGiaSuKien ThamGiaSuKien entity data for editing (optional)
 */

// Set default values if editing
$nguoi_dung_id = isset($thamGiaSuKien) ? $thamGiaSuKien->nguoi_dung_id : '';
$su_kien_id = isset($thamGiaSuKien) ? $thamGiaSuKien->su_kien_id : '';
$thoi_gian_diem_danh = isset($thamGiaSuKien) ? $thamGiaSuKien->thoi_gian_diem_danh : '';
$phuong_thuc_diem_danh = isset($thamGiaSuKien) ? $thamGiaSuKien->phuong_thuc_diem_danh : 'manual';
$ghi_chu = isset($thamGiaSuKien) ? $thamGiaSuKien->ghi_chu : '';
$status = isset($thamGiaSuKien) ? (string)$thamGiaSuKien->status : '1';
$id = isset($thamGiaSuKien) ? $thamGiaSuKien->tham_gia_su_kien_id : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('thamgiasukien/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$formTitle = isset($is_new) && $is_new ? 'Thêm mới tham gia sự kiện' : 'Cập nhật tham gia sự kiện';
$isUpdate = isset($thamGiaSuKien) && isset($thamGiaSuKien->tham_gia_su_kien_id);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="thamGiaSuKienForm" class="needs-validation" novalidate>
    <?php if ($isUpdate): ?>
        <input type="hidden" name="tham_gia_su_kien_id" value="<?= $id ?>">
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
                Thông tin tham gia sự kiện
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
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('nguoi_dung_id') ? 'is-invalid' : '' ?>" 
                                id="nguoi_dung_id" name="nguoi_dung_id" 
                                value="<?= old('nguoi_dung_id', $nguoi_dung_id) ?>" 
                                placeholder="ID người dùng"
                                required min="1">
                        <?php if (isset($validation) && $validation->hasError('nguoi_dung_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('nguoi_dung_id') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập ID người dùng</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        ID người dùng tham gia sự kiện
                    </div>
                </div>

                <!-- su_kien_id -->
                <div class="col-md-6">
                    <label for="su_kien_id" class="form-label fw-semibold">
                        Sự kiện <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar-event'></i></span>
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('su_kien_id') ? 'is-invalid' : '' ?>" 
                                id="su_kien_id" name="su_kien_id" 
                                value="<?= old('su_kien_id', $su_kien_id) ?>" 
                                placeholder="ID sự kiện"
                                required min="1">
                        <?php if (isset($validation) && $validation->hasError('su_kien_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('su_kien_id') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập ID sự kiện</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        ID sự kiện người dùng tham gia
                    </div>
                </div>

                <!-- thoi_gian_diem_danh -->
                <div class="col-md-6">
                    <label for="thoi_gian_diem_danh" class="form-label fw-semibold">
                        Thời gian điểm danh
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-time'></i></span>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_diem_danh') ? 'is-invalid' : '' ?>" 
                                id="thoi_gian_diem_danh" name="thoi_gian_diem_danh" 
                                value="<?= old('thoi_gian_diem_danh', $thoi_gian_diem_danh) ?>">
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_diem_danh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_diem_danh') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Thời gian người dùng điểm danh tham gia sự kiện
                    </div>
                </div>

                <!-- phuong_thuc_diem_danh -->
                <div class="col-md-6">
                    <label for="phuong_thuc_diem_danh" class="form-label fw-semibold">
                        Phương thức điểm danh <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-qr-scan'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('phuong_thuc_diem_danh') ? 'is-invalid' : '' ?>" 
                               id="phuong_thuc_diem_danh" name="phuong_thuc_diem_danh" required>
                            <option value="qr_code" <?= old('phuong_thuc_diem_danh', $phuong_thuc_diem_danh) == 'qr_code' ? 'selected' : '' ?>>QR Code</option>
                            <option value="face_id" <?= old('phuong_thuc_diem_danh', $phuong_thuc_diem_danh) == 'face_id' ? 'selected' : '' ?>>Face ID</option>
                            <option value="manual" <?= old('phuong_thuc_diem_danh', $phuong_thuc_diem_danh) == 'manual' ? 'selected' : '' ?>>Thủ công</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('phuong_thuc_diem_danh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('phuong_thuc_diem_danh') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng chọn phương thức điểm danh</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ghi_chu -->
                <div class="col-md-12">
                    <label for="ghi_chu" class="form-label fw-semibold">Ghi chú</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-notepad'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('ghi_chu') ? 'is-invalid' : '' ?>" 
                                id="ghi_chu" name="ghi_chu" rows="3"
                                placeholder="Nhập ghi chú"><?= old('ghi_chu', $ghi_chu) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('ghi_chu')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ghi_chu') ?>
                            </div>
                        <?php endif; ?>
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
                            <option value="0" <?= old('status', $status) == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php endif; ?>
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
                    <a href="<?= site_url('thamgiasukien') ?>" class="btn btn-light">
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
    });
</script> 