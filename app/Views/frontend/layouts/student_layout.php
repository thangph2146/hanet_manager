<!DOCTYPE html>
<html lang="vi">
<head>
    <!-- Meta -->
    <?= $this->include('frontend/components/student/head/meta') ?>

    <!-- Link href -->
    <?= $this->include('frontend/components/student/head/link_href') ?>

    <!-- Additional CSS -->
    <?= $this->include('frontend/components/student/head/styles') ?>
</head>
<body>
    <!-- Page Loader -->
    <!-- <div class="page-loader">
        <div class="loader"></div>
    </div>
     -->
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
    
    
</body>
</html>
