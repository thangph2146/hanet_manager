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
                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category->phan_loai ?>" <?= (isset($current_filter['category']) && $current_filter['category'] == $category->phan_loai) ? 'selected' : '' ?>><?= $category->phan_loai ?></option>
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
            
            <button id="applyFilters" class="btn-apply-filters">
                <i class="fas fa-filter"></i> Lọc
            </button>
        </div>
    </div>
    
    <!-- Danh sách sự kiện -->
    <div class="events-container">
        <?php if(!empty($events)): ?>
            <div class="events-grid">
                <?php foreach($events as $event): ?>
                    <?php 
                        $eventDate = new DateTime($event->thoi_gian_bat_dau);
                        $now = new DateTime();
                        $isUpcoming = $eventDate > $now;
                        
                        $remaining = '';
                        if($isUpcoming) {
                            $interval = $now->diff($eventDate);
                            if($interval->days > 0) {
                                $remaining = $interval->days . ' ngày nữa';
                            } else if($interval->h > 0) {
                                $remaining = $interval->h . ' giờ nữa';
                            } else {
                                $remaining = $interval->i . ' phút nữa';
                            }
                        }
                        
                        // Kiểm tra người dùng đã đăng ký chưa
                        $isRegistered = isset($userEvents) && in_array($event->ma_su_kien, $userEvents);
                    ?>
                    <div class="event-card" 
                         data-category="<?= $event->phan_loai ?>"
                         data-date="<?= $event->thoi_gian_bat_dau ?>"
                         data-views="<?= $event->luot_xem ?>"
                         data-status="<?= $isUpcoming ? 'upcoming' : 'ended' ?>">
                        <div class="event-image">
                            <img src="<?= !empty($event->hinh_anh) ? base_url('public/uploads/sukien/' . $event->hinh_anh) : base_url('public/assets/images/events/default.jpg') ?>" alt="<?= $event->ten_su_kien ?>">
                            
                            <?php if($isUpcoming && !empty($remaining)): ?>
                                <div class="event-countdown">
                                    <i class="far fa-clock"></i> <?= $remaining ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="event-date-badge">
                                <div class="event-day"><?= $eventDate->format('d') ?></div>
                                <div class="event-month">Th<?= $eventDate->format('m') ?></div>
                                <div class="event-year"><?= $eventDate->format('Y') ?></div>
                            </div>
                            
                            <?php if($isRegistered): ?>
                                <div class="event-registered-badge">
                                    <i class="fas fa-check-circle"></i> Đã đăng ký
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="event-content">
                            <div class="event-meta">
                                <span class="event-category"><?= $event->phan_loai ?></span>
                                <span class="event-views"><i class="far fa-eye"></i> <?= $event->luot_xem ?></span>
                            </div>
                            
                            <h3 class="event-title"><?= $event->ten_su_kien ?></h3>
                            
                            <div class="event-details">
                                <div class="event-time">
                                    <i class="far fa-clock"></i>
                                    <?= date('H:i', strtotime($event->gio_bat_dau)) ?> - <?= date('H:i', strtotime($event->gio_ket_thuc)) ?>
                                </div>
                                
                                <div class="event-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= $event->dia_diem ?>
                                </div>
                                
                                <div class="event-organizer">
                                    <i class="fas fa-users"></i>
                                    <?= $event->don_vi_to_chuc ?>
                                </div>
                            </div>
                            
                            <div class="event-description">
                                <?= character_limiter(strip_tags($event->mo_ta), 150) ?>
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
                            </div>
                            
                            <div class="event-actions">
                                <a href="<?= base_url('nguoidung/sukien/chi-tiet/' . $event->ma_su_kien) ?>" class="btn btn-details">
                                    <i class="fas fa-info-circle"></i> Chi tiết
                                </a>
                                
                                <?php if($isUpcoming): ?>
                                    <?php if($isRegistered): ?>
                                        <a href="<?= base_url('nguoidung/sukien/huy-dang-ky/' . $event->ma_su_kien) ?>" class="btn btn-cancel" onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký sự kiện này?');">
                                            <i class="fas fa-times-circle"></i> Hủy đăng ký
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('nguoidung/sukien/dang-ky/' . $event->ma_su_kien) ?>" class="btn btn-register">
                                            <i class="fas fa-calendar-plus"></i> Đăng ký
                                        </a>
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
            
        <?php else: ?>
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
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/nguoidung/pages/eventslist.js') ?>"></script>
<?= $this->endSection() ?>

