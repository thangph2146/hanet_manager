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

<?php
$userdropdown = [
    [
        'title' => 'Tài khoản', 
        'actions' => [
            [
                'icon' => 'user',
                'title' => 'Hồ sơ của tôi',
                'url' => 'students/profile'
            ],
            [
                'icon' => 'cog',
                'title' => 'Cài đặt',
                'url' => 'students/settings'
            ]
        ]
    ],
    [
        'title' => 'Thanh toán',
        'actions' => [
            [
                'icon' => 'file-invoice',
                'title' => 'Thanh toán',
                'url' => 'students/billing'
            ]
        ]
    ],
    [
        'title' => 'Đăng xuất',
        'actions' => [
            [
                'icon' => 'power-off',
                'title' => 'Đăng xuất',
                'url' => 'login/logoutstudent'
            ]
        ]
    ]
];  
?>
<!-- Link CSS file -->
<link rel="stylesheet" href="<?= base_url('assets/css/student/components/header.css') ?>">

<nav class="content-navbar">
    <!-- Sidebar Toggle - Chỉ hiển thị ở mobile -->
    <button class="sidebar-toggle-btn d-lg-none">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Search Bar - Ẩn trên mobile -->
    <div class="nav-search d-none d-lg-flex">
        <input type="text" placeholder="Tìm kiếm (Ctrl + /)" aria-label="Search">
        <i class="fas fa-search"></i>
    </div>
    
    <!-- Mobile Search Button -->
    <button class="nav-action-btn mobile-search-btn d-lg-none" aria-label="Mobile Search">
        <i class="fas fa-search"></i>
    </button>
    
    <!-- Action Buttons -->
    <div class="nav-actions">
        
        <!-- Notifications Dropdown -->
        <div class="dropdown">
            <button class="nav-action-btn" id="notifications-dropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                <i class="far fa-bell"></i>
                <span class="badge pulse"><?= count($notifications) ?></span>
            </button>
            
            <div class="dropdown-menu notification-dropdown" aria-labelledby="notifications-dropdown">
                <div class="dropdown-header">
                    <h6 class="mb-0">Thông báo</h6>
                    <a href="#" class="text-muted text-decoration-none">
                        <i class="fas fa-check-double"></i>
                        Đánh dấu tất cả đã đọc
                    </a>
                </div>
                
                <div class="notification-list">
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
                        <button class="notification-close" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <a href="<?= base_url('student/notifications') ?>" class="dropdown-item text-center view-all">
                    Xem tất cả thông báo
                    <i class="fas fa-chevron-right ms-1"></i>
                </a>
            </div>
        </div>
        
        <!-- User Dropdown -->
        <div class="dropdown">
            <a href="#" class="user-dropdown" id="user-dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar">
                    <img src="<?= base_url('assets/images/avatars/default.jpg') ?>" alt="User Avatar">
                    <span class="user-status"></span>
                </div>
                <div class="user-info d-none d-md-block">
                    <div class="user-name"><?= getFullName() ?></div>
                    <div class="user-role"><?= getFullRole() ?></div>
                </div>
            </a>
            
            <div class="dropdown-menu user-menu" aria-labelledby="user-dropdown">
                <div class="dropdown-header">
                    <div class="d-flex align-items-center">
                        <div class="user-avatar me-3">
                            <img src="<?= base_url('assets/images/avatars/default.jpg') ?>" alt="User Avatar">
                            <span class="user-status"></span>
                        </div>
                        <div>
                            <div class="fw-bold"><?= getFullName() ?></div>
                            <div class="text-muted small"><?= getFullRole() ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="dropdown-divider"></div>
                
                <div class="user-menu-section">
                    <small class="dropdown-header-text">Tài khoản</small>
                    <a class="dropdown-item" href="<?= base_url('student/profile') ?>">
                        <i class="fas fa-user"></i>
                        <span>Hồ sơ của tôi</span>
                    </a>
                    <a class="dropdown-item" href="<?= base_url('student/settings') ?>">
                        <i class="fas fa-cog"></i>
                        <span>Cài đặt</span>
                    </a>
                </div>
                
                <div class="user-menu-section">
                    <small class="dropdown-header-text">Thanh toán</small>
                    <a class="dropdown-item" href="<?= base_url('student/billing') ?>">
                        <i class="fas fa-file-invoice"></i>
                        <span>Thanh toán</span>
                        <span class="badge bg-danger ms-auto">4</span>
                    </a>
                    <a class="dropdown-item" href="<?= base_url('student/subscription') ?>">
                        <i class="fas fa-crown"></i>
                        <span>Nâng cấp Pro</span>
                    </a>
                </div>
                
                <div class="dropdown-divider"></div>
                
                <div class="user-menu-section">
                    <a class="dropdown-item text-danger" href="<?= base_url('login/logoutstudent') ?>">
                        <i class="fas fa-power-off"></i>
                        <span>Đăng xuất</span>
                    </a>
                </div>
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
    <div class="mobile-search-form">
        <div class="search-input-group">
            <i class="fas fa-search"></i>
            <input type="text" class="form-control" placeholder="Tìm kiếm..." aria-label="Mobile Search">
            <div class="search-shortcut d-none d-lg-flex">
                <span>Ctrl</span> + <span>/</span>
            </div>
        </div>
    </div>
    <div class="mobile-search-results">
        <div class="search-empty-state text-center py-5">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <p class="text-muted">Nhập từ khóa để tìm kiếm</p>
        </div>
    </div>
</div>

<!-- Link JS file -->
<script src="<?= base_url('assets/js/student/components/header.js') ?>"></script> 