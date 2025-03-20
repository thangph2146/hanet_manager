<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Ví dụ bảng với layout mặc định' ?>
<?= $this->endSection() ?>

<?= $this->section('title_content') ?>
<?= $title ?? 'Ví dụ bảng với layout mặc định' ?>
<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Bảng</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="/"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </nav>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <!-- Nội dung bảng -->
        <?= $table ?>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5>Danh sách ví dụ:</h5>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link <?= current_url() == site_url('table/basic') ? 'active' : '' ?>" href="<?= site_url('table/basic') ?>">Bảng cơ bản</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= current_url() == site_url('table/heading') ? 'active' : '' ?>" href="<?= site_url('table/heading') ?>">Bảng có tiêu đề</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= current_url() == site_url('table/template') ? 'active' : '' ?>" href="<?= site_url('table/template') ?>">Template tùy chỉnh</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= current_url() == site_url('table/datatable') ? 'active' : '' ?>" href="<?= site_url('table/datatable') ?>">DataTable</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= current_url() == site_url('table/export') ? 'active' : '' ?>" href="<?= site_url('table/export') ?>">Xuất dữ liệu</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= current_url() == site_url('table/report') ? 'active' : '' ?>" href="<?= site_url('table/report') ?>">Báo cáo</a>
            </li>
        </ul>
    </div>
</div>
<?= $this->endSection() ?> 