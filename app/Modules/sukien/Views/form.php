<?php
/**
 * Form component for creating and updating sự kiện
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var SuKien $data SuKien entity data for editing (optional)
 * @var array $loaiSuKienList Danh sách loại sự kiện
 */

// Set default values if editing
$ten_su_kien = isset($data) ? $data->getTenSuKien() : '';
$mo_ta = isset($data) ? $data->getMoTa() : '';
$mo_ta_su_kien = isset($data) ? $data->getMoTaSuKien() : '';
$chi_tiet_su_kien = isset($data) ? $data->getChiTietSuKien() : '';
$su_kien_poster = isset($data) ? $data->getSuKienPoster() : null;
$thoi_gian_bat_dau = isset($data) ? ($data->getThoiGianBatDau() ? $data->getThoiGianBatDau()->format('Y-m-d H:i') : '') : '';
$thoi_gian_ket_thuc = isset($data) ? ($data->getThoiGianKetThuc() ? $data->getThoiGianKetThuc()->format('Y-m-d H:i') : '') : '';
$dia_diem = isset($data) ? $data->getDiaDiem() : '';
$dia_chi_cu_the = isset($data) ? $data->getDiaChiCuThe() : '';
$toa_do_gps = isset($data) ? $data->getToaDoGPS() : '';
$loai_su_kien_id = isset($data) ? $data->getLoaiSuKienId() : '';
$ma_qr_code = isset($data) ? $data->getMaQRCode() : '';
$status = isset($data) ? (string)$data->isActive() : '1';
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$ten_su_kien = old('ten_su_kien', $ten_su_kien);
$mo_ta = old('mo_ta', $mo_ta);
$mo_ta_su_kien = old('mo_ta_su_kien', $mo_ta_su_kien);
$chi_tiet_su_kien = old('chi_tiet_su_kien', $chi_tiet_su_kien);
$su_kien_poster = old('su_kien_poster', $su_kien_poster);
$thoi_gian_bat_dau = old('thoi_gian_bat_dau', $thoi_gian_bat_dau);
$thoi_gian_ket_thuc = old('thoi_gian_ket_thuc', $thoi_gian_ket_thuc);
$dia_diem = old('dia_diem', $dia_diem);
$dia_chi_cu_the = old('dia_chi_cu_the', $dia_chi_cu_the);
$toa_do_gps = old('toa_do_gps', $toa_do_gps);
$loai_su_kien_id = old('loai_su_kien_id', $loai_su_kien_id);
$ma_qr_code = old('ma_qr_code', $ma_qr_code);
$status = old('status', $status);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="su_kien_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="su_kien_id" value="0">
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
                <i class='bx bx-calendar-event text-primary me-2'></i>
                Thông tin sự kiện
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_su_kien -->
                <div class="col-md-12">
                    <label for="ten_su_kien" class="form-label fw-semibold">
                        Tên sự kiện <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar-event'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_su_kien') ? 'is-invalid' : '' ?>" 
                            id="ten_su_kien" name="ten_su_kien" 
                            value="<?= esc($ten_su_kien) ?>" 
                            placeholder="Nhập tên sự kiện"
                            required maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('ten_su_kien')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_su_kien') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên sự kiện</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên sự kiện tối đa 255 ký tự
                    </div>
                </div>

                <!-- su_kien_poster -->
                <div class="col-md-12">
                    <label for="su_kien_poster" class="form-label fw-semibold">
                        Poster sự kiện
                    </label>
                    
                    <!-- Preview poster -->
                    <div class="mb-3">
                        <?php if (!empty($su_kien_poster['url'])): ?>
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="position-relative">
                                        <img src="<?= esc($su_kien_poster['url']) ?>" 
                                             alt="Poster sự kiện" 
                                             class="img-thumbnail" 
                                             style="max-width: 200px; height: auto;">
                                        <?php if (!empty($su_kien_poster['width']) && !empty($su_kien_poster['height'])): ?>
                                        <div class="position-absolute bottom-0 end-0 bg-dark bg-opacity-75 text-white px-2 py-1 rounded">
                                            <?= esc($su_kien_poster['width']) ?>x<?= esc($su_kien_poster['height']) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <a href="<?= esc($su_kien_poster['url']) ?>" 
                                               target="_blank" 
                                               class="btn btn-sm btn-outline-primary">
                                                <i class='bx bx-link-external me-1'></i> Mở trong tab mới
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="clearPoster()">
                                                <i class='bx bx-trash me-1'></i> Xóa poster
                                            </button>
                                        </div>
                                        <div class="text-muted small">
                                            <i class='bx bx-link me-1'></i>
                                            <?= esc($su_kien_poster['url']) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Form nhập poster -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class='bx bx-image'></i></span>
                                        <input type="text" 
                                               class="form-control <?= isset($validation) && $validation->hasError('su_kien_poster.url') ? 'is-invalid' : '' ?>" 
                                               id="su_kien_poster_url" 
                                               name="su_kien_poster[url]" 
                                               value="<?= esc($su_kien_poster['url'] ?? '') ?>" 
                                               placeholder="URL của poster">
                                        <?php if (isset($validation) && $validation->hasError('su_kien_poster.url')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('su_kien_poster.url') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class='bx bx-width'></i></span>
                                        <input type="number" 
                                               class="form-control <?= isset($validation) && $validation->hasError('su_kien_poster.width') ? 'is-invalid' : '' ?>" 
                                               id="su_kien_poster_width" 
                                               name="su_kien_poster[width]" 
                                               value="<?= esc($su_kien_poster['width'] ?? '') ?>" 
                                               placeholder="Chiều rộng (px)">
                                        <?php if (isset($validation) && $validation->hasError('su_kien_poster.width')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('su_kien_poster.width') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class='bx bx-height'></i></span>
                                        <input type="number" 
                                               class="form-control <?= isset($validation) && $validation->hasError('su_kien_poster.height') ? 'is-invalid' : '' ?>" 
                                               id="su_kien_poster_height" 
                                               name="su_kien_poster[height]" 
                                               value="<?= esc($su_kien_poster['height'] ?? '') ?>" 
                                               placeholder="Chiều cao (px)">
                                        <?php if (isset($validation) && $validation->hasError('su_kien_poster.height')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('su_kien_poster.height') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-text text-muted mt-2">
                        <i class='bx bx-info-circle me-1'></i>
                        Nhập URL và kích thước của poster sự kiện. Poster nên có tỷ lệ 2:3 (800x1200px) để hiển thị tốt nhất.
                    </div>
                </div>

                <!-- mo_ta -->
                <div class="col-md-12">
                    <label for="mo_ta" class="form-label fw-semibold">
                        Mô tả ngắn
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-text'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('mo_ta') ? 'is-invalid' : '' ?>" 
                            id="mo_ta" name="mo_ta" 
                            placeholder="Nhập mô tả ngắn" rows="2"><?= esc($mo_ta) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('mo_ta')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mo_ta') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Mô tả ngắn về sự kiện
                    </div>
                </div>

                <!-- mo_ta_su_kien -->
                <div class="col-md-12">
                    <label for="mo_ta_su_kien" class="form-label fw-semibold">
                        Mô tả sự kiện
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-detail'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('mo_ta_su_kien') ? 'is-invalid' : '' ?>" 
                            id="mo_ta_su_kien" name="mo_ta_su_kien" 
                            placeholder="Nhập mô tả sự kiện" rows="3"><?= esc($mo_ta_su_kien) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('mo_ta_su_kien')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mo_ta_su_kien') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- chi_tiet_su_kien -->
                <div class="col-md-12">
                    <label for="chi_tiet_su_kien" class="form-label fw-semibold">
                        Chi tiết sự kiện
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-detail'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('chi_tiet_su_kien') ? 'is-invalid' : '' ?>" 
                            id="chi_tiet_su_kien" name="chi_tiet_su_kien" 
                            placeholder="Nhập chi tiết sự kiện" rows="4"><?= esc($chi_tiet_su_kien) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('chi_tiet_su_kien')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('chi_tiet_su_kien') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- thoi_gian_bat_dau -->
                <div class="col-md-6">
                    <label for="thoi_gian_bat_dau" class="form-label fw-semibold">
                        Thời gian bắt đầu <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar'></i></span>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_bat_dau') ? 'is-invalid' : '' ?>" 
                            id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" 
                            value="<?= esc($thoi_gian_bat_dau) ?>" 
                            required>
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_bat_dau')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_bat_dau') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập thời gian bắt đầu</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- thoi_gian_ket_thuc -->
                <div class="col-md-6">
                    <label for="thoi_gian_ket_thuc" class="form-label fw-semibold">
                        Thời gian kết thúc <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar'></i></span>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_ket_thuc') ? 'is-invalid' : '' ?>" 
                            id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc" 
                            value="<?= esc($thoi_gian_ket_thuc) ?>" 
                            required>
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_ket_thuc')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_ket_thuc') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập thời gian kết thúc</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- dia_diem -->
                <div class="col-md-6">
                    <label for="dia_diem" class="form-label fw-semibold">
                        Địa điểm
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-map'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('dia_diem') ? 'is-invalid' : '' ?>" 
                            id="dia_diem" name="dia_diem" 
                            value="<?= esc($dia_diem) ?>" 
                            placeholder="Nhập địa điểm"
                            maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('dia_diem')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dia_diem') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- dia_chi_cu_the -->
                <div class="col-md-6">
                    <label for="dia_chi_cu_the" class="form-label fw-semibold">
                        Địa chỉ cụ thể
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-map-pin'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('dia_chi_cu_the') ? 'is-invalid' : '' ?>" 
                            id="dia_chi_cu_the" name="dia_chi_cu_the" 
                            value="<?= esc($dia_chi_cu_the) ?>" 
                            placeholder="Nhập địa chỉ cụ thể"
                            maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('dia_chi_cu_the')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dia_chi_cu_the') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- toa_do_gps -->
                <div class="col-md-6">
                    <label for="toa_do_gps" class="form-label fw-semibold">
                        Tọa độ GPS
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-current-location'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('toa_do_gps') ? 'is-invalid' : '' ?>" 
                            id="toa_do_gps" name="toa_do_gps" 
                            value="<?= esc($toa_do_gps) ?>" 
                            placeholder="Ví dụ: 21.007414,105.851335"
                            maxlength="100">
                        <?php if (isset($validation) && $validation->hasError('toa_do_gps')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('toa_do_gps') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Nhập tọa độ định dạng: vĩ độ,kinh độ (ví dụ: 21.007414,105.851335)
                    </div>
                </div>

                <!-- loai_su_kien_id -->
                <div class="col-md-6">
                    <label for="loai_su_kien_id" class="form-label fw-semibold">
                        Loại sự kiện <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-category'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('loai_su_kien_id') ? 'is-invalid' : '' ?>" 
                               id="loai_su_kien_id" name="loai_su_kien_id" required>
                            <option value="">-- Chọn loại sự kiện --</option>
                            <?php if (isset($loaiSuKienList) && is_array($loaiSuKienList)): ?>
                                <?php foreach ($loaiSuKienList as $loai): ?>
                                    <option value="<?= $loai->getId() ?>" <?= $loai_su_kien_id == $loai->getId() ? 'selected' : '' ?>>
                                        <?= esc($loai->getTenLoaiSuKien()) ?>
                                        <?php if (!empty($loai->getMaLoaiSuKien())): ?>
                                            (<?= esc($loai->getMaLoaiSuKien()) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('loai_su_kien_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('loai_su_kien_id') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng chọn loại sự kiện</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ma_qr_code -->
                <div class="col-md-6">
                    <label for="ma_qr_code" class="form-label fw-semibold">
                        Mã QR Code
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-qr'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_qr_code') ? 'is-invalid' : '' ?>" 
                            id="ma_qr_code" name="ma_qr_code" 
                            value="<?= esc($ma_qr_code) ?>" 
                            placeholder="Nhập mã QR code"
                            maxlength="100">
                        <?php if (isset($validation) && $validation->hasError('ma_qr_code')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_qr_code') ?>
                            </div>
                        <?php endif; ?>
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
                        Trạng thái sự kiện trong hệ thống
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
        document.getElementById('ten_su_kien').focus();
    });

    // Hàm xóa poster
    function clearPoster() {
        if (confirm('Bạn có chắc chắn muốn xóa poster này?')) {
            document.getElementById('su_kien_poster_url').value = '';
            document.getElementById('su_kien_poster_width').value = '';
            document.getElementById('su_kien_poster_height').value = '';
            location.reload();
        }
    }
</script> 