<?php
// Khởi tạo các biến từ dữ liệu đầu vào
if (is_object($data)) {
    $dangky_sukien_id = $data->getId() ?? '';
    $su_kien_id = $data->getSuKienId() ?? '';
    $email = $data->getEmail() ?? '';
    $ho_ten = $data->getHoTen() ?? '';
    $dien_thoai = $data->getDienThoai() ?? '';
    $loai_nguoi_dang_ky = $data->getLoaiNguoiDangKy() ?? 'khach';
    $status = $data->getStatus() ?? 0;
    $noi_dung_gop_y = $data->getNoiDungGopY() ?? '';
    $nguon_gioi_thieu = $data->getNguonGioiThieu() ?? '';
    $don_vi_to_chuc = $data->getDonViToChuc() ?? '';
    $hinh_thuc_tham_gia = $data->getHinhThucThamGia() ?? 'offline';
    $ly_do_tham_du = $data->getLyDoThamDu() ?? '';
    $ly_do_huy = $data->getLyDoHuy() ?? '';
    $face_image_path = $data->getFaceImagePath() ?? '';
    $face_verified = $data->isFaceVerified() ?? false;
    $da_check_in = $data->isDaCheckIn() ?? false;
    $da_check_out = $data->isDaCheckOut() ?? false;
    $checkin_sukien_id = $data->getCheckinSuKienId() ?? null;
    $checkout_sukien_id = $data->getCheckoutSuKienId() ?? null;
    $attendance_status = $data->getAttendanceStatus() ?? 'not_attended';
    $attendance_minutes = $data->getAttendanceMinutes() ?? 0;
    $diem_danh_bang = $data->getDiemDanhBang() ?? 'none';
    $thong_tin_dang_ky = $data->getThongTinDangKy() ?? [];
    $ngay_dang_ky = $data->getNgayDangKy() ? $data->getNgayDangKy()->format('Y-m-d H:i:s') : '';
    $thoi_gian_duyet = $data->getThoiGianDuyet() ? $data->getThoiGianDuyet()->format('Y-m-d H:i:s') : '';
    $thoi_gian_huy = $data->getThoiGianHuy() ? $data->getThoiGianHuy()->format('Y-m-d H:i:s') : '';
} else {
    $dangky_sukien_id = '';
    $su_kien_id = '';
    $email = '';
    $ho_ten = '';
    $dien_thoai = '';
    $loai_nguoi_dang_ky = 'khach';
    $status = 0;
    $noi_dung_gop_y = '';
    $nguon_gioi_thieu = '';
    $don_vi_to_chuc = '';
    $hinh_thuc_tham_gia = 'offline';
    $ly_do_tham_du = '';
    $ly_do_huy = '';
    $face_image_path = '';
    $face_verified = false;
    $da_check_in = false;
    $da_check_out = false;
    $checkin_sukien_id = null;
    $checkout_sukien_id = null;
    $attendance_status = 'not_attended';
    $attendance_minutes = 0;
    $diem_danh_bang = 'none';
    $thong_tin_dang_ky = [];
    $ngay_dang_ky = '';
    $thoi_gian_duyet = '';
    $thoi_gian_huy = '';
}

$errors = session()->getFlashdata('errors') ?? [];
?>

<style>
.form-card {
    transition: all 0.3s ease;
    border: none;
}

.form-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    transform: translateY(-2px);
}

