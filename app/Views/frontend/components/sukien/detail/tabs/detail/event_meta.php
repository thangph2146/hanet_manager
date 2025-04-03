<div class="event-meta mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="meta-item d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="lni lni-timer text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold">Ngày tổ chức</h6>
                        <span><?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?></span>
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
                        <span><?= date('H:i', strtotime($event['gio_bat_dau'])) ?> - <?= date('H:i', strtotime($event['gio_ket_thuc'])) ?></span>
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
                        <span><?= $event['so_luong_tham_gia'] ?> người</span>
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
                        <span><?= isset($event['hinh_thuc']) ? $event['hinh_thuc'] : 'Offline' ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Hiển thị thông tin liên kết nếu là sự kiện online -->
            <?php if (isset($event['hinh_thuc']) && strtolower($event['hinh_thuc']) == 'online' && !empty($event['link_online'])): ?>
            <div class="col-md-6 mb-3">
                <div class="meta-item d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="lni lni-link text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold">Liên kết tham gia</h6>
                        <a href="<?= $event['link_online'] ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                            <i class="lni lni-video"></i> Tham gia online
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>