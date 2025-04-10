<?= $this->extend('frontend\layouts\sukien_layout') ?>

<?= $this->section('title') ?>Sự Kiện Đại Học Ngân Hàng TP.HCM<?= $this->endSection() ?>
<?= $this->section('description') ?>Sự Kiện Đại Học Ngân Hàng TP.HCM<?= $this->endSection() ?>
<?= $this->section('keywords') ?>Sự Kiện Đại Học Ngân Hàng TP.HCM<?= $this->endSection() ?>
<?= $this->section('content') ?>
    <!-- Hero Section -->
    <section class="hero-section position-relative overflow-hidden">
        <div class="container position-relative">
            <div class="row min-vh-100 align-items-center justify-content-center">
                <div class="col-lg-10 text-center">
                    <div class="hero-content" data-aos="fade-up" data-aos-delay="100">
                        <?php if($job_fair_event): ?>
                            <h1 class="display-3 fw-bold text-gradient mb-4 text-white"><?= $job_fair_event['ten_su_kien'] ?></h1>
                            <p class="lead mb-4 fw-light" data-aos="fade-up" data-aos-delay="200">
                                Khám phá cơ hội nghề nghiệp và kết nối với doanh nghiệp hàng đầu tại sự kiện của 
                                <span class="text-white fw-bold">Trường Đại học Ngân hàng TP.HCM</span>
                            </p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="300">
                                <a href="#registration" class="btn btn-gradient btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Đăng ký tham gia
                                </a>
                                <a href="#schedule" class="btn btn-outline-light btn-lg btn-hover-gradient">
                                    <i class="fas fa-calendar-alt me-2"></i>Lịch trình
                                </a>
                            </div>
                        <?php else: ?>
                            <h1 class="display-3 fw-bold text-gradient mb-4 text-white">Sự Kiện Đại Học Ngân Hàng TP.HCM</h1>
                            <p class="lead mb-4 fw-light" data-aos="fade-up" data-aos-delay="200">
                                Khám phá các sự kiện và hoạt động tại
                                <span class="text-white fw-bold">Trường Đại học Ngân hàng TP.HCM</span>
                            </p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap" data-aos="fade-up" data-aos-delay="300">
                                <a href="<?= site_url('su-kien') ?>" class="btn btn-gradient btn-lg">
                                    <i class="fas fa-calendar-alt me-2"></i>Xem tất cả sự kiện
                                </a>
                            </div>
                        <?php endif; ?>
                        <div class="hero-scroll-indicator" data-aos="fade-up" data-aos-delay="400">
                            <a href="#hub-banner" class="text-white">
                                <div class="mouse">
                                    <div class="wheel"></div>
                                </div>
                                <div>
                                    <span class="m-scroll-arrows unu"></span>
                                    <span class="m-scroll-arrows doi"></span>
                                    <span class="m-scroll-arrows trei"></span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hero-shape-bottom">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
        <div class="hero-particles position-absolute top-0 left-0 w-100 h-100" id="particles-js"></div>
    </section>

    <!-- HUB Banner -->
    <section id="hub-banner" class="hub-banner position-relative py-6">
        <div class="dot-pattern" style="top: 20px; right: 10%;"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-right">
                    <div class="hub-logo-wrapper position-relative">
                        <div class="hub-logo-bg"></div>
                        <img src="<?= base_url('assets/modules/sukien/images/hub-logo.png') ?>" alt="HUB Logo" class="img-fluid hub-logo">
                    </div>
                </div>
                <div class="col-lg-7" data-aos="fade-left">
                    <div class="hub-content">
                        <h2 class="section-title mb-4">
                            <span class="text-gradient">Trường Đại học Ngân hàng TP.HCM</span>
                        </h2>
                        <p class="lead text-muted mb-4">
                            Trường Đại học Ngân hàng TP.HCM (Banking University of Ho Chi Minh City) là một trường đại học công lập 
                            trực thuộc Ngân hàng Nhà nước Việt Nam, được thành lập năm <?= $stats['founding_year'] ?>, là một trong những 
                            cơ sở giáo dục đại học hàng đầu trong lĩnh vực tài chính - ngân hàng tại Việt Nam.
                        </p>
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="https://hub.edu.vn" target="_blank" class="btn btn-gradient">
                                <i class="fas fa-external-link-alt me-2"></i>Tìm hiểu thêm
                            </a>
                            <a href="<?= site_url('su-kien') ?>" class="btn btn-outline-primary btn-hover-gradient">
                                <i class="fas fa-calendar-week me-2"></i>Xem sự kiện
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="floating-shapes">
            <span data-parallax='{"x": 150, "y": -20}'></span>
            <span data-parallax='{"x": 50, "y": 150}'></span>
            <span data-parallax='{"x": -180, "y": 80}'></span>
            <span data-parallax='{"x": -20, "y": 180}'></span>
            <span data-parallax='{"x": 200, "y": 70}'></span>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="stats-box">
                        <div class="stats-number"><?= $stats['founding_year'] ?></div>
                        <p class="mb-0">Năm thành lập</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="stats-box">
                        <div class="stats-number"><?= number_format($stats['total_participants']) ?>+</div>
                        <p class="mb-0">Sinh viên</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="stats-box">
                        <div class="stats-number"><?= $stats['total_events'] ?>+</div>
                        <p class="mb-0">Sự kiện đã tổ chức</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-box">
                        <div class="stats-number"><?= $stats['total_speakers'] ?>+</div>
                        <p class="mb-0">Diễn giả</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events -->
    <section class="py-5">
        <div class="dot-pattern" style="bottom: 50px; left: 5%;"></div>
        <div class="container">
            <h2 class="section-title">Sự Kiện Sắp Diễn Ra</h2>
            <div class="row">
                <?php foreach ($upcoming_events as $key => $event): ?>
                <div class="col-md-4 mb-4">
                    <?php 
                    // Sử dụng component event_card
                    echo view('frontend\components\sukien\event_card', [
                        'event' => $event,
                        'featured' => ($key === 0) // Sự kiện đầu tiên là nổi bật
                    ]);
                    ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="text-center mt-4">
                <a href="<?= site_url('su-kien') ?>" class="btn btn-primary">Xem tất cả sự kiện</a>
            </div>
        </div>
    </section>

    <!-- Countdown Section -->
    <section class="countdown-section" id="schedule">
        <div class="container">
            <?php if($job_fair_event): ?>
                <?php
                    $eventDateTime = strtotime($job_fair_event['ngay_to_chuc'] . ' ' . $job_fair_event['gio_bat_dau']);
                    $currentTime = time();
                    $eventEndDateTime = strtotime($job_fair_event['ngay_to_chuc'] . ' ' . $job_fair_event['gio_ket_thuc']);
                ?>
                
                <?php if($currentTime < $eventDateTime): ?>
                    <h2 class="countdown-title"><?= $job_fair_event['ten_su_kien'] ?> sẽ diễn ra sau</h2>
                <?php else: ?>
                    <h2 class="countdown-title text-warning">Sự kiện đang diễn ra!</h2>
                <?php endif; ?>
                
                <div class="row justify-content-center">
                    <div class="col-6 col-md-3">
                        <div class="countdown-box">
                            <div id="countdown-days" class="countdown-number">00</div>
                            <div class="countdown-label">Ngày</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="countdown-box">
                            <div id="countdown-hours" class="countdown-number">00</div>
                            <div class="countdown-label">Giờ</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="countdown-box">
                            <div id="countdown-minutes" class="countdown-number">00</div>
                            <div class="countdown-label">Phút</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="countdown-box">
                            <div id="countdown-seconds" class="countdown-number">00</div>
                            <div class="countdown-label">Giây</div>
                        </div>
                    </div>
                </div>
                
                <?php if($currentTime < $eventDateTime): ?>
                    <div class="text-center mt-4">
                        <p class="text-white mb-3">
                            Sự kiện sẽ diễn ra vào ngày <?= date('d/m/Y', strtotime($job_fair_event['ngay_to_chuc'])) ?>
                            từ <?= date('H:i', strtotime($job_fair_event['gio_bat_dau'])) ?> 
                            đến <?= date('H:i', strtotime($job_fair_event['gio_ket_thuc'])) ?>
                        </p>
                        <a href="<?= site_url('su-kien/chi-tiet/' . $job_fair_event['slug']) ?>" class="btn btn-light btn-lg">
                            <i class="fas fa-info-circle me-2"></i>Xem chi tiết sự kiện
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center mt-4">
                        <p class="text-warning h4 mb-3">
                            <i class="fas fa-broadcast-tower me-2"></i>Sự kiện đang diễn ra!
                        </p>
                        <p class="text-white mb-3">
                            Thời gian: <?= date('H:i', strtotime($job_fair_event['gio_bat_dau'])) ?> 
                            - <?= date('H:i', strtotime($job_fair_event['gio_ket_thuc'])) ?>
                        </p>
                        <a href="<?= site_url('su-kien/chi-tiet/' . $job_fair_event['slug']) ?>" class="btn btn-warning btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>Tham gia ngay
                        </a>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <h2 class="countdown-title">Không có sự kiện nào sắp diễn ra</h2>
                <div class="text-center mt-4">
                    <a href="<?= site_url('su-kien') ?>" class="btn btn-light btn-lg">Xem tất cả sự kiện</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="dot-pattern" style="top: 80px; right: 8%;"></div>
        <div class="container">
            <h2 class="section-title">Tại Sao Nên Tham Gia Sự Kiện?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-box animate-on-scroll">
                        <div class="feature-icon">
                            <i class="lni lni-network"></i>
                        </div>
                        <h4 class="mb-3">Kết nối với doanh nghiệp</h4>
                        <p>Gặp gỡ và trao đổi trực tiếp với đại diện từ các doanh nghiệp hàng đầu trong lĩnh vực tài chính, ngân hàng và công nghệ.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box animate-on-scroll">
                        <div class="feature-icon">
                            <i class="lni lni-graduation"></i>
                        </div>
                        <h4 class="mb-3">Phát triển kiến thức</h4>
                        <p>Tham gia các workshop và hội thảo chuyên sâu để cập nhật kiến thức mới nhất về ngành tài chính - ngân hàng.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box animate-on-scroll">
                        <div class="feature-icon">
                            <i class="lni lni-briefcase"></i>
                        </div>
                        <h4 class="mb-3">Cơ hội việc làm</h4>
                        <p>Khám phá hàng trăm vị trí tuyển dụng phù hợp và có cơ hội phỏng vấn trực tiếp ngay tại sự kiện.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Speakers Section -->
    <section class="speakers-section">
        <div class="dot-pattern" style="bottom: 100px; left: 10%;"></div>
        <div class="container">
            <h2 class="section-title">Diễn Giả Nổi Bật</h2>
            <div class="row">
                <?php foreach ($speakers as $speaker): ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="speaker-card animate-on-scroll">
                        <img src="<?= base_url($speaker['image']) ?>" alt="<?= $speaker['name'] ?>" class="speaker-image">
                        <h5><?= $speaker['name'] ?></h5>
                        <p class="text-muted"><?= $speaker['position'] ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Registration Section -->
    <section class="registration-section" id="registration">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="registration-form">
                        <h2 class="text-center mb-4">Đăng Ký Tham Gia</h2>
                        <form action="<?= site_url('su-kien/register') ?>" method="post">
                            <input type="hidden" name="nguoi_dung_id" value="<?= rand(1000, 9999) ?>"> <!-- Giả lập ID người dùng -->
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ho_ten" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" id="ho_ten" name="ho_ten" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="so_dien_thoai" class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nguoi_dung_id" class="form-label">Mã số sinh viên (nếu có)</label>
                                    <input type="text" class="form-control" id="nguoi_dung_id" name="nguoi_dung_id">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="su_kien_id" class="form-label">Sự kiện tham gia</label>
                                <select class="form-select" id="su_kien_id" name="su_kien_id" required>
                                    <option value="" selected disabled>Chọn sự kiện</option>
                                    <?php foreach ($upcoming_events as $event): ?>
                                    <option value="<?= $event['su_kien_id'] ?>"><?= $event['ten_su_kien'] ?> (<?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="noi_dung_gop_y" class="form-label">Góp ý/Câu hỏi (nếu có)</label>
                                <textarea class="form-control" id="noi_dung_gop_y" name="noi_dung_gop_y" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="nguon_gioi_thieu" class="form-label">Bạn biết đến sự kiện này từ đâu?</label>
                                <select class="form-select" id="nguon_gioi_thieu" name="nguon_gioi_thieu">
                                    <option value="Website trường">Website trường</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Email từ trường">Email từ trường</option>
                                    <option value="Bạn bè">Bạn bè</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 btn-lg">Đăng ký ngay</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Countdown Timer
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($job_fair_event) && !empty($job_fair_event)): ?>
        // Lấy thời gian bắt đầu và kết thúc sự kiện
        const eventStartDate = new Date("<?= date('Y-m-d', strtotime($job_fair_event['ngay_to_chuc'])) ?>T<?= $job_fair_event['gio_bat_dau'] ?>");
        const eventEndDate = new Date("<?= date('Y-m-d', strtotime($job_fair_event['ngay_to_chuc'])) ?>T<?= $job_fair_event['gio_ket_thuc'] ?>");
        
        // Cập nhật đếm ngược mỗi 1 giây
        const countdownTimer = setInterval(function() {
            // Lấy thời gian hiện tại
            const now = new Date().getTime();
            
            // Tính khoảng cách đến thời gian bắt đầu hoặc kết thúc
            const distance = now < eventStartDate.getTime() 
                ? eventStartDate.getTime() - now 
                : eventEndDate.getTime() - now;
            
            // Kiểm tra trạng thái sự kiện
            if (now < eventStartDate.getTime()) {
                // Sự kiện chưa diễn ra - đếm ngược đến thời gian bắt đầu
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                // Hiển thị kết quả
                document.getElementById("countdown-days").innerHTML = days < 10 ? "0" + days : days;
                document.getElementById("countdown-hours").innerHTML = hours < 10 ? "0" + hours : hours;
                document.getElementById("countdown-minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
                document.getElementById("countdown-seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;
            } else if (now <= eventEndDate.getTime()) {
                // Sự kiện đang diễn ra - đếm ngược đến thời gian kết thúc
                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                document.getElementById("countdown-days").innerHTML = "00";
                document.getElementById("countdown-hours").innerHTML = hours < 10 ? "0" + hours : hours;
                document.getElementById("countdown-minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
                document.getElementById("countdown-seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;
            } else {
                // Sự kiện đã kết thúc - tải lại trang để hiển thị sự kiện tiếp theo
                clearInterval(countdownTimer);
                window.location.reload();
            }
        }, 1000);
        <?php endif; ?>
    });
</script>
<?= $this->endSection() ?> 