<div class="event-meta mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
    <div class="border-bottom mb-4">
        <h3 class="section-title text-primary fw-bold">
            <i class="lni lni-information-circle me-2"></i>
            Thông tin chi tiết
        </h3>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="meta-item d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="lni lni-timer text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Ngày tổ chức</h6>
                    <span><?= date('d/m/Y', strtotime($event['thoi_gian_bat_dau_su_kien'])) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="meta-item d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="lni lni-timer text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Thời gian</h6>
                    <span><?= date('H:i', strtotime($event['thoi_gian_bat_dau_su_kien'])) ?> - <?= date('H:i', strtotime($event['thoi_gian_ket_thuc_su_kien'])) ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="meta-item d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="lni lni-map text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Địa điểm</h6>
                    <span><?= $event['dia_diem'] ?></span>
                    <?php if (!empty($event['dia_chi_cu_the'])): ?>
                    <div class="small text-muted mt-1"><?= $event['dia_chi_cu_the'] ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-3">
            <div class="meta-item d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="lni lni-users text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Số lượng</h6>
                    <div class="d-flex align-items-center">
                        <span class="me-2"><?= $event['so_luong_tham_gia'] > 0 ? $event['so_luong_tham_gia'] : 'Không giới hạn' ?><?= $event['so_luong_tham_gia'] > 0 ? ' người' : '' ?></span>
                        <?php 
                        // Tính tỷ lệ đăng ký
                        $registrationPercent = 0;
                        if (!empty($event['tong_dang_ky']) && $event['so_luong_tham_gia'] > 0) {
                            $registrationPercent = min(100, round(($event['tong_dang_ky'] / $event['so_luong_tham_gia']) * 100));
                        ?>
                        <div class="progress flex-grow-1" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: <?= $registrationPercent ?>%;" 
                                aria-valuenow="<?= $event['tong_dang_ky'] ?>" 
                                aria-valuemin="0" 
                                aria-valuemax="<?= $event['so_luong_tham_gia'] ?>">
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <?php if (!empty($event['tong_dang_ky'])): ?>
                    <div class="small text-muted mt-1">
                        Đã đăng ký: <span class="text-success fw-bold"><?= $event['tong_dang_ky'] ?></span> người 
                        <?php if ($event['so_luong_tham_gia'] > 0): ?>
                        (<?= $registrationPercent ?>%)
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Thống kê tham gia -->
        <?php if (!empty($event['tong_check_in']) || !empty($event['tong_check_out'])): ?>
        <div class="col-md-6 mb-3">
            <div class="meta-item d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="lni lni-checkmark-circle text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Thống kê tham gia</h6>
                    <?php 
                    // Tính % check-in so với tổng đăng ký
                    $checkInPercent = 0;
                    if (!empty($event['tong_check_in']) && !empty($event['tong_dang_ky'])) {
                        $checkInPercent = min(100, round(($event['tong_check_in'] / $event['tong_dang_ky']) * 100));
                    }
                    
                    // Tính % check-out so với đã check-in
                    $checkOutPercent = 0;
                    $checkOutBaseNumber = !empty($event['tong_check_in']) ? $event['tong_check_in'] : $event['tong_dang_ky'];
                    if (!empty($event['tong_check_out']) && $checkOutBaseNumber > 0) {
                        $checkOutPercent = min(100, round(($event['tong_check_out'] / $checkOutBaseNumber) * 100));
                    }
                    ?>
                    
                    <?php if (!empty($event['tong_check_in'])): ?>
                    <div class="d-flex align-items-center">
                        <div class="small me-2">Check-in:</div>
                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: <?= $checkInPercent ?>%;" 
                                aria-valuenow="<?= $event['tong_check_in'] ?>" 
                                aria-valuemin="0" 
                                aria-valuemax="<?= $event['tong_dang_ky'] ?>">
                            </div>
                        </div>
                        <span class="small text-success fw-bold"><?= $event['tong_check_in'] ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($event['tong_check_out'])): ?>
                    <div class="d-flex align-items-center mt-1">
                        <div class="small me-2">Check-out:</div>
                        <div class="progress flex-grow-1 me-2" style="height: 6px;">
                            <div class="progress-bar bg-info" role="progressbar" 
                                style="width: <?= $checkOutPercent ?>%;" 
                                aria-valuenow="<?= $event['tong_check_out'] ?>" 
                                aria-valuemin="0" 
                                aria-valuemax="<?= $checkOutBaseNumber ?>">
                            </div>
                        </div>
                        <span class="small text-info fw-bold"><?= $event['tong_check_out'] ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Thời gian đăng ký -->
        <?php if (!empty($event['thoi_gian_bat_dau_dang_ky']) || !empty($event['thoi_gian_ket_thuc_dang_ky'])): ?>
        <div class="col-md-6 mb-3">
            <div class="meta-item d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="lni lni-calendar text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Thời gian đăng ký</h6>
                    <?php if (!empty($event['thoi_gian_bat_dau_dang_ky'])): ?>
                    <div class="small">Từ: <span class="fw-bold"><?= date('d/m/Y', strtotime($event['thoi_gian_bat_dau_dang_ky'])) ?></span></div>
                    <?php endif; ?>
                    <?php if (!empty($event['thoi_gian_ket_thuc_dang_ky'])): ?>
                    <div class="small">Đến: <span class="fw-bold"><?= date('d/m/Y', strtotime($event['thoi_gian_ket_thuc_dang_ky'])) ?></span></div>
                    <?php endif; ?>
                    <?php if (!empty($event['han_huy_dang_ky'])): ?>
                    <div class="small">Hạn hủy: <span class="text-danger fw-bold"><?= date('d/m/Y', strtotime($event['han_huy_dang_ky'])) ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Thêm thông tin về loại sự kiện -->
        <div class="col-md-6 mb-3">
            <div class="meta-item d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="lni lni-tag text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Loại sự kiện</h6>
                    <span class="badge bg-primary"><?= $event['loai_su_kien'] ?? ($event['ten_loai_su_kien'] ?? 'Chưa phân loại') ?></span>
                </div>
            </div>
        </div>
        
        <!-- Thêm thông tin về hình thức tổ chức (online/offline) -->
        <div class="col-md-6 mb-3">
            <div class="meta-item d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="lni lni-laptop-phone text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Hình thức</h6>
                    <span class="badge bg-<?= strtolower($event['hinh_thuc'] ?? 'offline') == 'online' ? 'success' : 'secondary' ?>">
                        <?= ucfirst(isset($event['hinh_thuc']) ? $event['hinh_thuc'] : 'Offline') ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Hiển thị thông tin liên kết nếu là sự kiện online -->
        <?php if (isset($event['hinh_thuc']) && strtolower($event['hinh_thuc']) == 'online' && !empty($event['link_online'])): ?>
        <div class="col-md-12 mb-3">
            <div class="meta-item d-flex align-items-center">
                <div class="icon-container me-3">
                    <i class="lni lni-link text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Liên kết tham gia</h6>
                    <a href="<?= $event['link_online'] ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                        </i> Tham gia online
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Từ khóa sự kiện -->
        <?php if (!empty($event['tu_khoa_su_kien'])): ?>
        <div class="col-md-12 mb-3">
            <div class="meta-item d-flex align-items-start">
                <div class="icon-container me-3 mt-1">
                    <i class="lni lni-tag-alt text-primary"></i>
                </div>
                <div>
                    <h6 class="mb-1 fw-bold">Từ khóa</h6>
                    <div class="tag-list">
                        <?php 
                        $keywords = explode(',', $event['tu_khoa_su_kien']);
                        foreach ($keywords as $keyword): 
                            $keyword = trim($keyword);
                            if (!empty($keyword)):
                        ?>
                        <span class="badge bg-light text-dark me-1 mb-1"><?= $keyword ?></span>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>