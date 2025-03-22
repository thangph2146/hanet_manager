<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>CẬP NHẬT KHÓA HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật Khóa Học',
    'dashboard_url' => site_url('khoahoc/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Khóa Học', 'url' => site_url('khoahoc')],
        ['title' => 'Cập nhật', 'active' => true]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <?= view('App\Modules\khoahoc\Views\form', [
            'action' => site_url('khoahoc/update/' . $khoa_hoc->getId()),
            'method' => 'POST',
            'khoa_hoc' => $khoa_hoc
        ]) ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= loainguoidung_js('form') ?>
<?= $this->endSection() ?> 