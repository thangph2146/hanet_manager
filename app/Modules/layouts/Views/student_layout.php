<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Hệ thống Quản lý Sự kiện Sinh viên</title>
    
    <!-- CSS chung -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.min.css') ?>">
    
    <!-- CSS tùy chỉnh -->
    <?= $this->renderSection('css') ?>
</head>

<body class="main-body">
    <!-- Header -->
    <header class="main-header">
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="<?= site_url('students/dashboard') ?>">
                    <i class="fas fa-graduation-cap"></i> ĐH CÔNG NGHỆ SÀI GÒN
                </a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('students/dashboard') ?>">
                                <i class="fas fa-home"></i> Trang chủ
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('students/events') ?>">
                                <i class="fas fa-calendar-alt"></i> Sự kiện
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('students/events/registered') ?>">
                                <i class="fas fa-check-circle"></i> Đã đăng ký
                            </a>
                        </li>
                    </ul>
                    
                    <div class="d-flex align-items-center">
                        <!-- Thông báo -->
                        <div class="dropdown me-3">
                            <a class="text-reset dropdown-toggle hidden-arrow" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell fa-lg text-white"></i>
                                <span class="badge rounded-pill badge-notification bg-danger">
                                    <?= $notification_count ?? 0 ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                                <li><a class="dropdown-item text-center" href="#">Thông báo mới</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <?php if (isset($recent_notifications) && is_array($recent_notifications)) : ?>
                                    <?php foreach ($recent_notifications as $notification) : ?>
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" href="#">
                                                <div class="me-3">
                                                    <i class="<?= $notification['icon'] ?? 'fas fa-bell' ?> text-<?= $notification['type'] ?? 'primary' ?>"></i>
                                                </div>
                                                <div>
                                                    <div class="small text-muted"><?= $notification['time'] ?? '' ?></div>
                                                    <p class="mb-0"><?= $notification['content'] ?? '' ?></p>
                                                </div>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <li><a class="dropdown-item" href="#">Không có thông báo mới</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item text-center" href="#">Xem tất cả</a></li>
                            </ul>
                        </div>
                        
                        <!-- User -->
                        <div class="dropdown">
                            <a class="dropdown-toggle d-flex align-items-center hidden-arrow text-white text-decoration-none" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="<?= base_url('assets/img/avatar/default.jpg') ?>" class="rounded-circle me-2" height="25" alt="" loading="lazy" />
                                <span><?= $student_data['fullname'] ?? 'Sinh viên' ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?= site_url('students/profile') ?>">Thông tin cá nhân</a></li>
                                <li><a class="dropdown-item" href="<?= site_url('students/settings') ?>">Cài đặt</a></li>
                                <li><hr class="dropdown-divider" /></li>
                                <li><a class="dropdown-item" href="<?= site_url('auth/logout') ?>">Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    
    <!-- Main content -->
    <main class="main-content mt-4 mb-5">
        <div class="container">
            <?= $this->renderSection('content') ?>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="main-footer bg-primary text-white py-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date('Y') ?> Trường Đại học Công nghệ Sài Gòn. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    
    <!-- JavaScript tùy chỉnh -->
    <?= $this->renderSection('js') ?>
</body>
</html> 