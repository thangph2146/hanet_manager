<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= $this->endSection() ?>

<?= $this->section('title') ?>THÊM MỚI NGÀNH<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Thêm mới ngành',
    'dashboard_url' => site_url('nganh/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Ngành', 'url' => site_url('nganh')],
        ['title' => 'Thêm mới', 'active' => true]
    ],
    'actions' => [
		['url' => site_url('/nganh'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<?= view('App\Modules\nganh\Views\form', ['action' => site_url('nganh/create')]) ?>
<?= $this->endSection() ?>  
