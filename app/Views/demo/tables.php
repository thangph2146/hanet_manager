<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $title ?? 'Demo các loại bảng' ?>
<?= $this->endSection() ?>

<?= $this->section('title_content') ?>
<?= $title ?? 'Demo các loại bảng' ?>
<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Demo</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="/"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Bảng dữ liệu</li>
            </ol>
        </nav>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Bảng cơ bản -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Bảng cơ bản</h5>
    </div>
    <div class="card-body">
        <?= $basicTable ?>
    </div>
</div>

<!-- Bảng DataTable -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Bảng với DataTable</h5>
        <p class="card-subtitle">Bảng có tính năng phân trang, tìm kiếm và sắp xếp</p>
    </div>
    <div class="card-body">
        <?= $dataTable ?>
    </div>
</div>

<!-- Bảng với chức năng xuất dữ liệu -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Bảng với chức năng xuất dữ liệu</h5>
        <p class="card-subtitle">Xuất dữ liệu sang các định dạng: Copy, Excel, PDF, In ấn</p>
    </div>
    <div class="card-body">
        <?= $exportTable ?>
    </div>
</div>

<!-- Bảng với tính năng lọc dữ liệu -->
<div class="card mb-4">
    <div class="card-header">
        <h5>Bảng với tính năng lọc dữ liệu</h5>
        <p class="card-subtitle">Bảng kết hợp các tính năng lọc dữ liệu nâng cao</p>
    </div>
    <div class="card-body">
        <?= $filterTable ?>
    </div>
</div>
<?= $this->endSection() ?> 