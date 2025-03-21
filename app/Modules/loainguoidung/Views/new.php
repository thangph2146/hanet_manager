<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>THÊM MỚI LOẠI NGƯỜI DÙNG<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Thêm mới Loại Người Dùng',
    'dashboard_url' => site_url('loainguoidung/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Loại Người Dùng', 'url' => site_url('loainguoidung')],
        ['title' => 'Thêm mới', 'active' => true]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <?= view('App\Modules\loainguoidung\Views\form', [
            'action' => site_url('loainguoidung/create'),
            'method' => 'POST'
        ]) ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= loainguoidung_js('form') ?>
<?= $this->endSection() ?> 