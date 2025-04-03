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
    <div class="filter-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Bộ lọc</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="get" id="filter-form" class="row g-3">
                                <div class="col-md-3">
                                    <label for="search" class="form-label">Tìm kiếm:</label>
                                    <input type="text" class="form-control" id="search" name="search" style="height: 50px;"
                                        value="<?= esc($current_filter['search'] ?? '') ?>" placeholder="Tìm kiếm sự kiện...">
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="start_date" class="form-label">Từ ngày:
                                        <?php if(isset($formatted_filter['start_date_formatted']) && !empty($formatted_filter['start_date_formatted'])): ?>
                                        <span class="date-display">(<?= $formatted_filter['start_date_formatted'] ?>)</span>
                                        <?php endif; ?>
                                    </label>
                                    <input type="datetime-local" class="form-control date-input" id="start_date" name="start_date" 
                                           value="<?= $current_filter['start_date'] ?? '' ?>">
                                </div>
                                
                                <div class="col-md-3">
                                    <label for="end_date" class="form-label">Đến ngày:
                                        <?php if(isset($formatted_filter['end_date_formatted']) && !empty($formatted_filter['end_date_formatted'])): ?>
                                        <span class="date-display">(<?= $formatted_filter['end_date_formatted'] ?>)</span>
                                        <?php endif; ?>
                                    </label>
                                    <input type="datetime-local" class="form-control date-input" id="end_date" name="end_date"
                                           value="<?= $current_filter['end_date'] ?? '' ?>">
                                </div>
                                
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary btn-apply-filters" style="height: 50px;">
                                        <i class="fas fa-filter"></i> Áp dụng bộ lọc
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút áp dụng bộ lọc
    document.getElementById('filter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
    
    // Xử lý nút reset filter
    document.getElementById('resetFilters')?.addEventListener('click', function() {
        resetFilters();
    });
    
    // Thiết lập ban đầu cho các trường datetime-local nếu có giá trị trong URL
    initDateTimeFields();
    
    // Hàm định dạng ngày giờ theo dd/mm/yyyy h:i:s
    function formatDateTimeVN(date) {
        if (!date || isNaN(date.getTime())) return '';
        
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        
        return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
    }
    
    // Hàm khởi tạo các trường thời gian
    function initDateTimeFields() {
        // Lấy tham số từ URL hiện tại
        const urlParams = new URLSearchParams(window.location.search);
        
        // Định dạng lại giá trị start_date nếu có
        if(urlParams.has('start_date')) {
            const startDateValue = urlParams.get('start_date');
            try {
                const date = new Date(startDateValue);
                if(!isNaN(date.getTime())) {
                    // Định dạng để phù hợp với trường datetime-local (YYYY-MM-DDThh:mm)
                    const formattedDate = date.toISOString().slice(0, 16);
                    document.getElementById('start_date').value = formattedDate;
                    
                    // Thêm định dạng hiển thị dd/mm/yyyy h:i:s bên cạnh trường input
                    const formattedDisplayDate = formatDateTimeVN(date);
                    const displaySpan = document.createElement('span');
                    displaySpan.className = 'date-display';
                    displaySpan.textContent = `(${formattedDisplayDate})`;
                    
                    const label = document.querySelector('label[for="start_date"]');
                    // Kiểm tra nếu chưa có span hiển thị
                    if (!label.querySelector('.date-display')) {
                        label.appendChild(displaySpan);
                    }
                }
            } catch(e) {
                console.error('Lỗi khi xử lý ngày bắt đầu:', e);
            }
        }
        
        // Định dạng lại giá trị end_date nếu có
        if(urlParams.has('end_date')) {
            const endDateValue = urlParams.get('end_date');
            try {
                const date = new Date(endDateValue);
                if(!isNaN(date.getTime())) {
                    // Định dạng để phù hợp với trường datetime-local (YYYY-MM-DDThh:mm)
                    const formattedDate = date.toISOString().slice(0, 16);
                    document.getElementById('end_date').value = formattedDate;
                    
                    // Thêm định dạng hiển thị dd/mm/yyyy h:i:s bên cạnh trường input
                    const formattedDisplayDate = formatDateTimeVN(date);
                    const displaySpan = document.createElement('span');
                    displaySpan.className = 'date-display';
                    displaySpan.textContent = `(${formattedDisplayDate})`;
                    
                    const label = document.querySelector('label[for="end_date"]');
                    // Kiểm tra nếu chưa có span hiển thị
                    if (!label.querySelector('.date-display')) {
                        label.appendChild(displaySpan);
                    }
                }
            } catch(e) {
                console.error('Lỗi khi xử lý ngày kết thúc:', e);
            }
        }
        
        // Thêm sự kiện change cho các trường ngày để cập nhật hiển thị ngay lập tức
        document.getElementById('start_date').addEventListener('change', function() {
            updateDateDisplay(this, 'start_date');
        });
        
        document.getElementById('end_date').addEventListener('change', function() {
            updateDateDisplay(this, 'end_date');
        });
    }
    
    // Hàm cập nhật hiển thị ngày giờ khi người dùng thay đổi
    function updateDateDisplay(inputElement, fieldId) {
        try {
            const date = new Date(inputElement.value);
            if(!isNaN(date.getTime())) {
                const formattedDisplayDate = formatDateTimeVN(date);
                
                const label = document.querySelector(`label[for="${fieldId}"]`);
                let displaySpan = label.querySelector('.date-display');
                
                if (!displaySpan) {
                    displaySpan = document.createElement('span');
                    displaySpan.className = 'date-display';
                    label.appendChild(displaySpan);
                }
                
                displaySpan.textContent = `(${formattedDisplayDate})`;
            }
        } catch(e) {
            console.error('Lỗi khi cập nhật hiển thị ngày:', e);
        }
    }
    
    // Hàm áp dụng các bộ lọc
    function applyFilters() {
        let url = new URL(window.location.href);
        let searchParams = new URLSearchParams(url.search);
        
        // Lấy giá trị từ các trường
        const search = document.getElementById('search').value;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        // Cập nhật tham số URL
        updateParam(searchParams, 'search', search);
        
        // Xử lý và cập nhật tham số ngày giờ
        if(startDate) {
            searchParams.set('start_date', new Date(startDate).toISOString());
        } else {
            searchParams.delete('start_date');
        }
        
        if(endDate) {
            searchParams.set('end_date', new Date(endDate).toISOString());
        } else {
            searchParams.delete('end_date');
        }
        
        // Chuyển hướng đến URL mới
        url.search = searchParams.toString();
        window.location.href = url.toString();
    }
    
    // Hàm reset tất cả bộ lọc
    function resetFilters() {
        window.location.href = window.location.pathname;
    }
    
    // Hàm cập nhật tham số URL
    function updateParam(params, key, value) {
        if (value && value !== 'all') {
            params.set(key, value);
        } else {
            params.delete(key);
        }
    }
});
</script>
<?= $this->endSection() ?>