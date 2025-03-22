<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>THÊM MỚI KHÓA HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Thêm mới Khóa Học',
    'dashboard_url' => site_url('khoahoc/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Khóa Học', 'url' => site_url('khoahoc')],
        ['title' => 'Thêm mới', 'active' => true]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <?= view('App\Modules\khoahoc\Views\form', [
            'action' => site_url('khoahoc/create'),
            'method' => 'POST'
        ]) ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= loainguoidung_js('form') ?>
<?= $this->endSection() ?> 