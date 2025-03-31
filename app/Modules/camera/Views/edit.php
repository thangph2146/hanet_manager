<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị route_url từ controller hoặc sử dụng giá trị mặc định
$route_url = isset($route_url) ? $route_url : 'admin/camera';
$route_url_php = $route_url;
include __DIR__ . '/master_scripts.php'; 
?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CẬP NHẬT CAMERA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật Camera',
    'dashboard_url' => site_url($route_url),
    'breadcrumbs' => [
        ['title' => 'Quản lý camera', 'url' => site_url($route_url)],
        ['title' => 'Cập nhật', 'active' => true]
    ],
    'actions' => [
		['url' => site_url($route_url), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-body">
        <?= form_open($action, ['class' => 'needs-validation', 'novalidate' => true, 'id' => 'form-' . $module_name]) ?>
            <?php
            // Include form fields with data
            include __DIR__ . '/form.php';
            ?>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>  

<?= $this->section('script') ?>
<?= page_js('form', $route_url) ?>
<?= $this->endSection() ?> 