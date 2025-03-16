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
            <h4 class="card-title mb-0"><i class="lni lni-timer me-2"></i> Thời gian còn lại</h4>
        </div>
        <div class="card-body">
            <div class="countdown-container" id="countdown">
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
                    // Lấy thời gian sự kiện
                    const eventDate = new Date('<?= $event['ngay_to_chuc'] ?>'.replace(/-/g, '/'));
                    
                    // Cập nhật đếm ngược
                    function updateCountdown() {
                        const now = new Date();
                        const diff = eventDate - now;
                        
                        if (diff <= 0) {
                            document.getElementById('countdown-days').textContent = '00';
                            document.getElementById('countdown-hours').textContent = '00';
                            document.getElementById('countdown-minutes').textContent = '00';
                            document.getElementById('countdown-seconds').textContent = '00';
                            return;
                        }
                        
                        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        
                        document.getElementById('countdown-days').textContent = days < 10 ? '0' + days : days;
                        document.getElementById('countdown-hours').textContent = hours < 10 ? '0' + hours : hours;
                        document.getElementById('countdown-minutes').textContent = minutes < 10 ? '0' + minutes : minutes;
                        document.getElementById('countdown-seconds').textContent = seconds < 10 ? '0' + seconds : seconds;
                    }
                    
                    // Cập nhật mỗi giây
                    updateCountdown();
                    setInterval(updateCountdown, 1000);
                });
            </script>
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