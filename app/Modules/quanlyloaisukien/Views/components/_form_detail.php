<?php
/**
 * Component hiển thị chi tiết check-in sự kiện
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu check-in cần hiển thị
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
</style>

<div class="container-fluid px-0">
    <!-- Card header -->
    <div class="card shadow-sm mb-4 detail-card" style="--animation-order: 1">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="card-title mb-0 text-white">
                <i class="bx bx-id-card me-2"></i> <?= $title ?? 'Chi tiết check-in sự kiện' ?>
            </h4>
            <div class="d-flex gap-2">
                <a href="<?= base_url($module_name) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                    <i class="bx bx-arrow-back me-1"></i> Quay lại danh sách
                </a>
                <a href="<?= base_url($module_name . '/edit/' . $data->checkin_sukien_id) ?>" class="btn btn-light btn-sm px-3 py-2 rounded-3 d-flex align-items-center">
                    <i class="bx bx-edit me-1"></i> Chỉnh sửa
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card info-section" style="--animation-order: 2">
                <div class="card-header bg-info text-white py-3">
                    <h5 class="mb-0 text-white d-flex align-items-center text-icon">
                        <i class="bx bx-info-circle me-2"></i> Thông tin cơ bản
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">ID check-in</label>
                            <div class="h5">
                                <span class="badge bg-primary px-3 py-2">#<?= $data->checkin_sukien_id ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Sự kiện</label>
                            <div class="h5 text-icon">
                                <?php
                                $suKien = $data->getSuKien();
                                $tenSuKien = $suKien ? esc($suKien->ten_su_kien) : esc($data->ten_su_kien ?? '(Không xác định)');
                                ?>
                                <i class="bx bx-calendar me-2 text-primary"></i>
                                <span class="fw-bold"><?= $tenSuKien ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Thời gian check-in</label>
                            <div class="h5 text-icon">
                                <i class="bx bx-time me-2 text-success"></i>
                                <span class="fw-bold"><?= $data->getThoiGianCheckInFormatted() ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Trạng thái</label>
                            <div>
                                <?= $data->getStatusHtml() ?>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Mã xác nhận</label>
                            <div class="h5">
                                <?php if (!empty($data->ma_xac_nhan)): ?>
                                <div class="d-flex align-items-center">
                                    <code class="bg-light px-2 py-1 rounded fs-6 me-2"><?= esc($data->ma_xac_nhan) ?></code>
                                    <button class="btn btn-sm btn-outline-primary copy-btn" data-clipboard-text="<?= esc($data->ma_xac_nhan) ?>">
                                        <i class="bx bx-copy"></i>
                                    </button>
                                </div>
                                <?php else: ?>
                                <span class="text-muted"><i class="bx bx-minus"></i></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1 fw-medium">Ngày tạo / cập nhật</label>
                            <div class="d-flex flex-column">
                                <div class="text-icon mb-1">
                                    <i class="bx bx-plus-circle text-success me-1"></i> 
                                    <small><?= $data->getCreatedAtFormatted() ?></small>
                                </div>
                                <div class="text-icon">
                                    <i class="bx bx-edit text-warning me-1"></i> 
                                    <small><?= $data->getUpdatedAtFormatted() ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thông tin người tham gia -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card info-section" style="--animation-order: 3">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0 text-white d-flex align-items-center text-icon">
                        <i class="bx bx-user me-2"></i> Thông tin người tham gia
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <?php 
                        $avatarUrl = $data->hasFaceImage() ? $data->getFaceImageUrl() : 'https://ui-avatars.com/api/?name=' . urlencode($data->ho_ten) . '&size=128&background=random';
                        ?>
                        <img src="<?= $avatarUrl ?>" alt="<?= esc($data->ho_ten) ?>" class="rounded-circle img-thumbnail profile-img" style="width: 100px; height: 100px">
                    </div>
                    
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Họ tên</label>
                            <div class="h5 text-icon">
                                <i class="bx bx-user-pin text-primary me-2"></i>
                                <span class="fw-bold"><?= esc($data->ho_ten) ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Email</label>
                            <div class="h5 text-icon">
                                <i class="bx bx-envelope text-primary me-2"></i>
                                <a href="mailto:<?= esc($data->email) ?>" class="text-decoration-none"><?= esc($data->email) ?></a>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Phương thức check-in</label>
                            <div class="d-flex align-items-center">
                                <?= $data->getCheckinTypeHtml() ?>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Hình thức tham gia</label>
                            <div>
                                <?= $data->getHinhThucThamGiaHtml() ?>
                            </div>
                        </div>
                        
                        <?php if ($data->isFaceCheckIn()): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1 fw-medium">Xác minh khuôn mặt</label>
                            <div class="d-flex align-items-center">
                                <?= $data->getFaceVerifiedHtml() ?>
                                
                                <?php if ($data->getFaceMatchScore() !== null): ?>
                                <div class="ms-3 w-100">
                                    <div class="progress rounded-pill" style="height: 12px;">
                                        <div class="progress-bar bg-<?= $data->getFaceMatchScore() > 0.7 ? 'success' : ($data->getFaceMatchScore() > 0.5 ? 'warning' : 'danger') ?> rounded-pill" 
                                            role="progressbar" 
                                            style="width: 0%" 
                                            aria-valuenow="<?= $data->getFaceMatchScore() * 100 ?>" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted mt-1 d-block"><?= $data->getFaceMatchScorePercent() ?></small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thông tin bổ sung, ghi chú, nội dung khác -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm detail-card info-section" style="--animation-order: 4">
                <div class="card-header bg-secondary text-white py-3">
                    <h5 class="mb-0 text-white d-flex align-items-center text-icon">
                        <i class="bx bx-list-ul me-2"></i> Thông tin bổ sung
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Ghi chú -->
                        <?php if (!empty($data->getGhiChu())): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-header bg-light py-3 border-0">
                                    <h6 class="mb-0 fw-bold d-flex align-items-center text-icon">
                                        <i class="bx bx-note me-2 text-primary"></i> Ghi chú
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <p class="card-text"><?= nl2br(esc($data->getGhiChu())) ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Thông tin bổ sung dạng JSON -->
                        <?php 
                        $formattedInfo = $data->getFormattedThongTinBoSung();
                        if (!empty($formattedInfo)): 
                        ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-0 shadow-sm rounded-3 h-100">
                                <div class="card-header bg-light py-3 border-0">
                                    <h6 class="mb-0 fw-bold d-flex align-items-center text-icon">
                                        <i class="bx bx-tag-alt me-2 text-primary"></i> Thông tin thêm
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <ul class="list-group list-group-flush shadow-sm rounded-3">
                                        <?php foreach ($formattedInfo as $label => $value): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 border-bottom px-3 py-2">
                                            <span class="fw-medium text-muted"><?= esc($label) ?></span>
                                            <span class="fw-bold"><?= esc($value) ?></span>
                                        </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Thông tin thiết bị -->
                        <?php if (!empty($data->getDeviceInfo()) || !empty($data->getIpAddress()) || !empty($data->getLocationData())): ?>
                        <div class="col-md-12 mb-3">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-header bg-light py-3 border-0">
                                    <h6 class="mb-0 fw-bold d-flex align-items-center text-icon">
                                        <i class="bx bx-devices me-2 text-primary"></i> Thông tin thiết bị & Vị trí
                                    </h6>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row">
                                        <?php if (!empty($data->getDeviceInfo())): ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-center text-icon mb-2">
                                                <i class="bx bx-mobile-alt me-2 text-primary"></i>
                                                <strong>Thiết bị</strong>
                                            </div>
                                            <p class="mt-2 px-3 py-2 bg-light rounded-3 shadow-sm"><?= esc($data->getFormattedDeviceInfo() ?: $data->getDeviceInfo()) ?></p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($data->getIpAddress())): ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-center text-icon mb-2">
                                                <i class="bx bx-network-chart me-2 text-primary"></i>
                                                <strong>Địa chỉ IP</strong>
                                            </div>
                                            <p class="mt-2 px-3 py-2 bg-light rounded-3 shadow-sm">
                                                <code class="fs-6"><?= esc($data->getIpAddress()) ?></code>
                                            </p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($data->getLocationData())): ?>
                                        <div class="col-md-4 mb-3">
                                            <div class="d-flex align-items-center text-icon mb-2">
                                                <i class="bx bx-map-pin me-2 text-primary"></i>
                                                <strong>Vị trí</strong>
                                            </div>
                                            <p class="mt-2 px-3 py-2 bg-light rounded-3 shadow-sm"><?= esc($data->getLocationData()) ?></p>
                                        </div>
                                        <?php endif; ?>
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
</div>

<!-- Modal Xem QR Code -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="qrCodeModalLabel"><i class="bx bx-qr me-2"></i> Mã QR Check-in</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="qrcode-container" class="p-3 bg-light rounded-3 shadow-sm mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=CHECKIN:<?= $data->checkin_sukien_id ?>:<?= urlencode($data->ma_xac_nhan) ?>" class="img-fluid" alt="QR Code">
                </div>
                <div class="alert alert-info rounded-3">
                    <p class="mb-0">Mã xác nhận: <strong><?= esc($data->ma_xac_nhan) ?></strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-3 py-2 rounded-3" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Đóng
                </button>
                <button type="button" class="btn btn-primary px-3 py-2 rounded-3" id="download-qr">
                    <i class="bx bx-download me-1"></i> Tải xuống
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="bx bx-error-circle me-2"></i> Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning rounded-3">
                    <p class="mb-0"><i class="bx bx-error me-2"></i> Bạn có chắc chắn muốn xóa bản ghi check-in này không? Hành động này không thể hoàn tác.</p>
                </div>
                <div class="card border-0 shadow-sm rounded-3 mt-3">
                    <div class="card-body p-4">
                        <p class="mb-2 d-flex align-items-center">
                            <i class="bx bx-id-card me-2 text-primary"></i>
                            <strong class="me-2">ID:</strong> 
                            <span class="badge bg-primary px-2 py-1">#<?= $data->checkin_sukien_id ?></span>
                        </p>
                        <p class="mb-2 d-flex align-items-center">
                            <i class="bx bx-user me-2 text-primary"></i>
                            <strong class="me-2">Người tham gia:</strong> 
                            <span class="fw-medium"><?= esc($data->ho_ten) ?></span>
                        </p>
                        <p class="mb-0 d-flex align-items-center">
                            <i class="bx bx-envelope me-2 text-primary"></i>
                            <strong class="me-2">Email:</strong> 
                            <span><?= esc($data->email) ?></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary px-3 py-2 rounded-3" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Hủy
                </button>
                <a href="<?= base_url($module_name . '/delete/' . $data->checkin_sukien_id) ?>" class="btn btn-danger px-3 py-2 rounded-3">
                    <i class="bx bx-trash me-1"></i> Xác nhận xóa
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize clipboard for copy functionality
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
    
    // Download QR code functionality
    document.getElementById('download-qr')?.addEventListener('click', function() {
        const qrImg = document.querySelector('#qrcode-container img');
        if (qrImg) {
            const link = document.createElement('a');
            link.href = qrImg.src;
            link.download = 'qrcode-checkin-<?= $data->checkin_sukien_id ?>.png';
            link.click();
            
            // Hiệu ứng khi tải xuống
            this.innerHTML = '<i class="bx bx-check me-1"></i> Đã tải xuống';
            setTimeout(() => {
                this.innerHTML = '<i class="bx bx-download me-1"></i> Tải xuống';
            }, 2000);
        }
    });
    
    // Khởi tạo Animation cho các phần tử
    const infoSections = document.querySelectorAll('.info-section');
    infoSections.forEach(section => {
        setTimeout(() => {
            section.style.opacity = '1';
        }, 100);
    });
    
    // Hiệu ứng cho progress bar
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        setTimeout(() => {
            const width = bar.getAttribute('aria-valuenow') + '%';
            bar.style.width = width;
        }, 500);
    });
});
</script> 