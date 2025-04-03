<?= $this->extend('frontend/layouts/nguoidung_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/pages/eventshistoryregister.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="events-register-history-page">
    <!-- Page header -->
    <div class="page-header">
        <h1 class="page-title">Lịch sử đăng ký sự kiện</h1>
        <p class="page-description">Xem danh sách các sự kiện bạn đã đăng ký tham gia</p>
        <div class="page-header-overlay"></div>
    </div>
    
    <!-- Thống kê -->
    <?php
    $totalEvents = count($registeredEvents);
    $attendedEvents = 0;
    $pendingEvents = 0;
    $cancelledEvents = 0;
    
    foreach ($registeredEvents as $event) {
        if ($event->trang_thai_dang_ky == 0) {
            $cancelledEvents++;
        } else if ($event->da_check_in == 1) {
            $attendedEvents++;
        } else {
            $pendingEvents++;
        }
    }
    ?>
    
    <div class="stats-container">
        <div class="stats-item stats-total">
            <div class="stats-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stats-info">
                <div class="stats-value"><?= $totalEvents ?></div>
                <div class="stats-label">Tổng số sự kiện</div>
            </div>
        </div>
        
        <div class="stats-item stats-attended">
            <div class="stats-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stats-info">
                <div class="stats-value"><?= $attendedEvents ?></div>
                <div class="stats-label">Đã tham gia</div>
            </div>
        </div>
        
        <div class="stats-item stats-pending">
            <div class="stats-icon">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <div class="stats-info">
                <div class="stats-value"><?= $pendingEvents ?></div>
                <div class="stats-label">Chưa tham gia</div>
            </div>
        </div>
        
        <div class="stats-item stats-cancelled">
            <div class="stats-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <div class="stats-info">
                <div class="stats-value"><?= $cancelledEvents ?></div>
                <div class="stats-label">Đã hủy</div>
            </div>
        </div>
    </div>
    
    <!-- Container chứa lịch sử đăng ký -->
    <div class="register-history-container">
        <div class="register-history-header">
            <h2>Lịch sử đăng ký sự kiện</h2>
            
            <!-- Bộ lọc và tìm kiếm -->
            <div class="filter-controls">
                <div class="search-box">
                    <input type="text" id="eventSearch" placeholder="Tìm kiếm sự kiện...">
                    <i class="fas fa-search"></i>
                </div>
                
                <div class="filter-options">
                    <div class="sort-box">
                        <select id="eventSort">
                            <option value="newest">Mới nhất</option>
                            <option value="oldest">Cũ nhất</option>
                            <option value="name">Theo tên</option>
                        </select>
                    </div>
                    
                    <div class="filter-box">
                        <select id="statusFilter">
                            <option value="all">Tất cả trạng thái</option>
                            <option value="attended">Đã tham gia</option>
                            <option value="pending">Chưa tham gia</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Danh sách sự kiện đã đăng ký -->
            <?php if (!empty($registeredEvents)) : ?>
                <div class="register-history-list">
                <?php foreach ($registeredEvents as $event) : ?>
                    <?php 
                        $eventStatus = 'pending';
                        if ($event->trang_thai_dang_ky == 0) {
                            $eventStatus = 'cancelled';
                        } else if ($event->da_check_in == 1) {
                            $eventStatus = 'attended';
                        }
                        
                        $eventDate = new DateTime($event->ngay_to_chuc);
                        $now = new DateTime();
                        $isUpcoming = $eventDate > $now;
                    ?>
                    <div class="event-card" 
                         data-event-name="<?= $event->ten_sukien ?>"
                         data-event-date="<?= $event->ngay_to_chuc ?>"
                         data-event-status="<?= $eventStatus ?>">
                        <div class="event-image">
                            <img src="<?= base_url('public/uploads/sukien/' . ($event->hinh_anh ?? 'default-event.jpg')) ?>" alt="<?= $event->ten_sukien ?>">
                            
                            <div class="event-date-badge">
                                <div class="event-day"><?= date('d', strtotime($event->ngay_to_chuc)) ?></div>
                                <div class="event-month">Th<?= date('m', strtotime($event->ngay_to_chuc)) ?></div>
                                <div class="event-year"><?= date('Y', strtotime($event->ngay_to_chuc)) ?></div>
                            </div>
                            
                            <?php if ($eventStatus == 'attended') : ?>
                                <div class="event-status-badge attended">
                                    <i class="fas fa-check-circle"></i> Đã tham gia
                                </div>
                            <?php elseif ($eventStatus == 'cancelled') : ?>
                                <div class="event-status-badge cancelled">
                                    <i class="fas fa-times-circle"></i> Đã hủy
                                </div>
                            <?php else : ?>
                                <div class="event-status-badge pending">
                                    <i class="fas fa-clock"></i> Chưa tham gia
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="event-content">
                            <h3 class="event-title"><?= $event->ten_sukien ?></h3>
                            
                            <div class="event-info">
                                <div class="event-date">
                                    <i class="fas fa-calendar-alt"></i>
                                    <?= date('d/m/Y', strtotime($event->ngay_to_chuc)) ?>
                                </div>
                                <div class="event-time">
                                    <i class="fas fa-clock"></i>
                                    <?= date('H:i', strtotime($event->gio_bat_dau)) ?> - <?= date('H:i', strtotime($event->gio_ket_thuc)) ?>
                                </div>
                                <div class="event-venue">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= $event->dia_diem ?>
                                </div>
                                <div class="event-organizer">
                                    <i class="fas fa-user-tie"></i>
                                    <?= $event->to_chuc ?>
                                </div>
                            </div>
                            
                            <div class="registration-info">
                                <div class="registration-date">
                                    <i class="fas fa-calendar-plus"></i>
                                    Đăng ký: <?= date('d/m/Y H:i', strtotime($event->ngay_dang_ky)) ?>
                                </div>
                                
                                <?php if ($eventStatus == 'attended') : ?>
                                    <div class="checkin-date">
                                        <i class="fas fa-sign-in-alt"></i>
                                        Check-in: <?= date('d/m/Y H:i', strtotime($event->thoi_gian_check_in)) ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($eventStatus == 'cancelled') : ?>
                                    <div class="cancel-date">
                                        <i class="fas fa-ban"></i>
                                        Hủy: <?= date('d/m/Y H:i', strtotime($event->ngay_cap_nhat)) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="event-actions">
                                <?php if (!empty($event->chung_chi) && $event->da_check_in == 1) : ?>
                                    <a href="<?= base_url('public/uploads/chungchi/' . $event->chung_chi) ?>" class="btn btn-download-certificate" target="_blank">
                                        <i class="fas fa-award"></i> Tải chứng chỉ
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($event->da_check_in == 0 && $event->trang_thai_dang_ky == 1 && $isUpcoming) : ?>
                                    <a href="<?= base_url('nguoidung/sukien/huy-dang-ky/' . $event->ma_sukien) ?>" class="btn btn-cancel" onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký sự kiện này?');">
                                        <i class="fas fa-times-circle"></i> Hủy đăng ký
                                    </a>
                                <?php endif; ?>
                                
                                <a href="<?= base_url('nguoidung/sukien/chi-tiet/' . $event->ma_sukien) ?>" class="btn btn-view-details">
                                    <i class="fas fa-eye"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php else : ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3>Bạn chưa đăng ký sự kiện nào</h3>
                    <p>Bạn chưa đăng ký sự kiện nào. Hãy tìm kiếm và đăng ký tham gia các sự kiện sắp tới.</p>
                    <a href="<?= base_url('nguoidung/sukien') ?>" class="btn btn-find-events">
                        <i class="fas fa-search"></i> Tìm kiếm sự kiện
                    </a>
                </div>
            <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/nguoidung/pages/eventshistoryregister.js') ?>"></script>
<?= $this->endSection() ?>

