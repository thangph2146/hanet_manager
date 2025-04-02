<?php
/**
 * Component hiển thị chi tiết check-out sự kiện
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu check-out cần hiển thị
 * @var string $module_name Tên module
 */
?>
<div class="card mb-4 border-0 shadow-sm">
    <!-- Header với breadcrumb -->
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <h4 class="card-title mb-0 mt-2"><?= $title ?? 'Chi tiết check-out sự kiện' ?></h4>
        </div>
        <div class="d-flex">
            <a href="<?= base_url($module_name) ?>" class="btn btn-outline-secondary me-2">
                <i class="bx bx-arrow-back"></i> Quay lại
            </a>
            <a href="<?= base_url($module_name . '/edit/' . $data->checkout_sukien_id) ?>" class="btn btn-primary">
                <i class="bx bx-edit"></i> Chỉnh sửa
            </a>
        </div>
    </div>
    
    <div class="card-body">
        <div class="row">
            <!-- Cột thông tin cơ bản -->
            <div class="col-lg-6">
                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-info-circle me-2"></i>Thông tin cơ bản</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">ID</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= $data->checkout_sukien_id ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Sự kiện</label>
                            <div class="col-sm-8">
                                <?php
                                $suKien = $data->getSuKien();
                                $tenSuKien = $suKien ? esc($suKien->ten_su_kien) : esc($data->ten_su_kien ?? '(Không xác định)');
                                ?>
                                <p class="form-control-plaintext"><?= $tenSuKien ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Họ tên</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= esc($data->ho_ten) ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Email</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= esc($data->email) ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Trạng thái</label>
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
                                    <span class="badge <?= $statusClass ?>"><?= $data->getStatusText() ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Thông tin check-out -->
                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-exit me-2"></i>Thông tin check-out</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Thời gian check-out</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">
                                    <i class="bx bx-time me-1 text-primary"></i>
                                    <?= $data->getThoiGianCheckOutFormatted() ?>
                                </p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Loại check-out</label>
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
                                    <span class="badge <?= $checkoutClass ?>">
                                        <i class="bx <?= $checkoutIcon ?> me-1"></i>
                                        <?= $data->getCheckoutTypeText() ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Hình thức tham gia</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">
                                    <span class="badge <?= $data->hinh_thuc_tham_gia == 'offline' ? 'bg-purple' : 'bg-teal' ?>">
                                        <i class="bx <?= $data->hinh_thuc_tham_gia == 'offline' ? 'bx-building' : 'bx-globe' ?> me-1"></i>
                                        <?= $data->getHinhThucThamGiaText() ?>
                                    </span>
                                </p>
                            </div>
                        </div>
                        <?php if (!empty($data->ma_xac_nhan)): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Mã xác nhận</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><code><?= esc($data->ma_xac_nhan) ?></code></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($data->attendance_duration_minutes): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Thời gian tham dự</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">
                                    <i class="bx bx-time-five me-1 text-success"></i>
                                    <?= $data->getAttendanceDurationFormatted() ?>
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
                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-face me-2"></i>Nhận diện khuôn mặt</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Xác minh khuôn mặt</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">
                                    <?php if ($data->isFaceVerified()): ?>
                                    <span class="badge bg-success"><i class="bx bx-check me-1"></i>Đã xác minh</span>
                                    <?php else: ?>
                                    <span class="badge bg-danger"><i class="bx bx-x me-1"></i>Chưa xác minh</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <?php if ($data->face_match_score !== null): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Điểm số khớp KM</label>
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
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar <?= $scoreClass ?>" role="progressbar" 
                                             style="width: <?= $scorePercent ?>%;" 
                                             aria-valuenow="<?= $scorePercent ?>" aria-valuemin="0" aria-valuemax="100">
                                            <?= number_format($scorePercent, 2) ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if ($data->face_image_path): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Ảnh khuôn mặt</label>
                            <div class="col-sm-8">
                                <div class="form-control-plaintext">
                                    <a href="<?= base_url($data->face_image_path) ?>" class="spotlight" data-gallery="face">
                                        <img src="<?= base_url($data->face_image_path) ?>" alt="Ảnh khuôn mặt" class="img-thumbnail" style="max-width: 150px">
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
                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-star me-2"></i>Đánh giá & Phản hồi</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($data->danh_gia): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Đánh giá</label>
                            <div class="col-sm-8">
                                <div class="form-control-plaintext">
                                    <div class="text-warning">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="bx <?= $i <= $data->danh_gia ? 'bxs-star' : 'bx-star' ?> fs-4"></i>
                                        <?php endfor; ?>
                                        <span class="ms-2 text-muted"><?= $data->danh_gia ?>/5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($data->noi_dung_danh_gia): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Nội dung đánh giá</label>
                            <div class="col-sm-8">
                                <div class="form-control-plaintext">
                                    <div class="p-3 bg-light rounded border">
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
                            <label class="col-sm-4 col-form-label fw-bold">Phản hồi</label>
                            <div class="col-sm-8">
                                <div class="form-control-plaintext">
                                    <div class="p-3 bg-light rounded border">
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
                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bx bx-devices me-2"></i>Thông tin thiết bị & Vị trí
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($data->device_info)): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Thiết bị</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">
                                    <i class="bx bx-mobile me-1 text-primary"></i>
                                    <?= esc($data->device_info) ?>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($data->ip_address)): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Địa chỉ IP</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">
                                    <i class="bx bx-globe me-1 text-primary"></i>
                                    <?= esc($data->ip_address) ?>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($data->location_data)): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Dữ liệu vị trí</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext">
                                    <i class="bx bx-map me-1 text-primary"></i>
                                    <?= esc($data->location_data) ?>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php 
                        $formattedInfo = $data->getThongTinBoSung();
                        if (!empty($formattedInfo)): 
                        ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Thông tin bổ sung</label>
                            <div class="col-sm-8">
                                <div class="form-control-plaintext">
                                    <ul class="list-group">
                                        <?php foreach ($formattedInfo as $label => $value): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="fw-bold"><?= esc($label) ?></span>
                                            <span><?= esc($value) ?></span>
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
                <div class="card border shadow-none mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="bx bx-time-five me-2"></i>Thông tin thời gian</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Ngày tạo</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= $data->getCreatedAtFormatted() ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Ngày cập nhật</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= $data->getUpdatedAtFormatted() ?></p>
                            </div>
                        </div>
                        <?php if (!empty($data->ghi_chu)): ?>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label fw-bold">Ghi chú</label>
                            <div class="col-sm-8">
                                <div class="form-control-plaintext">
                                    <div class="p-3 bg-light rounded border">
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
    <div class="card-footer bg-white d-flex justify-content-between">
        <a href="<?= base_url($module_name) ?>" class="btn btn-outline-secondary">
            <i class="bx bx-arrow-back"></i> Quay lại danh sách
        </a>
        <div>
            <a href="<?= base_url($module_name . '/edit/' . $data->checkout_sukien_id) ?>" class="btn btn-primary me-2">
                <i class="bx bx-edit"></i> Chỉnh sửa
            </a>
            <button type="button" class="btn btn-danger btn-delete" 
                    data-id="<?= $data->checkout_sukien_id ?>" 
                    data-name="ID: <?= esc($data->checkout_sukien_id) ?> - <?= esc($data->ho_ten) ?>">
                <i class="bx bx-trash"></i> Xóa
            </button>
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
});
</script> 