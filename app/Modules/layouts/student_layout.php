<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Hệ thống Quản lý Sự kiện Sinh viên</title>
    
    <!-- CSS chung -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/student.css') ?>">
    
    <!-- CSS từ các sections -->
    <?= $this->renderSection('css') ?>
    
    <style>
        :root {
            --primary: #0d6efd;
            --secondary: #6c757d;
            --success: #198754;
            --info: #0dcaf0;
            --warning: #ffc107;
            --danger: #dc3545;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 0;
            background-color: #343a40;
            overflow-x: hidden;
            z-index: 100;
        }
        
        .sidebar-sticky {
            position: sticky;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: 0.5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.75rem 1rem;
            font-weight: 500;
            border-left: 3px solid transparent;
        }
        
        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #fff;
            border-left: 3px solid var(--primary);
            background-color: rgba(13, 110, 253, 0.25);
        }
        
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
        }
        
        .student-info {
            display: flex;
            align-items: center;
            padding: 15px;
            background-color: #2c3136;
            color: white;
        }
        
        .student-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .navbar-top {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,.08);
            padding: 0.5rem 1rem;
        }
        
        .notification-badge {
            position: absolute;
            top: 0px;
            right: 0px;
            padding: 0.25rem 0.5rem;
            border-radius: 50%;
            background-color: var(--danger);
            color: white;
            font-size: 0.75rem;
        }
        
        .page-header {
            margin-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
        }
        
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <nav class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="student-info">
            <img src="<?= base_url('assets/img/avatar/default.png') ?>" alt="Student Photo">
            <div>
                <h6 class="mb-0"><?= session()->get('student_name') ?? 'Nguyễn Văn A' ?></h6>
                <small><?= session()->get('nguoi_dung_id') ?? 'SV001' ?></small>
            </div>
        </div>
        <div class="sidebar-sticky">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() == 'students/dashboard' ? 'active' : '' ?>" href="<?= base_url('students/dashboard') ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= strpos(uri_string(), 'students/events') === 0 && uri_string() != 'students/events/registered' ? 'active' : '' ?>" href="<?= base_url('students/events') ?>">
                        <i class="fas fa-calendar-alt"></i> Sự kiện
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() == 'students/events/registered' ? 'active' : '' ?>" href="<?= base_url('students/events/registered') ?>">
                        <i class="fas fa-check-circle"></i> Đã đăng ký
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() == 'students/profile' ? 'active' : '' ?>" href="<?= base_url('students/profile') ?>">
                        <i class="fas fa-user"></i> Hồ sơ cá nhân
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= uri_string() == 'students/statistics' ? 'active' : '' ?>" href="<?= base_url('students/statistics') ?>">
                        <i class="fas fa-chart-bar"></i> Thống kê
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('logout') ?>">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    
    <!-- Main content -->
    <main class="main-content">
        <!-- Top navbar -->
        <nav class="navbar navbar-top navbar-expand-md mb-4">
            <div class="container-fluid">
                <h4 class="mb-0"><?= $this->renderSection('title') ?? 'Dashboard Sinh viên' ?></h4>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown px-3">
                        <a class="nav-link position-relative" href="#" id="notificationsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <?php if(isset($notification_count) && $notification_count > 0): ?>
                            <span class="notification-badge"><?= $notification_count ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="notificationsDropdown">
                            <span class="dropdown-header">Thông báo mới</span>
                            <div class="dropdown-divider"></div>
                            <!-- Placeholder for notifications -->
                            <a class="dropdown-item text-center" href="#">Xem tất cả</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        
        <!-- Main content area -->
        <div class="container-fluid">
            <?= $this->renderSection('content') ?>
        </div>
    </main>
    
    <!-- Common JavaScript -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/student.js') ?>"></script>
    
    <!-- JavaScript from sections -->
    <?= $this->renderSection('js') ?>
</body>
</html> 