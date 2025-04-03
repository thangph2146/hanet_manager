<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Meta -->
    <?= $this->include('frontend/components/nguoidung/head/meta') ?>

    <!-- Link href - CSS chính -->
    <?= $this->include('frontend/components/nguoidung/head/link_href') ?>

    <!-- Components CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/components/header.css') ?>">
    
    <!-- Additional CSS -->
    <?= $this->include('frontend/components/nguoidung/head/styles') ?>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Page Loader -->
    <div class="page-loader">
        <div class="loader"></div>
    </div>
    
    <!-- Toast Container cho thông báo -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    
    <!-- Layout Wrapper -->
    <div class="layout-wrapper">
        <!-- Sidebar Backdrop -->
        <div class="sidebar-backdrop" id="sidebar-backdrop"></div>
        
        <!-- Sidebar -->
        <?= $this->include('frontend/components/nguoidung/sidebar') ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Navbar -->
            <?= $this->include('frontend/components/nguoidung/header') ?>
            
            <!-- Page Content -->
            <div class="container-fluid">
                <!-- Main Content from page being extended -->
                <?= $this->renderSection('content') ?>
            </div>
            
            <!-- Footer -->
            <?= $this->include('frontend/components/nguoidung/footer') ?>
        </div>
    </div>
    
    <!-- Core JS -->
    <?= $this->include('frontend/components/nguoidung/body/js') ?>
    
    <!-- Page specific scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
