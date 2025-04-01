<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Khởi tạo thư viện MasterScript với cú pháp đúng
$masterScriptClass = "\App\Modules\\" . $module_name . '\Libraries\MasterScript';
$masterScript = new $masterScriptClass($module_name);
?>
<?= $masterScript->pageCss('table') ?>
<?= $masterScript->pageSectionCss('modal') ?>   
<?= $this->endSection() ?>
<?= $this->section('title') ?>$title<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => $title, 
	'dashboard_url' => site_url($module_name),
	'breadcrumbs' => [
		['title' => $title_home, 'url' => site_url($module_name)],
		['title' => $title, 'active' => true]
	],
]) ?>
<?= $this->endSection() ?>  

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <?= view('App\Modules\\' . $module_name . '\Views\components\_deleted_header', [
        'module_name' => $module_name
    ]) ?>
    
    <div class="card-body p-0">
        <?= view('App\Modules\\' . $module_name . '\Views\components\_filter', [
            'module_name' => $module_name,
            'keyword' => $keyword ?? '',
            'perPage' => $perPage
        ]) ?>
        
        <?= view('App\Modules\\' . $module_name . '\Views\components\_alerts') ?>
        
        <?= view('App\Modules\\' . $module_name . '\Views\components\_deleted_table', [
            'processedData' => $processedData,
            'module_name' => $module_name
        ]) ?>
        
        <?php if (!empty($processedData)): ?>
            <?= view('App\Modules\\' . $module_name . '\Views\components\_pager', [
                'pager' => $pager,
                'perPage' => $perPage,
                'total' => $total
            ]) ?>
        <?php endif; ?>
    </div>
</div>

<?= view('App\Modules\\' . $module_name . '\Views\components\_deleted_modals') ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $masterScript->pageJs('table') ?>
<?= $masterScript->pageSectionJs('table') ?>
<?= $masterScript->pageTableJs() ?>
<?= $this->endSection() ?> 