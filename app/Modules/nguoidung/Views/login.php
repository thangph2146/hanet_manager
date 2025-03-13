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
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<title>Đại học Ngân hàng TP.HCM - Sự kiện đặc biệt</title>
	<style>
		:root {
			--primary-color: #0a4b78;
			--secondary-color: #ffc107;
			--accent-color: #e63946;
			--text-color: #333;
			--light-color: #f8f9fa;
			--dark-color: #212529;
		}
		
		body {
			font-family: 'Montserrat', sans-serif;
			margin: 0;
			padding: 0;
			height: 100vh;
			overflow-x: hidden;
			color: var(--text-color);
		}
		
		#particles-js {
			position: absolute;
			width: 100%;
			height: 100%;
			background-color: var(--primary-color);
			background-image: url('https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
			background-repeat: no-repeat;
			background-size: cover;
			background-position: center;
			z-index: -2;
		}
		
		.overlay {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: linear-gradient(135deg, rgba(10, 75, 120, 0.9), rgba(0, 0, 0, 0.8));
			z-index: -1;
		}
		
		.wrapper {
			position: relative;
			z-index: 1;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
		}
		
		.card {
			border: none;
			border-radius: 20px;
			box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
			overflow: hidden;
			backdrop-filter: blur(10px);
			background-color: rgba(255, 255, 255, 0.95);
			transform: translateY(0);
			transition: all 0.5s ease;
		}
		
		.card:hover {
			transform: translateY(-5px);
			box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
		}
		
		.card-header {
			background: linear-gradient(135deg, var(--primary-color), #083658);
			color: white;
			text-align: center;
			padding: 25px 20px;
			border-radius: 20px 20px 0 0;
			position: relative;
			overflow: hidden;
		}
		
		.card-header::before {
			content: '';
			position: absolute;
			top: -50%;
			left: -50%;
			width: 200%;
			height: 200%;
			background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
			transform: rotate(30deg);
		}
		
		.card-body {
			padding: 40px 30px;
		}
		
		.btn {
			border-radius: 30px;
			padding: 12px 25px;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 1px;
			transition: all 0.4s ease;
			position: relative;
			overflow: hidden;
		}
		
		.btn::after {
			content: '';
			position: absolute;
			top: 50%;
			left: 50%;
			width: 0;
			height: 0;
			background: rgba(255, 255, 255, 0.2);
			border-radius: 50%;
			transform: translate(-50%, -50%);
			transition: width 0.6s ease, height 0.6s ease;
		}
		
		.btn:hover::after {
			width: 300px;
			height: 300px;
		}
		
		.btn-primary {
			background: linear-gradient(45deg, var(--primary-color), #0d6efd);
			border: none;
			box-shadow: 0 5px 15px rgba(10, 75, 120, 0.4);
		}
		
		.btn-primary:hover {
			background: linear-gradient(45deg, #0d6efd, var(--primary-color));
			transform: translateY(-3px);
			box-shadow: 0 8px 20px rgba(10, 75, 120, 0.5);
		}
		
		.btn-danger {
			background: linear-gradient(45deg, #dc3545, #c81e2b);
			border: none;
			box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
		}
		
		.btn-danger:hover {
			background: linear-gradient(45deg, #c81e2b, #dc3545);
			transform: translateY(-3px);
			box-shadow: 0 8px 20px rgba(220, 53, 69, 0.5);
		}
		
		.form-control {
			border-radius: 30px;
			padding: 12px 20px;
			border: 2px solid #e9ecef;
			transition: all 0.3s ease;
			font-size: 15px;
		}
		
		.form-control:focus {
			border-color: var(--primary-color);
			box-shadow: 0 0 0 0.25rem rgba(10, 75, 120, 0.25);
			transform: translateY(-2px);
		}
		
		.input-group-text {
			border-radius: 0 30px 30px 0;
			background: transparent;
			border-left: none;
		}
		
		.login-separater {
			position: relative;
			text-align: center;
			margin-bottom: 30px;
		}
		
		.login-separater span {
			background: white;
			padding: 0 20px;
			position: relative;
			z-index: 1;
			color: var(--primary-color);
			font-weight: 600;
			letter-spacing: 1px;
		}
		
		.login-separater hr {
			position: absolute;
			top: 50%;
			left: 0;
			width: 100%;
			margin: 0;
			border-top: 2px solid #e9ecef;
		}
		
		.logo-container {
			text-align: center;
			margin-bottom: 20px;
			position: relative;
		}
		
		.logo-container img {
			max-width: 120px;
			margin-bottom: 15px;
			filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.2));
			animation: float 3s ease-in-out infinite;
		}
		
		@keyframes float {
			0% { transform: translateY(0px); }
			50% { transform: translateY(-10px); }
			100% { transform: translateY(0px); }
		}
		
		.event-badge {
			position: absolute;
			top: 15px;
			right: 15px;
			background: linear-gradient(45deg, var(--secondary-color), #ff9800);
			color: #000;
			padding: 8px 20px;
			border-radius: 30px;
			font-weight: 600;
			box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3);
			animation: pulse 2s infinite;
			z-index: 10;
		}
		
		@keyframes pulse {
			0% { transform: scale(1); box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3); }
			50% { transform: scale(1.05); box-shadow: 0 5px 20px rgba(255, 193, 7, 0.5); }
			100% { transform: scale(1); box-shadow: 0 5px 15px rgba(255, 193, 7, 0.3); }
		}
		
		.page-footer {
			background: rgba(255, 255, 255, 0.9);
			left: 0px;
			right: 0;
			bottom: 0;
			position: relative;
			text-align: center;
			padding: 15px;
			font-size: 14px;
			border-top: 1px solid #e4e4e4;
			z-index: 3;
			color: var(--primary-color);
			margin-top: auto;
		}
		
		.social-icons {
			margin-top: 30px;
			text-align: center;
		}
		
		.social-icons a {
			display: inline-block;
			width: 45px;
			height: 45px;
			line-height: 45px;
			text-align: center;
			border-radius: 50%;
			background: #f8f9fa;
			color: var(--primary-color);
			margin: 0 8px;
			transition: all 0.4s ease;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
		}
		
		.social-icons a:hover {
			background: var(--primary-color);
			color: white;
			transform: translateY(-5px) rotate(360deg);
			box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
		}
		
		.alert {
			border-radius: 15px;
			padding: 15px;
			margin-bottom: 25px;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
			border: none;
		}
		
		.alert-warning {
			background-color: rgba(255, 193, 7, 0.2);
			border-left: 4px solid var(--secondary-color);
			color: #856404;
		}
		
		.alert-danger {
			background-color: rgba(220, 53, 69, 0.1);
			border-left: 4px solid var(--accent-color);
			color: #721c24;
		}
		
		.form-check-input:checked {
			background-color: var(--primary-color);
			border-color: var(--primary-color);
		}
		
		.text-primary {
			color: var(--primary-color) !important;
		}
		
		.countdown-container {
			display: flex;
			justify-content: center;
			margin: 20px 0;
		}
		
		.countdown-item {
			margin: 0 10px;
			text-align: center;
		}
		
		.countdown-number {
			background: linear-gradient(135deg, var(--primary-color), #0d6efd);
			color: white;
			border-radius: 10px;
			padding: 10px;
			width: 60px;
			height: 60px;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 24px;
			font-weight: bold;
			box-shadow: 0 5px 15px rgba(10, 75, 120, 0.3);
		}
		
		.countdown-label {
			margin-top: 5px;
			font-size: 12px;
			color: var(--primary-color);
			font-weight: 500;
		}
		
		.event-info {
			text-align: center;
			margin-top: 20px;
			padding: 15px;
			background-color: rgba(10, 75, 120, 0.1);
			border-radius: 15px;
		}
		
		.event-info h5 {
			color: var(--primary-color);
			margin-bottom: 10px;
		}
		
		.event-info p {
			margin-bottom: 5px;
			font-size: 14px;
		}
		
		.event-info i {
			margin-right: 5px;
			color: var(--primary-color);
		}
		
		@media (max-width: 768px) {
			.card-body {
				padding: 30px 20px;
			}
			
			.countdown-number {
				width: 50px;
				height: 50px;
				font-size: 20px;
			}
		}
	</style>
</head>

<body>
<div id="particles-js"></div>
<div class="overlay"></div>

<!--wrapper-->
<div class="wrapper">
	<div class="row mt-4">
		<div class="col-xl-12 mx-auto">
			<!-- warning -->
			<?php helper('App\Modules\nguoidung\Helpers\session'); ?>
			<?php if (nguoidung_session_has('warning')) : ?>
				<div class="container">
					<div class="alert alert-warning">
						<i class="fas fa-exclamation-triangle me-2"></i><?= nguoidung_session_get('warning') ?>
					</div>
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
						<div class="card-header">
							<div class="event-badge">Sự kiện đặc biệt</div>
							<div class="logo-container">
								<img src="https://www.buh.edu.vn/images/logo.png" alt="Logo Đại học Ngân hàng TP.HCM">
								<h4 class="text-white mb-0">ĐẠI HỌC NGÂN HÀNG TP.HCM</h4>
								<p class="text-white-50 small">Banking University of Ho Chi Minh City</p>
							</div>
						</div>
						<div class="card-body">
							<div class="border p-4 rounded">
								<div class="text-center">
									<h3 class="text-primary fw-bold">HỆ THỐNG QUẢN LÝ</h3>
									<p>Đăng nhập để tham gia sự kiện</p>
								</div>
								
								<div class="login-separater text-center mb-4"> 
									<span>ĐĂNG NHẬP</span>
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
														<ul class="mb-0">
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
											<label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
											<input type="email" class="form-control" id="email" name="email" placeholder="Nhập email của bạn" value="<?= old('email') ?>">
										</div>
										<div class="col-12">
											<label for="password" class="form-label"><i class="fas fa-lock me-2"></i>Mật khẩu</label>
											<div class="input-group" id="show_hide_password">
												<input type="password" class="form-control border-end-0" id="password" name="password" placeholder="Nhập mật khẩu của bạn"> 
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
											<a href="<?= site_url("nguoidung/forgot-password") ?>" class="text-primary">Quên mật khẩu?</a>
										</div>
										<div class="col-12">
											<div class="d-grid">
												<button type="submit" class="btn btn-primary"><i class="fas fa-sign-in-alt me-2"></i>Đăng nhập</button>
											</div>
										</div>
									</form>
									
									<div class="col-12 mt-4">
										<div class="login-separater text-center mb-3"> 
											<hr/>
										</div>
										<div class="d-grid">
											<a href="<?= $googleAuthUrl ?>" class="btn btn-danger"><i class="fab fa-google me-2"></i>Đăng nhập với Google</a>
										</div>
									</div>
									
									<div class="social-icons">
										<a href="#"><i class="fab fa-facebook-f"></i></a>
										<a href="#"><i class="fab fa-twitter"></i></a>
										<a href="#"><i class="fab fa-youtube"></i></a>
										<a href="#"><i class="fab fa-instagram"></i></a>
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
	<footer class="page-footer">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<p class="mb-0">© <?= date('Y') ?> Đại học Ngân hàng TP.HCM - Phát triển bởi Phòng Công nghệ Thông tin</p>
				</div>
			</div>
		</div>
	</footer>
</div>
<!--end wrapper-->
<!-- Bootstrap JS -->
<script src="<?= site_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
<!--plugins-->
<script src="<?= site_url('assets/js/jquery.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/metismenu/js/metisMenu.min.js') ?>"></script>
<script src="<?= site_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
<!-- Particles.js -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
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
    
    // Particles.js initialization
    particlesJS("particles-js", {
      "particles": {
        "number": {
          "value": 80,
          "density": {
            "enable": true,
            "value_area": 800
          }
        },
        "color": {
          "value": "#ffffff"
        },
        "shape": {
          "type": "circle",
          "stroke": {
            "width": 0,
            "color": "#000000"
          },
          "polygon": {
            "nb_sides": 5
          }
        },
        "opacity": {
          "value": 0.5,
          "random": false,
          "anim": {
            "enable": false,
            "speed": 1,
            "opacity_min": 0.1,
            "sync": false
          }
        },
        "size": {
          "value": 3,
          "random": true,
          "anim": {
            "enable": false,
            "speed": 40,
            "size_min": 0.1,
            "sync": false
          }
        },
        "line_linked": {
          "enable": true,
          "distance": 150,
          "color": "#ffffff",
          "opacity": 0.4,
          "width": 1
        },
        "move": {
          "enable": true,
          "speed": 3,
          "direction": "none",
          "random": false,
          "straight": false,
          "out_mode": "out",
          "bounce": false,
          "attract": {
            "enable": false,
            "rotateX": 600,
            "rotateY": 1200
          }
        }
      },
      "interactivity": {
        "detect_on": "canvas",
        "events": {
          "onhover": {
            "enable": true,
            "mode": "grab"
          },
          "onclick": {
            "enable": true,
            "mode": "push"
          },
          "resize": true
        },
        "modes": {
          "grab": {
            "distance": 140,
            "line_linked": {
              "opacity": 1
            }
          },
          "bubble": {
            "distance": 400,
            "size": 40,
            "duration": 2,
            "opacity": 8,
            "speed": 3
          },
          "repulse": {
            "distance": 200,
            "duration": 0.4
          },
          "push": {
            "particles_nb": 4
          },
          "remove": {
            "particles_nb": 2
          }
        }
      },
      "retina_detect": true
    });
    
    // Countdown Timer
    // Set the date we're counting down to
    var countDownDate = new Date("June 15, 2023 08:00:00").getTime();
    
    // Update the count down every 1 second
    var x = setInterval(function() {
      // Get today's date and time
      var now = new Date().getTime();
        
      // Find the distance between now and the count down date
      var distance = countDownDate - now;
        
      // Time calculations for days, hours, minutes and seconds
      var days = Math.floor(distance / (1000 * 60 * 60 * 24));
      var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
      var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
      // Output the result
      document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
      document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
      document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
      document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;
        
      // If the count down is over
      if (distance < 0) {
        clearInterval(x);
        document.getElementById("days").innerHTML = "00";
        document.getElementById("hours").innerHTML = "00";
        document.getElementById("minutes").innerHTML = "00";
        document.getElementById("seconds").innerHTML = "00";
      }
    }, 1000);
  });
</script>

<!--app JS-->
<script src="<?= site_url('assets/js/app.js') ?>"></script>
</body>

</html>