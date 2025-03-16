<?php
/**
 * Component hiển thị sidebar của trang chi tiết sự kiện
 */
?>

<div class="col-lg-12">
    <!-- Event Stats -->
    <div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.3s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-stats-up me-2"></i> Thông tin đăng ký</h4>
        </div>
        <div class="card-body">
            <div class="text-center mb-3">
                <div class="stats-number"><?= isset($registrationCount) ? $registrationCount : 0 ?></div>
                <p class="mb-0">Người đã đăng ký</p>
            </div>
            <div class="progress mb-3" style="height: 10px;">
                <?php 
                    $percent = isset($registrationCount) && $event['so_luong_tham_gia'] > 0 
                        ? min(100, round(($registrationCount / $event['so_luong_tham_gia']) * 100)) 
                        : 0;
                ?>
                <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $percent ?>%" 
                    aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= $percent ?>%
                </div>
            </div>
            <p class="text-center text-muted small mb-4">
                <i class="lni lni-ticket me-1"></i> Còn <?= max(0, $event['so_luong_tham_gia'] - (isset($registrationCount) ? $registrationCount : 0)) ?> chỗ trống
            </p>
            <a href="#" class="btn btn-primary w-100 pulse" data-bs-toggle="tab" data-bs-target="#event-registration" role="tab" aria-controls="event-registration" aria-selected="false">
                <i class="lni lni-pencil me-2"></i> Đăng ký ngay
            </a>
        </div>
    </div>

    <!-- Event Stats -->
    <div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.35s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-bar-chart me-2"></i> Thống kê sự kiện</h4>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="lni lni-eye text-primary me-2"></i> Lượt xem</span>
                    <span class="badge bg-primary rounded-pill"><?= isset($event['so_luot_xem']) ? number_format($event['so_luot_xem']) : 0 ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="lni lni-users text-primary me-2"></i> Người đăng ký</span>
                    <span class="badge bg-primary rounded-pill"><?= isset($registrationCount) ? number_format($registrationCount) : 0 ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="lni lni-checkmark-circle text-primary me-2"></i> Đã tham gia</span>
                    <span class="badge bg-primary rounded-pill"><?= isset($attendedCount) ? number_format($attendedCount) : 0 ?></span>
                </li>
            </ul>
        </div>
    </div>

    <!-- Event Countdown -->
    <div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.4s;">
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

    <!-- Event Organizer -->
    <div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.5s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-graduation me-2"></i> Đơn vị tổ chức</h4>
        </div>
        <div class="card-body">
            <div class="organizer-info text-center">
                <img src="<?= base_url('assets/images/hub-logo.png') ?>" alt="HUB Logo" class="img-fluid mb-3" style="max-height: 80px;">
                <h5>Trường Đại học Ngân hàng TP.HCM</h5>
                <hr class="my-3">
                <div class="contact-info text-start">
                    <p class="mb-2"><i class="lni lni-map-marker me-2 text-primary"></i> 36 Tôn Thất Đạm, Quận 1, TP.HCM</p>
                    <p class="mb-2"><i class="lni lni-phone me-2 text-primary"></i> (028) 38 212 593</p>
                    <p class="mb-0"><i class="lni lni-envelope me-2 text-primary"></i> info@hub.edu.vn</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Events -->
    <div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.6s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-calendar me-2"></i> Sự kiện liên quan</h4>
        </div>
        <div class="card-body">
            <div class="related-events">
                <?php if(isset($related_events) && !empty($related_events)): ?>
                    <?php foreach ($related_events as $related): ?>
                    <div class="related-event-item mb-3">
                        <a href="<?= site_url('su-kien/detail/' . $related['slug']) ?>" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div class="event-date text-center me-3">
                                    <div class="date-day"><?= date('d', strtotime($related['ngay_to_chuc'])) ?></div>
                                    <div class="date-month"><?= date('m/Y', strtotime($related['ngay_to_chuc'])) ?></div>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?= $related['ten_su_kien'] ?></h6>
                                    <p class="text-muted small mb-0">
                                        <i class="lni lni-map-marker me-1"></i> <?= $related['dia_diem'] ?>
                                        <br>
                                        <i class="lni lni-alarm-clock me-1"></i> <?= date('H:i', strtotime($related['ngay_to_chuc'])) ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">Không có sự kiện liên quan</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Social Share -->
    <div class="card shadow-sm mt-4 animate__animated animate__fadeInRight" style="animation-delay: 0.7s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-share me-2"></i> Chia sẻ sự kiện</h4>
        </div>
        <div class="card-body">
            <div class="share-buttons d-flex flex-wrap justify-content-center">
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" class="btn btn-sm btn-facebook me-2 mb-2">
                    <i class="lni lni-facebook-filled"></i> Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($event['ten_su_kien']) ?>" target="_blank" class="btn btn-sm btn-twitter me-2 mb-2">
                    <i class="lni lni-twitter-filled"></i> Twitter
                </a>
                <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(current_url()) ?>&title=<?= urlencode($event['ten_su_kien']) ?>" target="_blank" class="btn btn-sm btn-linkedin me-2 mb-2">
                    <i class="lni lni-linkedin-original"></i> LinkedIn
                </a>
                <a href="mailto:?subject=<?= urlencode($event['ten_su_kien']) ?>&body=<?= urlencode('Xem chi tiết sự kiện tại: ' . current_url()) ?>" class="btn btn-sm btn-outline-primary mb-2">
                    <i class="lni lni-envelope"></i> Email
                </a>
            </div>
            
            <div class="qr-code text-center mt-3">
                <p class="small mb-2">Quét mã QR để xem trên điện thoại</p>
                <img class="img-fluid w-100"  src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= urlencode(current_url()) ?>" alt="QR Code" style="max-width: 120px;">
            </div>
        </div>
    </div>
</div> 