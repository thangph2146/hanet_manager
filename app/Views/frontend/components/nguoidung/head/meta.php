<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= $description ?? 'Hệ thống Quản lý Sinh viên - Truy cập thông tin và sự kiện dành cho sinh viên' ?>">
<meta name="csrf-token" content="<?= csrf_hash() ?>">
<meta name="api-base-url" content="<?= base_url('api') ?>">
<title><?= $title ?? 'Hệ thống Quản lý Sinh viên' ?></title>