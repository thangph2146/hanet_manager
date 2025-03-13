<!doctype html>
<html lang="en">

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
	<title>Hệ thống Quản lý - Đăng nhập</title>
	<style>
		.page-footer {
			background: #fff;
			left: 0px;
			right: 0;
			bottom: 0;
			position: fixed;
			text-align: center;
			padding: 7px;
			font-size: 14px;
			border-top: 1px solid #e4e4e4;
			z-index: 3
		}
	</style>
</head>

<body class="bg-login">
<!--wrapper-->
<div class="wrapper">
	<div class="row">
		<div class="col-xl-12 mx-auto">
			<!-- warning -->
			<?php helper('App\Modules\nguoidung\Helpers\session'); ?>
			<?php if (nguoidung_session_has('warning')) : ?>
				<div class="alert alert-warning">
					<?= nguoidung_session_get('warning') ?>
				</div>
			<?php endif; ?>
			<!-- end warning -->
		</div>
	</div>
	<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
		<div class="container-fluid">
			<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
				<div class="col mx-auto">
					<div class="card">
						<div class="card-body">
							<div class="border p-4 rounded">
								<div class="text-center">
									<h3 class="">Hệ thống Quản lý</h3>
									<p>Đăng nhập</p>
								</div>
								<div class="login-separater text-center mb-4"> <span>Người dùng</span>
									<hr/>
								</div>
								<div class="form-body">
									<form action="<?= base_url('nguoidung/login/authenticate') ?>" method="post" class="row g-3">
										<?= csrf_field() ?>
										<div class="row">
											<div class="col-xl-12 mx-auto">
												<!--Error list -->
												<?php if (nguoidung_session_has('errors')) : ?>
													<div class="alert alert-danger">
														<ul>
															<?php foreach (nguoidung_session_get('errors') as $error) : ?>
																<li><?= $error ?></li>
															<?php endforeach; ?>
														</ul>
													</div>
												<?php endif; ?>
												<!--end Error list -->
											</div>
										</div>
										<div class="col-12">
											<label for="email" class="form-label">Email</label>
											<input type="email" class="form-control" id="email" name="email" placeholder="Nhập email" value="<?= old('email') ?>">
										</div>
										<div class="col-12">
											<label for="password" class="form-label">Mật khẩu</label>
											<div class="input-group" id="show_hide_password">
												<input type="password" class="form-control border-end-0" id="password" name="password" placeholder="Nhập mật khẩu"> 
												<a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-check form-switch">
												<input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" <?php if (old('remember')): ?>checked<?php endif; ?>>
												<label class="form-check-label" for="remember">Nhớ mật khẩu</label>
											</div>
										</div>
										<div class="col-md-6 text-end">	
											<a href="<?= site_url("nguoidung/forgot-password") ?>">Quên mật khẩu?</a>
										</div>
										<div class="col-12">
											<div class="d-grid">
												<button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Đăng nhập</button>
											</div>
										</div>
									</form>
									
									<div class="col-12 mt-3">
										<div class="d-grid">
											<a href="<?= $googleAuthUrl ?>" class="btn btn-danger"><i class="bx bxl-google"></i>Đăng nhập với Google</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--end row-->
		</div>
	</div>
</div>
<!--end wrapper-->
<!-- Bootstrap JS -->
<script src="<?= site_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<!--plugins-->
<script src="<?= site_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
<!--Password show & hide js -->
<script>
  $(document).ready(function () {
    $("#show_hide_password a").on('click', function (event) {
      event.preventDefault();
      if ($('#show_hide_password input').attr("type") == "text") {
        $('#show_hide_password input').attr('type', 'password');
        $('#show_hide_password i').addClass("bx-hide");
        $('#show_hide_password i').removeClass("bx-show");
      } else if ($('#show_hide_password input').attr("type") == "password") {
        $('#show_hide_password input').attr('type', 'text');
        $('#show_hide_password i').removeClass("bx-hide");
        $('#show_hide_password i').addClass("bx-show");
      }
    });
  });
</script>

<!--app JS-->
<script src="<?= site_url('assets/js/app.js') ?>"></script>
</body>

</html> 