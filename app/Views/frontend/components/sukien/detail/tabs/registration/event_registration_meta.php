<div class="event-meta mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="meta-item d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="lni lni-timer text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold">Ngày tổ chức</h6>
                        <span><?= date('d/m/Y', strtotime(isset($event['thoi_gian_bat_dau']) ? $event['thoi_gian_bat_dau'] : $event['ngay_to_chuc'])) ?></span>
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
            
            <!-- Thông tin về hình thức tổ chức (online/offline) -->
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
            
            <!-- Thời gian đăng ký -->
            <?php if (!empty($event['bat_dau_dang_ky']) && !empty($event['ket_thuc_dang_ky'])): ?>
            <div class="col-md-6 mb-3">
                <div class="meta-item d-flex align-items-center">
                    <div class="icon-container me-3">
                        <i class="lni lni-calendar text-primary"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-bold">Thời gian đăng ký</h6>
                        <span>Từ <?= date('d/m/Y', strtotime($event['bat_dau_dang_ky'])) ?></span>
                        <div class="small text-muted mt-1">Đến <?= date('d/m/Y', strtotime($event['ket_thuc_dang_ky'])) ?></div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
            
            <!-- CSS cho Registration Meta -->
            <style>
                .meta-item {
                    background-color: #f8f9fa;
                    padding: 15px;
                    border-radius: 8px;
                    height: 100%;
                    transition: all 0.3s ease;
                }
                .meta-item:hover {
                    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
                    transform: translateY(-3px);
                }
                .icon-container {
                    font-size: 24px;
                    width: 50px;
                    height: 50px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background-color: rgba(var(--bs-primary-rgb), 0.1);
                    border-radius: 50%;
                }
            </style>