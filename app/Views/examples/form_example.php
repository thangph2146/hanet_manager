<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Ví dụ Form' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4"><?= $title ?? 'Ví dụ Form' ?></h1>
        
        <!-- Flash Messages -->
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <!-- Form Content -->
                <?= $content ?>
            </div>
        </div>
        
        <div class="mt-4">
            <h3>Danh sách form mẫu:</h3>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('form/basic') ? 'active' : '' ?>" href="<?= site_url('form/basic') ?>">Form cơ bản</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('form/advanced') ? 'active' : '' ?>" href="<?= site_url('form/advanced') ?>">Form nâng cao</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('form/edit-user') ? 'active' : '' ?>" href="<?= site_url('form/edit-user') ?>">Form chỉnh sửa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('form/time') ? 'active' : '' ?>" href="<?= site_url('form/time') ?>">Form chọn thời gian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('form/product-table') ? 'active' : '' ?>" href="<?= site_url('form/product-table') ?>">Form với bảng sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('form/timeline') ? 'active' : '' ?>" href="<?= site_url('form/timeline') ?>">Form với timeline sự kiện</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('form/upload') ? 'active' : '' ?>" href="<?= site_url('form/upload') ?>">Form Upload File</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 