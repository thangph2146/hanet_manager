<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= hedaotao_css('form') ?>
<?= $this->endSection() ?>
<?= $this->section('title') ?>THÊM MỚI HỆ ĐÀO TẠO<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm mới Hệ Đào Tạo',
	'dashboard_url' => site_url('hedaotao/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Hệ Đào Tạo', 'url' => site_url('hedaotao')],
		['title' => 'Thêm mới Hệ Đào Tạo', 'active' => true]
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-body">
        <h5 class="card-title">Thêm mới Hệ Đào Tạo</h5>
        <?= view('App\Modules\hedaotao\Views\form', ['action' => site_url('hedaotao/create')]) ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= hedaotao_js('form') ?>
<?= $this->endSection() ?> 