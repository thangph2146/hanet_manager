<header class="header py-3 shadow-sm">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-link d-lg-none me-2 p-0" id="mobileMenuToggle">
                    <i class="bx bx-menu fs-4"></i>
                </button>
                <h5 class="m-0"><?= isset($title) ? $title : 'Dashboard' ?></h5>
            </div>
            
            <div class="d-flex align-items-center">
                <!-- Thông báo -->
                <div class="dropdown me-3">
                    <button class="btn btn-link position-relative p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bx bx-bell fs-4"></i>
                        <?php if (isset($notification_count) && $notification_count > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $notification_count > 9 ? '9+' : $notification_count ?>
                        </span>
                        <?php endif; ?>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end p-0 shadow-sm notifications-dropdown">
                        <div class="dropdown-header d-flex justify-content-between align-items-center p-3">
                            <h6 class="mb-0">Thông báo</h6>
                            <a href="<?= base_url('students/notifications') ?>" class="text-decoration-none small">Xem tất cả</a>
                        </div>
                        <div class="dropdown-divider m-0"></div>
                        <div class="notifications-container">
                            <?php if (isset($recent_notifications) && count($recent_notifications) > 0): ?>
                                <?php foreach ($recent_notifications as $notification): ?>
                                <a href="#" class="dropdown-item p-3 border-bottom">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0">
                                            <div class="notification-icon bg-light-<?= $notification['type'] ?>">
                                                <i class="<?= $notification['icon'] ?>"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mb-1"><?= $notification['title'] ?></h6>
                                            <p class="text-muted mb-1 small"><?= $notification['content'] ?></p>
                                            <p class="text-muted mb-0 x-small"><?= $notification['time'] ?></p>
                                        </div>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center p-3">
                                    <p class="text-muted mb-0">Không có thông báo mới</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Thông tin người dùng -->
                <div class="dropdown">
                    <button class="btn btn-link d-flex align-items-center text-decoration-none p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="avatar-container me-2">
                            <?php if (isset($student_data) && !empty($student_data['picture'])): ?>
                                <img src="<?= base_url($student_data['picture']) ?>" alt="Profile" class="rounded-circle" width="32" height="32">
                            <?php else: ?>
                                <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                    <?= substr(isset($student_data['fullname']) ? $student_data['fullname'] : 'U', 0, 1) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-none d-md-block text-start">
                            <div class="small fw-medium"><?= isset($student_data) ? $student_data['fullname'] : 'Sinh viên' ?></div>
                            <div class="text-muted x-small"><?= isset($student_data) ? $student_data['student_id'] : '' ?></div>
                        </div>
                        <i class="bx bx-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="<?= base_url('students/profile') ?>"><i class="bx bx-user me-2"></i>Hồ sơ cá nhân</a></li>
                        <li><a class="dropdown-item" href="<?= base_url('students/settings') ?>"><i class="bx bx-cog me-2"></i>Cài đặt</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="<?= base_url('students/logout') ?>"><i class="bx bx-log-out me-2"></i>Đăng xuất</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header> 