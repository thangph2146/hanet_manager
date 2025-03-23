<?php $this->extend('layouts/default') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>

<?php $this->section('styles') ?>
<?= nganh_css('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI KHUÔN MẶT NGƯỜI DÙNG<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới khuôn mặt người dùng',
	'dashboard_url' => site_url('facenguoidung'),
	'breadcrumbs' => [
		['title' => 'Quản lý khuôn mặt người dùng', 'url' => site_url('facenguoidung')],
		['title' => 'Thêm mới', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/facenguoidung'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<?= $this->include('App\Modules\facenguoidung\Views\form', [
	'action' => site_url('facenguoidung/create'),
	'method' => 'POST',
	'nguoidungs' => $nguoidungs ?? [],
	'is_new' => true
]) ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= nganh_js('form') ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const form = document.getElementById('faceForm');
		
		// Validate form khi submit
		if (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault();
					event.stopPropagation();
				}
				
				form.classList.add('was-validated');
			});
		}
	});
</script>
<?= $this->endSection() ?> 