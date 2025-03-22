<?php
/**
 * Form component for creating and updating bac_hoc
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var array $bac_hoc BacHoc entity data for editing (optional)
 */

// Set default values if editing
$ten_bac_hoc = isset($bac_hoc) ? $bac_hoc->getTenBacHoc() : '';
$ma_bac_hoc = isset($bac_hoc) ? $bac_hoc->getMaBacHoc() : '';
$status = isset($bac_hoc) ? $bac_hoc->isActive() : 1;
$id = isset($bac_hoc) ? $bac_hoc->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('bachoc/create');
$method = isset($method) ? $method : 'POST';
?>

<?= form_open($action, ['class' => 'row g-3 needs-validation', 'novalidate' => true]) ?>
    <?php if (isset($bac_hoc)): ?>
        <input type="hidden" name="bac_hoc_id" value="<?= $id ?>">
    <?php endif; ?>
    
    <!-- ten_bac_hoc -->
    <div class="col-md-12">
        <label for="ten_bac_hoc" class="form-label">Tên bậc học <span class="text-danger">*</span></label>
        <input type="text" class="form-control <?= session('errors.ten_bac_hoc') ? 'is-invalid' : '' ?>" 
                id="ten_bac_hoc" name="ten_bac_hoc" 
                value="<?= old('ten_bac_hoc', $ten_bac_hoc) ?>" 
                required minlength="3" maxlength="100">
        <?php if (session('errors.ten_bac_hoc')): ?>
            <div class="invalid-feedback">
                <?= session('errors.ten_bac_hoc') ?>
            </div>
        <?php else: ?>
            <div class="invalid-feedback">Vui lòng nhập tên bậc học (tối thiểu 3 ký tự)</div>
        <?php endif; ?>
    </div>
    
    <!-- ma_bac_hoc -->
    <div class="col-md-6">
        <label for="ma_bac_hoc" class="form-label">Mã bậc học</label>
        <input type="text" class="form-control <?= session('errors.ma_bac_hoc') ? 'is-invalid' : '' ?>" 
                id="ma_bac_hoc" name="ma_bac_hoc" 
                value="<?= old('ma_bac_hoc', $ma_bac_hoc) ?>" 
                maxlength="20">
        <?php if (session('errors.ma_bac_hoc')): ?>
            <div class="invalid-feedback">
                <?= session('errors.ma_bac_hoc') ?>
            </div>
        <?php else: ?>
            <div class="invalid-feedback">Mã bậc học không được vượt quá 20 ký tự</div>
        <?php endif; ?>
        <div class="form-text">Mã bậc học không bắt buộc</div>
    </div>

    <!-- Status -->
    <div class="col-md-6">
        <label for="status" class="form-label">Trạng thái</label>
        <select class="form-select" id="status" name="status">
            <option value="1" <?= old('status', $status) == '1' ? 'selected' : '' ?>>Hoạt động</option>
            <option value="0" <?= old('status', $status) == '0' ? 'selected' : '' ?>>Không hoạt động</option>
        </select>
    </div>

    <div class="col-12 mt-4">
        <button class="btn btn-primary" type="submit">
            <?= isset($bac_hoc) ? 'Cập nhật' : 'Thêm mới' ?>
        </button>
        <a href="<?= site_url('bachoc') ?>" class="btn btn-secondary">Hủy</a>
    </div>
<?= form_close() ?> 