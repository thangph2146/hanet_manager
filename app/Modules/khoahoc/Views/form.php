<?php
/**
 * Form component for creating and updating khoa_hoc
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var array $khoa_hoc KhoaHoc entity data for editing (optional)
 */

// Set default values if editing
$ten_khoa_hoc = isset($khoa_hoc) ? $khoa_hoc->getTenKhoaHoc() : '';
$nam_bat_dau = isset($khoa_hoc) ? $khoa_hoc->getNamBatDau() : '';
$nam_ket_thuc = isset($khoa_hoc) ? $khoa_hoc->getNamKetThuc() : '';
$phong_khoa_id = isset($khoa_hoc) ? $khoa_hoc->getPhongKhoaId() : '';
$status = isset($khoa_hoc) ? $khoa_hoc->isActive() : 1;
$id = isset($khoa_hoc) ? $khoa_hoc->getId() : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('khoahoc/create');
$method = isset($method) ? $method : 'POST';
?>

<?= form_open($action, ['class' => 'row g-3 needs-validation', 'novalidate' => true]) ?>
    <?php if (isset($khoa_hoc)): ?>
        <input type="hidden" name="khoa_hoc_id" value="<?= $id ?>">
    <?php endif; ?>
    
    <!-- ten_khoa_hoc -->
    <div class="col-md-12">
        <label for="ten_khoa_hoc" class="form-label">Tên khóa học <span class="text-danger">*</span></label>
        <input type="text" class="form-control <?= session('errors.ten_khoa_hoc') ? 'is-invalid' : '' ?>" 
                id="ten_khoa_hoc" name="ten_khoa_hoc" 
                value="<?= old('ten_khoa_hoc', $ten_khoa_hoc) ?>" 
                required minlength="3" maxlength="100">
        <?php if (session('errors.ten_khoa_hoc')): ?>
            <div class="invalid-feedback">
                <?= session('errors.ten_khoa_hoc') ?>
            </div>
        <?php else: ?>
            <div class="invalid-feedback">Vui lòng nhập tên khóa học (tối thiểu 3 ký tự)</div>
        <?php endif; ?>
    </div>

    <!-- nam_bat_dau -->
    <div class="col-md-6">
        <label for="nam_bat_dau" class="form-label">Năm bắt đầu</label>
        <input type="number" class="form-control <?= session('errors.nam_bat_dau') ? 'is-invalid' : '' ?>" 
                id="nam_bat_dau" name="nam_bat_dau" 
                value="<?= old('nam_bat_dau', $nam_bat_dau) ?>" 
                min="1900" max="<?= date('Y') + 10 ?>">
        <?php if (session('errors.nam_bat_dau')): ?>
            <div class="invalid-feedback">
                <?= session('errors.nam_bat_dau') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- nam_ket_thuc -->
    <div class="col-md-6">
        <label for="nam_ket_thuc" class="form-label">Năm kết thúc</label>
        <input type="number" class="form-control <?= session('errors.nam_ket_thuc') ? 'is-invalid' : '' ?>" 
                id="nam_ket_thuc" name="nam_ket_thuc" 
                value="<?= old('nam_ket_thuc', $nam_ket_thuc) ?>" 
                min="1900" max="<?= date('Y') + 50 ?>">
        <?php if (session('errors.nam_ket_thuc')): ?>
            <div class="invalid-feedback">
                <?= session('errors.nam_ket_thuc') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- phong_khoa_id -->
    <div class="col-md-6">
        <label for="phong_khoa_id" class="form-label">Phòng Khoa</label>
        <select class="form-select <?= session('errors.phong_khoa_id') ? 'is-invalid' : '' ?>" 
                id="phong_khoa_id" name="phong_khoa_id">
            <option value="">-- Chọn phòng/khoa --</option>
            <?php if (isset($phong_khoa_list) && !empty($phong_khoa_list)): ?>
                <?php foreach ($phong_khoa_list as $phong_khoa): ?>
                    <option value="<?= $phong_khoa['phong_khoa_id'] ?>" 
                            <?= old('phong_khoa_id', $phong_khoa_id) == $phong_khoa['phong_khoa_id'] ? 'selected' : '' ?>>
                        <?= esc($phong_khoa['ten_phong_khoa']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <?php if (session('errors.phong_khoa_id')): ?>
            <div class="invalid-feedback">
                <?= session('errors.phong_khoa_id') ?>
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
            <?= isset($khoa_hoc) ? 'Cập nhật' : 'Thêm mới' ?>
        </button>
        <a href="<?= site_url('khoahoc') ?>" class="btn btn-secondary">Hủy</a>
    </div>
<?= form_close() ?> 