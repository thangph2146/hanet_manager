<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('form') ?>
<?= page_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CẬP NHẬT NGƯỜI DÙNG<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật người dùng',
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => 'Quản lý Người dùng', 'url' => site_url($module_name)],
        ['title' => 'Cập nhật', 'active' => true]
    ],
    'actions' => [
		['url' => site_url($module_name), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-body">
        <?= form_open(site_url($module_name . '/update/' . $data->face_nguoi_dung_id), ['class' => 'needs-validation', 'novalidate' => true, 'id' => 'form-' . $module_name]) ?>
            <?php
            // Include form fields with namHoc data
            $action = site_url($module_name . '/update/' . $data->face_nguoi_dung_id);
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