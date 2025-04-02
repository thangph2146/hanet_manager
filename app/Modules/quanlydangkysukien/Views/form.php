<?php
/**
 * Form component for creating and updating đăng ký sự kiện
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var DangKySuKien $data DangKySuKien entity data for editing (optional)
 * @var array $suKienList List of all events
 */

// Set default values if editing
$su_kien_id = isset($data) ? $data->getSuKienId() : '';
$email = isset($data) ? $data->getEmail() : '';
$ho_ten = isset($data) ? $data->getHoTen() : '';
$dien_thoai = isset($data) ? $data->getDienThoai() : '';
$loai_nguoi_dang_ky = isset($data) ? $data->getLoaiNguoiDangKy() : 'khach';
$hinh_thuc_tham_gia = isset($data) ? $data->getHinhThucThamGia() : 'offline';
$status = isset($data) ? $data->getStatus() : 0;
$attendance_status = isset($data) ? $data->getAttendanceStatus() : 'not_attended';
$attendance_minutes = isset($data) ? $data->getAttendanceMinutes() : 0;
$diem_danh_bang = isset($data) ? $data->getDiemDanhBang() : 'none';
$ly_do_tham_du = isset($data) ? $data->getLyDoThamDu() : '';
$thong_tin_dang_ky = isset($data) ? $data->getThongTinDangKy() : [];
$noi_dung_gop_y = isset($data) ? $data->getNoiDungGopY() : '';
$nguon_gioi_thieu = isset($data) ? $data->getNguonGioiThieu() : '';
$don_vi_to_chuc = isset($data) ? $data->getDonViToChuc() : '';
$face_image_path = isset($data) ? $data->getFaceImagePath() : '';
$face_verified = isset($data) ? $data->isFaceVerified() : false;
$da_check_in = isset($data) ? $data->isDaCheckIn() : false;
$da_check_out = isset($data) ? $data->isDaCheckOut() : false;

