<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI NĂM HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới Năm Học',
	'dashboard_url' => site_url($module_name),
	'breadcrumbs' => [
		['title' => 'Quản lý Năm Học', 'url' => site_url($module_name)],
		['title' => 'Thêm mới', 'active' => true]
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
	<div class="card-body">
		<?= form_open(site_url($module_name . '/create'), ['class' => 'needs-validation', 'novalidate' => true, 'id' => 'form-' . $module_name]) ?>
			<?php
			// Include form fields
			$action = site_url($module_name . '/create');
			$method = 'POST';
			include __DIR__ . '/form.php';
			?>
		<?= form_close() ?>
	</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= page_js('form', $module_name) ?>
<?= $this->endSection() ?> 