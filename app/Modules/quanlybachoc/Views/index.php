<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
// Lấy giá trị module_name từ controller hoặc sử dụng giá trị mặc định
 
$module_name = isset($module_name) ? $module_name : 'quanlybachoc';
$module_name_php = $module_name;

// Khởi tạo thư viện MasterScript
$masterScript = new \App\Modules\quanlybachoc\Libraries\MasterScript($module_name, $module_name);
?>
<?= $masterScript->pageCss('table') ?>
<?= $masterScript->pageSectionCss('modal') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>DANH SÁCH BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Danh sách bậc học',
	'dashboard_url' => site_url($module_name),
	'breadcrumbs' => [
		['title' => 'Quản lý Bậc Học', 'url' => site_url($module_name)],
		['title' => 'Danh sách', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/' . $module_name . '/new'), 'title' => 'Thêm mới', 'icon' => 'bx bx-plus-circle']
	]
]) ?>
<?= $this->endSection() ?>  

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <?= view('App\Modules\quanlybachoc\Views\components\_header', [
        'module_name' => $module_name
    ]) ?>
    
    <div class="card-body p-0">
        <?= view('App\Modules\quanlybachoc\Views\components\_filter', [
            'module_name' => $module_name,
            'keyword' => $keyword ?? '',
            'status' => $status ?? '',
            'perPage' => $perPage
        ]) ?>
        
        <?= view('App\Modules\quanlybachoc\Views\components\_alerts') ?>
        
        <?= view('App\Modules\quanlybachoc\Views\components\_table', [
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

<?= view('App\Modules\quanlybachoc\Views\components\_modals') ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $masterScript->pageJs('table') ?>
<?= $masterScript->pageSectionJs('table') ?>
<?= $masterScript->pageTableJs() ?>
<?= $this->endSection() ?> 