<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Ví dụ bảng' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4"><?= $title ?? 'Ví dụ bảng' ?></h1>
        
        <div class="card">
            <div class="card-body">
                <!-- Nội dung bảng -->
                <?= $table ?>
            </div>
        </div>
        
        <div class="mt-4">
            <h3>Danh sách ví dụ:</h3>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('table/basic') ? 'active' : '' ?>" href="<?= site_url('table/basic') ?>">Bảng cơ bản</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('table/heading-footing') ? 'active' : '' ?>" href="<?= site_url('table/heading-footing') ?>">Bảng có tiêu đề và footer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('table/custom-template') ? 'active' : '' ?>" href="<?= site_url('table/custom-template') ?>">Bảng có template tùy chỉnh</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('table/datatable') ? 'active' : '' ?>" href="<?= site_url('table/datatable') ?>">Bảng với DataTable</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('table/export') ? 'active' : '' ?>" href="<?= site_url('table/export') ?>">Bảng có chức năng xuất dữ liệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= current_url() == site_url('table/database') ? 'active' : '' ?>" href="<?= site_url('table/database') ?>">Bảng từ cơ sở dữ liệu</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 