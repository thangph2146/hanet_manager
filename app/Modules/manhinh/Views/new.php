<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= manhinh_css('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI MÀN HÌNH<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới Màn Hình',
	'dashboard_url' => site_url('manhinh/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Màn Hình', 'url' => site_url('manhinh')],
		['title' => 'Thêm mới Màn Hình', 'active' => true]
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
	<div class="card-body">
		<?= form_open(site_url('manhinh/create'), ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-manhinh']) ?>
			<?php
			// Include form fields
			include __DIR__ . '/form.php';
			?>
		<?= form_close() ?>
	</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= manhinh_js('form') ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const form = document.getElementById('form-manhinh');
		
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