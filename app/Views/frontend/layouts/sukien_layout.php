<?php
/**
 * Layout chung cho module sukien
 * Trường Đại học Ngân hàng TP.HCM
 */
?>
<!DOCTYPE html>
<html lang="vi">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?= $this->renderSection('description') ?? 'Sự Kiện Đại Học Ngân Hàng TP.HCM - Thông tin về các sự kiện, hội thảo, workshop của Trường Đại học Ngân hàng TP.HCM' ?>">
	<meta name="keywords" content="<?= $this->renderSection('keywords') ?? 'đại học ngân hàng, sự kiện hub, hội thảo, workshop, nghề nghiệp, sinh viên' ?>">
	<meta name="author" content="Trường Đại học Ngân hàng TP.HCM">
	<meta property="og:title" content="<?= $this->renderSection('title') ?? 'Sự Kiện Đại Học Ngân Hàng TP.HCM' ?>">
	<meta property="og:description" content="<?= $this->renderSection('description') ?? 'Sự Kiện Đại Học Ngân Hàng TP.HCM - Thông tin về các sự kiện, hội thảo, workshop của Trường Đại học Ngân hàng TP.HCM' ?>">
	<meta property="og:image" content="<?= $this->renderSection('og_image') ?? base_url('assets/images/hub-logo.png') ?>">
	<meta property="og:url" content="<?= current_url() ?>">
	<meta property="og:type" content="website">
	<meta name="twitter:card" content="summary_large_image">
	<meta name="robots" content="index, follow">
	<link rel="canonical" href="<?= current_url() ?>">
	
	<!--favicon-->
	<link rel="icon" href="<?= base_url('assets/images/favicon-32x32.png') ?>" type="image/png" />
	
	<!--plugins-->
	<link href="<?= base_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
	<link href="<?= base_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
	<link href="<?= base_url('assets/plugins/metismenu/css/metisMenu.min.css') ?>" rel="stylesheet" />
	
	<!-- LineIcons CSS -->
	<link href="https://cdn.lineicons.com/3.0/lineicons.css" rel="stylesheet">
	
	<!-- Font Awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
	
	<!-- AOS - Animate On Scroll -->
	<link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet">
	
	<!-- loader-->
	<link href="<?= base_url('assets/css/pace.min.css') ?>" rel="stylesheet" />
	<script src="<?= base_url('assets/js/pace.min.js') ?>"></script>
	
	<!-- Bootstrap CSS -->
	<link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet">
	<link href="<?= base_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet">
	<link href="<?= base_url('assets/css/app.css') ?>" rel="stylesheet">
	<link href="<?= base_url('assets/css/icons.css') ?>" rel="stylesheet">
	
	<!-- Module specific CSS -->
	<link href="<?= base_url('assets/modules/sukien/css/style.css') ?>" rel="stylesheet">
	
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
	
	<title><?= $this->renderSection('title') ?></title>
	
	<!-- Structured Data for Events -->
	<?= $this->renderSection('structured_data') ?>
	
	<!-- Additional CSS -->
	<?= $this->renderSection('additional_css') ?>
	
	<!-- CSS tùy chỉnh để tối ưu màu sắc -->
	<style>
		:root {
			--primary: #800000;          /* Đỏ đô chủ đạo */
			--primary-dark: #5c0000;     /* Đỏ đô đậm */
			--primary-darker: #400000;   /* Đỏ đô rất đậm */
			--primary-light: #a30000;    /* Đỏ đô nhạt */
			--primary-lighter: #cc0000;  /* Đỏ đô rất nhạt */
			--white: #ffffff;
			--light-gray: #f8f8f8;
			--shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
			--shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
			--shadow-primary: 0 5px 15px rgba(128, 0, 0, 0.2);
		}
		
		/* Tối ưu màu sắc cho navbar */
		.navbar {
			background: linear-gradient(135deg, var(--primary-dark), var(--primary)) !important;
			box-shadow: 0 2px 15px rgba(0, 0, 0, 0.2);
		}
		
		.navbar-brand {
			padding: 0.5rem 0;
		}
		
		.navbar-brand img {
			height: 45px;
			filter: brightness(1.05);
		}
		
		.navbar-dark .navbar-nav .nav-link {
			color: #ffffff !important;
			font-weight: 600;
			padding: 0.5rem 1rem;
			transition: all 0.3s ease;
			position: relative;
			text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
		}
		
		.navbar-dark .navbar-nav .nav-link:hover {
			color: rgba(255, 255, 255, 1) !important;
			transform: translateY(-2px);
		}
		
		.navbar-dark .navbar-nav .nav-link.active {
			color: var(--white);
			font-weight: 600;
		}
		
		.navbar-dark .navbar-nav .nav-link.active::after {
			content: '';
			position: absolute;
			bottom: -2px;
			left: 1rem;
			right: 1rem;
			height: 2px;
			background-color: var(--white);
		}
		
		.navbar .btn {
			font-weight: 500;
			padding: 0.5rem 1rem;
			border-radius: 4px;
			transition: all 0.3s ease;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
		}
		
		.navbar .btn-light {
			background: rgba(255, 255, 255, 0.9);
			border: none;
			color: var(--primary);
		}
		
		.navbar .btn-light:hover {
			background: var(--white);
			transform: translateY(-2px);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
		}
		
		.navbar .btn-outline-light {
			border: 1px solid rgba(255, 255, 255, 0.7);
			color: var(--white);
		}
		
		.navbar .btn-outline-light:hover {
			background: rgba(255, 255, 255, 0.1);
			transform: translateY(-2px);
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
		}
		
		.navbar .btn i {
			margin-right: 5px;
		}
		
		/* Footer Styles */
		footer {
			background: linear-gradient(135deg, var(--primary-dark), var(--primary)) !important;
			color: #ffffff;
			padding: 70px 0 30px;
			position: relative;
			border-top: 1px solid rgba(255, 255, 255, 0.1);
		}
		
		footer::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-image: url('<?= base_url('assets/images/pattern.png') ?>');
			opacity: 0.03;
			z-index: 0;
		}
		
		footer .container {
			position: relative;
			z-index: 1;
		}
		
		.footer-title {
			color: #ffffff !important;
			font-weight: 700;
			margin-bottom: 1.5rem;
			position: relative;
			padding-left: 1rem;
			border-left: 3px solid #ffffff;
		}
		
		footer p, footer a {
			color: rgba(255, 255, 255, 0.9) !important;
			line-height: 1.7;
		}
		
		footer ul li {
			margin-bottom: 12px;
		}
		
		.footer-link {
			color: rgba(255, 255, 255, 0.8);
			transition: all 0.3s ease;
			display: inline-block;
		}
		
		.footer-link:hover {
			color: var(--white);
			transform: translateX(5px);
			text-decoration: none;
		}
		
		footer .contact-item {
			display: flex;
			align-items: flex-start;
			margin-bottom: 1rem;
		}
		
		footer .contact-icon {
			color: rgba(255, 255, 255, 0.9);
			margin-right: 10px;
			font-size: 1.1rem;
			margin-top: 3px;
		}
		
		.social-icon {
			background: rgba(255, 255, 255, 0.1);
			color: #ffffff !important;
			width: 40px;
			height: 40px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			border-radius: 50%;
			margin-right: 10px;
			transition: all 0.3s ease;
		}
		
		.social-icon:hover {
			background: rgba(255, 255, 255, 0.2);
			transform: translateY(-3px);
			color: #ffffff !important;
		}
		
		.footer-bottom {
			margin-top: 3rem;
			padding-top: 1.5rem;
			border-top: 1px solid rgba(255, 255, 255, 0.1);
		}
		
		.footer-bottom p {
			margin-bottom: 0;
			font-size: 0.9rem;
		}
		
		/* Back to Top Button */
		.back-to-top {
			position: fixed;
			bottom: 30px;
			right: 30px;
			width: 45px;
			height: 45px;
			background: linear-gradient(135deg, var(--primary-dark), var(--primary));
			color: #ffffff;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
			transition: all 0.3s ease;
			z-index: 999;
		}
		
		.back-to-top:hover {
			background: linear-gradient(135deg, var(--primary), var(--primary-light));
			color: #ffffff;
			transform: translateY(-5px);
			box-shadow: 0 8px 25px rgba(128, 0, 0, 0.25);
		}
		
		/* Tối ưu màu sắc cho dropdown menu */
		.dropdown-menu {
			background: var(--bg-white);
			border: none;
			border-radius: 8px;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
			padding: 0.5rem;
			margin-top: 0.5rem;
			border-top: 3px solid var(--primary);
		}
		
		.dropdown-item {
			padding: 0.6rem 1rem;
			border-radius: 4px;
			transition: all 0.2s ease;
			color: var(--text-dark);
			font-weight: 500;
		}
		
		.dropdown-item:hover, .dropdown-item:focus {
			background-color: var(--primary-ultra-light);
			color: var(--primary);
			transform: translateX(5px);
		}
		
		.dropdown-item.active, .dropdown-item:active {
			background-color: var(--primary);
			color: #ffffff;
		}
	</style>
