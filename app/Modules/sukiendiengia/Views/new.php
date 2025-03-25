<?php
$module_name = 'sukiendiengia';
?>
<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI SỰ KIỆN DIỄN GIẢ<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới Sự Kiện Diễn Giả',
	'dashboard_url' => site_url($module_name),
	'breadcrumbs' => [
		['title' => 'Quản lý Sự Kiện Diễn Giả', 'url' => site_url($module_name)],
		['title' => 'Thêm mới', 'active' => true]
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
	<div class="card-body">
		<?= form_open(site_url($module_name . '/create'), ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-' . $module_name]) ?>
			<?php
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
		const form = document.getElementById('form-' . $module_name);
		
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