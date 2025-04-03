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
                <form action="" method="get" id="filter-form" class="w-100 row g-3 d-flex justify-content-center flex-wrap">
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
                    <a href="<?= base_url('su-kien') ?>" class="btn btn-find-events">
                        <i class="fas fa-search text-white" style="color: white; width: 40px; height: 30px;"></i> Tìm kiếm sự kiện
                    </a>
                </div>
            <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý click vào các sự kiện để xem chi tiết
    const eventCards = document.querySelectorAll('.event-card');
    eventCards.forEach(card => {
        card.addEventListener('click', function() {
            const eventId = this.getAttribute('data-event-id');
            if (eventId) {
                window.location.href = `<?= base_url('nguoidung/su-kien/chi-tiet/') ?>${eventId}`;
            }
        });
    });
    
    // Xử lý form lọc
    document.getElementById('filter-form').addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
    
    // Thiết lập ban đầu cho các trường datetime-local
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
        if (search) {
            searchParams.set('search', search);
        } else {
            searchParams.delete('search');
        }
        
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
});
</script>
<?= $this->endSection() ?>

