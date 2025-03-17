<div class="user-box dropdown">
    <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <?php if (isset($student_data['picture']) && !empty($student_data['picture'])): ?>
            <img src="<?= $student_data['picture'] ?>" class="user-img" alt="Ảnh đại diện">
        <?php else: ?>
            <img src="<?= site_url('assets/images/avatars/avatar-1.png') ?>" class="user-img" alt="Ảnh đại diện">
        <?php endif; ?>
        <div class="user-info ps-3">
            <p class="user-name mb-0"><?= $student_data['fullname'] ?? session()->get('student_name') ?? 'Sinh viên' ?></p>
            <p class="designattion mb-0"><?= $student_data['student_id'] ?? session()->get('student_id') ?? '' ?></p>
        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="<?= base_url('students/profile') ?>">
            <i class="bx bx-user"></i><span>Thông tin cá nhân</span></a>
        </li>
        <li><a class="dropdown-item" href="<?= base_url('students/change-password') ?>">
            <i class="bx bx-lock"></i><span>Đổi mật khẩu</span></a>
        </li>
        <li>
            <div class="dropdown-divider mb-0"></div>
        </li>
        <li><a class="dropdown-item" href="<?= base_url('login/logoutstudent') ?>">
            <i class='bx bx-log-out-circle'></i><span>Đăng xuất</span></a>
        </li>
    </ul>
</div> 