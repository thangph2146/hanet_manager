<div class="card shadow-sm mb-4 animate__animated animate__fadeInLeft" style="animation-delay: 0.4s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-timer me-2"></i> Thời gian</h4>
        </div>
        <div class="card-body">
            <?php
            // Hiển thị đếm ngược đến sự kiện
            $now = time();
            
            // Lấy thời gian sự kiện từ thoi_gian_bat_dau và thoi_gian_ket_thuc
            $eventStartTime = null;
            $eventEndTime = null;
            
            // Ưu tiên sử dụng thoi_gian_bat_dau và thoi_gian_ket_thuc
            if (!empty($event['thoi_gian_bat_dau'])) {
                $eventStartTime = strtotime($event['thoi_gian_bat_dau']);
                log_message('debug', 'Thời gian bắt đầu từ thoi_gian_bat_dau: ' . $event['thoi_gian_bat_dau'] . ' => ' . date('Y-m-d H:i:s', $eventStartTime));
            } elseif (!empty($event['ngay_to_chuc']) && !empty($event['gio_bat_dau'])) {
                // Fallback nếu không có thoi_gian_bat_dau
                $eventStartTime = strtotime($event['ngay_to_chuc'] . ' ' . $event['gio_bat_dau']);
                log_message('debug', 'Thời gian bắt đầu từ ngay_to_chuc và gio_bat_dau: ' . $event['ngay_to_chuc'] . ' ' . $event['gio_bat_dau'] . ' => ' . date('Y-m-d H:i:s', $eventStartTime));
            } else {
                // Mặc định nếu không có dữ liệu
                $eventStartTime = time() + 86400; // Mặc định 1 ngày sau
                log_message('debug', 'Không tìm thấy thời gian bắt đầu, mặc định 1 ngày sau: ' . date('Y-m-d H:i:s', $eventStartTime));
            }
            
            if (!empty($event['thoi_gian_ket_thuc'])) {
                $eventEndTime = strtotime($event['thoi_gian_ket_thuc']);
                log_message('debug', 'Thời gian kết thúc từ thoi_gian_ket_thuc: ' . $event['thoi_gian_ket_thuc'] . ' => ' . date('Y-m-d H:i:s', $eventEndTime));
            } elseif (!empty($event['ngay_to_chuc']) && !empty($event['gio_ket_thuc'])) {
                // Fallback nếu không có thoi_gian_ket_thuc
                $eventEndTime = strtotime($event['ngay_to_chuc'] . ' ' . $event['gio_ket_thuc']);
                log_message('debug', 'Thời gian kết thúc từ ngay_to_chuc và gio_ket_thuc: ' . $event['ngay_to_chuc'] . ' ' . $event['gio_ket_thuc'] . ' => ' . date('Y-m-d H:i:s', $eventEndTime));
            } else {
                // Mặc định nếu không có dữ liệu
                $eventEndTime = $eventStartTime + 7200; // Mặc định 2 giờ sau khi bắt đầu
                log_message('debug', 'Không tìm thấy thời gian kết thúc, mặc định 2 giờ sau bắt đầu: ' . date('Y-m-d H:i:s', $eventEndTime));
            }
            
            // Ghi log debug thông tin thời gian để kiểm tra
            log_message('debug', 'Thời gian hiện tại: ' . date('Y-m-d H:i:s', $now));
            log_message('debug', 'Thời gian bắt đầu: ' . date('Y-m-d H:i:s', $eventStartTime));
            log_message('debug', 'Thời gian kết thúc: ' . date('Y-m-d H:i:s', $eventEndTime));
            
            // Tính toán trạng thái sự kiện
            $status = 'upcoming';
            $timeRemaining = $eventStartTime - $now;
            
            if ($now >= $eventStartTime && $now <= $eventEndTime) {
                $status = 'ongoing';
                $timeRemaining = $eventEndTime - $now;
            } elseif ($now > $eventEndTime) {
                $status = 'past';
                $timeRemaining = 0;
            }
            
            log_message('debug', 'Trạng thái sự kiện: ' . $status . ', Thời gian còn lại: ' . $timeRemaining . ' giây');
            
            // Tính toán thời gian còn lại
            $days = 0;
            $hours = 0;
            $minutes = 0;
            $seconds = 0;
            
            if ($timeRemaining > 0) {
                $days = floor($timeRemaining / 86400);
                $hours = floor(($timeRemaining % 86400) / 3600);
                $minutes = floor(($timeRemaining % 3600) / 60);
                $seconds = $timeRemaining % 60;
            }
            
            // Hiển thị thông báo tương ứng với trạng thái
            $statusText = '';
            $statusClass = '';
            
            switch ($status) {
                case 'upcoming':
                    $statusText = 'Sự kiện sẽ diễn ra sau';
                    $statusClass = 'alert-info';
                    break;
                case 'ongoing':
                    $statusText = 'Sự kiện sẽ kết thúc sau';
                    $statusClass = 'alert-success';
                    break;
                case 'past':
                    $statusText = 'Sự kiện đã kết thúc vào lúc ' . date('H:i', $eventEndTime) . ' ngày ' . date('d/m/Y', $eventEndTime);
                    $statusClass = 'alert-secondary';
                    break;
            }
            ?>
            
            <div class="event-countdown mb-4 animate__animated animate__fadeInUp">
                <div class="alert <?= $statusClass ?> d-flex align-items-center" role="alert">
                    <i class="lni lni-alarm-clock fs-4 me-3"></i>
                    <div>
                        <strong><?= $statusText ?></strong>
                        
                        <?php if ($status != 'past'): ?>
                        <div class="countdown-timer d-flex mt-2" id="countdown-timer">
                            <div class="countdown-item">
                                <span class="countdown-value" id="days"><?= str_pad($days, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="countdown-label">Ngày</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-value" id="hours"><?= str_pad($hours, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="countdown-label">Giờ</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-value" id="minutes"><?= str_pad($minutes, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="countdown-label">Phút</span>
                            </div>
                            <div class="countdown-item">
                                <span class="countdown-value" id="seconds"><?= str_pad($seconds, 2, '0', STR_PAD_LEFT) ?></span>
                                <span class="countdown-label">Giây</span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php if ($status != 'past'): ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Thời gian đích
                <?php if ($status == 'upcoming'): ?>
                var targetTime = <?= $eventStartTime ?> * 1000; // Chuyển đổi sang milliseconds
                <?php else: ?>
                var targetTime = <?= $eventEndTime ?> * 1000; // Chuyển đổi sang milliseconds
                <?php endif; ?>
                
                // Cập nhật đồng hồ đếm ngược mỗi giây
                var countdownTimer = setInterval(function() {
                    // Lấy thời gian hiện tại
                    var now = new Date().getTime();
                    
                    // Tính toán thời gian còn lại
                    var timeRemaining = targetTime - now;
                    
                    // Nếu đã hết thời gian, dừng đếm ngược
                    if (timeRemaining < 0) {
                        clearInterval(countdownTimer);
                        // Refresh trang để cập nhật trạng thái
                        location.reload();
                        return;
                    }
                    
                    // Tính toán ngày, giờ, phút, giây
                    var days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
                    var hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    var minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
                    var seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);
                    
                    // Hiển thị kết quả
                    document.getElementById('days').innerHTML = (days < 10 ? '0' : '') + days;
                    document.getElementById('hours').innerHTML = (hours < 10 ? '0' : '') + hours;
                    document.getElementById('minutes').innerHTML = (minutes < 10 ? '0' : '') + minutes;
                    document.getElementById('seconds').innerHTML = (seconds < 10 ? '0' : '') + seconds;
                }, 1000);
            });
            </script>
            
            <style>
            .countdown-timer {
                display: flex;
                justify-content: flex-start;
                gap: 15px;
            }
            
            .countdown-item {
                text-align: center;
                min-width: 60px;
            }
            
            .countdown-value {
                font-size: 1.5rem;
                font-weight: bold;
                display: block;
                background-color: rgba(255, 255, 255, 0.3);
                border-radius: 4px;
                padding: 5px 10px;
            }
            
            .countdown-label {
                font-size: 0.8rem;
                display: block;
                margin-top: 5px;
            }
            </style>
            <?php endif; ?>
        </div>
    </div>