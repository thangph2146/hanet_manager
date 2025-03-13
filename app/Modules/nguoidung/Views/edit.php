<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>CẬP NHẬT NGƯỜI DÙNG<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Cập nhật Người Dùng',
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý Người Dùng', 'url' => site_url('/nguoidung')],
        ['title' => 'Cập nhật Người Dùng', 'active' => true]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= $this->include('App\Modules\nguoidung\Views\form') ?>
<?= $this->endSection() ?> 