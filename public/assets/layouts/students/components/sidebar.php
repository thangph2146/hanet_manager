<div class="sidebar">
    <div class="sidebar-header d-flex align-items-center">
        <a href="<?= base_url('students/dashboard') ?>" class="sidebar-brand d-flex align-items-center">
            <img src="<?= base_url('assets/img/logo.png') ?>" alt="Logo" height="30" class="me-2">
            <span class="sidebar-brand-text">CMS Sinh viên</span>
        </a>
    </div>
    
    <div class="sidebar-user d-flex align-items-center px-3 py-3 border-bottom">
        <?php if (session()->get('avatar')): ?>
            <img src="<?= base_url(session()->get('avatar')) ?>" class="avatar rounded-circle me-2" width="40" height="40" alt="User">
        <?php else: ?>
            <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                <?= substr(session()->get('fullname') ?? 'U', 0, 1) ?>
            </div>
        <?php endif; ?>
        <div class="user-info">
            <div class="fw-bold"><?= session()->get('fullname') ?? 'Nguyễn Văn A' ?></div>
            <div class="text-muted small"><?= session()->get('student_id') ?? 'SV001' ?></div>
        </div>
    </div>
    
    <ul class="sidebar-nav">
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/dashboard') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/dashboard') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-tachometer-alt"></i>
                <span class="sidebar-nav-text">Bảng điều khiển</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/profile') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/profile') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-user"></i>
                <span class="sidebar-nav-text">Thông tin cá nhân</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/courses') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/courses') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-book"></i>
                <span class="sidebar-nav-text">Khóa học</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/schedules') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/schedules') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-calendar-alt"></i>
                <span class="sidebar-nav-text">Lịch học</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/exams') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/exams') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-file-alt"></i>
                <span class="sidebar-nav-text">Lịch thi</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/grades') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/grades') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-chart-line"></i>
                <span class="sidebar-nav-text">Kết quả học tập</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/fees') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/fees') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-credit-card"></i>
                <span class="sidebar-nav-text">Học phí</span>
            </a>
        </li>
        
        <!-- Phần Sự kiện với submenu -->
        <li class="sidebar-nav-item">
            <a href="#" class="sidebar-nav-link d-flex justify-content-between align-items-center sidebar-dropdown-toggle <?= strpos(current_url(), base_url('students/events')) === 0 ? 'active' : '' ?>">
                <div>
                    <i class="sidebar-nav-icon fas fa-calendar-week"></i>
                    <span class="sidebar-nav-text">Sự kiện</span>
                </div>
                <i class="fas fa-chevron-right sidebar-dropdown-indicator"></i>
            </a>
            <ul class="sidebar-dropdown-menu" style="display: <?= strpos(current_url(), base_url('students/events')) === 0 ? 'block' : 'none' ?>;">
                <li>
                    <a href="<?= base_url('students/events') ?>" class="sidebar-dropdown-link <?= current_url() == base_url('students/events') ? 'active' : '' ?>">
                        <i class="sidebar-nav-icon fas fa-list"></i>
                        <span class="sidebar-nav-text">Danh sách sự kiện</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('students/events/registered') ?>" class="sidebar-dropdown-link <?= current_url() == base_url('students/events/registered') ? 'active' : '' ?>">
                        <i class="sidebar-nav-icon fas fa-clipboard-check"></i>
                        <span class="sidebar-nav-text">Sự kiện đã đăng ký</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('students/events/completed') ?>" class="sidebar-dropdown-link <?= current_url() == base_url('students/events/completed') ? 'active' : '' ?>">
                        <i class="sidebar-nav-icon fas fa-check-circle"></i>
                        <span class="sidebar-nav-text">Sự kiện đã tham gia</span>
                    </a>
                </li>
            </ul>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/certificates') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/certificates') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-certificate"></i>
                <span class="sidebar-nav-text">Chứng chỉ</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/notifications') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/notifications') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-bell"></i>
                <span class="sidebar-nav-text">Thông báo</span>
                <span class="badge bg-danger rounded-pill ms-auto">3</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/help') ?>" class="sidebar-nav-link <?= current_url() == base_url('students/help') ? 'active' : '' ?>">
                <i class="sidebar-nav-icon fas fa-question-circle"></i>
                <span class="sidebar-nav-text">Trợ giúp</span>
            </a>
        </li>
        
        <li class="sidebar-nav-item">
            <a href="<?= base_url('students/logout') ?>" class="sidebar-nav-link">
                <i class="sidebar-nav-icon fas fa-sign-out-alt"></i>
                <span class="sidebar-nav-text">Đăng xuất</span>
            </a>
        </li>
    </ul>
</div>

<script>
// Initialize dropdown toggles
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggles = document.querySelectorAll('.sidebar-dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const dropdownMenu = this.nextElementSibling;
            const indicator = this.querySelector('.sidebar-dropdown-indicator');
            
            // Toggle the menu
            if (dropdownMenu.style.display === 'none' || dropdownMenu.style.display === '') {
                dropdownMenu.style.display = 'block';
                indicator.classList.remove('fa-chevron-right');
                indicator.classList.add('fa-chevron-down');
            } else {
                dropdownMenu.style.display = 'none';
                indicator.classList.remove('fa-chevron-down');
                indicator.classList.add('fa-chevron-right');
            }
        });
    });
});
</script> 