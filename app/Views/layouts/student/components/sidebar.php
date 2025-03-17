<div class="sidebar bg-white shadow-sm">
    <div class="sidebar-header p-3 border-bottom">
        <div class="d-flex align-items-center justify-content-center">
            <a href="<?= base_url('students/dashboard') ?>" class="d-flex align-items-center text-decoration-none">
                <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" height="40" class="me-2">
                <div class="d-flex flex-column">
                    <span class="fw-bold text-primary fs-5">STUDENT</span>
                    <span class="small text-muted">Portal</span>
                </div>
            </a>
        </div>
    </div>
    
    <div class="sidebar-body p-0">
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="<?= base_url('students/dashboard') ?>" class="nav-link <?= current_url() == base_url('students/dashboard') ? 'active' : '' ?>">
                        <i class="bx bx-home-alt me-2"></i>
                        <span>Trang chủ</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('students/profile') ?>" class="nav-link <?= current_url() == base_url('students/profile') ? 'active' : '' ?>">
                        <i class="bx bx-user me-2"></i>
                        <span>Hồ sơ cá nhân</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('students/courses') ?>" class="nav-link <?= current_url() == base_url('students/courses') ? 'active' : '' ?>">
                        <i class="bx bx-book me-2"></i>
                        <span>Khóa học</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('students/schedules') ?>" class="nav-link <?= current_url() == base_url('students/schedules') ? 'active' : '' ?>">
                        <i class="bx bx-calendar me-2"></i>
                        <span>Lịch học</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('students/exams') ?>" class="nav-link <?= current_url() == base_url('students/exams') ? 'active' : '' ?>">
                        <i class="bx bx-edit me-2"></i>
                        <span>Lịch thi</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('students/grades') ?>" class="nav-link <?= current_url() == base_url('students/grades') ? 'active' : '' ?>">
                        <i class="bx bx-bar-chart-alt-2 me-2"></i>
                        <span>Điểm số</span>
                    </a>
                </li>
                
                <!-- Bắt đầu: Mục sự kiện -->
                <li class="nav-item">
                    <a href="#eventsSubmenu" data-bs-toggle="collapse" class="nav-link d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bx bx-calendar-event me-2"></i>
                            <span>Sự kiện</span>
                        </div>
                        <i class="bx bx-chevron-down"></i>
                    </a>
                    <div class="collapse <?= strpos(current_url(), 'students/events') !== false ? 'show' : '' ?>" id="eventsSubmenu">
                        <ul class="nav flex-column ms-3">
                            <li class="nav-item">
                                <a href="<?= base_url('students/events') ?>" class="nav-link <?= current_url() == base_url('students/events') ? 'active' : '' ?>">
                                    <i class="bx bx-list-ul me-2"></i>
                                    <span>Danh sách sự kiện</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('students/events/registered') ?>" class="nav-link <?= current_url() == base_url('students/events/registered') ? 'active' : '' ?>">
                                    <i class="bx bx-check-square me-2"></i>
                                    <span>Sự kiện đã đăng ký</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('students/events/completed') ?>" class="nav-link <?= current_url() == base_url('students/events/completed') ? 'active' : '' ?>">
                                    <i class="bx bx-badge-check me-2"></i>
                                    <span>Sự kiện đã tham gia</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- Kết thúc: Mục sự kiện -->
                
                <li class="nav-item">
                    <a href="<?= base_url('students/fees') ?>" class="nav-link <?= current_url() == base_url('students/fees') ? 'active' : '' ?>">
                        <i class="bx bx-money me-2"></i>
                        <span>Học phí</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('students/certificates') ?>" class="nav-link <?= current_url() == base_url('students/certificates') ? 'active' : '' ?>">
                        <i class="bx bx-certification me-2"></i>
                        <span>Chứng chỉ</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="<?= base_url('students/help') ?>" class="nav-link <?= current_url() == base_url('students/help') ? 'active' : '' ?>">
                        <i class="bx bx-help-circle me-2"></i>
                        <span>Trợ giúp</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    
    <div class="sidebar-footer p-3 border-top mt-auto">
        <a href="<?= base_url('students/logout') ?>" class="btn btn-outline-danger w-100">
            <i class="bx bx-log-out me-2"></i>
            <span>Đăng xuất</span>
        </a>
    </div>
</div> 