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
            <div class="card dashboard-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>Sự kiện đã đăng ký gần đây
                    </h5>
                    <a href="<?= base_url('nguoi-dung/profile') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="event-list">
                        <?php if(!empty($registeredEvents)): ?>
                            <?php foreach($registeredEvents as $event): ?>
                                <div class="event-item">
                                    <div class="event-date">
                                        <?php 
                                            $eventDate = new DateTime($event->thoi_gian_bat_dau); 
                                            $day = $eventDate->format('d');
                                            $month = $eventDate->format('m');
                                        ?>
                                        <span class="event-day"><?= $day ?></span>
                                        <span class="event-month">Th<?= $month ?></span>
                                    </div>
                                    <div class="event-info">
                                        <div class="event-title"><?= $event->ten_su_kien ?></div>
                                        <div class="event-meta">
                                            <span class="event-time">
                                                <i class="far fa-clock"></i> 
                                                <?= substr($event->gio_bat_dau, 0, 5) ?> - <?= substr($event->gio_ket_thuc, 0, 5) ?>
                                            </span>
                                            <span class="event-location">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                <?= $event->dia_diem ?>
                                            </span>
                                        </div>
                                        <div class="event-status">
                                            <?php if($event->trang_thai == 1): ?>
                                                <span class="badge bg-success" data-bs-toggle="tooltip" title="Đăng ký của bạn đã được xác nhận">
                                                    <i class="fas fa-check-circle me-1"></i> Đã xác nhận
                                                </span>
                                            <?php elseif($event->trang_thai == 0): ?>
                                                <span class="badge bg-warning" data-bs-toggle="tooltip" title="Đăng ký của bạn đang chờ xác nhận">
                                                    <i class="fas fa-clock me-1"></i> Chờ xác nhận
                                                </span>
                                            <?php elseif($event->trang_thai == 2): ?>
                                                <span class="badge bg-danger" data-bs-toggle="tooltip" title="Đăng ký của bạn đã bị hủy">
                                                    <i class="fas fa-times-circle me-1"></i> Đã hủy
                                                </span>
                                            <?php endif; ?>
                                            
                                            <?php if($event->da_check_in == 1): ?>
                                                <span class="badge bg-primary" data-bs-toggle="tooltip" title="Bạn đã tham gia sự kiện này">
                                                    <i class="fas fa-user-check me-1"></i> Đã tham gia
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a href="<?= base_url('su-kien/detail/'.$event->slug) ?>" class="event-link" data-bs-toggle="tooltip" title="Xem chi tiết">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state p-4 text-center">
                                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                <p>Bạn chưa đăng ký sự kiện nào.</p>
                                <a href="<?= base_url('nguoi-dung/events/list') ?>" class="btn btn-primary btn-sm">
                                    Khám phá sự kiện ngay
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sự kiện đã tham gia gần đây -->
        <div class="col-md-6">
            <div class="card dashboard-card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Sự kiện đã tham gia
                    </h5>
                    <a href="<?= base_url('nguoi-dung/events-checkin') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="event-list">
                        <?php if(!empty($attendedEvents)): ?>
                            <?php foreach($attendedEvents as $event): ?>
                                <div class="event-item">
                                    <div class="event-date">
                                        <?php 
                                            $eventDate = new DateTime($event->thoi_gian_bat_dau); 
                                            $day = $eventDate->format('d');
                                            $month = $eventDate->format('m');
                                        ?>
                                        <span class="event-day"><?= $day ?></span>
                                        <span class="event-month">Th<?= $month ?></span>
                                    </div>
                                    <div class="event-info">
                                        <div class="event-title"><?= $event->ten_su_kien ?></div>
                                        <div class="event-meta">
                                            <span class="event-time">
                                                <i class="far fa-clock"></i> 
                                                <?= substr($event->gio_bat_dau, 0, 5) ?> - <?= substr($event->gio_ket_thuc, 0, 5) ?>
                                            </span>
                                            <span class="event-location">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                <?= $event->dia_diem ?>
                                            </span>
                                        </div>
                                        <div class="event-attendance">
                                            <span class="badge bg-success" data-bs-toggle="tooltip" title="Bạn đã tham gia sự kiện này">
                                                <i class="fas fa-check me-1"></i> Đã tham gia
                                            </span>
                                            <?php if(!empty($event->chung_chi)): ?>
                                                <a href="<?= base_url('nguoi-dung/certificate/download/'.$event->dangky_id) ?>" class="badge bg-info text-white text-decoration-none" data-bs-toggle="tooltip" title="Tải chứng chỉ tham gia">
                                                    <i class="fas fa-certificate me-1"></i> Chứng chỉ
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <a href="<?= base_url('su-kien/detail/'.$event->slug) ?>" class="event-link" data-bs-toggle="tooltip" title="Xem chi tiết">
                                        <i class="fas fa-angle-right"></i>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state p-4 text-center text-white">
                                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                <p>Bạn chưa tham gia sự kiện nào.</p>
                                <a href="<?= base_url('nguoi-dung/profile') ?>" class="btn btn-primary btn-sm text-white">
                                    Xem sự kiện đã đăng ký
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sự kiện sắp diễn ra -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>Sự kiện sắp diễn ra
                    </h5>
                    <a href="<?= base_url('nguoi-dung/events/list') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body">
                    <div class="row upcoming-events">
                        <?php if(!empty($upcomingEvents)): ?>
                            <?php foreach($upcomingEvents as $event): ?>
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="upcoming-event-card">
                                        <div class="event-image">
                                            <img src="<?= !empty($event->hinh_anh) ? base_url('uploads/events/'.$event->hinh_anh) : base_url('assets/images/events/default.jpg') ?>" 
                                                alt="<?= $event->ten_su_kien ?>">
                                            <div class="event-date-badge">
                                                <?php 
                                                    $eventDate = new DateTime($event->thoi_gian_bat_dau); 
                                                    $day = $eventDate->format('d');
                                                    $month = $eventDate->format('m');
                                                ?>
                                                <span class="event-day"><?= $day ?></span>
                                                <span class="event-month">Th<?= $month ?></span>
                                            </div>
                                        </div>
                                        <div class="event-content">
                                            <h6 class="event-title"><?= $event->ten_su_kien ?></h6>
                                            <div class="event-details">
                                                <div class="event-timing">
                                                    <i class="far fa-clock"></i> 
                                                    <?= substr($event->gio_bat_dau, 0, 5) ?> - <?= substr($event->gio_ket_thuc, 0, 5) ?>
                                                </div>
                                                <div class="event-venue">
                                                    <i class="fas fa-map-marker-alt"></i> 
                                                    <?= $event->dia_diem ?>
                                                </div>
                                            </div>
                                            <div class="event-actions">
                                                <a href="<?= base_url('su-kien/detail/'.$event->slug) ?>" class="btn btn-primary btn-sm w-100">
                                                    <i class="fas fa-info-circle me-1"></i> Chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="empty-state p-4 text-center">
                                    <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                    <p>Không có sự kiện nào sắp diễn ra.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/nguoidung/pages/dashboard.js') ?>"></script>
<?= $this->endSection() ?>

