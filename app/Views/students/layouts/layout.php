<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hệ thống quản lý sinh viên' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/layouts/students/css/main.css') ?>">
    
    <!-- Component CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/layouts/students/css/components/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layouts/students/css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layouts/students/css/components/footer.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layouts/students/css/components/notification.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layouts/students/css/components/modal.css') ?>">
    
    <!-- Page-specific CSS -->
    <?php if (isset($page_css) && !empty($page_css)): ?>
        <?php foreach ($page_css as $css): ?>
        <link rel="stylesheet" href="<?= base_url('assets/layouts/students/css/pages/' . $css . '.css') ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Additional styles -->
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="st-header">
            <div class="st-header-logo">
                <div class="st-menu-toggle">
                    <i class="fas fa-bars"></i>
                </div>
                <a href="<?= base_url('students/dashboard') ?>" class="st-logo">
                    <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo">
                </a>
                <div class="st-header-title">
                    <span>Hệ thống sinh viên</span>
                </div>
            </div>
            
            <div class="st-header-right">
                <div class="st-notification">
                    <button class="st-notification-btn">
                        <i class="far fa-bell"></i>
                        <span class="st-notification-badge">5</span>
                    </button>
                </div>
                
                <div class="st-user-info">
                    <div class="st-user-name"><?= session()->get('name') ?? 'Sinh viên' ?></div>
                    <div class="st-user-avatar">
                        <?php if (session()->has('avatar') && !empty(session()->get('avatar'))): ?>
                            <img src="<?= base_url(session()->get('avatar')) ?>" alt="Avatar">
                        <?php else: ?>
                            <div class="st-avatar-text"><?= substr(session()->get('name') ?? 'User', 0, 1) ?></div>
                        <?php endif; ?>
                        <div class="st-user-dropdown">
                            <a href="<?= base_url('students/profile') ?>" class="st-dropdown-item">
                                <i class="fas fa-user"></i> Hồ sơ
                            </a>
                            <a href="<?= base_url('students/settings') ?>" class="st-dropdown-item">
                                <i class="fas fa-cog"></i> Cài đặt
                            </a>
                            <a href="<?= base_url('logout') ?>" class="st-dropdown-item">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Sidebar -->
        <aside class="st-sidebar">
            <div class="st-sidebar-overlay"></div>
            <div class="st-sidebar-content">
                <div class="st-sidebar-profile">
                    <div class="st-profile-avatar">
                        <?php if (session()->has('avatar') && !empty(session()->get('avatar'))): ?>
                            <img src="<?= base_url(session()->get('avatar')) ?>" alt="Avatar">
                        <?php else: ?>
                            <div class="st-avatar-text"><?= substr(session()->get('name') ?? 'User', 0, 1) ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="st-profile-info">
                        <div class="st-profile-name"><?= session()->get('name') ?? 'Sinh viên' ?></div>
                        <div class="st-profile-role"><?= session()->get('student_id') ?? 'MSSV' ?></div>
                    </div>
                </div>
                
                <nav class="st-sidebar-menu">
                    <ul>
                        <li>
                            <a href="<?= base_url('students/dashboard') ?>" class="<?= uri_string() == 'students/dashboard' ? 'active' : '' ?>">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('students/events') ?>" class="<?= str_contains(uri_string(), 'students/events') ? 'active' : '' ?>">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Sự kiện</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('students/certificates') ?>" class="<?= str_contains(uri_string(), 'students/certificates') ? 'active' : '' ?>">
                                <i class="fas fa-certificate"></i>
                                <span>Chứng chỉ</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('students/reports') ?>" class="<?= str_contains(uri_string(), 'students/reports') ? 'active' : '' ?>">
                                <i class="fas fa-chart-pie"></i>
                                <span>Báo cáo</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('students/profile') ?>" class="<?= uri_string() == 'students/profile' ? 'active' : '' ?>">
                                <i class="fas fa-user"></i>
                                <span>Hồ sơ</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('students/settings') ?>" class="<?= uri_string() == 'students/settings' ? 'active' : '' ?>">
                                <i class="fas fa-cog"></i>
                                <span>Cài đặt</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        
        <!-- Main Content -->
        <main class="st-main-content">
            <?= $this->renderSection('content') ?>
        </main>
        
        <!-- Footer -->
        <footer class="st-footer">
            <div class="st-footer-content">
                <div class="st-copyright">
                    &copy; <?= date('Y') ?> Hệ thống quản lý sinh viên. Bản quyền thuộc về Trường.
                </div>
                <div class="st-footer-links">
                    <a href="#">Chính sách</a>
                    <a href="#">Điều khoản</a>
                    <a href="#">Liên hệ</a>
                </div>
            </div>
        </footer>
    </div>
    
    <!-- Notification container -->
    <div class="notification-container" id="notification-container"></div>
    
    <!-- Loading overlay -->
    <div class="loading-overlay" id="loading-overlay">
        <div class="loading-spinner"></div>
    </div>
    
    <!-- Main JS -->
    <script src="<?= base_url('assets/layouts/students/js/components/notification.js') ?>"></script>
    <script src="<?= base_url('assets/layouts/students/js/components/modal.js') ?>"></script>
    <script src="<?= base_url('assets/layouts/students/js/layout.js') ?>"></script>
    
    <!-- Page-specific JS -->
    <?php if (isset($page_js) && !empty($page_js)): ?>
        <?php foreach ($page_js as $js): ?>
        <script src="<?= base_url('assets/layouts/students/js/pages/' . $js . '.js') ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Additional scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html> 