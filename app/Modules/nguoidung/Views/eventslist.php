<?= $this->extend('frontend/layouts/nguoidung_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/pages/eventslist.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="events-list-page">
    <!-- Page header -->
    <div class="page-header">
        <h1 class="page-title">Danh sách sự kiện</h1>
        <p class="page-description">Khám phá các sự kiện đang diễn ra và sắp tới</p>
        <div class="page-header-overlay"></div>
    </div>
    
    <!-- Thống kê tổng quan -->
    <div class="stats-container">
        <div class="stats-item stats-total">
            <div class="stats-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stats-info">
                <div class="stats-value" id="total-events" data-count="<?= is_array($events) ? count($events) : 0 ?>"><?= is_array($events) ? count($events) : 0 ?></div>
                <div class="stats-label">Tổng số sự kiện</div>
            </div>
        </div>
        
        <div class="stats-item stats-upcoming">
            <div class="stats-icon">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div class="stats-info">
                <div class="stats-value" id="upcoming-events" data-count="<?= $upcomingCount ?? 0 ?>"><?= $upcomingCount ?? 0 ?></div>
                <div class="stats-label">Sắp diễn ra</div>
            </div>
        </div>
        
        <div class="stats-item stats-registered">
            <div class="stats-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stats-info">
                <div class="stats-value" id="registered-events" data-count="<?= $registeredCount ?? 0 ?>"><?= $registeredCount ?? 0 ?></div>
                <div class="stats-label">Đã đăng ký</div>
            </div>
        </div>
        
        <div class="stats-item stats-attended">
            <div class="stats-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stats-info">
                <div class="stats-value" id="attended-events" data-count="<?= $attendedCount ?? 0 ?>"><?= $attendedCount ?? 0 ?></div>
                <div class="stats-label">Đã tham gia</div>
            </div>
        </div>
    </div>
    
    <!-- Bộ lọc và tìm kiếm -->
    <div class="filter-container">
        <div class="search-box">
            <input type="text" id="eventSearch" placeholder="Tìm kiếm sự kiện..." 
                   value="<?= $current_filter['search'] ?? '' ?>">
            <i class="fas fa-search"></i>
        </div>
        
        <div class="filter-options">
            <div class="filter-group">
                <label for="eventCategory">Phân loại</label>
                <select id="eventCategory">
                    <option value="all" <?= (!isset($current_filter['category']) || $current_filter['category'] == 'all') ? 'selected' : '' ?>>Tất cả</option>
                    <?php if(!empty($categories)): ?>
                        <?php foreach($categories as $category_id => $category_name): ?>
                            <option value="<?= $category_id ?>" <?= (isset($current_filter['category']) && $current_filter['category'] == $category_id) ? 'selected' : '' ?>><?= $category_name ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="eventSort">Sắp xếp</label>
                <select id="eventSort">
                    <option value="upcoming" <?= (isset($current_filter['sort']) && $current_filter['sort'] == 'upcoming') ? 'selected' : '' ?>>Sắp diễn ra</option>
                    <option value="newest" <?= (isset($current_filter['sort']) && $current_filter['sort'] == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="popular" <?= (isset($current_filter['sort']) && $current_filter['sort'] == 'popular') ? 'selected' : '' ?>>Phổ biến nhất</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="eventStatus">Trạng thái</label>
                <select id="eventStatus">
                    <option value="all" <?= (isset($current_filter['status']) && $current_filter['status'] == 'all') ? 'selected' : '' ?>>Tất cả</option>
                    <option value="upcoming" <?= (isset($current_filter['status']) && $current_filter['status'] == 'upcoming') ? 'selected' : '' ?>>Sắp diễn ra</option>
                    <option value="ongoing" <?= (isset($current_filter['status']) && $current_filter['status'] == 'ongoing') ? 'selected' : '' ?>>Đang diễn ra</option>
                    <option value="ended" <?= (isset($current_filter['status']) && $current_filter['status'] == 'ended') ? 'selected' : '' ?>>Đã kết thúc</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="registrationStatus">Đăng ký</label>
                <select id="registrationStatus">
                    <option value="all" <?= (isset($current_filter['registration']) && $current_filter['registration'] == 'all') ? 'selected' : '' ?>>Tất cả</option>
                    <option value="registered" <?= (isset($current_filter['registration']) && $current_filter['registration'] == 'registered') ? 'selected' : '' ?>>Đã đăng ký</option>
                    <option value="attended" <?= (isset($current_filter['registration']) && $current_filter['registration'] == 'attended') ? 'selected' : '' ?>>Đã tham gia</option>
                    <option value="not_registered" <?= (isset($current_filter['registration']) && $current_filter['registration'] == 'not_registered') ? 'selected' : '' ?>>Chưa đăng ký</option>
                </select>
            </div>
            
            <button id="applyFilters" class="btn-apply-filters">
                <i class="fas fa-filter"></i> Lọc
            </button>
            
            <button id="resetFilters" class="btn-reset-filters">
                <i class="fas fa-redo"></i> Đặt lại
            </button>
        </div>
    </div>
    
    <!-- Danh sách sự kiện -->
    <div class="events-container">
        <?php if(empty($events)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h3 class="empty-state-title">Không tìm thấy sự kiện nào</h3>
                <p class="empty-state-description">Hiện tại không có sự kiện nào phù hợp với tiêu chí tìm kiếm. Vui lòng thử lại với các bộ lọc khác.</p>
                <button id="resetFilters" class="btn-reset-filters">
                    <i class="fas fa-redo"></i> Đặt lại bộ lọc
                </button>
            </div>
        <?php else: ?>
            <div class="events-grid">
                <?php foreach($events as $event): ?>
                    <?php 
                        $eventDate = new DateTime($event->thoi_gian_bat_dau ?? date('Y-m-d H:i:s'));
                        $now = new DateTime();
                        $isUpcoming = $eventDate > $now;
                        
                        // Kiểm tra sự kiện đang diễn ra
                        $endDate = new DateTime($event->thoi_gian_ket_thuc ?? $event->thoi_gian_bat_dau ?? date('Y-m-d H:i:s'));
                        $isOngoing = $now >= $eventDate && $now <= $endDate;
                        
                        $eventStatus = $isUpcoming ? 'upcoming' : ($isOngoing ? 'ongoing' : 'ended');
                        
                        // Tính thời gian còn lại
                        $remaining = '';
                        if($isUpcoming) {
                            $interval = $now->diff($eventDate);
                            if($interval->days > 0) {
                                $remaining = $interval->format('%a ngày %h giờ');
                            } else if($interval->h > 0) {
                                $remaining = $interval->format('%h giờ %i phút');
                            } else {
                                $remaining = $interval->format('%i phút');
                            }
                        } elseif ($isOngoing) {
                            $interval = $now->diff($endDate);
                            $remaining = 'Còn: ' . $interval->format('%h giờ %i phút');
                        }
                        
                        // Kiểm tra người dùng đã đăng ký chưa
                        $isRegistered = isset($userEvents) && in_array($event->ma_su_kien, $userEvents);
                        $hasAttended = isset($attendedEvents) && in_array($event->ma_su_kien, $attendedEvents);
                        
                        // Thiết lập trạng thái đăng ký
                        $registrationStatus = 'not_registered';
                        if($hasAttended) {
                            $registrationStatus = 'attended';
                        } elseif($isRegistered) {
                            $registrationStatus = 'registered';
                        }
                        
                        // Định dạng ảnh
                        $eventImage = !empty($event->hinh_anh) 
                            ? base_url('public/uploads/sukien/' . $event->hinh_anh)
                            : base_url('public/assets/images/events/default.jpg');
                    ?>
                    <div class="event-card" 
                         data-category="<?= $event->phan_loai ?? '' ?>"
                         data-date="<?= $event->thoi_gian_bat_dau ?? '' ?>"
                         data-views="<?= $event->luot_xem ?? 0 ?>"
                         data-status="<?= $eventStatus ?>"
                         data-registration="<?= $registrationStatus ?>">
                        <div class="event-image">
                            <img src="<?= $eventImage ?>" alt="<?= $event->ten_su_kien ?? 'Sự kiện' ?>">
                            
                            <?php if($isUpcoming && !empty($remaining)): ?>
                                <div class="event-countdown">
                                    <i class="far fa-clock"></i> <?= $remaining ?>
                                </div>
                            <?php elseif($isOngoing): ?>
                                <div class="event-countdown ongoing">
                                    <i class="fas fa-hourglass-half"></i> <?= $remaining ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="event-date-badge">
                                <div class="event-day"><?= $eventDate->format('d') ?></div>
                                <div class="event-month">Th<?= $eventDate->format('m') ?></div>
                                <div class="event-year"><?= $eventDate->format('Y') ?></div>
                            </div>
                            
                            <?php if($registrationStatus == 'registered'): ?>
                                <div class="event-registered-badge">
                                    <i class="fas fa-check-circle"></i> Đã đăng ký
                                </div>
                            <?php elseif($registrationStatus == 'attended'): ?>
                                <div class="event-registered-badge attended">
                                    <i class="fas fa-calendar-check"></i> Đã tham gia
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="event-content">
                            <div class="event-meta">
                                <span class="event-category"><?= $event->phan_loai ?? 'Chưa phân loại' ?></span>
                                <span class="event-views"><i class="far fa-eye"></i> <?= $event->luot_xem ?? 0 ?></span>
                            </div>
                            
                            <h3 class="event-title"><?= $event->ten_su_kien ?? 'Chưa có tên' ?></h3>
                            
                            <div class="event-details">
                                <div class="event-time">
                                    <i class="far fa-clock"></i>
                                    <?= isset($event->gio_bat_dau) ? date('H:i', strtotime($event->gio_bat_dau)) : '--:--' ?> - <?= isset($event->gio_ket_thuc) ? date('H:i', strtotime($event->gio_ket_thuc)) : '--:--' ?>
                                </div>
                                
                                <div class="event-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= $event->dia_diem ?? 'Chưa cập nhật địa điểm' ?>
                                </div>
                                
                                <div class="event-organizer">
                                    <i class="fas fa-users"></i>
                                    <?= $event->don_vi_to_chuc ?? 'Chưa cập nhật đơn vị tổ chức' ?>
                                </div>
                            </div>
                            
                            <div class="event-description">
                                <?= isset($event->mo_ta) ? character_limiter(strip_tags($event->mo_ta), 150) : 'Chưa có mô tả' ?>
                            </div>
                            
                            <div class="event-stats">
                                <div class="event-stat">
                                    <i class="fas fa-users"></i>
                                    <span><?= $event->so_luong_dang_ky ?? 0 ?> đăng ký</span>
                                </div>
                                <div class="event-stat">
                                    <i class="fas fa-calendar-check"></i>
                                    <span><?= $event->so_luong_check_in ?? 0 ?> tham dự</span>
                                </div>
                                
                                <?php if(isset($event->so_luong_toi_da) && $event->so_luong_toi_da > 0): ?>
                                <div class="event-stat capacity">
                                    <i class="fas fa-user-friends"></i>
                                    <span><?= $event->so_luong_dang_ky ?? 0 ?>/<?= $event->so_luong_toi_da ?></span>
                                    
                                    <div class="capacity-bar-container">
                                        <?php 
                                            $capacityPercent = $event->so_luong_toi_da > 0 
                                                ? min(100, round(($event->so_luong_dang_ky ?? 0) / $event->so_luong_toi_da * 100)) 
                                                : 0;
                                        ?>
                                        <div class="capacity-bar" style="width: <?= $capacityPercent ?>%"></div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="event-actions">
                                <a href="<?= base_url('nguoidung/sukien/chi-tiet/' . ($event->ma_su_kien ?? 0)) ?>" class="btn btn-details">
                                    <i class="fas fa-info-circle"></i> Chi tiết
                                </a>
                                
                                <?php if($isUpcoming || $isOngoing): ?>
                                    <?php if($registrationStatus == 'registered'): ?>
                                        <a href="<?= base_url('nguoidung/sukien/huy-dang-ky/' . ($event->ma_su_kien ?? 0)) ?>" class="btn btn-cancel" data-event-id="<?= $event->ma_su_kien ?? 0 ?>">
                                            <i class="fas fa-times-circle"></i> Hủy đăng ký
                                        </a>
                                    <?php elseif($registrationStatus == 'attended'): ?>
                                        <div class="btn btn-attended disabled">
                                            <i class="fas fa-calendar-check"></i> Đã tham gia
                                        </div>
                                    <?php else: ?>
                                        <?php if(!isset($event->so_luong_toi_da) || !isset($event->so_luong_dang_ky) || $event->so_luong_dang_ky < $event->so_luong_toi_da): ?>
                                            <a href="<?= base_url('nguoidung/sukien/dang-ky/' . ($event->ma_su_kien ?? 0)) ?>" class="btn btn-register" data-event-id="<?= $event->ma_su_kien ?? 0 ?>">
                                                <i class="fas fa-calendar-plus"></i> Đăng ký
                                            </a>
                                        <?php else: ?>
                                            <div class="btn btn-full disabled">
                                                <i class="fas fa-users-slash"></i> Đã đủ số lượng
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="btn btn-disabled">
                                        <i class="fas fa-calendar-times"></i> Đã kết thúc
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Phân trang -->
            <?php if(!empty($pager)): ?>
                <div class="pagination-container">
                    <?= $pager->links() ?>
                </div>
            <?php endif; ?>
            
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/nguoidung/pages/eventslist.js') ?>"></script>
<?= $this->endSection() ?>

