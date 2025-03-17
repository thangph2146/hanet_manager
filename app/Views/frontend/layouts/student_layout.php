<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $description ?? 'Hệ thống Quản lý Sinh viên - Truy cập thông tin và sự kiện dành cho sinh viên' ?>">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="api-base-url" content="<?= base_url('api') ?>">
    <title><?= $title ?? 'Hệ thống Quản lý Sinh viên' ?></title>
    
    <!-- Preload key assets -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Apex Charts -->
    <link href="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.css" rel="stylesheet">
    
    <!-- Layout CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/student/layouts/student_layout.css') ?>">
    
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader">
        <div class="loader"></div>
    </div>
    
    <!-- Layout Wrapper -->
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <?= $this->include('frontend/components/student/sidebar') ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <?= $this->include('frontend/components/student/header') ?>
            
            <!-- Page Content -->
            <div class="container-fluid">
                <?php if(uri_string() === 'student/dashboard'): ?>
                    <?= $this->include('frontend/components/student/dashboard_stats') ?>
                    <?= $this->include('frontend/components/student/upcoming_events') ?>
                <?php endif; ?>
                
                <!-- Main Content from page being extended -->
                <?= $this->renderSection('content') ?>
            </div>
            
            <!-- Footer -->
            <?= $this->include('frontend/components/student/footer') ?>
        </div>
    </div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.35.3/dist/apexcharts.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Layout JS -->
    <script src="<?= base_url('assets/js/student/layouts/student_layout.js') ?>"></script>
    
    <!-- Page specific scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
