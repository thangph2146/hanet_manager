<?php
/**
 * Form component for creating and updating diễn giả
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var DienGia $data DienGia entity data for editing (optional)
 */

// Set default values if editing
$ten_dien_gia = isset($data) ? $data->getTenDienGia() : '';
$chuc_danh = isset($data) ? $data->getChucDanh() : '';
$to_chuc = isset($data) ? $data->getToChuc() : '';
$gioi_thieu = isset($data) ? $data->getGioiThieu() : '';
$avatar = isset($data) ? $data->getAvatar() : '';
$email = isset($data) ? $data->getEmail() : '';
$dien_thoai = isset($data) ? $data->getDienThoai() : '';
$website = isset($data) ? $data->getWebsite() : '';
$chuyen_mon = isset($data) ? $data->getChuyenMon() : '';
$thanh_tuu = isset($data) ? $data->getThanhTuu() : '';
$mang_xa_hoi = isset($data) ? $data->getMangXaHoi() : [];
$status = isset($data) ? $data->getStatus() : 1;
$id = isset($data) ? $data->getId() : '';
$so_su_kien_tham_gia = isset($data) ? $data->getSoSuKienThamGia() : 0;

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy giá trị từ old() nếu có
$ten_dien_gia = old('ten_dien_gia', $ten_dien_gia);
$chuc_danh = old('chuc_danh', $chuc_danh);
$to_chuc = old('to_chuc', $to_chuc);
$gioi_thieu = old('gioi_thieu', $gioi_thieu);
$avatar = old('avatar', $avatar);
$email = old('email', $email);
$dien_thoai = old('dien_thoai', $dien_thoai);
$website = old('website', $website);
$chuyen_mon = old('chuyen_mon', $chuyen_mon);
$thanh_tuu = old('thanh_tuu', $thanh_tuu);
$mang_xa_hoi = old('mang_xa_hoi', $mang_xa_hoi);
$status = old('status', $status);
$so_su_kien_tham_gia = old('so_su_kien_tham_gia', $so_su_kien_tham_gia);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" novalidate enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="dien_gia_id" value="<?= $id ?>">
    <?php else: ?>
        <input type="hidden" name="dien_gia_id" value="0">
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
                Thông tin diễn giả
            </h5>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- ten_dien_gia -->
                <div class="col-md-12">
                    <label for="ten_dien_gia" class="form-label fw-semibold">
                        Tên diễn giả <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_dien_gia') ? 'is-invalid' : '' ?>" 
                            id="ten_dien_gia" name="ten_dien_gia" 
                            value="<?= esc($ten_dien_gia) ?>" 
                            placeholder="Nhập tên diễn giả"
                            required maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('ten_dien_gia')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_dien_gia') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên diễn giả</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tên diễn giả là bắt buộc, tối đa 255 ký tự
                    </div>
                </div>

                <!-- chuc_danh -->
                <div class="col-md-6">
                    <label for="chuc_danh" class="form-label fw-semibold">
                        Chức danh
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-briefcase'></i></span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('chuc_danh') ? 'is-invalid' : '' ?>" 
                               id="chuc_danh" name="chuc_danh"
                               value="<?= esc($chuc_danh) ?>"
                               placeholder="Nhập chức danh"
                               maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('chuc_danh')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('chuc_danh') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- to_chuc -->
                <div class="col-md-6">
                    <label for="to_chuc" class="form-label fw-semibold">
                        Tổ chức
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-building'></i></span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('to_chuc') ? 'is-invalid' : '' ?>" 
                               id="to_chuc" name="to_chuc"
                               value="<?= esc($to_chuc) ?>"
                               placeholder="Nhập tổ chức"
                               maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('to_chuc')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('to_chuc') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- email -->
                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">
                        Email
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-envelope'></i></span>
                        <input type="email" 
                               class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                               id="email" name="email"
                               value="<?= esc($email) ?>"
                               placeholder="Nhập email"
                               maxlength="100">
                        <?php if (isset($validation) && $validation->hasError('email')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('email') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- dien_thoai -->
                <div class="col-md-6">
                    <label for="dien_thoai" class="form-label fw-semibold">
                        Số điện thoại
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-phone'></i></span>
                        <input type="text" 
                               class="form-control <?= isset($validation) && $validation->hasError('dien_thoai') ? 'is-invalid' : '' ?>" 
                               id="dien_thoai" name="dien_thoai"
                               value="<?= esc($dien_thoai) ?>"
                               placeholder="Nhập số điện thoại"
                               maxlength="20">
                        <?php if (isset($validation) && $validation->hasError('dien_thoai')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('dien_thoai') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- website -->
                <div class="col-md-12">
                    <label for="website" class="form-label fw-semibold">
                        Website
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-globe'></i></span>
                        <input type="url" 
                               class="form-control <?= isset($validation) && $validation->hasError('website') ? 'is-invalid' : '' ?>" 
                               id="website" name="website"
                               value="<?= esc($website) ?>"
                               placeholder="Nhập website"
                               maxlength="255">
                        <?php if (isset($validation) && $validation->hasError('website')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('website') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- gioi_thieu -->
                <div class="col-md-12">
                    <label for="gioi_thieu" class="form-label fw-semibold">
                        Giới thiệu
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-detail'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('gioi_thieu') ? 'is-invalid' : '' ?>" 
                                  id="gioi_thieu" name="gioi_thieu"
                                  rows="4"
                                  placeholder="Nhập giới thiệu"><?= esc($gioi_thieu) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('gioi_thieu')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('gioi_thieu') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- chuyen_mon -->
                <div class="col-md-12">
                    <label for="chuyen_mon" class="form-label fw-semibold">
                        Chuyên môn
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-brain'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('chuyen_mon') ? 'is-invalid' : '' ?>" 
                                  id="chuyen_mon" name="chuyen_mon"
                                  rows="4"
                                  placeholder="Nhập chuyên môn"><?= esc($chuyen_mon) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('chuyen_mon')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('chuyen_mon') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- thanh_tuu -->
                <div class="col-md-12">
                    <label for="thanh_tuu" class="form-label fw-semibold">
                        Thành tựu
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-trophy'></i></span>
                        <textarea class="form-control <?= isset($validation) && $validation->hasError('thanh_tuu') ? 'is-invalid' : '' ?>" 
                                  id="thanh_tuu" name="thanh_tuu"
                                  rows="4"
                                  placeholder="Nhập thành tựu"><?= esc($thanh_tuu) ?></textarea>
                        <?php if (isset($validation) && $validation->hasError('thanh_tuu')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('thanh_tuu') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- so_su_kien_tham_gia - Chỉ hiển thị khi chỉnh sửa, không cho phép thay đổi -->
                <?php if ($isUpdate): ?>
                <div class="col-md-12">
                    <label for="so_su_kien_tham_gia" class="form-label fw-semibold">
                        Số sự kiện tham gia
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar-event'></i></span>
                        <input type="text" 
                               class="form-control" 
                               id="so_su_kien_tham_gia"
                               name="so_su_kien_tham_gia"
                               value="<?= esc($so_su_kien_tham_gia) ?>"
                               readonly>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Số sự kiện tham gia được cập nhật tự động và không thể chỉnh sửa trực tiếp
                    </div>
                </div>
                <?php endif; ?>

                <!-- mang_xa_hoi -->
                <div class="col-md-12">
                    <label class="form-label fw-semibold">
                        Mạng xã hội
                    </label>
                    <div class="card border shadow-none">
                        <div class="card-body p-3">
                            <div id="social-media-container">
                                <?php 
                                $socialMedia = is_string($mang_xa_hoi) ? json_decode($mang_xa_hoi, true) : $mang_xa_hoi;
                                if (empty($socialMedia)) {
                                    $socialMedia = [
                                        'facebook' => '',
                                        'linkedin' => '',
                                        'google_scholar' => '',
                                        'researchgate' => ''
                                    ];
                                }
                                foreach ($socialMedia as $platform => $url): 
                                ?>
                                <div class="social-media-item mb-3">
                                    <div class="row g-2">
                                        <div class="col-md-3">
                                            <select class="form-select social-platform">
                                                <option value="facebook" <?= $platform === 'facebook' ? 'selected' : '' ?>>Facebook</option>
                                                <option value="linkedin" <?= $platform === 'linkedin' ? 'selected' : '' ?>>LinkedIn</option>
                                                <option value="google_scholar" <?= $platform === 'google_scholar' ? 'selected' : '' ?>>Google Scholar</option>
                                                <option value="researchgate" <?= $platform === 'researchgate' ? 'selected' : '' ?>>ResearchGate</option>
                                                <option value="github" <?= $platform === 'github' ? 'selected' : '' ?>>GitHub</option>
                                                <option value="stackoverflow" <?= $platform === 'stackoverflow' ? 'selected' : '' ?>>Stack Overflow</option>
                                                <option value="twitter" <?= $platform === 'twitter' ? 'selected' : '' ?>>Twitter</option>
                                                <option value="youtube" <?= $platform === 'youtube' ? 'selected' : '' ?>>YouTube</option>
                                                <option value="other" <?= !in_array($platform, ['facebook', 'linkedin', 'google_scholar', 'researchgate', 'github', 'stackoverflow', 'twitter', 'youtube']) ? 'selected' : '' ?>>Khác</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 platform-name-col" style="display: <?= !in_array($platform, ['facebook', 'linkedin', 'google_scholar', 'researchgate', 'github', 'stackoverflow', 'twitter', 'youtube']) ? 'block' : 'none' ?>;">
                                            <input type="text" class="form-control platform-name" placeholder="Tên nền tảng" value="<?= !in_array($platform, ['facebook', 'linkedin', 'google_scholar', 'researchgate', 'github', 'stackoverflow', 'twitter', 'youtube']) ? $platform : '' ?>">
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class='bx bx-link'></i></span>
                                                <input type="url" class="form-control social-url" placeholder="Nhập URL" value="<?= esc($url) ?>">
                                                <button type="button" class="btn btn-outline-danger remove-social-media">
                                                    <i class='bx bx-trash'></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <input type="hidden" name="mang_xa_hoi" id="mang_xa_hoi_json" value='<?= is_array($mang_xa_hoi) ? json_encode($mang_xa_hoi) : esc($mang_xa_hoi) ?>'>
                            
                            <div class="mt-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="add-social-media">
                                    <i class='bx bx-plus'></i> Thêm mạng xã hội
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="form-text text-muted mt-2">
                        <i class='bx bx-info-circle me-1'></i>
                        Chọn nền tảng mạng xã hội và nhập URL tương ứng
                    </div>
                </div>

                <!-- avatar -->
                <div class="col-md-12">
                    <label for="avatar" class="form-label fw-semibold">
                        Ảnh đại diện
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-image'></i></span>
                        <input type="file" 
                               class="form-control <?= isset($validation) && $validation->hasError('avatar') ? 'is-invalid' : '' ?>" 
                               id="avatar" name="avatar"
                               accept="image/*">
                        <?php if (isset($validation) && $validation->hasError('avatar')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('avatar') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($avatar)): ?>
                        <div class="mt-2">
                            <img src="<?= base_url('uploads/diengia/' . $avatar) ?>" alt="Ảnh đại diện" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    <?php endif; ?>
                </div>

                <!-- status -->
                <div class="col-md-12">
                    <label for="status" class="form-label fw-semibold">
                        Trạng thái <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-toggle-left'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                                id="status" name="status"
                                required>
                            <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Không hoạt động</option>
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
        document.getElementById('ten_dien_gia').focus();
    });

    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('social-media-container');
        const addButton = document.getElementById('add-social-media');
        const hiddenInput = document.getElementById('mang_xa_hoi_json');

        // Hàm cập nhật JSON
        function updateJSON() {
            const items = container.querySelectorAll('.social-media-item');
            const data = {};
            
            items.forEach(item => {
                const platform = item.querySelector('.social-platform').value;
                const url = item.querySelector('.social-url').value;
                const customName = item.querySelector('.platform-name');
                
                if (url) {
                    if (platform === 'other' && customName.value) {
                        data[customName.value] = url;
                    } else if (platform !== 'other') {
                        data[platform] = url;
                    }
                }
            });
            
            hiddenInput.value = JSON.stringify(data);
        }

        // Xử lý hiện/ẩn input tên nền tảng tùy chỉnh
        function toggleCustomPlatform(select) {
            const item = select.closest('.social-media-item');
            const customInput = item.querySelector('.platform-name-col');
            if (select.value === 'other') {
                customInput.style.display = 'block';
                customInput.querySelector('.platform-name').required = true;
            } else {
                customInput.style.display = 'none';
                customInput.querySelector('.platform-name').required = false;
            }
        }

        // Thêm mạng xã hội mới
        addButton.addEventListener('click', function() {
            const newItem = document.createElement('div');
            newItem.className = 'social-media-item mb-3';
            newItem.innerHTML = `
                <div class="row g-2">
                    <div class="col-md-3">
                        <select class="form-select social-platform">
                            <option value="facebook">Facebook</option>
                            <option value="linkedin">LinkedIn</option>
                            <option value="google_scholar">Google Scholar</option>
                            <option value="researchgate">ResearchGate</option>
                            <option value="github">GitHub</option>
                            <option value="stackoverflow">Stack Overflow</option>
                            <option value="twitter">Twitter</option>
                            <option value="youtube">YouTube</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <div class="col-md-3 platform-name-col" style="display: none;">
                        <input type="text" class="form-control platform-name" placeholder="Tên nền tảng">
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class='bx bx-link'></i></span>
                            <input type="url" class="form-control social-url" placeholder="Nhập URL">
                            <button type="button" class="btn btn-outline-danger remove-social-media">
                                <i class='bx bx-trash'></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(newItem);
            
            // Gắn sự kiện cho các elements mới
            const select = newItem.querySelector('.social-platform');
            select.addEventListener('change', function() {
                toggleCustomPlatform(this);
                updateJSON();
            });
            
            newItem.querySelector('.social-url').addEventListener('input', updateJSON);
            newItem.querySelector('.platform-name').addEventListener('input', updateJSON);
            newItem.querySelector('.remove-social-media').addEventListener('click', function() {
                newItem.remove();
                updateJSON();
            });
        });

        // Gắn sự kiện cho các elements có sẵn
        container.querySelectorAll('.social-platform').forEach(select => {
            toggleCustomPlatform(select);
            select.addEventListener('change', function() {
                toggleCustomPlatform(this);
                updateJSON();
            });
        });

        container.querySelectorAll('.social-url').forEach(input => {
            input.addEventListener('input', updateJSON);
        });

        container.querySelectorAll('.platform-name').forEach(input => {
            input.addEventListener('input', updateJSON);
        });

        container.querySelectorAll('.remove-social-media').forEach(button => {
            button.addEventListener('click', function() {
                this.closest('.social-media-item').remove();
                updateJSON();
            });
        });

        // Cập nhật JSON ban đầu
        updateJSON();
    });
</script> 