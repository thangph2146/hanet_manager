<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= isset($user) ? 'CẬP NHẬT NGƯỜI DÙNG' : 'THÊM MỚI NGƯỜI DÙNG' ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <?= form_open_multipart(isset($user) ? "nguoidung/update/{$user->id}" : 'nguoidung/store', ['class' => 'row g-3 needs-validation', 'novalidate' => true]) ?>
            
            <!-- AccountId -->
            <div class="col-md-6">
                <label for="AccountId" class="form-label">Mã người dùng <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="AccountId" name="AccountId" 
                       value="<?= old('AccountId', isset($user) ? $user->AccountId : '') ?>" required>
                <div class="invalid-feedback">Vui lòng nhập mã người dùng</div>
            </div>

            <!-- FullName -->
            <div class="col-md-6">
                <label for="FullName" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="FullName" name="FullName" 
                       value="<?= old('FullName', isset($user) ? $user->FullName : '') ?>" required>
                <div class="invalid-feedback">Vui lòng nhập họ và tên</div>
            </div>

            <!-- FirstName -->
            <div class="col-md-6">
                <label for="FirstName" class="form-label">Tên</label>
                <input type="text" class="form-control" id="FirstName" name="FirstName" 
                       value="<?= old('FirstName', isset($user) ? $user->FirstName : '') ?>">
            </div>

            <!-- Email -->
            <div class="col-md-6">
                <label for="Email" class="form-label">Email</label>
                <input type="email" class="form-control" id="Email" name="Email" 
                       value="<?= old('Email', isset($user) ? $user->Email : '') ?>">
                <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
            </div>

            <!-- MobilePhone -->
            <div class="col-md-4">
                <label for="MobilePhone" class="form-label">Điện thoại di động</label>
                <input type="tel" class="form-control" id="MobilePhone" name="MobilePhone" 
                       value="<?= old('MobilePhone', isset($user) ? $user->MobilePhone : '') ?>">
            </div>

            <!-- HomePhone -->
            <div class="col-md-4">
                <label for="HomePhone" class="form-label">Điện thoại nhà</label>
                <input type="tel" class="form-control" id="HomePhone" name="HomePhone" 
                       value="<?= old('HomePhone', isset($user) ? $user->HomePhone : '') ?>">
            </div>

            <!-- HomePhone1 -->
            <div class="col-md-4">
                <label for="HomePhone1" class="form-label">Điện thoại phụ</label>
                <input type="tel" class="form-control" id="HomePhone1" name="HomePhone1" 
                       value="<?= old('HomePhone1', isset($user) ? $user->HomePhone1 : '') ?>">
            </div>

            <!-- Password fields - only show on create -->
            <?php if (!isset($user)): ?>
            <div class="col-md-6">
                <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password" name="password" required>
                <div class="invalid-feedback">Vui lòng nhập mật khẩu</div>
            </div>

            <div class="col-md-6">
                <label for="password_confirm" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                <div class="invalid-feedback">Vui lòng xác nhận mật khẩu</div>
            </div>
            <?php endif; ?>

            <!-- AccountType -->
            <div class="col-md-6">
                <label for="AccountType" class="form-label">Loại tài khoản</label>
                <input type="text" class="form-control" id="AccountType" name="AccountType" 
                       value="<?= old('AccountType', isset($user) ? $user->AccountType : '') ?>">
            </div>

            <!-- loai_nguoi_dung_id -->
            <div class="col-md-6">
                <label for="loai_nguoi_dung_id" class="form-label">Loại người dùng</label>
                <select class="form-select" id="loai_nguoi_dung_id" name="loai_nguoi_dung_id">
                    <option value="">Chọn loại người dùng</option>
                    <!-- Add options dynamically from your database -->
                </select>
            </div>

            <!-- nam_hoc_id -->
            <div class="col-md-6">
                <label for="nam_hoc_id" class="form-label">Năm học</label>
                <select class="form-select" id="nam_hoc_id" name="nam_hoc_id">
                    <option value="">Chọn năm học</option>
                    <!-- Add options dynamically from your database -->
                </select>
            </div>

            <!-- bac_hoc_id -->
            <div class="col-md-6">
                <label for="bac_hoc_id" class="form-label">Bậc học</label>
                <select class="form-select" id="bac_hoc_id" name="bac_hoc_id">
                    <option value="">Chọn bậc học</option>
                    <!-- Add options dynamically from your database -->
                </select>
            </div>

            <!-- he_dao_tao_id -->
            <div class="col-md-6">
                <label for="he_dao_tao_id" class="form-label">Hệ đào tạo</label>
                <select class="form-select" id="he_dao_tao_id" name="he_dao_tao_id">
                    <option value="">Chọn hệ đào tạo</option>
                    <!-- Add options dynamically from your database -->
                </select>
            </div>

            <!-- nganh_id -->
            <div class="col-md-6">
                <label for="nganh_id" class="form-label">Ngành</label>
                <select class="form-select" id="nganh_id" name="nganh_id">
                    <option value="">Chọn ngành</option>
                    <!-- Add options dynamically from your database -->
                </select>
            </div>

            <!-- phong_khoa_id -->
            <div class="col-md-6">
                <label for="phong_khoa_id" class="form-label">Phòng/Khoa</label>
                <select class="form-select" id="phong_khoa_id" name="phong_khoa_id">
                    <option value="">Chọn phòng/khoa</option>
                    <!-- Add options dynamically from your database -->
                </select>
            </div>

            <!-- Status -->
            <div class="col-md-6">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-select" id="status" name="status">
                    <option value="1" <?= old('status', isset($user) ? $user->status : '1') == '1' ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="0" <?= old('status', isset($user) ? $user->status : '') == '0' ? 'selected' : '' ?>>Khóa</option>
                </select>
            </div>

            <div class="col-12">
                <button class="btn btn-primary" type="submit">
                    <?= isset($user) ? 'Cập nhật' : 'Thêm mới' ?>
                </button>
                <a href="<?= site_url('nguoidung') ?>" class="btn btn-secondary">Hủy</a>
            </div>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
// Form validation
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
})()
</script>
<?= $this->endSection() ?> 