.card-header {
    border-bottom: 0;
    border-top-left-radius: 8px !important;
    border-top-right-radius: 8px !important;
    padding: 1rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

.form-control, .form-select {
    transition: all 0.2s ease;
    padding: 0.6rem 1rem;
    border-radius: 6px;
}

.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-control-lg, .form-select-lg {
    height: calc(2.5rem + 2px);
}

.btn {
    border-radius: 6px;
    padding: 0.6rem 1.5rem;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.input-group .btn {
    padding-top: 0.6rem;
    padding-bottom: 0.6rem;
}

.face-verification-section {
    transition: all 0.5s ease;
}

.form-switch .form-check-input {
    height: 1.5rem;
    width: 3rem !important;
    cursor: pointer;
}

.form-label i {
    transition: all 0.3s ease;
}

.form-label:hover i {
    transform: scale(1.2);
}
</style>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-info-circle me-2"></i> Thông tin đăng ký sự kiện
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="su_kien_id" class="form-label fw-bold">
                            <i class="fas fa-tag text-primary me-1"></i> Sự kiện <span class="text-danger">*</span>
                        </label>
                        <select name="su_kien_id" class="form-select form-select-lg <?= isset($validation) && $validation->hasError('su_kien_id') ? 'is-invalid' : '' ?>" 
                               id="su_kien_id" required>
                            <option value="">Chọn sự kiện</option>
                            <?php foreach ($suKiens as $suKien): ?>
                                <option value="<?= $suKien->getId() ?>" <?= old('su_kien_id', $su_kien_id) == $suKien->getId() ? 'selected' : '' ?>>
                                    <?= esc($suKien->getTenSuKien()) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('su_kien_id')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('su_kien_id') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label fw-bold">
                            <i class="fas fa-envelope text-primary me-1"></i> Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                               id="email" name="email" value="<?= $email ?>" placeholder="Nhập email" required>
                        <?php if (isset($validation) && $validation->hasError('email')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('email') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="ho_ten" class="form-label fw-bold">
                            <i class="fas fa-user text-primary me-1"></i> Họ tên <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ho_ten') ? 'is-invalid' : '' ?>" 
                               id="ho_ten" name="ho_ten" value="<?= $ho_ten ?>" placeholder="Nhập họ tên" required>
                        <?php if (isset($validation) && $validation->hasError('ho_ten')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ho_ten') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="dien_thoai" class="form-label fw-bold">
                            <i class="fas fa-phone text-primary me-1"></i> Điện thoại
                        </label>
                        <input type="tel" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('dien_thoai') ? 'is-invalid' : '' ?>" 
                               id="dien_thoai" name="dien_thoai" value="<?= $dien_thoai ?>" placeholder="Nhập số điện thoại">
                        <?php if (isset($validation) && $validation->hasError('dien_thoai')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dien_thoai') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="loai_nguoi_dang_ky" class="form-label fw-bold">
                            <i class="fas fa-user text-primary me-1"></i> Loại người đăng ký <span class="text-danger">*</span>
                        </label>
                        <select name="loai_nguoi_dang_ky" class="form-select form-select-lg <?= isset($validation) && $validation->hasError('loai_nguoi_dang_ky') ? 'is-invalid' : '' ?>" 
                               id="loai_nguoi_dang_ky" required>
                            <option value="khach" <?= $loai_nguoi_dang_ky == 'khach' ? 'selected' : '' ?>>Khách mời</option>
                            <option value="sinh_vien" <?= $loai_nguoi_dang_ky == 'sinh_vien' ? 'selected' : '' ?>>Sinh viên</option>
                            <option value="giang_vien" <?= $loai_nguoi_dang_ky == 'giang_vien' ? 'selected' : '' ?>>Giảng viên</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('loai_nguoi_dang_ky')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('loai_nguoi_dang_ky') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="hinh_thuc_tham_gia" class="form-label fw-bold">
                            <i class="fas fa-video text-primary me-1"></i> Hình thức tham gia <span class="text-danger">*</span>
                        </label>
                        <select name="hinh_thuc_tham_gia" class="form-select form-select-lg <?= isset($validation) && $validation->hasError('hinh_thuc_tham_gia') ? 'is-invalid' : '' ?>" 
                               id="hinh_thuc_tham_gia" required>
                            <option value="offline" <?= $hinh_thuc_tham_gia == 'offline' ? 'selected' : '' ?>>Trực tiếp</option>
                            <option value="online" <?= $hinh_thuc_tham_gia == 'online' ? 'selected' : '' ?>>Trực tuyến</option>
                            <option value="hybrid" <?= $hinh_thuc_tham_gia == 'hybrid' ? 'selected' : '' ?>>Kết hợp</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('hinh_thuc_tham_gia')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('hinh_thuc_tham_gia') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="don_vi_to_chuc" class="form-label fw-bold">
                            <i class="fas fa-building text-primary me-1"></i> Đơn vị tổ chức
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('don_vi_to_chuc') ? 'is-invalid' : '' ?>" 
                               id="don_vi_to_chuc" name="don_vi_to_chuc" value="<?= $don_vi_to_chuc ?>" placeholder="Nhập đơn vị tổ chức">
                        <?php if (isset($validation) && $validation->hasError('don_vi_to_chuc')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('don_vi_to_chuc') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="nguon_gioi_thieu" class="form-label fw-bold">
                            <i class="fas fa-share-alt text-primary me-1"></i> Nguồn giới thiệu
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('nguon_gioi_thieu') ? 'is-invalid' : '' ?>" 
                               id="nguon_gioi_thieu" name="nguon_gioi_thieu" value="<?= $nguon_gioi_thieu ?>" placeholder="Nhập nguồn giới thiệu">
                        <?php if (isset($validation) && $validation->hasError('nguon_gioi_thieu')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('nguon_gioi_thieu') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-12">
                        <label for="ly_do_tham_du" class="form-label fw-bold">
                            <i class="fas fa-align-left text-primary me-1"></i> Lý do tham dự
                        </label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('ly_do_tham_du') ? 'is-invalid' : '' ?>" 
                                  id="ly_do_tham_du" name="ly_do_tham_du" rows="3" 
                                  placeholder="Nhập lý do tham dự"><?= $ly_do_tham_du ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('ly_do_tham_du')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ly_do_tham_du') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-12">
                        <label for="noi_dung_gop_y" class="form-label fw-bold">
                            <i class="fas fa-align-left text-primary me-1"></i> Nội dung góp ý
                        </label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('noi_dung_gop_y') ? 'is-invalid' : '' ?>" 
                                  id="noi_dung_gop_y" name="noi_dung_gop_y" rows="3" 
                                  placeholder="Nhập nội dung góp ý"><?= $noi_dung_gop_y ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('noi_dung_gop_y')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('noi_dung_gop_y') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">
                            <i class="fas fa-toggle-on text-primary me-1"></i> Trạng thái <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select form-select-lg <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                                id="status" required>
                            <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Đã xác nhận</option>
                            <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Chờ xác nhận</option>
                            <option value="-1" <?= $status == -1 ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ảnh khuôn mặt</label>
                        <div class="input-group">
                            <input type="file" name="face_image" class="form-control form-control-solid" accept="image/*" id="face_image_input">
                            <?php if (!empty($face_image_path)): ?>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#faceImageModal">
                                    <i class="fas fa-eye"></i> Xem ảnh
                                </button>
                            <?php endif; ?>
                        </div>
                        <?php if (isset($errors['face_image_path'])): ?>
                            <div class="text-danger"><?= $errors['face_image_path'] ?></div>
                        <?php endif; ?>
                        
                        <?php if (!empty($face_image_path)): ?>
                            <div class="form-text d-flex align-items-center mt-2">
                                <img src="<?= base_url($face_image_path) ?>" class="img-thumbnail img-face-preview me-2" 
                                     style="height: 50px; width: auto; cursor: pointer;"
                                     title="Click để xem ảnh lớn hơn">
                                <span class="text-muted"><?= $face_image_path ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <div id="face_image_preview" class="mt-2" style="display: none;">
                            <img src="" class="img-thumbnail" style="height: 100px; width: auto;">
                            <button type="button" class="btn btn-sm btn-danger" id="remove_preview">
                                <i class="fas fa-times"></i> Hủy
                            </button>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Xác thực khuôn mặt</label>
                        <div class="form-check form-switch">
                            <input type="checkbox" name="face_verified" class="form-check-input" value="1" 
                                   <?= old('face_verified', $data->isFaceVerified() ?? false) ? 'checked' : '' ?>>
                            <label class="form-check-label">Đã xác thực</label>
                        </div>
                        <?php if (isset($errors['face_verified'])): ?>
                            <div class="text-danger"><?= $errors['face_verified'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Điểm danh bằng</label>
                        <select name="diem_danh_bang" class="form-select form-select-solid">
                            <option value="none" <?= old('diem_danh_bang', $data->getDiemDanhBang() ?? 'none') == 'none' ? 'selected' : '' ?>>Chưa điểm danh</option>
                            <option value="qr_code" <?= old('diem_danh_bang', $data->getDiemDanhBang() ?? 'none') == 'qr_code' ? 'selected' : '' ?>>Mã QR</option>
                            <option value="face_id" <?= old('diem_danh_bang', $data->getDiemDanhBang() ?? 'none') == 'face_id' ? 'selected' : '' ?>>Nhận diện khuôn mặt</option>
                            <option value="manual" <?= old('diem_danh_bang', $data->getDiemDanhBang() ?? 'none') == 'manual' ? 'selected' : '' ?>>Thủ công</option>
                        </select>
                        <?php if (isset($errors['diem_danh_bang'])): ?>
                            <div class="text-danger"><?= $errors['diem_danh_bang'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Trạng thái điểm danh</label>
                        <select name="attendance_status" class="form-select form-select-solid">
                            <option value="not_attended" <?= old('attendance_status', $data->getAttendanceStatus() ?? 'not_attended') == 'not_attended' ? 'selected' : '' ?>>Chưa tham dự</option>
                            <option value="partial" <?= old('attendance_status', $data->getAttendanceStatus() ?? 'not_attended') == 'partial' ? 'selected' : '' ?>>Tham dự một phần</option>
                            <option value="full" <?= old('attendance_status', $data->getAttendanceStatus() ?? 'not_attended') == 'full' ? 'selected' : '' ?>>Tham dự đầy đủ</option>
                        </select>
                        <?php if (isset($errors['attendance_status'])): ?>
                            <div class="text-danger"><?= $errors['attendance_status'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Thời gian tham dự (phút)</label>
                        <input type="number" name="attendance_minutes" class="form-control form-control-solid" 
                               value="<?= old('attendance_minutes', $data->getAttendanceMinutes() ?? 0) ?>" min="0">
                        <?php if (isset($errors['attendance_minutes'])): ?>
                            <div class="text-danger"><?= $errors['attendance_minutes'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Trạng thái check-in/out</label>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="da_check_in" class="form-check-input" value="1" 
                                           <?= old('da_check_in', $data->isDaCheckIn() ?? false) ? 'checked' : '' ?>>
                                    <label class="form-check-label">Đã check-in</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input type="checkbox" name="da_check_out" class="form-check-input" value="1" 
                                           <?= old('da_check_out', $data->isDaCheckOut() ?? false) ? 'checked' : '' ?>>
                                    <label class="form-check-label">Đã check-out</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
     <!-- Buttons -->
     <div class="col-12 mt-3">
        <div class="d-flex justify-content-between">
            <a href="<?= site_url($module_name) ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
            <div>
                <button type="reset" class="btn btn-outline-warning me-2">
                    <i class="fas fa-redo me-1"></i> Đặt lại
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Lưu thông tin
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal hiển thị ảnh khuôn mặt -->
<div class="modal fade" id="faceImageModal" tabindex="-1" aria-labelledby="faceImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="faceImageModalLabel">Ảnh khuôn mặt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <?php if (!empty($face_image_path)): ?>
                    <img src="<?= base_url($face_image_path) ?>" class="img-fluid" alt="Ảnh khuôn mặt" style="max-height: 500px;">
                    <p class="mt-2 text-muted"><?= $face_image_path ?></p>
                <?php else: ?>
                    <div class="alert alert-warning">Không có ảnh khuôn mặt</div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý modal khuôn mặt
    document.querySelectorAll('.img-face-preview').forEach(function(img) {
        img.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('faceImageModal'));
            modal.show();
        });
    });
    
    // Xử lý xem trước ảnh khi chọn file
    const faceImageInput = document.getElementById('face_image_input');
    const faceImagePreview = document.getElementById('face_image_preview');
    const previewImg = faceImagePreview.querySelector('img');
    const removePreviewBtn = document.getElementById('remove_preview');
    
    faceImageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                faceImagePreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
    
    removePreviewBtn.addEventListener('click', function() {
        faceImageInput.value = '';
        faceImagePreview.style.display = 'none';
    });
    
    // Xử lý hiển thị/ẩn trường lý do hủy khi thay đổi trạng thái
    const statusSelect = document.getElementById('status');
    const lyDoHuySection = document.querySelector('.ly-do-huy-section');
    
    if (statusSelect && lyDoHuySection) {
        statusSelect.addEventListener('change', function() {
            lyDoHuySection.style.display = this.value == -1 ? 'block' : 'none';
        });
    }
});
</script>