// Đảm bảo định dạng thời gian theo chuẩn ISO 8601 cho input datetime-local
$ngay_dang_ky = '';
if (isset($data) && $data->getNgayDangKy()) {
    $ngay_dang_ky = $data->getNgayDangKy()->format('Y-m-d\TH:i');
}

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$su_kien_id = old('su_kien_id', $su_kien_id);
$email = old('email', $email);
$ho_ten = old('ho_ten', $ho_ten);
$dien_thoai = old('dien_thoai', $dien_thoai);
$loai_nguoi_dang_ky = old('loai_nguoi_dang_ky', $loai_nguoi_dang_ky);
$hinh_thuc_tham_gia = old('hinh_thuc_tham_gia', $hinh_thuc_tham_gia);
$status = old('status', $status);
$attendance_status = old('attendance_status', $attendance_status);
$attendance_minutes = old('attendance_minutes', $attendance_minutes);
$diem_danh_bang = old('diem_danh_bang', $diem_danh_bang);
$ly_do_tham_du = old('ly_do_tham_du', $ly_do_tham_du);
$thong_tin_dang_ky = old('thong_tin_dang_ky', $thong_tin_dang_ky);
$ngay_dang_ky = old('ngay_dang_ky', $ngay_dang_ky);
$noi_dung_gop_y = old('noi_dung_gop_y', $noi_dung_gop_y);
$nguon_gioi_thieu = old('nguon_gioi_thieu', $nguon_gioi_thieu);
$don_vi_to_chuc = old('don_vi_to_chuc', $don_vi_to_chuc);
$face_verified = old('face_verified', $face_verified);
$da_check_in = old('da_check_in', $da_check_in);
$da_check_out = old('da_check_out', $da_check_out);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate>
    <?= csrf_field() ?>
    
    <?php if (isset($data) && $data->getId()): ?>
        <input type="hidden" name="dangky_sukien_id" value="<?= $data->getId() ?>">
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
                        <?php 
                        foreach ($errors as $field => $error): 
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
                <i class='bx bx-user text-primary me-2'></i>
                Thông tin đăng ký sự kiện
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
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

                <!-- email -->
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" 
                           class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                           id="email" name="email"
                           value="<?= esc($email) ?>"
                           required>
                    <?php if (isset($validation) && $validation->hasError('email')): ?>
                        <div class="invalid-feedback">
                            <?php 
                            $emailError = $validation->getError('email');
                            if (strpos($emailError, 'unique') !== false) {
                                echo 'Email này đã được đăng ký cho sự kiện này.';
                            } else {
                                echo $emailError;
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
                           required>
                    <?php if (isset($validation) && $validation->hasError('ho_ten')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('ho_ten') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- dien_thoai -->
                <div class="col-md-6">
                    <label for="dien_thoai" class="form-label fw-semibold">
                        Điện thoại
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($validation) && $validation->hasError('dien_thoai') ? 'is-invalid' : '' ?>" 
                           id="dien_thoai" name="dien_thoai"
                           value="<?= esc($dien_thoai) ?>">
                    <?php if (isset($validation) && $validation->hasError('dien_thoai')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('dien_thoai') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- loai_nguoi_dang_ky -->
                <div class="col-md-6">
                    <label for="loai_nguoi_dang_ky" class="form-label fw-semibold">
                        Loại người đăng ký <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('loai_nguoi_dang_ky') ? 'is-invalid' : '' ?>" 
                            id="loai_nguoi_dang_ky" name="loai_nguoi_dang_ky" required>
                        <option value="khach" <?= $loai_nguoi_dang_ky == 'khach' ? 'selected' : '' ?>>Khách mời</option>
                        <option value="sinh_vien" <?= $loai_nguoi_dang_ky == 'sinh_vien' ? 'selected' : '' ?>>Sinh viên</option>
                        <option value="giang_vien" <?= $loai_nguoi_dang_ky == 'giang_vien' ? 'selected' : '' ?>>Giảng viên</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('loai_nguoi_dang_ky')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('loai_nguoi_dang_ky') ?>
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
                        <option value="offline" <?= $hinh_thuc_tham_gia == 'offline' ? 'selected' : '' ?>>Trực tiếp</option>
                        <option value="online" <?= $hinh_thuc_tham_gia == 'online' ? 'selected' : '' ?>>Trực tuyến</option>
                        <option value="hybrid" <?= $hinh_thuc_tham_gia == 'hybrid' ? 'selected' : '' ?>>Kết hợp</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('hinh_thuc_tham_gia')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('hinh_thuc_tham_gia') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ngay_dang_ky -->
                <div class="col-md-6">
                    <label for="ngay_dang_ky" class="form-label fw-semibold">
                        Ngày đăng ký
                    </label>
                    <input type="datetime-local" 
                           class="form-control <?= isset($validation) && $validation->hasError('ngay_dang_ky') ? 'is-invalid' : '' ?>" 
                           id="ngay_dang_ky" name="ngay_dang_ky"
                           value="<?= esc($ngay_dang_ky) ?>"
                           step="60">
                    <?php if (isset($validation) && $validation->hasError('ngay_dang_ky')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('ngay_dang_ky') ?>
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
                        <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Chờ xác nhận</option>
                        <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Đã xác nhận</option>
                        <option value="-1" <?= $status == -1 ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('status')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('status') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- attendance_status -->
                <div class="col-md-6">
                    <label for="attendance_status" class="form-label fw-semibold">
                        Trạng thái tham dự
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('attendance_status') ? 'is-invalid' : '' ?>" 
                            id="attendance_status" name="attendance_status">
                        <option value="not_attended" <?= $attendance_status == 'not_attended' ? 'selected' : '' ?>>Chưa tham dự</option>
                        <option value="partial" <?= $attendance_status == 'partial' ? 'selected' : '' ?>>Tham dự một phần</option>
                        <option value="full" <?= $attendance_status == 'full' ? 'selected' : '' ?>>Tham dự đầy đủ</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('attendance_status')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('attendance_status') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- attendance_minutes -->
                <div class="col-md-6">
                    <label for="attendance_minutes" class="form-label fw-semibold">
                        Số phút tham dự
                    </label>
                    <input type="number" 
                           class="form-control <?= isset($validation) && $validation->hasError('attendance_minutes') ? 'is-invalid' : '' ?>" 
                           id="attendance_minutes" name="attendance_minutes"
                           value="<?= esc($attendance_minutes) ?>"
                           min="0">
                    <?php if (isset($validation) && $validation->hasError('attendance_minutes')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('attendance_minutes') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- diem_danh_bang -->
                <div class="col-md-6">
                    <label for="diem_danh_bang" class="form-label fw-semibold">
                        Phương thức điểm danh
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('diem_danh_bang') ? 'is-invalid' : '' ?>" 
                            id="diem_danh_bang" name="diem_danh_bang">
                        <option value="none" <?= $diem_danh_bang == 'none' ? 'selected' : '' ?>>Chưa điểm danh</option>
                        <option value="qr_code" <?= $diem_danh_bang == 'qr_code' ? 'selected' : '' ?>>Mã QR</option>
                        <option value="face_id" <?= $diem_danh_bang == 'face_id' ? 'selected' : '' ?>>Nhận diện khuôn mặt</option>
                        <option value="manual" <?= $diem_danh_bang == 'manual' ? 'selected' : '' ?>>Thủ công</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('diem_danh_bang')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('diem_danh_bang') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- don_vi_to_chuc -->
                <div class="col-md-6">
                    <label for="don_vi_to_chuc" class="form-label fw-semibold">
                        Đơn vị tổ chức
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($validation) && $validation->hasError('don_vi_to_chuc') ? 'is-invalid' : '' ?>" 
                           id="don_vi_to_chuc" name="don_vi_to_chuc"
                           value="<?= esc($don_vi_to_chuc) ?>">
                    <?php if (isset($validation) && $validation->hasError('don_vi_to_chuc')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('don_vi_to_chuc') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- nguon_gioi_thieu -->
                <div class="col-md-6">
                    <label for="nguon_gioi_thieu" class="form-label fw-semibold">
                        Nguồn giới thiệu
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($validation) && $validation->hasError('nguon_gioi_thieu') ? 'is-invalid' : '' ?>" 
                           id="nguon_gioi_thieu" name="nguon_gioi_thieu"
                           value="<?= esc($nguon_gioi_thieu) ?>">
                    <?php if (isset($validation) && $validation->hasError('nguon_gioi_thieu')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('nguon_gioi_thieu') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ly_do_tham_du -->
                <div class="col-md-12">
                    <label for="ly_do_tham_du" class="form-label fw-semibold">
                        Lý do tham dự
                    </label>
                    <textarea class="form-control <?= isset($validation) && $validation->hasError('ly_do_tham_du') ? 'is-invalid' : '' ?>" 
                              id="ly_do_tham_du" name="ly_do_tham_du"
                              rows="3"><?= esc($ly_do_tham_du) ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('ly_do_tham_du')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('ly_do_tham_du') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- noi_dung_gop_y -->
                <div class="col-md-12">
                    <label for="noi_dung_gop_y" class="form-label fw-semibold">
                        Nội dung góp ý
                    </label>
                    <textarea class="form-control <?= isset($validation) && $validation->hasError('noi_dung_gop_y') ? 'is-invalid' : '' ?>" 
                              id="noi_dung_gop_y" name="noi_dung_gop_y"
                              rows="3"><?= esc($noi_dung_gop_y) ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('noi_dung_gop_y')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('noi_dung_gop_y') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- face_verified -->
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input <?= isset($validation) && $validation->hasError('face_verified') ? 'is-invalid' : '' ?>" 
                               id="face_verified" name="face_verified"
                               <?= $face_verified ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="face_verified">
                            Đã xác thực khuôn mặt
                        </label>
                    </div>
                </div>

                <!-- da_check_in -->
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input <?= isset($validation) && $validation->hasError('da_check_in') ? 'is-invalid' : '' ?>" 
                               id="da_check_in" name="da_check_in"
                               <?= $da_check_in ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="da_check_in">
                            Đã check-in
                        </label>
                    </div>
                </div>

                <!-- da_check_out -->
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" 
                               class="form-check-input <?= isset($validation) && $validation->hasError('da_check_out') ? 'is-invalid' : '' ?>" 
                               id="da_check_out" name="da_check_out"
                               <?= $da_check_out ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="da_check_out">
                            Đã check-out
                        </label>
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