<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true" data-sidebar-initialized="false">
    <div class="sidebar-header">
        <div class="logo-icon-container">
            <img src="<?= site_url('assets/modules/images/hub-logo.png') ?>" 
            class="logo-icon" 
            alt="HUB Events" 
            style="width: 50px;"
            >
        </div>
        <div class="logo-text-wrapper d-flex flex-column align-items-center">
            <h4 class="logo-text">HUB Events</h4>
        </div>
        <div class="toggle-icon ms-4" aria-label="Toggle sidebar" data-bs-toggle="tooltip" data-bs-placement="right" title="Thu gọn/Mở rộng">
            <i class='bx bx-arrow-to-left'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu" data-menu-initialized="false">
        <li class="<?= (current_url() == base_url('students/dashboard') || current_url() == base_url('@dashboard.php')) ? 'mm-active' : '' ?>">
            <a href="<?= base_url('students/dashboard') ?>">
                <div class="parent-icon"><i class='bx bx-home-circle'></i>
                </div>
                <div class="menu-title">Trang chủ</div>
            </a>
        </li>
        <li class="<?= strpos(current_url(), base_url('students/events')) !== false ? 'mm-active' : '' ?>">
            <a href="<?= base_url('students/events') ?>">
                <div class="parent-icon"><i class='bx bx-calendar-event'></i>
                </div>
                <div class="menu-title">Sự kiện</div>
            </a>
        </li>
        <li class="<?= strpos(current_url(), base_url('students/my-registrations')) !== false ? 'mm-active' : '' ?>">
            <a href="<?= base_url('students/my-registrations') ?>">
                <div class="parent-icon"><i class='bx bx-list-check'></i>
                </div>
                <div class="menu-title">Đăng ký của tôi</div>
            </a>
        </li>
        <li class="<?= strpos(current_url(), base_url('students/certificates')) !== false ? 'mm-active' : '' ?>">
            <a href="<?= base_url('students/certificates') ?>">
                <div class="parent-icon"><i class='bx bx-certification'></i>
                </div>
                <div class="menu-title">Chứng chỉ</div>
            </a>
        </li>
        <li class="<?= strpos(current_url(), base_url('students/profile')) !== false ? 'mm-active' : '' ?>">
            <a href="<?= base_url('students/profile') ?>">
                <div class="parent-icon"><i class='bx bx-user'></i>
                </div>
                <div class="menu-title">Thông tin cá nhân</div>
            </a>
        </li>
        <li class="<?= strpos(current_url(), base_url('students/change-password')) !== false ? 'mm-active' : '' ?>">
            <a href="<?= base_url('students/change-password') ?>">
                <div class="parent-icon"><i class='bx bx-lock'></i>
                </div>
                <div class="menu-title">Đổi mật khẩu</div>
            </a>
        </li>
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper --> 