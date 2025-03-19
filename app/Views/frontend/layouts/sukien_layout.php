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
	<?= $this->include('frontend/components/sukien/head/meta') ?>

	<!-- Link href -->
	<?= $this->include('frontend/components/sukien/head/link_href') ?>
	
	<!-- Structured Data for Events -->
	<?= $this->renderSection('structured_data') ?>
	
	<!-- CSS tùy chỉnh để tối ưu màu sắc -->
	<?= $this->include('frontend/components/sukien/head/style') ?>

	<!-- Additional CSS -->
	<?= $this->renderSection('styles') ?>

</head>

<body>
	<!-- Navbar -->
	<?= $this->include('frontend/components/sukien/body/navbar') ?>

	<!-- Content Area -->
	<main>
		<?= $this->renderSection('content') ?>
	</main>

	<!-- Footer -->
	<?= $this->include('frontend/components/sukien/body/footer') ?>

	<!-- Back to Top -->
	<?= $this->include('frontend/components/sukien/back_to_top') ?>

	<!-- JS -->
	<?= $this->include('frontend/components/sukien/body/js') ?>

	<!-- Additional JS -->
	<?= $this->renderSection('scripts') ?>
	
</body>

</html> 