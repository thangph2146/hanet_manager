<?php
/**
 * 9/15/2022
 * AUTHOR:PDV-PC
 */
?>

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
						<a class="nav-link <?= uri_string() == 'su-kien' ? 'active' : '' ?>" href="<?= site_url('su-kien') ?>">
							<i class="fas fa-home me-1"></i> Trang chủ
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link <?= uri_string() == 'su-kien/list' ? 'active' : '' ?>" href="<?= site_url('su-kien/list') ?>">
							<i class="fas fa-calendar-alt me-1"></i> Sự kiện
						</a>
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
							<i class="fas fa-tags me-1"></i> Loại sự kiện
						</a>
						<ul class="dropdown-menu">
							<li><a class="dropdown-item" href="<?= site_url('su-kien/loai/hoi-thao') ?>">Hội thảo</a></li>
							<li><a class="dropdown-item" href="<?= site_url('su-kien/loai/nghe-nghiep') ?>">Nghề nghiệp</a></li>
							<li><a class="dropdown-item" href="<?= site_url('su-kien/loai/workshop') ?>">Workshop</a></li>
							<li><a class="dropdown-item" href="<?= site_url('su-kien/loai/hoat-dong-sinh-vien') ?>">Hoạt động sinh viên</a></li>
						</ul>
					</li>
				</ul>
				<div class="d-flex align-items-center gap-2">
					<a href="https://hub.edu.vn" target="_blank" class="btn btn-outline-light btn-sm px-3">
						<i class="fas fa-external-link-alt me-1"></i> HUB
					</a>
					<a href="<?= site_url('login') ?>" class="btn btn-light btn-sm px-3 me-2 btn-login">
						<i class="fas fa-user-plus me-1"></i> Đăng nhập
					</a>
					
				</div>
			</div>
		</div>
	</nav>