</head>

<body>
	<!-- Navbar -->
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

	<!-- Content Area -->
	<main>
		<?= $this->renderSection('content') ?>
	</main>

	<!-- Footer -->
	<footer>
		<div class="container">
			<div class="row g-4">
				<div class="col-lg-4">
					<h5 class="footer-title">Về chúng tôi</h5>
					<p>
						Trường Đại học Ngân hàng TP.HCM là một trong những cơ sở giáo dục đại học hàng đầu trong lĩnh vực tài chính - ngân hàng tại Việt Nam.
					</p>
					<div class="mt-4">
						<a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
						<a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
						<a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
						<a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
					</div>
				</div>
				<div class="col-lg-2 col-md-4">
					<h5 class="footer-title">Liên kết</h5>
					<ul class="list-unstyled">
						<li>
							<a href="#" class="footer-link">Trang chủ</a>
						</li>
						<li>
							<a href="#" class="footer-link">Sự kiện</a>
						</li>
						<li>
							<a href="#" class="footer-link">Giới thiệu</a>
						</li>
						<li>
							<a href="#" class="footer-link">Liên hệ</a>
						</li>
					</ul>
				</div>
				<div class="col-lg-3 col-md-4">
					<h5 class="footer-title">Liên hệ</h5>
					<ul class="list-unstyled">
						<li class="contact-item">
							<i class="fas fa-map-marker-alt contact-icon"></i>
							<span class="contact-text">36 Tôn Thất Đạm, Q.1, TP.HCM</span>
						</li>
						<li class="contact-item">
							<i class="fas fa-phone contact-icon"></i>
							<span class="contact-text">(028) 38 212 593</span>
						</li>
						<li class="contact-item">
							<i class="fas fa-envelope contact-icon"></i>
							<span class="contact-text">info@hub.edu.vn</span>
						</li>
					</ul>
				</div>
				<div class="col-lg-3 col-md-4">
					<h5 class="footer-title">Bản đồ</h5>
					<div class="rounded overflow-hidden">
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.6219257597554!2d106.70230851744384!3d10.765753899999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f4949dbf177%3A0x77c434e30fb9ce86!2zVHLGsOG7nW5nIMSQ4bqhaSBI4buNYyBOZ8OibiBIw6BuZyBUUC5IQ00!5e0!3m2!1svi!2s!4v1647942508574!5m2!1svi!2s" width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
					</div>
				</div>
			</div>
			<div class="footer-bottom">
				<div class="row">
					<div class="col-md-6 text-center text-md-start">
						<p>&copy; <?= date('Y') ?> Trường Đại học Ngân hàng TP.HCM</p>
					</div>
					<div class="col-md-6 text-center text-md-end">
						<p>Thiết kế bởi Ban Truyền thông HUB</p>
					</div>
				</div>
			</div>
		</div>
	</footer>

	<!-- Back to Top -->
	<a href="#" class="back-to-top">
		<i class="fas fa-arrow-up"></i>
	</a>

	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
	<!-- LineIcons JS -->
	<script src="https://cdn.lineicons.com/3.0/lineicons.js"></script>
	<!-- Custom JS -->
	<script src="<?= base_url('assets/modules/sukien/js/scripts.js') ?>"></script>
	
	<!-- JavaScript Libraries -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
	<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/parallax.js/1.5.0/parallax.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/2.0.0/countUp.min.js"></script>
	
	<!-- Custom JavaScript -->
	<script>
		// Initialize AOS
		AOS.init({
			duration: 800,
			once: true
		});
		
		// Initialize Particles.js
		particlesJS('particles-js', {
			particles: {
				number: { value: 80, density: { enable: true, value_area: 800 } },
				color: { value: '#ffffff' },
				shape: { type: 'circle' },
				opacity: { value: 0.5, random: false },
				size: { value: 3, random: true },
				line_linked: { enable: true, distance: 150, color: '#ffffff', opacity: 0.4, width: 1 },
				move: { enable: true, speed: 6, direction: 'none', random: false, straight: false, out_mode: 'out', bounce: false }
			},
			interactivity: {
				detect_on: 'canvas',
				events: {
					onhover: { enable: true, mode: 'repulse' },
					onclick: { enable: true, mode: 'push' },
					resize: true
				}
			},
			retina_detect: true
		});
		
		// Initialize Parallax
		document.addEventListener('DOMContentLoaded', function() {
			const elements = document.querySelectorAll('.floating-shapes span');
			if (elements.length > 0) {
				elements.forEach(shape => {
					new Parallax(shape);
				});
			}
		});
		
		// Initialize CountUp
		document.addEventListener('DOMContentLoaded', function() {
			const counters = document.querySelectorAll('.counter');
			if (counters.length > 0) {
				counters.forEach(counter => {
					const countUp = new CountUp(counter, counter.textContent, {
						duration: 2.5,
						separator: ',',
						decimal: '.'
					});
					countUp.start();
				});
			}
		});
		
		// Smooth Scroll
		document.querySelectorAll('a[href^="#"]').forEach(anchor => {
			anchor.addEventListener('click', function (e) {
				e.preventDefault();
				const target = document.querySelector(this.getAttribute('href'));
				if (target) {
					target.scrollIntoView({
						behavior: 'smooth'
					});
				}
			});
		});
		
		// Back to Top Button
		window.addEventListener('scroll', function() {
			const backToTop = document.querySelector('.back-to-top');
			if (window.pageYOffset > 300) {
				backToTop.style.opacity = '1';
				backToTop.style.visibility = 'visible';
			} else {
				backToTop.style.opacity = '0';
				backToTop.style.visibility = 'hidden';
			}
		});
	</script>
	
	<?= $this->renderSection('scripts') ?>
</body>

</html> 