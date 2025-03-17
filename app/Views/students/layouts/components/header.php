<header class="header">
    <!-- Logo and Title -->
    <div class="header-logo">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo">
        <div class="header-title">Hệ thống quản lý sinh viên</div>
    </div>
    
    <!-- Right Side Elements -->
    <div class="header-right">
        <!-- Sidebar Toggle Button -->
        <button class="header-toggle-btn">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Notifications -->
        <div class="header-notification">
            <button class="header-notification-btn">
                <i class="far fa-bell"></i>
                <?php if (isset($notification_count) && $notification_count > 0): ?>
                <span class="notification-badge"><?= $notification_count > 9 ? '9+' : $notification_count ?></span>
                <?php endif; ?>
            </button>
        </div>
        
        <!-- User Info -->
        <div class="header-user">
            <div class="header-user-avatar">
                <?php if (isset($user_avatar) && !empty($user_avatar)): ?>
                <img src="<?= base_url($user_avatar) ?>" alt="Avatar">
                <?php else: ?>
                <i class="fas fa-user"></i>
                <?php endif; ?>
            </div>
            <div class="header-user-info">
                <div class="header-user-name"><?= isset($user_name) ? $user_name : 'Người dùng' ?></div>
                <div class="header-user-role"><?= isset($user_role) ? $user_role : 'Sinh viên' ?></div>
            </div>
        </div>
    </div>
</header>