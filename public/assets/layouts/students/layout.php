<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hệ thống quản lý sinh viên' ?></title>
    <meta name="description" content="<?= $meta_description ?? 'Hệ thống quản lý sinh viên - Cổng thông tin dành cho sinh viên' ?>">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts - Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?= base_url('assets/css/students-style.css') ?>" rel="stylesheet">
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <header class="app-header">
            <button class="mobile-sidebar-toggle d-md-none" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
            <button class="sidebar-toggle d-none d-md-flex" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <a href="<?= base_url('students/dashboard') ?>" class="header-brand">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" width="30" height="30" class="me-2">
                <span>HUB StuCMS</span>
            </a>
            
            <div class="ms-auto d-flex align-items-center">
                <!-- Notifications -->
                <div class="dropdown me-3">
                    <button class="btn btn-link text-secondary position-relative p-1" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-bell fs-5"></i>
                        <?php if (isset($notification_count) && $notification_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $notification_count > 9 ? '9+' : $notification_count ?>
                        </span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow-sm" style="width: 300px;">
                        <h6 class="dropdown-header">Thông báo</h6>
                        <div class="dropdown-divider"></div>
                        
                        <?php if (isset($recent_notifications) && count($recent_notifications) > 0): ?>
                            <?php foreach(array_slice($recent_notifications, 0, 3) as $notification): ?>
                            <a href="#" class="dropdown-item d-flex align-items-center p-2">
                                <div class="flex-shrink-0">
                                    <div class="notification-icon bg-light-<?= $notification['type'] ?>">
                                        <i class="fas <?= str_replace('bx ', 'fa-', $notification['icon']) ?>"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0 small"><?= $notification['title'] ?></p>
                                    <p class="text-muted mb-0 x-small"><?= $notification['time'] ?></p>
                                </div>
                            </a>
                            <?php endforeach; ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?= base_url('students/notifications') ?>" class="dropdown-item text-center small">Xem tất cả thông báo</a>
                        <?php else: ?>
                            <p class="text-muted small text-center my-2">Không có thông báo mới</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="dropdown">
                    <button class="btn btn-link p-0 text-dark" type="button" data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center">
                            <div class="avatar-container me-2">
                                <?php if (isset($student_data) && !empty($student_data['picture'])): ?>
                                    <img src="<?= base_url($student_data['picture']) ?>" alt="Profile" class="rounded-circle" width="32" height="32">
                                <?php else: ?>
                                    <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <?= substr(isset($student_data['fullname']) ? $student_data['fullname'] : 'U', 0, 1) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <span class="d-none d-md-block"><?= isset($student_data) ? $student_data['fullname'] : 'Sinh viên' ?></span>
                            <i class="fas fa-chevron-down ms-1 small"></i>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="<?= base_url('students/profile') ?>"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('students/settings') ?>"><i class="fas fa-cog me-2"></i> Cài đặt</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('students/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </header>
        
        <!-- Main Wrapper -->
        <div class="main-wrapper">
            <!-- Sidebar -->
            <aside class="sidebar" id="sidebar">
                <!-- Sidebar User -->
                <div class="sidebar-user">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-container me-3">
                            <?php if (isset($student_data) && !empty($student_data['picture'])): ?>
                                <img src="<?= base_url($student_data['picture']) ?>" alt="Profile" class="rounded-circle" width="45" height="45">
                            <?php else: ?>
                                <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                    <?= substr(isset($student_data['fullname']) ? $student_data['fullname'] : 'U', 0, 1) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="user-info">
                            <h6 class="mb-0"><?= isset($student_data) ? $student_data['fullname'] : 'Sinh viên' ?></h6>
                            <p class="text-muted mb-0 small"><?= isset($student_data) ? $student_data['student_id'] : 'MSSV: ---' ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar Nav -->
                <ul class="sidebar-nav">
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/dashboard') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/dashboard') ? 'active' : '' ?>">
                            <i class="fas fa-home sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Trang chủ</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/events') ?>" class="sidebar-nav-link <?= strpos(current_url(), base_url('students/events')) !== false ? 'active' : '' ?>">
                            <i class="fas fa-calendar-alt sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Sự kiện</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/events/registered') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/events/registered') ? 'active' : '' ?>">
                            <i class="fas fa-check-circle sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Sự kiện đã đăng ký</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/courses') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/courses') ? 'active' : '' ?>">
                            <i class="fas fa-book sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Khóa học</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/schedules') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/schedules') ? 'active' : '' ?>">
                            <i class="fas fa-calendar sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Lịch học</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/exams') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/exams') ? 'active' : '' ?>">
                            <i class="fas fa-file-alt sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Lịch thi</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/grades') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/grades') ? 'active' : '' ?>">
                            <i class="fas fa-chart-bar sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Điểm số</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/fees') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/fees') ? 'active' : '' ?>">
                            <i class="fas fa-credit-card sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Học phí</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/certificates') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/certificates') ? 'active' : '' ?>">
                            <i class="fas fa-certificate sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Chứng chỉ</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/settings') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/settings') ? 'active' : '' ?>">
                            <i class="fas fa-cog sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Cài đặt</span>
                        </a>
                    </li>
                    
                    <li class="sidebar-nav-item">
                        <a href="<?= base_url('students/help') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/help') ? 'active' : '' ?>">
                            <i class="fas fa-question-circle sidebar-nav-icon"></i>
                            <span class="sidebar-nav-text">Trợ giúp</span>
                        </a>
                    </li>
                </ul>
            </aside>
            
            <!-- Main Content -->
            <main class="main-content">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
        
        <!-- Footer -->
        <footer class="app-footer text-center">
            <p class="mb-0">© <?= date('Y') ?> HUB Student Management System - Được phát triển bởi <strong>HUB University</strong></p>
        </footer>
        
        <!-- Toast Container -->
        <div id="toast-container" class="position-fixed bottom-0 end-0 p-3" style="z-index: 1050;"></div>
    </div>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Main JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
            });
        }
        
        if (mobileMenuToggle) {
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('mobile-open');
            });
        }
        
        // Tooltips for collapsed sidebar
        const sidebarNavLinks = document.querySelectorAll('.sidebar-nav-link');
        
        function showTooltip(e) {
            if (!sidebar.classList.contains('collapsed')) return;
            
            const tooltipText = e.currentTarget.querySelector('.sidebar-nav-text').textContent;
            
            const tooltip = document.createElement('div');
            tooltip.className = 'sidebar-tooltip';
            tooltip.textContent = tooltipText;
            tooltip.style.left = `${e.currentTarget.offsetWidth + 10}px`;
            tooltip.style.top = `${e.currentTarget.offsetTop + e.currentTarget.offsetHeight / 2 - 15}px`;
            
            sidebar.appendChild(tooltip);
        }
        
        function hideTooltip() {
            const tooltips = document.querySelectorAll('.sidebar-tooltip');
            tooltips.forEach(t => t.remove());
        }
        
        sidebarNavLinks.forEach(link => {
            link.addEventListener('mouseenter', showTooltip);
            link.addEventListener('mouseleave', hideTooltip);
        });
        
        // Handle outside click on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 768 && 
                sidebar.classList.contains('mobile-open') && 
                !sidebar.contains(e.target) && 
                e.target !== mobileMenuToggle) {
                sidebar.classList.remove('mobile-open');
            }
        });
    });
    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html> 