<?php
// Kiểm tra xem $data có phải là đối tượng hay không
if (is_object($data)) {
    $camera_id = $data->getId() ?? '';
    $ten_camera = $data->getTenCamera() ?? '';
    $ma_camera = $data->getMaCamera() ?? '';
    $ip_camera = $data->getIpCamera() ?? '';
    $status = $data->isActive() ? 1 : 0;
    $mo_ta = $data->attributes['mo_ta'] ?? '';
} else {
    $camera_id = '';
    $ten_camera = '';
    $ma_camera = '';
    $ip_camera = '';
    $status = 1;
    $mo_ta = '';
}
?>

<div class="col-md-6">
    <label for="ten_camera" class="form-label">Tên camera <span class="text-danger">*</span></label>
    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ten_camera') ? 'is-invalid' : '' ?>" id="ten_camera" name="ten_camera" value="<?= $ten_camera ?>" required>
    <?php if (isset($validation) && $validation->hasError('ten_camera')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('ten_camera') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="ma_camera" class="form-label">Mã camera <span class="text-danger">*</span></label>
    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ma_camera') ? 'is-invalid' : '' ?>" id="ma_camera" name="ma_camera" value="<?= $ma_camera ?>" required>
    <?php if (isset($validation) && $validation->hasError('ma_camera')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('ma_camera') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="ip_camera" class="form-label">IP Camera</label>
    <input type="text" class="form-control <?= isset($validation) && $validation->hasError('ip_camera') ? 'is-invalid' : '' ?>" id="ip_camera" name="ip_camera" value="<?= $ip_camera ?>">
    <?php if (isset($validation) && $validation->hasError('ip_camera')) : ?>
        <div class="invalid-feedback">
            <?= $validation->getError('ip_camera') ?>
        </div>
    <?php endif ?>
</div>

<div class="col-md-6">
    <label for="status" class="form-label">Trạng thái</label>
    <select class="form-select" id="status" name="status">
        <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Hoạt động</option>
        <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Không hoạt động</option>
    </select>
</div>

<div class="col-12">
    <label for="mo_ta" class="form-label">Mô tả</label>
    <textarea class="form-control" id="mo_ta" name="mo_ta" rows="3"><?= $mo_ta ?></textarea>
</div>

<div class="col-12 mt-3 d-flex justify-content-end gap-2">
    <button type="submit" class="btn btn-primary">Lưu</button>
    <a href="<?= site_url($module_name) ?>" class="btn btn-secondary">Quay lại</a>
</div> 