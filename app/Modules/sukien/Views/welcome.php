<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Thống Quản Lý Sự Kiện - Đại học Ngân hàng TP.HCM</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- AOS Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('app/Modules/sukien/Assets/css/welcome.css') ?>">
</head>
<body>
    <!-- Top Header -->
    <div class="top-header d-none d-md-block">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <a href="mailto:info@hub.edu.vn"><i class="fas fa-envelope me-2"></i>info@hub.edu.vn</a>
                    <a href="tel:02838291901" class="ms-3"><i class="fas fa-phone me-2"></i>(028) 3829 1901</a>
                </div>
                <div class="col-md-6 text-end">
                    <span><i class="fas fa-map-marker-alt me-2"></i>36 Tôn Thất Đạm, Quận 1, TP.HCM</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="https://hub.edu.vn/wp-content/uploads/2023/07/logo.svg" alt="Logo ĐH Ngân Hàng TP.HCM">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Trang Chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sự Kiện</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Lịch Trình</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Thông Báo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Liên Hệ</a>
                    </li>
                    <li class="nav-item ms-2">
                        <a class="btn btn-hub-secondary" href="/account/login">Đăng Nhập</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1 class="display-4 fw-bold mb-4">Hệ Thống Quản Lý<br>Sự Kiện HUB</h1>
                    <p class="lead mb-4">Giải pháp quản lý sự kiện toàn diện của Trường Đại học Ngân hàng TP.HCM, giúp bạn dễ dàng tham gia và checkin các sự kiện của trường.</p>
                    <div class="d-flex gap-3 btn-group">
                        <a href="/account/login" class="btn btn-hub-outline">Đăng Nhập</a>
                        <a href="/account/register" class="btn btn-hub-secondary">Đăng Ký Tham Gia</a>
                    </div>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left">
                    <div class="hero-image">
                        <div class="floating-shape shape-1"></div>
                        <div class="floating-shape shape-2"></div>
                        <img src="https://placehold.co/600x400/00366e/ffffff?text=HUB+Event+Management" alt="HUB Event Management" class="img-fluid rounded-3 shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events Section -->
    <section class="event-section">
        <div class="container">
            <h2 class="section-heading" data-aos="fade-up">Sự Kiện Sắp Diễn Ra</h2>
            <div class="row">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="event-card">
                        <div class="event-image">
                            <img src="https://placehold.co/600x400/f8c301/00366e?text=HUB+Event" alt="Event 1">
                        </div>
                        <div class="event-details">
                            <div class="event-tag">Hội thảo</div>
                            <div class="event-date">
                                <i class="far fa-calendar-alt"></i> 25 Tháng 7, 2023
                            </div>
                            <h3 class="event-title">Hội Thảo Khoa Học Ngân Hàng</h3>
                            <p class="event-description">Hội thảo khoa học về các xu hướng mới trong lĩnh vực tài chính ngân hàng toàn cầu.</p>
                            <a href="#" class="btn btn-hub-primary btn-event">Đăng Ký Tham Gia</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="event-card">
                        <div class="event-image">
                            <img src="https://placehold.co/600x400/00366e/f8c301?text=HUB+Event" alt="Event 2">
                        </div>
                        <div class="event-details">
                            <div class="event-tag">Tuyển dụng</div>
                            <div class="event-date">
                                <i class="far fa-calendar-alt"></i> 30 Tháng 7, 2023
                            </div>
                            <h3 class="event-title">Ngày Hội Việc Làm 2023</h3>
                            <p class="event-description">Cơ hội kết nối với hơn 50 doanh nghiệp hàng đầu trong lĩnh vực tài chính.</p>
                            <a href="#" class="btn btn-hub-primary btn-event">Đăng Ký Tham Gia</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="event-card">
                        <div class="event-image">
                            <img src="https://placehold.co/600x400/c10000/ffffff?text=HUB+Event" alt="Event 3">
                        </div>
                        <div class="event-details">
                            <div class="event-tag">Lễ khai giảng</div>
                            <div class="event-date">
                                <i class="far fa-calendar-alt"></i> 10 Tháng 8, 2023
                            </div>
                            <h3 class="event-title">Lễ Khai Giảng Năm Học Mới</h3>
                            <p class="event-description">Chào đón tân sinh viên khóa 2023 và khai giảng năm học mới.</p>
                            <a href="#" class="btn btn-hub-primary btn-event">Đăng Ký Tham Gia</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title" data-aos="fade-up">Tính Năng Nổi Bật</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Hệ thống quản lý sự kiện thông minh của Trường Đại học Ngân hàng TP.HCM</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-qrcode"></i>
                        </div>
                        <h3>Checkin Nhanh Chóng</h3>
                        <p>Checkin tự động bằng mã QR, giúp tiết kiệm thời gian và tăng trải nghiệm người dùng. Không cần đợi xếp hàng, chỉ với một lần quét.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h3>Đăng Ký Sự Kiện</h3>
                        <p>Đăng ký tham gia sự kiện trực tuyến dễ dàng, nhận thông báo và lời nhắc tự động qua email và tin nhắn.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <h3>Chứng Chỉ Điện Tử</h3>
                        <p>Nhận chứng chỉ tham dự điện tử sau khi hoàn thành sự kiện một cách nhanh chóng, tiết kiệm và thân thiện với môi trường.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-content">
                <div class="row g-4">
                    <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="100">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="stat-number">100+</div>
                            <div class="stat-label">Sự Kiện</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="200">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="stat-number">15000+</div>
                            <div class="stat-label">Sinh Viên</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="300">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                            <div class="stat-number">500+</div>
                            <div class="stat-label">Đối Tác</div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6" data-aos="zoom-in" data-aos-delay="400">
                        <div class="stat-item">
                            <div class="stat-icon">
                                <i class="fas fa-thumbs-up"></i>
                            </div>
                            <div class="stat-number">98%</div>
                            <div class="stat-label">Hài Lòng</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Partner Section -->
    <section class="partner-section">
        <div class="container">
            <h2 class="section-heading" data-aos="fade-up">Đối Tác</h2>
            <div class="row align-items-center">
                <div class="col-md-2 col-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="partner-logo">
                        <img src="https://placehold.co/150x80/eeeeee/555555?text=Logo+1" alt="Partner 1" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-2 col-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="partner-logo">
                        <img src="https://placehold.co/150x80/eeeeee/555555?text=Logo+2" alt="Partner 2" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-2 col-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="partner-logo">
                        <img src="https://placehold.co/150x80/eeeeee/555555?text=Logo+3" alt="Partner 3" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-2 col-4" data-aos="fade-up" data-aos-delay="250">
                    <div class="partner-logo">
                        <img src="https://placehold.co/150x80/eeeeee/555555?text=Logo+4" alt="Partner 4" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-2 col-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="partner-logo">
                        <img src="https://placehold.co/150x80/eeeeee/555555?text=Logo+5" alt="Partner 5" class="img-fluid">
                    </div>
                </div>
                <div class="col-md-2 col-4" data-aos="fade-up" data-aos-delay="350">
                    <div class="partner-logo">
                        <img src="https://placehold.co/150x80/eeeeee/555555?text=Logo+6" alt="Partner 6" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <h5>Về ĐH Ngân Hàng TP.HCM</h5>
                    <p>Trường Đại học Ngân hàng TP.HCM là trường đại học công lập trực thuộc Ngân hàng Nhà nước Việt Nam, đào tạo nguồn nhân lực chất lượng cao trong lĩnh vực tài chính, ngân hàng.</p>
                    <div class="social-icons mt-3">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5>Liên Kết Nhanh</h5>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Trang chủ</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Về chúng tôi</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Sự kiện</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Tin tức</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Liên hệ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5>Sự Kiện</h5>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Lịch sự kiện</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Hội thảo khoa học</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Hoạt động sinh viên</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Tuyển dụng</a></li>
                        <li><a href="#"><i class="fas fa-chevron-right me-2"></i>Sự kiện quốc tế</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5>Liên Hệ</h5>
                    <ul class="list-unstyled">
                        <li class="footer-contact-item">
                            <div class="footer-contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>36 Tôn Thất Đạm, Quận 1, TP.HCM</div>
                        </li>
                        <li class="footer-contact-item">
                            <div class="footer-contact-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>(028) 3829 1901</div>
                        </li>
                        <li class="footer-contact-item">
                            <div class="footer-contact-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>info@hub.edu.vn</div>
                        </li>
                        <li class="footer-contact-item">
                            <div class="footer-contact-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>Thứ Hai - Thứ Sáu: 7:30 - 17:00</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom mt-5">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p class="mb-0">&copy; <?= date('Y') ?> Trường Đại học Ngân hàng TP.HCM. Tất cả quyền được bảo lưu.</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="fas fa-arrow-up"></i></a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Custom JS -->
    <script src="<?= base_url('app/Modules/sukien/Assets/js/welcome.js') ?>"></script>
</body>
</html>
