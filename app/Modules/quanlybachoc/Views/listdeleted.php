<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
 
$module_name = isset($module_name) ? $module_name : 'quanlybachoc';

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\quanlybachoc\Libraries\MasterScript($module_name, $module_name);
?>
<?= $masterScript->pageCss('table') ?>
<?= $masterScript->pageSectionCss('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÙNG RÁC - BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thùng rác - Bậc học',
	'dashboard_url' => site_url($module_name),
	'breadcrumbs' => [
		['title' => 'Quản lý Bậc học', 'url' => site_url($module_name)],
		['title' => 'Thùng rác', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/' . $module_name), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card shadow-sm">
    <?= view('App\Modules\quanlybachoc\Views\components\_deleted_header', [
        'module_name' => $module_name
    ]) ?>
    
    <div class="card-body p-0">
        <?= view('App\Modules\quanlybachoc\Views\components\_deleted_filter', [
            'module_name' => $module_name,
            'keyword' => $keyword ?? '',
            'perPage' => $perPage
        ]) ?>
        
        <?= view('App\Modules\quanlybachoc\Views\components\_alerts') ?>
        
        <?= view('App\Modules\quanlybachoc\Views\components\_deleted_table', [
            'processedData' => $processedData,
            'module_name' => $module_name
        ]) ?>
        
        <?php if (!empty($processedData)): ?>
            <?= view('App\Modules\quanlybachoc\Views\components\_pager', [
                'pager' => $pager,
                'perPage' => $perPage,
                'total' => $total
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?= view('App\Modules\quanlybachoc\Views\components\_deleted_modals') ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $masterScript->pageJs('table') ?>
<?= $masterScript->pageSectionJs('table') ?>
<?= $masterScript->pageTableJs() ?>
<?= $this->endSection() ?> 