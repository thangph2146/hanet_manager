<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('form') ?>
<?= page_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI DIỄN GIẢ<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới diễn giả',
	'dashboard_url' => site_url('diengia/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Diễn giả', 'url' => site_url('diengia')],
		['title' => 'Thêm mới', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/diengia'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
	<div class="card-body">
		<?= form_open_multipart(site_url('diengia/create'), ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-diengia']) ?>
			<?php
			// Xác định rõ là form thêm mới, không cần dien_gia_id
			$is_new = true;
			// Include form fields
			include __DIR__ . '/form.php';
			?>
		<?= form_close() ?>
	</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= page_js('form') ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const form = document.getElementById('form-diengia');
		
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