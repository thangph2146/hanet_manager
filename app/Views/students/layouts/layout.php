<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title') ?> - Quản lý sinh viên</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/bootstrap/css/bootstrap.min.css') ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= base_url('assets/plugins/fontawesome/css/all.min.css') ?>">
    
    <!-- StudentApp Main CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/layouts/studentsapp/css/main.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layouts/studentsapp/css/header.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layouts/studentsapp/css/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layouts/studentsapp/css/footer.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/layouts/studentsapp/css/notifications.css') ?>">
    
    <!-- Page specific CSS -->
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="student-app">
        <!-- Header Component -->
        <?= $this->include('students/layouts/components/header') ?>
        
        <!-- Sidebar Component -->
        <?= $this->include('students/layouts/components/sidebar') ?>
        
        <!-- Sidebar Overlay (for mobile) -->
        <div class="sidebar-overlay"></div>
        
        <!-- Main Content -->
        <div class="content-wrapper">
            <?= $this->renderSection('content') ?>
        </div>
        
        <!-- Footer Component -->
        <?= $this->include('students/layouts/components/footer') ?>
    </div>
    
    <!-- jQuery -->
    <script src="<?= base_url('assets/plugins/jquery/jquery.min.js') ?>"></script>
    
    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
    
    <!-- StudentApp Main JS -->
    <script src="<?= base_url('assets/layouts/studentsapp/js/layout.js') ?>"></script>
    
    <!-- Page specific JS -->
    <?= $this->renderSection('scripts') ?>
</body>
</html> 