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
    <div class="border-bottom mb-4">
        <h3 class="section-title text-primary fw-bold">
            <i class="lni lni-calendar me-2"></i>
            Lịch trình sự kiện
        </h3>
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
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <?php if (!empty($time)): ?>
                            <div class="event-time fw-bold" style="color: <?= $color ?>">
                                <i class="lni lni-timer me-1"></i> <?= $time ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($date)): ?>
                            <div class="event-date text-muted small">
                                <i class="lni lni-calendar me-1"></i> <?= $date ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <h5 class="card-title mb-2"><?= $content ?></h5>
                        
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <?php if (!empty($location)): ?>
                            <span class="badge bg-light text-dark">
                                <i class="lni lni-map me-1"></i> <?= $location ?>
                            </span>
                            <?php endif; ?>
                            
                            <?php if (!empty($type)): ?>
                            <span class="badge bg-primary-soft text-primary">
                                <i class="lni lni-tag me-1"></i> <?= $type ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($speaker)): ?>
                        <div class="mt-3 pt-2 border-top">
                            <div class="small text-muted mb-1">Người phụ trách:</div>
                            <div class="speaker-info">
                                <span class="fw-bold"><?= $speaker ?></span>
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
.timeline-container {
    position: relative;
    padding-left: 40px;
}
.timeline-container:before {
    content: '';
    position: absolute;
    top: 0;
    bottom: 0;
    left: 15px;
    width: 2px;
    background-color: #e9ecef;
}
.timeline-item {
    position: relative;
}
.timeline-marker {
    position: absolute;
    left: -40px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    top: 15px;
    z-index: 1;
    box-shadow: 0 0 0 4px rgba(255,255,255,0.7);
}
.timeline-content {
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: none;
}
.event-time {
    font-size: 1.1rem;
}
.badge.bg-primary-soft {
    background-color: rgba(13, 110, 253, 0.15);
}
</style>
<?php else: ?>
<div class="alert alert-info mb-5">
    <i class="lni lni-information-circle me-2"></i>
    Chưa có lịch trình chi tiết cho sự kiện này.
</div>
<?php endif; ?>