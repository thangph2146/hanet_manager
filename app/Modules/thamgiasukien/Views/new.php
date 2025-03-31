<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị route_url từ controller hoặc sử dụng giá trị mặc định
$route_url = isset($route_url) ? $route_url : 'admin/thamgiasukien';
$module_name = isset($module_name) ? $module_name : 'thamgiasukien';

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\thamgiasukien\Libraries\MasterScript($route_url, $module_name);
?>
<?= $masterScript->pageCss('form') ?>
<?= $masterScript->pageSectionCss('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI THAM GIA SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới tham gia sự kiện',
	'dashboard_url' => site_url($route_url),
	'breadcrumbs' => [
		['title' => 'Quản lý tham gia sự kiện', 'url' => site_url($route_url)],
		['title' => 'Thêm mới', 'active' => true]
	],
	'actions' => [
		['url' => site_url($route_url), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
	<div class="card-body">
		<?= form_open(site_url($route_url . '/create'), ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-' . $route_url]) ?>
			<?php
			// Include form fields
			include __DIR__ . '/form.php';
			?>
		<?= form_close() ?>
	</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $masterScript->pageJs('form') ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		const form = document.getElementById('form-<?= $route_url ?>');
		
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