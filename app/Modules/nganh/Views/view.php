<?= $this->extend('layouts/default') ?>
<?= $this->section('linkHref') ?>
<?php include __DIR__ . '/master_scripts.php'; ?>
<?= $this->endSection() ?>

<?= $this->section('title') ?>CHI TIẾT NGÀNH<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
	'title' => 'Chi tiết ngành',
	'dashboard_url' => site_url('nganh/dashboard'),
	'breadcrumbs' => [
		['title' => 'Quản lý Ngành', 'url' => site_url('nganh')],
		['title' => 'Chi tiết', 'active' => true]
	],
	'actions' => [
		['url' => site_url('/nganh'), 'title' => 'Quay lại', 'icon' => 'bx bx-arrow-back']
	]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Chi tiết ngành</h5>
                <div>
                    <a href="<?= site_url('nganh/edit/' . $nganh->nganh_id) ?>" class="btn btn-primary btn-sm">
                        <i class="bx bx-edit"></i> Chỉnh sửa
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="35%" class="bg-light">Mã ngành</th>
                                <td><?= esc($nganh->ma_nganh) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Tên ngành</th>
                                <td><?= esc($nganh->ten_nganh) ?></td>
                            </tr>
                            <tr>
                                <th class="bg-light">Phòng/Khoa</th>
                                <td>
                                    <?= $nganh->getPhongKhoaInfo() ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <th width="35%" class="bg-light">Trạng thái</th>
                                <td>
                                    <?= $nganh->getStatusLabel() ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Ngày tạo</th>
                                <td>
                                    <?= $nganh->getCreatedAtFormatted() ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-light">Cập nhật lần cuối</th>
                                <td>
                                    <?= $nganh->getUpdatedAtFormatted() ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 