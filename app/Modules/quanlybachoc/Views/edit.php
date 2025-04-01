<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị module_name từ controller hoặc sử dụng giá trị mặc định
 
$module_name = isset($module_name) ? $module_name : 'quanlybachoc';

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\quanlybachoc\Libraries\MasterScript($module_name);
?>
<?= $masterScript->pageCss('form') ?>
<?= $masterScript->pageSectionCss('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>CẬP NHẬT BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật bậc học',
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => 'Quản lý Bậc Học', 'url' => site_url($module_name)],
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
        <?= form_open($action, ['class' => 'row g-3 needs-validation', 'novalidate' => true, 'id' => 'form-' . $module_name]) ?>
            <?= view('App\Modules\quanlybachoc\Views\components\_form', [
                'module_name' => $module_name,
                'data' => $data ?? null,
                'validation' => $validation ?? null
            ]) ?>
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