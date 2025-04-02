<?php
/**
 * Component hiển thị chi tiết check-out sự kiện
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu check-out cần hiển thị
 * @var string $module_name Tên module
 */
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

.progress {
    overflow: hidden;
    border-radius: 10px;
}

.progress-bar {
    transition: width 1s ease;
}

.img-thumbnail {
    transition: all 0.3s ease;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.img-thumbnail:hover {
    transform: scale(1.05);
}

.list-group-item {
    transition: all 0.2s ease;
}

.list-group-item:hover {
    background-color: rgba(0,0,0,0.02);
}

.form-control-plaintext {
    transition: all 0.2s ease;
}

.info-section {
    opacity: 0;
    animation: fadeIn 0.5s ease forwards;
    animation-delay: calc(var(--animation-order) * 0.1s);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.bx {
    transition: all 0.3s ease;
}

.text-icon:hover .bx {
    transform: scale(1.2);
}
</style>

<div class="container-fluid px-0">
    <div class="card mb-4 border-0 shadow-sm detail-card" style="--animation-order: 1">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="card-title mb-0 text-white">
                <i class="bx bx-detail me-2"></i> <?= $title ?? 'Chi tiết check-out sự kiện' ?>
            </h4>
            <div class="d-flex gap-2">
                <a href="<?= base_url($module_name) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                    <i class="bx bx-arrow-back me-1"></i> Quay lại
                </a>
                <a href="<?= base_url($module_name . '/edit/' . $data->checkout_sukien_id) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                    <i class="bx bx-edit me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>
        
        <div class="card-body p-4">
            <div class="row">
                <!-- Cột thông tin cơ bản -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-3 mb-4 info-section" style="--animation-order: 2">
                        <div class="card-header bg-light py-3 border-0">
                            <h5 class="mb-0 fw-bold d-flex align-items-center text-icon">
                                <i class="bx bx-info-circle me-2 text-primary"></i>Thông tin cơ bản
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">ID</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext fw-bold">
                                        <span class="badge bg-primary px-3 py-2">#<?= $data->checkout_sukien_id ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Sự kiện</label>
                                <div class="col-sm-8">
                                    <?php
                                    $suKien = $data->getSuKien();
                                    $tenSuKien = $suKien ? esc($suKien->ten_su_kien) : esc($data->ten_su_kien ?? '(Không xác định)');
                                    ?>
                                    <p class="form-control-plaintext fw-bold text-icon">
                                        <i class="bx bx-calendar-event me-1 text-primary"></i>
                                        <?= $tenSuKien ?>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Họ tên</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext fw-bold text-icon">
                                        <i class="bx bx-user me-1 text-primary"></i>
                                        <?= esc($data->ho_ten) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Email</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext text-icon">
                                        <i class="bx bx-envelope me-1 text-primary"></i>
                                        <a href="mailto:<?= esc($data->email) ?>" class="text-decoration-none"><?= esc($data->email) ?></a>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Trạng thái</label>
                                <div class="col-sm-8">
                                    <?php 
                                    $statusClass = '';
                                    switch($data->status) {
                                        case 1: $statusClass = 'bg-success'; break;
                                        case 0: $statusClass = 'bg-danger'; break;
                                        case 2: $statusClass = 'bg-warning'; break;
                                        default: $statusClass = 'bg-secondary';
                                    }
                                    ?>
                                    <p class="form-control-plaintext">
                                        <span class="badge <?= $statusClass ?> px-3 py-2 fs-6"><?= $data->getStatusText() ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Thông tin check-out -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4 info-section" style="--animation-order: 3">
                        <div class="card-header bg-light py-3 border-0">
                            <h5 class="mb-0 fw-bold d-flex align-items-center text-icon">
                                <i class="bx bx-exit me-2 text-primary"></i>Thông tin check-out
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Thời gian check-out</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext text-icon">
                                        <i class="bx bx-time me-1 text-primary"></i>
                                        <span class="fw-bold"><?= $data->getThoiGianCheckOutFormatted() ?></span>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Loại check-out</label>
                                <div class="col-sm-8">
                                    <?php 
                                    $checkoutIcon = '';
                                    $checkoutClass = '';
                                    switch($data->checkout_type) {
                                        case 'face_id': 
                                            $checkoutIcon = 'bx-face'; 
                                            $checkoutClass = 'bg-info';
                                            break;
                                        case 'qr_code': 
                                            $checkoutIcon = 'bx-qr-scan'; 
                                            $checkoutClass = 'bg-primary';
                                            break;
                                        case 'manual': 
                                            $checkoutIcon = 'bx-edit'; 
                                            $checkoutClass = 'bg-warning';
                                            break;
                                        case 'auto': 
                                            $checkoutIcon = 'bx-timer'; 
                                            $checkoutClass = 'bg-success';
                                            break;
                                        case 'online': 
                                            $checkoutIcon = 'bx-globe'; 
                                            $checkoutClass = 'bg-secondary';
                                            break;
                                        default: 
                                            $checkoutIcon = 'bx-log-out-circle'; 
                                            $checkoutClass = 'bg-dark';
                                    }
                                    ?>
                                    <p class="form-control-plaintext">
                                        <span class="badge <?= $checkoutClass ?> px-3 py-2 fs-6">
                                            <i class="bx <?= $checkoutIcon ?> me-1"></i>
                                            <?= $data->getCheckoutTypeText() ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Hình thức tham gia</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">
                                        <span class="badge <?= $data->hinh_thuc_tham_gia == 'offline' ? 'bg-purple' : 'bg-teal' ?> px-3 py-2 fs-6">
                                            <i class="bx <?= $data->hinh_thuc_tham_gia == 'offline' ? 'bx-building' : 'bx-globe' ?> me-1"></i>
                                            <?= $data->getHinhThucThamGiaText() ?>
                                        </span>
                                    </p>
                                </div>
                            </div>
                            <?php if (!empty($data->ma_xac_nhan)): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Mã xác nhận</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">
                                        <div class="d-flex align-items-center">
                                            <code class="bg-light px-2 py-1 rounded fs-6 me-2"><?= esc($data->ma_xac_nhan) ?></code>
                                            <button class="btn btn-sm btn-outline-primary copy-btn" data-clipboard-text="<?= esc($data->ma_xac_nhan) ?>">
                                                <i class="bx bx-copy"></i>
                                            </button>
                                        </div>
                                    </p>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($data->attendance_duration_minutes): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Thời gian tham dự</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext text-icon">
                                        <i class="bx bx-time-five me-1 text-success"></i>
                                        <span class="fw-bold"><?= $data->getAttendanceDurationFormatted() ?></span>
                                    </p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Cột thông tin bổ sung -->
                <div class="col-lg-6">
                    <?php if ($data->checkout_type == 'face_id'): ?>
                    <!-- Thông tin nhận diện khuôn mặt -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4 info-section" style="--animation-order: 4">
                        <div class="card-header bg-light py-3 border-0">
                            <h5 class="mb-0 fw-bold d-flex align-items-center text-icon">
                                <i class="bx bx-face me-2 text-primary"></i>Nhận diện khuôn mặt
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Xác minh khuôn mặt</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext">
                                        <?php if ($data->isFaceVerified()): ?>
                                        <span class="badge bg-success px-3 py-2 fs-6"><i class="bx bx-check me-1"></i>Đã xác minh</span>
                                        <?php else: ?>
                                        <span class="badge bg-danger px-3 py-2 fs-6"><i class="bx bx-x me-1"></i>Chưa xác minh</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <?php if ($data->face_match_score !== null): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Điểm số khớp KM</label>
                                <div class="col-sm-8">
                                    <?php 
                                    $scorePercent = $data->face_match_score * 100;
                                    $scoreClass = '';
                                    if ($scorePercent >= 90) $scoreClass = 'bg-success';
                                    elseif ($scorePercent >= 70) $scoreClass = 'bg-info';
                                    elseif ($scorePercent >= 50) $scoreClass = 'bg-warning';
                                    else $scoreClass = 'bg-danger';
                                    ?>
                                    <div class="form-control-plaintext">
                                        <div class="progress rounded-pill" style="height: 25px;">
                                            <div class="progress-bar <?= $scoreClass ?> rounded-pill" role="progressbar" 
                                                 style="width: 0%;" 
                                                 aria-valuenow="<?= $scorePercent ?>" aria-valuemin="0" aria-valuemax="100">
                                                <span class="fw-bold"><?= number_format($scorePercent, 2) ?>%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if ($data->face_image_path): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Ảnh khuôn mặt</label>
                                <div class="col-sm-8">
                                    <div class="form-control-plaintext">
                                        <a href="<?= base_url($data->face_image_path) ?>" class="spotlight" data-gallery="face">
                                            <img src="<?= base_url($data->face_image_path) ?>" alt="Ảnh khuôn mặt" class="img-thumbnail rounded-3 shadow-sm" style="max-width: 150px;">
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($data->danh_gia || $data->noi_dung_danh_gia || $data->feedback): ?>
                    <!-- Thông tin đánh giá và phản hồi -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4 info-section" style="--animation-order: 5">
                        <div class="card-header bg-light py-3 border-0">
                            <h5 class="mb-0 fw-bold d-flex align-items-center text-icon">
                                <i class="bx bx-star me-2 text-warning"></i>Đánh giá & Phản hồi
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if ($data->danh_gia): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Đánh giá</label>
                                <div class="col-sm-8">
                                    <div class="form-control-plaintext">
                                        <div class="d-flex align-items-center">
                                            <div class="text-warning me-3">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="bx <?= $i <= $data->danh_gia ? 'bxs-star' : 'bx-star' ?> fs-4"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <?php
                                            $ratingClass = '';
                                            switch($data->danh_gia) {
                                                case 1: $ratingClass = 'bg-danger'; $ratingText = 'Rất không hài lòng'; break;
                                                case 2: $ratingClass = 'bg-warning text-dark'; $ratingText = 'Không hài lòng'; break;
                                                case 3: $ratingClass = 'bg-secondary'; $ratingText = 'Bình thường'; break;
                                                case 4: $ratingClass = 'bg-info'; $ratingText = 'Hài lòng'; break;
                                                case 5: $ratingClass = 'bg-success'; $ratingText = 'Rất hài lòng'; break;
                                                default: $ratingClass = 'bg-secondary'; $ratingText = 'Không xác định';
                                            }
                                            ?>
                                            <span class="badge <?= $ratingClass ?> px-3 py-2 fs-6"><?= $ratingText ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($data->noi_dung_danh_gia): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Nội dung đánh giá</label>
                                <div class="col-sm-8">
                                    <div class="form-control-plaintext">
                                        <div class="p-3 bg-light rounded-3 border-0 shadow-sm">
                                            <i class="bx bxs-quote-alt-left text-muted me-1"></i>
                                            <?= nl2br(esc($data->noi_dung_danh_gia)) ?>
                                            <i class="bx bxs-quote-alt-right text-muted ms-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($data->feedback): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Phản hồi</label>
                                <div class="col-sm-8">
                                    <div class="form-control-plaintext">
                                        <div class="p-3 bg-light rounded-3 border-0 shadow-sm">
                                            <?= nl2br(esc($data->feedback)) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Thông tin thiết bị và vị trí -->
                    <?php if (!empty($data->location_data) || !empty($data->device_info) || !empty($data->ip_address) || !empty($data->getThongTinBoSung())): ?>
                    <div class="card border-0 shadow-sm rounded-3 mb-4 info-section" style="--animation-order: 6">
                        <div class="card-header bg-light py-3 border-0">
                            <h5 class="mb-0 fw-bold d-flex align-items-center text-icon">
                                <i class="bx bx-devices me-2 text-primary"></i>Thông tin thiết bị & Vị trí
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <?php if (!empty($data->device_info)): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Thiết bị</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext text-icon">
                                        <i class="bx bx-mobile me-1 text-primary"></i>
                                        <span class="fst-italic"><?= esc($data->device_info) ?></span>
                                    </p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($data->ip_address)): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Địa chỉ IP</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext text-icon">
                                        <i class="bx bx-globe me-1 text-primary"></i>
                                        <code class="bg-light px-2 py-1 rounded fs-6"><?= esc($data->ip_address) ?></code>
                                    </p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($data->location_data)): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Dữ liệu vị trí</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext text-icon">
                                        <i class="bx bx-map me-1 text-primary"></i>
                                        <span class="fst-italic"><?= esc($data->location_data) ?></span>
                                    </p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php 
                            $formattedInfo = $data->getThongTinBoSung();
                            if (!empty($formattedInfo)): 
                            ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Thông tin bổ sung</label>
                                <div class="col-sm-8">
                                    <div class="form-control-plaintext">
                                        <ul class="list-group shadow-sm rounded-3">
                                            <?php foreach ($formattedInfo as $label => $value): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom">
                                                <span class="fw-medium text-muted"><?= esc($label) ?></span>
                                                <span class="fw-bold"><?= esc($value) ?></span>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Thông tin thời gian -->
                    <div class="card border-0 shadow-sm rounded-3 mb-4 info-section" style="--animation-order: 7">
                        <div class="card-header bg-light py-3 border-0">
                            <h5 class="mb-0 fw-bold d-flex align-items-center text-icon">
                                <i class="bx bx-time-five me-2 text-primary"></i>Thông tin thời gian
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Ngày tạo</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext text-icon">
                                        <i class="bx bx-calendar-plus me-1 text-primary"></i> 
                                        <?= $data->getCreatedAtFormatted() ?>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Ngày cập nhật</label>
                                <div class="col-sm-8">
                                    <p class="form-control-plaintext text-icon">
                                        <i class="bx bx-calendar-edit me-1 text-primary"></i> 
                                        <?= $data->getUpdatedAtFormatted() ?>
                                    </p>
                                </div>
                            </div>
                            <?php if (!empty($data->ghi_chu)): ?>
                            <div class="mb-3 row">
                                <label class="col-sm-4 col-form-label fw-medium text-muted">Ghi chú</label>
                                <div class="col-sm-8">
                                    <div class="form-control-plaintext">
                                        <div class="p-3 bg-light rounded-3 border-0 shadow-sm">
                                            <?= nl2br(esc($data->ghi_chu)) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer card với các nút hành động -->
        <div class="card-footer bg-white d-flex justify-content-between py-3 border-top-0">
            <a href="<?= base_url($module_name) ?>" class="btn btn-outline-secondary px-3 py-2 rounded-3 d-flex align-items-center">
                <i class="bx bx-arrow-back me-1"></i> Quay lại danh sách
            </a>
            <div>
                <a href="<?= base_url($module_name . '/edit/' . $data->checkout_sukien_id) ?>" class="btn btn-primary me-2 px-3 py-2 rounded-3 d-flex align-items-center d-inline-flex">
                    <i class="bx bx-edit me-1"></i> Chỉnh sửa
                </a>
                <button type="button" class="btn btn-danger px-3 py-2 rounded-3 d-flex align-items-center d-inline-flex btn-delete" 
                        data-id="<?= $data->checkout_sukien_id ?>" 
                        data-name="ID: <?= esc($data->checkout_sukien_id) ?> - <?= esc($data->ho_ten) ?>">
                    <i class="bx bx-trash me-1"></i> Xóa
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý sự kiện nút xóa
    document.querySelector('.btn-delete')?.addEventListener('click', function() {
        const id = this.dataset.id;
        const name = this.dataset.name;
        
        if (confirm(`Bạn có chắc chắn muốn xóa check-out có thông tin "${name}"?`)) {
            window.location.href = `<?= base_url($module_name) ?>/delete/${id}`;
        }
    });
    
    // Thêm hiệu ứng hover cho ảnh
    const images = document.querySelectorAll('.img-thumbnail');
    images.forEach(img => {
        img.addEventListener('mouseenter', function() {
            this.style.transform = 'scale(1.05)';
        });
        img.addEventListener('mouseleave', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Khởi tạo Animation cho các phần tử
    const infoSections = document.querySelectorAll('.info-section');
    infoSections.forEach(section => {
        setTimeout(() => {
            section.style.opacity = '1';
        }, 100);
    });
    
    // Animation cho progress bar
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        setTimeout(() => {
            const width = bar.getAttribute('aria-valuenow') + '%';
            bar.style.width = width;
        }, 500);
    });
    
    // Khởi tạo Clipboard.js nếu có
    if (typeof ClipboardJS !== 'undefined') {
        var clipboard = new ClipboardJS('.copy-btn');
        clipboard.on('success', function(e) {
            e.trigger.innerHTML = '<i class="bx bx-check"></i>';
            e.trigger.classList.add('btn-success');
            e.trigger.classList.remove('btn-outline-primary');
            
            setTimeout(function() {
                e.trigger.innerHTML = '<i class="bx bx-copy"></i>';
                e.trigger.classList.remove('btn-success');
                e.trigger.classList.add('btn-outline-primary');
            }, 2000);
            e.clearSelection();
        });
    }
});
</script> 