<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= loaisukien_css('form') ?>
<?= loaisukien_section_css('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CẬP NHẬT LOẠI SỰ KIỆN<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật loại sự kiện',
    'dashboard_url' => site_url('loaisukien/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Loại Sự Kiện', 'url' => site_url('loaisukien')],
        ['title' => 'Cập nhật', 'active' => true]
    ],
    'actions' => [
		['url' => site_url('/loaisukien'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <div class="card-header py-3">
        <h5 class="card-title mb-0">Cập nhật loại sự kiện</h5>
    </div>
    <div class="card-body">
        <?= form_open(site_url('loaisukien/update/' . $data->loai_su_kien_id), ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-loaisukien']) ?>
            <?php
            // Include form fields
            include __DIR__ . '/form.php';
            ?>
        <?= form_close() ?>
    </div>
</div>
<?= $this->endSection() ?>  

<?= $this->section('script') ?>
<?= loaisukien_js('form') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-loaisukien');
        
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