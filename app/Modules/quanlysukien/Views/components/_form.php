<?php
// Kiểm tra xem $data có phải là đối tượng hay không
if (is_object($data)) {
    $su_kien_id = $data->getId() ?? '';
    $ten_su_kien = $data->getTenSuKien() ?? '';
    $mo_ta = $data->getMoTa() ?? '';
    $mo_ta_su_kien = $data->getMoTaSuKien() ?? '';
    $chi_tiet_su_kien = $data->getChiTietSuKien() ?? '';
    $thoi_gian_bat_dau_su_kien = $data->getThoiGianBatDauSuKien() ? $data->getThoiGianBatDauSuKien()->format('Y-m-d\TH:i') : '';
    $thoi_gian_ket_thuc_su_kien = $data->getThoiGianKetThucSuKien() ? $data->getThoiGianKetThucSuKien()->format('Y-m-d\TH:i') : '';
    $thoi_gian_bat_dau_dang_ky = $data->getThoiGianBatDauDangKy() ? $data->getThoiGianBatDauDangKy()->format('Y-m-d\TH:i') : '';
    $thoi_gian_ket_thuc_dang_ky = $data->getThoiGianKetThucDangKy() ? $data->getThoiGianKetThucDangKy()->format('Y-m-d\TH:i') : '';
    $thoi_gian_checkin_bat_dau = $data->getThoiGianCheckinBatDau() ? $data->getThoiGianCheckinBatDau()->format('Y-m-d\TH:i') : '';
    $thoi_gian_checkin_ket_thuc = $data->getThoiGianCheckinKetThuc() ? $data->getThoiGianCheckinKetThuc()->format('Y-m-d\TH:i') : '';
    $thoi_gian_checkout_bat_dau = $data->getThoiGianCheckoutBatDau() ? $data->getThoiGianCheckoutBatDau()->format('Y-m-d\TH:i') : '';
    $thoi_gian_checkout_ket_thuc = $data->getThoiGianCheckoutKetThuc() ? $data->getThoiGianCheckoutKetThuc()->format('Y-m-d\TH:i') : '';
    $han_huy_dang_ky = $data->getHanHuyDangKy() ? $data->getHanHuyDangKy()->format('Y-m-d\TH:i') : '';
    $don_vi_to_chuc = $data->getDonViToChuc() ?? '';
    $don_vi_phoi_hop = $data->getDonViPhoiHop() ?? '';
    $doi_tuong_tham_gia = $data->getDoiTuongThamGia() ?? '';
    $dia_diem = $data->getDiaDiem() ?? '';
    $dia_chi_cu_the = $data->getDiaChiCuThe() ?? '';
    $toa_do_gps = $data->getToaDoGPS() ?? '';
    $loai_su_kien_id = $data->getLoaiSuKienId() ?? '';
    $ma_qr_code = $data->getMaQRCode() ?? '';
    $status = $data->getStatus() ?? 1;
    $tong_dang_ky = $data->getTongDangKy() ?? 0;
    $tong_check_in = $data->getTongCheckIn() ?? 0;
    $tong_check_out = $data->getTongCheckOut() ?? 0;
    $cho_phep_check_in = $data->isAllowCheckIn() ?? true;
    $cho_phep_check_out = $data->isAllowCheckOut() ?? true;
    $yeu_cau_face_id = $data->isRequireFaceId() ?? false;
    $cho_phep_checkin_thu_cong = $data->isAllowManualCheckin() ?? true;
    $so_luong_tham_gia = $data->getSoLuongThamGia() ?? 0;
    $so_luong_dien_gia = $data->getSoLuongDienGia() ?? 0;
    $gioi_han_loai_nguoi_dung = $data->getGioiHanLoaiNguoiDung() ?? '';
    $tu_khoa_su_kien = $data->getTuKhoaSuKien() ?? '';
    $hashtag = $data->getHashtag() ?? '';
    $slug = $data->getSlug() ?? '';
    $so_luot_xem = $data->getSoLuotXem() ?? 0;
    $lich_trinh = $data->getLichTrinh() ?? [];
    $hinh_thuc = $data->getHinhThuc() ?? 'offline';
    $link_online = $data->getLinkOnline() ?? '';
    $mat_khau_online = $data->getMatKhauOnline() ?? '';
    $su_kien_poster = $data->getSuKienPoster() ?? [];
    $version = $data->getVersion() ?? 1; 
} else {
    $su_kien_id = '';
    $ten_su_kien = '';
    $mo_ta = '';
    $mo_ta_su_kien = '';
    $chi_tiet_su_kien = '';
    $thoi_gian_bat_dau_su_kien = '';
    $thoi_gian_ket_thuc_su_kien = '';
    $thoi_gian_bat_dau_dang_ky = '';
    $thoi_gian_ket_thuc_dang_ky = '';
    $thoi_gian_checkin_bat_dau = '';
    $thoi_gian_checkin_ket_thuc = '';
    $thoi_gian_checkout_bat_dau = '';
    $thoi_gian_checkout_ket_thuc = '';
    $han_huy_dang_ky = '';
    $don_vi_to_chuc = '';
    $don_vi_phoi_hop = '';
    $doi_tuong_tham_gia = '';
    $dia_diem = '';
    $dia_chi_cu_the = '';
    $toa_do_gps = '';
    $loai_su_kien_id = '';
    $ma_qr_code = '';
    $status = 1;
    $tong_dang_ky = 0;
    $tong_check_in = 0;
    $tong_check_out = 0;
    $cho_phep_check_in = true;
    $cho_phep_check_out = true;
    $yeu_cau_face_id = false;
    $cho_phep_checkin_thu_cong = true;
    $so_luong_tham_gia = 0;
    $so_luong_dien_gia = 0;
    $gioi_han_loai_nguoi_dung = '';
    $tu_khoa_su_kien = '';
    $hashtag = '';
    $slug = '';
    $so_luot_xem = 0;
    $lich_trinh = [];
    $hinh_thuc = 'offline';
    $link_online = '';
    $mat_khau_online = '';
    $su_kien_poster = [];
    $version = 1;
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

/* Xóa phần CSS cho custom editor */
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
                               value="<?= esc($ten_su_kien) ?>" 
                               placeholder="Nhập tên sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('ten_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="ma_qr_code" class="form-label fw-bold">
                            <i class="fas fa-qrcode text-primary me-1"></i> Mã QR Code
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ma_qr_code') ? 'is-invalid' : '' ?>" 
                               id="ma_qr_code" name="ma_qr_code" 
                               value="<?= esc($ma_qr_code) ?>" 
                               placeholder="Mã QR Code cho sự kiện">
                        <?php if (isset($validation) && $validation->hasError('ma_qr_code')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_qr_code') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thoi_gian_bat_dau_su_kien" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt text-primary me-1"></i> Thời gian bắt đầu sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('thoi_gian_bat_dau_su_kien') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_bat_dau_su_kien" name="thoi_gian_bat_dau_su_kien" 
                               value="<?= $thoi_gian_bat_dau_su_kien ?>" required>
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_bat_dau_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_bat_dau_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thoi_gian_ket_thuc_su_kien" class="form-label fw-bold">
                            <i class="fas fa-calendar-check text-primary me-1"></i> Thời gian kết thúc sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="datetime-local" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('thoi_gian_ket_thuc_su_kien') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_ket_thuc_su_kien" name="thoi_gian_ket_thuc_su_kien" 
                               value="<?= $thoi_gian_ket_thuc_su_kien ?>" required>
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_ket_thuc_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_ket_thuc_su_kien') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="dia_diem" class="form-label fw-bold">
                            <i class="fas fa-map-marker-alt text-primary me-1"></i> Địa điểm <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('dia_diem') ? 'is-invalid' : '' ?>" 
                               id="dia_diem" name="dia_diem" 
                               value="<?= esc($dia_diem) ?>" 
                               placeholder="Nhập địa điểm sự kiện">
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
                               value="<?= esc($dia_chi_cu_the) ?>" 
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
                                value="<?= esc($link_online) ?>" 
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
                                value="<?= esc($mat_khau_online) ?>" 
                                placeholder="Mật khẩu tham gia">
                        <?php if (isset($validation) && $validation->hasError('mat_khau_online')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mat_khau_online') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="slug" class="form-label fw-bold">
                            <i class="fas fa-link text-primary me-1"></i> Slug (URL thân thiện)
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('slug') ? 'is-invalid' : '' ?>" 
                               id="slug" name="slug" 
                               value="<?= esc($slug) ?>" 
                               placeholder="su-kien-abc">
                        <?php if (isset($validation) && $validation->hasError('slug')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('slug') ?>
                            </div>
                        <?php endif ?>
                        <div class="form-text">URL thân thiện cho sự kiện, để trống hệ thống sẽ tự tạo từ tên sự kiện</div>
                    </div>

                    <div class="col-md-6">
                        <label for="toa_do_gps" class="form-label fw-bold">
                            <i class="fas fa-map text-primary me-1"></i> Tọa độ GPS
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('toa_do_gps') ? 'is-invalid' : '' ?>" 
                               id="toa_do_gps" name="toa_do_gps" 
                               value="<?= esc($toa_do_gps) ?>" 
                               placeholder="VD: 10.762622,106.660172">
                        <?php if (isset($validation) && $validation->hasError('toa_do_gps')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('toa_do_gps') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-12">
                        <label for="mo_ta" class="form-label fw-bold">
                            <i class="fas fa-align-left text-primary me-1"></i> Mô tả ngắn
                        </label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('mo_ta') ? 'is-invalid' : '' ?>" 
                                  id="mo_ta" name="mo_ta" rows="3"><?= esc($mo_ta) ?></textarea>
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
                                  id="mo_ta_su_kien" name="mo_ta_su_kien" rows="5"><?= esc($mo_ta_su_kien) ?></textarea>
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
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('chi_tiet_su_kien') ? 'is-invalid' : '' ?>" 
                                  id="chi_tiet_su_kien" name="chi_tiet_su_kien" rows="8"><?= esc($chi_tiet_su_kien) ?></textarea>
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
                            <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Không hoạt động</option>
                            <option value="-1" <?= $status == -1 ? 'selected' : '' ?>>Đã hủy</option>
                            <option value="2" <?= $status == 2 ? 'selected' : '' ?>>Tạm hoãn</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="version" class="form-label fw-bold">
                            <i class="fas fa-code-branch text-primary me-1"></i> Version
                        </label>
                        <input type="number" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('version') ? 'is-invalid' : '' ?>" 
                               id="version" name="version" 
                               value="<?= $version ?? 1 ?>" min="1">
                        <?php if (isset($validation) && $validation->hasError('version')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('version') ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thêm thời gian đăng ký -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card" id="registration-times-section">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-clock me-2"></i> Thời gian đăng ký
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="thoi_gian_bat_dau_dang_ky" class="form-label fw-bold">
                            <i class="fas fa-calendar-plus text-primary me-1"></i> Thời gian bắt đầu đăng ký
                        </label>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_bat_dau_dang_ky') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_bat_dau_dang_ky" name="thoi_gian_bat_dau_dang_ky" 
                               value="<?= esc($thoi_gian_bat_dau_dang_ky) ?>">
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_bat_dau_dang_ky')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_bat_dau_dang_ky') ?>
                            </div>
                        <?php endif ?>
                        <div class="form-text">Thời điểm cho phép bắt đầu đăng ký tham gia sự kiện</div>
                    </div>

                    <div class="col-md-4">
                        <label for="thoi_gian_ket_thuc_dang_ky" class="form-label fw-bold">
                            <i class="fas fa-calendar-minus text-primary me-1"></i> Thời gian kết thúc đăng ký
                        </label>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_ket_thuc_dang_ky') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_ket_thuc_dang_ky" name="thoi_gian_ket_thuc_dang_ky" 
                               value="<?= esc($thoi_gian_ket_thuc_dang_ky) ?>">
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_ket_thuc_dang_ky')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_ket_thuc_dang_ky') ?>
                            </div>
                        <?php endif ?>
                        <div class="form-text">Thời điểm đóng đăng ký tham gia sự kiện</div>
                    </div>
                    
                    <div class="col-md-4">
                        <label for="han_huy_dang_ky" class="form-label fw-bold">
                            <i class="fas fa-calendar-times text-warning me-1"></i> Hạn chót hủy đăng ký
                        </label>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('han_huy_dang_ky') ? 'is-invalid' : '' ?>" 
                               id="han_huy_dang_ky" name="han_huy_dang_ky" 
                               value="<?= esc($han_huy_dang_ky) ?>">
                        <?php if (isset($validation) && $validation->hasError('han_huy_dang_ky')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('han_huy_dang_ky') ?>
                            </div>
                        <?php endif ?>
                        <div class="form-text">Thời điểm chốt danh sách, sau đó không thể hủy đăng ký</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin check-in và check-out -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-check-circle me-2"></i> Thông tin check-in và check-out
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="thoi_gian_checkin_bat_dau" class="form-label fw-bold">
                            <i class="fas fa-clock text-primary me-1"></i> Thời gian bắt đầu check-in
                        </label>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_checkin_bat_dau') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_checkin_bat_dau" name="thoi_gian_checkin_bat_dau" 
                               value="<?= esc($thoi_gian_checkin_bat_dau) ?>">
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_checkin_bat_dau')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_checkin_bat_dau') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thoi_gian_checkin_ket_thuc" class="form-label fw-bold">
                            <i class="fas fa-clock text-primary me-1"></i> Thời gian kết thúc check-in
                        </label>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_checkin_ket_thuc') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_checkin_ket_thuc" name="thoi_gian_checkin_ket_thuc" 
                               value="<?= esc($thoi_gian_checkin_ket_thuc) ?>">
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_checkin_ket_thuc')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_checkin_ket_thuc') ?>
                            </div>
                        <?php endif ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="thoi_gian_checkout_bat_dau" class="form-label fw-bold">
                            <i class="fas fa-clock text-primary me-1"></i> Thời gian bắt đầu check-out
                        </label>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_checkout_bat_dau') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_checkout_bat_dau" name="thoi_gian_checkout_bat_dau" 
                               value="<?= esc($thoi_gian_checkout_bat_dau) ?>">
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_checkout_bat_dau')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_checkout_bat_dau') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="thoi_gian_checkout_ket_thuc" class="form-label fw-bold">
                            <i class="fas fa-clock text-primary me-1"></i> Thời gian kết thúc check-out
                        </label>
                        <input type="datetime-local" class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_checkout_ket_thuc') ? 'is-invalid' : '' ?>" 
                               id="thoi_gian_checkout_ket_thuc" name="thoi_gian_checkout_ket_thuc" 
                               value="<?= esc($thoi_gian_checkout_ket_thuc) ?>">
                        <?php if (isset($validation) && $validation->hasError('thoi_gian_checkout_ket_thuc')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thoi_gian_checkout_ket_thuc') ?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" id="cho_phep_check_in" name="cho_phep_check_in" value="1"
                                  <?= $cho_phep_check_in ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="cho_phep_check_in">
                                <i class="fas fa-door-open text-success me-1"></i> Cho phép check-in
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" id="cho_phep_check_out" name="cho_phep_check_out" value="1"
                                  <?= $cho_phep_check_out ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="cho_phep_check_out">
                                <i class="fas fa-door-closed text-danger me-1"></i> Cho phép check-out
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" id="yeu_cau_face_id" name="yeu_cau_face_id" value="1"
                                  <?= $yeu_cau_face_id ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="yeu_cau_face_id">
                                <i class="fas fa-user-check text-primary me-1"></i> Yêu cầu xác thực khuôn mặt
                            </label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" id="cho_phep_checkin_thu_cong" name="cho_phep_checkin_thu_cong" value="1"
                                  <?= $cho_phep_checkin_thu_cong ? 'checked' : '' ?>>
                            <label class="form-check-label fw-bold" for="cho_phep_checkin_thu_cong">
                                <i class="fas fa-edit text-warning me-1"></i> Cho phép check-in thủ công
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thông tin thêm -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-cog me-2"></i> Thông tin bổ sung
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
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

                    <div class="col-md-6">
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
                    
                    <div class="col-md-6">
                        <label for="doi_tuong_tham_gia" class="form-label fw-bold">
                            <i class="fas fa-user-tag text-primary me-1"></i> Đối tượng tham gia
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('doi_tuong_tham_gia') ? 'is-invalid' : '' ?>" 
                               id="doi_tuong_tham_gia" name="doi_tuong_tham_gia" 
                               value="<?= esc($doi_tuong_tham_gia) ?>" 
                               placeholder="Sinh viên, Giảng viên, Cán bộ, v.v.">
                        <?php if (isset($validation) && $validation->hasError('doi_tuong_tham_gia')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('doi_tuong_tham_gia') ?>
                            </div>
                        <?php endif ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="gioi_han_loai_nguoi_dung" class="form-label fw-bold">
                            <i class="fas fa-users-cog text-primary me-1"></i> Giới hạn loại người dùng
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('gioi_han_loai_nguoi_dung') ? 'is-invalid' : '' ?>" 
                               id="gioi_han_loai_nguoi_dung" name="gioi_han_loai_nguoi_dung" 
                               value="<?= esc($gioi_han_loai_nguoi_dung) ?>" 
                               placeholder="VD: 1,2,3 (ID loại người dùng)">
                        <?php if (isset($validation) && $validation->hasError('gioi_han_loai_nguoi_dung')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('gioi_han_loai_nguoi_dung') ?>
                            </div>
                        <?php endif ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="don_vi_to_chuc" class="form-label fw-bold">
                            <i class="fas fa-building text-primary me-1"></i> Đơn vị tổ chức
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('don_vi_to_chuc') ? 'is-invalid' : '' ?>" 
                               id="don_vi_to_chuc" name="don_vi_to_chuc" 
                               value="<?= esc($don_vi_to_chuc) ?>" 
                               placeholder="Nhập đơn vị tổ chức">
                        <?php if (isset($validation) && $validation->hasError('don_vi_to_chuc')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('don_vi_to_chuc') ?>
                            </div>
                        <?php endif ?>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="don_vi_phoi_hop" class="form-label fw-bold">
                            <i class="fas fa-handshake text-primary me-1"></i> Đơn vị phối hợp
                        </label>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('don_vi_phoi_hop') ? 'is-invalid' : '' ?>" 
                                  id="don_vi_phoi_hop" name="don_vi_phoi_hop" 
                                  rows="3" placeholder="Nhập các đơn vị phối hợp tổ chức, mỗi đơn vị một dòng"><?= esc($don_vi_phoi_hop) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('don_vi_phoi_hop')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('don_vi_phoi_hop') ?>
                            </div>
                        <?php endif ?>
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
                               value="<?= esc($tu_khoa_su_kien) ?>" 
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
                               value="<?= esc($hashtag) ?>" 
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
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-calendar-week me-2"></i> Lịch trình sự kiện
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle me-2"></i> Thêm các phiên làm việc, thời gian diễn ra và người phụ trách cho sự kiện.
                </div>
                
                <div id="lich-trinh-container">
                    <?php 
                    if (empty($lich_trinh)) {
                        // Tạo một mục lịch trình mặc định nếu chưa có
                        $lich_trinh = [
                            [
                                'tieu_de' => '',
                                'mo_ta' => '',
                                'thoi_gian_bat_dau' => '',
                                'thoi_gian_ket_thuc' => '',
                                'nguoi_phu_trach' => ''
                            ]
                        ];
                    }
                    
                    foreach ($lich_trinh as $index => $session):
                    ?>
                    <div class="lich-trinh-item border rounded p-3 mb-3" data-index="<?= $index ?>">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Tiêu đề phiên</label>
                                    <input type="text" class="form-control session-title" 
                                           value="<?= esc($session['tieu_de'] ?? '') ?>" 
                                           placeholder="Ví dụ: Khai mạc, Phiên thảo luận 1, Giải lao...">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Thời gian bắt đầu</label>
                                    <input type="datetime-local" class="form-control session-start" 
                                           value="<?= !empty($session['thoi_gian_bat_dau']) ? date('Y-m-d\TH:i', strtotime($session['thoi_gian_bat_dau'])) : '' ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Thời gian kết thúc</label>
                                    <input type="datetime-local" class="form-control session-end" 
                                           value="<?= !empty($session['thoi_gian_ket_thuc']) ? date('Y-m-d\TH:i', strtotime($session['thoi_gian_ket_thuc'])) : '' ?>">
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Mô tả</label>
                                    <textarea class="form-control session-desc" rows="2"
                                              placeholder="Mô tả ngắn gọn về nội dung phiên"><?= esc($session['mo_ta'] ?? '') ?></textarea>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-bold">Người phụ trách</label>
                                    <input type="text" class="form-control session-manager" 
                                           value="<?= esc($session['nguoi_phu_trach'] ?? '') ?>" 
                                           placeholder="Người chịu trách nhiệm cho phiên này">
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end">
                            <button type="button" class="btn btn-sm btn-danger remove-session" <?= ($index === 0 && count($lich_trinh) === 1) ? 'style="display:none"' : '' ?>>
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
                <input type="hidden" name="lich_trinh" id="lich_trinh_json" value='<?= json_encode($lich_trinh) ?>'>
            </div>
        </div>
    </div>

    <!-- Poster sự kiện -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="fas fa-image me-2"></i> Poster Sự kiện
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="poster_upload" class="form-label fw-bold">Tải lên poster sự kiện</label>
                            <input class="form-control" type="file" id="poster_upload" name="poster_upload" accept="image/*">
                            <small class="form-text text-muted">Hỗ trợ các định dạng: JPG, PNG, GIF. Kích thước tối đa: 5MB</small>
                        </div>
                        
                        <?php if (isset($su_kien_poster) && !empty($su_kien_poster)): ?>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Poster hiện tại</label>
                            <div class="row">
                                <?php foreach ((array)$su_kien_poster as $key => $poster): ?>
                                    <?php if (isset($poster['url'])): ?>
                                    <div class="col-md-4 mb-3">
                                        <div class="card">
                                            <img src="<?= esc($poster['url']) ?>" class="card-img-top" alt="Poster sự kiện">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <span class="text-muted small"><?= esc($poster['filename'] ?? 'Poster ' . ($key+1)) ?></span>
                                                    <button type="button" class="btn btn-sm btn-danger remove-poster" data-index="<?= $key ?>">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="su_kien_poster" id="su_kien_poster_json" value='<?= json_encode($su_kien_poster) ?>'>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Buttons -->
    <div class="col-12 mt-3 mb-5">
        <div class="d-flex justify-content-between">
            <a href="<?= site_url('quanlysukien') ?>" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
            <div>
                <button type="reset" class="btn btn-outline-warning btn-lg me-2">
                    <i class="fas fa-redo me-1"></i> Đặt lại
                </button>
                <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                    <i class="fas fa-save me-1"></i> Lưu thông tin
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xóa hàm khởi tạo custom editor
    
    // Tạo slug tự động từ tên sự kiện
    const tenSuKienInput = document.getElementById('ten_su_kien');
    const slugInput = document.getElementById('slug');
    
    if (tenSuKienInput && slugInput) {
        tenSuKienInput.addEventListener('input', function() {
            if (!slugInput.value) { // Chỉ tạo slug nếu trường slug đang trống
                const slug = createSlug(this.value);
                slugInput.value = slug;
            }
        });
    }
    
    function createSlug(str) {
        // Chuyển đổi tiếng Việt sang không dấu
        str = str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
        return str.toLowerCase()
                 .replace(/[^\w ]+/g, '')
                 .replace(/ +/g, '-');
    }

    // Kiểm tra thời gian kết thúc phải sau thời gian bắt đầu
    const thoiGianBatDauSuKien = document.getElementById('thoi_gian_bat_dau_su_kien');
    const thoiGianKetThucSuKien = document.getElementById('thoi_gian_ket_thuc_su_kien');
    
    if (thoiGianBatDauSuKien && thoiGianKetThucSuKien) {
        thoiGianKetThucSuKien.addEventListener('change', function() {
            if (thoiGianBatDauSuKien.value && thoiGianKetThucSuKien.value) {
                if (new Date(thoiGianKetThucSuKien.value) <= new Date(thoiGianBatDauSuKien.value)) {
                    thoiGianKetThucSuKien.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu');
                } else {
                    thoiGianKetThucSuKien.setCustomValidity('');
                }
            }
        });
        
        thoiGianBatDauSuKien.addEventListener('change', function() {
            if (thoiGianBatDauSuKien.value && thoiGianKetThucSuKien.value) {
                if (new Date(thoiGianKetThucSuKien.value) <= new Date(thoiGianBatDauSuKien.value)) {
                    thoiGianKetThucSuKien.setCustomValidity('Thời gian kết thúc phải sau thời gian bắt đầu');
                } else {
                    thoiGianKetThucSuKien.setCustomValidity('');
                }
            }
        });
    }

    // Hiển thị/ẩn các trường online dựa trên hình thức sự kiện
    const hinhThucSelect = document.getElementById('hinh_thuc');
    const onlineFields = document.querySelectorAll('.online-fields');
    
    if (hinhThucSelect) {
        hinhThucSelect.addEventListener('change', function() {
            const isOffline = this.value === 'offline';
            onlineFields.forEach(field => {
                field.style.display = isOffline ? 'none' : 'block';
            });
        });
    }

    // Quản lý lịch trình sự kiện
    const lichTrinhContainer = document.getElementById('lich-trinh-container');
    const addSessionBtn = document.getElementById('add-session-btn');
    const lichTrinhJson = document.getElementById('lich_trinh_json');
    
    if (lichTrinhContainer && addSessionBtn && lichTrinhJson) {
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
                        thoi_gian_bat_dau: sessionStart.value ? new Date(sessionStart.value).toISOString() : '',
                        thoi_gian_ket_thuc: sessionEnd.value ? new Date(sessionEnd.value).toISOString() : '',
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
                                value="" placeholder="Người chịu trách nhiệm cho phiên này">
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
            
            // Cập nhật JSON
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
    }
});
</script> 