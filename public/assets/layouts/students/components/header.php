<header class="app-header">
    <button class="btn mobile-sidebar-toggle d-md-none">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="sidebar-toggle d-none d-md-block">
        <i class="fas fa-bars"></i>
    </div>
    
    <a href="<?= base_url('students/dashboard') ?>" class="header-brand d-none d-md-block">
        <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" height="30" class="me-2">
        <span>CMS Sinh viên</span>
    </a>
    
    <div class="ms-auto d-flex align-items-center">
        <!-- Thông báo -->
        <div class="dropdown me-3 position-relative">
            <a href="#" class="nav-link dropdown-toggle no-caret" id="notificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                <span class="badge bg-danger badge-counter position-absolute top-0 end-0 translate-middle">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg shadow-sm" aria-labelledby="notificationsDropdown">
                <h6 class="dropdown-header">Thông báo</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="me-3">
                        <div class="text-primary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">15/03/2024</div>
                        <span>Sự kiện "Workshop Công nghệ AI" sắp diễn ra</span>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="me-3">
                        <div class="text-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">14/03/2024</div>
                        <span>Đăng ký tham gia "Ngày hội việc làm" thành công</span>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item d-flex align-items-center" href="#">
                    <div class="me-3">
                        <div class="text-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div>
                        <div class="small text-gray-500">12/03/2024</div>
                        <span>Bạn cần xác nhận tham gia Hội thảo</span>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center small text-gray-500" href="<?= base_url('students/notifications') ?>">Xem tất cả thông báo</a>
            </div>
        </div>
        
        <!-- User profile -->
        <div class="dropdown">
            <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="me-2 d-none d-sm-block text-end">
                    <div class="fw-bold"><?= session()->get('fullname') ?? 'Nguyễn Văn A' ?></div>
                    <div class="text-muted small"><?= session()->get('student_id') ?? 'SV001' ?></div>
                </div>
                <?php if (session()->get('avatar')): ?>
                    <img src="<?= base_url(session()->get('avatar')) ?>" class="avatar rounded-circle" width="32" height="32" alt="User">
                <?php else: ?>
                    <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <?= substr(session()->get('fullname') ?? 'U', 0, 1) ?>
                    </div>
                <?php endif; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="<?= base_url('students/profile') ?>"><i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Thông tin cá nhân</a></li>
                <li><a class="dropdown-item" href="<?= base_url('students/dashboard') ?>"><i class="fas fa-tachometer-alt fa-sm fa-fw me-2 text-gray-400"></i> Bảng điều khiển</a></li>
                <li><a class="dropdown-item" href="<?= base_url('students/settings') ?>"><i class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i> Cài đặt</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= base_url('students/logout') ?>"><i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</header> 