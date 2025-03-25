<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI THAM GIA SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới Tham Gia Sự Kiện',
	'dashboard_url' => site_url('thamgiasukien/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Tham Gia Sự Kiện', 'url' => site_url('thamgiasukien')],
		['title' => 'Thêm mới', 'active' => true]
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
	<div class="card-body">
		<?= form_open(site_url('thamgiasukien/create'), ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-thamgiasukien']) ?>
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
		const form = document.getElementById('form-thamgiasukien');
		
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