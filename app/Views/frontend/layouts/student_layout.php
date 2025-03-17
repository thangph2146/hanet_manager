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
    
    <?= $this->renderSection('styles') ?>
    
    <!-- Inline Critical CSS -->
    <style>
        :root {
            --primary-color: #8A2BE2;
            --primary-light: #9d4edd;
            --primary-dark: #7209b7;
            --secondary-color: #6c757d;
            --success-color: #28c76f;
            --danger-color: #ea5455;
            --warning-color: #ff9f43;
            --info-color: #00cfe8;
            --light-color: #f8f9fa;
            --dark-color: #4b4b4b;
            --white: #fff;
            --body-bg: #f8f8f8;
            --card-bg: #fff;
            --border-color: #ebe9f1;
            --transition-normal: 0.15s ease;
            --transition-slow: 0.3s ease;
            --box-shadow: 0 4px 24px 0 rgba(34, 41, 47, 0.1);
            --box-shadow-sm: 0 2px 8px 0 rgba(34, 41, 47, 0.08);
            --border-radius-sm: 0.25rem;
            --border-radius: 0.375rem;
            --border-radius-lg: 0.5rem;
            --border-radius-xl: 0.8rem;
            --font-family: 'Public Sans', sans-serif;
        }
        
        body {
            font-family: var(--font-family);
            background-color: var(--body-bg);
            color: var(--dark-color);
            overflow-x: hidden;
            font-size: 0.9rem;
        }
        
        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: var(--white);
            border-right: 1px solid var(--border-color);
            transition: all var(--transition-normal);
            z-index: 1040;
            box-shadow: var(--box-shadow);
        }
        
        .sidebar-header {
            padding: 1.25rem 1.25rem;
            display: flex;
            align-items: center;
        }
        
        .sidebar-logo {
            color: var(--primary-dark);
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar-logo .logo-icon {
            color: var(--primary-color);
            font-size: 1.75rem;
        }
        
        .sidebar-menu {
            padding: 0;
            list-style-type: none;
            margin: 0;
        }
        
        .sidebar-menu-item {
            position: relative;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: var(--dark-color);
            text-decoration: none;
            font-weight: 500;
            transition: all var(--transition-normal);
            position: relative;
        }
        
        .sidebar-link .menu-icon {
            width: 1.5rem;
            height: 1.5rem;
            margin-right: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }
        
        .sidebar-link .badge-pro {
            font-size: 0.7rem;
            background-color: #e7dcf7;
            color: var(--primary-color);
            padding: 0.15rem 0.5rem;
            border-radius: 0.25rem;
            margin-left: auto;
        }
        
        .sidebar-link.active {
            background-color: #f0ecf8;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .sidebar-link:hover {
            background-color: #f0ecf8;
            color: var(--primary-color);
        }
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            padding: 1.5rem;
            transition: all var(--transition-normal);
        }
        
      
        
        .nav-search {
            flex-grow: 1;
            max-width: 400px;
            position: relative;
            margin: 0 1.5rem;
        }
        
        .nav-search input {
            border: none;
            background-color: #f8f8f8;
            padding: 0.75rem 1.25rem;
            padding-left: 3rem;
            border-radius: var(--border-radius-lg);
            width: 100%;
            transition: all var(--transition-normal);
            font-size: 0.95rem;
        }
        
        .nav-search input:focus {
            outline: none;
            background-color: #f0f0f0;
            box-shadow: 0 0 0 3px rgba(138, 43, 226, 0.1);
        }
        
        .nav-search i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--secondary-color);
            font-size: 1.1rem;
        }
        
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }
        
        .nav-action-btn {
            background: none;
            border: none;
            color: var(--dark-color);
            font-size: 1.2rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            transition: all var(--transition-normal);
        }
        
        .nav-action-btn:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
        }
        
        .nav-action-btn .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            background: var(--primary-color);
            color: white;
            border: 2px solid var(--white);
            font-weight: 600;
        }
        
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.25rem;
            border-radius: var(--border-radius-lg);
            transition: all var(--transition-normal);
            text-decoration: none;
        }
        
        .user-dropdown:hover {
            background-color: #f0f0f0;
        }
        
        .user-dropdown img {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary-light);
        }
        
        .user-info {
            display: none;
        }
        
        @media (min-width: 768px) {
            .user-info {
                display: block;
            }
            
            .user-name {
                font-weight: 600;
                color: var(--dark-color);
                font-size: 0.95rem;
                margin: 0;
            }
            
            .user-role {
                color: var(--secondary-color);
                font-size: 0.8rem;
                margin: 0;
            }
        }
        
        /* Cards */
        .card {
            background-color: var(--card-bg);
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-sm);
            margin-bottom: 1.5rem;
            transition: all var(--transition-normal);
        }
        
        .card:hover {
            box-shadow: var(--box-shadow);
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .card-title {
            margin-bottom: 0;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .card-body {
            padding: 1.25rem;
        }
        
        .card-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .card-action-btn {
            background: none;
            border: none;
            color: var(--secondary-color);
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            transition: all var(--transition-normal);
        }
        
        .card-action-btn:hover {
            background-color: #f0f0f0;
            color: var(--dark-color);
        }
        
        /* Stats Cards */
        .stats-card {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: var(--white);
        }
        
        .stats-icon.bg-primary-light {
            background-color: rgba(138, 43, 226, 0.2);
            color: var(--primary-color);
        }
        
        .stats-icon.bg-success-light {
            background-color: rgba(40, 199, 111, 0.2);
            color: var(--success-color);
        }
        
        .stats-icon.bg-warning-light {
            background-color: rgba(255, 159, 67, 0.2);
            color: var(--warning-color);
        }
        
        .stats-icon.bg-info-light {
            background-color: rgba(0, 207, 232, 0.2);
            color: var(--info-color);
        }
        
        .stats-info {
            flex-grow: 1;
        }
        
        .stats-title {
            color: var(--secondary-color);
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        
        .stats-number {
            font-weight: 600;
            font-size: 1.25rem;
            color: var(--dark-color);
            margin: 0;
        }
        
        /* Progress Card */
        .target-progress {
            margin-top: 0.5rem;
            background-color: #f0f0f0;
            height: 0.25rem;
            border-radius: 1rem;
            overflow: hidden;
        }
        
        .target-progress .progress-bar {
            height: 100%;
            border-radius: 1rem;
            background-color: var(--primary-color);
        }
        
        /* Buttons */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--white);
        }
        
        /* Upgrade button */
        .upgrade-pro-btn {
            display: block;
            background-color: #ea5455;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            text-align: center;
            font-weight: 600;
            transition: all var(--transition-normal);
            margin: 1rem;
            text-decoration: none;
        }
        
        .upgrade-pro-btn:hover {
            background-color: #d63031;
            color: white;
        }
        
        /* Events card */
        .event-card {
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            background-color: var(--white);
            box-shadow: var(--box-shadow-sm);
            transition: all var(--transition-normal);
            height: 100%;
            border: 1px solid var(--border-color);
        }
        
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow);
        }
        
        .event-image {
            position: relative;
            height: 180px;
            overflow: hidden;
        }
        
        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .event-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            padding: 0.25rem 0.75rem;
            border-radius: var(--border-radius);
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .event-badge.popular {
            background-color: var(--warning-color);
            color: white;
        }
        
        .event-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.8rem;
            color: var(--secondary-color);
        }
        
        .event-date i, .event-location i {
            margin-right: 0.25rem;
        }
        
        .event-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }
        
        .event-category {
            font-size: 0.8rem;
            color: var(--secondary-color);
        }
        
        .featured-event {
            border-color: var(--primary-color);
            border-width: 2px;
        }
        
        /* Mobile Responsiveness */
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                bottom: 0;
                width: 100%;
                max-width: 260px;
                z-index: 1045;
                transition: all 0.3s ease;
                background: var(--white);
            }
            
            .sidebar.show {
                left: 0;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 1rem;
            }
            
            .sidebar-toggle-btn {
                display: block !important;
            }
            
          
            
            .nav-search {
                display: none;
            }
            
            .nav-actions {
                margin-left: auto;
            }
            
            /* Stats Cards Mobile */
            .stats-card {
                margin-bottom: 1rem;
            }
            
            .stats-icon {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
            
            .stats-number {
                font-size: 1rem;
            }
            
            /* Welcome Card Mobile */
            .welcome-card img {
                width: 50px;
            }
            
            /* Events Card Mobile */
            .event-card {
                margin-bottom: 1rem;
            }
            
            .event-image {
                height: 160px;
            }
            
            .event-meta {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            /* Footer Mobile */
            .footer {
                text-align: center;
                padding: 1rem;
            }
            
            .footer-links {
                margin-top: 1rem;
                text-align: center;
            }
            
            .footer-links a {
                display: inline-block;
                margin: 0.5rem;
            }
        }
        
        @media (max-width: 576px) {
         
            .nav-action-btn {
                width: 35px;
                height: 35px;
                font-size: 1rem;
            }
            
            .user-dropdown img {
                width: 35px;
                height: 35px;
            }
            
            .card {
                margin-bottom: 1rem;
            }
            
            .card-header {
                padding: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            .stats-card {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            
            .stats-icon {
                margin-bottom: 0.5rem;
            }
            
            .welcome-card .card-title {
                font-size: 1.1rem;
            }
            
            .welcome-card h3 {
                font-size: 1.5rem;
            }
            
            /* Dropdown Menus */
            .dropdown-menu {
                position: fixed !important;
                top: auto !important;
                left: 0 !important;
                right: 0 !important;
                bottom: 0;
                margin: 0;
                width: 100%;
                border-radius: 1rem 1rem 0 0;
                max-height: 80vh;
                overflow-y: auto;
                transform: translateY(100%);
                transition: transform 0.3s ease;
            }
            
            .dropdown-menu.show {
                transform: translateY(0);
            }
            
            .notification-item {
                padding: 1rem;
            }
            
            /* Mobile Search Overlay */
            .mobile-search {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: var(--white);
                z-index: 1050;
                padding: 1rem;
                display: none;
            }
            
            .mobile-search.show {
                display: block;
            }
            
            .mobile-search-header {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
            }
            
            .mobile-search-close {
                margin-left: auto;
                background: none;
                border: none;
                font-size: 1.5rem;
                color: var(--dark-color);
            }
            
            .mobile-search input {
                width: 100%;
                padding: 0.75rem;
                border: 1px solid var(--border-color);
                border-radius: var(--border-radius);
                margin-bottom: 1rem;
            }
        }
        
        /* Mobile Search Button */
        .mobile-search-btn {
            display: none;
            background: none;
            border: none;
            color: var(--dark-color);
            font-size: 1.2rem;
            padding: 0.5rem;
        }
        
        @media (max-width: 992px) {
            .mobile-search-btn {
                display: block;
            }
        }
        
        /* Backdrop for Mobile */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1044;
        }
        
        @media (max-width: 992px) {
            .sidebar-backdrop.show {
                display: block;
            }
        }
        
        /* Loader */
        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity var(--transition-normal);
        }
        
        .loader {
            width: 48px;
            height: 48px;
            border: 5px solid var(--primary-color);
            border-bottom-color: transparent;
            border-radius: 50%;
            animation: loader 1s linear infinite;
        }
        
        @keyframes loader {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Section headings */
        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: var(--dark-color);
        }
        
        /* Dividers and section separators */
        .section-divider {
            margin: 1.5rem 0;
            border-top: 1px solid var(--border-color);
        }
        
        /* Notifications dropdown */
        .dropdown-menu {
            border: none;
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
            padding: 0.5rem 0;
        }
        
        .dropdown-item {
            padding: 0.75rem 1rem;
        }
        
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: var(--white);
        }
        
        .notification-content {
            flex-grow: 1;
        }
        
        .notification-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .notification-text {
            font-size: 0.85rem;
            color: var(--secondary-color);
            margin-bottom: 0.25rem;
        }
        
        .notification-time {
            font-size: 0.75rem;
            color: var(--secondary-color);
        }
    </style>
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
    
    <!-- Page specific scripts -->
    <?= $this->renderSection('scripts') ?>

    <script>
    // Student Dashboard App
    document.addEventListener('DOMContentLoaded', function() {
        // Hide loader
        setTimeout(function() {
            document.querySelector('.page-loader').style.display = 'none';
        }, 500);
        
        // Elements
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarBackdrop = document.getElementById('sidebar-backdrop');
        const mobileSearch = document.getElementById('mobile-search');
        const mobileSearchClose = document.getElementById('mobile-search-close');
        const mainContent = document.querySelector('.main-content');
        
        // Sidebar Toggle
        if (sidebarToggle && sidebar && sidebarBackdrop) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                sidebarBackdrop.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            });
            
            // Close sidebar when backdrop is clicked
            sidebarBackdrop.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarBackdrop.classList.remove('show');
                document.body.style.overflow = '';
            });
        }
        
        // Mobile Search
        const mobileSearchBtn = document.querySelector('.mobile-search-btn');
        if (mobileSearchBtn && mobileSearch && mobileSearchClose) {
            mobileSearchBtn.addEventListener('click', function() {
                mobileSearch.classList.add('show');
                document.body.style.overflow = 'hidden';
                mobileSearch.querySelector('input').focus();
            });
            
            mobileSearchClose.addEventListener('click', function() {
                mobileSearch.classList.remove('show');
                document.body.style.overflow = '';
            });
        }
        
        // Handle Dropdowns on Mobile
        const dropdowns = document.querySelectorAll('.dropdown-toggle');
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', function() {
                if (window.innerWidth <= 576) {
                    document.body.style.overflow = 'hidden';
                }
            });
        });
        
        document.addEventListener('hidden.bs.dropdown', function() {
            if (window.innerWidth <= 576) {
                document.body.style.overflow = '';
            }
        });
        
        // Close sidebar on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 992 && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                sidebarBackdrop.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
        
        // Handle Search Keyboard Shortcut
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                e.preventDefault();
                if (window.innerWidth <= 992) {
                    mobileSearch.classList.add('show');
                    document.body.style.overflow = 'hidden';
                    mobileSearch.querySelector('input').focus();
                } else {
                    document.querySelector('.nav-search input').focus();
                }
            }
            
            if (e.key === 'Escape') {
                if (mobileSearch.classList.contains('show')) {
                    mobileSearch.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        });
    });
    </script>
</body>
</html>
