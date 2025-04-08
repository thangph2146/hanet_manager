<?= $this->extend('frontend/layouts/nguoidung_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/pages/dashboard.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="welcome-banner">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="welcome-title">Xin chào, <?= $profile->FullName ?>!</h2>
                        <p class="welcome-text">Chào mừng bạn quay trở lại với hệ thống quản lý sự kiện của chúng tôi.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="<?= base_url('su-kien') ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-calendar-plus me-2"></i> Khám phá sự kiện
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="stat-card bg-primary text-white">
                <div class="stat-card-overlay"></div>
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content text-white">
                    <div class="stat-value text-white" data-value="<?= $stats['registered'] ?>"><?= $stats['registered'] ?></div>
                    <div class="stat-label text-white">Sự kiện đã đăng ký</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <div class="stat-card bg-success text-white">
                <div class="stat-card-overlay"></div>
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content text-white">
                    <div class="stat-value text-white" data-value="<?= $stats['attended'] ?>"><?= $stats['attended'] ?></div>
                    <div class="stat-label text-white">Sự kiện đã tham gia</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card bg-info text-white">
                <div class="stat-card-overlay"></div>
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-content text-white">
                    <div class="stat-value text-white" data-value="<?= $stats['completion_rate'] ?>"><?= $stats['completion_rate'] ?>%</div>
                    <div class="stat-label text-white">Tỷ lệ tham gia</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <!-- Sự kiện đã đăng ký gần đây -->
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="dashboard-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="fas fa-clipboard-list me-2"></i>Sự kiện đã đăng ký gần đây
                    </h5>
                    <a href="<?= base_url('nguoi-dung/profile') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="events-grid">
                    <?php if(!empty($registeredEvents)): ?>
                        <?php foreach($registeredEvents as $event): ?>
                            <div class="event-card">
                                <div class="event-image">
                                    <?php 
                                        $eventDate = new DateTime($event->thoi_gian_bat_dau); 
                                        $now = new DateTime();
                                        $isUpcoming = $eventDate > $now;
                                    ?>
                                    <img src="<?= !empty($event->hinh_anh) ? base_url('uploads/events/' . $event->hinh_anh) : base_url('assets/images/events/default.jpg') ?>" alt="<?= $event->ten_su_kien ?>">
                                    
                                    <?php if($isUpcoming): ?>
                                        <?php 
                                            $interval = $now->diff($eventDate);
                                            $remaining = '';
                                            if($interval->days > 0) {
                                                $remaining = $interval->format('%a ngày %h giờ');
                                            } else if($interval->h > 0) {
                                                $remaining = $interval->format('%h giờ %i phút');
                                            } else {
                                                $remaining = $interval->format('%i phút');
                                            }
                                        ?>
                                        <div class="event-countdown">
                                            <i class="far fa-clock"></i> <?= $remaining ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="event-date-badge">
                                        <div class="event-day"><?= $eventDate->format('d') ?></div>
                                        <div class="event-month">Th<?= $eventDate->format('m') ?></div>
                                        <div class="event-year"><?= $eventDate->format('Y') ?></div>
                                    </div>
                                    
                                    <?php 
                                    // Kiểm tra sự tồn tại của thuộc tính trang_thai
                                    $trangThai = isset($event->trang_thai) ? $event->trang_thai : (isset($event->status) ? $event->status : -1);
                                    
                                    if($trangThai == 1): 
                                    ?>
                                        <div class="event-registered-badge">
                                            <i class="fas fa-check-circle"></i> Đã xác nhận
                                        </div>
                                    <?php elseif($trangThai == 0): ?>
                                        <div class="event-registered-badge pending">
                                            <i class="fas fa-clock"></i> Chờ xác nhận
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if(isset($event->da_check_in) && $event->da_check_in == 1): ?>
                                        <div class="event-registered-badge attended">
                                            <i class="fas fa-user-check"></i> Đã tham gia
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="event-content">
                                    <div class="event-meta">
                                        <span class="event-category"><?= $event->phan_loai ?? 'Chưa phân loại' ?></span>
                                        <span class="event-views"><i class="far fa-eye"></i> <?= $event->luot_xem ?? 0 ?></span>
                                    </div>
                                    
                                    <h3 class="event-title"><?= esc($event->ten_su_kien ?? $event->ten_sukien ?? 'Sự kiện không xác định') ?></h3>
                                    
                                    <div class="event-details">
                                        <div class="event-time">
                                            <i class="far fa-clock"></i>
                                            <?= isset($event->gio_bat_dau) ? date('H:i', strtotime($event->gio_bat_dau)) : '--:--' ?> - <?= isset($event->gio_ket_thuc) ? date('H:i', strtotime($event->gio_ket_thuc)) : '--:--' ?>
                                        </div>
                                        
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= esc($event->dia_diem ?? 'Không có địa điểm') ?>
                                        </div>
                                        
                                        <?php if(!empty($event->don_vi_to_chuc)): ?>
                                        <div class="event-organizer">
                                            <i class="fas fa-users"></i>
                                            <?= esc($event->don_vi_to_chuc) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if(!empty($event->mo_ta)): ?>
                                    <div class="event-description">
                                        <?= character_limiter(strip_tags($event->mo_ta), 100) ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="event-actions">
                                        <a href="<?= base_url('su-kien/chi-tiet/'.$event->slug) ?>" class="btn btn-details">
                                            <i class="fas fa-info-circle"></i> Chi tiết
                                        </a>
                                        
                                        <?php if(!empty($event->chung_chi)): ?>
                                        <a href="<?= base_url('nguoi-dung/certificate/download/'.$event->dangky_id) ?>" class="btn btn-certificate">
                                            <i class="fas fa-certificate"></i> Chứng chỉ
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <h5 class="empty-state-title">Bạn chưa đăng ký sự kiện nào</h5>
                            <p class="empty-state-description">Khám phá và đăng ký các sự kiện để bắt đầu</p>
                            <a href="<?= base_url('nguoi-dung/events/list') ?>" class="btn btn-primary" style="display: flex; justify-content: center; align-items: center;">
                                <i class="fas fa-calendar-plus me-1" style="margin-bottom: 0rem;"></i> Khám phá sự kiện
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sự kiện đã tham gia gần đây -->
        <div class="col-md-6">
            <div class="dashboard-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="fas fa-check-circle me-2"></i>Sự kiện đã tham gia
                    </h5>
                    <a href="<?= base_url('nguoi-dung/events-checkin') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="events-grid">
                    <?php if(!empty($attendedEvents)): ?>
                        <?php foreach($attendedEvents as $event): ?>
                            <div class="event-card">
                                <div class="event-image">
                                    <?php 
                                        $eventDate = new DateTime($event->thoi_gian_bat_dau); 
                                    ?>
                                    <img src="<?= !empty($event->hinh_anh) ? base_url('uploads/events/' . $event->hinh_anh) : base_url('assets/images/events/default.jpg') ?>" alt="<?= $event->ten_su_kien ?>">
                                    
                                    <div class="event-date-badge">
                                        <div class="event-day"><?= $eventDate->format('d') ?></div>
                                        <div class="event-month">Th<?= $eventDate->format('m') ?></div>
                                        <div class="event-year"><?= $eventDate->format('Y') ?></div>
                                    </div>
                                    
                                    <div class="event-registered-badge attended">
                                        <i class="fas fa-user-check"></i> Đã tham gia
                                    </div>
                                </div>
                                
                                <div class="event-content">
                                    <div class="event-meta">
                                        <span class="event-category"><?= $event->phan_loai ?? 'Chưa phân loại' ?></span>
                                        <span class="event-views"><i class="far fa-eye"></i> <?= $event->luot_xem ?? 0 ?></span>
                                    </div>
                                    
                                    <h3 class="event-title"><?= esc($event->ten_su_kien ?? $event->ten_sukien ?? 'Sự kiện không xác định') ?></h3>
                                    
                                    <div class="event-details">
                                        <div class="event-time">
                                            <i class="far fa-clock"></i>
                                            <?= isset($event->gio_bat_dau) ? date('H:i', strtotime($event->gio_bat_dau)) : '--:--' ?> - <?= isset($event->gio_ket_thuc) ? date('H:i', strtotime($event->gio_ket_thuc)) : '--:--' ?>
                                        </div>
                                        
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= esc($event->dia_diem ?? 'Không có địa điểm') ?>
                                        </div>
                                        
                                        <?php if(!empty($event->don_vi_to_chuc)): ?>
                                        <div class="event-organizer">
                                            <i class="fas fa-users"></i>
                                            <?= esc($event->don_vi_to_chuc) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if(!empty($event->mo_ta)): ?>
                                    <div class="event-description">
                                        <?= character_limiter(strip_tags($event->mo_ta), 100) ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="event-actions">
                                        <a href="<?= base_url('su-kien/chi-tiet/'.$event->slug) ?>" class="btn btn-details">
                                            <i class="fas fa-info-circle"></i> Chi tiết
                                        </a>
                                        
                                        <?php if(!empty($event->chung_chi)): ?>
                                            <a href="<?= base_url('nguoi-dung/certificate/download/'.$event->dangky_id) ?>" class="btn btn-certificate">
                                                <i class="fas fa-certificate"></i> Chứng chỉ
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h5 class="empty-state-title">Bạn chưa tham gia sự kiện nào</h5>
                            <p class="empty-state-description">Tham gia sự kiện để nhận các chứng chỉ và lợi ích khác</p>
                            <a href="<?= base_url('nguoi-dung/events-checkin') ?>" class="btn btn-primary" style="display: flex; justify-content: center; align-items: center;">
                                <i class="fas fa-clipboard-list me-1" style="margin-bottom: 0rem;"></i> Xem sự kiện đã đăng ký
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sự kiện sắp diễn ra -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="dashboard-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="fas fa-calendar-alt me-2"></i>Sự kiện sắp diễn ra
                    </h5>
                    <a href="<?= base_url('nguoi-dung/events/list') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="upcoming-events-grid">
                    <?php if(!empty($upcomingEvents)): ?>
                        <?php foreach($upcomingEvents as $event): ?>
                            <div class="event-card">
                                <div class="event-image">
                                    <?php 
                                        $eventDate = new DateTime($event->thoi_gian_bat_dau); 
                                        $now = new DateTime();
                                        $interval = $now->diff($eventDate);
                                        $remaining = '';
                                        if($interval->days > 0) {
                                            $remaining = $interval->format('%a ngày %h giờ');
                                        } else if($interval->h > 0) {
                                            $remaining = $interval->format('%h giờ %i phút');
                                        } else {
                                            $remaining = $interval->format('%i phút');
                                        }
                                    ?>
                                    <img src="<?= !empty($event->hinh_anh) ? base_url('uploads/events/'.$event->hinh_anh) : base_url('assets/images/events/default.jpg') ?>" 
                                        alt="<?= $event->ten_su_kien ?>">
                                    
                                    <div class="event-countdown">
                                        <i class="far fa-clock"></i> <?= $remaining ?>
                                    </div>
                                    
                                    <div class="event-date-badge">
                                        <div class="event-day"><?= $eventDate->format('d') ?></div>
                                        <div class="event-month">Th<?= $eventDate->format('m') ?></div>
                                        <div class="event-year"><?= $eventDate->format('Y') ?></div>
                                    </div>
                                </div>
                                
                                <div class="event-content">
                                    <div class="event-meta">
                                        <span class="event-category"><?= $event->phan_loai ?? 'Chưa phân loại' ?></span>
                                        <span class="event-views"><i class="far fa-eye"></i> <?= $event->luot_xem ?? 0 ?></span>
                                    </div>
                                    
                                    <h3 class="event-title"><?= esc($event->ten_su_kien ?? $event->ten_sukien ?? 'Sự kiện không xác định') ?></h3>
                                    
                                    <div class="event-details">
                                        <div class="event-time">
                                            <i class="far fa-clock"></i>
                                            <?= isset($event->gio_bat_dau) ? date('H:i', strtotime($event->gio_bat_dau)) : '--:--' ?> - <?= isset($event->gio_ket_thuc) ? date('H:i', strtotime($event->gio_ket_thuc)) : '--:--' ?>
                                        </div>
                                        
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= esc($event->dia_diem ?? 'Không có địa điểm') ?>
                                        </div>
                                        
                                        <?php if(!empty($event->don_vi_to_chuc)): ?>
                                        <div class="event-organizer">
                                            <i class="fas fa-users"></i>
                                            <?= esc($event->don_vi_to_chuc) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if(!empty($event->mo_ta)): ?>
                                    <div class="event-description">
                                        <?= character_limiter(strip_tags($event->mo_ta), 100) ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="event-actions">
                                        <a href="<?= base_url('su-kien/chi-tiet/'.$event->slug) ?>" class="w-100 bg-primary text-white btn btn-details">
                                            <i class="fas fa-info-circle"></i> Chi tiết
                                        </a>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h5 class="empty-state-title">Không có sự kiện nào sắp diễn ra</h5>
                            <p class="empty-state-description">Vui lòng quay lại sau để cập nhật các sự kiện mới</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/nguoidung/pages/dashboard.js') ?>"></script>
<?= $this->endSection() ?>

