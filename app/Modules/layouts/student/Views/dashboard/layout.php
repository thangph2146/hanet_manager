<!doctype html>
<html lang="vi">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="<?= site_url('assets/images/favicon-32x32.png') ?>" type="image/png" />
    <!--plugins-->
    <link href="<?= site_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
    <link href="<?= site_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
    <link href="<?= site_url('assets/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet" />
    <!-- loader-->
    <link href="<?= site_url('assets/css/pace.min.css') ?>" rel="stylesheet" />
    <script src="<?= site_url('assets/js/pace.min.js') ?>"></script>
    <!-- Bootstrap CSS -->
    <link href="<?= site_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/app.css') ?>" rel="stylesheet">
    <link href="<?= site_url('assets/css/icons.css') ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="<?= site_url('assets/css/dark-theme.css') ?>" />
    <link rel="stylesheet" href="<?= site_url('assets/css/semi-dark.css') ?>" />
    <link rel="stylesheet" href="<?= site_url('assets/css/header-colors.css') ?>" />
    <?= $this->renderSection("styles") ?>
    <title><?= $title ?? 'Dashboard Sinh viên' ?> - Hệ thống Đăng ký Sự kiện ĐH Ngân hàng TP.HCM</title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="<?= site_url('assets/modules/images/hub-logo.png') ?>" class="logo-icon" alt="logo icon" style="width: 40px;">
                </div>
                <div>
                    <h4 class="logo-text">BUH Events</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <a href="<?= base_url('students/dashboard') ?>">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Trang chủ</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('students/events') ?>">
                        <div class="parent-icon"><i class='bx bx-calendar-event'></i>
                        </div>
                        <div class="menu-title">Sự kiện</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('students/my-registrations') ?>">
                        <div class="parent-icon"><i class='bx bx-list-check'></i>
                        </div>
                        <div class="menu-title">Đăng ký của tôi</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('students/certificates') ?>">
                        <div class="parent-icon"><i class='bx bx-certification'></i>
                        </div>
                        <div class="menu-title">Chứng chỉ</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('students/profile') ?>">
                        <div class="parent-icon"><i class='bx bx-user'></i>
                        </div>
                        <div class="menu-title">Thông tin cá nhân</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('students/change-password') ?>">
                        <div class="parent-icon"><i class='bx bx-lock'></i>
                        </div>
                        <div class="menu-title">Đổi mật khẩu</div>
                    </a>
                </li>
            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar wrapper -->
        
        <!--start header -->
        <header>
            <div class="topbar d-flex align-items-center">
                <nav class="navbar navbar-expand">
                    <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                    </div>
                    <div class="search-bar flex-grow-1">
                        <div class="position-relative search-bar-box">
                            <input type="text" class="form-control search-control" placeholder="Tìm kiếm sự kiện..."> 
                            <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                            <span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
                        </div>
                    </div>
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-bell'></i>
                                    <span class="alert-count"><?= $notification_count ?? 0 ?></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="#">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Thông báo</p>
                                            <p class="msg-header-clear ms-auto">Đánh dấu tất cả là đã đọc</p>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">
                                        <?php if (isset($notifications) && count($notifications) > 0): ?>
                                            <?php foreach ($notifications as $notification): ?>
                                                <a class="dropdown-item" href="<?= $notification['link'] ?? '#' ?>">
                                                    <div class="d-flex align-items-center">
                                                        <div class="notify bg-light-primary text-primary">
                                                            <i class="<?= $notification['icon'] ?? 'bx bx-bell' ?>"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="msg-name"><?= $notification['title'] ?></h6>
                                                            <p class="msg-info"><?= $notification['content'] ?></p>
                                                            <p class="time-info"><?= $notification['time'] ?></p>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <a class="dropdown-item" href="#">
                                                <div class="d-flex align-items-center">
                                                    <div class="notify bg-light-info text-info">
                                                        <i class="bx bx-info-circle"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="msg-name">Không có thông báo mới</h6>
                                                        <p class="msg-info">Bạn không có thông báo nào</p>
                                                    </div>
                                                </div>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">Xem tất cả thông báo</div>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (isset($student_data['picture']) && !empty($student_data['picture'])): ?>
                                <img src="<?= $student_data['picture'] ?>" class="user-img" alt="Ảnh đại diện">
                            <?php else: ?>
                                <img src="<?= site_url('assets/images/avatars/avatar-1.png') ?>" class="user-img" alt="Ảnh đại diện">
                            <?php endif; ?>
                            <div class="user-info ps-3">
                                <p class="user-name mb-0"><?= $student_data['fullname'] ?? session()->get('student_name') ?? 'Sinh viên' ?></p>
                                <p class="designattion mb-0"><?= $student_data['student_id'] ?? session()->get('student_id') ?? '' ?></p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('students/profile') ?>">
                                <i class="bx bx-user"></i><span>Thông tin cá nhân</span></a>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('students/change-password') ?>">
                                <i class="bx bx-lock"></i><span>Đổi mật khẩu</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('login/logoutstudent') ?>">
                                <i class='bx bx-log-out-circle'></i><span>Đăng xuất</span></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </header>
        <!--end header -->
        
        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                <!-- Thông báo -->
                <?php if (session()->getFlashdata('info')) : ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('info') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('warning')) : ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('warning') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Nội dung chính -->
                <?= $this->renderSection("content") ?>
            </div>
        </div>
        <!--end page wrapper -->
        
        <!--start footer -->
        <footer class="page-footer">
            <p class="mb-0">Copyright © <?= date('Y') ?>. Trường Đại học Ngân hàng TP.HCM</p>
        </footer>
        <!--end footer -->
    </div>
    <!--end wrapper-->
    
    <!-- Bootstrap JS -->
    <script src="<?= site_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <!--plugins-->
    <script src="<?= site_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
    <script src="<?= site_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
    <!--app JS-->
    <script src="<?= site_url('assets/js/app.js') ?>"></script>
    <?= $this->renderSection("scripts") ?>
</body>

</html> 