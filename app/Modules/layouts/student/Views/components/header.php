<!--start header -->
<header class="top-header">
    <nav class="navbar navbar-expand gap-3">
        <div class="mobile-menu-button" aria-label="Toggle sidebar" role="button" tabindex="0">
            <i class="bi bi-list"></i>
        </div>
        <a href="<?= base_url('students/dashboard') ?>" class="brand-logo d-flex align-items-center">
            <img src="<?= base_url('assets/images/logo-icon.png') ?>" alt="Logo" class="logo-icon me-2" width="36">
            <span class="logo-text d-none d-xl-block fw-bold fs-5">BUH Events</span>
        </a>
        <form class="searchbar" id="searchForm">
            <div class="position-absolute top-50 translate-middle-y search-icon ms-3" role="button" tabindex="0">
                <i class="bi bi-search"></i>
            </div>
            <input class="form-control" type="text" placeholder="Tìm kiếm sự kiện..." 
                aria-label="Tìm kiếm sự kiện" autocomplete="off" data-search-url="<?= base_url('students/events') ?>">
            <div class="position-absolute top-50 translate-middle-y search-close-icon" role="button" tabindex="0" aria-label="Xóa tìm kiếm">
                <i class="bi bi-x-lg"></i>
            </div>
            
            <!-- Search results dropdown -->
            <div class="search-results-dropdown card shadow-sm" style="display: none;">
                <div class="card-body p-0">
                    <div class="search-results-list" data-simplebar="true" style="max-height: 350px;">
                        <!-- Results will be loaded dynamically -->
                        <div class="search-loading text-center py-3" style="display: none;">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Đang tìm kiếm...</span>
                            </div>
                            <span class="ms-2">Đang tìm kiếm...</span>
                        </div>
                        <div class="search-empty text-center py-3" style="display: none;">
                            <i class="bi bi-search fs-4 text-muted"></i>
                            <p class="mb-0 text-muted">Không tìm thấy kết quả</p>
                        </div>
                        <div class="search-results"></div>
                    </div>
                </div>
            </div>
        </form>
        <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown dropdown-large">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                       href="#" data-bs-toggle="dropdown" aria-expanded="false" 
                       id="notificationsDropdown" aria-label="Thông báo">
                        <div class="notifications">
                            <span class="notify-badge" id="notifyBadge" style="display: none;">0</span>
                            <i class="bi bi-bell-fill"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end py-0 dropdown-notify" aria-labelledby="notificationsDropdown">
                        <div class="dropdown-notify-header p-3 border-bottom d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Thông báo</h6>
                            <span class="badge bg-primary rounded-pill" id="notifyCount">0</span>
                        </div>
                        <div class="dropdown-notify-list p-1" data-simplebar="true" style="max-height: 360px;">
                            <div id="notificationListContainer">
                                <!-- Notifications will be loaded via AJAX -->
                                <div class="notification-loading text-center py-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Đang tải...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown-notify-footer p-2 border-top">
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-sm btn-light mark-all-read">
                                    <i class="bi bi-check-all"></i> Đánh dấu tất cả đã đọc
                                </button>
                                <a href="<?= base_url('students/notifications') ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-bell"></i> Xem tất cả thông báo
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" 
                       data-bs-toggle="dropdown" aria-expanded="false" id="userDropdown" aria-label="Tài khoản">
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?= base_url('assets/images/avatars/avatar-1.png') ?>" class="user-img" alt="User avatar">
                            <div class="user-info d-none d-md-block">
                                <p class="user-name mb-0"><?= session()->get('student_name') ?? 'Sinh viên' ?></p>
                                <p class="designattion mb-0"><?= session()->get('student_email') ?? 'student@buh.edu.vn' ?></p>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-dropdown">
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('students/profile') ?>">
                                <i class="bi bi-person-fill"></i>
                                <span class="ms-2">Thông tin cá nhân</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('students/events/registered') ?>">
                                <i class="bi bi-calendar-check-fill"></i>
                                <span class="ms-2">Sự kiện đã đăng ký</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?= base_url('students/settings') ?>">
                                <i class="bi bi-gear-fill"></i>
                                <span class="ms-2">Cài đặt</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center text-danger" href="<?= base_url('login/logout') ?>">
                                <i class="bi bi-box-arrow-right"></i>
                                <span class="ms-2">Đăng xuất</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!--end header --> 