<?php
$notifications = [
    [
        'icon' => 'bell',
        'bg' => 'primary',
        'title' => 'Sự kiện mới đã được thêm',
        'text' => 'Hội thảo công nghệ 2023',
        'time' => '30 phút trước'
    ],
    [
        'icon' => 'check-circle',
        'bg' => 'success', 
        'title' => 'Đăng ký thành công',
        'text' => 'Bạn đã đăng ký thành công vào Workshop Kỹ năng mềm',
        'time' => '2 giờ trước'
    ]
];
?>

<!-- Link CSS file -->
<link rel="stylesheet" href="<?= base_url('assets/css/student/components/header.css') ?>">

<nav class="content-navbar">
    <!-- Sidebar Toggle -->
    <button class="nav-action-btn sidebar-toggle-btn" id="sidebar-toggle" aria-label="Toggle Sidebar">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Search Bar -->
    <div class="nav-search">
        <input type="text" placeholder="Tìm kiếm (Ctrl + /)" aria-label="Search">
        <i class="fas fa-search"></i>
    </div>
    
    <!-- Mobile Search Button -->
    <button class="nav-action-btn mobile-search-btn d-lg-none" aria-label="Mobile Search">
        <i class="fas fa-search"></i>
    </button>
    
    <!-- Action Buttons -->
    <div class="nav-actions">
        <!-- Star Button -->
        <button class="nav-action-btn star-btn" aria-label="Starred Items">
            <i class="far fa-star"></i>
            <span class="badge">32</span>
        </button>
        
        <!-- Notifications Dropdown -->
        <div class="dropdown">
            <button class="nav-action-btn" id="notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                <i class="far fa-bell"></i>
                <span class="badge"><?= count($notifications) ?></span>
            </button>
            
            <div class="dropdown-menu notification-dropdown" aria-labelledby="notifications-dropdown">
                <div class="dropdown-header">
                    <h6 class="mb-0">Thông báo</h6>
                    <span class="badge bg-primary"><?= count($notifications) ?> Mới</span>
                </div>
                
                <?php foreach($notifications as $notification): ?>
                <div class="notification-item">
                    <div class="notification-icon bg-<?= $notification['bg'] ?>">
                        <i class="fas fa-<?= $notification['icon'] ?>"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title"><?= $notification['title'] ?></div>
                        <div class="notification-text"><?= $notification['text'] ?></div>
                        <div class="notification-time">
                            <i class="far fa-clock"></i>
                            <?= $notification['time'] ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <a href="<?= base_url('student/notifications') ?>" class="dropdown-item text-center py-2">
                    Xem tất cả thông báo
                </a>
            </div>
        </div>
        
        <!-- User Dropdown -->
        <div class="dropdown">
            <a href="#" class="user-dropdown" id="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?= base_url('assets/images/avatars/default.jpg') ?>" alt="User Avatar">
                <div class="user-info d-none d-md-block">
                    <div class="user-name">John Doe</div>
                    <div class="user-role">Admin</div>
                </div>
            </a>
            
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="user-dropdown">
                <div class="dropdown-header">
                    <div class="d-flex align-items-center">
                        <img src="<?= base_url('assets/images/avatars/default.jpg') ?>" alt="User Avatar" class="me-2" style="width: 32px; height: 32px; border-radius: 8px;">
                        <div>
                            <div class="fw-bold">John Doe</div>
                            <small class="text-muted">Admin</small>
                        </div>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center gap-2" href="<?= base_url('student/profile') ?>">
                    <i class="fas fa-user"></i> Hồ sơ của tôi
                </a>
                <a class="dropdown-item d-flex align-items-center gap-2" href="<?= base_url('student/settings') ?>">
                    <i class="fas fa-cog"></i> Cài đặt
                </a>
                <a class="dropdown-item d-flex align-items-center justify-content-between" href="<?= base_url('student/billing') ?>">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-file-invoice"></i> Thanh toán
                    </div>
                    <span class="badge bg-danger">4</span>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="<?= base_url('auth/logout') ?>">
                    <i class="fas fa-power-off"></i> Đăng xuất
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Search Overlay -->
<div class="mobile-search" id="mobile-search">
    <div class="mobile-search-header">
        <h5 class="mb-0">Tìm kiếm</h5>
        <button class="mobile-search-close" id="mobile-search-close" aria-label="Close Search">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <input type="text" class="form-control" placeholder="Tìm kiếm (Ctrl + /)" aria-label="Mobile Search">
    <div class="mobile-search-results"></div>
</div>

<!-- Link JS file -->
<script src="<?= base_url('assets/js/student/components/header.js') ?>"></script> 