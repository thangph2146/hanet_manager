<aside class="sidebar">
    <!-- User Profile -->
    <div class="sidebar-profile">
        <div class="sidebar-avatar">
            <?php if (isset($user_avatar) && !empty($user_avatar)): ?>
            <img src="<?= base_url($user_avatar) ?>" alt="Avatar">
            <?php else: ?>
            <i class="fas fa-user"></i>
            <?php endif; ?>
        </div>
        <div class="sidebar-name"><?= isset($user_name) ? $user_name : 'Người dùng' ?></div>
        <div class="sidebar-role"><?= isset($user_role) ? $user_role : 'Sinh viên' ?></div>
        <div class="sidebar-progress">
            <div class="sidebar-progress-bar" style="width: <?= isset($user_progress) ? $user_progress : '0' ?>%;"></div>
        </div>
    </div>
    
    <!-- Dashboard Menu -->
    <div class="sidebar-menu">
        <div class="sidebar-menu-title">TRANG CHÍNH</div>
        <div class="menu-item">
            <a href="<?= base_url('students/dashboard') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-home"></i></div>
                <div class="menu-text">Dashboard</div>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('students/profile') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-user"></i></div>
                <div class="menu-text">Hồ sơ sinh viên</div>
            </a>
        </div>
    </div>
    
    <!-- Academic Menu -->
    <div class="sidebar-menu">
        <div class="sidebar-menu-title">HỌC TẬP</div>
        <div class="menu-item">
            <a href="<?= base_url('students/courses') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-book"></i></div>
                <div class="menu-text">Khóa học</div>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('students/assignments') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-tasks"></i></div>
                <div class="menu-text">Bài tập</div>
                <?php if (isset($assignment_count) && $assignment_count > 0): ?>
                <span class="menu-badge"><?= $assignment_count ?></span>
                <?php endif; ?>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('students/exams') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-file-alt"></i></div>
                <div class="menu-text">Lịch thi</div>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('students/grades') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-chart-line"></i></div>
                <div class="menu-text">Điểm số</div>
            </a>
        </div>
    </div>
    
    <!-- Activities Menu -->
    <div class="sidebar-menu">
        <div class="sidebar-menu-title">HOẠT ĐỘNG</div>
        <div class="menu-item">
            <a href="<?= base_url('students/events') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="menu-text">Sự kiện</div>
                <?php if (isset($event_count) && $event_count > 0): ?>
                <span class="menu-badge"><?= $event_count ?></span>
                <?php endif; ?>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('students/events/registered') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-calendar-check"></i></div>
                <div class="menu-text">Sự kiện đã đăng ký</div>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('students/clubs') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-users"></i></div>
                <div class="menu-text">Câu lạc bộ</div>
            </a>
        </div>
    </div>
    
    <!-- Support Menu -->
    <div class="sidebar-menu">
        <div class="sidebar-menu-title">HỖ TRỢ</div>
        <div class="menu-item">
            <a href="<?= base_url('students/support') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-headset"></i></div>
                <div class="menu-text">Hỗ trợ trực tuyến</div>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('students/faq') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-question-circle"></i></div>
                <div class="menu-text">Câu hỏi thường gặp</div>
            </a>
        </div>
    </div>
    
    <!-- Settings Menu -->
    <div class="sidebar-menu">
        <div class="sidebar-menu-title">CÀI ĐẶT</div>
        <div class="menu-item">
            <a href="<?= base_url('students/settings') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-cog"></i></div>
                <div class="menu-text">Thiết lập</div>
            </a>
        </div>
        <div class="menu-item">
            <a href="<?= base_url('logout') ?>" class="menu-link">
                <div class="menu-icon"><i class="fas fa-sign-out-alt"></i></div>
                <div class="menu-text">Đăng xuất</div>
            </a>
        </div>
    </div>
</aside> 