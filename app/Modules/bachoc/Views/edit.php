<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị route_url từ controller hoặc sử dụng giá trị mặc định
$route_url = isset($route_url) ? $route_url : 'admin/bachoc';
$module_name = isset($module_name) ? $module_name : 'bachoc';

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\bachoc\Libraries\MasterScript($route_url, $module_name);
?>
<?= $masterScript->pageCss('form') ?>
<?= $masterScript->pageSectionCss('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CẬP NHẬT BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật bậc học',
    'dashboard_url' => site_url($route_url),
    'breadcrumbs' => [
        ['title' => 'Quản lý Bậc Học', 'url' => site_url($route_url)],
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
        <?= form_open($action, ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-' . $module_name]) ?>
            <?php
            // Include form fields with data
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
        const form = document.getElementById('form-<?= $module_name ?>');
        
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