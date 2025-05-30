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
        'title' => 'Đăng xuất',
        'actions' => [
            [
                'type' => 'danger',
                'icon' => 'power-off',
                'title' => 'Đăng xuất',
                'url' => 'login/logoutnguoidung'
            ]
        ]
    ]
];  
?>

<nav class="content-navbar">
    <!-- Sidebar Toggle - Chỉ hiển thị ở mobile -->
    <button class="sidebar-toggle-btn d-lg-none" id="sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>
    
    
    <!-- Action Buttons -->
    <div class="nav-actions">
     
        <?php if (!isLoggedInStudent()): ?>
					<a href="<?= site_url('login') ?>" class="btn btn-light btn-sm px-3 me-2 btn-login">
						<i class="fas fa-user-plus me-1"></i> Đăng nhập
					</a>
					<?php else: ?>
					 <!-- User Dropdown -->
					 <?php 
                     // Định nghĩa menu người dùng
                     $userMenuGroups = [
                         [
                             'actions' => [
                                 [
                                     'title' => 'Profile',
                                     'url' => 'nguoi-dung/thong-tin-ca-nhan',
                                     'icon' => 'user'
                                 ],
                                 [
                                     'title' => 'Đăng xuất',
                                     'url' => 'login/logoutnguoidung',
                                     'icon' => 'sign-out-alt',
                                     'type' => 'danger'
                                 ]
                             ]
                         ]
                     ];
                       // Hiển thị dropdown người dùng với dữ liệu đã định nghĩa
                     echo view('frontend/components/nguoidung_dropdown', [
						'username' => getFullNameStudent(),
						'avatar' => base_url('assets/images/avatars/default.jpg'),
						'menu_groups' => $userMenuGroups
					]);
					 ?>
					<?php endif; ?>
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

<!-- Script cho header -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý mobile search
    const mobileSearchBtn = document.querySelector('.mobile-search-btn');
    const mobileSearchClose = document.getElementById('mobile-search-close');
    
    if (mobileSearchBtn) {
        mobileSearchBtn.addEventListener('click', function() {
            if (window.StudentUI) {
                window.StudentUI.openMobileSearch();
            } else {
                const mobileSearch = document.getElementById('mobile-search');
                if (mobileSearch) {
                    mobileSearch.classList.add('show');
                    document.body.style.overflow = 'hidden';
                    setTimeout(() => {
                        mobileSearch.querySelector('input')?.focus();
                    }, 300);
                }
            }
        });
    }
    
    if (mobileSearchClose) {
        mobileSearchClose.addEventListener('click', function() {
            if (window.StudentUI) {
                window.StudentUI.closeMobileSearch();
            } else {
                const mobileSearch = document.getElementById('mobile-search');
                if (mobileSearch) {
                    mobileSearch.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        });
    }
    
    // Xử lý đóng thông báo
    const notificationCloseButtons = document.querySelectorAll('.notification-close');
    notificationCloseButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const item = btn.closest('.notification-item');
            item.style.height = item.offsetHeight + 'px';
            
            // Trigger reflow
            item.offsetHeight;
            
            item.style.height = '0';
            item.style.opacity = '0';
            item.style.marginTop = '0';
            item.style.marginBottom = '0';
            item.style.padding = '0';
            
            setTimeout(() => {
                item.remove();
                updateNotificationCount();
            }, 300);
        });
    });
    
    function updateNotificationCount() {
        const badge = document.querySelector('#notifications-dropdown .badge');
        const items = document.querySelectorAll('.notification-item');
        if (badge) {
            const count = items.length;
            badge.textContent = count;
            if (count === 0) {
                badge.style.display = 'none';
            }
        }
    }
});
</script>