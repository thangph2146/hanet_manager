<?php
/**
 * Form component for creating and updating check-out sự kiện
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var CheckOutSuKien $data CheckOutSuKien entity data for editing (optional)
 * @var array $suKienList List of all events
 */

// Đảm bảo biến module_name luôn có giá trị
$module_name = $module_name ?? 'checkoutsukien';

// Set default values if editing
$checkout_sukien_id = $id ?? (isset($record) ? $record->getId() : (isset($data) ? $data->getId() : 0));
$su_kien_id = isset($data) ? $data->getSuKienId() : '';
$email = isset($data) ? $data->getEmail() : '';
$ho_ten = isset($data) ? $data->getHoTen() : '';
$dangky_sukien_id = isset($data) ? $data->getDangKySuKienId() : '';
$checkin_sukien_id = isset($data) ? $data->getCheckInSuKienId() : '';

// Đảm bảo định dạng thời gian theo chuẩn ISO 8601 cho input datetime-local
$thoi_gian_check_out = '';
if (isset($data) && $data->getThoiGianCheckOut()) {
    $thoi_gian_check_out = $data->getThoiGianCheckOut()->format('Y-m-d\TH:i');
}

$checkout_type = isset($data) ? $data->getCheckoutType() : 'manual';
$face_image_path = isset($data) ? $data->getFaceImagePath() : '';
$face_match_score = isset($data) ? $data->getFaceMatchScore() : '';
$face_verified = isset($data) ? $data->isFaceVerified() : false;
$ma_xac_nhan = isset($data) ? $data->getMaXacNhan() : '';
$status = isset($data) ? $data->getStatus() : 1;
$location_data = isset($data) ? $data->getLocationData() : '';
$device_info = isset($data) ? $data->getDeviceInfo() : '';
$attendance_duration_minutes = isset($data) ? $data->getAttendanceDurationMinutes() : '';
$hinh_thuc_tham_gia = isset($data) ? $data->getHinhThucThamGia() : 'offline';
$ip_address = isset($data) ? $data->getIpAddress() : '';
$thong_tin_bo_sung = isset($data) ? json_encode($data->getThongTinBoSung()) : '{}';
$ghi_chu = isset($data) ? $data->getGhiChu() : '';
$feedback = isset($data) ? $data->getFeedback() : '';
$danh_gia = isset($data) ? $data->getDanhGia() : '';
$noi_dung_danh_gia = isset($data) ? $data->getNoiDungDanhGia() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$su_kien_id = old('su_kien_id', $su_kien_id);
$email = old('email', $email);
$ho_ten = old('ho_ten', $ho_ten);
$dangky_sukien_id = old('dangky_sukien_id', $dangky_sukien_id);
$checkin_sukien_id = old('checkin_sukien_id', $checkin_sukien_id);
$thoi_gian_check_out = old('thoi_gian_check_out', $thoi_gian_check_out);
$checkout_type = old('checkout_type', $checkout_type);
$face_verified = old('face_verified', $face_verified);
$ma_xac_nhan = old('ma_xac_nhan', $ma_xac_nhan);
$status = old('status', $status);
$attendance_duration_minutes = old('attendance_duration_minutes', $attendance_duration_minutes);
$hinh_thuc_tham_gia = old('hinh_thuc_tham_gia', $hinh_thuc_tham_gia);
$ghi_chu = old('ghi_chu', $ghi_chu);
$feedback = old('feedback', $feedback);
$danh_gia = old('danh_gia', $danh_gia);
$noi_dung_danh_gia = old('noi_dung_danh_gia', $noi_dung_danh_gia);

// Kiểm tra biến validation tồn tại
$validation = $validation ?? [];
$errorClass = 'is-invalid';
$feedbackClass = 'invalid-feedback';

// Lấy giá trị từ dữ liệu record hoặc post data
function getValue($field, $record, $post) {
    if (isset($post[$field])) {
        return $post[$field];
    } elseif (isset($record) && method_exists($record, 'get' . ucfirst($field))) {
        $method = 'get' . ucfirst($field);
        return $record->$method();
    } elseif (isset($record) && isset($record->$field)) {
        return $record->$field;
    }
    return '';
}

