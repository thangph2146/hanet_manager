<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị route_url từ controller hoặc sử dụng giá trị mặc định
$route_url = isset($route_url) ? $route_url : 'admin/bachoc';
$route_url_php = $route_url;
include __DIR__ . '/master_scripts.php'; 
?>
<?= page_css('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới Bậc Học',
	'dashboard_url' => site_url($route_url),
	'breadcrumbs' => [
		['title' => 'Quản lý Bậc Học', 'url' => site_url($route_url)],
		['title' => 'Thêm mới', 'active' => true]
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
	<div class="card-body">
		<?= form_open($action, ['class' => 'needs-validation', 'novalidate' => true, 'id' => 'form-' . $module_name]) ?>
			<?php
			// Include form fields
			include __DIR__ . '/form.php';
			?>
		<?= form_close() ?>
	</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= page_js('form', $route_url) ?>
<?= $this->endSection() ?> 