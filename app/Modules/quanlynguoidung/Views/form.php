<?php
/**
 * Form component for creating and updating người dùng
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var NguoiDung $data NguoiDung entity data for editing (optional)
 */

// Set default values if editing
$AccountId = isset($data) ? $data->getField('AccountId') : '';
$FullName = isset($data) ? $data->getField('FullName') : '';
$LastName = isset($data) ? $data->getField('LastName') : '';
$MiddleName = isset($data) ? $data->getField('MiddleName') : '';
$FirstName = isset($data) ? $data->getField('FirstName') : '';
$Email = isset($data) ? $data->getField('Email') : '';
$MobilePhone = isset($data) ? $data->getField('MobilePhone') : '';
$AccountType = isset($data) ? $data->getField('AccountType') : '';
$HomePhone1 = isset($data) ? $data->getField('HomePhone1') : '';
$HomePhone = isset($data) ? $data->getField('HomePhone') : '';
$PW = isset($data) ? $data->getField('PW') : '';
$mat_khau_local = isset($data) ? $data->getField('mat_khau_local') : '';
$u_id = isset($data) ? $data->getField('u_id') : '';
$last_login = isset($data) ? $data->getLastLoginFormatted() : '';
$loai_nguoi_dung_id = isset($data) ? $data->getField('loai_nguoi_dung_id') : '';
$phong_khoa_id = isset($data) ? $data->getField('phong_khoa_id') : '';
$nam_hoc_id = isset($data) ? $data->getField('nam_hoc_id') : '';
$bac_hoc_id = isset($data) ? $data->getField('bac_hoc_id') : '';
$he_dao_tao_id = isset($data) ? $data->getField('he_dao_tao_id') : '';
$nganh_id = isset($data) ? $data->getField('nganh_id') : '';
$status = isset($data) ? (string)$data->getField('status', '1') : '1';
$id = isset($data) ? $data->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url($module_name . '/create');
$method = isset($method) ? $method : 'POST';

// Xác định tiêu đề form dựa trên mode
$isUpdate = isset($data) && $data->getId() > 0;

// Lấy dữ liệu từ old() nếu có
$AccountId = old('AccountId', $AccountId);
$FullName = old('FullName', $FullName);
$LastName = old('LastName', $LastName);
$MiddleName = old('MiddleName', $MiddleName);
$FirstName = old('FirstName', $FirstName);
$Email = old('Email', $Email);
$MobilePhone = old('MobilePhone', $MobilePhone);
$AccountType = old('AccountType', $AccountType);
$HomePhone1 = old('HomePhone1', $HomePhone1);
$HomePhone = old('HomePhone', $HomePhone);
$PW = old('PW', $PW);
$mat_khau_local = old('mat_khau_local', $mat_khau_local);
$u_id = old('u_id', $u_id);
$last_login = old('last_login', $last_login);
$loai_nguoi_dung_id = old('loai_nguoi_dung_id', $loai_nguoi_dung_id);
$phong_khoa_id = old('phong_khoa_id', $phong_khoa_id);
$nam_hoc_id = old('nam_hoc_id', $nam_hoc_id);
$bac_hoc_id = old('bac_hoc_id', $bac_hoc_id);
$he_dao_tao_id = old('he_dao_tao_id', $he_dao_tao_id);
$nganh_id = old('nganh_id', $nganh_id);
$status = old('status', $status);
?>