// Lấy dữ liệu cho các trường
$id = $id ?? (isset($record) ? $record->getId() : 0);
$sukien_id = getValue('SuKienId', $record, $post ?? []);
$ho_ten = getValue('HoTen', $record, $post ?? []);
$email = getValue('Email', $record, $post ?? []);
$dangky_sukien_id = getValue('DangKySuKienId', $record, $post ?? []);
$checkin_sukien_id = getValue('CheckInSuKienId', $record, $post ?? []);
$thoi_gian_check_out = getValue('ThoiGianCheckOut', $record, $post ?? []);
if (is_object($thoi_gian_check_out)) {
    $thoi_gian_check_out = $thoi_gian_check_out->format('Y-m-d\TH:i');
}
$checkout_type = getValue('CheckoutType', $record, $post ?? []);
$face_image_path = getValue('FaceImagePath', $record, $post ?? []);
$face_match_score = getValue('FaceMatchScore', $record, $post ?? []);
$face_verified = getValue('FaceVerified', $record, $post ?? []) ? 1 : 0;
$ma_xac_nhan = getValue('MaXacNhan', $record, $post ?? []);
$danh_gia = getValue('DanhGia', $record, $post ?? []);
$noi_dung_danh_gia = getValue('NoiDungDanhGia', $record, $post ?? []);
$attendance_duration_minutes = getValue('AttendanceDurationMinutes', $record, $post ?? []);
$hinh_thuc_tham_gia = getValue('HinhThucThamGia', $record, $post ?? []) ?: 'offline';
$ghi_chu = getValue('GhiChu', $record, $post ?? []);
$feedback = getValue('Feedback', $record, $post ?? []);
$status = getValue('Status', $record, $post ?? []) ?? 1;
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <?php if ($checkout_sukien_id): ?>
        <input type="hidden" name="checkout_sukien_id" value="<?= $checkout_sukien_id ?>">
    <?php else: ?>
        <input type="hidden" name="checkout_sukien_id" value="0">
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
    <?php if (isset($validation) && $validation->getErrors()): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-start border-danger border-4" role="alert">
            <div class="d-flex">
                <div class="me-3">
                    <i class='bx bx-error-circle fs-3'></i>
                </div>
                <div>
                    <strong>Lỗi nhập liệu:</strong>
                    <ul class="mb-0 ps-3 mt-1">
                        <?php 
                        foreach ($validation->getErrors() as $field => $error): 
                            // Sử dụng hàm ucfirst và str_replace để tạo label tự động, không phụ thuộc vào validation
                            $label = ucfirst(str_replace('_', ' ', $field));
                        ?>
                            <li>
                                <strong><?= $label ?>:</strong> 
                                <?= is_array($error) ? implode(', ', $error) : $error ?>
                            </li>
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
                <i class='bx bx-log-out text-primary me-2'></i>
                Thông tin check-out sự kiện
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- su_kien_id -->
                <div class="col-md-6">
                    <input type="hidden" name="su_kien_id" value="<?= $su_kien_id ?>">
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
                            <?php 
                            $suKienError = $validation->getError('su_kien_id');
                            if (strpos($suKienError, 'is_not_unique') !== false) {
                                echo 'Sự kiện không tồn tại trong hệ thống';
                            } else {
                                echo $suKienError;
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ho_ten -->
                <div class="col-md-6">
                    <label for="ho_ten" class="form-label fw-semibold">
                        Họ tên <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($validation) && $validation->hasError('ho_ten') ? 'is-invalid' : '' ?>" 
                           id="ho_ten" name="ho_ten"
                           value="<?= esc($ho_ten) ?>"
                           placeholder="Nhập họ tên" required>
                    <?php if (isset($validation) && $validation->hasError('ho_ten')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('ho_ten') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- email -->
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                           id="email" name="email"
                           value="<?= esc($email) ?>"
                           placeholder="Nhập email" required>
                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('email') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- thoi_gian_check_out -->
                <div class="col-md-6">
                    <label for="thoi_gian_check_out" class="form-label fw-semibold">
                        Thời gian check-out <span class="text-danger">*</span>
                    </label>
                    <input type="datetime-local" 
                           class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_check_out') ? 'is-invalid' : '' ?>" 
                           id="thoi_gian_check_out" name="thoi_gian_check_out"
                           value="<?= esc($thoi_gian_check_out) ?>"
                           step="60" required>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Định dạng: YYYY-MM-DDThh:mm theo giờ 24h (VD: 2023-12-31T14:30)
                    </div>
                    <?php if (isset($validation) && $validation->hasError('thoi_gian_check_out')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('thoi_gian_check_out') ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- checkout_type -->
                <div class="col-md-6">
                    <label for="checkout_type" class="form-label fw-semibold">
                        Loại check-out <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('checkout_type') ? 'is-invalid' : '' ?>" 
                            id="checkout_type" name="checkout_type" required>
                        <option value="face_id" <?= $checkout_type == 'face_id' ? 'selected' : '' ?>>Nhận diện khuôn mặt</option>
                        <option value="manual" <?= $checkout_type == 'manual' ? 'selected' : '' ?>>Thủ công</option>
                        <option value="qr_code" <?= $checkout_type == 'qr_code' ? 'selected' : '' ?>>Mã QR</option>
                        <option value="auto" <?= $checkout_type == 'auto' ? 'selected' : '' ?>>Tự động</option>
                        <option value="online" <?= $checkout_type == 'online' ? 'selected' : '' ?>>Trực tuyến</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('checkout_type')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('checkout_type') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- hinh_thuc_tham_gia -->
                <div class="col-md-6">
                    <label for="hinh_thuc_tham_gia" class="form-label fw-semibold">
                        Hình thức tham gia <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('hinh_thuc_tham_gia') ? 'is-invalid' : '' ?>" 
                            id="hinh_thuc_tham_gia" name="hinh_thuc_tham_gia" required>
                        <option value="offline" <?= $hinh_thuc_tham_gia == 'offline' ? 'selected' : '' ?>>Trực tiếp (Offline)</option>
                        <option value="online" <?= $hinh_thuc_tham_gia == 'online' ? 'selected' : '' ?>>Trực tuyến (Online)</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('hinh_thuc_tham_gia')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('hinh_thuc_tham_gia') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- face_verified -->
                <div class="col-md-6">
                    <label for="face_verified" class="form-label fw-semibold">
                        Xác minh khuôn mặt
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('face_verified') ? 'is-invalid' : '' ?>" 
                            id="face_verified" name="face_verified">
                        <option value="1" <?= $face_verified ? 'selected' : '' ?>>Đã xác minh</option>
                        <option value="0" <?= !$face_verified ? 'selected' : '' ?>>Chưa xác minh</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('face_verified')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('face_verified') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- face_image_path -->
                <div class="col-md-6">
                    <label for="face_image" class="form-label fw-semibold">
                        Ảnh khuôn mặt
                    </label>
                    <input type="file" 
                           class="form-control <?= isset($validation) && $validation->hasError('face_image_path') ? 'is-invalid' : '' ?>" 
                           id="face_image" name="face_image"
                           accept="image/*">
                    <?php if (isset($validation) && $validation->hasError('face_image_path')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('face_image_path') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($face_image_path)): ?>
                        <div class="mt-2">
                            <small class="text-muted">Ảnh hiện tại: <?= esc($face_image_path) ?></small>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- face_match_score -->
                <div class="col-md-6">
                    <label for="face_match_score" class="form-label fw-semibold">
                        Điểm khớp khuôn mặt
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($validation) && $validation->hasError('face_match_score') ? 'is-invalid' : '' ?>" 
                           id="face_match_score" name="face_match_score"
                           value="<?= esc($face_match_score) ?>"
                           placeholder="Nhập điểm khớp (0-1)"
                           pattern="^(0(\.\d+)?|1(\.0+)?)$">
                    <?php if (isset($validation) && $validation->hasError('face_match_score')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('face_match_score') ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Nhập giá trị từ 0 đến 1, ví dụ: 0.85 hoặc 0,85
                    </div>
                </div>

                <!-- ma_xac_nhan -->
                <div class="col-md-6">
                    <label for="ma_xac_nhan" class="form-label fw-semibold">
                        Mã xác nhận
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($validation) && $validation->hasError('ma_xac_nhan') ? 'is-invalid' : '' ?>" 
                           id="ma_xac_nhan" name="ma_xac_nhan"
                           value="<?= esc($ma_xac_nhan) ?>"
                           placeholder="Nhập mã xác nhận">
                    <?php if (isset($validation) && $validation->hasError('ma_xac_nhan')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('ma_xac_nhan') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- attendance_duration_minutes -->
                <div class="col-md-6">
                    <label for="attendance_duration_minutes" class="form-label fw-semibold">
                        Thời lượng tham dự (phút)
                    </label>
                    <input type="number" 
                           class="form-control <?= isset($validation) && $validation->hasError('attendance_duration_minutes') ? 'is-invalid' : '' ?>" 
                           id="attendance_duration_minutes" name="attendance_duration_minutes"
                           value="<?= esc($attendance_duration_minutes) ?>"
                           placeholder="Nhập thời lượng tham dự">
                    <?php if (isset($validation) && $validation->hasError('attendance_duration_minutes')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('attendance_duration_minutes') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- danh_gia -->
                <div class="col-md-6">
                    <label for="danh_gia" class="form-label fw-semibold">
                        Đánh giá (1-5 sao)
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('danh_gia') ? 'is-invalid' : '' ?>" 
                            id="danh_gia" name="danh_gia">
                        <option value="">Chọn đánh giá</option>
                        <option value="5" <?= $danh_gia == 5 || $danh_gia == '5' ? 'selected' : '' ?>>★★★★★ (5 sao)</option>
                        <option value="4" <?= $danh_gia == 4 || $danh_gia == '4' ? 'selected' : '' ?>>★★★★☆ (4 sao)</option>
                        <option value="3" <?= $danh_gia == 3 || $danh_gia == '3' ? 'selected' : '' ?>>★★★☆☆ (3 sao)</option>
                        <option value="2" <?= $danh_gia == 2 || $danh_gia == '2' ? 'selected' : '' ?>>★★☆☆☆ (2 sao)</option>
                        <option value="1" <?= $danh_gia == 1 || $danh_gia == '1' ? 'selected' : '' ?>>★☆☆☆☆ (1 sao)</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('danh_gia')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('danh_gia') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- status -->
                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">
                        Trạng thái <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                            id="status" name="status" required>
                        <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Vô hiệu</option>
                        <option value="2" <?= $status == 2 ? 'selected' : '' ?>>Đang xử lý</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('status')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('status') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- feedback -->
                <div class="col-md-12">
                    <label for="feedback" class="form-label fw-semibold">
                        Phản hồi
                    </label>
                    <textarea class="form-control <?= isset($validation) && $validation->hasError('feedback') ? 'is-invalid' : '' ?>" 
                              id="feedback" name="feedback"
                              rows="3"
                              placeholder="Nhập phản hồi"><?= esc($feedback) ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('feedback')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('feedback') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- noi_dung_danh_gia -->
                <div class="col-md-12">
                    <label for="noi_dung_danh_gia" class="form-label fw-semibold">
                        Nội dung đánh giá
                    </label>
                    <textarea class="form-control <?= isset($validation) && $validation->hasError('noi_dung_danh_gia') ? 'is-invalid' : '' ?>" 
                              id="noi_dung_danh_gia" name="noi_dung_danh_gia"
                              rows="3"
                              placeholder="Nhập nội dung đánh giá"><?= esc($noi_dung_danh_gia) ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('noi_dung_danh_gia')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('noi_dung_danh_gia') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ghi_chu -->
                <div class="col-md-12">
                    <label for="ghi_chu" class="form-label fw-semibold">
                        Ghi chú
                    </label>
                    <textarea class="form-control <?= isset($validation) && $validation->hasError('ghi_chu') ? 'is-invalid' : '' ?>" 
                              id="ghi_chu" name="ghi_chu"
                              rows="3"
                              placeholder="Nhập ghi chú"><?= esc($ghi_chu) ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('ghi_chu')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('ghi_chu') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- dangky_sukien_id and checkin_sukien_id -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="dangky_sukien_id" class="form-label fw-semibold">
                            ID đăng ký sự kiện
                        </label>
                        <input type="number" 
                               class="form-control <?= isset($validation) && $validation->hasError('dangky_sukien_id') ? 'is-invalid' : '' ?>" 
                               id="dangky_sukien_id" name="dangky_sukien_id"
                               value="<?= esc($dangky_sukien_id) ?>"
                               placeholder="Nhập ID đăng ký sự kiện (nếu có)" min="1" step="1">
                        <?php if (isset($validation) && $validation->hasError('dangky_sukien_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dangky_sukien_id') ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-text">
                            Để trống nếu không có thông tin đăng ký. Chỉ nhập số nguyên dương.
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="checkin_sukien_id" class="form-label fw-semibold">
                            ID check-in sự kiện
                        </label>
                        <input type="number" 
                               class="form-control <?= isset($validation) && $validation->hasError('checkin_sukien_id') ? 'is-invalid' : '' ?>" 
                               id="checkin_sukien_id" name="checkin_sukien_id"
                               value="<?= esc($checkin_sukien_id) ?>"
                               placeholder="Nhập ID check-in sự kiện (nếu có)" min="1" step="1">
                        <?php if (isset($validation) && $validation->hasError('checkin_sukien_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('checkin_sukien_id') ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-text">
                            Để trống nếu không có thông tin check-in. Chỉ nhập số nguyên dương.
                        </div>
                    </div>
                </div>

                <!-- Thêm trường inputs ẩn cho các trường khác -->
                <input type="hidden" name="location_data" value="<?= esc($location_data) ?>">
                <input type="hidden" name="device_info" value="<?= esc($device_info) ?>">
                <input type="hidden" name="ip_address" value="<?= esc($ip_address) ?>">
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