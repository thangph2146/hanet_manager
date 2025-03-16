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
	<!-- Particles CSS -->
	<link href="<?= site_url('assets/modules/particles/css/particles.css') ?>" rel="stylesheet">
	<!-- Login CSS -->
	<link href="<?= site_url('assets/modules/login/css/login.css') ?>" rel="stylesheet">
	<title>Đăng nhập - Quản trị viên Hệ thống Đăng ký Sự kiện ĐH Ngân hàng TP.HCM</title>
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
						<a href="<?= site_url('') ?>">	
							<img src="<?= base_url('assets/modules/images/hub-logo.png') ?>" alt="Logo Đại học Ngân hàng TP.HCM" class="school-logo">
						</a>
						<h2 class="school-name">ĐẠI HỌC NGÂN HÀNG<br>TP. HỒ CHÍ MINH</h2>
					</div>
					
					<h3 class="events-title">QUẢN TRỊ HỆ THỐNG SỰ KIỆN</h3>
					
					<div class="events-list">
						<div class="event-item">
							<div class="event-icon">
								<i class="fas fa-tasks"></i>
							</div>
							<div class="event-text">
								<strong>Quản lý sự kiện</strong>
								Tạo và quản lý <span class="highlight">tất cả sự kiện</span> trong trường
							</div>
						</div>
						
						<div class="event-item">
							<div class="event-icon">
								<i class="fas fa-users-cog"></i>
							</div>
							<div class="event-text">
								<strong>Quản lý người dùng</strong>
								Phân quyền và quản lý tài khoản trong hệ thống
							</div>
						</div>
						
						<div class="event-item">
							<div class="event-icon">
								<i class="fas fa-chart-bar"></i>
							</div>
							<div class="event-text">
								<strong>Thống kê báo cáo</strong>
								Theo dõi và phân tích dữ liệu tham gia sự kiện
							</div>
						</div>
						
						<div class="event-item">
							<div class="event-icon">
								<i class="fas fa-bullhorn"></i>
							</div>
							<div class="event-text">
								<strong>Thông báo và tin tức</strong>
								Quản lý thông báo đến sinh viên và cộng đồng
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
						<a href="<?= site_url('') ?>">
							<img src="<?= base_url('assets/modules/images/hub-logo.png') ?>" alt="Logo Đại học Ngân hàng TP.HCM" class="mobile-logo">
						</a>
						<h3 class="login-title">ĐĂNG NHẬP QUẢN TRỊ VIÊN</h3>
						<p class="login-subtitle">Quản lý sự kiện và hoạt động của Trường Đại học Ngân hàng TP.HCM</p>
					</div>
					
					<!-- Form Body -->
					<div class="form-body">
						<?= form_open(site_url("login/create"), ['class' => 'row g-3']) ?>
							<div class="row">
								<div class="col-xl-12 mx-auto">
									<!--Error list -->
									<?= $this->include('components/_errors_list'); ?>
									<!--end Error list -->
								</div>
							</div>
							<div class="col-12">
								<label for="u_email" class="form-label">Email quản trị viên</label>
								<input type="text" class="form-control" id="u_email" name="u_email" placeholder="Nhập email quản trị viên">
							</div>
							<div class="col-12">
								<label for="inputChoosePassword" class="form-label">Mật khẩu</label>
								<div class="input-group" id="show_hide_password">
									<input type="password" class="form-control border-end-0" id="inputChoosePassword" name="password" placeholder="Nhập mật khẩu"> 
									<a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
								</div>
							</div>
							<div class="col-12">
								<div class="d-grid">
									<button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Đăng nhập</button>
								</div>
							</div>
						</form>
						
						<div class="col-12 mt-3">
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
