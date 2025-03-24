<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= camera_css('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI CAMERA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới Camera',
	'dashboard_url' => site_url('camera/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Camera', 'url' => site_url('camera')],
		['title' => 'Thêm mới Camera', 'active' => true]
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
	<div class="card-header py-3">
		<h5 class="card-title mb-0">Thêm mới camera</h5>
	</div>
	<div class="card-body">
		<?= form_open(site_url('camera/create'), ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-camera']) ?>
			<?php
			// Include form fields
			include __DIR__ . '/form.php';
			?>
		<?= form_close() ?>
	</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= camera_js('form') ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const form = document.getElementById('form-camera');
		
		// Validate form khi submit
		form.addEventListener('submit', function (event) {
			if (!form.checkValidity()) {
				event.preventDefault();
				event.stopPropagation();
			}
			
			form.classList.add('was-validated');
		});
	});
</script>
<?= $this->endSection() ?> 