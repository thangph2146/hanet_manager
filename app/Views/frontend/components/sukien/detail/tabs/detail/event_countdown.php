<div class="card shadow-sm mb-4 animate__animated animate__fadeInLeft" style="animation-delay: 0.4s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-timer me-2"></i> Thời gian</h4>
        </div>
        <div class="card-body">
            <?php
            $current_time = time();
            $event_start_time = strtotime($event['ngay_to_chuc'] . ' ' . $event['gio_bat_dau']);
            $event_end_time = strtotime($event['ngay_to_chuc'] . ' ' . $event['gio_ket_thuc']);
            
            // Xác định trạng thái sự kiện
            $event_status = '';
            $status_color = '';
            $status_icon = '';
            
            if ($current_time < $event_start_time) {
                // Sự kiện sắp diễn ra
                $event_status = 'Sắp diễn ra';
                $status_color = 'primary';
                $status_icon = 'lni-calendar';
            } elseif ($current_time >= $event_start_time && $current_time <= $event_end_time) {
                // Sự kiện đang diễn ra
                $event_status = 'Đang diễn ra';
                $status_color = 'success';
                $status_icon = 'lni-pulse';
            } else {
                // Sự kiện đã kết thúc
                $event_status = 'Đã kết thúc';
                $status_color = 'secondary';
                $status_icon = 'lni-checkmark-circle';
            }
            ?>
            
            <div class="text-center mb-3">
                <span class="badge bg-<?= $status_color ?> p-2 mb-3">
                    <i class="lni <?= $status_icon ?>"></i> <?= $event_status ?>
                </span>
                
                <div class="event-datetime mb-3">
                    <div class="event-date fs-4 fw-bold text-<?= $status_color ?>">
                        <?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?>
                    </div>
                    <div class="event-time">
                        <i class="lni lni-alarm-clock me-1"></i> 
                        <?= date('H:i', strtotime($event['gio_bat_dau'])) ?> - <?= $event['gio_ket_thuc'] ?>
                    </div>
                </div>
            </div>
            
            <?php if ($current_time < $event_start_time): ?>
                <div class="countdown-container" id="countdown">
                    <p class="text-center text-muted small mb-2">Còn lại:</p>
                    <div class="row text-center">
                        <div class="col-3">
                            <div class="countdown-item">
                                <div class="countdown-value" id="countdown-days">00</div>
                                <div class="countdown-label">Ngày</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="countdown-item">
                                <div class="countdown-value" id="countdown-hours">00</div>
                                <div class="countdown-label">Giờ</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="countdown-item">
                                <div class="countdown-value" id="countdown-minutes">00</div>
                                <div class="countdown-label">Phút</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="countdown-item">
                                <div class="countdown-value" id="countdown-seconds">00</div>
                                <div class="countdown-label">Giây</div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Sử dụng timestamp để đảm bảo tính toán chính xác
                        const eventTimestamp = <?= $event_start_time * 1000 ?>; // Chuyển đổi sang milliseconds cho JavaScript
                        
                        // Cập nhật đếm ngược
                        function updateCountdown() {
                            const now = new Date().getTime();
                            const diff = eventTimestamp - now;
                            
                            if (diff <= 0) {
                                document.getElementById('countdown-days').textContent = '00';
                                document.getElementById('countdown-hours').textContent = '00';
                                document.getElementById('countdown-minutes').textContent = '00';
                                document.getElementById('countdown-seconds').textContent = '00';
                                
                                // Làm mới trang sau khi đếm ngược kết thúc để cập nhật trạng thái
                                if (diff > -5000) { // Chỉ làm mới nếu vừa hết (trong vòng 5 giây)
                                    setTimeout(function() {
                                        location.reload();
                                    }, 2000);
                                }
                                return;
                            }
                            
                            // Tính toán ngày, giờ, phút, giây còn lại
                            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                            
                            // Cập nhật UI với định dạng 2 chữ số
                            document.getElementById('countdown-days').textContent = days < 10 ? '0' + days : days;
                            document.getElementById('countdown-hours').textContent = hours < 10 ? '0' + hours : hours;
                            document.getElementById('countdown-minutes').textContent = minutes < 10 ? '0' + minutes : minutes;
                            document.getElementById('countdown-seconds').textContent = seconds < 10 ? '0' + seconds : seconds;
                        }
                        
                        // Cập nhật ban đầu và thiết lập interval
                        updateCountdown();
                        const countdownInterval = setInterval(updateCountdown, 1000);
                        
                        // Theo dõi thay đổi tab và focus để đảm bảo đồng bộ
                        document.addEventListener('visibilitychange', function() {
                            if (document.visibilityState === 'visible') {
                                updateCountdown();
                            }
                        });
                    });
                </script>
                
                <!-- Hiển thị ngày giờ đếm ngược đến -->
                <div class="text-center mt-3">
                    <p class="text-muted small">
                        Sự kiện sẽ bắt đầu lúc <?= date('H:i', $event_start_time) ?> ngày <?= date('d/m/Y', $event_start_time) ?>
                    </p>
                </div>
            <?php elseif ($current_time >= $event_start_time && $current_time <= $event_end_time): ?>
                <div class="text-center">
                    <div class="progress mb-3" style="height: 10px;">
                        <?php 
                        $total_duration = $event_end_time - $event_start_time;
                        $elapsed_time = $current_time - $event_start_time;
                        $progress = min(100, round(($elapsed_time / $total_duration) * 100));
                        ?>
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" 
                             style="width: <?= $progress ?>%" aria-valuenow="<?= $progress ?>" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                    <p class="text-muted small mb-0">
                        Sự kiện đang diễn ra (<?= $progress ?>% thời gian đã trôi qua)
                    </p>
                </div>
            <?php else: ?>
                <div class="text-center">
                    <p class="text-muted mb-0">
                        Sự kiện đã kết thúc vào lúc <?= date('H:i', $event_end_time) ?> ngày <?= date('d/m/Y', $event_end_time) ?>
                    </p>
                    <?php if (isset($attendedCount) && $attendedCount > 0): ?>
                    <div class="alert alert-info mt-3 mb-0">
                        <i class="lni lni-users"></i> <?= $attendedCount ?> người đã tham gia sự kiện này
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>