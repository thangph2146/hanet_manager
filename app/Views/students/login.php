<!doctype html>
<html lang="vi">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="<?= site_url('assets/images/favicon-32x32.png') ?>" type="image/png" />
	<!--plugins-->
	<link href="<?= site_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
	<link href="<?= site_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
	<link href="<?= site_url('assets/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet" />
	<!-- loader-->
	<link href="<?= site_url('assets/css/pace.min.css') ?>" rel="stylesheet" />
	<script src="<?= site_url('assets/js/pace.min.js') ?>"></script>
	<!-- Bootstrap CSS -->
	<link href="<?= site_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?= site_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet">
	<link href="<?= site_url('assets/css/app.css') ?>" rel="stylesheet">
	<link href="<?= site_url('assets/css/icons.css') ?>" rel="stylesheet">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
	<!-- Animate.css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
	<!-- Login CSS -->
	<link href="<?= site_url('assets/modules/login/css/login.css') ?>" rel="stylesheet">
	<!-- Particles CSS -->
	<link href="<?= site_url('assets/modules/particles/css/particles.css') ?>" rel="stylesheet">
	<title>Đăng nhập - Hệ thống đăng ký sự kiện ĐH Ngân hàng TP.HCM</title>
</head>

<body class="bg-login">
<!--wrapper-->
<div class="wrapper">
	<div class="row">
		<div class="col-xl-12 mx-auto">
			<!-- warning -->
			<?= $this->include('components/_warning') ?>
			<!-- end warning -->
		</div>
	</div>
	<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
		<div class="container-fluid">
			<div class="login-container">
				<!-- Info Panel (Left Side) -->
				<div class="info-panel">
					<div class="text-center">
						<img src="<?= base_url('assets/modules/images/hub-logo.png') ?>" alt="Logo Đại học Ngân hàng TP.HCM" class="school-logo">
						<h2 class="school-name">ĐẠI HỌC NGÂN HÀNG<br>TP. HỒ CHÍ MINH</h2>
					</div>
					
					<h3 class="events-title">SỰ KIỆN NỔI BẬT</h3>
					
					<div class="events-list">
						<div class="event-item">
							<div class="event-icon">
								<i class="fas fa-calendar-alt"></i>
							</div>
							<div class="event-text">
								<strong>Ngày hội việc làm <?= date('Y') ?></strong>
								Cơ hội kết nối với hơn <span class="highlight">50+ doanh nghiệp hàng đầu</span>
							</div>
						</div>
						
						<div class="event-item">
							<div class="event-icon">
								<i class="fas fa-microphone"></i>
							</div>
							<div class="event-text">
								<strong>Hội thảo chuyên đề</strong>
								Gặp gỡ các chuyên gia trong ngành tài chính - ngân hàng
							</div>
						</div>
						
						<div class="event-item">
							<div class="event-icon">
								<i class="fas fa-trophy"></i>
							</div>
							<div class="event-text">
								<strong>Cuộc thi tài năng sinh viên</strong>
								Cơ hội giành học bổng và những phần quà giá trị
							</div>
						</div>
						
						<div class="event-item">
							<div class="event-icon">
								<i class="fas fa-users"></i>
							</div>
							<div class="event-text">
								<strong>Hoạt động câu lạc bộ</strong>
								Tham gia và phát triển kỹ năng mềm cùng các CLB sinh viên
							</div>
						</div>
					</div>
					
					<div class="info-footer text-center">
						© <?= date('Y') ?> Trường Đại học Ngân hàng TP. Hồ Chí Minh<br>
						<small>36 Tôn Thất Đạm, Phường Nguyễn Thái Bình, Quận 1, TP.HCM</small>
					</div>
				</div>
				
				<!-- Login Form Panel (Right Side) -->
				<div class="login-form-panel">
					<div class="floating-shape shape1"></div>
					<div class="floating-shape shape2"></div>
					
					<div class="login-header">
						<img src="<?= base_url('assets/modules/images/hub-logo.png') ?>" alt="Logo Đại học Ngân hàng TP.HCM" class="mobile-logo">
						<h3 class="login-title">HỆ THỐNG ĐĂNG KÝ SỰ KIỆN</h3>
						<p class="login-subtitle">Đăng nhập để đăng ký và tham gia các sự kiện đặc biệt của trường</p>
					</div>
					
					<!-- Form Body -->
					<div class="form-body">
						<?= form_open(site_url("login/create_student"), ['class' => 'row g-3']) ?>
							<div class="row">
								<div class="col-xl-12 mx-auto">
									<!--Error list -->
									<?= $this->include('components/_errors_list'); ?>
									<!--end Error list -->
								</div>
							</div>
							<div class="col-12">
								<label for="email" class="form-label">Email sinh viên</label>
								<input type="email" class="form-control" id="email" name="email" placeholder="Nhập email sinh viên">
							</div>
							<div class="col-12">
								<label for="inputChoosePassword" class="form-label">Mật khẩu</label>
								<div class="input-group" id="show_hide_password">
									<input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password" placeholder="Nhập mật khẩu"> 
									<a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" id="remember_me" name="remember_me" <?php if (old('remember_me')): ?>checked<?php endif; ?>>
									<label class="form-check-label" for="remember_me">Ghi nhớ đăng nhập</label>
								</div>
							</div>
							<div class="col-md-6 text-end">
								<a href="<?= site_url("Password/forgot") ?>" class="forgot-password">Quên mật khẩu?</a>
							</div>
							<div class="col-12">
								<div class="d-grid">
									<button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Đăng nhập</button>
								</div>
							</div>
							<div class="col-12">
								<div class="login-separator text-center"> 
									<span>HOẶC</span>
									<hr/>
								</div>
								<div class="d-grid">
									<a href="<?= $googleAuthUrl ?? '#' ?>" class="btn btn-danger google-btn">
										<i class="bx bxl-google"></i> Đăng nhập bằng Google
									</a>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="particles-js" class="particles-js"></div>
</div>
<!--end wrapper-->

<!-- Bootstrap JS -->
<script src="<?= site_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<!--plugins-->
<script src="<?= site_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
<!--app JS-->
<script src="<?= site_url('assets/js/app.js') ?>"></script>
<!-- Login JS -->
<script src="<?= site_url('assets/modules/login/js/login.js') ?>"></script>
<!-- Particles JS -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script src="<?= site_url('assets/modules/particles/js/particles.js') ?>"></script>
</body>

</html>
