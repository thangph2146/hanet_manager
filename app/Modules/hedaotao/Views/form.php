<?php
/**
 * Form component for creating and updating he_dao_tao
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var array $he_dao_tao HeDaoTao entity data for editing (optional)
 */

// Set default values if editing
$ten_he_dao_tao = isset($he_dao_tao) ? $he_dao_tao->getTenHeDaoTao() : '';
$ma_he_dao_tao = isset($he_dao_tao) ? $he_dao_tao->getMaHeDaoTao() : '';
$status = isset($he_dao_tao) ? $he_dao_tao->isActive() : 1;
$id = isset($he_dao_tao) ? $he_dao_tao->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('hedaotao/create');
$method = isset($method) ? $method : 'POST';
?>

<?= form_open($action, ['class' => 'row g-3 needs-validation', 'novalidate' => true]) ?>
    <?php if (isset($he_dao_tao)): ?>
        <input type="hidden" name="he_dao_tao_id" value="<?= $id ?>">
    <?php endif; ?>
    
    <!-- ten_he_dao_tao -->
    <div class="col-md-12">
        <label for="ten_he_dao_tao" class="form-label">Tên hệ đào tạo <span class="text-danger">*</span></label>
        <input type="text" class="form-control <?= session('errors.ten_he_dao_tao') ? 'is-invalid' : '' ?>" 
                id="ten_he_dao_tao" name="ten_he_dao_tao" 
                value="<?= old('ten_he_dao_tao', $ten_he_dao_tao) ?>" 
                required minlength="3" maxlength="100">
        <?php if (session('errors.ten_he_dao_tao')): ?>
            <div class="invalid-feedback">
                <?= session('errors.ten_he_dao_tao') ?>
            </div>
        <?php else: ?>
            <div class="invalid-feedback">Vui lòng nhập tên hệ đào tạo (tối thiểu 3 ký tự)</div>
        <?php endif; ?>
    </div>

    <!-- ma_he_dao_tao -->
    <div class="col-md-12">
        <label for="ma_he_dao_tao" class="form-label">Mã hệ đào tạo</label>
        <input type="text" class="form-control <?= session('errors.ma_he_dao_tao') ? 'is-invalid' : '' ?>" 
                id="ma_he_dao_tao" name="ma_he_dao_tao" 
                value="<?= old('ma_he_dao_tao', $ma_he_dao_tao) ?>" 
                maxlength="20">
        <?php if (session('errors.ma_he_dao_tao')): ?>
            <div class="invalid-feedback">
                <?= session('errors.ma_he_dao_tao') ?>
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
            <?= isset($he_dao_tao) ? 'Cập nhật' : 'Thêm mới' ?>
        </button>
        <a href="<?= site_url('hedaotao') ?>" class="btn btn-secondary">Hủy</a>
    </div>
<?= form_close() ?> 