<!-- Form chính -->
<form action="<?= $action ?>" method="<?= $method ?>" id="form-<?= $module_name ?>" class="needs-validation" enctype="multipart/form-data" novalidate>
    <?= csrf_field() ?>
    
    <?php if ($id): ?>
        <input type="hidden" name="nguoi_dung_id" value="<?= $id ?>">
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

    <!-- Progress bar -->
    <div class="progress mb-4" style="height: 3px;">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class='bx bx-user text-primary me-2'></i>
                    Thông tin người dùng
                </h5>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="togglePassword">
                    <label class="form-check-label" for="togglePassword">Hiển thị mật khẩu</label>
                </div>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row g-3">
                <!-- AccountId -->
                <div class="col-md-6">
                    <label for="AccountId" class="form-label fw-semibold">
                        Tài khoản <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('AccountId') ? 'is-invalid' : '' ?>" 
                            id="AccountId" name="AccountId" 
                            value="<?= esc($AccountId) ?>" 
                            placeholder="Nhập tài khoản"
                            required maxlength="50"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Tài khoản đăng nhập vào hệ thống">
                        <?php if (isset($validation) && $validation->hasError('AccountId')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('AccountId') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tài khoản</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tài khoản tối đa 50 ký tự và phải là duy nhất
                    </div>
                </div>

                <!-- LastName -->
                <div class="col-md-4">
                    <label for="LastName" class="form-label fw-semibold">
                        Họ <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('LastName') ? 'is-invalid' : '' ?>" 
                            id="LastName" name="LastName" 
                            value="<?= esc($LastName) ?>" 
                            placeholder="Nhập họ"
                            required maxlength="100"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Họ của người dùng">
                        <?php if (isset($validation) && $validation->hasError('LastName')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('LastName') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập họ</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- MiddleName -->
                <div class="col-md-4">
                    <label for="MiddleName" class="form-label fw-semibold">
                        Tên đệm
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('MiddleName') ? 'is-invalid' : '' ?>" 
                            id="MiddleName" name="MiddleName" 
                            value="<?= esc($MiddleName) ?>" 
                            placeholder="Nhập tên đệm"
                            maxlength="100"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Tên đệm của người dùng">
                        <?php if (isset($validation) && $validation->hasError('MiddleName')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('MiddleName') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- FirstName -->
                <div class="col-md-4">
                    <label for="FirstName" class="form-label fw-semibold">
                        Tên <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('FirstName') ? 'is-invalid' : '' ?>" 
                            id="FirstName" name="FirstName" 
                            value="<?= esc($FirstName) ?>" 
                            placeholder="Nhập tên"
                            required maxlength="100"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Tên của người dùng">
                        <?php if (isset($validation) && $validation->hasError('FirstName')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('FirstName') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập tên</div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Hidden FullName field to maintain compatibility -->
                <input type="hidden" name="FullName" id="FullName" value="<?= esc($FullName) ?>">
                
                <script>
                // Update FullName field when LastName, MiddleName or FirstName changes
                document.addEventListener('DOMContentLoaded', function() {
                    const lastNameInput = document.getElementById('LastName');
                    const middleNameInput = document.getElementById('MiddleName');
                    const firstNameInput = document.getElementById('FirstName');
                    const fullNameInput = document.getElementById('FullName');
                    
                    const updateFullName = function() {
                        const lastName = lastNameInput.value.trim();
                        const middleName = middleNameInput.value.trim();
                        const firstName = firstNameInput.value.trim();
                        
                        const nameParts = [];
                        if (lastName) nameParts.push(lastName);
                        if (middleName) nameParts.push(middleName);
                        if (firstName) nameParts.push(firstName);
                        
                        fullNameInput.value = nameParts.join(' ');
                    };
                    
                    // Listen for changes in any name field
                    lastNameInput.addEventListener('input', updateFullName);
                    middleNameInput.addEventListener('input', updateFullName);
                    firstNameInput.addEventListener('input', updateFullName);
                    
                    // Initialize on page load if name fields are empty but FullName has a value
                    if ((!lastNameInput.value && !firstNameInput.value) && fullNameInput.value) {
                        // Try to split FullName into name parts
                        const fullNameParts = fullNameInput.value.trim().split(' ');
                        if (fullNameParts.length === 1) {
                            // Only one word, use as FirstName
                            firstNameInput.value = fullNameParts[0];
                        } else if (fullNameParts.length === 2) {
                            // Two words, use as LastName and FirstName
                            lastNameInput.value = fullNameParts[0];
                            firstNameInput.value = fullNameParts[1];
                        } else {
                            // More than two words
                            lastNameInput.value = fullNameParts[0];
                            firstNameInput.value = fullNameParts[fullNameParts.length - 1];
                            middleNameInput.value = fullNameParts.slice(1, fullNameParts.length - 1).join(' ');
                        }
                    }
                });
                </script>

                <!-- Email -->
                <div class="col-md-6">
                    <label for="Email" class="form-label fw-semibold">
                        Email <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-envelope'></i></span>
                        <input type="email" class="form-control <?= isset($validation) && $validation->hasError('Email') ? 'is-invalid' : '' ?>" 
                            id="Email" name="Email" 
                            value="<?= esc($Email) ?>" 
                            placeholder="Nhập email"
                            required maxlength="100"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Địa chỉ email liên hệ">
                        <?php if (isset($validation) && $validation->hasError('Email')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('Email') ?>
                            </div>
                        <?php else: ?>
                            <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Email phải là duy nhất và tối đa 100 ký tự
                    </div>
                </div>

                <!-- MobilePhone -->
                <div class="col-md-6">
                    <label for="MobilePhone" class="form-label fw-semibold">
                        Số điện thoại
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-phone'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('MobilePhone') ? 'is-invalid' : '' ?>" 
                            id="MobilePhone" name="MobilePhone" 
                            value="<?= esc($MobilePhone) ?>" 
                            placeholder="Nhập số điện thoại"
                            maxlength="20"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Số điện thoại di động">
                        <?php if (isset($validation) && $validation->hasError('MobilePhone')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('MobilePhone') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Số điện thoại tối đa 20 ký tự
                    </div>
                </div>

                <!-- AccountType -->
                <div class="col-md-6">
                    <label for="AccountType" class="form-label fw-semibold">
                        Loại tài khoản
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user-check'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('AccountType') ? 'is-invalid' : '' ?>" 
                            id="AccountType" name="AccountType" 
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Phân loại tài khoản người dùng">
                            <option value="">-- Chọn loại tài khoản --</option>
                            <?php if (isset($loaiNguoiDungList) && is_array($loaiNguoiDungList)): ?>
                                <?php foreach ($loaiNguoiDungList as $loai): ?>
                                    <option value="<?= esc($loai->getTenLoai()) ?>" <?= $AccountType === $loai->getTenLoai() ? 'selected' : '' ?>>
                                        <?= esc($loai->getTenLoai()) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('AccountType')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('AccountType') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Chọn loại tài khoản người dùng
                    </div>
                </div>

                <!-- HomePhone1 -->
                <div class="col-md-6">
                    <label for="HomePhone1" class="form-label fw-semibold">
                        Số điện thoại nhà 1
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-phone'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('HomePhone1') ? 'is-invalid' : '' ?>" 
                            id="HomePhone1" name="HomePhone1" 
                            value="<?= esc($HomePhone1) ?>" 
                            placeholder="Nhập số điện thoại nhà 1"
                            maxlength="20"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Số điện thoại cố định 1">
                        <?php if (isset($validation) && $validation->hasError('HomePhone1')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('HomePhone1') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- HomePhone -->
                <div class="col-md-6">
                    <label for="HomePhone" class="form-label fw-semibold">
                        Số điện thoại nhà
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-phone'></i></span>
                        <input type="text" class="form-control <?= isset($validation) && $validation->hasError('HomePhone') ? 'is-invalid' : '' ?>" 
                            id="HomePhone" name="HomePhone" 
                            value="<?= esc($HomePhone) ?>" 
                            placeholder="Nhập số điện thoại nhà"
                            maxlength="20"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Số điện thoại cố định 2">
                        <?php if (isset($validation) && $validation->hasError('HomePhone')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('HomePhone') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Avatar -->
                <div class="col-md-6">
                    <label for="avatar" class="form-label fw-semibold">
                        Ảnh đại diện
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-image'></i></span>
                        <input type="file" class="form-control <?= isset($validation) && $validation->hasError('avatar_file') ? 'is-invalid' : '' ?>" 
                            id="avatar_file" name="avatar_file" 
                            accept="image/*"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Chọn ảnh đại diện">
                        <input type="hidden" name="avatar" value="<?= esc($data->avatar ?? '') ?>">
                        <?php if (isset($validation) && $validation->hasError('avatar_file')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('avatar_file') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if(isset($data) && $data->avatar): ?>
                    <div class="mt-2">
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?= base_url($data->avatar) ?>" alt="Avatar" class="img-thumbnail" style="height: 64px; width: auto;">
                            <span class="text-muted small"><?= $data->avatar ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Tải lên ảnh đại diện (JPG, PNG, GIF)
                    </div>
                </div>

                <!-- PW -->
                <div class="col-md-6">
                    <label for="PW" class="form-label fw-semibold">
                        Mật khẩu
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-lock-alt'></i></span>
                        <input type="password" class="form-control <?= isset($validation) && $validation->hasError('PW') ? 'is-invalid' : '' ?>" 
                            id="PW" name="PW" 
                            value="<?= esc($PW) ?>" 
                            placeholder="Nhập mật khẩu"
                            maxlength="255"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Mật khẩu đăng nhập">
                        <?php if (isset($validation) && $validation->hasError('PW')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('PW') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- mat_khau_local -->
                <div class="col-md-6">
                    <label for="mat_khau_local" class="form-label fw-semibold">
                        Mật khẩu local
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-lock-alt'></i></span>
                        <input type="password" class="form-control <?= isset($validation) && $validation->hasError('mat_khau_local') ? 'is-invalid' : '' ?>" 
                            id="mat_khau_local" name="mat_khau_local" 
                            value="<?= esc($mat_khau_local) ?>" 
                            placeholder="Nhập mật khẩu local"
                            maxlength="255"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Mật khẩu cho hệ thống local">
                        <?php if (isset($validation) && $validation->hasError('mat_khau_local')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('mat_khau_local') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- u_id -->
                <div class="col-md-6">
                    <label for="u_id" class="form-label fw-semibold">
                        ID người dùng
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-id-card'></i></span>
                        <input type="number" class="form-control <?= isset($validation) && $validation->hasError('u_id') ? 'is-invalid' : '' ?>" 
                            id="u_id" name="u_id" 
                            value="<?= esc($u_id) ?>" 
                            placeholder="Nhập ID người dùng"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Mã định danh người dùng">
                        <?php if (isset($validation) && $validation->hasError('u_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('u_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Loại người dùng -->
                <div class="col-md-6">
                    <label for="loai_nguoi_dung_id" class="form-label fw-semibold">
                        Loại người dùng
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-user-check'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('loai_nguoi_dung_id') ? 'is-invalid' : '' ?>" 
                               id="loai_nguoi_dung_id" name="loai_nguoi_dung_id"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Phân loại người dùng trong hệ thống">
                            <option value="">-- Chọn loại người dùng --</option>
                            <?php if (isset($loaiNguoiDungList) && is_array($loaiNguoiDungList)): ?>
                                <?php foreach ($loaiNguoiDungList as $loai): ?>
                                    <option value="<?= $loai->getId() ?>" <?= $loai_nguoi_dung_id == $loai->getId() ? 'selected' : '' ?>>
                                        <?= esc($loai->getTenLoai()) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('loai_nguoi_dung_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('loai_nguoi_dung_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Phòng khoa -->
                <div class="col-md-6">
                    <label for="phong_khoa_id" class="form-label fw-semibold">
                        Phòng khoa
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-building'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('phong_khoa_id') ? 'is-invalid' : '' ?>" 
                               id="phong_khoa_id" name="phong_khoa_id"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Đơn vị phòng/khoa trực thuộc">
                            <option value="">-- Chọn phòng khoa --</option>
                            <?php if (isset($phongKhoaList) && is_array($phongKhoaList)): ?>
                                <?php foreach ($phongKhoaList as $pk): ?>
                                    <option value="<?= $pk->getId() ?>" <?= $phong_khoa_id == $pk->getId() ? 'selected' : '' ?>>
                                        <?= esc($pk->getTenPhongKhoa()) ?>
                                        <?php if (!empty($pk->getMaPhongKhoa())): ?>
                                            (<?= esc($pk->getMaPhongKhoa()) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('phong_khoa_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('phong_khoa_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Năm học -->
                <div class="col-md-6">
                    <label for="nam_hoc_id" class="form-label fw-semibold">
                        Năm học
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-calendar'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('nam_hoc_id') ? 'is-invalid' : '' ?>" 
                               id="nam_hoc_id" name="nam_hoc_id"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Năm học hiện tại">
                            <option value="">-- Chọn năm học --</option>
                            <?php if (isset($namHocList) && is_array($namHocList)): ?>
                                <?php foreach ($namHocList as $nh): ?>
                                    <option value="<?= $nh->getId() ?>" <?= $nam_hoc_id == $nh->getId() ? 'selected' : '' ?>>
                                        <?= esc($nh->getTenNamHoc()) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('nam_hoc_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('nam_hoc_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Bậc học -->
                <div class="col-md-6">
                    <label for="bac_hoc_id" class="form-label fw-semibold">
                        Bậc học
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-graduation-cap'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('bac_hoc_id') ? 'is-invalid' : '' ?>" 
                               id="bac_hoc_id" name="bac_hoc_id"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Bậc học của người dùng">
                            <option value="">-- Chọn bậc học --</option>
                            <?php if (isset($bacHocList) && is_array($bacHocList)): ?>
                                <?php foreach ($bacHocList as $bh): ?>
                                    <option value="<?= $bh->getId() ?>" <?= $bac_hoc_id == $bh->getId() ? 'selected' : '' ?>>
                                        <?= esc($bh->getTenBacHoc()) ?>
                                        <?php if (!empty($bh->getMaBacHoc())): ?>
                                            (<?= esc($bh->getMaBacHoc()) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('bac_hoc_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('bac_hoc_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Hệ đào tạo -->
                <div class="col-md-6">
                    <label for="he_dao_tao_id" class="form-label fw-semibold">
                        Hệ đào tạo
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-book-reader'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('he_dao_tao_id') ? 'is-invalid' : '' ?>" 
                               id="he_dao_tao_id" name="he_dao_tao_id"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Hệ đào tạo của người dùng">
                            <option value="">-- Chọn hệ đào tạo --</option>
                            <?php if (isset($heDaoTaoList) && is_array($heDaoTaoList)): ?>
                                <?php foreach ($heDaoTaoList as $hdt): ?>
                                    <option value="<?= $hdt->getId() ?>" <?= $he_dao_tao_id == $hdt->getId() ? 'selected' : '' ?>>
                                        <?= esc($hdt->getTenHeDaoTao()) ?>
                                        <?php if (!empty($hdt->getMaHeDaoTao())): ?>
                                            (<?= esc($hdt->getMaHeDaoTao()) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('he_dao_tao_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('he_dao_tao_id') ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Ngành -->
                <div class="col-md-6">
                    <label for="nganh_id" class="form-label fw-semibold">
                        Ngành
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-book'></i></span>
                        <select class="form-select <?= isset($validation) && $validation->hasError('nganh_id') ? 'is-invalid' : '' ?>" 
                               id="nganh_id" name="nganh_id"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Ngành học của người dùng">
                            <option value="">-- Chọn ngành --</option>
                            <?php if (isset($nganhList) && is_array($nganhList)): ?>
                                <?php foreach ($nganhList as $n): ?>
                                    <option value="<?= $n->getId() ?>" <?= $nganh_id == $n->getId() ? 'selected' : '' ?>>
                                        <?= esc($n->getTenNganh()) ?>
                                        <?php if (!empty($n->getMaNganh())): ?>
                                            (<?= esc($n->getMaNganh()) ?>)
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('nganh_id')): ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('nganh_id') ?>
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
                               id="status" name="status" required
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Trạng thái hoạt động của tài khoản">
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
                        Trạng thái người dùng trong hệ thống
                    </div>
                </div>

                <!-- last_login -->
                <div class="col-md-6">
                    <label for="last_login" class="form-label fw-semibold">
                        Lần đăng nhập cuối
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class='bx bx-time'></i></span>
                        <input type="datetime-local" 
                            class="form-control" 
                            id="last_login" 
                            name="last_login" 
                            value="<?= esc($last_login) ?>" 
                            placeholder="Chọn thời gian đăng nhập cuối"
                            data-bs-toggle="tooltip"
                            data-bs-placement="top"
                            title="Thời điểm đăng nhập cuối cùng">
                    </div>
                    <div class="form-text text-muted">
                        <i class='bx bx-info-circle me-1'></i>
                        Định dạng: YYYY-MM-DD HH:mm:ss (VD: 2024-03-26 14:30:00)
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
        document.getElementById('AccountId').focus();

        // Khởi tạo tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Xử lý hiển thị/ẩn mật khẩu
        const togglePassword = document.getElementById('togglePassword');
        const passwordFields = document.querySelectorAll('input[type="password"]');
        
        togglePassword.addEventListener('change', function() {
            passwordFields.forEach(field => {
                field.type = this.checked ? 'text' : 'password';
            });
        });

        // Xử lý progress bar khi cuộn form
        const progressBar = document.querySelector('.progress-bar');
        window.addEventListener('scroll', function() {
            const form = document.getElementById('form-<?= $module_name ?>');
            const formRect = form.getBoundingClientRect();
            const formHeight = formRect.height;
            const formTop = formRect.top;
            const windowHeight = window.innerHeight;
            
            const scrolled = Math.abs(formTop);
            const progress = (scrolled / (formHeight - windowHeight)) * 100;
            
            progressBar.style.width = Math.min(100, progress) + '%';
        });
        
        // Xem trước ảnh đại diện
        const avatarFileInput = document.getElementById('avatar_file');
        if (avatarFileInput) {
            avatarFileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Tạo hoặc cập nhật phần tử xem trước
                        let previewContainer = document.querySelector('.avatar-preview');
                        if (!previewContainer) {
                            previewContainer = document.createElement('div');
                            previewContainer.className = 'avatar-preview mt-2';
                            avatarFileInput.parentElement.parentElement.appendChild(previewContainer);
                        }
                        
                        // Cập nhật nội dung
                        previewContainer.innerHTML = `
                            <div class="d-flex align-items-center gap-2">
                                <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="height: 64px; width: auto;">
                                <span class="text-muted small">Xem trước ảnh đại diện</span>
                            </div>
                        `;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script> 