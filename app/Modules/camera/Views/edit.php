<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= page_css('form') ?>
<?= page_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CẬP NHẬT CAMERA<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật camera',
    'dashboard_url' => site_url('camera/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Camera', 'url' => site_url('camera')],
        ['title' => 'Cập nhật', 'active' => true]
    ],
    'actions' => [
		['url' => site_url('/camera'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-body">
        <?= form_open(site_url('camera/update/' . $camera->camera_id), ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-camera']) ?>
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
        const form = document.getElementById('form-camera');
        
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