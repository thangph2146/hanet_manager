<?php
/**
 * Form component for creating and updating sự kiện diễn giả (event speaker relationship)
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var SuKienDienGia $data SuKienDienGia entity data for editing (optional)
 * @var array $suKienList List of all events
 * @var array $dienGiaList List of all speakers
 */

// Set default values if editing
$su_kien_id = isset($data) ? $data->getSuKienId() : '';
$dien_gia_id = isset($data) ? $data->getDienGiaId() : '';
$thu_tu = isset($data) ? $data->getThuTu() : '';
$vai_tro = isset($data) ? $data->getVaiTro() : '';
$mo_ta = isset($data) ? $data->getMoTa() : '';
$thoi_gian_trinh_bay = isset($data) ? $data->getThoiGianTrinhBayFormatted('Y-m-d\TH:i') : '';
$thoi_gian_ket_thuc = isset($data) ? $data->getThoiGianKetThucFormatted('Y-m-d\TH:i') : '';
$thoi_luong_phut = isset($data) ? $data->getThoiLuongPhut() : '';
$tieu_de_trinh_bay = isset($data) ? $data->getTieuDeTrinhBay() : '';
$tai_lieu_dinh_kem = isset($data) ? $data->getTaiLieuDinhKem() : [];
$trang_thai_tham_gia = isset($data) ? $data->getTrangThaiThamGia() : 'cho_xac_nhan';
$hien_thi_cong_khai = isset($data) ? $data->getHienThiCongKhai() : true;
$ghi_chu = isset($data) ? $data->getGhiChu() : '';
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$su_kien_id = old('su_kien_id', $su_kien_id);
$dien_gia_id = old('dien_gia_id', $dien_gia_id);
$thu_tu = old('thu_tu', $thu_tu);
$vai_tro = old('vai_tro', $vai_tro);
$mo_ta = old('mo_ta', $mo_ta);
$thoi_gian_trinh_bay = old('thoi_gian_trinh_bay', $thoi_gian_trinh_bay);
$thoi_gian_ket_thuc = old('thoi_gian_ket_thuc', $thoi_gian_ket_thuc);
$thoi_luong_phut = old('thoi_luong_phut', $thoi_luong_phut);
$tieu_de_trinh_bay = old('tieu_de_trinh_bay', $tieu_de_trinh_bay);
$tai_lieu_dinh_kem = old('tai_lieu_dinh_kem', $tai_lieu_dinh_kem);
$trang_thai_tham_gia = old('trang_thai_tham_gia', $trang_thai_tham_gia);
$hien_thi_cong_khai = old('hien_thi_cong_khai', $hien_thi_cong_khai);
$ghi_chu = old('ghi_chu', $ghi_chu);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="su_kien_dien_gia_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="su_kien_dien_gia_id" value="0">
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
                Thông tin sự kiện diễn giả
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

                <!-- dien_gia_id -->
                <div class="col-md-6">
                    <label for="dien_gia_id" class="form-label fw-semibold">
                        Diễn giả <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('dien_gia_id') ? 'is-invalid' : '' ?>" 
                            id="dien_gia_id" name="dien_gia_id" required>
                        <option value="">Chọn diễn giả</option>
                        <?php foreach ($dienGiaList as $dienGia): ?>
                            <option value="<?= $dienGia->dien_gia_id ?>" <?= $dien_gia_id == $dienGia->dien_gia_id ? 'selected' : '' ?>>
                                <?= esc($dienGia->ten_dien_gia) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('dien_gia_id')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('dien_gia_id') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- thu_tu -->
                <div class="col-md-6">
                    <label for="thu_tu" class="form-label fw-semibold">
                        Thứ tự
                    </label>
                    <input type="number" 
                           class="form-control <?= isset($validation) && $validation->hasError('thu_tu') ? 'is-invalid' : '' ?>" 
                           id="thu_tu" name="thu_tu"
                           value="<?= esc($thu_tu) ?>"
                           placeholder="Nhập thứ tự">
                    <?php if (isset($validation) && $validation->hasError('thu_tu')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('thu_tu') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- vai_tro -->
                <div class="col-md-6">
                    <label for="vai_tro" class="form-label fw-semibold">
                        Vai trò
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($validation) && $validation->hasError('vai_tro') ? 'is-invalid' : '' ?>" 
                           id="vai_tro" name="vai_tro"
                           value="<?= esc($vai_tro) ?>"
                           placeholder="Nhập vai trò">
                    <?php if (isset($validation) && $validation->hasError('vai_tro')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('vai_tro') ?>
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

                <!-- thoi_gian_trinh_bay -->
                <div class="col-md-6">
                    <label for="thoi_gian_trinh_bay" class="form-label fw-semibold">
                        Thời gian trình bày
                    </label>
                    <input type="datetime-local" 
                           class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_trinh_bay') ? 'is-invalid' : '' ?>" 
                           id="thoi_gian_trinh_bay" name="thoi_gian_trinh_bay"
                           value="<?= esc($thoi_gian_trinh_bay) ?>">
                    <?php if (isset($validation) && $validation->hasError('thoi_gian_trinh_bay')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('thoi_gian_trinh_bay') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- thoi_gian_ket_thuc -->
                <div class="col-md-6">
                    <label for="thoi_gian_ket_thuc" class="form-label fw-semibold">
                        Thời gian kết thúc
                    </label>
                    <input type="datetime-local" 
                           class="form-control <?= isset($validation) && $validation->hasError('thoi_gian_ket_thuc') ? 'is-invalid' : '' ?>" 
                           id="thoi_gian_ket_thuc" name="thoi_gian_ket_thuc"
                           value="<?= esc($thoi_gian_ket_thuc) ?>">
                    <?php if (isset($validation) && $validation->hasError('thoi_gian_ket_thuc')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('thoi_gian_ket_thuc') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- thoi_luong_phut -->
                <div class="col-md-6">
                    <label for="thoi_luong_phut" class="form-label fw-semibold">
                        Thời lượng (phút)
                    </label>
                    <input type="number" 
                           class="form-control <?= isset($validation) && $validation->hasError('thoi_luong_phut') ? 'is-invalid' : '' ?>" 
                           id="thoi_luong_phut" name="thoi_luong_phut"
                           value="<?= esc($thoi_luong_phut) ?>"
                           placeholder="Nhập thời lượng">
                    <?php if (isset($validation) && $validation->hasError('thoi_luong_phut')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('thoi_luong_phut') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- tieu_de_trinh_bay -->
                <div class="col-md-6">
                    <label for="tieu_de_trinh_bay" class="form-label fw-semibold">
                        Tiêu đề trình bày
                    </label>
                    <input type="text" 
                           class="form-control <?= isset($validation) && $validation->hasError('tieu_de_trinh_bay') ? 'is-invalid' : '' ?>" 
                           id="tieu_de_trinh_bay" name="tieu_de_trinh_bay"
                           value="<?= esc($tieu_de_trinh_bay) ?>"
                           placeholder="Nhập tiêu đề trình bày">
                    <?php if (isset($validation) && $validation->hasError('tieu_de_trinh_bay')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('tieu_de_trinh_bay') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- tai_lieu_dinh_kem -->
                <div class="col-md-12">
                    <label for="tai_lieu_dinh_kem" class="form-label fw-semibold">
                        Tài liệu đính kèm
                    </label>
                    <input type="file" 
                           class="form-control <?= isset($validation) && $validation->hasError('tai_lieu_dinh_kem') ? 'is-invalid' : '' ?>" 
                           id="tai_lieu_dinh_kem" name="tai_lieu_dinh_kem[]"
                           multiple>
                    <?php if (isset($validation) && $validation->hasError('tai_lieu_dinh_kem')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('tai_lieu_dinh_kem') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- trang_thai_tham_gia -->
                <div class="col-md-6">
                    <label for="trang_thai_tham_gia" class="form-label fw-semibold">
                        Trạng thái tham gia
                    </label>
                    <select class="form-select <?= isset($validation) && $validation->hasError('trang_thai_tham_gia') ? 'is-invalid' : '' ?>" 
                            id="trang_thai_tham_gia" name="trang_thai_tham_gia">
                        <option value="cho_xac_nhan" <?= $trang_thai_tham_gia == 'cho_xac_nhan' ? 'selected' : '' ?>>Chờ xác nhận</option>
                        <option value="da_xac_nhan" <?= $trang_thai_tham_gia == 'da_xac_nhan' ? 'selected' : '' ?>>Đã xác nhận</option>
                        <option value="huy_bo" <?= $trang_thai_tham_gia == 'huy_bo' ? 'selected' : '' ?>>Hủy bỏ</option>
                    </select>
                    <?php if (isset($validation) && $validation->hasError('trang_thai_tham_gia')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('trang_thai_tham_gia') ?>
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

                <!-- ghi_chu -->
                <div class="col-md-12">
                    <label for="ghi_chu" class="form-label fw-semibold">
                        Ghi chú
                    </label>
                    <textarea class="form-control <?= isset($validation) && $validation->hasError('ghi_chu') ? 'is-invalid' : '' ?>" 
                              id="ghi_chu" name="ghi_chu"
                              rows="4"
                              placeholder="Nhập ghi chú"><?= esc($ghi_chu) ?></textarea>
                    <?php if (isset($validation) && $validation->hasError('ghi_chu')): ?>
                        <div class="invalid-feedback">
                            <?= $validation->getError('ghi_chu') ?>
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
                form.classList.add('was-validated');
            }, false);
        });
        
        // Tự động focus vào trường đầu tiên
        document.getElementById('su_kien_id').focus();
    });
</script>