<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>THÊM NĂM HỌC MỚI<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Thêm năm học mới',
	'dashboard_url' => site_url('namhoc/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Năm Học', 'url' => site_url('namhoc')],
		['title' => 'Thêm năm học mới', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/namhoc'), 'title' => 'Quay lại Danh sách Năm Học']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Thêm năm học mới</h4>
    </div>
    <div class="card-body">
        <?= view('App\Modules\namhoc\Views\form', [
            'action' => site_url('namhoc/create'),
            'method' => 'POST'
        ]) ?>
    </div>
</div>
<?= $this->endSection() ?> 