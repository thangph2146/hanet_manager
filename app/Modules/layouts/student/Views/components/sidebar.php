<!--start sidebar -->
<div class="sidebar-wrapper">
    <div class="sidebar-header">
        <div class="logo-icon-container">
            <img src="<?= site_url('assets/modules/images/hub-icon.png') ?>" class="logo-icon" alt="HUB logo">
        </div>
        <div class="logo-text-wrapper">
            <h5 class="logo-text"><?= lang('Layout.sidebar_title', ['name' => 'HUB Events']) ?></h5>
        </div>
        <div class="toggle-icon" aria-label="Toggle sidebar mini mode" role="button" tabindex="0">
            <i class="bi bi-chevron-left"></i>
        </div>
    </div>
    
    <!-- Thanh tìm kiếm mobile cho sidebar -->
    <div class="sidebar-search d-md-none">
        <div class="search-input-container">
            <i class="bi bi-search search-icon-sidebar"></i>
            <input type="text" class="form-control search-input" placeholder="Tìm menu...">
            <button type="button" class="btn btn-sm sidebar-search-clear">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
    
    <!-- Phần menu chính -->
    <ul class="metismenu" id="sidemenu">
        <li>
            <a href="<?= base_url('students/dashboard') ?>" aria-label="Dashboard" data-menu-id="menu-item-dashboard">
                <div class="parent-icon"><i class="bi bi-house-door"></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li>
            <a href="<?= base_url('students/events') ?>" aria-label="Sự kiện" data-menu-id="menu-item-events">
                <div class="parent-icon"><i class="bi bi-calendar-event"></i></div>
                <div class="menu-title">Sự kiện</div>
            </a>
        </li>
        <li>
            <a href="<?= base_url('students/events/registered') ?>" aria-label="Sự kiện đã đăng ký" data-menu-id="menu-item-registered-events">
                <div class="parent-icon"><i class="bi bi-calendar-check"></i></div>
                <div class="menu-title">Đã đăng ký</div>
            </a>
        </li>
        <li>
            <a href="<?= base_url('students/qrcode') ?>" aria-label="Quét mã QR" data-menu-id="menu-item-qrcode">
                <div class="parent-icon"><i class="bi bi-qr-code-scan"></i></div>
                <div class="menu-title">Quét mã QR</div>
            </a>
        </li>
        <li>
            <a href="<?= base_url('students/certificates') ?>" aria-label="Chứng chỉ" data-menu-id="menu-item-certificates">
                <div class="parent-icon"><i class="bi bi-award"></i></div>
                <div class="menu-title">Chứng chỉ</div>
            </a>
        </li>
        <li>
            <a href="javascript:;" class="has-arrow" aria-label="Thống kê" data-menu-id="menu-item-statistics">
                <div class="parent-icon"><i class="bi bi-graph-up"></i></div>
                <div class="menu-title">Thống kê</div>
                <div class="menu-arrow">
                    <i class="bi bi-chevron-down"></i>
                </div>
            </a>
            <ul class="mm-collapse">
                <li>
                    <a href="<?= base_url('students/statistics/attendance') ?>" data-menu-id="menu-item-statistics-attendance">
                        <i class="bi bi-circle"></i> Tham dự
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('students/statistics/achievements') ?>" data-menu-id="menu-item-statistics-achievements">
                        <i class="bi bi-circle"></i> Thành tích
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="<?= base_url('students/profile') ?>" aria-label="Hồ sơ cá nhân" data-menu-id="menu-item-profile">
                <div class="parent-icon"><i class="bi bi-person"></i></div>
                <div class="menu-title">Hồ sơ cá nhân</div>
            </a>
        </li>
        <li>
            <a href="<?= base_url('students/notifications') ?>" aria-label="Thông báo" data-menu-id="menu-item-notifications">
                <div class="parent-icon"><i class="bi bi-bell"></i></div>
                <div class="menu-title">Thông báo</div>
            </a>
        </li>
        <li>
            <a href="<?= base_url('students/settings') ?>" aria-label="Cài đặt" data-menu-id="menu-item-settings">
                <div class="parent-icon"><i class="bi bi-gear"></i></div>
                <div class="menu-title">Cài đặt</div>
            </a>
        </li>
    </ul>
    
    <!-- Phần bottom của sidebar - có thể thêm các liên kết hữu ích -->
    <div class="sidebar-footer">
        <div class="sidebar-footer-content">
            <div class="sidebar-footer-item">
                <a href="<?= base_url('help') ?>" class="sidebar-footer-link" data-menu-id="menu-item-help">
                    <i class="bi bi-question-circle"></i>
                    <span>Trợ giúp</span>
                </a>
            </div>
            <div class="sidebar-footer-item">
                <a href="<?= base_url('login/logoutstudent') ?>" class="sidebar-footer-link text-danger">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Đăng xuất</span>
                </a>
            </div>
        </div>
    </div>
</div>
<!--end sidebar--> 