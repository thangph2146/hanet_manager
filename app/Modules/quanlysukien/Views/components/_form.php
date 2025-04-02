<?php
// Kiểm tra xem $data có phải là đối tượng hay không
if (is_object($data)) {
    $su_kien_id = $data->getId() ?? '';
    $ten_su_kien = $data->getTenSuKien() ?? '';
    $ma_su_kien = $data->getMaQrCode() ?? '';
    $mo_ta = $data->getMoTa() ?? '';
    $mo_ta_su_kien = $data->getMoTaSuKien() ?? '';
    $chi_tiet_su_kien = $data->getChiTietSuKien() ?? '';
    $thoi_gian_bat_dau = $data->getThoiGianBatDau() ?? '';
    $thoi_gian_ket_thuc = $data->getThoiGianKetThuc() ?? '';
    $dia_diem = $data->getDiaDiem() ?? '';
    $dia_chi_cu_the = $data->getDiaChiCuThe() ?? '';
    $toa_do_gps = $data->getToaDoGPS() ?? '';
    $loai_su_kien_id = $data->getLoaiSuKienId() ?? '';
    $hinh_thuc = $data->getHinhThuc() ?? 'offline';
    $link_online = $data->getLinkOnline() ?? '';
    $mat_khau_online = $data->getMatKhauOnline() ?? '';
    $status = $data->getStatus() ?? 1;
    $tong_dang_ky = $data->getTongDangKy() ?? 0;
    $tong_check_in = $data->getTongCheckIn() ?? 0;
    $tong_check_out = $data->getTongCheckOut() ?? 0;
    $cho_phep_check_in = $data->isAllowCheckIn() ?? true;
    $cho_phep_check_out = $data->isAllowCheckOut() ?? true;
    $yeu_cau_face_id = $data->isRequireFaceId() ?? false;
    $cho_phep_checkin_thu_cong = $data->isAllowManualCheckin() ?? true;
    $bat_dau_dang_ky = $data->getBatDauDangKy() ?? '';
    $ket_thuc_dang_ky = $data->getKetThucDangKy() ?? '';
    $han_huy_dang_ky = $data->getHanHuyDangKy() ?? '';
    $so_luong_tham_gia = $data->getSoLuongThamGia() ?? 0;
    $so_luong_dien_gia = $data->getSoLuongDienGia() ?? 0;
    $gioi_han_loai_nguoi_dung = $data->getGioiHanLoaiNguoiDung() ?? '';
    $tu_khoa_su_kien = $data->getTuKhoaSuKien() ?? '';
    $hashtag = $data->getHashtag() ?? '';
    $slug = $data->getSlug() ?? '';
    $so_luot_xem = $data->getSoLuotXem() ?? 0;
} else {
    $su_kien_id = '';
    $ten_su_kien = '';
    $ma_su_kien = '';
    $mo_ta = '';
    $mo_ta_su_kien = '';
    $chi_tiet_su_kien = '';
    $thoi_gian_bat_dau = '';
    $thoi_gian_ket_thuc = '';
    $dia_diem = '';
    $dia_chi_cu_the = '';
    $toa_do_gps = '';
    $loai_su_kien_id = '';
    $hinh_thuc = 'offline';
    $link_online = '';
    $mat_khau_online = '';
    $status = 1;
    $tong_dang_ky = 0;
    $tong_check_in = 0;
    $tong_check_out = 0;
    $cho_phep_check_in = true;
    $cho_phep_check_out = true;
    $yeu_cau_face_id = false;
    $cho_phep_checkin_thu_cong = true;
    $bat_dau_dang_ky = '';
    $ket_thuc_dang_ky = '';
    $han_huy_dang_ky = '';
    $so_luong_tham_gia = 0;
    $so_luong_dien_gia = 0;
    $gioi_han_loai_nguoi_dung = '';
    $tu_khoa_su_kien = '';
    $hashtag = '';
    $slug = '';
    $so_luot_xem = 0;
}
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
                    <i class="fas fa-info-circle me-2"></i> Thông tin sự kiện
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="ten_su_kien" class="form-label fw-bold">
                            <i class="fas fa-tag text-primary me-1"></i> Tên sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ten_su_kien') ? 'is-invalid' : '' ?>" 
                               id="ten_su_kien" name="ten_su_kien" 
                               value="<?= $ten_su_kien ?>" 
                               placeholder="Nhập tên sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('ten_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="ma_su_kien" class="form-label fw-bold">
                            <i class="fas fa-code text-primary me-1"></i> Mã sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ma_su_kien') ? 'is-invalid' : '' ?>" 
                               id="ma_su_kien" name="ma_su_kien" 
                               value="<?= $ma_su_kien ?>" 
                               placeholder="Nhập mã sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('ma_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thoi_gian_bat_dau" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt text-primary me-1"></i> Thời gian bắt đầu <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('thoi_gian_bat_dau') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_bat_dau" name="thoi_gian_bat_dau" 
                               value="<?= $thoi_gian_bat_dau ?>" required>
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_bat_dau')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_bat_dau') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thoi_gian_ket_thuc" class="form-label fw-bold">
                            <i class="fas fa-calendar-check text-primary me-1"></i> Thời gian kết thúc <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('thoi_gian_ket_thuc') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc" 
                               value="<?= $thoi_gian_ket_thuc ?>" required>
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_ket_thuc')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_ket_thuc') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="dia_diem" class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i> Địa điểm <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('dia_diem') ? 'is-invalid' : '' ?>" 
                               id="dia_diem" name="dia_diem" 
                               value="<?= $dia_diem ?>" 
                               placeholder="Nhập địa điểm sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('dia_diem')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dia_diem') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="dia_chi_cu_the" class="form-label fw-bold">
                            <i class="fas fa-location-arrow text-primary me-1"></i> Địa chỉ cụ thể
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('dia_chi_cu_the') ? 'is-invalid' : '' ?>" 
                               id="dia_chi_cu_the" name="dia_chi_cu_the" 
                               value="<?= $dia_chi_cu_the ?>" 
                               placeholder="Nhập địa chỉ cụ thể">
                        <?php if (isset($validation) && $validation->hasError('dia_chi_cu_the')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dia_chi_cu_the') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="loai_su_kien_id" class="form-label fw-bold">
                            <i class="fas fa-list-alt text-primary me-1"></i> Loại sự kiện <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('loai_su_kien_id') ? 'is-invalid' : '' ?>" 
                                id="loai_su_kien_id" name="loai_su_kien_id" required>
                            <option value="" disabled selected>-- Chọn loại sự kiện --</option>
                            <?php 
                            // Sử dụng dịch vụ model để lấy danh sách loại sự kiện
                            $loaiSuKienModel = new \App\Modules\quanlyloaisukien\Models\LoaiSuKienModel();
                            $danhSachLoaiSuKien = $loaiSuKienModel->findAll();
                            
                            foreach ($danhSachLoaiSuKien as $loai) : 
                                $selected = ($loai_su_kien_id == $loai->getId()) ? 'selected' : '';
                            ?>
                                <option value="<?= $loai->getId() ?>" <?= $selected ?>><?= esc($loai->getTenLoaiSuKien()) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('loai_su_kien_id')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('loai_su_kien_id') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="hinh_thuc" class="form-label fw-bold">
                            <i class="fas fa-video text-primary me-1"></i> Hình thức <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('hinh_thuc') ? 'is-invalid' : '' ?>" 
                                id="hinh_thuc" name="hinh_thuc" required>
                            <option value="offline" <?= $hinh_thuc == 'offline' ? 'selected' : '' ?>>Offline</option>
                            <option value="online" <?= $hinh_thuc == 'online' ? 'selected' : '' ?>>Online</option>
                            <option value="hybrid" <?= $hinh_thuc == 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('hinh_thuc')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('hinh_thuc') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <!-- Trường thông tin Online - chỉ hiển thị khi hình thức là online hoặc hybrid -->
                    <div class="col-md-6 online-fields" style="display: <?= ($hinh_thuc == 'offline') ? 'none' : 'block' ?>;">
                        <label for="link_online" class="form-label fw-bold">
                            <i class="fas fa-link text-primary me-1"></i> Link tham gia trực tuyến
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('link_online') ? 'is-invalid' : '' ?>" 
                                id="link_online" name="link_online" 
                                value="<?= $link_online ?>" 
                                placeholder="https://meet.example.com/event">
                        <?php if (isset($validation) && $validation->hasError('link_online')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('link_online') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6 online-fields" style="display: <?= ($hinh_thuc == 'offline') ? 'none' : 'block' ?>;">
                        <label for="mat_khau_online" class="form-label fw-bold">
                            <i class="fas fa-key text-primary me-1"></i> Mật khẩu tham gia (nếu có)
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('mat_khau_online') ? 'is-invalid' : '' ?>" 
                                id="mat_khau_online" name="mat_khau_online" 
                                value="<?= $mat_khau_online ?>" 
                                placeholder="Mật khẩu tham gia">
                        <?php if (isset($validation) && $validation->hasError('mat_khau_online')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mat_khau_online') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="slug" class="form-label fw-bold">
                            <i class="fas fa-link text-primary me-1"></i> Slug
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('slug') ? 'is-invalid' : '' ?>" 
                               id="slug" name="slug" 
                               value="<?= $slug ?>" 
                               placeholder="su-kien-abc">
                        <?php if (isset($validation) && $validation->hasError('slug')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('slug') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="toa_do_gps" class="form-label fw-bold">
                            <i class="fas fa-map text-primary me-1"></i> Tọa độ GPS
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('toa_do_gps') ? 'is-invalid' : '' ?>" 
                               id="toa_do_gps" name="toa_do_gps" 
                               value="<?= $toa_do_gps ?>" 
                               placeholder="VD: 10.762622,106.660172">
                        <?php if (isset($validation) && $validation->hasError('toa_do_gps')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('toa_do_gps') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-12">
                        <label for="mo_ta" class="form-label fw-bold">
                            <i class="fas fa-align-left text-primary me-1"></i> Mô tả
                        </label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('mo_ta') ? 'is-invalid' : '' ?>" 
                                  id="mo_ta" name="mo_ta" rows="3" 
                                  placeholder="Nhập mô tả sự kiện"><?= $mo_ta ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('mo_ta')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mo_ta') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-12">
                        <label for="mo_ta_su_kien" class="form-label fw-bold">
                            <i class="fas fa-file-alt text-primary me-1"></i> Mô tả chi tiết
                        </label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('mo_ta_su_kien') ? 'is-invalid' : '' ?>" 
                                  id="mo_ta_su_kien" name="mo_ta_su_kien" rows="5" 
                                  placeholder="Nhập mô tả chi tiết về sự kiện"><?= $mo_ta_su_kien ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('mo_ta_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mo_ta_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-12">
                        <label for="chi_tiet_su_kien" class="form-label fw-bold">
                            <i class="fas fa-info-circle text-primary me-1"></i> Chi tiết sự kiện
                        </label>
                        <textarea class="form-control wysiwyg <?= isset($validation) && $validation->hasError('chi_tiet_su_kien') ? 'is-invalid' : '' ?>" 
                                  id="chi_tiet_su_kien" name="chi_tiet_su_kien" rows="8" 
                                  placeholder="Nhập chi tiết sự kiện (hỗ trợ HTML)"><?= $chi_tiet_su_kien ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('chi_tiet_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('chi_tiet_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">
                            <i class="fas fa-toggle-on text-primary me-1"></i> Trạng thái <span class="text-danger">*</span>
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                                id="status" name="status" required>
                            <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Vô hiệu</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin đăng ký -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-calendar-check me-2"></i> Thông tin đăng ký
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="bat_dau_dang_ky" class="form-label fw-bold">
                            <i class="fas fa-calendar-plus text-primary me-1"></i> Thời gian bắt đầu đăng ký
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('bat_dau_dang_ky') ? 'is-invalid' : '' ?>" 
                               id="bat_dau_dang_ky" name="bat_dau_dang_ky" 
                               value="<?= $bat_dau_dang_ky ?>">
                        <?php if (isset($validation) && $validation->hasError('bat_dau_dang_ky')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('bat_dau_dang_ky') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-4">
                        <label for="ket_thuc_dang_ky" class="form-label fw-bold">
                            <i class="fas fa-calendar-times text-primary me-1"></i> Thời gian kết thúc đăng ký
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ket_thuc_dang_ky') ? 'is-invalid' : '' ?>" 
                               id="ket_thuc_dang_ky" name="ket_thuc_dang_ky" 
                               value="<?= $ket_thuc_dang_ky ?>">
                        <?php if (isset($validation) && $validation->hasError('ket_thuc_dang_ky')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ket_thuc_dang_ky') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-4">
                        <label for="han_huy_dang_ky" class="form-label fw-bold">
                            <i class="fas fa-calendar-minus text-primary me-1"></i> Hạn hủy đăng ký
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('han_huy_dang_ky') ? 'is-invalid' : '' ?>" 
                               id="han_huy_dang_ky" name="han_huy_dang_ky" 
                               value="<?= $han_huy_dang_ky ?>">
                        <?php if (isset($validation) && $validation->hasError('han_huy_dang_ky')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('han_huy_dang_ky') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-4">
                        <label for="so_luong_tham_gia" class="form-label fw-bold">
                            <i class="fas fa-users text-primary me-1"></i> Số lượng người tham gia tối đa
                        </label>
                        <input type="number" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('so_luong_tham_gia') ? 'is-invalid' : '' ?>" 
                               id="so_luong_tham_gia" name="so_luong_tham_gia" 
                               value="<?= $so_luong_tham_gia ?>" min="0">
                        <?php if (isset($validation) && $validation->hasError('so_luong_tham_gia')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('so_luong_tham_gia') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-4">
                        <label for="so_luong_dien_gia" class="form-label fw-bold">
                            <i class="fas fa-user-tie text-primary me-1"></i> Số lượng diễn giả
                        </label>
                        <input type="number" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('so_luong_dien_gia') ? 'is-invalid' : '' ?>" 
                               id="so_luong_dien_gia" name="so_luong_dien_gia" 
                               value="<?= $so_luong_dien_gia ?>" min="0">
                        <?php if (isset($validation) && $validation->hasError('so_luong_dien_gia')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('so_luong_dien_gia') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-4">
                        <label for="gioi_han_loai_nguoi_dung" class="form-label fw-bold">
                            <i class="fas fa-user-shield text-primary me-1"></i> Giới hạn loại người dùng
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('gioi_han_loai_nguoi_dung') ? 'is-invalid' : '' ?>" 
                               id="gioi_han_loai_nguoi_dung" name="gioi_han_loai_nguoi_dung" 
                               value="<?= $gioi_han_loai_nguoi_dung ?>" 
                               placeholder="VD: all hoặc member,vip">
                        <?php if (isset($validation) && $validation->hasError('gioi_han_loai_nguoi_dung')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('gioi_han_loai_nguoi_dung') ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin check-in -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-qrcode me-2"></i> Thông tin check-in/check-out
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cho_phep_check_in" name="cho_phep_check_in" value="1" <?= $cho_phep_check_in ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="cho_phep_check_in">
                                <i class="fas fa-sign-in-alt text-primary me-1"></i> Cho phép check-in
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cho_phep_check_out" name="cho_phep_check_out" value="1" <?= $cho_phep_check_out ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="cho_phep_check_out">
                                <i class="fas fa-sign-out-alt text-primary me-1"></i> Cho phép check-out
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="yeu_cau_face_id" name="yeu_cau_face_id" value="1" <?= $yeu_cau_face_id ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="yeu_cau_face_id">
                                <i class="fas fa-user-check text-primary me-1"></i> Yêu cầu xác thực khuôn mặt
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="cho_phep_checkin_thu_cong" name="cho_phep_checkin_thu_cong" value="1" <?= $cho_phep_checkin_thu_cong ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="cho_phep_checkin_thu_cong">
                                <i class="fas fa-user-edit text-primary me-1"></i> Cho phép check-in thủ công
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin SEO và metadata -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-tags me-2"></i> SEO và Metadata
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="tu_khoa_su_kien" class="form-label fw-bold">
                            <i class="fas fa-key text-primary me-1"></i> Từ khóa sự kiện
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('tu_khoa_su_kien') ? 'is-invalid' : '' ?>" 
                               id="tu_khoa_su_kien" name="tu_khoa_su_kien" 
                               value="<?= $tu_khoa_su_kien ?>" 
                               placeholder="sự kiện, tech, hội thảo, ...">
                        <?php if (isset($validation) && $validation->hasError('tu_khoa_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('tu_khoa_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="hashtag" class="form-label fw-bold">
                            <i class="fas fa-hashtag text-primary me-1"></i> Hashtag
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('hashtag') ? 'is-invalid' : '' ?>" 
                               id="hashtag" name="hashtag" 
                               value="<?= $hashtag ?>" 
                               placeholder="#SuKienABC #Tech2023">
                        <?php if (isset($validation) && $validation->hasError('hashtag')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('hashtag') ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lịch trình sự kiện -->
    <div class="card my-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Lịch trình sự kiện</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fa fa-info-circle me-2"></i> Thêm các phiên làm việc, thời gian diễn ra và người phụ trách cho sự kiện.
            </div>
            
            <div id="lich-trinh-container">
                <?php 
                $lichTrinh = [];
                if (isset($data) && !empty($data->lich_trinh)) {
                    if (is_string($data->lich_trinh)) {
                        $lichTrinh = json_decode($data->lich_trinh, true);
                    } else {
                        $lichTrinh = $data->lich_trinh;
                    }
                }
                
                if (empty($lichTrinh)) {
                    // Tạo một mục lịch trình mặc định nếu chưa có
                    $lichTrinh = [
                        [
                            'tieu_de' => '',
                            'mo_ta' => '',
                            'thoi_gian_bat_dau' => '',
                            'thoi_gian_ket_thuc' => '',
                            'nguoi_phu_trach' => ''
                        ]
                    ];
                }
                
                foreach ($lichTrinh as $index => $session):
                ?>
                <div class="lich-trinh-item border rounded p-3 mb-3">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="session_title_<?= $index ?>">Tiêu đề phiên</label>
                                <input type="text" class="form-control session-title" id="session_title_<?= $index ?>" 
                                       value="<?= $session['tieu_de'] ?? '' ?>" 
                                       placeholder="Ví dụ: Khai mạc, Phiên thảo luận 1, Giải lao...">
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="session_start_<?= $index ?>">Thời gian bắt đầu</label>
                                <input type="datetime-local" class="form-control session-start" id="session_start_<?= $index ?>" 
                                       value="<?= !empty($session['thoi_gian_bat_dau']) ? date('Y-m-d\TH:i', strtotime($session['thoi_gian_bat_dau'])) : '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="session_end_<?= $index ?>">Thời gian kết thúc</label>
                                <input type="datetime-local" class="form-control session-end" id="session_end_<?= $index ?>" 
                                       value="<?= !empty($session['thoi_gian_ket_thuc']) ? date('Y-m-d\TH:i', strtotime($session['thoi_gian_ket_thuc'])) : '' ?>">
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="session_desc_<?= $index ?>">Mô tả</label>
                                <textarea class="form-control session-desc" id="session_desc_<?= $index ?>" rows="2"
                                          placeholder="Mô tả ngắn gọn về nội dung phiên"><?= $session['mo_ta'] ?? '' ?></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-group">
                                <label for="session_manager_<?= $index ?>">Người phụ trách</label>
                                <input type="text" class="form-control session-manager" id="session_manager_<?= $index ?>" 
                                       value="<?= $session['nguoi_phu_trach'] ?? '' ?>" 
                                       placeholder="Người chịu trách nhiệm cho phiên này">
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-sm btn-danger remove-session" <?= ($index === 0 && count($lichTrinh) === 1) ? 'style="display:none"' : '' ?>>
                            <i class="fas fa-trash-alt"></i> Xóa phiên
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center mt-3">
                <button type="button" class="btn btn-success" id="add-session-btn">
                    <i class="fas fa-plus-circle"></i> Thêm phiên mới
                </button>
            </div>
            
            <!-- Input ẩn để lưu dữ liệu lịch trình dưới dạng JSON -->
            <input type="hidden" name="lich_trinh" id="lich_trinh_json" value='<?= isset($data) && !empty($data->lich_trinh) ? (is_string($data->lich_trinh) ? $data->lich_trinh : json_encode($data->lich_trinh)) : '[]' ?>'>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Thêm hiệu ứng cho form inputs
    const inputs = document.querySelectorAll('.form-control, .form-select');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.closest('.col-md-6, .col-md-12, .col-md-4').classList.add('animate__animated', 'animate__pulse');
        });
        
        input.addEventListener('blur', function() {
            this.closest('.col-md-6, .col-md-12, .col-md-4').classList.remove('animate__animated', 'animate__pulse');
        });
    });
    
    // Kiểm tra thời gian kết thúc phải sau thời gian bắt đầu
    const thoiGianBatDau = document.getElementById('thoi_gian_bat_dau');
    const thoiGianKetThuc = document.getElementById('thoi_gian_ket_thuc');
    
    thoiGianKetThuc.addEventListener('change', function() {
        if (thoiGianBatDau.value && thoiGianKetThuc.value) {
            if (new Date(thoiGianKetThuc.value) <= new Date(thoiGianBatDau.value)) {
                thoiGianKetThuc.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu');
            } else {
                thoiGianKetThuc.setCustomValidity('');
            }
        }
    });
    
    thoiGianBatDau.addEventListener('change', function() {
        if (thoiGianBatDau.value && thoiGianKetThuc.value) {
            if (new Date(thoiGianKetThuc.value) <= new Date(thoiGianBatDau.value)) {
                thoiGianKetThuc.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu');
            } else {
                thoiGianKetThuc.setCustomValidity('');
            }
        }
    });

    // Hiển thị/ẩn các trường online dựa trên hình thức sự kiện
    const hinhThucSelect = document.getElementById('hinh_thuc');
    const onlineFields = document.querySelectorAll('.online-fields');
    
    hinhThucSelect.addEventListener('change', function() {
        const isOffline = this.value === 'offline';
        onlineFields.forEach(field => {
            field.style.display = isOffline ? 'none' : 'block';
        });
    });

    // Quản lý lịch trình sự kiện
    const lichTrinhContainer = document.getElementById('lich-trinh-container');
    const addSessionBtn = document.getElementById('add-session-btn');
    const lichTrinhJson = document.getElementById('lich_trinh_json');
    
    // Cập nhật hidden input JSON khi có thay đổi
    function updateLichTrinhJson() {
        const items = document.querySelectorAll('.lich-trinh-item');
        const data = [];
        
        items.forEach((item, idx) => {
            const sessionTitle = item.querySelector('.session-title');
            const sessionStart = item.querySelector('.session-start');
            const sessionEnd = item.querySelector('.session-end');
            const sessionDesc = item.querySelector('.session-desc');
            const sessionManager = item.querySelector('.session-manager');
            
            if (sessionTitle && sessionStart && sessionEnd && sessionDesc && sessionManager) {
                data.push({
                    tieu_de: sessionTitle.value || '',
                    thoi_gian_bat_dau: sessionStart.value || '',
                    thoi_gian_ket_thuc: sessionEnd.value || '',
                    mo_ta: sessionDesc.value || '',
                    nguoi_phu_trach: sessionManager.value || ''
                });
            }
        });
        
        lichTrinhJson.value = JSON.stringify(data);
    }
    
    // Thêm phiên mới
    addSessionBtn.addEventListener('click', function() {
        const items = document.querySelectorAll('.lich-trinh-item');
        const newIndex = items.length;
        
        const template = `
        <div class="lich-trinh-item border rounded p-3 mb-3" data-index="${newIndex}">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">Tiêu đề phiên</label>
                        <input type="text" class="form-control session-title" 
                            value="" placeholder="Ví dụ: Khai mạc, Phiên thảo luận 1, Giải lao...">
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">Thời gian bắt đầu</label>
                        <input type="datetime-local" class="form-control session-start" 
                            value="">
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">Thời gian kết thúc</label>
                        <input type="datetime-local" class="form-control session-end" 
                            value="">
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">Mô tả</label>
                        <textarea class="form-control session-desc" rows="2" 
                            placeholder="Mô tả nội dung của phiên"></textarea>
                    </div>
                </div>
                
                <div class="col-md-6 mb-3">
                    <div class="form-group">
                        <label class="form-label fw-bold">Người phụ trách</label>
                        <input type="text" class="form-control session-manager" 
                            value="" placeholder="Người phụ trách phiên này">
                    </div>
                </div>
            </div>
            
            <div class="text-end">
                <button type="button" class="btn btn-sm btn-danger remove-session">
                    <i class="fas fa-trash-alt"></i> Xóa phiên
                </button>
            </div>
        </div>`;
        
        lichTrinhContainer.insertAdjacentHTML('beforeend', template);
        
        // Kích hoạt nút xóa cho tất cả các phiên
        updateRemoveButtonState();
        
        // Đăng ký sự kiện cho các input mới
        bindSessionInputEvents();
        
        // Đăng ký sự kiện xóa cho phiên mới
        bindRemoveSessionEvents();
        
        // Tự động cuộn xuống phiên mới
        const newSession = lichTrinhContainer.querySelector(`.lich-trinh-item[data-index="${newIndex}"]`);
        if (newSession) {
            newSession.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        updateLichTrinhJson();
    });
    
    // Cập nhật trạng thái nút xóa
    function updateRemoveButtonState() {
        const items = document.querySelectorAll('.lich-trinh-item');
        const buttons = document.querySelectorAll('.remove-session');
        
        buttons.forEach(btn => {
            btn.style.display = items.length <= 1 ? 'none' : 'inline-block';
        });
    }
    
    // Xóa phiên
    function bindRemoveSessionEvents() {
        document.querySelectorAll('.remove-session').forEach(btn => {
            // Xóa event listener cũ để tránh duplicate
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);
            
            newBtn.addEventListener('click', function() {
                const item = this.closest('.lich-trinh-item');
                if (item) {
                    item.remove();
                    updateRemoveButtonState();
                    updateLichTrinhJson();
                }
            });
        });
    }
    
    // Sự kiện thay đổi dữ liệu phiên
    function bindSessionInputEvents() {
        document.querySelectorAll('.session-title, .session-start, .session-end, .session-desc, .session-manager').forEach(element => {
            element.addEventListener('input', updateLichTrinhJson);
            element.addEventListener('change', updateLichTrinhJson);
        });
    }
    
    // Khởi tạo các sự kiện
    bindRemoveSessionEvents();
    bindSessionInputEvents();
    updateRemoveButtonState();
    
    // Cập nhật ban đầu
    updateLichTrinhJson();
});
</script> 