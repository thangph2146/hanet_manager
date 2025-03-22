<?php
/**
 * Form component for creating and updating nam_hoc
 * 
 * @var string $action Form submission URL
 * @var string $method Form method (POST or PUT)
 * @var array $nam_hoc NamHoc entity data for editing (optional)
 */

// Set default values if editing
$ten_nam_hoc = isset($nam_hoc) ? $nam_hoc->getTenNamHoc() : '';
$ngay_bat_dau = isset($nam_hoc) ? $nam_hoc->getNgayBatDauFormatted('Y-m-d') : '';
$ngay_ket_thuc = isset($nam_hoc) ? $nam_hoc->getNgayKetThucFormatted('Y-m-d') : '';
$status = isset($nam_hoc) ? $nam_hoc->isActive() : 1;
$id = isset($nam_hoc) ? $nam_hoc->getId() : '';

// For display in data-display attribute
$ngay_bat_dau_display = isset($nam_hoc) && $nam_hoc->getNgayBatDau() ? $nam_hoc->getNgayBatDauFormatted('d/m/Y') : '';
$ngay_ket_thuc_display = isset($nam_hoc) && $nam_hoc->getNgayKetThuc() ? $nam_hoc->getNgayKetThucFormatted('d/m/Y') : '';

// Set default values for form action and method
$action = isset($action) ? $action : site_url('namhoc/create');
$method = isset($method) ? $method : 'POST';
?>

<?= form_open($action, ['class' => 'row g-3 needs-validation', 'novalidate' => true]) ?>
    <?php if (isset($nam_hoc)): ?>
        <input type="hidden" name="nam_hoc_id" value="<?= $id ?>">
    <?php endif; ?>
    
    <!-- ten_nam_hoc -->
    <div class="col-md-12">
        <label for="ten_nam_hoc" class="form-label">Tên năm học <span class="text-danger">*</span></label>
        <input type="text" class="form-control <?= session('errors.ten_nam_hoc') ? 'is-invalid' : '' ?>" 
                id="ten_nam_hoc" name="ten_nam_hoc" 
                value="<?= old('ten_nam_hoc', $ten_nam_hoc) ?>" 
                required minlength="3" maxlength="50">
        <?php if (session('errors.ten_nam_hoc')): ?>
            <div class="invalid-feedback">
                <?= session('errors.ten_nam_hoc') ?>
            </div>
        <?php else: ?>
            <div class="invalid-feedback">Vui lòng nhập tên năm học (tối thiểu 3 ký tự)</div>
        <?php endif; ?>
    </div>

    <!-- ngay_bat_dau -->
    <div class="col-md-6">
        <label for="ngay_bat_dau_display" class="form-label">Ngày bắt đầu</label>
        <input type="text" class="form-control datepicker <?= session('errors.ngay_bat_dau') ? 'is-invalid' : '' ?>" 
                id="ngay_bat_dau_display" 
                placeholder="DD/MM/YYYY"
                value="<?= old('ngay_bat_dau', $ngay_bat_dau_display) ?>"
                autocomplete="off">
        <input type="hidden" name="ngay_bat_dau" id="ngay_bat_dau" 
               value="<?= old('ngay_bat_dau', $ngay_bat_dau) ?>">
        <?php if (session('errors.ngay_bat_dau')): ?>
            <div class="invalid-feedback">
                <?= session('errors.ngay_bat_dau') ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- ngay_ket_thuc -->
    <div class="col-md-6">
        <label for="ngay_ket_thuc_display" class="form-label">Ngày kết thúc</label>
        <input type="text" class="form-control datepicker <?= session('errors.ngay_ket_thuc') ? 'is-invalid' : '' ?>" 
                id="ngay_ket_thuc_display" 
                placeholder="DD/MM/YYYY"
                value="<?= old('ngay_ket_thuc', $ngay_ket_thuc_display) ?>"
                autocomplete="off">
        <input type="hidden" name="ngay_ket_thuc" id="ngay_ket_thuc" 
               value="<?= old('ngay_ket_thuc', $ngay_ket_thuc) ?>">
        <?php if (session('errors.ngay_ket_thuc')): ?>
            <div class="invalid-feedback">
                <?= session('errors.ngay_ket_thuc') ?>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- status -->
    <div class="col-md-6">
        <label for="status" class="form-label">Trạng thái</label>
        <select class="form-select <?= session('errors.status') ? 'is-invalid' : '' ?>" 
                id="status" name="status">
            <option value="1" <?= old('status', $status) == 1 ? 'selected' : '' ?>>Hoạt động</option>
            <option value="0" <?= old('status', $status) == 0 ? 'selected' : '' ?>>Không hoạt động</option>
        </select>
        <?php if (session('errors.status')): ?>
            <div class="invalid-feedback">
                <?= session('errors.status') ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="col-12 mt-4">
        <button type="submit" class="btn btn-primary">Lưu</button>
        <a href="<?= site_url('namhoc') ?>" class="btn btn-secondary">Hủy</a>
    </div>
<?= form_close() ?>

<!-- Thêm CSS của flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Tùy chỉnh vị trí của calendar */
    .flatpickr-calendar {
        width: 100% !important;
        max-width: 307px;
    }
    
    /* Đảm bảo calendar hiển thị phía dưới input */
    .flatpickr-calendar.open {
        z-index: 1000;
    }
</style>

<!-- Thêm thư viện flatpickr -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Khởi tạo flatpickr cho các trường datepicker
        const configDatePicker = {
            dateFormat: "d/m/Y",
            locale: "vn",
            allowInput: true,
            position: "below", // Hiển thị phía dưới input
            static: true,      // Không bị ẩn khi click bên ngoài
            onChange: function(selectedDates, dateStr, instance) {
                // Khi người dùng chọn ngày, cập nhật trường ẩn với giá trị YYYY-MM-DD
                const hiddenInput = document.getElementById(instance.input.id.replace('_display', ''));
                if (selectedDates.length > 0) {
                    const date = selectedDates[0];
                    const year = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day = String(date.getDate()).padStart(2, '0');
                    hiddenInput.value = `${year}-${month}-${day}`;
                } else {
                    hiddenInput.value = '';
                }
            }
        };
        
        // Áp dụng flatpickr cho trường ngày bắt đầu
        flatpickr("#ngay_bat_dau_display", configDatePicker);
        
        // Áp dụng flatpickr cho trường ngày kết thúc
        flatpickr("#ngay_ket_thuc_display", configDatePicker);
    });
</script> 