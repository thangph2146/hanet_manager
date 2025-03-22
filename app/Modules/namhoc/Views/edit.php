<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>CHỈNH SỬA NĂM HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Chỉnh sửa năm học',
	'dashboard_url' => site_url('namhoc/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Năm Học', 'url' => site_url('namhoc')],
		['title' => 'Chỉnh sửa năm học', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/namhoc'), 'title' => 'Quay lại Danh sách Năm Học'],
		['url' => site_url('/namhoc/new'), 'title' => 'Tạo Năm Học Mới']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Chỉnh sửa năm học: <?= isset($nam_hoc) ? esc($nam_hoc->getTenNamHoc()) : '' ?></h4>
    </div>
    <div class="card-body">
        <?php if(isset($nam_hoc)): ?>
            <?= view('App\Modules\namhoc\Views\form', [
                'action' => site_url('namhoc/update/' . $nam_hoc->getId()),
                'method' => 'POST',
                'nam_hoc' => $nam_hoc
            ]) ?>
        <?php else: ?>
            <div class="alert alert-danger">
                Không tìm thấy thông tin năm học. <a href="<?= site_url('namhoc') ?>">Quay lại danh sách</a>.
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?> 