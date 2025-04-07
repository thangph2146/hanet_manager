<?= $this->extend('frontend/layouts/nguoidung_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/pages/eventshistoryregister.css') ?>">
<style>
/* CSS cho responsive layout dạng danh sách */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

.statistics-card {
    transition: all 0.3s ease;
    margin-bottom: 20px;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.event-card-container {
    transition: all 0.3s ease;
    margin-bottom: 20px;
}

.event-card-container .card {
    background-color: #fff;
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.event-card-container .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
}

.page-header {
    padding: 20px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.page-header h2 {
    font-weight: 700;
    margin-bottom: 0;
}

.event-date-badge {
    min-width: 60px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    background-color: #f8f9fa;
    border-radius: 8px;
    padding: 8px;
}

.event-timeline {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    height: 100%;
}

.event-card-container .btn {
    border-radius: 6px;
    padding: 0.4rem 0.8rem;
    font-weight: 500;
    transition: all 0.2s;
}

.event-card-container .btn:hover {
    transform: translateY(-2px);
}

/* Breakpoints for responsive design */
/* Large desktop */
@media (min-width: 1200px) {
    .container {
        max-width: 1140px;
    }
    
    .event-card-container .card-body {
        padding: 1.5rem;
    }
}

/* Desktop */
@media (min-width: 992px) and (max-width: 1199px) {
    .container {
        max-width: 960px;
    }
    
    .event-card-container .card-body {
        padding: 1.25rem;
    }
}

/* Tablet */
@media (min-width: 768px) and (max-width: 991px) {
    .container {
        max-width: 720px;
    }
    
    .page-header h2 {
        font-size: 1.75rem;
    }
    
    .event-card-container .card-body {
        padding: 1.1rem;
    }
}

/* Mobile landscape */
@media (min-width: 576px) and (max-width: 767.98px) {
    .container {
        max-width: 540px;
    }
    
    .page-header h2 {
        font-size: 1.5rem;
    }
    
    .event-card-container .card-body {
        padding: 1rem;
    }
    
    .event-card-container h5.card-title {
        font-size: 1rem;
    }
    
    .event-timeline {
        margin-top: 10px;
    }
}

/* Mobile portrait */
@media (max-width: 575.98px) {
    .page-header h2 {
        font-size: 1.25rem;
    }
    
    .event-card-container .card-body {
        padding: 0.8rem;
    }
    
    .event-card-container h5.card-title {
        font-size: 0.9rem;
    }
    
    .event-card-container .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .event-timeline {
        padding: 6px;
        margin-top: 8px;
    }
    
    .statistics-card .card-title {
        font-size: 0.9rem;
    }
    
    .statistics-card .h1 {
        font-size: 1.5rem;
    }
}

/* Thêm hiệu ứng khi hover vào thẻ */
.hover-shadow {
    transition: all 0.3s ease;
}

.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

/* CSS cho filter buttons */
.filter-container {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 20px;
}

.filter-btn {
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 0.85rem;
    transition: all 0.2s;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    color: #495057;
}

.filter-btn:hover {
    background-color: #e9ecef;
}

.filter-btn.active {
    background-color: #0d6efd;
    color: #fff;
    border-color: #0d6efd;
}

/* CSS cho empty state */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    margin: 20px 0;
}

.empty-icon {
    font-size: 4rem;
    color: #adb5bd;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #495057;
}

.empty-state p {
    color: #6c757d;
    margin-bottom: 20px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

@media (max-width: 767.98px) {
    .empty-state {
        padding: 30px 15px;
    }
    
    .empty-icon {
        font-size: 3rem;
    }
    
    .empty-state h3 {
        font-size: 1.25rem;
    }
    
    .empty-state p {
        font-size: 0.9rem;
    }
}

/* CSS cho các thẻ thống kê sự kiện */
.donut-chart-container {
    position: relative;
    width: 180px;
    height: 180px;
    margin: 0 auto;
}

.donut-chart-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.status-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* CSS cho trang trống */
.empty-state {
    padding: 40px 20px;
    text-align: center;
}

.empty-filter-results {
    background-color: #f8f9fa;
    border-radius: 10px;
    margin-top: 20px;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="page-header bg-primary bg-gradient text-white rounded-3 mb-4 p-4">
        <h2 class="page-title fw-bold"><i class="far fa-calendar-alt me-2"></i>Lịch sử đăng ký sự kiện</h2>
        <p class="page-description mb-0">Theo dõi tất cả các sự kiện bạn đã đăng ký tham gia</p>
    </div>

    <?php
    // Đếm tổng số lượng sự kiện và phân loại theo trạng thái
    $totalEvents = count($registeredEvents ?? []);
    $attendedEvents = 0;
    $pendingEvents = 0;
    $cancelledEvents = 0;

    foreach ($registeredEvents ?? [] as $event) {
        if (isset($event->trang_thai_dang_ky)) {
            if ($event->trang_thai_dang_ky == 3) {
                $attendedEvents++;
            } else if ($event->trang_thai_dang_ky == 2) {
                $cancelledEvents++;
            } else {
                $pendingEvents++;
            }
        } else {
            // Mặc định là đang chờ nếu không có trạng thái
            $pendingEvents++;
        }
    }

    // Tính phần trăm
    $attendedPercent = $totalEvents > 0 ? round(($attendedEvents / $totalEvents) * 100) : 0;
    $pendingPercent = $totalEvents > 0 ? round(($pendingEvents / $totalEvents) * 100) : 0;
    $cancelledPercent = $totalEvents > 0 ? round(($cancelledEvents / $totalEvents) * 100) : 0;
    ?>

    <div class="row mb-4">
        <div class="col-lg-12">
            <!-- Thẻ thống kê -->
            <div class="card statistics-card shadow-sm border-0">
                <div class="card-header bg-white p-3 border-bottom">
                    <h5 class="card-title mb-0"><i class="fas fa-chart-pie text-primary me-2"></i>Thống kê sự kiện</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <!-- Biểu đồ tròn thống kê -->
                            <div class="donut-chart-container">
                                <canvas id="eventStatusChart" width="180" height="180"></canvas>
                                <div class="donut-chart-text">
                                    <h3 class="mb-0 text-center"><?= $totalEvents ?></h3>
                                    <p class="text-center text-muted mb-0">Tổng số</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Thống kê tổng -->
                                <div class="col-12 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0 fw-bold">Tổng sự kiện đã đăng ký</h6>
                                    </div>
                                    <div class="progress" style="height: 8px;">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                
                                <!-- Đã tham gia -->
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="status-icon bg-success rounded-circle mb-2">
                                                <i class="fas fa-check text-white"></i>
                                            </div>
                                            <h2 class="mb-0 h3"><?= $attendedEvents ?></h2>
                                            <p class="mb-1 text-center">Đã tham gia</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Đang chờ -->
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="status-icon bg-warning rounded-circle mb-2">
                                                <i class="fas fa-clock text-white"></i>
                                            </div>
                                            <h2 class="mb-0 h3"><?= $pendingEvents ?></h2>
                                            <p class="mb-1 text-center">Đang chờ</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Đã hủy -->
                                <div class="col-md-4 mb-3">
                                    <div class="p-3 bg-light rounded-3 h-100">
                                        <div class="d-flex flex-column align-items-center">
                                            <div class="status-icon bg-danger rounded-circle mb-2">
                                                <i class="fas fa-times text-white"></i>
                                            </div>
                                            <h2 class="mb-0 h3"><?= $cancelledEvents ?></h2>
                                            <p class="mb-1 text-center">Đã hủy</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-3 border-bottom">
                    <div class="w-100 d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-list text-primary me-2"></i>Danh sách sự kiện
                        </h5>
                        <div class="input-group" style="max-width: 300px;">
                            <input type="text" class="form-control" placeholder="Tìm kiếm sự kiện..." id="searchEvent">
                            <button class="btn btn-outline-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (empty($registeredEvents)): ?>
                    <!-- Hiển thị trạng thái trống -->
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <h3>Chưa có sự kiện nào được đăng ký</h3>
                        <p>Bạn chưa đăng ký tham gia sự kiện nào. Hãy khám phá các sự kiện hiện có để đăng ký tham gia!</p>
                        <a href="<?= site_url('sukien') ?>" class="btn btn-primary">
                            <i class="fas fa-calendar-plus me-2"></i>Khám phá sự kiện
                        </a>
                    </div>
                    <?php else: ?>
                    <!-- Danh sách thẻ sự kiện -->
                    <div class="event-cards">
                        <?php 
                        // Kiểm tra nếu $registeredEvents là một mảng và không rỗng
                        if (is_array($registeredEvents) && count($registeredEvents) > 0):
                        foreach ($registeredEvents as $event): 
                            // Xác định trạng thái đăng ký
                            $status = isset($event->trang_thai_dang_ky) ? $event->trang_thai_dang_ky : 1;
                            
                            // Định dạng màu sắc và văn bản trạng thái
                            $statusClass = 'warning';
                            $statusText = 'Đang chờ';
                            $statusIcon = 'clock';
                            
                            if ($status == 2) {
                                $statusClass = 'danger';
                                $statusText = 'Đã hủy';
                                $statusIcon = 'times';
                            } else if ($status == 3) {
                                $statusClass = 'success';
                                $statusText = 'Đã tham gia';
                                $statusIcon = 'check';
                            }
                            
                            // Định dạng ngày giờ sự kiện
                            $eventDate = isset($event->ngay_bat_dau) ? date('Y-m-d', strtotime($event->ngay_bat_dau)) : '';
                            $eventTime = isset($event->ngay_bat_dau) ? date('H:i', strtotime($event->ngay_bat_dau)) : '';
                            $eventDay = isset($event->ngay_bat_dau) ? date('d', strtotime($event->ngay_bat_dau)) : '';
                            $eventMonth = isset($event->ngay_bat_dau) ? date('m', strtotime($event->ngay_bat_dau)) : '';
                            $eventYear = isset($event->ngay_bat_dau) ? date('Y', strtotime($event->ngay_bat_dau)) : '';
                            
                            // Định dạng ngày đăng ký
                            $registrationDate = isset($event->ngay_dang_ky) ? date('d/m/Y H:i', strtotime($event->ngay_dang_ky)) : '';
                            
                            // Định dạng check-in, check-out
                            $checkinDate = isset($event->ngay_checkin) ? date('d/m/Y H:i', strtotime($event->ngay_checkin)) : '';
                            $checkoutDate = isset($event->ngay_checkout) ? date('d/m/Y H:i', strtotime($event->ngay_checkout)) : '';
                        ?>
                        <div class="event-card-container mb-3" 
                             data-event-status="<?= $status ?>"
                             data-event-date="<?= $eventDate ?>">
                            <div class="card hover-shadow border-start border-<?= $statusClass ?> border-4">
                                <div class="row g-0">
                                    <!-- Hình ảnh sự kiện -->
                                    <div class="col-md-3 col-lg-2">
                                        <div class="position-relative h-100">
                                            <img src="<?= base_url('uploads/sukien/' . ($event->hinh_anh ?? 'default-event.jpg')) ?>" 
                                                 class="img-fluid rounded-start h-100" 
                                                 alt="<?= esc($event->ten_sukien ?? 'Sự kiện') ?>"
                                                 style="object-fit: cover; width: 100%; min-height: 120px;">
                                            
                                            <!-- Badge trạng thái -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-<?= $statusClass ?> p-2">
                                                    <i class="fas fa-<?= $statusIcon ?> me-1"></i><?= $statusText ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Thông tin sự kiện -->
                                    <div class="col-md-9 col-lg-10">
                                        <div class="card-body py-3">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <!-- Tiêu đề sự kiện -->
                                                <h5 class="card-title fw-bold mb-1">
                                                    <?= esc($event->ten_sukien ?? 'Sự kiện không xác định') ?>
                                                </h5>
                                                
                                                <!-- Ngày diễn ra -->
                                                <div class="event-date-badge text-center bg-light rounded p-2 ms-3 d-none d-md-block">
                                                    <div class="event-day fw-bold"><?= $eventDay ?></div>
                                                    <div class="event-year small text-muted"><?= $eventYear ?></div>
                                                </div>
                                            </div>
                                            
                                            <!-- Thông tin cơ bản -->
                                            <div class="row mb-3">
                                                <div class="col-md-8">
                                                    <?php if (isset($event->dia_diem)): ?>
                                                    <div class="d-flex align-items-center text-muted mb-1">
                                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                                        <span><?= esc($event->dia_diem) ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <div class="d-flex align-items-center text-muted mb-1">
                                                        <i class="far fa-calendar-alt text-primary me-2"></i>
                                                        <span>
                                                            <?= isset($event->ngay_bat_dau) ? date('d/m/Y', strtotime($event->ngay_bat_dau)) : 'Chưa xác định' ?>
                                                            <?php if (!empty($eventTime)): ?>
                                                                <i class="fas fa-clock ms-2 me-1"></i><?= $eventTime ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </div>
                                                    
                                                    <?php if (isset($event->to_chuc) || isset($event->ban_to_chuc)): ?>
                                                    <div class="d-flex align-items-center text-muted mb-1">
                                                        <i class="fas fa-user-tie text-info me-2"></i>
                                                        <span><?= esc($event->to_chuc ?? $event->ban_to_chuc ?? '') ?></span>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="event-timeline small">
                                                        <div class="mb-1 d-flex align-items-center">
                                                            <p style="font-size: 15px;"><?= $registrationDate ?: 'N/A' ?></p>
                                                        </div>
                                                        
                                                        <?php if ($status == 3 && !empty($checkinDate)): ?>
                                                        <div class="mb-1 d-flex align-items-center">
                                                            <span class="badge bg-success me-2">Check-in</span>
                                                            <span class="text-muted"><?= $checkinDate ?></span>
                                                        </div>
                                                        <?php endif; ?>
                                                        
                                                        <?php if ($status == 3 && !empty($checkoutDate)): ?>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-info me-2">Check-out</span>
                                                            <span class="text-muted"><?= $checkoutDate ?></span>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Card footer với các nút tương tác -->
                                            <div class="d-flex justify-content-end mt-2">
                                                <?php if (isset($event->chung_chi) && !empty($event->chung_chi) && isset($event->da_check_in) && $event->da_check_in == 1): ?>
                                                <a href="<?= base_url('uploads/chungchi/' . $event->chung_chi) ?>" 
                                                   class="btn btn-outline-success me-2" 
                                                   target="_blank"
                                                   data-toggle="tooltip" 
                                                   title="Xem chứng chỉ tham gia">
                                                    <i class="fas fa-award me-1"></i>Chứng chỉ
                                                </a>
                                                <?php endif; ?>
                                                
                                                <?php if ($status == 1): ?>
                                                <a href="<?= site_url('nguoidung/huy-dang-ky-su-kien/' . ($event->id_dangky ?? ($event->dang_ky_id ?? 0))) ?>" 
                                                   class="btn btn-outline-danger me-2" 
                                                   data-toggle="tooltip" 
                                                   title="Hủy đăng ký tham gia"
                                                   onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký sự kiện này?');">
                                                    <i class="fas fa-times me-1"></i>Hủy đăng ký
                                                </a>
                                                <?php endif; ?>
                                                
                                                <a href="<?= site_url('sukien/detail/' . ($event->id_sukien ?? ($event->su_kien_id ?? 0)) . '/' . ($event->slug ?? '')) ?>" 
                                                   class="btn btn-primary" 
                                                   data-toggle="tooltip" 
                                                   title="Xem chi tiết sự kiện">
                                                    <i class="fas fa-info-circle me-1"></i>Chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; 
                        else: ?>
                        <!-- Không có dữ liệu sự kiện -->
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Không tìm thấy sự kiện nào. Có thể do lỗi dữ liệu hoặc sự kiện đã bị xóa.
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra và hiển thị danh sách sự kiện
    function validateEventDisplay() {
        const eventCards = document.querySelectorAll('.event-card-container');
        const emptyStateElement = document.querySelector('.empty-state-filtered');
        const statsNumberElement = document.querySelector('.stats-number');
        
        // Nếu có số liệu thống kê nhưng không thấy danh sách
        if (statsNumberElement && parseInt(statsNumberElement.textContent) > 0 && eventCards.length === 0) {
            // Tự động reload trang để lấy lại dữ liệu
            if (!window.location.search.includes('reload=true')) {
                window.location.href = window.location.pathname + 
                    (window.location.search ? window.location.search + '&reload=true' : '?reload=true');
            }
        }
        
        // Nếu có danh sách sự kiện
        if (eventCards.length > 0) {
            // Ẩn thông báo trống nếu có
            if (emptyStateElement) {
                emptyStateElement.style.display = 'none';
            }
            
            // Đếm thẻ sự kiện hiển thị
            let visibleCount = 0;
            eventCards.forEach(card => {
                if (card.style.display !== 'none') {
                    visibleCount++;
                }
            });
            
            // Hiển thị thông báo trống nếu không có sự kiện nào hiển thị sau khi lọc
            if (visibleCount === 0 && emptyStateElement) {
                emptyStateElement.style.display = 'block';
            }
        }
    }
    
    // Chạy kiểm tra ngay khi trang tải xong
    validateEventDisplay();
    
    // Khởi tạo tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function(tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Filter events by status
    const filterButtons = document.querySelectorAll('.event-filter button');
    const eventCards = document.querySelectorAll('.event-card-container');
    const emptyState = document.querySelector('.empty-state-filtered');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            const filterValue = this.getAttribute('data-filter');
            let visibleCount = 0;
            
            eventCards.forEach(card => {
                if (filterValue === 'all') {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    const cardStatus = card.getAttribute('data-event-status');
                    if (cardStatus === filterValue) {
                        card.style.display = 'block';
                        visibleCount++;
                    } else {
                        card.style.display = 'none';
                    }
                }
            });
            
            // Show empty state if no cards are visible
            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }
        });
    });
    
    // Sort events
    const sortButtons = document.querySelectorAll('.event-sort button');
    const eventCardsList = document.querySelector('.event-cards');
    
    if (eventCards.length > 0 && eventCardsList) {
        sortButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                sortButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
                
                const sortValue = this.getAttribute('data-sort');
                const cards = Array.from(eventCards);
                
                cards.sort((a, b) => {
                    const dateA = new Date(a.getAttribute('data-event-date') || '2000-01-01');
                    const dateB = new Date(b.getAttribute('data-event-date') || '2000-01-01');
                    
                    if (sortValue === 'newest') {
                        return dateB - dateA;
                    } else {
                        return dateA - dateB;
                    }
                });
                
                // Remove all cards
                cards.forEach(card => card.remove());
                
                // Append sorted cards
                cards.forEach(card => eventCardsList.appendChild(card));
            });
        });
    }
    
    // Xử lý form lọc
    const filterForm = document.getElementById('filter-form');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }
    
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
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        if (!startDateInput || !endDateInput) return;
        
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
                    startDateInput.value = formattedDate;
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
                    endDateInput.value = formattedDate;
                }
            } catch(e) {
                console.error('Lỗi khi xử lý ngày kết thúc:', e);
            }
        }
        
        // Thêm sự kiện change cho các trường ngày để cập nhật hiển thị ngay lập tức
        startDateInput.addEventListener('change', function() {
            updateDateDisplay(this, 'start_date');
        });
        
        endDateInput.addEventListener('change', function() {
            updateDateDisplay(this, 'end_date');
        });
    }
    
    // Hàm cập nhật hiển thị ngày giờ khi người dùng thay đổi
    function updateDateDisplay(inputElement, fieldId) {
        try {
            const date = new Date(inputElement.value);
            if(!isNaN(date.getTime())) {
                const formattedDisplayDate = formatDateTimeVN(date);
                
                const infoElement = document.querySelector(`label[for="${fieldId}"]`).closest('.col-md-3').querySelector('.small.text-muted');
                if (infoElement) {
                    infoElement.innerHTML = `<i class="fas fa-info-circle me-1"></i>${formattedDisplayDate}`;
                }
            }
        } catch(e) {
            console.error('Lỗi khi cập nhật hiển thị ngày:', e);
        }
    }
    
    // Hàm áp dụng các bộ lọc
    function applyFilters() {
        const searchInput = document.getElementById('search');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        if (!searchInput || !startDateInput || !endDateInput) return;
        
        let url = new URL(window.location.href);
        let searchParams = new URLSearchParams(url.search);
        
        // Lấy giá trị từ các trường
        const search = searchInput.value;
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
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

<?= $this->section('script_ext') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dữ liệu cho biểu đồ
    const eventData = {
        attended: <?= $attendedEvents ?>,
        pending: <?= $pendingEvents ?>,
        cancelled: <?= $cancelledEvents ?>
    };
    
    // Nếu không có dữ liệu, không vẽ biểu đồ
    if (eventData.attended + eventData.pending + eventData.cancelled === 0) {
        return;
    }
    
    // Vẽ biểu đồ tròn
    const ctx = document.getElementById('eventStatusChart').getContext('2d');
    const eventStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Đã tham gia', 'Đang chờ', 'Đã hủy'],
            datasets: [{
                data: [eventData.attended, eventData.pending, eventData.cancelled],
                backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
                borderColor: ['#ffffff', '#ffffff', '#ffffff'],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Xử lý bộ lọc sự kiện
    const filterButtons = document.querySelectorAll('.filter-btn');
    const eventCards = document.querySelectorAll('.event-card-container');
    
    // Lọc theo trạng thái
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Loại bỏ active class từ tất cả các nút
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Thêm active class cho nút được chọn
            this.classList.add('active');
            
            // Lấy giá trị bộ lọc
            const filter = this.getAttribute('data-filter');
            
            // Lọc thẻ sự kiện
            eventCards.forEach(card => {
                if (filter === 'all' || card.getAttribute('data-event-status') === filter) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Kiểm tra nếu không có sự kiện nào hiển thị
            checkEmptyResults();
        });
    });
    
    // Lọc theo ngày
    const dateFilter = document.querySelector('.date-filter');
    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            const filter = this.value;
            const today = new Date();
            const currentMonth = today.getMonth();
            const currentYear = today.getFullYear();
            
            eventCards.forEach(card => {
                const eventDate = new Date(card.getAttribute('data-event-date'));
                
                let show = true;
                
                if (filter === 'this-month') {
                    // Lọc trong tháng này
                    show = eventDate.getMonth() === currentMonth && eventDate.getFullYear() === currentYear;
                } else if (filter === 'this-year') {
                    // Lọc trong năm nay
                    show = eventDate.getFullYear() === currentYear;
                } else if (filter === 'past') {
                    // Lọc sự kiện đã diễn ra
                    show = eventDate < today;
                } else if (filter === 'future') {
                    // Lọc sự kiện sắp diễn ra
                    show = eventDate > today;
                }
                
                if (show) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Kiểm tra nếu không có sự kiện nào hiển thị
            checkEmptyResults();
        });
    }
    
    // Tìm kiếm sự kiện
    const searchInput = document.getElementById('searchEvent');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            if (searchTerm === '') {
                // Nếu không có từ khóa tìm kiếm, hiển thị tất cả
                eventCards.forEach(card => {
                    card.style.display = 'block';
                });
            } else {
                // Lọc theo từ khóa
                eventCards.forEach(card => {
                    const cardText = card.textContent.toLowerCase();
                    if (cardText.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
            
            // Kiểm tra nếu không có sự kiện nào hiển thị
            checkEmptyResults();
        });
    }
    
    // Kiểm tra nếu không có sự kiện nào được hiển thị
    function checkEmptyResults() {
        let visibleCount = 0;
        eventCards.forEach(card => {
            if (card.style.display !== 'none') {
                visibleCount++;
            }
        });
        
        // Hiển thị thông báo nếu không có kết quả
        const eventCardContainer = document.querySelector('.event-cards');
        let emptyMessage = eventCardContainer.querySelector('.empty-filter-results');
        
        if (visibleCount === 0) {
            if (!emptyMessage) {
                emptyMessage = document.createElement('div');
                emptyMessage.className = 'empty-filter-results empty-state';
                emptyMessage.innerHTML = `
                    <div class="empty-icon">
                        <i class="fas fa-filter"></i>
                    </div>
                    <h3>Không tìm thấy sự kiện</h3>
                    <p>Không có sự kiện nào phù hợp với bộ lọc hiện tại. Hãy thử các bộ lọc khác.</p>
                    <button class="btn btn-outline-primary reset-filters">
                        <i class="fas fa-undo me-2"></i>Đặt lại bộ lọc
                    </button>
                `;
                eventCardContainer.appendChild(emptyMessage);
                
                // Thêm sự kiện click cho nút đặt lại bộ lọc
                const resetButton = emptyMessage.querySelector('.reset-filters');
                resetButton.addEventListener('click', resetAllFilters);
            }
        } else if (emptyMessage) {
            emptyMessage.remove();
        }
    }
    
    // Đặt lại tất cả các bộ lọc
    function resetAllFilters() {
        // Đặt lại bộ lọc trạng thái
        filterButtons.forEach(btn => btn.classList.remove('active'));
        filterButtons[0].classList.add('active');
        
        // Đặt lại bộ lọc ngày
        if (dateFilter) {
            dateFilter.value = 'all';
        }
        
        // Đặt lại ô tìm kiếm
        if (searchInput) {
            searchInput.value = '';
        }
        
        // Hiển thị lại tất cả thẻ sự kiện
        eventCards.forEach(card => {
            card.style.display = 'block';
        });
        
        // Xóa thông báo trống nếu có
        const emptyMessage = document.querySelector('.empty-filter-results');
        if (emptyMessage) {
            emptyMessage.remove();
        }
    }
    
    // Áp dụng bộ lọc
    const applyFiltersButton = document.getElementById('applyFilters');
    if (applyFiltersButton) {
        applyFiltersButton.addEventListener('click', function() {
            // Áp dụng cả bộ lọc trạng thái và ngày
            const activeFilter = document.querySelector('.filter-btn.active').getAttribute('data-filter');
            const dateFilterValue = dateFilter ? dateFilter.value : 'all';
            const searchTermValue = searchInput ? searchInput.value.toLowerCase().trim() : '';
            
            eventCards.forEach(card => {
                const cardStatus = card.getAttribute('data-event-status');
                const cardDate = new Date(card.getAttribute('data-event-date'));
                const cardText = card.textContent.toLowerCase();
                
                // Kiểm tra điều kiện trạng thái
                const statusMatch = activeFilter === 'all' || cardStatus === activeFilter;
                
                // Kiểm tra điều kiện ngày
                let dateMatch = true;
                if (dateFilterValue !== 'all') {
                    const today = new Date();
                    const currentMonth = today.getMonth();
                    const currentYear = today.getFullYear();
                    
                    if (dateFilterValue === 'this-month') {
                        dateMatch = cardDate.getMonth() === currentMonth && cardDate.getFullYear() === currentYear;
                    } else if (dateFilterValue === 'this-year') {
                        dateMatch = cardDate.getFullYear() === currentYear;
                    } else if (dateFilterValue === 'past') {
                        dateMatch = cardDate < today;
                    } else if (dateFilterValue === 'future') {
                        dateMatch = cardDate > today;
                    }
                }
                
                // Kiểm tra điều kiện tìm kiếm
                const searchMatch = searchTermValue === '' || cardText.includes(searchTermValue);
                
                // Hiển thị hoặc ẩn thẻ sự kiện
                if (statusMatch && dateMatch && searchMatch) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Kiểm tra nếu không có sự kiện nào hiển thị
            checkEmptyResults();
        });
    }
});
</script>
<?= $this->endSection() ?>

