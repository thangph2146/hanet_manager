<div class="card shadow-sm mb-4 animate__animated animate__fadeInLeft" style="animation-delay: 0.8s;">
    <div class="card-header text-white py-3">
        <h4 class="card-title mb-0"><i class="lni lni-calendar me-2"></i> Lịch trình sự kiện</h4>
    </div>
    <div class="card-body">
        <div class="schedule-timeline">
            <?php 
            // Lấy lịch trình sự kiện từ model
            $schedules = isset($event_schedule) ? $event_schedule : [];
            
            if (!empty($schedules)): 
                foreach ($schedules as $schedule): 
            ?>
                <div class="schedule-item">
                    <div class="time"><?= $schedule['thoi_gian'] ?? '' ?></div>
                    <div class="content">
                        <h5><?= $schedule['noi_dung'] ?? '' ?></h5>
                        <?php if (!empty($schedule['ngay'])): ?>
                            <p><i class="lni lni-calendar me-1"></i> <?= $schedule['ngay'] ?? '' ?></p>
                        <?php endif; ?>
                        <?php if (!empty($schedule['dia_diem'])): ?>
                            <span class="badge bg-primary"><i class="lni lni-map-marker me-1"></i> <?= $schedule['dia_diem'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <div class="alert alert-info">
                    <i class="lni lni-information me-2"></i> Chưa có lịch trình chi tiết cho sự kiện này.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>