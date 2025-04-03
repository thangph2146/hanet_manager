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
                    <label for="eventSearch">
                        Tìm kiếm:
                    </label>
                    <input type="text" id="eventSearch" placeholder="Tìm kiếm sự kiện..." 
                           value="<?= $current_filter['search'] ?? '' ?>">
                    <i class="fas fa-search"></i>
                </div>
                
                <div class="sort-box">
                    <label for="eventSort">
                        Sắp xếp:
                    </label>
                    <select id="eventSort">
                        <option value="newest">Mới nhất</option>
                        <option value="oldest">Cũ nhất</option>
                        <option value="name">Theo tên</option>
                    </select>
                </div>
                
                <!-- Thêm filter thời gian -->
                <div class="filter-box date-filter">
                    <label for="startDate">
                        Từ ngày giờ:
                        <?php if(isset($formatted_filter['start_date_formatted']) && !empty($formatted_filter['start_date_formatted'])): ?>
                        <span class="date-display">(<?= $formatted_filter['start_date_formatted'] ?>)</span>
                        <?php endif; ?>
                    </label>
                    <input type="datetime-local" id="startDate" class="date-input" 
                           value="<?= $current_filter['start_date'] ?? '' ?>"
                           placeholder="dd/mm/yyyy h:i:s">
                </div>
                
                <div class="filter-box date-filter">
                    <label for="endDate">
                        Đến ngày giờ:
                        <?php if(isset($formatted_filter['end_date_formatted']) && !empty($formatted_filter['end_date_formatted'])): ?>
                        <span class="date-display">(<?= $formatted_filter['end_date_formatted'] ?>)</span>
                        <?php endif; ?>
                    </label>
                    <input type="datetime-local" id="endDate" class="date-input"
                           value="<?= $current_filter['end_date'] ?? '' ?>"
                           placeholder="dd/mm/yyyy h:i:s">
                </div>
                
                <button id="applyFilters" class="btn-apply-filters" style="height: 50px;">
                    <i class="fas fa-filter"></i> Lọc
                </button>
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
                        <i class="fas fa-search text-white" style="color: white; width: 40px; height: 30px;"></i> Tìm kiếm sự kiện
                    </a>
                </div>
            <?php endif; ?>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/js/nguoidung/pages/eventscheckin.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý nút áp dụng bộ lọc
    document.getElementById('applyFilters').addEventListener('click', function() {
        applyFilters();
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
                    document.getElementById('startDate').value = formattedDate;
                    
                    // Thêm định dạng hiển thị dd/mm/yyyy h:i:s bên cạnh trường input
                    const formattedDisplayDate = formatDateTimeVN(date);
                    const displaySpan = document.createElement('span');
                    displaySpan.className = 'date-display';
                    displaySpan.textContent = `(${formattedDisplayDate})`;
                    
                    const label = document.querySelector('label[for="startDate"]');
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
                    document.getElementById('endDate').value = formattedDate;
                    
                    // Thêm định dạng hiển thị dd/mm/yyyy h:i:s bên cạnh trường input
                    const formattedDisplayDate = formatDateTimeVN(date);
                    const displaySpan = document.createElement('span');
                    displaySpan.className = 'date-display';
                    displaySpan.textContent = `(${formattedDisplayDate})`;
                    
                    const label = document.querySelector('label[for="endDate"]');
                    // Kiểm tra nếu chưa có span hiển thị
                    if (!label.querySelector('.date-display')) {
                        label.appendChild(displaySpan);
                    }
                }
            } catch(e) {
                console.error('Lỗi khi xử lý ngày kết thúc:', e);
            }
        }
        
        // Thiết lập sự kiện change để cập nhật hiển thị ngày giờ
        document.getElementById('startDate').addEventListener('change', function() {
            updateDateDisplay(this, 'startDate');
        });
        
        document.getElementById('endDate').addEventListener('change', function() {
            updateDateDisplay(this, 'endDate');
        });
        
        // Thiết lập sự kiện cho sorting
        const sortSelect = document.getElementById('eventSort');
        if (urlParams.has('sort')) {
            sortSelect.value = urlParams.get('sort');
        }
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
        const search = document.getElementById('eventSearch').value;
        const sort = document.getElementById('eventSort').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        // Cập nhật tham số URL
        updateParam(searchParams, 'search', search);
        updateParam(searchParams, 'sort', sort);
        
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

