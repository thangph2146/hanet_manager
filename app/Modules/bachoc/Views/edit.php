<?= $this->extend('layouts/default') ?>
<?= $this->section('title') ?>CẬP NHẬT BẬC HỌC<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật Bậc học',
    'dashboard_url' => site_url('bachoc/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Bậc học', 'url' => site_url('bachoc')],
        ['title' => 'Cập nhật', 'active' => true]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
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