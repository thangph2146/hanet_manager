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

<div class="card shadow-sm">
    <div class="card-header py-3">
        <h5 class="card-title mb-0">
            <?= isset($he_dao_tao) ? 'Cập nhật hệ đào tạo' : 'Thêm mới hệ đào tạo' ?>
        </h5>
    </div>
    <div class="card-body">
        <?= form_open($action, ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-he-dao-tao']) ?>
            <?php if (isset($he_dao_tao)): ?>
                <input type="hidden" name="he_dao_tao_id" value="<?= $id ?>">
            <?php endif; ?>
            
            <!-- Hiển thị thông báo lỗi nếu có -->
            <?php if (session('error')): ?>
                <div class="col-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class='bx bx-error-circle me-1'></i> <?= session('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- ten_he_dao_tao -->
            <div class="col-md-12 mb-2">
                <label for="ten_he_dao_tao" class="form-label fw-semibold">
                    Tên hệ đào tạo <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-book-alt'></i></span>
                    <input type="text" class="form-control <?= session('errors.ten_he_dao_tao') ? 'is-invalid' : '' ?>" 
                            id="ten_he_dao_tao" name="ten_he_dao_tao" 
                            value="<?= old('ten_he_dao_tao', $ten_he_dao_tao) ?>" 
                            placeholder="Nhập tên hệ đào tạo"
                            required minlength="3" maxlength="100">
                    <?php if (session('errors.ten_he_dao_tao')): ?>
                        <div class="invalid-feedback">
                            <?= session('errors.ten_he_dao_tao') ?>
                        </div>
                    <?php else: ?>
                        <div class="invalid-feedback">Vui lòng nhập tên hệ đào tạo (tối thiểu 3 ký tự)</div>
                    <?php endif; ?>
                </div>
                <div class="form-text">Tên hệ đào tạo không được trùng với các hệ đào tạo khác</div>
            </div>

            <!-- ma_he_dao_tao -->
            <div class="col-md-12 mb-2">
                <label for="ma_he_dao_tao" class="form-label fw-semibold">Mã hệ đào tạo</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-hash'></i></span>
                    <input type="text" class="form-control <?= session('errors.ma_he_dao_tao') ? 'is-invalid' : '' ?>" 
                            id="ma_he_dao_tao" name="ma_he_dao_tao" 
                            value="<?= old('ma_he_dao_tao', $ma_he_dao_tao) ?>" 
                            placeholder="Nhập mã hệ đào tạo (không bắt buộc)"
                            maxlength="20">
                    <?php if (session('errors.ma_he_dao_tao')): ?>
                        <div class="invalid-feedback">
                            <?= session('errors.ma_he_dao_tao') ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-text">Mã hệ đào tạo là tùy chọn, tối đa 20 ký tự</div>
            </div>

            <!-- Status -->
            <div class="col-md-12 mb-2">
                <label for="status" class="form-label fw-semibold">Trạng thái</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-toggle-left'></i></span>
                    <select class="form-select" id="status" name="status">
                        <option value="1" <?= old('status', $status) == '1' ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="0" <?= old('status', $status) == '0' ? 'selected' : '' ?>>Không hoạt động</option>
                    </select>
                </div>
            </div>

            <div class="col-12 mt-4">
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" type="submit">
                        <i class='bx bx-save me-1'></i>
                        <?= isset($he_dao_tao) ? 'Cập nhật' : 'Lưu' ?>
                    </button>
                    <a href="<?= site_url('hedaotao') ?>" class="btn btn-secondary">
                        <i class='bx bx-x me-1'></i> Hủy
                    </a>
                </div>
            </div>
        <?= form_close() ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-he-dao-tao');
        
        // Validate form khi submit
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            form.classList.add('was-validated');
        });
        
        // Tự động focus vào trường đầu tiên
        document.getElementById('ten_he_dao_tao').focus();
    });
</script> 