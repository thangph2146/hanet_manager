<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>THÊM MỚI BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Thêm mới Bậc Học',
    'dashboard_url' => site_url('dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Bậc Học', 'url' => site_url('bachoc')],
        ['title' => 'Thêm mới', 'active' => true]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <?= view('App\Modules\bachoc\Views\form', [
            'action' => site_url('bachoc/create'),
            'method' => 'POST'
        ]) ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= bachoc_js('form') ?>
<?= $this->endSection() ?> 