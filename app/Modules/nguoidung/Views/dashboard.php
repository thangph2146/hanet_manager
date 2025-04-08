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
                        <h2 class="welcome-title">Xin chào, <?= esc(is_object($profile) && method_exists($profile, 'getFullName') ? $profile->getFullName() : ($profile->FullName ?? 'Người dùng')) ?>!</h2>
                        <p class="welcome-text">Chào mừng bạn quay trở lại với hệ thống quản lý sự kiện của chúng tôi.</p>
                        <?php if(is_object($profile) && method_exists($profile, 'getLastLoginFormatted') && $profile->getLastLoginFormatted()): ?>
                        <p class="last-login-info">Đăng nhập lần cuối: <?= $profile->getLastLoginFormatted() ?></p>
                        <?php elseif(isset($profile->last_login) && $profile->last_login): ?>
                        <p class="last-login-info">Đăng nhập lần cuối: <?= date('d/m/Y H:i:s', strtotime($profile->last_login)) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="<?= base_url('su-kien') ?>" class="btn btn-light btn-lg">
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
        <!-- Thông tin người dùng -->
        <div class="col-md-3 mb-4 mb-md-0">
            <div class="dashboard-section user-profile-card">
                <div class="user-avatar">
                    <?php
                    try {
                        $avatarUrl = is_object($profile) && method_exists($profile, 'getAvatarUrl') 
                            ? $profile->getAvatarUrl() 
                            : (isset($profile->avatar) && !empty($profile->avatar) 
                                ? base_url($profile->avatar) 
                                : base_url('assets/images/avatars/default.jpg'));
                    } catch (Exception $e) {
                        $avatarUrl = base_url('assets/images/avatars/default.jpg');
                    }
                    ?>
                    <img src="<?= $avatarUrl ?>" alt="Avatar" class="img-fluid rounded-circle" loading="lazy">
                </div>
                <div class="user-info">
                    <?php 
                    $displayName = 'Người dùng';
                    $email = '';
                    $phone = '';
                    
                    try {
                        if (is_object($profile)) {
                            if (method_exists($profile, 'getDisplayName')) {
                                $displayName = $profile->getDisplayName();
                            } elseif (isset($profile->FullName)) {
                                $displayName = $profile->FullName;
                            }
                            
                            if (method_exists($profile, 'getEmail')) {
                                $email = $profile->getEmail();
                            } elseif (isset($profile->Email)) {
                                $email = $profile->Email;
                            }
                            
                            if (method_exists($profile, 'getMobilePhone')) {
                                $phone = $profile->getMobilePhone();
                            } elseif (isset($profile->MobilePhone)) {
                                $phone = $profile->MobilePhone;
                            }
                        }
                    } catch (Exception $e) {
                        // Giữ nguyên giá trị mặc định
                    }
                    ?>
                    <h4 class="user-name"><?= esc($displayName) ?></h4>
                    <?php if ($email): ?>
                    <p class="user-email"><?= esc($email) ?></p>
                    <?php endif; ?>
                    
                    <?php if ($phone): ?>
                    <p class="user-phone"><?= esc($phone) ?></p>
                    <?php endif; ?>
                    
                    <?php 
                    try {
                        if (is_object($profile) && method_exists($profile, 'getLoaiNguoiDungDisplay') && $profile->getLoaiNguoiDungDisplay()): 
                    ?>
                    <p class="user-type"><span>Loại người dùng:</span> <?= esc($profile->getLoaiNguoiDungDisplay()) ?></p>
                    <?php 
                        endif;
                    } catch (Exception $e) {} 
                    ?>
                    
                    <?php 
                    try {
                        if (is_object($profile) && method_exists($profile, 'getPhongKhoaDisplay') && $profile->getPhongKhoaDisplay()): 
                    ?>
                    <p class="user-department"><span>Phòng/Khoa:</span> <?= esc($profile->getPhongKhoaDisplay()) ?></p>
                    <?php 
                        endif;
                    } catch (Exception $e) {} 
                    ?>
                    
                    <?php 
                    try {
                        if (is_object($profile) && method_exists($profile, 'isActive') && method_exists($profile, 'getStatusLabel')): 
                    ?>
                    <div class="user-status <?= $profile->isActive() ? 'active' : 'inactive' ?>">
                        <?= $profile->getStatusLabel() ?>
                    </div>
                    <?php 
                        elseif (isset($profile->status)): 
                    ?>
                    <div class="user-status <?= (int)$profile->status === 1 ? 'active' : 'inactive' ?>">
                        <?= (int)$profile->status === 1 ? 'Đang hoạt động' : 'Không hoạt động' ?>
                    </div>
                    <?php 
                        endif;
                    } catch (Exception $e) {} 
                    ?>
                    
                    <a href="<?= base_url('nguoi-dung/thong-tin-ca-nhan') ?>" class="btn btn-outline-primary btn-sm mt-3 w-100">
                        <i class="fas fa-user-edit me-1"></i> Chỉnh sửa thông tin
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Sự kiện đã đăng ký gần đây -->
        <div class="col-md-9">
            <div class="dashboard-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="fas fa-clipboard-list me-2"></i>Sự kiện đã đăng ký gần đây
                    </h5>
                    <a href="<?= base_url('nguoi-dung/events-checkin') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="events-grid">
                    <?php if(!empty($registeredEvents)): ?>
                        <?php 
                        // Giới hạn tối đa 3 sự kiện
                        $limitedEvents = array_slice($registeredEvents, 0, 3);
                        foreach($limitedEvents as $event): 
                        ?>
                            <?php
                            try {
                                // Đảm bảo event là object
                                if (!is_object($event)) continue;
                                
                                // Lấy ngày sự kiện từ nhiều trường khả dĩ
                                $eventDateStr = null;
                                foreach (['thoi_gian_bat_dau', 'ngay_to_chuc', 'created_at'] as $dateField) {
                                    if (isset($event->$dateField) && !empty($event->$dateField)) {
                                        $eventDateStr = $event->$dateField;
                                        break;
                                    }
                                }
                                
                                if (!$eventDateStr) continue; // Bỏ qua sự kiện không có thông tin ngày
                                
                                $eventDate = new DateTime($eventDateStr);
                                $now = new DateTime();
                                $isUpcoming = $eventDate > $now;
                                
                                // Xác định tên sự kiện từ các trường khả dĩ
                                $eventName = '';
                                foreach (['ten_su_kien', 'ten_sukien', 'tieu_de'] as $nameField) {
                                    if (isset($event->$nameField) && !empty($event->$nameField)) {
                                        $eventName = $event->$nameField;
                                        break;
                                    }
                                }
                                if (empty($eventName)) {
                                    $eventName = 'Sự kiện không xác định';
                                }
                                
                                // Xác định địa điểm
                                $location = '';
                                foreach (['dia_diem', 'venue', 'dia_chi'] as $locationField) {
                                    if (isset($event->$locationField) && !empty($event->$locationField)) {
                                        $location = $event->$locationField;
                                        break;
                                    }
                                }
                                if (empty($location)) {
                                    $location = 'Không có địa điểm';
                                }
                                
                                // Xác định đơn vị tổ chức
                                $organizer = '';
                                foreach (['don_vi_to_chuc', 'to_chuc', 'ban_to_chuc'] as $organizerField) {
                                    if (isset($event->$organizerField) && !empty($event->$organizerField)) {
                                        $organizer = $event->$organizerField;
                                        break;
                                    }
                                }
                                
                                // Xác định URL sự kiện
                                $eventSlug = null;
                                foreach (['slug', 'su_kien_id', 'id'] as $slugField) {
                                    if (isset($event->$slugField) && !empty($event->$slugField)) {
                                        $eventSlug = $event->$slugField;
                                        break;
                                    }
                                }
                                
                                // Xác định trạng thái
                                $status = -1;
                                foreach (['trang_thai', 'status', 'trang_thai_dang_ky'] as $statusField) {
                                    if (isset($event->$statusField)) {
                                        $status = (int)$event->$statusField;
                                        break;
                                    }
                                }
                                
                                // Xác định trạng thái check-in
                                $checkedIn = false;
                                if (isset($event->da_check_in)) {
                                    $checkedIn = (bool)$event->da_check_in;
                                }
                                
                                // Xác định thời gian
                                $startTime = '';
                                $endTime = '';
                                foreach (['gio_bat_dau', 'thoi_gian_bat_dau'] as $timeField) {
                                    if (isset($event->$timeField) && !empty($event->$timeField)) {
                                        $startTime = date('H:i', strtotime($event->$timeField));
                                        break;
                                    }
                                }
                                foreach (['gio_ket_thuc', 'thoi_gian_ket_thuc'] as $timeField) {
                                    if (isset($event->$timeField) && !empty($event->$timeField)) {
                                        $endTime = date('H:i', strtotime($event->$timeField));
                                        break;
                                    }
                                }
                            } catch (Exception $e) {
                                log_message('error', 'Lỗi xử lý sự kiện: ' . $e->getMessage());
                                continue; // Bỏ qua sự kiện này
                            }
                            ?>
                            <div class="event-card">
                                <div class="event-image">
                                    <?php 
                                        $imagePath = !empty($event->hinh_anh) ? base_url('uploads/events/' . $event->hinh_anh) : base_url('assets/images/events/default.jpg');
                                    ?>
                                    <img src="<?= $imagePath ?>" alt="<?= esc($eventName) ?>" loading="lazy">
                                    
                                    <?php if($isUpcoming): ?>
                                        <?php 
                                        try {
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
                                        <?php 
                                        } catch (Exception $e) {} 
                                        ?>
                                    <?php endif; ?>
                                    
                                    <div class="event-date-badge">
                                        <div class="event-day"><?= $eventDate->format('d') ?></div>
                                        <div class="event-month">Th<?= $eventDate->format('m') ?></div>
                                        <div class="event-year"><?= $eventDate->format('Y') ?></div>
                                    </div>
                                    
                                    <?php if($status == 1): ?>
                                        <div class="event-registered-badge">
                                            <i class="fas fa-check-circle"></i> Đã xác nhận
                                        </div>
                                    <?php elseif($status == 0): ?>
                                        <div class="event-registered-badge pending">
                                            <i class="fas fa-clock"></i> Chờ xác nhận
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if($checkedIn): ?>
                                        <div class="event-registered-badge attended">
                                            <i class="fas fa-user-check"></i> Đã tham gia
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="event-content">
                                    <div class="event-meta">
                                        <span class="event-category"><?= isset($event->phan_loai) && !empty($event->phan_loai) ? esc($event->phan_loai) : 'Chưa phân loại' ?></span>
                                        <span class="event-views"><i class="far fa-eye"></i> <?= $event->luot_xem ?? 0 ?></span>
                                    </div>
                                    
                                    <h3 class="event-title"><?= esc($eventName) ?></h3>
                                    
                                    <div class="event-details">
                                        <div class="event-time">
                                            <i class="far fa-clock"></i>
                                            <?= $startTime ?: '--:--' ?> - <?= $endTime ?: '--:--' ?>
                                        </div>
                                        
                                        <div class="event-location">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <?= esc($location) ?>
                                        </div>
                                        
                                        <?php if(!empty($organizer)): ?>
                                        <div class="event-organizer">
                                            <i class="fas fa-users"></i>
                                            <?= esc($organizer) ?>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <?php if(!empty($event->mo_ta)): ?>
                                    <div class="event-description">
                                        <?= character_limiter(strip_tags($event->mo_ta), 100) ?>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <div class="event-actions">
                                        <?php if($eventSlug): ?>
                                        <a href="<?= base_url('su-kien/chi-tiet/'.$eventSlug) ?>" class="btn btn-details">
                                            <i class="fas fa-info-circle"></i> Chi tiết
                                        </a>
                                        <?php endif; ?>
                                        
                                        <?php 
                                        // Xác định ID đăng ký an toàn
                                        $registrationId = null;
                                        // Kiểm tra xem registrationId đã được xác định chưa
                                        if (!isset($registrationId) || empty($registrationId)) {
                                            foreach (['dangky_id', 'dangky_sukien_id', 'id'] as $idField) {
                                                if (isset($event->$idField) && !empty($event->$idField)) {
                                                    $registrationId = $event->$idField;
                                                    break;
                                                }
                                            }
                                        }
                                        
                                        if(!empty($event->chung_chi) && $registrationId): 
                                        ?>
                                        <a href="<?= base_url('nguoi-dung/certificate/download/'.$registrationId) ?>" class="btn btn-certificate">
                                            <i class="fas fa-certificate"></i> Chứng chỉ
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        
                        <?php if (count($registeredEvents) > 3): 
                        ?>
                        <div class="view-more-container">
                            <a href="<?= base_url('nguoi-dung/events-checkin') ?>" class="btn btn-outline-primary btn-view-more">
                                <i class="fas fa-plus-circle me-1"></i> Xem thêm <?= count($registeredEvents) - 3 ?> sự kiện
                            </a>
                        </div>
                        <?php endif; ?>
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
    </div>

    <div class="row">
        <!-- Sự kiện đã tham gia gần đây -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="fas fa-check-circle me-2"></i>Sự kiện đã tham gia
                    </h5>
                    <a href="<?= base_url('nguoi-dung/events-history-register') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="events-grid">
                    <?php if(!empty($attendedEvents)): ?>
                        <?php 
                        // Giới hạn tối đa 3 sự kiện
                        $limitedAttendedEvents = array_slice($attendedEvents, 0, 3);
                        foreach($limitedAttendedEvents as $event): 
                        ?>
                            <div class="event-card">
                                <div class="event-image">
                                    <?php 
                                    try {
                                        // Tìm trường thời gian từ nhiều trường khả dĩ
                                        $eventDateStr = null;
                                        foreach (['thoi_gian_bat_dau', 'ngay_to_chuc', 'created_at'] as $dateField) {
                                            if (isset($event->$dateField) && !empty($event->$dateField)) {
                                                $eventDateStr = $event->$dateField;
                                                break;
                                            }
                                        }
                                        
                                        if (!$eventDateStr) {
                                            $eventDateStr = date('Y-m-d H:i:s'); // Giá trị mặc định
                                        }
                                        
                                        $eventDate = new DateTime($eventDateStr);
                                    ?>
                                    <img src="<?= !empty($event->hinh_anh) ? base_url('uploads/events/' . $event->hinh_anh) : base_url('assets/images/events/default.jpg') ?>" 
                                        alt="<?= $event->ten_su_kien ?? $event->ten_sukien ?? 'Sự kiện không xác định' ?>" loading="lazy">
                                    
                                    <div class="event-date-badge">
                                        <div class="event-day"><?= $eventDate->format('d') ?></div>
                                        <div class="event-month">Th<?= $eventDate->format('m') ?></div>
                                        <div class="event-year"><?= $eventDate->format('Y') ?></div>
                                    </div>
                                    
                                    <div class="event-registered-badge attended">
                                        <i class="fas fa-user-check"></i> Đã tham gia
                                    </div>
                                    <?php 
                                    } catch (Exception $e) {
                                        // Xử lý lỗi một cách im lặng
                                    ?>
                                    <img src="<?= base_url('assets/images/events/default.jpg') ?>" alt="Sự kiện" loading="lazy">
                                    <div class="event-date-badge">
                                        <div class="event-day"><?= date('d') ?></div>
                                        <div class="event-month">Th<?= date('m') ?></div>
                                        <div class="event-year"><?= date('Y') ?></div>
                                    </div>
                                    <div class="event-registered-badge attended">
                                        <i class="fas fa-user-check"></i> Đã tham gia
                                    </div>
                                    <?php } ?>
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
                                            <?php
                                            $gioBatDau = '--:--';
                                            $gioKetThuc = '--:--';
                                            
                                            if (isset($event->gio_bat_dau) && !empty($event->gio_bat_dau)) {
                                                $gioBatDau = date('H:i', strtotime($event->gio_bat_dau));
                                            } elseif (isset($event->thoi_gian_bat_dau) && !empty($event->thoi_gian_bat_dau)) {
                                                $gioBatDau = date('H:i', strtotime($event->thoi_gian_bat_dau));
                                            }
                                            
                                            if (isset($event->gio_ket_thuc) && !empty($event->gio_ket_thuc)) {
                                                $gioKetThuc = date('H:i', strtotime($event->gio_ket_thuc));
                                            } elseif (isset($event->thoi_gian_ket_thuc) && !empty($event->thoi_gian_ket_thuc)) {
                                                $gioKetThuc = date('H:i', strtotime($event->thoi_gian_ket_thuc));
                                            }
                                            ?>
                                            <?= $gioBatDau ?> - <?= $gioKetThuc ?>
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
                                        <a href="<?= base_url('su-kien/chi-tiet/'.($event->slug ?? $event->su_kien_id ?? 0)) ?>" class="btn btn-details">
                                            <i class="fas fa-info-circle"></i> Chi tiết
                                        </a>
                                        
                                        <?php 
                                        $dangky_id = null;
                                        foreach (['dangky_id', 'dangky_sukien_id', 'id'] as $idField) {
                                            if (isset($event->$idField) && !empty($event->$idField)) {
                                                $dangky_id = $event->$idField;
                                                break;
                                            }
                                        }
                                        
                                        if(!empty($event->chung_chi) && $dangky_id): 
                                        ?>
                                            <a href="<?= base_url('nguoi-dung/certificate/download/'.$dangky_id) ?>" class="btn btn-certificate">
                                                <i class="fas fa-certificate"></i> Chứng chỉ
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                        
                        if (count($attendedEvents) > 3): 
                        ?>
                        <div class="view-more-container">
                            <a href="<?= base_url('nguoi-dung/events-history-register') ?>" class="btn btn-outline-primary btn-view-more">
                                <i class="fas fa-plus-circle me-1"></i> Xem thêm <?= count($attendedEvents) - 3 ?> sự kiện
                            </a>
                        </div>
                        <?php endif; ?>
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

        <!-- Sự kiện sắp diễn ra -->
        <div class="col-md-6 mb-4">
            <div class="dashboard-section">
                <div class="section-header">
                    <h5 class="section-title">
                        <i class="fas fa-calendar-alt me-2"></i>Sự kiện sắp diễn ra
                    </h5>
                    <a href="<?= base_url('nguoi-dung/events/list') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="upcoming-events-grid">
                    <?php if(!empty($upcomingEvents)): ?>
                        <?php 
                        // Giới hạn tối đa 3 sự kiện
                        $limitedUpcomingEvents = array_slice($upcomingEvents, 0, 3);
                        foreach($limitedUpcomingEvents as $event): 
                        ?>
                            <div class="event-card">
                                <div class="event-image">
                                    <?php 
                                    try {
                                        // Tìm trường thời gian từ nhiều trường khả dĩ
                                        $eventDateStr = null;
                                        foreach (['thoi_gian_bat_dau', 'ngay_to_chuc', 'created_at'] as $dateField) {
                                            if (isset($event->$dateField) && !empty($event->$dateField)) {
                                                $eventDateStr = $event->$dateField;
                                                break;
                                            }
                                        }
                                        
                                        if (!$eventDateStr) {
                                            $eventDateStr = date('Y-m-d H:i:s'); // Giá trị mặc định
                                        }
                                        
                                        $eventDate = new DateTime($eventDateStr);
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
                                        alt="<?= $event->ten_su_kien ?? $event->ten_sukien ?? 'Sự kiện' ?>" loading="lazy">
                                    
                                    <div class="event-countdown">
                                        <i class="far fa-clock"></i> <?= $remaining ?>
                                    </div>
                                    
                                    <div class="event-date-badge">
                                        <div class="event-day"><?= $eventDate->format('d') ?></div>
                                        <div class="event-month">Th<?= $eventDate->format('m') ?></div>
                                        <div class="event-year"><?= $eventDate->format('Y') ?></div>
                                    </div>
                                    <?php 
                                    } catch (Exception $e) {
                                        // Xử lý lỗi một cách im lặng, hiển thị thông tin tối thiểu
                                    ?>
                                    <img src="<?= base_url('assets/images/events/default.jpg') ?>" alt="Sự kiện" loading="lazy">
                                    <div class="event-date-badge">
                                        <div class="event-day"><?= date('d') ?></div>
                                        <div class="event-month">Th<?= date('m') ?></div>
                                        <div class="event-year"><?= date('Y') ?></div>
                                    </div>
                                    <?php } ?>
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
                                            <?php
                                            $gioBatDau = '--:--';
                                            $gioKetThuc = '--:--';
                                            
                                            if (isset($event->gio_bat_dau) && !empty($event->gio_bat_dau)) {
                                                $gioBatDau = date('H:i', strtotime($event->gio_bat_dau));
                                            } elseif (isset($event->thoi_gian_bat_dau) && !empty($event->thoi_gian_bat_dau)) {
                                                $gioBatDau = date('H:i', strtotime($event->thoi_gian_bat_dau));
                                            }
                                            
                                            if (isset($event->gio_ket_thuc) && !empty($event->gio_ket_thuc)) {
                                                $gioKetThuc = date('H:i', strtotime($event->gio_ket_thuc));
                                            } elseif (isset($event->thoi_gian_ket_thuc) && !empty($event->thoi_gian_ket_thuc)) {
                                                $gioKetThuc = date('H:i', strtotime($event->thoi_gian_ket_thuc));
                                            }
                                            ?>
                                            <?= $gioBatDau ?> - <?= $gioKetThuc ?>
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
                                        <a href="<?= base_url('su-kien/chi-tiet/'.($event->slug ?? $event->su_kien_id ?? 0)) ?>" class="w-100 bg-primary text-white btn btn-details">
                                            <i class="fas fa-info-circle"></i> Chi tiết
                                        </a>
                                        
                                    </div>
                                </div>
                            </div>
                        <?php endforeach;
                        
                        if (count($upcomingEvents) > 3): 
                        ?>
                        <div class="view-more-container">
                            <a href="<?= base_url('nguoi-dung/events/list') ?>" class="btn btn-outline-primary btn-view-more">
                                <i class="fas fa-plus-circle me-1"></i> Xem thêm <?= count($upcomingEvents) - 3 ?> sự kiện
                            </a>
                        </div>
                        <?php endif; ?>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hiệu ứng loading
        const dashboardSections = document.querySelectorAll('.dashboard-section');
        
        // Hiệu ứng loading khi trang tải xong
        setTimeout(() => {
            dashboardSections.forEach((section, index) => {
                setTimeout(() => {
                    section.classList.add('loaded');
                }, index * 150);
            });
        }, 300);
        
        // Hiệu ứng đếm cho các thẻ thống kê
        const statValues = document.querySelectorAll('.stat-value');
        statValues.forEach(value => {
            const target = parseInt(value.getAttribute('data-value'), 10);
            const duration = 1500;
            const startTime = performance.now();
            
            // Sử dụng requestAnimationFrame để tối ưu hiệu suất
            const animateCount = (timestamp) => {
                const runtime = timestamp - startTime;
                const progress = Math.min(runtime / duration, 1);
                const currentCount = Math.floor(progress * target);
                
                value.textContent = currentCount;
                
                if (runtime < duration) {
                    requestAnimationFrame(animateCount);
                } else {
                    value.textContent = target;
                }
            };
            
            requestAnimationFrame(animateCount);
        });
    });
    
    // Lazy loading cho hình ảnh
    if ('loading' in HTMLImageElement.prototype) {
        // Trình duyệt hỗ trợ lazy loading
        const images = document.querySelectorAll('img[loading="lazy"]');
    } else {
        // Trình duyệt không hỗ trợ - tải thư viện thay thế
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
        document.body.appendChild(script);
    }
</script>
<?= $this->endSection() ?>

