<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title><?= $title ?? 'Hệ thống quản lý sinh viên' ?></title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/favicon.ico') ?>" type="image/x-icon">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- BoxIcons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-color-hover: #3a56d4;
            --secondary-color: #6c757d;
            --success-color: #2e8540;
            --info-color: #2196f3;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --gray-100: #f8f9fa;
            --gray-200: #e9ecef;
            --gray-300: #dee2e6;
            --gray-400: #ced4da;
            --gray-500: #adb5bd;
            --gray-600: #6c757d;
            --gray-700: #495057;
            --gray-800: #343a40;
            --gray-900: #212529;
            --sidebar-width: 260px;
            --sidebar-collapsed-width: 70px;
            --header-height: 60px;
            --content-padding: 1.5rem;
        }
        
        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background-color: var(--gray-100);
            color: var(--gray-800);
            overflow-x: hidden;
        }
        
        /* Layout Structure */
        .app-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .main-wrapper {
            display: flex;
            flex: 1;
        }
        
        .main-content {
            flex: 1;
            padding: var(--content-padding);
            transition: all 0.3s;
            margin-top: var(--header-height);
        }
        
        /* Responsive adjustments */
        @media (min-width: 992px) {
            .main-content {
                margin-left: var(--sidebar-width);
            }
            
            .sidebar-collapsed .main-content {
                margin-left: var(--sidebar-collapsed-width);
            }
        }
        
        @media (max-width: 991.98px) {
            .main-content {
                margin-left: 0;
            }
        }
        
        /* Miscellaneous */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--primary-color-hover);
            border-color: var(--primary-color-hover);
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .bg-primary {
            background-color: var(--primary-color) !important;
        }
        
        a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        a:hover {
            color: var(--primary-color-hover);
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.5rem;
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 1.25rem;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--gray-200);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gray-400);
            border-radius: 3px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-500);
        }
    </style>
    
    <!-- Page Specific Styles -->
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="app-container">
        <!-- Header -->
        <?= $this->include('layouts/student/components/header') ?>
        
        <!-- Main Content Wrapper -->
        <div class="main-wrapper">
            <!-- Sidebar -->
            <?= $this->include('layouts/student/components/sidebar') ?>
            
            <!-- Main Content -->
            <main class="main-content">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
        
        <!-- Footer -->
        <?= $this->include('layouts/student/components/footer') ?>
    </div>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Base URL for AJAX requests -->
    <script>
        const base_url = '<?= base_url() ?>/';
    </script>
    
    <!-- Common JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Toggle Sidebar
            const sidebarToggle = document.getElementById('sidebar-toggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-collapsed');
                    
                    // Store the preference
                    if (document.body.classList.contains('sidebar-collapsed')) {
                        localStorage.setItem('sidebar-collapsed', 'true');
                    } else {
                        localStorage.setItem('sidebar-collapsed', 'false');
                    }
                });
                
                // Check if sidebar was collapsed in previous session
                if (localStorage.getItem('sidebar-collapsed') === 'true') {
                    document.body.classList.add('sidebar-collapsed');
                }
            }
            
            // Mobile Sidebar Toggle
            const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
            if (mobileSidebarToggle) {
                mobileSidebarToggle.addEventListener('click', function () {
                    document.body.classList.toggle('sidebar-mobile-open');
                });
            }
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function (event) {
                if (document.body.classList.contains('sidebar-mobile-open') && 
                    !event.target.closest('.sidebar') && 
                    !event.target.closest('#mobile-sidebar-toggle')) {
                    document.body.classList.remove('sidebar-mobile-open');
                }
            });
            
            // Enable tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
            
            // Enable popovers
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function (popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });
    </script>
    
    <!-- Page Specific Scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html> 