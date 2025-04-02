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

.info-icon {
    transition: all 0.3s ease;
}

label:hover .info-icon {
    transform: scale(1.2);
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
</style>

<div class="container-fluid px-0">
    <!-- Card header -->
    <div class="card shadow-sm mb-4 detail-card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
            <h4 class="card-title mb-0 text-white">
                <i class="fas fa-id-card me-2"></i> <?= $title ?? 'Chi tiết check-in sự kiện' ?>
            </h4>
            <div class="d-flex gap-2">
                <a href="<?= base_url($module_name) ?>" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
                <a href="<?= base_url($module_name . '/edit/' . $data->checkin_sukien_id) ?>" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit me-1"></i> Chỉnh sửa
                </a>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="<?= base_url($module_name . '/printDetail/' . $data->checkin_sukien_id) ?>"><i class="fas fa-print me-2"></i> In chi tiết</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#qrCodeModal"><i class="fas fa-qrcode me-2"></i> Xem mã QR</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#deleteModal"><i class="fas fa-trash-alt me-2"></i> Xóa</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0 text-white"><i class="fas fa-info-circle me-2 info-icon"></i> Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">ID check-in</label>
                            <div class="h5">
                                <span class="badge bg-primary">#<?= $data->checkin_sukien_id ?></span>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">Sự kiện</label>
                            <div class="h5">
                                <?php
                                $suKien = $data->getSuKien();
                                $tenSuKien = $suKien ? esc($suKien->ten_su_kien) : esc($data->ten_su_kien ?? '(Không xác định)');
                                echo '<i class="fas fa-calendar-alt text-primary me-2 info-icon"></i>' . $tenSuKien;
                                ?>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">Thời gian check-in</label>
                            <div class="h5">
                                <i class="fas fa-clock text-success me-2 info-icon"></i>
                                <?= $data->getThoiGianCheckInFormatted() ?>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">Trạng thái</label>
                            <div>
                                <?= $data->getStatusHtml() ?>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">Mã xác nhận</label>
                            <div class="h5">
                                <?php if (!empty($data->ma_xac_nhan)): ?>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-dark me-2"><?= esc($data->ma_xac_nhan) ?></span>
                                    <button class="btn btn-sm btn-outline-primary copy-btn" data-clipboard-text="<?= esc($data->ma_xac_nhan) ?>">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                                <?php else: ?>
                                <span class="text-muted"><i class="fas fa-minus"></i></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="text-muted mb-1">Ngày tạo / cập nhật</label>
                            <div>
                                <small><i class="fas fa-plus-circle text-success me-1 info-icon"></i> <?= $data->getCreatedAtFormatted() ?></small><br>
                                <small><i class="fas fa-edit text-warning me-1 info-icon"></i> <?= $data->getUpdatedAtFormatted() ?></small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Thông tin người tham gia -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm h-100 detail-card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 text-white"><i class="fas fa-user me-2 info-icon"></i> Thông tin người tham gia</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <?php 
                        $avatarUrl = $data->hasFaceImage() ? $data->getFaceImageUrl() : 'https://ui-avatars.com/api/?name=' . urlencode($data->ho_ten) . '&size=128&background=random';
                        ?>
                        <img src="<?= $avatarUrl ?>" alt="<?= esc($data->ho_ten) ?>" class="rounded-circle img-thumbnail profile-img" style="width: 100px; height: 100px">
                    </div>
                    
                    <div class="d-flex flex-column">
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">Họ tên</label>
                            <div class="h5">
                                <i class="fas fa-user-tag text-primary me-2 info-icon"></i>
                                <?= esc($data->ho_ten) ?>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">Email</label>
                            <div class="h5">
                                <i class="fas fa-envelope text-primary me-2 info-icon"></i>
                                <a href="mailto:<?= esc($data->email) ?>" class="text-decoration-none"><?= esc($data->email) ?></a>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">Phương thức check-in</label>
                            <div class="d-flex align-items-center">
                                <?= $data->getCheckinTypeHtml() ?>
                            </div>
                        </div>
                        
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">Hình thức tham gia</label>
                            <div>
                                <?= $data->getHinhThucThamGiaHtml() ?>
                            </div>
                        </div>
                        
                        <?php if ($data->isFaceCheckIn()): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <label class="text-muted mb-1">Xác minh khuôn mặt</label>
                            <div class="d-flex align-items-center">
                                <?= $data->getFaceVerifiedHtml() ?>
                                
                                <?php if ($data->getFaceMatchScore() !== null): ?>
                                <div class="ms-3">
                                    <div class="progress" style="height: 8px; width: 100px;">
                                        <div class="progress-bar bg-<?= $data->getFaceMatchScore() > 0.7 ? 'success' : ($data->getFaceMatchScore() > 0.5 ? 'warning' : 'danger') ?>" 
                                            role="progressbar" 
                                            style="width: <?= $data->getFaceMatchScore() * 100 ?>%" 
                                            aria-valuenow="<?= $data->getFaceMatchScore() * 100 ?>" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                        </div>
                                    </div>
                                    <small class="text-muted"><?= $data->getFaceMatchScorePercent() ?></small>
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
            <div class="card shadow-sm detail-card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0 text-white"><i class="fas fa-list-alt me-2 info-icon"></i> Thông tin bổ sung</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Ghi chú -->
                        <?php if (!empty($data->getGhiChu())): ?>
                        <div class="col-md-6 mb-3">
                            <div class="card border-light h-100 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-dark"><i class="fas fa-sticky-note me-2 info-icon"></i> Ghi chú</h6>
                                </div>
                                <div class="card-body">
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
                            <div class="card border-light h-100 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-dark"><i class="fas fa-tags me-2 info-icon"></i> Thông tin thêm</h6>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($formattedInfo as $label => $value): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <span class="text-muted"><?= esc($label) ?></span>
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
                            <div class="card border-light shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-dark"><i class="fas fa-laptop me-2 info-icon"></i> Thông tin thiết bị & Vị trí</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <?php if (!empty($data->getDeviceInfo())): ?>
                                        <div class="col-md-4 mb-3">
                                            <strong><i class="fas fa-mobile-alt me-2 info-icon"></i> Thiết bị:</strong>
                                            <p class="mt-2 px-3 py-2 bg-light rounded"><?= esc($data->getFormattedDeviceInfo() ?: $data->getDeviceInfo()) ?></p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($data->getIpAddress())): ?>
                                        <div class="col-md-4 mb-3">
                                            <strong><i class="fas fa-network-wired me-2 info-icon"></i> Địa chỉ IP:</strong>
                                            <p class="mt-2 px-3 py-2 bg-light rounded"><?= esc($data->getIpAddress()) ?></p>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($data->getLocationData())): ?>
                                        <div class="col-md-4 mb-3">
                                            <strong><i class="fas fa-map-marker-alt me-2 info-icon"></i> Vị trí:</strong>
                                            <p class="mt-2 px-3 py-2 bg-light rounded"><?= esc($data->getLocationData()) ?></p>
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
                <h5 class="modal-title" id="qrCodeModalLabel"><i class="fas fa-qrcode me-2"></i> Mã QR Check-in</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <div id="qrcode-container" class="p-3 bg-light rounded shadow-sm mb-3">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=CHECKIN:<?= $data->checkin_sukien_id ?>:<?= urlencode($data->ma_xac_nhan) ?>" class="img-fluid" alt="QR Code">
                </div>
                <div class="alert alert-info">
                    <p class="mb-0">Mã xác nhận: <strong><?= esc($data->ma_xac_nhan) ?></strong></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Đóng
                </button>
                <button type="button" class="btn btn-primary" id="download-qr">
                    <i class="fas fa-download me-1"></i> Tải xuống
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
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i> Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="alert alert-warning">
                    <p><i class="fas fa-exclamation-circle me-2"></i> Bạn có chắc chắn muốn xóa bản ghi check-in này không? Hành động này không thể hoàn tác.</p>
                </div>
                <div class="card border-light mt-3">
                    <div class="card-body">
                        <p class="mb-1"><strong><i class="fas fa-id-badge me-2"></i> ID:</strong> #<?= $data->checkin_sukien_id ?></p>
                        <p class="mb-1"><strong><i class="fas fa-user me-2"></i> Người tham gia:</strong> <?= esc($data->ho_ten) ?></p>
                        <p class="mb-0"><strong><i class="fas fa-envelope me-2"></i> Email:</strong> <?= esc($data->email) ?></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Hủy
                </button>
                <a href="<?= base_url($module_name . '/delete/' . $data->checkin_sukien_id) ?>" class="btn btn-danger">
                    <i class="fas fa-trash-alt me-1"></i> Xác nhận xóa
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
            e.trigger.innerHTML = '<i class="fas fa-check"></i>';
            e.trigger.classList.add('btn-success');
            e.trigger.classList.remove('btn-outline-primary');
            
            setTimeout(function() {
                e.trigger.innerHTML = '<i class="fas fa-copy"></i>';
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
            this.innerHTML = '<i class="fas fa-check me-1"></i> Đã tải xuống';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-download me-1"></i> Tải xuống';
            }, 2000);
        }
    });
    
    // Hiệu ứng xuất hiện cho các thẻ
    const cards = document.querySelectorAll('.detail-card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '1';
        }, 100 * (index + 1));
    });
    
    // Hiệu ứng cho progress bar
    const progressBars = document.querySelectorAll('.progress-bar');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
});
</script>

<style>
/* Hiệu ứng xuất hiện dần cho các thẻ */
.detail-card {
    opacity: 0;
    animation: fadeIn 0.5s ease forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Hiệu ứng cho các badge */
.badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Hiệu ứng hover cho các button */
.btn:hover {
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}
</style> 