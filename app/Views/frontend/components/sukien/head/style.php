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