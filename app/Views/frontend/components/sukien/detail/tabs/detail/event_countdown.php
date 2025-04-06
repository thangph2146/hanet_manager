<div class="card shadow-sm mb-4 animate__animated animate__fadeInLeft" style="animation-delay: 0.4s;">
    <div class="card-header text-white py-3">
        <h4 class="card-title mb-0"><i class="lni lni-timer me-2"></i> Thời gian sự kiện</h4>
    </div>
    <div class="card-body">
        <?php
        // Lấy thời gian sự kiện
        $eventDate = null;
        $startTime = null;
        $endTime = null;
        $eventStatus = 'upcoming'; // upcoming, ongoing, ended
        
        if (!empty($event['thoi_gian_bat_dau'])) {
            $startTime = strtotime($event['thoi_gian_bat_dau']);
            $eventDate = date('Y-m-d', $startTime);
        } elseif (!empty($event['ngay_to_chuc'])) {
            $eventDate = date('Y-m-d', strtotime($event['ngay_to_chuc']));
            if (!empty($event['gio_bat_dau'])) {
                $startTime = strtotime($eventDate . ' ' . $event['gio_bat_dau']);
            } else {
                $startTime = strtotime($eventDate . ' 00:00:00');
            }
        }
        
        if (!empty($event['thoi_gian_ket_thuc'])) {
            $endTime = strtotime($event['thoi_gian_ket_thuc']);
        } elseif (!empty($event['gio_ket_thuc'])) {
            $endTime = strtotime($eventDate . ' ' . $event['gio_ket_thuc']);
        } else {
            $endTime = $startTime ? $startTime + 86400 : null; // Mặc định 1 ngày nếu không có thời gian kết thúc
        }
        
        // Tính toán trạng thái sự kiện
        $currentTime = time();
        if ($startTime && $endTime) {
            if ($currentTime < $startTime) {
                $eventStatus = 'upcoming';
                $countdownTime = $startTime;
            } elseif ($currentTime >= $startTime && $currentTime <= $endTime) {
                $eventStatus = 'ongoing';
                $countdownTime = $endTime;
            } else {
                $eventStatus = 'ended';
                $countdownTime = null;
            }
        }
        
        // Tính trạng thái đăng ký
        $registrationStatus = 'closed'; // not-started, open, closed, ended
        $registrationCountdown = null;
        
        if (!empty($event['bat_dau_dang_ky']) && !empty($event['ket_thuc_dang_ky'])) {
            $registrationStart = strtotime($event['bat_dau_dang_ky']);
            $registrationEnd = strtotime($event['ket_thuc_dang_ky']);
            
            if ($currentTime < $registrationStart) {
                $registrationStatus = 'not-started';
                $registrationCountdown = $registrationStart;
            } elseif ($currentTime >= $registrationStart && $currentTime <= $registrationEnd) {
                $registrationStatus = 'open';
                $registrationCountdown = $registrationEnd;
            } else {
                $registrationStatus = 'closed';
            }
        }
        ?>
        
        <div class="event-countdown mb-5 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <?php if ($eventStatus == 'upcoming' && $countdownTime): ?>
                    <div class="countdown-section text-white p-4 rounded-top d-flex flex-column align-items-center justify-content-center">
                        <h4 class="mb-3 fw-bold text-white  ">Sự kiện sẽ diễn ra sau</h4>
                        <div class="countdown-timer d-flex justify-content-center text-white" data-countdown="<?= date('Y/m/d H:i:s', $countdownTime) ?>">
                            <div class="countdown-item">
                                <div class="days">00</div>
                                <span>Ngày</span>
                            </div>
                            <div class="countdown-item">
                                <div class="hours">00</div>
                                <span>Giờ</span>
                            </div>
                            <div class="countdown-item">
                                <div class="minutes">00</div>
                                <span>Phút</span>
                            </div>
                            <div class="countdown-item">
                                <div class="seconds">00</div>
                                <span>Giây</span>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($eventStatus == 'ongoing'): ?>
                    <div class="countdown-section bg-success text-white p-4 rounded-top d-flex flex-column align-items-center justify-content-center">
                        <h4 class="mb-3 fw-bold">Sự kiện đang diễn ra</h4>
                        <?php if ($countdownTime): ?>
                        <div class="d-flex align-items-center">
                            <i class="lni lni-timer me-2 fs-4"></i>
                            <span>Kết thúc sau: <span class="remaining-time" data-endtime="<?= date('Y/m/d H:i:s', $countdownTime) ?>">
                                <?= ceil(($countdownTime - $currentTime) / 3600) ?> giờ
                            </span></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <div class="countdown-section bg-secondary text-white p-4 rounded-top d-flex flex-column align-items-center justify-content-center">
                        <h4 class="mb-3 fw-bold">Sự kiện đã kết thúc</h4>
                        <div class="d-flex align-items-center">
                            <i class="lni lni-checkmark-circle me-2 fs-4"></i>
                            <span>Cảm ơn bạn đã quan tâm đến sự kiện</span>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Phần hiển thị thông tin đăng ký -->
                    <div class="registration-info p-4">
                        <div class="event-timeline mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="lni lni-calendar me-2"></i>
                                Tiến trình sự kiện
                            </h5>
                            
                            <div class="timeline">
                                <div class="timeline-step <?= $registrationStatus == 'not-started' || $registrationStatus == 'open' || $registrationStatus == 'closed' ? 'active' : '' ?>">
                                    <div class="timeline-content">
                                        <div class="inner-circle">
                                            <i class="lni lni-user"></i>
                                        </div>
                                        <p>Đăng ký</p>
                                        <?php if (!empty($event['bat_dau_dang_ky'])): ?>
                                        <small class="text-muted"><?= date('d/m/Y', strtotime($event['bat_dau_dang_ky'])) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="timeline-step <?= $eventStatus == 'ongoing' || $eventStatus == 'ended' ? 'active' : '' ?>">
                                    <div class="timeline-content">
                                        <div class="inner-circle">
                                            <i class="lni lni-play"></i>
                                        </div>
                                        <p>Bắt đầu</p>
                                        <?php if ($startTime): ?>
                                        <small class="text-muted"><?= date('d/m/Y', $startTime) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="timeline-step <?= $eventStatus == 'ended' ? 'active' : '' ?>">
                                    <div class="timeline-content">
                                        <div class="inner-circle">
                                            <i class="lni lni-flag"></i>
                                        </div>
                                        <p>Kết thúc</p>
                                        <?php if ($endTime): ?>
                                        <small class="text-muted"><?= date('d/m/Y', $endTime) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hiển thị trạng thái đăng ký -->
                        <div class="registration-status">
                            <?php if ($registrationStatus == 'not-started'): ?>
                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="lni lni-alarm-clock fs-4 me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Đăng ký chưa mở</h6>
                                        <p class="mb-0">Đăng ký sẽ mở vào ngày <?= date('d/m/Y', strtotime($event['bat_dau_dang_ky'])) ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php elseif ($registrationStatus == 'open'): ?>
                            <div class="alert alert-success">
                                <div class="d-flex align-items-center">
                                    <i class="lni lni-checkmark-circle fs-4 me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Đăng ký đang mở</h6>
                                        <p class="mb-0">Hạn đăng ký: <?= date('d/m/Y', strtotime($event['ket_thuc_dang_ky'])) ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php if (!empty($event['link_dang_ky'])): ?>
                            <div class="mt-3 text-center">
                                <a href="<?= $event['link_dang_ky'] ?>" target="_blank" class="btn btn-primary">
                                    <i class="lni lni-user me-2"></i> Đăng ký tham gia
                                </a>
                            </div>
                            <?php endif; ?>
                            <?php else: ?>
                            <div class="alert alert-secondary">
                                <div class="d-flex align-items-center">
                                    <i class="lni lni-close fs-4 me-3"></i>
                                    <div>
                                        <h6 class="mb-1 fw-bold">Đăng ký đã đóng</h6>
                                        <p class="mb-0">Thời gian đăng ký đã kết thúc</p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
        .countdown-timer {
            display: flex;
            gap: 15px;
        }
        .countdown-item {
            text-align: center;
            min-width: 70px;
        }
        .countdown-item div {
            font-size: 2.5rem;
            font-weight: bold;
            line-height: 1;
        }
        .countdown-item span {
            font-size: 0.85rem;
            text-transform: uppercase;
        }
        
        .timeline {
            display: flex;
            justify-content: space-between;
            position: relative;
        }
        .timeline:after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: #e9ecef;
            top: 25px;
            left: 0;
            z-index: 1;
        }
        .timeline-step {
            position: relative;
            z-index: 2;
            text-align: center;
            width: 33.33%;
        }
        .timeline-content {
            padding: 0 10px;
        }
        .inner-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #e9ecef;
            margin: 0 auto 10px;
            transition: all 0.3s;
        }
        .timeline-step.active .inner-circle {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        .timeline-step p {
            margin-bottom: 0;
            font-weight: 500;
        }
        </style>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Countdown timer function
            function updateCountdown() {
                const countdownElement = document.querySelector('.countdown-timer');
                if (!countdownElement) return;
                
                const targetDate = new Date(countdownElement.dataset.countdown).getTime();
                
                function update() {
                    const now = new Date().getTime();
                    const distance = targetDate - now;
                    
                    if (distance < 0) {
                        // Reload page if countdown reaches zero
                        location.reload();
                        return;
                    }
                    
                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                    
                    countdownElement.querySelector('.days').innerHTML = days < 10 ? '0' + days : days;
                    countdownElement.querySelector('.hours').innerHTML = hours < 10 ? '0' + hours : hours;
                    countdownElement.querySelector('.minutes').innerHTML = minutes < 10 ? '0' + minutes : minutes;
                    countdownElement.querySelector('.seconds').innerHTML = seconds < 10 ? '0' + seconds : seconds;
                }
                
                // Update immediately then every second
                update();
                setInterval(update, 1000);
            }
            
            // Update remaining time
            function updateRemainingTime() {
                const remainingTimeElement = document.querySelector('.remaining-time');
                if (!remainingTimeElement) return;
                
                const endTime = new Date(remainingTimeElement.dataset.endtime).getTime();
                
                function update() {
                    const now = new Date().getTime();
                    const distance = endTime - now;
                    
                    if (distance < 0) {
                        // Reload page if time reaches zero
                        location.reload();
                        return;
                    }
                    
                    const hours = Math.ceil(distance / (1000 * 60 * 60));
                    remainingTimeElement.innerHTML = hours + ' giờ';
                }
                
                // Update every minute
                update();
                setInterval(update, 60000);
            }
            
            updateCountdown();
            updateRemainingTime();
        });
        </script>
    </div>
</div>