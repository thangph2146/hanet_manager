<!--start header -->
<header class="top-header">
    <nav class="navbar navbar-expand gap-3">
        <div class="mobile-menu-button" aria-label="Toggle sidebar" role="button" tabindex="0">
            <i class="bi bi-list"></i>
        </div>
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
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative notification-toggle"
                       href="#" role="button" id="notificationsDropdown" aria-label="Thông báo">
                        <div class="notifications">
                            <span class="notify-badge" id="notifyBadge" style="display: none;">0</span>
                            <i class="bi bi-bell-fill"></i>
                        </div>
                    </a>
                    <!-- Dropdown được định nghĩa nhưng sẽ được di chuyển ra ngoài scope -->
                    <template id="notification-dropdown-template">
                        <div class="fixed-dropdown dropdown-menu dropdown-menu-end py-0 dropdown-notify" 
                             data-dropdown-for="notificationsDropdown">
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
                    </template>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative user-dropdown-toggle" 
                       href="#" role="button" id="userDropdown" aria-label="Tài khoản">
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?= base_url('assets/images/avatars/avatar-1.png') ?>" class="user-img" alt="User avatar">
                            <div class="user-info d-none d-md-block">
                                <p class="user-name mb-0"><?= session()->get('student_name') ?? 'Sinh viên' ?></p>
                                <p class="designattion mb-0"><?= session()->get('student_email') ?? 'student@buh.edu.vn' ?></p>
                            </div>
                        </div>
                    </a>
                    <!-- Dropdown được định nghĩa nhưng sẽ được di chuyển ra ngoài scope -->
                    <template id="user-dropdown-template">
                        <ul class="fixed-dropdown dropdown-menu dropdown-menu-end user-dropdown" 
                            data-dropdown-for="userDropdown">
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
                                <a class="dropdown-item d-flex align-items-center text-danger" href="<?= base_url('login/logoutstudent') ?>">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span class="ms-2">Đăng xuất</span>
                                </a>
                            </li>
                        </ul>
                    </template>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!--end header --> 

<!-- Container cho tất cả các fixed dropdown (sẽ được di chuyển ra khỏi flow/scope) -->
<div id="fixed-dropdowns-container" style="position: relative; z-index: 9999;"></div>

<style>
/* Reset một số style mặc định từ Bootstrap để tránh xung đột */
body {
    overflow-x: hidden;
}

.dropdown-menu {
    margin: 0;
}

/* CSS cho fixed dropdowns thoát khỏi scope giới hạn */
.fixed-dropdown {
    position: fixed !important;
    display: none;
    z-index: 9999 !important;
    opacity: 1 !important;
    visibility: visible !important;
    min-width: 280px !important;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 0.375rem;
    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15) !important;
    animation: dropdown-animation 0.2s ease-in;
}

.dark-theme .fixed-dropdown {
    background-color: #2c3e50;
    border-color: rgba(255, 255, 255, 0.15);
}

.fixed-dropdown.show {
    display: block !important;
}

@keyframes dropdown-animation {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Kiểu dáng bổ sung cho dropdown */
.dropdown-notify {
    width: 320px;
}

.user-dropdown {
    width: 250px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Di chuyển tất cả các dropdown ra ngoài scope giới hạn sử dụng template
    const container = document.getElementById('fixed-dropdowns-container');
    
    // Di chuyển notification dropdown
    const notifyTemplate = document.getElementById('notification-dropdown-template');
    if (notifyTemplate && container) {
        container.appendChild(notifyTemplate.content.cloneNode(true));
    }
    
    // Di chuyển user dropdown
    const userTemplate = document.getElementById('user-dropdown-template');
    if (userTemplate && container) {
        container.appendChild(userTemplate.content.cloneNode(true));
    }
    
    // Thiết lập các toggle cho dropdown
    setupDropdowns();
    
    function setupDropdowns() {
        // Lấy tất cả các dropdown trigger và fixed-dropdown
        const triggers = document.querySelectorAll('[id]');
        const fixedDropdowns = document.querySelectorAll('.fixed-dropdown[data-dropdown-for]');
        
        triggers.forEach(trigger => {
            const id = trigger.getAttribute('id');
            const dropdown = document.querySelector(`.fixed-dropdown[data-dropdown-for="${id}"]`);
            
            if (trigger && dropdown) {
                // Click event cho trigger
                trigger.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Ẩn tất cả các dropdown khác
                    fixedDropdowns.forEach(d => {
                        if (d !== dropdown) {
                            d.classList.remove('show');
                        }
                    });
                    
                    // Toggle dropdown hiện tại
                    dropdown.classList.toggle('show');
                    
                    if (dropdown.classList.contains('show')) {
                        // Định vị dropdown dưới trigger
                        positionDropdown(trigger, dropdown);
                    }
                });
            }
        });
        
        // Click outside để đóng dropdown
        document.addEventListener('click', function(e) {
            let clickedInside = false;
            
            // Kiểm tra xem click có nằm trong dropdown hoặc trigger không
            triggers.forEach(trigger => {
                if (trigger.contains(e.target)) {
                    clickedInside = true;
                }
            });
            
            fixedDropdowns.forEach(dropdown => {
                if (dropdown.contains(e.target)) {
                    clickedInside = true;
                }
            });
            
            // Nếu click bên ngoài, đóng tất cả dropdown
            if (!clickedInside) {
                fixedDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('show');
                });
            }
        });
        
        // Xử lý resize window
        window.addEventListener('resize', function() {
            fixedDropdowns.forEach(dropdown => {
                if (dropdown.classList.contains('show')) {
                    const forId = dropdown.getAttribute('data-dropdown-for');
                    const trigger = document.getElementById(forId);
                    if (trigger) {
                        positionDropdown(trigger, dropdown);
                    }
                }
            });
        });
    }
    
    // Hàm tính toán vị trí cho dropdown
    function positionDropdown(trigger, dropdown) {
        const triggerRect = trigger.getBoundingClientRect();
        const dropdownWidth = dropdown.offsetWidth;
        const windowWidth = window.innerWidth;
        
        // Xác định vị trí trên/dưới
        let top = triggerRect.bottom + window.scrollY;
        
        // Xác định vị trí trái/phải
        let left;
        if (triggerRect.right - dropdownWidth < 0) {
            // Không đủ không gian bên trái
            left = triggerRect.left;
        } else {
            // Căn phải
            left = triggerRect.right - dropdownWidth;
        }
        
        // Đảm bảo dropdown không bị tràn ra khỏi màn hình
        if (left + dropdownWidth > windowWidth) {
            left = windowWidth - dropdownWidth - 5;
        }
        
        if (left < 0) {
            left = 5;
        }
        
        // Áp dụng vị trí
        dropdown.style.top = `${top}px`;
        dropdown.style.left = `${left}px`;
    }
});
</script> 