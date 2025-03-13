<!doctype html>
<html lang="en">

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
    <?= $this->renderSection("styles") ?>
    <title><?= $title ?? 'Dashboard' ?> - Hệ thống Quản lý</title>
</head>

<body>
    <!--wrapper-->
    <div class="wrapper">
        <!--sidebar wrapper -->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div>
                    <img src="<?= site_url('assets/images/logo-icon.png') ?>" class="logo-icon" alt="logo icon">
                </div>
                <div>
                    <h4 class="logo-text">Quản lý</h4>
                </div>
                <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                </div>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                <li>
                    <a href="<?= base_url('nguoidung/dashboard') ?>">
                        <div class="parent-icon"><i class='bx bx-home-circle'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('nguoidung/dashboard/profile') ?>">
                        <div class="parent-icon"><i class='bx bx-user'></i>
                        </div>
                        <div class="menu-title">Thông tin cá nhân</div>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('nguoidung/dashboard/change-password') ?>">
                        <div class="parent-icon"><i class='bx bx-lock'></i>
                        </div>
                        <div class="menu-title">Đổi mật khẩu</div>
                    </a>
                </li>
                <!-- Thêm các menu khác tại đây -->
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
                    <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class='bx bx-bell'></i>
                                    <span class="alert-count">0</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="header-notifications-list">
                                        <!-- Thông báo sẽ hiển thị ở đây -->
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="user-box dropdown">
                        <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php if (isset($userData['picture']) && !empty($userData['picture'])): ?>
                                <img src="<?= $userData['picture'] ?>" class="user-img" alt="user avatar">
                            <?php else: ?>
                                <img src="<?= site_url('assets/images/avatars/avatar-1.png') ?>" class="user-img" alt="user avatar">
                            <?php endif; ?>
                            <div class="user-info ps-3">
                                <p class="user-name mb-0"><?= $userData['fullname'] ?? 'Người dùng' ?></p>
                                <p class="designattion mb-0"><?= $userData['email'] ?? '' ?></p>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= base_url('nguoidung/dashboard/profile') ?>"><i class="bx bx-user"></i><span>Thông tin cá nhân</span></a>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('nguoidung/dashboard/change-password') ?>"><i class="bx bx-lock"></i><span>Đổi mật khẩu</span></a>
                            </li>
                            <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li>
                            <li><a class="dropdown-item" href="<?= base_url('nguoidung/login/logout') ?>"><i class='bx bx-log-out-circle'></i><span>Đăng xuất</span></a>
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
                <?php if (nguoidung_session_has('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= nguoidung_session_get('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (nguoidung_session_has('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= nguoidung_session_get('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (nguoidung_session_has('warning')): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <?= nguoidung_session_get('warning') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (nguoidung_session_has('info')): ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <?= nguoidung_session_get('info') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                
                <?= $this->renderSection("content") ?>
            </div>
        </div>
        <!--end page wrapper -->
        
        <!--start footer -->
        <footer class="page-footer">
            <p class="mb-0">Copyright © <?= date('Y') ?>. Hệ thống Quản lý.</p>
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