<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<?= $this->include('components/_css') ?>
	<title><?= $this->renderSection("title") ?></title>
</head>

<body>
<!--wrapper-->
<div class="wrapper">
	<!--sidebar wrapper -->
	<div class="sidebar-wrapper" data-simplebar="true">
		<?= $this->include('components/_sidebar') ?>
		<!--navigation-->
		<?= $this->include('components/_nav') ?>
		<!--end navigation-->
	</div>
	<!--end sidebar wrapper -->
	<!--start header -->
	<?= $this->include('components/_header') ?>
	<!--end header -->
	<!--start page wrapper -->
	<div class="page-wrapper">
		<div class="page-content">
			<?= $this->renderSection("bread_cum_link") ?>
			<!-- content -->
			<div class="row">
				<div class="col-xl-12 mx-auto">
					<h6 class="mb-0 text-uppercase"><?= $this->renderSection("title_content") ?></h6>
					<hr/>
					<!-- warning -->
					<?= $this->include('components/_warning') ?>
					<!-- end warning -->
					<!--Error list -->
					<?= $this->include('components/_errors_list'); ?>
					<!--end Error list -->
				</div>
			</div>
			<?= $this->renderSection("content") ?>
			<!-- end content -->
		</div>
	</div>
	<!--end page wrapper -->
	<!-- footer -->
	<?= $this->include('components/_footer') ?>
	<!--end footer -->
</div>
<!--end wrapper-->
<!--start switcher-->
<?= $this->include('components/_switcher') ?>
<!--end switcher-->
<!-- js -->
<?= $this->include('components/_js') ?>
<!-- end js -->
<?= $this->renderSection("script") ?>
</body>

</html>
