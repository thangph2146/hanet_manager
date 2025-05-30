<?php
/**
 * Component hiển thị chi tiết loại sự kiện
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu loại sự kiện cần hiển thị
 * @var string $module_name Tên module
 */

// Khởi tạo các biến từ dữ liệu đầu vào
if (is_object($data)) {
    $loai_su_kien_id = $data->getId() ?? '';
    $ten_loai_su_kien = $data->getTenLoaiSuKien() ?? '';
    $ma_loai_su_kien = $data->getMaLoaiSuKien() ?? '';
    $mo_ta = $data->getMoTa() ?? '';
    $thu_tu = $data->getThuTu() ?? 0;
    $status = $data->getStatus() ?? 1;
    $created_at = $data->getCreatedAtFormatted() ?? '';
    $updated_at = $data->getUpdatedAtFormatted() ?? '';
} else {
    $loai_su_kien_id = '';
    $ten_loai_su_kien = '';
    $ma_loai_su_kien = '';
    $mo_ta = '';
    $thu_tu = 0;
    $status = 1;
    $created_at = '';
    $updated_at = '';
}
?>

<style>
.detail-card {
    transition: all 0.3s ease;
    border-radius: 8px;
    overflow: hidden;
    border: none;
}

.detail-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    transform: translateY(-3px);
}

.card-header {
    border-bottom: 0;
    padding: 1rem 1.5rem;
}

.card-body {
    padding: 1.5rem;
}

.badge {
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

.btn {
    border-radius: 6px;
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.dropdown-item {
    transition: all 0.2s ease;
    border-radius: 4px;
    margin: 2px 5px;
}

.copy-btn {
    transition: all 0.2s ease;
}

.copy-btn:hover {
    transform: scale(1.1);
}

.progress {
    overflow: hidden;
    border-radius: 10px;
}

.progress-bar {
    transition: width 1s ease;
}

.profile-img {
    transition: all 0.3s ease;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.profile-img:hover {
    transform: scale(1.05);
}

.list-group-item {
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: rgba(0,0,0,0.02);
}

.modal-content {
    border-radius: 10px;
    overflow: hidden;
}

.info-section {
    opacity: 0;
    animation: fadeIn 0.5s ease forwards;
    animation-delay: calc(var(--animation-order) * 0.1s);
}

.text-icon {
    transition: all 0.3s ease;
}

.text-icon:hover .bx {
    transform: scale(1.2);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.detail-item {
    margin-bottom: 1rem;
}

.detail-item label {
    display: block;
    margin-bottom: 0.5rem;
}

.detail-item span {
    display: block;
    padding: 0.5rem;
    background-color: #f8f9fa;
    border-radius: 0.25rem;
}
</style>

<div class="container-fluid px-0">
    <!-- Card header -->
    <div class="card shadow-sm mb-4 detail-card" style="--animation-order: 1">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="card-title mb-0 text-white">
                <i class="fas fa-info-circle me-2"></i> Chi tiết loại sự kiện
            </h4>
            <div class="d-flex gap-2">
                <a href="<?= base_url($module_name) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
                <a href="<?= base_url($module_name . '/edit/' . $loai_su_kien_id) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card info-section" style="--animation-order: 2">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-info-circle me-2"></i> Thông tin cơ bản
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">ID loại sự kiện</label>
                            <div class="h5">
                                <span class="badge bg-primary px-3 py-2">#<?= $loai_su_kien_id ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Tên loại sự kiện</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-tag me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($ten_loai_su_kien) ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Mã loại sự kiện</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-code me-2 text-primary"></i>
                                <span class="fw-bold"><?= esc($ma_loai_su_kien) ?></span>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Thứ tự</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-sort-numeric-down me-2 text-primary"></i>
                                <span class="fw-bold"><?= $thu_tu ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thông tin bổ sung -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card info-section" style="--animation-order: 3">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-clipboard-list me-2"></i> Thông tin bổ sung
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Trạng thái</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-toggle-on me-2 text-primary"></i>
                                <?php if ($status == 1): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Vô hiệu</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Ngày tạo</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-calendar-plus me-2 text-primary"></i>
                                <span class="fw-bold"><?= $created_at ?></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted mb-1 fw-medium">Ngày cập nhật</label>
                            <div class="h5 text-icon">
                                <i class="fas fa-calendar-check me-2 text-primary"></i>
                                <span class="fw-bold"><?= $updated_at ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mô tả -->
        <div class="col-12">
            <div class="card shadow-sm detail-card info-section" style="--animation-order: 4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 text-white">
                        <i class="fas fa-align-left me-2"></i> Mô tả
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="h5">
                        <?= empty($mo_ta) ? '<em class="text-muted">Không có mô tả</em>' : nl2br(esc($mo_ta)) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo Animation cho các phần tử
    const infoSections = document.querySelectorAll('.info-section');
    infoSections.forEach(section => {
        setTimeout(() => {
            section.style.opacity = '1';
        }, 100);
    });
});
</script> 