<?php
/**
 * Form component for creating and updating sự kiện
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var SuKien $data SuKien entity data for editing (optional)
 */

// Set default values if editing
$ten_su_kien = isset($data) ? $data->getTenSuKien() : '';
$su_kien_poster = isset($data) ? $data->getSuKienPoster() : [];
$mo_ta = isset($data) ? $data->getMoTa() : '';
$mo_ta_su_kien = isset($data) ? $data->getMoTaSuKien() : '';
$chi_tiet_su_kien = isset($data) ? $data->getChiTietSuKien() : '';
$thoi_gian_bat_dau = isset($data) && $data->getThoiGianBatDau() ? $data->getThoiGianBatDau()->format('Y-m-d\TH:i') : '';
$thoi_gian_ket_thuc = isset($data) && $data->getThoiGianKetThuc() ? $data->getThoiGianKetThuc()->format('Y-m-d\TH:i') : '';
$dia_diem = isset($data) ? $data->getDiaDiem() : '';
$dia_chi_cu_the = isset($data) ? $data->getDiaChiCuThe() : '';
$toa_do_gps = isset($data) ? $data->getToaDoGPS() : '';
$loai_su_kien_id = isset($data) ? $data->getLoaiSuKienId() : '';
$ma_qr_code = isset($data) ? $data->getMaQRCode() : '';
$status = isset($data) ? $data->getStatus() : 1;
$tong_dang_ky = isset($data) ? $data->getTongDangKy() : 0;
$tong_check_in = isset($data) ? $data->getTongCheckIn() : 0;
$tong_check_out = isset($data) ? $data->getTongCheckOut() : 0;
$cho_phep_check_in = isset($data) ? $data->getChoPhepCheckIn() : true;
$cho_phep_check_out = isset($data) ? $data->getChoPhepCheckOut() : true;
$yeu_cau_face_id = isset($data) ? $data->getYeuCauFaceId() : false;
$cho_phep_checkin_thu_cong = isset($data) ? $data->getChoPhepCheckinThuCong() : true;
$bat_dau_dang_ky = isset($data) && $data->getBatDauDangKy() ? $data->getBatDauDangKy()->format('Y-m-d\TH:i') : '';
$ket_thuc_dang_ky = isset($data) && $data->getKetThucDangKy() ? $data->getKetThucDangKy()->format('Y-m-d\TH:i') : '';
$han_huy_dang_ky = isset($data) && $data->getHanHuyDangKy() ? $data->getHanHuyDangKy()->format('Y-m-d\TH:i') : '';
$gio_bat_dau = isset($data) && $data->getGioBatDau() ? $data->getGioBatDau()->format('Y-m-d\TH:i') : '';
$gio_ket_thuc = isset($data) && $data->getGioKetThuc() ? $data->getGioKetThuc()->format('Y-m-d\TH:i') : '';
$so_luong_tham_gia = isset($data) ? $data->getSoLuongThamGia() : 0;
$so_luong_dien_gia = isset($data) ? $data->getSoLuongDienGia() : 0;
$gioi_han_loai_nguoi_dung = isset($data) ? $data->getGioiHanLoaiNguoiDung() : '';
$tu_khoa_su_kien = isset($data) ? $data->getTuKhoaSuKien() : '';
$hashtag = isset($data) ? $data->getHashtag() : '';
$slug = isset($data) ? $data->getSlug() : '';
$so_luot_xem = isset($data) ? $data->getSoLuotXem() : 0;
$lich_trinh = isset($data) ? $data->getLichTrinh() : [];
$hinh_thuc = isset($data) ? $data->getHinhThuc() : 'offline';
$link_online = isset($data) ? $data->getLinkOnline() : '';
$mat_khau_online = isset($data) ? $data->getMatKhauOnline() : '';
$version = isset($data) ? $data->getVersion() : 1;
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$ten_su_kien = old('ten_su_kien', $ten_su_kien);
$su_kien_poster = old('su_kien_poster', $su_kien_poster);
$mo_ta = old('mo_ta', $mo_ta);
$mo_ta_su_kien = old('mo_ta_su_kien', $mo_ta_su_kien);
$chi_tiet_su_kien = old('chi_tiet_su_kien', $chi_tiet_su_kien);
$thoi_gian_bat_dau = old('thoi_gian_bat_dau', $thoi_gian_bat_dau);
$thoi_gian_ket_thuc = old('thoi_gian_ket_thuc', $thoi_gian_ket_thuc);
$dia_diem = old('dia_diem', $dia_diem);
$dia_chi_cu_the = old('dia_chi_cu_the', $dia_chi_cu_the);
$toa_do_gps = old('toa_do_gps', $toa_do_gps);
$loai_su_kien_id = old('loai_su_kien_id', $loai_su_kien_id);
$ma_qr_code = old('ma_qr_code', $ma_qr_code);
$status = old('status', $status);
$cho_phep_check_in = old('cho_phep_check_in', $cho_phep_check_in);
$cho_phep_check_out = old('cho_phep_check_out', $cho_phep_check_out);
$yeu_cau_face_id = old('yeu_cau_face_id', $yeu_cau_face_id);
$cho_phep_checkin_thu_cong = old('cho_phep_checkin_thu_cong', $cho_phep_checkin_thu_cong);
$bat_dau_dang_ky = old('bat_dau_dang_ky', $bat_dau_dang_ky);
$ket_thuc_dang_ky = old('ket_thuc_dang_ky', $ket_thuc_dang_ky);
$han_huy_dang_ky = old('han_huy_dang_ky', $han_huy_dang_ky);
$gio_bat_dau = old('gio_bat_dau', $gio_bat_dau);
$gio_ket_thuc = old('gio_ket_thuc', $gio_ket_thuc);
$so_luong_tham_gia = old('so_luong_tham_gia', $so_luong_tham_gia);
$so_luong_dien_gia = old('so_luong_dien_gia', $so_luong_dien_gia);
$gioi_han_loai_nguoi_dung = old('gioi_han_loai_nguoi_dung', $gioi_han_loai_nguoi_dung);
$tu_khoa_su_kien = old('tu_khoa_su_kien', $tu_khoa_su_kien);
$hashtag = old('hashtag', $hashtag);
$slug = old('slug', $slug);
$lich_trinh = old('lich_trinh', $lich_trinh);
$hinh_thuc = old('hinh_thuc', $hinh_thuc);
$link_online = old('link_online', $link_online);
$mat_khau_online = old('mat_khau_online', $mat_khau_online);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="su_kien_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="su_kien_id" value="0">
    <?php endif; ?>
    
    <!-- Thêm hidden field cho version -->
    <input type="hidden" name="version" value="<?= $version ?>">
    
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

    <div class="bg-light py-3">
        <div class="d-flex justify-content-between align-items-center container-fluid">
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

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-fill" id="eventTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="basic-info-tab" data-bs-toggle="tab" data-bs-target="#basic-info" type="button" role="tab" aria-controls="basic-info" aria-selected="true">
                                    <i class='bx bx-info-circle me-1'></i>
                                    Thông tin cơ bản
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="datetime-location-tab" data-bs-toggle="tab" data-bs-target="#datetime-location" type="button" role="tab" aria-controls="datetime-location" aria-selected="false">
                                    <i class='bx bx-calendar-event me-1'></i>
                                    Thời gian & Địa điểm
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="registration-tab" data-bs-toggle="tab" data-bs-target="#registration" type="button" role="tab" aria-controls="registration" aria-selected="false">
                                    <i class='bx bx-edit me-1'></i>
                                    Đăng ký & Tham gia
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="checkinout-tab" data-bs-toggle="tab" data-bs-target="#checkinout" type="button" role="tab" aria-controls="checkinout" aria-selected="false">
                                    <i class='bx bx-check-shield me-1'></i>
                                    Check-in/Check-out
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seo-content-tab" data-bs-toggle="tab" data-bs-target="#seo-content" type="button" role="tab" aria-controls="seo-content" aria-selected="false">
                                    <i class='bx bx-search-alt me-1'></i>
                                    SEO & Nội dung
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content p-3" id="eventTabContent">

                            <!-- Tab Thông tin cơ bản -->
                            <div class="tab-pane fade show active" id="basic-info" role="tabpanel" aria-labelledby="basic-info-tab">
                                <div class="row g-3">
                                    <!-- ten_su_kien -->
                                    <div class="col-md-12">
                                        <label for="ten_su_kien" class="form-label fw-semibold">
                                            Tên sự kiện <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-calendar-edit'></i></span>
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
                                            Tên sự kiện là bắt buộc, tối đa 255 ký tự
                                        </div>
                                    </div>

                                    <!-- su_kien_poster -->
                                    <div class="col-md-12">
                                        <label for="su_kien_poster" class="form-label fw-semibold">
                                            Poster sự kiện
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-image'></i></span>
                                            <input type="file"
                                                class="form-control <?= isset($validation) && $validation->hasError('su_kien_poster') ? 'is-invalid' : '' ?>"
                                                id="su_kien_poster" name="su_kien_poster"
                                                accept="image/*">
                                            <?php if (isset($validation) && $validation->hasError('su_kien_poster')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('su_kien_poster') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($su_kien_poster)): ?>
                                        <div class="mt-2">
                                            <?php if (is_array($su_kien_poster) || is_object($su_kien_poster)): ?>
                                            <?php if (isset($su_kien_poster->path) || (is_array($su_kien_poster) && isset($su_kien_poster['path']))): ?>
                                            <?php
                                                    $posterPath = is_object($su_kien_poster) ? $su_kien_poster->path : $su_kien_poster['path'];
                                                    ?>
                                            <img src="<?= base_url($posterPath) ?>" alt="Poster sự kiện" class="img-thumbnail" style="max-width: 200px;">
                                            <?php endif; ?>
                                            <?php else: ?>
                                            <img src="<?= base_url('uploads/sukien/' . $su_kien_poster) ?>" alt="Poster sự kiện" class="img-thumbnail" style="max-width: 200px;">
                                            <?php endif; ?>
                                        </div>
                                        <?php endif; ?>
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
                                                rows="3" placeholder="Nhập mô tả ngắn về sự kiện"><?= esc($mo_ta) ?></textarea>
                                            <?php if (isset($validation) && $validation->hasError('mo_ta')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('mo_ta') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text text-muted">
                                            <i class='bx bx-info-circle me-1'></i>
                                            Mô tả ngắn gọn về sự kiện (tối đa 500 ký tự)
                                        </div>
                                    </div>

                                    <!-- mo_ta_su_kien -->
                                    <div class="col-md-12">
                                        <label for="mo_ta_su_kien" class="form-label fw-semibold">
                                            Mô tả chi tiết sự kiện
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-detail'></i></span>
                                            <textarea class="form-control <?= isset($validation) && $validation->hasError('mo_ta_su_kien') ? 'is-invalid' : '' ?>"
                                                id="mo_ta_su_kien" name="mo_ta_su_kien"
                                                rows="4"
                                                placeholder="Nhập mô tả chi tiết sự kiện"><?= esc($mo_ta_su_kien) ?></textarea>
                                            <?php if (isset($validation) && $validation->hasError('mo_ta_su_kien')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('mo_ta_su_kien') ?>
                                            </div>
                                            <?php endif; ?>
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
                                                <?php if (!empty($loaiSuKienList)): ?>
                                                <?php foreach ($loaiSuKienList as $loaiSuKien): ?>
                                                <option value="<?= $loaiSuKien->loai_su_kien_id ?>" <?= $loai_su_kien_id == $loaiSuKien->loai_su_kien_id ? 'selected' : '' ?>>
                                                    <?= esc($loaiSuKien->ten_loai_su_kien) ?>
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

                                    <!-- hinh_thuc -->
                                    <div class="col-md-6">
                                        <label for="hinh_thuc" class="form-label fw-semibold">
                                            Hình thức tổ chức <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-laptop'></i></span>
                                            <select class="form-select <?= isset($validation) && $validation->hasError('hinh_thuc') ? 'is-invalid' : '' ?>"
                                                id="hinh_thuc" name="hinh_thuc" required>
                                                <option value="offline" <?= $hinh_thuc == 'offline' ? 'selected' : '' ?>>Trực tiếp (Offline)</option>
                                                <option value="online" <?= $hinh_thuc == 'online' ? 'selected' : '' ?>>Trực tuyến (Online)</option>
                                                <option value="hybrid" <?= $hinh_thuc == 'hybrid' ? 'selected' : '' ?>>Kết hợp (Hybrid)</option>
                                            </select>
                                            <?php if (isset($validation) && $validation->hasError('hinh_thuc')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('hinh_thuc') ?>
                                            </div>
                                            <?php else: ?>
                                            <div class="invalid-feedback">Vui lòng chọn hình thức tổ chức</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Thời gian & Địa điểm -->
                            <div class="tab-pane fade" id="datetime-location" role="tabpanel" aria-labelledby="datetime-location-tab">
                                <div class="row g-3">
                                    <!-- thoi_gian_bat_dau -->
                                    <div class="col-md-6">
                                        <label for="thoi_gian_bat_dau" class="form-label fw-semibold">
                                            Thời gian bắt đầu <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-calendar-plus'></i></span>
                                            <input type="datetime-local"
                                                class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_bat_dau') ? 'is-invalid' : '' ?>"
                                                id="thoi_gian_bat_dau" name="thoi_gian_bat_dau"
                                                value="<?= esc($thoi_gian_bat_dau) ?>"
                                                required>
                                            <?php if (isset($validation) && $validation->hasError('thoi_gian_bat_dau')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('thoi_gian_bat_dau') ?>
                                            </div>
                                            <?php else: ?>
                                            <div class="invalid-feedback">Vui lòng chọn thời gian bắt đầu</div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- thoi_gian_ket_thuc -->
                                    <div class="col-md-6">
                                        <label for="thoi_gian_ket_thuc" class="form-label fw-semibold">
                                            Thời gian kết thúc <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-calendar-check'></i></span>
                                            <input type="datetime-local"
                                                class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_ket_thuc') ? 'is-invalid' : '' ?>"
                                                id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc"
                                                value="<?= esc($thoi_gian_ket_thuc) ?>"
                                                required>
                                            <?php if (isset($validation) && $validation->hasError('thoi_gian_ket_thuc')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('thoi_gian_ket_thuc') ?>
                                            </div>
                                            <?php else: ?>
                                            <div class="invalid-feedback">Vui lòng chọn thời gian kết thúc</div>
                                            <?php endif; ?>
                                        </div>
                                        <div id="time-error-message" class="text-danger small mt-1" style="display: none;"></div>
                                    </div>

                                    <!-- gio_bat_dau -->
                                    <div class="col-md-6">
                                        <label for="gio_bat_dau" class="form-label fw-semibold">
                                            Giờ bắt đầu chính xác
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-time'></i></span>
                                            <input type="datetime-local"
                                                class="form-control <?= isset($validation) && $validation->hasError('gio_bat_dau') ? 'is-invalid' : '' ?>"
                                                id="gio_bat_dau" name="gio_bat_dau"
                                                value="<?= esc($gio_bat_dau) ?>">
                                            <?php if (isset($validation) && $validation->hasError('gio_bat_dau')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('gio_bat_dau') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text text-muted">
                                            <i class='bx bx-info-circle me-1'></i>
                                            Giờ bắt đầu chính xác của sự kiện (nếu khác với thời gian bắt đầu)
                                        </div>
                                    </div>

                                    <!-- gio_ket_thuc -->
                                    <div class="col-md-6">
                                        <label for="gio_ket_thuc" class="form-label fw-semibold">
                                            Giờ kết thúc chính xác
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-time-five'></i></span>
                                            <input type="datetime-local"
                                                class="form-control <?= isset($validation) && $validation->hasError('gio_ket_thuc') ? 'is-invalid' : '' ?>"
                                                id="gio_ket_thuc" name="gio_ket_thuc"
                                                value="<?= esc($gio_ket_thuc) ?>">
                                            <?php if (isset($validation) && $validation->hasError('gio_ket_thuc')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('gio_ket_thuc') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text text-muted">
                                            <i class='bx bx-info-circle me-1'></i>
                                            Giờ kết thúc chính xác của sự kiện (nếu khác với thời gian kết thúc)
                                        </div>
                                    </div>

                                    <!-- dia_diem -->
                                    <div class="col-md-12">
                                        <label for="dia_diem" class="form-label fw-semibold">
                                            Địa điểm
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-map'></i></span>
                                            <input type="text"
                                                class="form-control <?= isset($validation) && $validation->hasError('dia_diem') ? 'is-invalid' : '' ?>"
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
                                    <div class="col-md-12">
                                        <label for="dia_chi_cu_the" class="form-label fw-semibold">
                                            Địa chỉ chi tiết
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-map'></i></span>
                                            <input type="text"
                                                class="form-control <?= isset($validation) && $validation->hasError('dia_chi_cu_the') ? 'is-invalid' : '' ?>"
                                                id="dia_chi_cu_the" name="dia_chi_cu_the"
                                                value="<?= esc($dia_chi_cu_the) ?>"
                                                placeholder="Nhập địa chỉ chi tiết"
                                                maxlength="255">
                                            <?php if (isset($validation) && $validation->hasError('dia_chi_cu_the')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('dia_chi_cu_the') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- toa_do_gps -->
                                    <div class="col-md-12">
                                        <label for="toa_do_gps" class="form-label fw-semibold">
                                            Toạ độ GPS
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-map'></i></span>
                                            <input type="text"
                                                class="form-control <?= isset($validation) && $validation->hasError('toa_do_gps') ? 'is-invalid' : '' ?>"
                                                id="toa_do_gps" name="toa_do_gps"
                                                value="<?= esc($toa_do_gps) ?>"
                                                placeholder="Nhập toạ độ GPS"
                                                maxlength="255">
                                            <?php if (isset($validation) && $validation->hasError('toa_do_gps')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('toa_do_gps') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Đăng ký & Tham gia -->
                            <div class="tab-pane fade" id="registration" role="tabpanel" aria-labelledby="registration-tab">
                                <div class="row g-3">
                                    <!-- bat_dau_dang_ky -->
                                    <div class="col-md-6">
                                        <label for="bat_dau_dang_ky" class="form-label fw-semibold">
                                            Thời gian bắt đầu đăng ký
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-calendar-plus'></i></span>
                                            <input type="datetime-local"
                                                class="form-control <?= isset($validation) && $validation->hasError('bat_dau_dang_ky') ? 'is-invalid' : '' ?>"
                                                id="bat_dau_dang_ky" name="bat_dau_dang_ky"
                                                value="<?= esc($bat_dau_dang_ky) ?>">
                                            <?php if (isset($validation) && $validation->hasError('bat_dau_dang_ky')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('bat_dau_dang_ky') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- ket_thuc_dang_ky -->
                                    <div class="col-md-6">
                                        <label for="ket_thuc_dang_ky" class="form-label fw-semibold">
                                            Thời gian kết thúc đăng ký
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-calendar-x'></i></span>
                                            <input type="datetime-local"
                                                class="form-control <?= isset($validation) && $validation->hasError('ket_thuc_dang_ky') ? 'is-invalid' : '' ?>"
                                                id="ket_thuc_dang_ky" name="ket_thuc_dang_ky"
                                                value="<?= esc($ket_thuc_dang_ky) ?>">
                                            <?php if (isset($validation) && $validation->hasError('ket_thuc_dang_ky')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('ket_thuc_dang_ky') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- han_huy_dang_ky -->
                                    <div class="col-md-6">
                                        <label for="han_huy_dang_ky" class="form-label fw-semibold">
                                            Hạn chót hủy đăng ký
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-calendar-exclamation'></i></span>
                                            <input type="datetime-local"
                                                class="form-control <?= isset($validation) && $validation->hasError('han_huy_dang_ky') ? 'is-invalid' : '' ?>"
                                                id="han_huy_dang_ky" name="han_huy_dang_ky"
                                                value="<?= esc($han_huy_dang_ky) ?>">
                                            <?php if (isset($validation) && $validation->hasError('han_huy_dang_ky')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('han_huy_dang_ky') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- so_luong_tham_gia (Giới hạn số người tham gia) -->
                                    <div class="col-md-6">
                                        <label for="so_luong_tham_gia" class="form-label fw-semibold">
                                            Giới hạn số người tham gia
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-group'></i></span>
                                            <input type="number"
                                                class="form-control <?= isset($validation) && $validation->hasError('so_luong_tham_gia') ? 'is-invalid' : '' ?>"
                                                id="so_luong_tham_gia" name="so_luong_tham_gia"
                                                value="<?= esc($so_luong_tham_gia) ?>"
                                                min="0">
                                            <?php if (isset($validation) && $validation->hasError('so_luong_tham_gia')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('so_luong_tham_gia') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text text-muted">
                                            <i class='bx bx-info-circle me-1'></i>
                                            Để 0 nếu không giới hạn số lượng
                                        </div>
                                    </div>

                                    <!-- gioi_han_loai_nguoi_dung -->
                                    <div class="col-md-12">
                                        <label for="gioi_han_loai_nguoi_dung" class="form-label fw-semibold">
                                            Giới hạn loại người dùng được tham gia
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-user-check'></i></span>
                                            <input type="text"
                                                class="form-control <?= isset($validation) && $validation->hasError('gioi_han_loai_nguoi_dung') ? 'is-invalid' : '' ?>"
                                                id="gioi_han_loai_nguoi_dung" name="gioi_han_loai_nguoi_dung"
                                                value="<?= esc($gioi_han_loai_nguoi_dung) ?>"
                                                placeholder="VD: Sinh viên, Giảng viên, Nhân viên">
                                            <?php if (isset($validation) && $validation->hasError('gioi_han_loai_nguoi_dung')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('gioi_han_loai_nguoi_dung') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text text-muted">
                                            <i class='bx bx-info-circle me-1'></i>
                                            Để trống nếu không giới hạn loại người dùng
                                        </div>
                                    </div>

                                    <!-- link_online -->
                                    <div class="col-md-12" id="online-link-field" style="display: none;">
                                        <label for="link_online" class="form-label fw-semibold">
                                            Link trực tuyến
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-link'></i></span>
                                            <input type="text"
                                                class="form-control <?= isset($validation) && $validation->hasError('link_online') ? 'is-invalid' : '' ?>"
                                                id="link_online" name="link_online"
                                                value="<?= esc($link_online) ?>"
                                                placeholder="Nhập link trực tuyến">
                                            <?php if (isset($validation) && $validation->hasError('link_online')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('link_online') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- mat_khau_online -->
                                    <div class="col-md-6" id="online-password-field" style="display: none;">
                                        <label for="mat_khau_online" class="form-label fw-semibold">
                                            Mật khẩu trực tuyến
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-lock'></i></span>
                                            <input type="text"
                                                class="form-control <?= isset($validation) && $validation->hasError('mat_khau_online') ? 'is-invalid' : '' ?>"
                                                id="mat_khau_online" name="mat_khau_online"
                                                value="<?= esc($mat_khau_online) ?>"
                                                placeholder="Nhập mật khẩu trực tuyến">
                                            <?php if (isset($validation) && $validation->hasError('mat_khau_online')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('mat_khau_online') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Check-in/Check-out -->
                            <div class="tab-pane fade" id="checkinout" role="tabpanel" aria-labelledby="checkinout-tab">
                                <div class="row g-3">
                                    <!-- ma_qr_code -->
                                    <div class="col-md-12">
                                        <label for="ma_qr_code" class="form-label fw-semibold">
                                            Mã QR code điểm danh
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-qr'></i></span>
                                            <input type="text"
                                                class="form-control <?= isset($validation) && $validation->hasError('ma_qr_code') ? 'is-invalid' : '' ?>"
                                                id="ma_qr_code" name="ma_qr_code"
                                                value="<?= esc($ma_qr_code) ?>"
                                                placeholder="Nhập mã QR code hoặc để trống để tạo tự động">
                                            <?php if (isset($validation) && $validation->hasError('ma_qr_code')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('ma_qr_code') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text text-muted">
                                            <i class='bx bx-info-circle me-1'></i>
                                            Mã QR code dùng để điểm danh. Để trống sẽ được tạo tự động
                                        </div>
                                    </div>

                                    <!-- Các tùy chọn check-in/out -->
                                    <div class="col-md-6">
                                        <div class="card border shadow-none">
                                            <div class="card-header bg-light py-2">
                                                <h6 class="mb-0">Tùy chọn Check-in/out</h6>
                                            </div>
                                            <div class="card-body">
                                                <!-- cho_phep_check_in -->
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" id="cho_phep_check_in" name="cho_phep_check_in" value="1" <?= $cho_phep_check_in ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="cho_phep_check_in">Cho phép check-in</label>
                                                </div>

                                                <!-- cho_phep_check_out -->
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" id="cho_phep_check_out" name="cho_phep_check_out" value="1" <?= $cho_phep_check_out ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="cho_phep_check_out">Cho phép check-out</label>
                                                </div>

                                                <!-- yeu_cau_face_id -->
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" id="yeu_cau_face_id" name="yeu_cau_face_id" value="1" <?= $yeu_cau_face_id ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="yeu_cau_face_id">Yêu cầu nhận diện khuôn mặt</label>
                                                </div>

                                                <!-- cho_phep_checkin_thu_cong -->
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="cho_phep_checkin_thu_cong" name="cho_phep_checkin_thu_cong" value="1" <?= $cho_phep_checkin_thu_cong ? 'checked' : '' ?>>
                                                    <label class="form-check-label" for="cho_phep_checkin_thu_cong">Cho phép check-in thủ công</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Thống kê tham gia (chỉ hiển thị khi chỉnh sửa) -->
                                    <?php if ($isUpdate): ?>
                                    <div class="col-md-6">
                                        <div class="card border shadow-none">
                                            <div class="card-header bg-light py-2">
                                                <h6 class="mb-0">Thống kê tham gia</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-4 text-center">
                                                        <div class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center mb-2" style="width: 60px; height: 60px;">
                                                            <h4 class="mb-0"><?= esc($tong_dang_ky) ?></h4>
                                                        </div>
                                                        <p class="small mb-0">Đăng ký</p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <div class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center mb-2" style="width: 60px; height: 60px;">
                                                            <h4 class="mb-0"><?= esc($tong_check_in) ?></h4>
                                                        </div>
                                                        <p class="small mb-0">Check-in</p>
                                                    </div>
                                                    <div class="col-4 text-center">
                                                        <div class="rounded-circle bg-light d-inline-flex justify-content-center align-items-center mb-2" style="width: 60px; height: 60px;">
                                                            <h4 class="mb-0"><?= esc($tong_check_out) ?></h4>
                                                        </div>
                                                        <p class="small mb-0">Check-out</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Tab SEO & Nội dung -->
                            <div class="tab-pane fade" id="seo-content" role="tabpanel" aria-labelledby="seo-content-tab">
                                <div class="row g-3">
                                    <!-- slug -->
                                    <div class="col-md-6">
                                        <label for="slug" class="form-label fw-semibold">
                                            Slug (đường dẫn thân thiện)
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class='bx bx-link'></i></span>
                                            <input type="text"
                                                class="form-control <?= isset($validation) && $validation->hasError('slug') ? 'is-invalid' : '' ?>"
                                                id="slug" name="slug"
                                                value="<?= esc($slug) ?>"
                                                placeholder="Sinh tự động nếu để trống">
                                            <?php if (isset($validation) && $validation->hasError('slug')): ?>
                                            <div class="invalid-feedback">
                                                <?= $validation->getError('slug') ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-text text-muted">
                                            <i class='bx bx-info-circle me-1'></i>
                        Các từ khóa ngăn cách bởi dấu phẩy, dùng để tìm kiếm
                    </div>
                </div>

                <!-- hashtag -->
                <div class="col-md-6">
                    <label for="hashtag" class="form-label fw-semibold">
                        Hashtag
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-hash'></i></span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('hashtag') ? 'is-invalid' : '' ?>" 
                               id="hashtag" name="hashtag"
                               value="<?= esc($hashtag) ?>"
                               placeholder="Nhập hashtag ngăn cách bởi dấu phẩy">
                        <?php if (isset($validation) && $validation->hasError('hashtag')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('hashtag') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Không cần nhập dấu # trước hashtag
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card: Nội dung chi tiết -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class='bx bx-detail text-primary me-2'></i>
                Nội dung chi tiết
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- chi_tiet_su_kien -->
                <div class="col-md-12">
                    <label for="chi_tiet_su_kien" class="form-label fw-semibold">
                        Chi tiết sự kiện
                    </label>
                    <textarea class="form-control editor <?= isset($validation) && $validation->hasError('chi_tiet_su_kien') ? 'is-invalid' : '' ?>" 
                              id="chi_tiet_su_kien" name="chi_tiet_su_kien" 
                              rows="8"><?= esc($chi_tiet_su_kien) ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('chi_tiet_su_kien')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('chi_tiet_su_kien') ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Lịch trình -->
                <div class="col-md-12 mt-4">
                    <div class="card card-body border-0 bg-light mb-0">
                        <h6 class="fw-semibold mb-3">
                            <i class='bx bx-calendar-event text-primary me-1'></i>
                            Lịch trình chi tiết
                        </h6>
                        
                        <div id="lich-trinh-container">
                            <?php 
                            $lichTrinh = is_string($lich_trinh) ? json_decode($lich_trinh, true) : $lich_trinh;
                            
                            // Kiểm tra xem lịch trình có phải là mảng hợp lệ không
                            $lichTrinhItems = [];
                            
                            // Nếu là cấu trúc mảng theo thuộc tính
                            if (isset($lichTrinh['thoi_gian']) && is_array($lichTrinh['thoi_gian'])) {
                                $totalItems = count($lichTrinh['thoi_gian']);
                                for ($i = 0; $i < $totalItems; $i++) {
                                    $lichTrinhItems[] = [
                                        'thoi_gian' => $lichTrinh['thoi_gian'][$i] ?? '',
                                        'noi_dung' => $lichTrinh['noi_dung'][$i] ?? '',
                                        'ghi_chu' => $lichTrinh['ghi_chu'][$i] ?? ''
                                    ];
                                }
                            } 
                            // Nếu là mảng các đối tượng
                            else if (is_array($lichTrinh) && isset($lichTrinh[0])) {
                                foreach ($lichTrinh as $item) {
                                    if (is_array($item) || is_object($item)) {
                                        $item = (array)$item;
                                        $lichTrinhItems[] = [
                                            'thoi_gian' => $item['thoi_gian'] ?? '',
                                            'noi_dung' => $item['noi_dung'] ?? '',
                                            'ghi_chu' => $item['ghi_chu'] ?? ''
                                        ];
                                    }
                                }
                            }
                            
                            // Nếu không có dữ liệu, tạo một mục mặc định
                            if (empty($lichTrinhItems)) {
                                $now = date('Y-m-d\TH:i');
                                $lichTrinhItems[] = [
                                    'thoi_gian' => $now,
                                    'noi_dung' => '',
                                    'ghi_chu' => ''
                                ];
                            }
                            
                            // Hiển thị các mục lịch trình
                            foreach ($lichTrinhItems as $index => $item): 
                            ?>
                            <div class="lich-trinh-item mb-3">
                                <div class="card border shadow-none">
                                    <div class="card-body p-3">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Thời gian</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class='bx bx-calendar-event'></i></span>
                                                    <input type="datetime-local" class="form-control lich-trinh-thoi-gian" 
                                                        value="<?= esc($item['thoi_gian']) ?>" 
                                                        placeholder="Chọn thời gian">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Nội dung</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class='bx bx-notepad'></i></span>
                                                    <input type="text" class="form-control lich-trinh-noi-dung" 
                                                        value="<?= esc($item['noi_dung']) ?>" 
                                                        placeholder="Nhập nội dung">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Ghi chú</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light"><i class='bx bx-message-detail'></i></span>
                                                    <input type="text" class="form-control lich-trinh-ghi-chu" 
                                                        value="<?= esc($item['ghi_chu']) ?>" 
                                                        placeholder="Nhập ghi chú">
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger w-100 remove-lich-trinh">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <input type="hidden" name="lich_trinh" id="lich_trinh_json" 
                            value='<?= is_array($lich_trinh) ? json_encode($lich_trinh) : esc($lich_trinh) ?>'>
                        
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-success" id="add-lich-trinh">
                                <i class='bx bx-plus'></i> Thêm lịch trình
                            </button>
                        </div>
                        <div class="form-text text-muted">
                            <i class='bx bx-info-circle me-1'></i>
                            Thêm lịch trình chi tiết cho sự kiện, bao gồm thời gian và nội dung các hoạt động
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nút lưu -->
    <div class="d-flex justify-content-between">
        <a href="<?= site_url($module_name) ?>" class="btn btn-secondary">
            <i class='bx bx-arrow-back me-1'></i> Quay lại
        </a>
        <button type="submit" class="btn btn-primary">
            <i class='bx bx-save me-1'></i> Lưu lại
        </button>
    </div>
</form>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    // Xử lý hiển thị/ẩn các phần tương ứng với hình thức sự kiện
    function toggleEventTypeSections() {
        var hinhThuc = $('#hinh_thuc').val();
        
        // Hiển thị/ẩn trường Online dựa vào hình thức
        if (hinhThuc === 'online' || hinhThuc === 'hybrid') {
            $('#online-link-field, #online-password-field').show();
        } else {
            $('#online-link-field, #online-password-field').hide();
        }
    }
    
    // Gọi hàm khi trang tải và khi thay đổi hình thức
    toggleEventTypeSections();
    $('#hinh_thuc').on('change', toggleEventTypeSections);
    
    // Xử lý kiểm tra thời gian bắt đầu và kết thúc
    function validateDates() {
        var startDateStr = $('#thoi_gian_bat_dau').val();
        var endDateStr = $('#thoi_gian_ket_thuc').val();
        
        // Nếu một trong hai trường không có giá trị, không cần kiểm tra
        if (!startDateStr || !endDateStr) {
            $('#time-error-message').hide();
            return true;
        }
        
        var startDate = new Date(startDateStr);
        var endDate = new Date(endDateStr);
        
        // Kiểm tra nếu thời gian kết thúc trước thời gian bắt đầu
        if (endDate <= startDate) {
            // Hiển thị thông báo lỗi
            $('#time-error-message').text('Thời gian kết thúc phải sau thời gian bắt đầu').show();
            return false;
        } else {
            // Ẩn thông báo lỗi nếu có
            $('#time-error-message').hide();
            return true;
        }
    }
    
    // Gắn sự kiện kiểm tra thời gian khi thay đổi
    $('#thoi_gian_bat_dau, #thoi_gian_ket_thuc').on('change', validateDates);
    
    // Tự động điền thời gian đăng ký dựa trên thời gian sự kiện
    $('#thoi_gian_bat_dau').on('change', function() {
        var startDate = new Date($(this).val());
        
        // Nếu chưa có thời gian bắt đầu đăng ký, tự động điền
        if ($('#bat_dau_dang_ky').val() === '') {
            // Mặc định bắt đầu đăng ký trước 7 ngày
            var regStartDate = new Date(startDate);
            regStartDate.setDate(startDate.getDate() - 7);
            
            // Format lại theo định dạng datetime-local
            $('#bat_dau_dang_ky').val(formatDateForInput(regStartDate));
        }
        
        // Nếu chưa có thời gian kết thúc đăng ký, tự động điền
        if ($('#ket_thuc_dang_ky').val() === '') {
            // Mặc định kết thúc đăng ký trước 1 ngày
            var regEndDate = new Date(startDate);
            regEndDate.setDate(startDate.getDate() - 1);
            
            // Format lại theo định dạng datetime-local
            $('#ket_thuc_dang_ky').val(formatDateForInput(regEndDate));
        }
    });
    
    // Hàm hỗ trợ format date cho input datetime-local
    function formatDateForInput(date) {
        var year = date.getFullYear();
        var month = ('0' + (date.getMonth() + 1)).slice(-2);
        var day = ('0' + date.getDate()).slice(-2);
        var hours = ('0' + date.getHours()).slice(-2);
        var minutes = ('0' + date.getMinutes()).slice(-2);
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
    
    // Tự động tạo slug từ tên sự kiện
    $('#ten_su_kien').on('blur', function() {
        if ($('#slug').val() === '') {
            var tenSuKien = $(this).val();
            var slug = tenSuKien.toLowerCase()
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd')
                .replace(/[^a-z0-9]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
                
            $('#slug').val(slug);
        }
    });
    
    // Xử lý lịch trình với cách tiếp cận mới
    const lichTrinhContainer = document.getElementById('lich-trinh-container');
    const addLichTrinhButton = document.getElementById('add-lich-trinh');
    const hiddenLichTrinhInput = document.getElementById('lich_trinh_json');

    // Hàm cập nhật JSON lịch trình
    function updateLichTrinhJSON() {
        const items = lichTrinhContainer.querySelectorAll('.lich-trinh-item');
        let data = [];
        
        items.forEach(item => {
            const thoiGian = item.querySelector('.lich-trinh-thoi-gian').value;
            const noiDung = item.querySelector('.lich-trinh-noi-dung').value;
            const ghiChu = item.querySelector('.lich-trinh-ghi-chu').value;
            
            data.push({
                thoi_gian: thoiGian,
                noi_dung: noiDung,
                ghi_chu: ghiChu
            });
        });
        
        // Sắp xếp theo thời gian
        data.sort((a, b) => {
            return new Date(a.thoi_gian) - new Date(b.thoi_gian);
        });
        
        hiddenLichTrinhInput.value = JSON.stringify(data);
    }

    // Thêm lịch trình mới
    addLichTrinhButton.addEventListener('click', function() {
        const now = new Date();
        const year = now.getFullYear();
        const month = ('0' + (now.getMonth() + 1)).slice(-2);
        const day = ('0' + now.getDate()).slice(-2);
        const hours = ('0' + now.getHours()).slice(-2);
        const minutes = ('0' + now.getMinutes()).slice(-2);
        const currentDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;

        const newItem = document.createElement('div');
        newItem.className = 'lich-trinh-item mb-3';
        newItem.innerHTML = `
            <div class="card border shadow-none">
                <div class="card-body p-3">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Thời gian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class='bx bx-calendar-event'></i></span>
                                <input type="datetime-local" class="form-control lich-trinh-thoi-gian" 
                                    value="${currentDateTime}" 
                                    placeholder="Chọn thời gian">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nội dung</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class='bx bx-notepad'></i></span>
                                <input type="text" class="form-control lich-trinh-noi-dung" 
                                    placeholder="Nhập nội dung">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Ghi chú</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class='bx bx-message-detail'></i></span>
                                <input type="text" class="form-control lich-trinh-ghi-chu" 
                                    placeholder="Nhập ghi chú">
                            </div>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger w-100 remove-lich-trinh">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        lichTrinhContainer.appendChild(newItem);
        
        // Gắn sự kiện cho các elements mới
        newItem.querySelector('.lich-trinh-thoi-gian').addEventListener('input', updateLichTrinhJSON);
        newItem.querySelector('.lich-trinh-noi-dung').addEventListener('input', updateLichTrinhJSON);
        newItem.querySelector('.lich-trinh-ghi-chu').addEventListener('input', updateLichTrinhJSON);
        newItem.querySelector('.remove-lich-trinh').addEventListener('click', function() {
            const item = this.closest('.lich-trinh-item');
            if (lichTrinhContainer.querySelectorAll('.lich-trinh-item').length > 1) {
                if (confirm('Bạn có chắc chắn muốn xóa mục lịch trình này?')) {
                    // Thêm hiệu ứng fade out
                    item.style.transition = 'opacity 0.5s';
                    item.style.opacity = '0';
                    setTimeout(() => {
                        item.remove();
                        updateLichTrinhJSON();
                    }, 500);
                }
            } else {
                // Nếu chỉ còn 1 phần tử, xóa dữ liệu nhưng giữ lại phần tử
                item.querySelector('.lich-trinh-thoi-gian').value = getCurrentDateTime();
                item.querySelector('.lich-trinh-noi-dung').value = '';
                item.querySelector('.lich-trinh-ghi-chu').value = '';
                updateLichTrinhJSON();
            }
        });
        
        // Cập nhật JSON
        updateLichTrinhJSON();
        
        // Focus vào trường nội dung
        newItem.querySelector('.lich-trinh-noi-dung').focus();
    });

    // Hàm hỗ trợ lấy thời gian hiện tại theo định dạng datetime-local
    function getCurrentDateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = ('0' + (now.getMonth() + 1)).slice(-2);
        const day = ('0' + now.getDate()).slice(-2);
        const hours = ('0' + now.getHours()).slice(-2);
        const minutes = ('0' + now.getMinutes()).slice(-2);
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
    
    // Khởi tạo trình soạn thảo nếu có
    if (typeof ClassicEditor !== 'undefined') {
        ClassicEditor
            .create(document.querySelector('#chi_tiet_su_kien'))
            .catch(error => {
                console.error(error);
            });
    }
});
</script>
<?= $this->endSection() ?> 