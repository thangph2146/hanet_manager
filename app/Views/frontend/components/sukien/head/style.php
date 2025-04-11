<?php
/**
 * 9/15/2022
 * AUTHOR:PDV-PC
 */
?>
<!-- Additional CSS -->
<?= $this->renderSection('additional_css') ?>
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
		label {
			font-size: 15px;
		}
		
		/* Features Section Styles */
		.features-section {
			padding: 80px 0;
			background: linear-gradient(135deg, rgba(128, 0, 0, 0.03), rgba(128, 0, 0, 0.08));
			position: relative;
			overflow: hidden;
		}
		
		.features-section:before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23800000' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
			opacity: 0.5;
			z-index: 0;
		}
		
		.features-section .container {
			position: relative;
			z-index: 1;
		}
		
		.features-section .section-title {
			text-align: center;
			margin-bottom: 50px;
			position: relative;
			font-weight: 700;
			color: var(--primary-dark);
			font-size: 2.2rem;
			text-transform: uppercase;
			letter-spacing: 1px;
		}
		
		.features-section .section-title:after {
			content: '';
			position: absolute;
			bottom: 15px;
			left: 50%;
			transform: translateX(-50%);
			width: 80px;
			height: 3px;
			background: linear-gradient(90deg, var(--primary-light), var(--primary));
			border-radius: 3px;
		}
		
		.features-section .section-title:before {
			content: '';
			position: absolute;
			bottom: 15px;
			left: 50%;
			transform: translateX(-50%);
			width: 120px;
			height: 3px;
			background: linear-gradient(90deg, var(--primary-light), var(--primary));
			border-radius: 3px;
			opacity: 0.3;
			margin-left: -20px;
		}
		
		.feature-box {
			background: #fff;
			border-radius: 12px;
			padding: 30px;
			height: 100%;
			transition: all 0.4s ease;
			position: relative;
			overflow: hidden;
			box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
			border: 1px solid rgba(128, 0, 0, 0.1);
			z-index: 1;
		}
		
		.feature-box:before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: linear-gradient(135deg, rgba(128, 0, 0, 0.05), rgba(128, 0, 0, 0.1));
			opacity: 0;
			transition: all 0.4s ease;
			z-index: -1;
		}
		
		.feature-box:hover {
			transform: translateY(-10px);
			box-shadow: 0 15px 30px rgba(128, 0, 0, 0.15);
		}
		
		.feature-box:hover:before {
			opacity: 1;
		}
		
		.feature-box:after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 0;
			width: 0;
			height: 3px;
			background: linear-gradient(90deg, var(--primary-light), var(--primary));
			transition: all 0.4s ease;
		}
		
		.feature-box:hover:after {
			width: 100%;
		}
		
		.feature-icon {
			width: 70px;
			height: 70px;
			background: linear-gradient(135deg, var(--primary-light), var(--primary));
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 25px;
			box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2);
			transition: all 0.4s ease;
			position: relative;
			z-index: 2;
		}
		
		.feature-icon:before {
			content: '';
			position: absolute;
			top: -5px;
			left: -5px;
			right: -5px;
			bottom: -5px;
			border-radius: 50%;
			background: linear-gradient(135deg, var(--primary-light), var(--primary));
			opacity: 0.3;
			z-index: -1;
			transition: all 0.4s ease;
		}
		
		.feature-box:hover .feature-icon {
			transform: scale(1.1) rotate(5deg);
		}
		
		.feature-box:hover .feature-icon:before {
			opacity: 0.5;
			transform: scale(1.1);
		}
		
		.feature-icon i {
			font-size: 30px;
			color: #fff;
		}
		
		.feature-box h4 {
			font-weight: 700;
			margin-bottom: 15px;
			color: var(--primary-dark);
			position: relative;
			padding-bottom: 10px;
			font-size: 1.4rem;
			transition: all 0.3s ease;
		}
		
		.feature-box:hover h4 {
			color: var(--primary);
		}
		
		.feature-box h4:after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 0;
			width: 40px;
			height: 2px;
			background: var(--primary);
			transition: all 0.4s ease;
		}
		
		.feature-box:hover h4:after {
			width: 60px;
		}
		
		.feature-box p {
			color: #666;
			line-height: 1.7;
			margin-bottom: 0;
			transition: all 0.3s ease;
		}
		
		.feature-box:hover p {
			color: #444;
		}
		
		/* Feature action button */
		.feature-action {
			opacity: 0;
			transform: translateY(10px);
			transition: all 0.4s ease;
		}
		
		.feature-box:hover .feature-action {
			opacity: 1;
			transform: translateY(0);
		}
		
		.feature-action .btn {
			border-color: var(--primary);
			color: var(--primary);
			font-weight: 600;
			padding: 0.4rem 1rem;
			border-radius: 30px;
			transition: all 0.3s ease;
		}
		
		.feature-action .btn:hover {
			background-color: var(--primary);
			color: #fff;
			transform: translateX(5px);
		}
		
		.feature-action .btn i {
			transition: all 0.3s ease;
		}
		
		.feature-action .btn:hover i {
			transform: translateX(3px);
		}
		
		/* Feature animation classes */
		.animate-on-scroll {
			opacity: 0;
			transform: translateY(30px);
			transition: all 0.6s ease;
		}
		
		.animate-on-scroll.visible {
			opacity: 1;
			transform: translateY(0);
		}
		
		/* Feature animation delay */
		.feature-box:nth-child(1) {
			transition-delay: 0.1s;
		}
		
		.feature-box:nth-child(2) {
			transition-delay: 0.2s;
		}
		
		.feature-box:nth-child(3) {
			transition-delay: 0.3s;
		}
		
		/* Feature counter */
		.feature-counter {
			position: absolute;
			top: 20px;
			right: 20px;
			font-size: 60px;
			font-weight: 800;
			opacity: 0.05;
			color: var(--primary);
			line-height: 1;
			transition: all 0.4s ease;
		}
		
		.feature-box:hover .feature-counter {
			opacity: 0.1;
			transform: scale(1.1);
		}
		
		/* Speakers Section Styles */
		.speakers-section {
			padding: 80px 0;
			background: linear-gradient(135deg, rgba(128, 0, 0, 0.05), rgba(128, 0, 0, 0.1));
			position: relative;
			overflow: hidden;
		}
		
		.speakers-section:before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23800000' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
			opacity: 0.5;
			z-index: 0;
		}
		
		.speakers-section .container {
			position: relative;
			z-index: 1;
		}
		
		.speakers-section .section-title {
			text-align: center;
			margin-bottom: 50px;
			position: relative;
			font-weight: 700;
			color: var(--primary-dark);
			font-size: 2.2rem;
			text-transform: uppercase;
			letter-spacing: 1px;
		}
		
		.speakers-section .section-title:after {
			content: '';
			position: absolute;
			bottom: 15px;
			left: 50%;
			transform: translateX(-50%);
			width: 80px;
			height: 3px;
			background: linear-gradient(90deg, var(--primary-light), var(--primary));
			border-radius: 3px;
		}
		
		.speakers-section .section-title:before {
			content: '';
			position: absolute;
			bottom: 15px;
			left: 50%;
			transform: translateX(-50%);
			width: 120px;
			height: 3px;
			background: linear-gradient(90deg, var(--primary-light), var(--primary));
			border-radius: 3px;
			opacity: 0.3;
			margin-left: -20px;
		}
		
		.speaker-card {
			background: #fff;
			border-radius: 12px;
			padding: 20px;
			text-align: center;
			transition: all 0.4s ease;
			position: relative;
			overflow: hidden;
			box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
			border: 1px solid rgba(128, 0, 0, 0.1);
			height: 100%;
			z-index: 1;
		}
		
		.speaker-card:before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: linear-gradient(135deg, rgba(128, 0, 0, 0.05), rgba(128, 0, 0, 0.1));
			opacity: 0;
			transition: all 0.4s ease;
			z-index: -1;
		}
		
		.speaker-card:hover {
			transform: translateY(-10px);
			box-shadow: 0 15px 30px rgba(128, 0, 0, 0.15);
		}
		
		.speaker-card:hover:before {
			opacity: 1;
		}
		
		.speaker-image-wrapper {
			position: relative;
			width: 150px;
			height: 150px;
			margin: 0 auto 20px;
			border-radius: 8px;
			overflow: hidden;
			transition: all 0.4s ease;
		}
		
		.speaker-card:hover .speaker-image-wrapper {
			transform: scale(1.05);
		}
		
		.speaker-image {
			width: 100%;
			height: 100%;
			object-fit: cover;
			transition: all 0.4s ease;
		}
		
		.speaker-card:hover .speaker-image {
			transform: scale(1.1);
		}
		
		.speaker-social {
			position: absolute;
			bottom: 0;
			left: 0;
			right: 0;
			background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
			padding: 10px;
			display: flex;
			justify-content: center;
			gap: 10px;
			opacity: 0;
			transform: translateY(20px);
			transition: all 0.4s ease;
		}
		
		.speaker-card:hover .speaker-social {
			opacity: 1;
			transform: translateY(0);
		}
		
		.speaker-social a {
			width: 35px;
			height: 35px;
			background: rgba(255, 255, 255, 0.2);
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			color: #fff;
			transition: all 0.3s ease;
		}
		
		.speaker-social a:hover {
			background: var(--primary);
			transform: translateY(-3px);
		}
		
		.speaker-card h5 {
			font-weight: 700;
			margin-bottom: 5px;
			color: var(--primary-dark);
			position: relative;
			padding-bottom: 10px;
			font-size: 1.2rem;
		}
		
		.speaker-card h5:after {
			content: '';
			position: absolute;
			bottom: 0;
			left: 50%;
			transform: translateX(-50%);
			width: 30px;
			height: 2px;
			background: var(--primary);
			transition: all 0.4s ease;
		}
		
		.speaker-card:hover h5:after {
			width: 50px;
		}
		
		.speaker-card p {
			color: #666;
			margin-bottom: 15px;
			font-size: 0.9rem;
		}
		
		.speaker-role {
			display: inline-block;
			background: rgba(128, 0, 0, 0.1);
			color: var(--primary);
			padding: 3px 10px;
			border-radius: 20px;
			font-size: 0.8rem;
			font-weight: 600;
			margin-bottom: 15px;
		}
		
		.speaker-bio {
			font-size: 0.9rem;
			color: #666;
			line-height: 1.6;
			margin-bottom: 0;
			display: -webkit-box;
			-webkit-line-clamp: 3;
			-webkit-box-orient: vertical;
			overflow: hidden;
		}
		
		/* Speaker animation classes */
		.speaker-animate {
			opacity: 0;
			transform: translateY(30px);
			transition: all 0.6s ease;
		}
		
		.speaker-animate.visible {
			opacity: 1;
			transform: translateY(0);
		}
		
		/* Speaker animation delay */
		.speaker-card:nth-child(1) {
			transition-delay: 0.1s;
		}
		
		.speaker-card:nth-child(2) {
			transition-delay: 0.2s;
		}
		
		.speaker-card:nth-child(3) {
			transition-delay: 0.3s;
		}
		
		.speaker-card:nth-child(4) {
			transition-delay: 0.4s;
		}
		
		/* Speaker section button */
		.speakers-section .btn-gradient {
			background: linear-gradient(135deg, var(--primary), var(--primary-light));
			color: #fff;
			border: none;
			padding: 0.6rem 1.5rem;
			border-radius: 30px;
			font-weight: 600;
			box-shadow: 0 5px 15px rgba(128, 0, 0, 0.2);
			transition: all 0.3s ease;
		}
		
		.speakers-section .btn-gradient:hover {
			background: linear-gradient(135deg, var(--primary-light), var(--primary));
			color: #fff;
			transform: translateY(-3px);
			box-shadow: 0 8px 25px rgba(128, 0, 0, 0.3);
		}
		
		.speakers-section .btn-gradient i {
			transition: all 0.3s ease;
		}
		
		.speakers-section .btn-gradient:hover i {
			transform: translateX(3px);
		}
		
		/* Button animation */
		.speakers-section .text-center {
			opacity: 0;
			transform: translateY(20px);
			transition: all 0.6s ease;
		}
		
		.speakers-section .text-center.visible {
			opacity: 1;
			transform: translateY(0);
		}
	</style>
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			const observer = new IntersectionObserver((entries) => {
				entries.forEach(entry => {
					if (entry.isIntersecting) {
						entry.target.classList.add('visible');
					}
				});
			}, {
				threshold: 0.1
			});
			
			document.querySelectorAll('.speakers-section .text-center').forEach(el => {
				observer.observe(el);
			});
		});
	</script>