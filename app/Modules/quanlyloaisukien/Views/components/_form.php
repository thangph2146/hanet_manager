<?php
// Khởi tạo các biến giá trị từ đối tượng entity nếu có
if (isset($entity) && is_object($entity)) {
    $loai_su_kien_id = $entity->getId() ?? '';
    $ten_loai_su_kien = $entity->getTenLoaiSuKien() ?? '';
    $ma_loai_su_kien = $entity->getMaLoaiSuKien() ?? '';
    $status = $entity->getStatus() ?? 1;
} else {
    // Nếu không có đối tượng entity, thiết lập giá trị mặc định
    $loai_su_kien_id = '';
    $ten_loai_su_kien = '';
    $ma_loai_su_kien = '';
    $status = 1;
}

// Xác định action form
$action = site_url($module_name . ($loai_su_kien_id ? '/update/' . $loai_su_kien_id : '/create'));
?>

<style>
.form-card {
    transition: all 0.3s ease;
    border: none;
}

.form-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
    transform: translateY(-2px);
}

.card-header {
    border-bottom: 0;
    border-top-left-radius: 8px !important;
    border-top-right-radius: 8px !important;
    padding: 1rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

.form-control, .form-select {
    transition: all 0.2s ease;
    padding: 0.6rem 1rem;
    border-radius: 6px;
}

.form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.form-control-lg, .form-select-lg {
    height: calc(2.5rem + 2px);
}

.btn {
    border-radius: 6px;
    padding: 0.6rem 1.5rem;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-switch .form-check-input {
    height: 1.5rem;
    width: 3rem !important;
    cursor: pointer;
}
</style>

<?= form_open($action, ['id' => 'form-loai-su-kien', 'class' => 'needs-validation', 'novalidate' => true]) ?>
<div class="row">
    <!-- Thông tin loại sự kiện -->
    <div class="col-12">
        <div class="card mb-4 shadow-sm form-card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">
                    <i class="bx bx-info-circle me-2"></i> Thông tin loại sự kiện
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="ten_loai_su_kien" class="form-label fw-bold">
                            <i class="bx bx-tag text-primary me-1"></i> Tên loại sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ten_loai_su_kien') ? 'is-invalid' : '' ?>" 
                            id="ten_loai_su_kien" name="ten_loai_su_kien" value="<?= $ten_loai_su_kien ?>" 
                            placeholder="Nhập tên loại sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('ten_loai_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ten_loai_su_kien') ?>
                            </div>
                        <?php else: ?>
                            <div class="form-text">Nhập tên dễ hiểu cho loại sự kiện</div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="ma_loai_su_kien" class="form-label fw-bold">
                            <i class="bx bx-code text-primary me-1"></i> Mã loại sự kiện <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-lg <?= isset($validation) && $validation->hasError('ma_loai_su_kien') ? 'is-invalid' : '' ?>" 
                            id="ma_loai_su_kien" name="ma_loai_su_kien" value="<?= $ma_loai_su_kien ?>" 
                            placeholder="Nhập mã cho loại sự kiện" required>
                        <?php if (isset($validation) && $validation->hasError('ma_loai_su_kien')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('ma_loai_su_kien') ?>
                            </div>
                        <?php else: ?>
                            <div class="form-text">Nhập mã duy nhất cho loại sự kiện (không dấu, không khoảng trắng)</div>
                        <?php endif ?>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label fw-bold">
                            <i class="bx bx-toggle-left text-primary me-1"></i> Trạng thái
                        </label>
                        <select class="form-select form-select-lg <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>" 
                            id="status" name="status">
                            <option value="1" <?= $status == 1 ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="0" <?= $status == 0 ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                        <?php if (isset($validation) && $validation->hasError('status')) : ?>
                            <div class="invalid-feedback">
                                <?= $validation->getError('status') ?>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nút submit -->
    <div class="col-12 mb-3">
        <div class="d-flex justify-content-end gap-2">
            <a href="<?= site_url($module_name) ?>" class="btn btn-secondary btn-lg">
                <i class="bx bx-arrow-back me-1"></i> Hủy
            </a>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="bx bx-save me-1"></i> Lưu loại sự kiện
            </button>
        </div>
    </div>
</div>
<?= form_close() ?>

<script>
// Validate form khi submit
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-loai-su-kien');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // Tự động tạo mã loại sự kiện từ tên
    const tenLoaiInput = document.getElementById('ten_loai_su_kien');
    const maLoaiInput = document.getElementById('ma_loai_su_kien');
    
    if (tenLoaiInput && maLoaiInput && maLoaiInput.value === '') {
        tenLoaiInput.addEventListener('blur', function() {
            if (maLoaiInput.value === '') {
                let maLoai = tenLoaiInput.value
                    .toLowerCase()
                    .normalize("NFD").replace(/[\u0300-\u036f]/g, "") // Xóa dấu
                    .replace(/[^a-z0-9]/g, '_') // Thay thế ký tự đặc biệt bằng gạch dưới
                    .replace(/_{2,}/g, '_'); // Xóa nhiều gạch dưới liên tiếp
                
                maLoaiInput.value = maLoai;
            }
        });
    }
});
</script>