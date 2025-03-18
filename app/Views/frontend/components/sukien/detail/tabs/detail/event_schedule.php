<div class="event-schedule mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
        <h3>Lịch trình</h3>
        <div class="schedule-timeline">
            <?php 
            // Lấy lịch trình sự kiện từ model
            $schedules = isset($event_schedule) ? $event_schedule : [];
            
            if (!empty($schedules)): 
                foreach ($schedules as $schedule): 
            ?>
                <div class="schedule-item">
                    <div class="time"><?= $schedule['thoi_gian'] ?></div>
                    <div class="content">
                        <h5><?= $schedule['tieu_de'] ?></h5>
                        <p><?= $schedule['mo_ta'] ?></p>
                        <?php if (!empty($schedule['dien_gia'])): ?>
                            <span class="badge bg-primary"><i class="lni lni-user me-1"></i> <?= $schedule['dien_gia'] ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php 
                endforeach; 
            else: 
            ?>
                <div class="alert alert-info">
                    <i class="lni lni-information me-2"></i> Lịch trình chi tiết của sự kiện sẽ được cập nhật sớm.
                </div>
            <?php endif; ?>
        </div>
    </div>