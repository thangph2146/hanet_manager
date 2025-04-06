<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
		<div class="container">
			<a class="navbar-brand" href="<?= site_url() ?>">
				<img src="<?= base_url('assets/images/hub-logo.png') ?>" alt="HUB Logo">
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav me-auto">
					<li class="nav-item">
						<a class="nav-link" href="<?= site_url() ?>">
							<i class="fas fa-home me-1"></i> Trang chủ
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= uri_string() == 'su-kien' ? 'active' : '' ?>" href="<?= site_url('su-kien') ?>">
							<i class="fas fa-calendar-alt me-1"></i> Sự kiện
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
							<i class="fas fa-calendar-alt me-1"></i> Loại sự kiện
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="<?= site_url('su-kien/loai/hoi-thao') ?>">Hội thảo</a></li>
							<li><a class="dropdown-item" href="<?= site_url('su-kien/loai/nghe-nghiep') ?>">Nghề nghiệp</a></li>
							<li><a class="dropdown-item" href="<?= site_url('su-kien/loai/workshop') ?>">Workshop</a></li>
							<li><a class="dropdown-item" href="<?= site_url('su-kien/loai/hoat-dong-sinh-vien') ?>">Hoạt động sinh viên</a></li>
						</ul>
					</li>
					<?php if (isLoggedInStudent()): ?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
							<i class="fas fa-user-circle me-1"></i> Dashboard
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="<?= site_url('nguoi-dung/danh-sach-su-kien') ?>">Danh sách sự kiện</a></li>
							<li><a class="dropdown-item" href="<?= site_url('nguoi-dung/su-kien-da-dang-ky') ?>">Sự kiện đã đăng ký</a></li>
							<li><a class="dropdown-item" href="<?= site_url('nguoi-dung/su-kien-da-tham-gia') ?>">Sự kiện đã tham gia</a></li>
						</ul>
					</li>
					<?php endif; ?>
				</ul>
				<div class="d-flex align-items-center gap-2">
					<a href="https://hub.edu.vn" target="_blank" class="btn btn-outline-light btn-sm px-3">
						<i class="fas fa-external-link-alt me-1"></i> HUB
					</a>
					<?php if (!isLoggedInStudent()): ?>
					<a href="<?= site_url('login') ?>" class="btn btn-light btn-sm px-3 me-2 btn-login">
						<i class="fas fa-user-plus me-1"></i> Đăng nhập
					</a>
					<?php else: ?>
					 <!-- User Dropdown -->
					 <?php 
                     // Định nghĩa menu người dùng
                     $userMenuGroups = [
                         [
                             'actions' => [
                                 [
                                     'title' => 'Profile',
                                     'url' => 'nguoi-dung/thong-tin-ca-nhan',
                                     'icon' => 'user'
                                 ],
                                 [
                                     'title' => 'Đăng xuất',
                                     'url' => 'login/logoutnguoidung',
                                     'icon' => 'sign-out-alt',
                                     'type' => 'danger'
                                 ]
                             ]
                         ]
                     ];
                       // Hiển thị dropdown người dùng với dữ liệu đã định nghĩa
                     echo view('frontend/components/nguoidung_dropdown', [
						'username' => getFullNameStudent(),
						'avatar' => base_url('assets/images/avatars/default.jpg'),
						'menu_groups' => $userMenuGroups
					]);
					 ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</nav>