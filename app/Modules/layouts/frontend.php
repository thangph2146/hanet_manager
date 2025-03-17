<?php
/**
 * 6/10/2022
 * AUTHOR:PDV-PC
 */
?>
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
	<title><?= $this->renderSection("title") ?></title>
</head>

<body>
<!--wrapper-->
<div class="wrapper">
	<?= $this->renderSection("content") ?>
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
<script src="<?= site_url('assets/js/show-hide-password.js') ?>"></script>
<!--app JS-->
<script src="<?= site_url('assets/js/app.js') ?>"></script>
</body>

</html>
