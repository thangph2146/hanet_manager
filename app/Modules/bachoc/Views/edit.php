<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>CHỈNH SỬA BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Chỉnh sửa Bậc Học',
    'dashboard_url' => site_url('dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Bậc Học', 'url' => site_url('bachoc')],
        ['title' => 'Chỉnh sửa', 'active' => true]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <?= view('App\Modules\bachoc\Views\form', [
            'action' => site_url('bachoc/update/' . $bac_hoc->getId()),
            'method' => 'POST',
            'bac_hoc' => $bac_hoc
        ]) ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= bachoc_js('form') ?>
<?= $this->endSection() ?> 