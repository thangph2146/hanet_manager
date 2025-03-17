<!doctype html>
<html lang="vi" class="light-theme">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Hệ thống quản lý sự kiện sinh viên - Trường Đại học Ngân hàng TP.HCM">
    <meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
    <link rel="icon" href="<?= base_url('assets/images/favicon-32x32.png') ?>" type="image/png" />
    <title><?= $title ?? 'Hệ thống quản lý sự kiện sinh viên' ?> - BUH Events</title>

    <!-- Bootstrap CSS -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/icons.css') ?>" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- Boxicons CDN -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Plugins -->
    <link href="<?= base_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/datatable/css/dataTables.bootstrap5.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
    
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/dark-theme.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/semi-dark.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/css/header-colors.css') ?>" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>" />
    
    <!-- Layout Component CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/modules/layouts/student/css/style.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/modules/layouts/student/css/header.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/modules/layouts/student/css/sidebar.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/modules/layouts/student/css/user_dropdown.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/modules/layouts/student/css/footer.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/modules/layouts/student/css/alerts.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('assets/modules/layouts/student/css/breadcrumb.css') ?>" />
    
    <?= $this->renderSection('styles') ?>
    
    <!-- Preload key resources -->
    <link rel="preload" href="<?= base_url('assets/js/jquery.min.js') ?>" as="script">
    <link rel="preload" href="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>" as="script">
    <link rel="preload" href="<?= base_url('assets/modules/layouts/student/js/sidebar.js') ?>" as="script">
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?= $this->include('App\Modules\layouts\student\Views\components\sidebar') ?>
        
        <!-- Main Content -->
        <main class="page-content">
            <!-- Header -->
            <?= $this->include('App\Modules\layouts\student\Views\components\header') ?>
            
            <!-- Container -->
            <div class="page-content-wrapper">
                <?= $this->include('App\Modules\layouts\student\Views\components\alerts') ?>
                
                <!-- Breadcrumb -->
                <?= $this->include('App\Modules\layouts\student\Views\components\breadcrumb') ?>
                
                <!-- Page Content -->
                <?= $this->renderSection('content') ?>
                
                <!-- Footer -->
                <?= $this->include('App\Modules\layouts\student\Views\components\footer') ?>
            </div>
        </main>
        
        <!-- Overlay -->
        <div class="overlay"></div>
        
        <!-- Back To Top Button -->
        <a href="javascript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    </div>

    <!-- JavaScript -->
    <!-- jQuery & Bootstrap JS -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    
    <!-- Plugins -->
    <script src="<?= base_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/simplebar/js/simplebar.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') ?>"></script>
    
    <!-- Layout Component JS -->
    <script src="<?= base_url('assets/modules/layouts/student/js/sidebar.js') ?>"></script>
    <script src="<?= base_url('assets/modules/layouts/student/js/main.js') ?>"></script>
    <script src="<?= base_url('assets/modules/layouts/student/js/header.js') ?>"></script>
    <script src="<?= base_url('assets/modules/layouts/student/js/alerts.js') ?>"></script>
    
    <?= $this->renderSection('scripts') ?>
    
    <script>
    // Ứng dụng Simplebar cho các phần tử có scroll
    document.addEventListener('DOMContentLoaded', function() {
        const scrollableElements = document.querySelectorAll('[data-simplebar="true"]');
        scrollableElements.forEach(function(element) {
            new SimpleBar(element);
        });
    });
    </script>
</body>
</html> 