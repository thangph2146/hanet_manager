<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php 
$masterScriptClass = "\App\Modules\\" . $module_name . '\Libraries\MasterScript';
$masterScript = new $masterScriptClass($module_name);
?>
<?= $masterScript->pageCss('detail') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>$title<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => $title_home,
    'dashboard_url' => site_url($module_name),
    'breadcrumbs' => [
        ['title' => $title_home, 'url' => site_url($module_name)],
        ['title' => $title, 'active' => true]
    ],
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card shadow-sm">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-primary"><?= $title ?></h6>
            <div>
                <a href="<?= site_url($module_name . '/edit/' . $entity->loai_su_kien_id) ?>" class="btn btn-primary btn-sm">
                    <i class="bx bx-edit"></i> Sửa
                </a>
                <a href="<?= site_url($module_name) ?>" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <?= view('App\Modules\\' . $module_name . '\Views\components\_alerts') ?>
        
        <?= view('App\Modules\\' . $module_name . '\Views\components\_form_detail', [
            'module_name' => $module_name,
            'entity' => $entity ?? null
        ]) ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $masterScript->pageJs('detail') ?>
<?= $this->endSection() ?>
