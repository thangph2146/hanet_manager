<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= nganh_select2_assets() ?>
<?= nganh_form_style() ?>
<?= $this->endSection() ?>

<?= $this->section('title') ?><?= $title ?? 'THÊM MỚI NGÀNH' ?><?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => isset($nganh) ? 'Cập nhật ngành' : 'Thêm mới ngành',
    'dashboard_url' => site_url('nganh/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Ngành', 'url' => site_url('nganh')],
        ['title' => isset($nganh) ? 'Cập nhật' : 'Thêm mới', 'active' => true]
    ],
    'actions' => [
		['url' => site_url('/nganh'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bx bx-<?= isset($nganh) ? 'edit' : 'plus-circle' ?> me-2"></i>
                    <?= isset($nganh) ? 'Cập nhật ngành' : 'Thêm mới ngành' ?>
                </h5>
            </div>
            <div class="card-body p-4">
                <?php if (service('session')->has('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center fade-in" role="alert">
                        <i class="bx bx-error-circle me-2 fs-5"></i>
                        <div><?= service('session')->get('error') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (service('session')->has('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center fade-in" role="alert">
                        <i class="bx bx-check-circle me-2 fs-5"></i>
                        <div><?= service('session')->get('success') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?= form_open(isset($nganh) ? "nganh/update/{$nganh->nganh_id}" : "nganh/create", ['id' => 'nganh-form', 'class' => 'needs-validation', 'novalidate' => true]) ?>
                
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <label for="ma_nganh" class="form-label required-label">Mã ngành</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-hash"></i></span>
                            <input type="text" class="form-control <?= nganh_invalid_class('ma_nganh') ?>" 
                                   id="ma_nganh" name="ma_nganh" 
                                   value="<?= nganh_old('ma_nganh', isset($nganh) ? $nganh->ma_nganh : '') ?>" 
                                   placeholder="Nhập mã ngành"
                                   required>
                        </div>
                        <?= nganh_error('ma_nganh') ?>
                        <div class="invalid-feedback">Vui lòng nhập mã ngành</div>
                        <div class="form-text">Mã ngành là mã định danh duy nhất, tối thiểu 2 ký tự.</div>
                    </div>
                    
                    <div class="col-12 col-md-6">
                        <label for="ten_nganh" class="form-label required-label">Tên ngành</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-book-alt"></i></span>
                            <input type="text" class="form-control <?= nganh_invalid_class('ten_nganh') ?>" 
                                   id="ten_nganh" name="ten_nganh" 
                                   value="<?= nganh_old('ten_nganh', isset($nganh) ? $nganh->ten_nganh : '') ?>" 
                                   placeholder="Nhập tên ngành"
                                   required>
                        </div>
                        <?= nganh_error('ten_nganh') ?>
                        <div class="invalid-feedback">Vui lòng nhập tên ngành</div>
                        <div class="form-text">Tên ngành phải có từ 3 đến 200 ký tự.</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="phong_khoa_id" class="form-label">Phòng/Khoa</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-building"></i></span>
                            <select class="form-select select2bs5 <?= nganh_invalid_class('phong_khoa_id') ?>" 
                                    id="phong_khoa_id" name="phong_khoa_id"
                                    data-placeholder="-- Chọn phòng/khoa --">
                                <option value="">-- Chọn phòng/khoa --</option>
                                <?php if (!empty($phongkhoas)): ?>
                                    <?php foreach ($phongkhoas as $pk): ?>
                                        <option value="<?= $pk->phong_khoa_id ?>" 
                                            <?= (nganh_old('phong_khoa_id', isset($nganh) ? $nganh->phong_khoa_id : '') == $pk->phong_khoa_id) ? 'selected' : '' ?>>
                                            <?= esc($pk->ten_phong_khoa) ?> (<?= esc($pk->ma_phong_khoa) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <?= nganh_error('phong_khoa_id') ?>
                        <div class="form-text">Chọn phòng/khoa quản lý ngành này (không bắt buộc).</div>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label d-block">Trạng thái</label>
                        <div class="d-flex align-items-center mt-2">
                            <div class="form-check form-switch form-check-inline">
                                <input class="form-check-input" type="checkbox" role="switch" name="status" id="status_toggle" value="1"
                                    <?= nganh_old('status', isset($nganh) ? $nganh->status : '1') == '1' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_toggle">
                                    <span id="status_label" class="badge <?= nganh_old('status', isset($nganh) ? $nganh->status : '1') == '1' ? 'bg-success' : 'bg-secondary' ?>">
                                        <?= nganh_old('status', isset($nganh) ? $nganh->status : '1') == '1' ? 'Hoạt động' : 'Không hoạt động' ?>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <?= nganh_error('status') ?>
                        <div class="form-text">Chỉ những ngành có trạng thái "Hoạt động" mới được hiển thị.</div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="<?= site_url('nganh') ?>" class="btn btn-outline-secondary">
                        <i class="bx bx-x me-1"></i> Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-<?= isset($nganh) ? 'save' : 'plus' ?> me-1"></i>
                        <?= isset($nganh) ? 'Cập nhật' : 'Thêm mới' ?>
                    </button>
                </div>
                
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= nganh_form_script() ?>
<?= $this->endSection() ?> 