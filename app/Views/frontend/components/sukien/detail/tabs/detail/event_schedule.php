<?php
// Đảm bảo $schedules là một mảng
if (!isset($schedules) || empty($schedules)) {
    // Thử lấy lịch trình từ event['lich_trinh'] nếu có
    if (isset($event['lich_trinh']) && !empty($event['lich_trinh'])) {
        if (is_string($event['lich_trinh'])) {
            $schedules = json_decode($event['lich_trinh'], true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $schedules = [];
            }
        } else {
            $schedules = $event['lich_trinh'];
        }
    } else {
        $schedules = [];
    }
}

// Nếu vẫn là chuỗi, có thể là do định dạng JSON đã được escape
if (is_string($schedules) && !empty($schedules)) {
    $schedules = json_decode($schedules, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $schedules = [];
    }
}

// Đảm bảo $schedules là một mảng
if (!is_array($schedules)) {
    $schedules = [];
}

// Màu sắc cho các phiên
$colors = ['#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#9b59b6', '#1abc9c'];
?>

<?php if (!empty($schedules)): ?>
<div class="event-schedule mb-5 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
    <div class="schedule-header mb-4">
        <h3 class="section-title text-burgundy fw-bold">
            <i class="lni lni-calendar me-2"></i>
            Lịch trình sự kiện
        </h3>
        <div class="burgundy-line"></div>
    </div>

    <div class="timeline-container">
        <?php foreach ($schedules as $index => $item): ?>
            <?php 
            // Xác định màu sắc cho timeline
            $color = $colors[$index % count($colors)];
            
            // Lấy dữ liệu
            $time = isset($item['thoi_gian']) ? $item['thoi_gian'] : '';
            $content = isset($item['noi_dung']) ? $item['noi_dung'] : '';
            $date = isset($item['ngay']) ? $item['ngay'] : '';
            $location = isset($item['dia_diem']) ? $item['dia_diem'] : '';
            $type = isset($item['loai']) ? $item['loai'] : '';
            $speaker = isset($item['nguoi_phu_trach']) ? $item['nguoi_phu_trach'] : '';
            
            // Hiển thị địa điểm nếu có, nếu không thì hiển thị địa điểm chung từ event
            if (empty($location) && isset($event['dia_diem'])) {
                $location = $event['dia_diem'];
            }
            
            // Format ngày nếu là timestamp hoặc chuỗi ngày hợp lệ
            if (!empty($date) && is_numeric($date)) {
                $date = date('d/m/Y', $date);
            } elseif (!empty($date) && strtotime($date)) {
                $date = date('d/m/Y', strtotime($date));
            }
            
            // Nếu không có ngày, sử dụng ngày của sự kiện
            if (empty($date) && isset($event['thoi_gian_bat_dau_su_kien'])) {
                $date = date('d/m/Y', strtotime($event['thoi_gian_bat_dau_su_kien']));
            }
            ?>
            <div class="timeline-item mb-4">
                <div class="timeline-marker" style="background-color: <?= $color ?>"></div>
                <div class="timeline-content card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <?php if (!empty($time)): ?>
                            <div class="event-time">
                                <i class="lni lni-timer me-1"></i> <?= $time ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($date)): ?>
                            <div class="event-date">
                                <i class="lni lni-calendar me-1"></i> <?= $date ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <h5 class="card-title mb-3"><?= $content ?></h5>
                        
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <?php if (!empty($location)): ?>
                            <span class="event-badge location-badge">
                                <i class="lni lni-map me-1"></i> <?= $location ?>
                            </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($type)): ?>
                            <span class="event-badge type-badge">
                                <i class="lni lni-tag me-1"></i> <?= $type ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($speaker)): ?>
                        <div class="speaker-section">
                            <div class="speaker-label">Người phụ trách:</div>
                            <div class="speaker-info">
                                <i class="lni lni-user me-2"></i>
                                <span><?= $speaker ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
:root {
    --burgundy: #800020;
    --burgundy-light: #a3324d;
    --burgundy-dark: #4d0013;
    --burgundy-transparent: rgba(128, 0, 32, 0.1);
}

.schedule-header {
    position: relative;
    padding-bottom: 1rem;
}

.text-burgundy {
    color: var(--burgundy);
}

.burgundy-line {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100px;
    height: 3px;
    background: var(--burgundy);
}

.timeline-container {
    position: relative;
    padding-left: 50px;
    padding-top: 20px;
}

.timeline-container:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 25px;
    width: 2px;
    background: linear-gradient(to bottom, 
        var(--burgundy) 0%, 
        var(--burgundy-light) 50%, 
        var(--burgundy-dark) 100%
    );
}

.timeline-item {
    position: relative;
}

.timeline-marker {
    position: absolute;
    left: -50px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--burgundy);
    top: 15px;
    z-index: 1;
    box-shadow: 0 0 0 4px rgba(128, 0, 32, 0.2),
                0 0 0 8px rgba(128, 0, 32, 0.1);
    transition: all 0.3s ease;
}

.timeline-content {
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: none;
    transition: all 0.3s ease;
}

.timeline-content:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(128, 0, 32, 0.15);
}

.event-time {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--burgundy);
}

.event-date {
    color: #666;
    font-size: 0.95rem;
}

.card-title {
    color: var(--burgundy-dark);
    font-weight: 600;
    line-height: 1.4;
}

.event-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.location-badge {
    background-color: var(--burgundy-transparent);
    color: var(--burgundy);
}

.type-badge {
    background-color: var(--burgundy);
    color: white;
}

.speaker-section {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(128, 0, 32, 0.1);
}

.speaker-label {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.5rem;
}

.speaker-info {
    display: flex;
    align-items: center;
    color: var(--burgundy);
    font-weight: 500;
}

.speaker-info i {
    color: var(--burgundy-light);
}

@media (max-width: 768px) {
    .timeline-container {
        padding-left: 30px;
    }
    
    .timeline-container:before {
        left: 15px;
    }
    
    .timeline-marker {
        left: -30px;
        width: 16px;
        height: 16px;
    }
    
    .event-time, .event-date {
        font-size: 0.9rem;
    }
}
</style>

<?php else: ?>
<div class="alert alert-burgundy mb-5">
    <i class="lni lni-information-circle me-2"></i>
    Chưa có lịch trình chi tiết cho sự kiện này.
</div>

<style>
.alert-burgundy {
    background-color: var(--burgundy-transparent);
    color: var(--burgundy);
    border: none;
    border-radius: 10px;
}
</style>
<?php endif; ?>