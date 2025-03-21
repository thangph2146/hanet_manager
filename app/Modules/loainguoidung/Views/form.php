<?php
/**
 * Form component for creating and updating loai_nguoi_dung
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var array $loai_nguoi_dung LoaiNguoiDung entity data for editing (optional)
 */

// Set default values if editing
$ten_loai = isset($loai_nguoi_dung) ? $loai_nguoi_dung->getTenLoai() : '';
$mo_ta = isset($loai_nguoi_dung) ? $loai_nguoi_dung->getMoTa() : '';
$status = isset($loai_nguoi_dung) ? $loai_nguoi_dung->isActive() : 1;
$id = isset($loai_nguoi_dung) ? $loai_nguoi_dung->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('loainguoidung/create');
$method = isset($method) ? $method : 'POST';
?>

<?= form_open($action, ['class' => 'row g-3 needs-validation', 'novalidate' => true]) ?>
    <?php if (isset($loai_nguoi_dung)): ?>
        <input type="hidden" name="loai_nguoi_dung_id" value="<?= $id ?>">
    <?php endif; ?>
    
    <!-- ten_loai -->
    <div class="col-md-12">
        <label for="ten_loai" class="form-label">Tên loại người dùng <span class="text-danger">*</span></label>
        <input type="text" class="form-control <?= session('errors.ten_loai') ? 'is-invalid' : '' ?>" 
                id="ten_loai" name="ten_loai" 
                value="<?= old('ten_loai', $ten_loai) ?>" 
                required minlength="3" maxlength="50">
        <?php if (session('errors.ten_loai')): ?>
            <div class="invalid-feedback">
                <?= session('errors.ten_loai') ?>
            </div>
        <?php else: ?>
            <div class="invalid-feedback">Vui lòng nhập tên loại người dùng (tối thiểu 3 ký tự)</div>
        <?php endif; ?>
    </div>

    <!-- mo_ta -->
    <div class="col-md-12">
        <label for="mo_ta" class="form-label">Mô tả</label>
        <textarea class="form-control <?= session('errors.mo_ta') ? 'is-invalid' : '' ?>" 
                    id="mo_ta" name="mo_ta" rows="4"><?= old('mo_ta', $mo_ta) ?></textarea>
        <?php if (session('errors.mo_ta')): ?>
            <div class="invalid-feedback">
                <?= session('errors.mo_ta') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Status -->
    <div class="col-md-6">
        <label for="status" class="form-label">Trạng thái</label>
        <select class="form-select" id="status" name="status">
            <option value="1" <?= old('status', $status) == '1' ? 'selected' : '' ?>>Hoạt động</option>
            <option value="0" <?= old('status', $status) == '0' ? 'selected' : '' ?>>Không hoạt động</option>
        </select>
    </div>

    <div class="col-12">
        <button class="btn btn-primary" type="submit">
            <?= isset($loai_nguoi_dung) ? 'Cập nhật' : 'Thêm mới' ?>
        </button>
        <a href="<?= site_url('loainguoidung') ?>" class="btn btn-secondary">Hủy</a>
    </div>
<?= form_close() ?> 