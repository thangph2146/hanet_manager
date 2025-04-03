<?= $this->extend('frontend/layouts/nguoidung_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/pages/eventscheckin.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="events-checkin-page">
    <!-- Page header -->
    <div class="page-header">
        <h1 class="page-title">Sự kiện đã tham gia</h1>
        <p class="page-description">Danh sách các sự kiện mà bạn đã tham gia và check-in</p>
        <div class="page-header-overlay"></div>
    </div>
    
    <!-- Thống kê -->
    <div class="stats-container">
        <div class="stats-card">
            <div class="stats-item">
                <div class="stats-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-value"><?= count($attendedEvents) ?></div>
                    <div class="stats-label">Đã tham gia</div>
                </div>
            </div>
        </div>
        <div class="stats-card">
            <div class="stats-item">
                <div class="stats-icon">
                    <i class="fas fa-award"></i>
                </div>
                <div class="stats-info">
                    <div class="stats-value"><?= isset($certificateCount) ? $certificateCount : 0 ?></div>
                    <div class="stats-label">Chứng chỉ đã nhận</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Container chứa danh sách sự kiện đã tham gia -->
    <div class="attended-events-container">
        <div class="attended-events-header">
            <h2>Sự kiện đã tham gia</h2>
            
            <!-- Bộ lọc và tìm kiếm -->
            <div class="filter-controls">
                <div class="search-box">
                    <input type="text" id="eventSearch" placeholder="Tìm kiếm sự kiện...">
                    <i class="fas fa-search"></i>
                </div>
                
                <div class="sort-box">
                    <select id="eventSort">
                        <option value="newest">Mới nhất</option>
                        <option value="oldest">Cũ nhất</option>
                        <option value="name">Theo tên</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Danh sách sự kiện -->
        <?php if (!empty($attendedEvents)) : ?>
            <div class="attended-events-list">
                <?php foreach ($attendedEvents as $event) : ?>
                    <div class="event-card" 
                         data-event-name="<?= $event->ten_sukien ?>"
                         data-event-date="<?= $event->ngay_to_chuc ?>">
                        <div class="event-image">
                            <img src="<?= base_url('public/uploads/sukien/' . ($event->hinh_anh ?? 'default-event.jpg')) ?>" alt="<?= $event->ten_sukien ?>">
                            <div class="event-date-badge">
                                <div class="event-day"><?= date('d', strtotime($event->ngay_to_chuc)) ?></div>
                                <div class="event-month">Th<?= date('m', strtotime($event->ngay_to_chuc)) ?></div>
                                <div class="event-year"><?= date('Y', strtotime($event->ngay_to_chuc)) ?></div>
                            </div>
                            <?php if($event->da_check_out == 1): ?>
                                <div class="event-status-badge completed">
                                    <i class="fas fa-check-circle"></i> Hoàn thành
                                </div>
                            <?php else: ?>
                                <div class="event-status-badge partial">
                                    <i class="fas fa-hourglass-half"></i> Đã check-in
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
                            
                            <div class="event-meta">
                                <div class="attendance">
                                    <span class="checkin-time">
                                        <i class="fas fa-sign-in-alt"></i> Check-in: <?= date('H:i', strtotime($event->thoi_gian_check_in)) ?>
                                    </span>
                                    <?php if($event->da_check_out == 1): ?>
                                        <span class="checkout-time">
                                            <i class="fas fa-sign-out-alt"></i> Check-out: <?= date('H:i', strtotime($event->thoi_gian_check_out)) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="event-actions">
                                <?php if (!empty($event->chung_chi) && $event->da_check_in == 1) : ?>
                                    <a href="<?= base_url('public/uploads/chungchi/' . $event->chung_chi) ?>" class="btn btn-download-certificate" target="_blank">
                                        <i class="fas fa-award"></i> Tải chứng chỉ
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($event->da_check_out == 0 && $event->da_check_in == 1) : ?>
                                    <a href="<?= base_url('nguoidung/sukien/checkout/' . $event->ma_sukien) ?>" class="btn btn-checkout">
                                        <i class="fas fa-sign-out-alt"></i> Check-out
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
                    <h3>Bạn chưa tham gia sự kiện nào</h3>
                    <p>Bạn chưa tham gia sự kiện nào. Hãy tìm kiếm và đăng ký tham gia các sự kiện sắp tới.</p>
                    <a href="<?= base_url('nguoidung/sukien') ?>" class="btn btn-find-events">
                        <i class="fas fa-search"></i> Tìm kiếm sự kiện
                    </a>
                </div>
            <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/nguoidung/pages/eventscheckin.js') ?>"></script>
<?= $this->endSection() ?>

