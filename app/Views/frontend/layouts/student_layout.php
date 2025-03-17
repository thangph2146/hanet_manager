<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hệ thống Quản lý Sinh viên - Truy cập thông tin và sự kiện dành cho sinh viên">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="api-base-url" content="<?= base_url('api') ?>">
    <title><?= $title ?? 'Hệ thống Quản lý Sinh viên' ?></title>
    
    <!-- Preload key assets -->
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style">
    <link rel="preload" href="<?= base_url('assets/css/student/style.css') ?>" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" as="script">
    <link rel="preload" href="https://code.jquery.com/jquery-3.6.0.min.js" as="script">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/student/style.css') ?>">
    
    <!-- Thêm theme-color cho mobile -->
    <meta name="theme-color" content="#0d6efd">
    
    <?= $this->renderSection('styles') ?>
    
    <!-- Inline Critical CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-dark: #0b5ed7;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --transition-normal: 0.3s ease;
            --transition-bounce: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --box-shadow-hover: 0 10px 15px rgba(0, 0, 0, 0.15);
            --border-radius-sm: 0.25rem;
            --border-radius: 0.375rem;
            --border-radius-lg: 0.5rem;
            --border-radius-xl: 1rem;
        }
        
        body {
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }
        
        /* Loader Styles */
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
            box-shadow: var(--box-shadow);
        }
        @keyframes loader {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Progress Indicator */
        .ajax-indicator {
            position: fixed;
            top: 0;
            left: 0;
            height: 4px;
            background: var(--primary-color);
            z-index: 9998;
            width: 0;
            transition: width var(--transition-normal);
            box-shadow: var(--box-shadow);
        }
        .ajax-indicator.loading {
            animation: progress 2s ease-in-out infinite;
        }
        @keyframes progress {
            0% { width: 0; }
            50% { width: 50%; }
            100% { width: 90%; }
        }
        
        /* Layout Transitions */
        .sidebar-container {
            transition: transform var(--transition-bounce);
        }
        .content-wrapper {
            transition: margin-left var(--transition-bounce);
        }
        
        /* Mobile-first Sidebar */
        @media (max-width: 767.98px) {
            .sidebar-container {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 1050;
                width: 260px;
                transform: translateX(-100%);
                transition: transform var(--transition-bounce);
                box-shadow: var(--box-shadow);
            }
            .sidebar-container.show {
                transform: translateX(0);
            }
            .content-wrapper {
                width: 100%;
                margin-left: 0 !important;
            }
            .sidebar-toggle {
                opacity: 0.9;
                padding: 0.5rem;
                border-radius: var(--border-radius);
                box-shadow: var(--box-shadow);
            }
            .sidebar-toggle:hover {
                opacity: 1;
            }
        }
        
        @media (min-width: 768px) {
            .sidebar-container {
                width: 240px;
                min-height: 100vh;
                position: sticky;
                top: 0;
            }
            .content-wrapper {
                margin-left: 240px;
                width: calc(100% - 240px);
            }
            .sidebar-toggle {
                display: none;
            }
        }
        
        /* Card hovers and interactions */
        .hover-scale {
            transition: transform 0.2s ease, box-shadow var(--transition-normal);
            box-shadow: var(--box-shadow);
        }
        .hover-scale:hover {
            transform: scale(1.02);
            box-shadow: var(--box-shadow-hover);
        }
        
        /* Buttons styling */
        .btn {
            position: relative;
            overflow: hidden;
            transition: all var(--transition-normal);
        }
        .btn::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
            z-index: -1;
        }
        .btn:hover::after {
            width: 300%;
            height: 300%;
        }
        
        /* Back to top button */
        #back-to-top {
            opacity: 0.7;
            transition: opacity var(--transition-normal), transform var(--transition-normal);
            box-shadow: var(--box-shadow);
        }
        #back-to-top:hover {
            opacity: 1;
            transform: translateY(-3px);
            box-shadow: var(--box-shadow-hover);
        }
        
        /* Toast styling */
        .toast {
            box-shadow: var(--box-shadow);
            border-radius: var(--border-radius);
        }
        
        /* Events styling */
        .event-card {
            transition: transform var(--transition-normal), box-shadow var(--transition-normal);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            height: 100%;
        }
        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-hover);
        }
        .event-image {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }
        .event-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
        }
        .event-date {
            background: var(--primary-color);
            color: white;
            text-align: center;
            padding: 0.5rem 0;
            font-weight: bold;
            border-radius: var(--border-radius-sm);
        }
        
        /* Accessibility improvements */
        :focus {
            outline: 3px solid var(--primary-color);
            outline-offset: 3px;
        }
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--primary-color);
            color: white;
            padding: 8px;
            z-index: 9999;
        }
        .skip-link:focus {
            top: 0;
        }
        
        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            :root {
                --light-color: #1a1d20;
                --dark-color: #f8f9fa;
            }
            body {
                background-color: var(--light-color);
                color: var(--dark-color);
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
    <!-- Accessibility skip link -->
    <a href="#main-content-wrapper" class="skip-link">Bỏ qua đến nội dung chính</a>
    
        
    <!-- AJAX Progress Indicator -->
    <div class="ajax-indicator" id="ajax-progress-indicator"></div>
    
    <!-- Sidebar Toggle Button (for mobile) -->
    <button class="sidebar-toggle btn btn-primary d-md-none position-fixed" type="button" style="top: 10px; left: 10px; z-index: 1051;" aria-label="Mở menu điều hướng">
        <i class="fas fa-bars"></i>
    </button>
    
    <div class="wrapper d-flex">
        <!-- Sidebar -->
        <div class="sidebar-container" id="sidebar">
            <?= $this->include('frontend/components/student/sidebar') ?>
        </div>
        
        <!-- Main Content -->
        <div class="content-wrapper flex-grow-1">
            <!-- Header -->
            <?= $this->include('frontend/components/student/header') ?>
            
            <!-- Page Content -->
            <div class="container-fluid py-3 py-md-4 main-container">
                <?php if(isset($breadcrumbs)): ?>
                <nav aria-label="breadcrumb" class="mb-3 mb-md-4">
                    <ol class="breadcrumb py-2 px-3 bg-light rounded shadow-sm">
                        <li class="breadcrumb-item"><a href="<?= base_url('student/dashboard') ?>" class="text-decoration-none"><i class="fas fa-home me-1"></i>Trang chủ</a></li>
                        <?php foreach($breadcrumbs as $label => $url): ?>
                            <?php if($url): ?>
                                <li class="breadcrumb-item"><a href="<?= $url ?>" class="text-decoration-none"><?= $label ?></a></li>
                            <?php else: ?>
                                <li class="breadcrumb-item active" aria-current="page"><?= $label ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
                <?php endif; ?>
                
                <?php if(session()->getFlashdata('message')): ?>
                <div class="alert alert-<?= session()->getFlashdata('alert-type') ?? 'info' ?> alert-dismissible fade show shadow-sm" role="alert">
                    <i class="fas fa-info-circle me-2"></i><?= session()->getFlashdata('message') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
                <?php endif; ?>
                
                <!-- Events Quick Access Section (Always visible) -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary bg-gradient text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Sự kiện sắp tới</h5>
                                <a href="<?= base_url('student/events') ?>" class="btn btn-sm btn-light text-primary">Xem tất cả</a>
                            </div>
                            <div class="card-body p-0">
                                <div class="row g-0 events-carousel px-2 py-3">
                                    <!-- Placeholder for dynamic events, will be replaced by real data -->
                                    <div class="col-12 col-md-6 col-lg-4 p-2">
                                        <div class="event-card position-relative">
                                            <span class="event-badge badge bg-danger">Mới</span>
                                            <img src="<?= base_url('assets/images/events/placeholder.jpg') ?>" class="event-image" alt="Sự kiện" loading="lazy">
                                            <div class="p-3">
                                                <div class="event-date mb-2">
                                                    <div>25 Th.7</div>
                                                </div>
                                                <h5 class="card-title">Hội thảo Công nghệ 2023</h5>
                                                <p class="card-text text-muted small mb-2"><i class="fas fa-map-marker-alt me-1"></i>Hội trường A</p>
                                                <p class="card-text small mb-3">Tham gia hội thảo về các xu hướng công nghệ mới nhất...</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-success">Còn 10 slot</span>
                                                    <a href="<?= base_url('student/events/register/1') ?>" class="btn btn-sm btn-primary">Đăng ký</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 p-2">
                                        <div class="event-card position-relative">
                                            <img src="<?= base_url('assets/images/events/placeholder2.jpg') ?>" class="event-image" alt="Sự kiện" loading="lazy">
                                            <div class="p-3">
                                                <div class="event-date mb-2">
                                                    <div>30 Th.7</div>
                                                </div>
                                                <h5 class="card-title">Workshop Kỹ năng mềm</h5>
                                                <p class="card-text text-muted small mb-2"><i class="fas fa-map-marker-alt me-1"></i>Phòng B2.05</p>
                                                <p class="card-text small mb-3">Phát triển kỹ năng giao tiếp và làm việc nhóm...</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-warning text-dark">Còn 5 slot</span>
                                                    <a href="<?= base_url('student/events/register/2') ?>" class="btn btn-sm btn-primary">Đăng ký</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 p-2">
                                        <div class="event-card position-relative">
                                            <span class="event-badge badge bg-info">Phổ biến</span>
                                            <img src="<?= base_url('assets/images/events/placeholder3.jpg') ?>" class="event-image" alt="Sự kiện" loading="lazy">
                                            <div class="p-3">
                                                <div class="event-date mb-2">
                                                    <div>05 Th.8</div>
                                                </div>
                                                <h5 class="card-title">Ngày hội việc làm 2023</h5>
                                                <p class="card-text text-muted small mb-2"><i class="fas fa-map-marker-alt me-1"></i>Sân trường</p>
                                                <p class="card-text small mb-3">Cơ hội gặp gỡ và phỏng vấn với các doanh nghiệp hàng đầu...</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-success">Không giới hạn</span>
                                                    <a href="<?= base_url('student/events/register/3') ?>" class="btn btn-sm btn-primary">Đăng ký</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Main Content Wrapper -->
                <div id="main-content-wrapper">
                    <?= $this->renderSection('content') ?>
                </div>
                
                <!-- Event Registration Modal -->
                <div class="modal fade" id="eventRegistrationModal" tabindex="-1" aria-labelledby="eventRegistrationModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="eventRegistrationModalLabel">Đăng ký sự kiện</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <form id="eventRegistrationForm" action="<?= base_url('student/events/register') ?>" method="post">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="event_id" id="event_id" value="">
                                    
                                    <div class="mb-3">
                                        <label for="event_name" class="form-label">Tên sự kiện</label>
                                        <input type="text" class="form-control" id="event_name" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="event_date" class="form-label">Thời gian</label>
                                        <input type="text" class="form-control" id="event_date" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="event_location" class="form-label">Địa điểm</label>
                                        <input type="text" class="form-control" id="event_location" readonly>
                                    </div>
                                    
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="terms_agree" name="terms_agree" required>
                                        <label class="form-check-label" for="terms_agree">Tôi đồng ý với <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">điều khoản tham gia</a></label>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Xác nhận đăng ký</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Terms Modal -->
                <div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Điều khoản tham gia sự kiện</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                <p>1. Sinh viên cần đến đúng giờ theo thời gian quy định.</p>
                                <p>2. Mang theo thẻ sinh viên để kiểm tra khi tham gia.</p>
                                <p>3. Tuân thủ các quy định của ban tổ chức sự kiện.</p>
                                <p>4. Nếu đăng ký mà không tham gia, sẽ bị trừ điểm rèn luyện.</p>
                                <p>5. Sinh viên có thể hủy đăng ký trước 24 giờ diễn ra sự kiện.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Back to top button -->
            <button id="back-to-top" class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4" style="display: none; z-index: 1000;" aria-label="Về đầu trang">
                <i class="fas fa-arrow-up"></i>
            </button>
            
            <!-- Footer -->
            <?= $this->include('frontend/components/student/footer') ?>
        </div>
    </div>
    
    <!-- Toasts Container -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3"></div>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/student/scripts.js') ?>"></script>
    
    <!-- Page specific scripts -->
    <?= $this->renderSection('scripts') ?>

    <script>
    // StudentApp namespace
    const StudentApp = {
        init: function() {
            this.setupPageLoader();
            this.setupSidebar();
            this.setupBackToTop();
            this.setupLazyLoading();
            this.setupDropdowns();
            this.setupPjaxNavigation();
            this.setupEventRegistration();
            
            // Hiển thị toast message nếu có
            <?php if(session()->getFlashdata('toast_message')): ?>
            this.showToast(
                '<?= session()->getFlashdata('toast_title') ?? 'Thông báo' ?>', 
                '<?= session()->getFlashdata('toast_message') ?>', 
                '<?= session()->getFlashdata('toast_type') ?? 'info' ?>'
            );
            <?php endif; ?>
        },
        
        setupPageLoader: function() {
            const loader = document.querySelector('.page-loader');
            if (loader) {
                setTimeout(() => {
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 300);
                }, 400);
            }
        },
        
        setupSidebar: function() {
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
                
                // Đóng sidebar khi click bên ngoài (trên mobile)
                document.addEventListener('click', function(e) {
                    if (window.innerWidth < 768 && 
                        sidebar.classList.contains('show') && 
                        !sidebar.contains(e.target) && 
                        !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                });
            }
        },
        
        setupBackToTop: function() {
            const backToTopBtn = document.getElementById('back-to-top');
            if (backToTopBtn) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTopBtn.style.display = 'block';
                    } else {
                        backToTopBtn.style.display = 'none';
                    }
                });
                
                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        },
        
        setupLazyLoading: function() {
            if ('loading' in HTMLImageElement.prototype) {
                const images = document.querySelectorAll('img[loading="lazy"]');
                images.forEach(img => {
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                    }
                });
            } else {
                // Fallback cho trình duyệt không hỗ trợ lazy loading
                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js';
                document.body.appendChild(script);
            }
        },
        
        setupDropdowns: function() {
            document.querySelectorAll('.dropdown-toggle').forEach(function(dropdown) {
                dropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dropdownMenu = this.nextElementSibling;
                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                    } else {
                        // Đóng tất cả dropdown khác
                        document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                            menu.classList.remove('show');
                        });
                        dropdownMenu.classList.add('show');
                    }
                });
            });
            
            // Đóng dropdown khi click ra ngoài
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.dropdown')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
                        menu.classList.remove('show');
                    });
                }
            });
        },
        
        setupPjaxNavigation: function() {
            document.querySelectorAll('a[data-pjax]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.getAttribute('href');
                    
                    // Thêm class loading
                    document.getElementById('ajax-progress-indicator').classList.add('loading');
                    
                    // Thực hiện AJAX
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-PJAX': 'true'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        // Cập nhật tiêu đề và URL
                        const titleMatch = html.match(/<title>(.*?)<\/title>/i);
                        if (titleMatch && titleMatch[1]) {
                            document.title = titleMatch[1];
                        }
                        
                        window.history.pushState({}, '', url);
                        
                        // Trích xuất nội dung chính
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;
                        const newContent = tempDiv.querySelector('#main-content-wrapper').innerHTML;
                        
                        // Cập nhật nội dung
                        document.getElementById('main-content-wrapper').innerHTML = newContent;
                        
                        // Xóa class loading
                        document.getElementById('ajax-progress-indicator').classList.remove('loading');
                        
                        // Scroll lên đầu
                        window.scrollTo(0, 0);
                        
                        // Khởi chạy lại các script
                        StudentApp.init();
                    })
                    .catch(error => {
                        console.error('PJAX navigation error:', error);
                        window.location.href = url; // Fallback
                    });
                });
            });
        },
        
        setupEventRegistration: function() {
            // Xử lý nút đăng ký sự kiện
            document.querySelectorAll('a[href^="<?= base_url('student/events/register/') ?>"]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const eventId = this.getAttribute('href').split('/').pop();
                    const eventCard = this.closest('.event-card');
                    
                    if (eventCard) {
                        const eventName = eventCard.querySelector('.card-title').textContent;
                        const eventDate = eventCard.querySelector('.event-date').textContent.trim();
                        const eventLocation = eventCard.querySelector('.card-text:nth-of-type(1)').textContent.trim();
                        
                        document.getElementById('event_id').value = eventId;
                        document.getElementById('event_name').value = eventName;
                        document.getElementById('event_date').value = eventDate;
                        document.getElementById('event_location').value = eventLocation;
                        
                        const modal = new bootstrap.Modal(document.getElementById('eventRegistrationModal'));
                        modal.show();
                    }
                });
            });
            
            // Xử lý form đăng ký sự kiện
            const eventForm = document.getElementById('eventRegistrationForm');
            if (eventForm) {
                eventForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Hiển thị loader
                    document.getElementById('ajax-progress-indicator').classList.add('loading');
                    
                    // Gửi form bằng fetch API
                    fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Ẩn modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('eventRegistrationModal'));
                        modal.hide();
                        
                        // Hiển thị kết quả
                        StudentApp.showToast(
                            data.success ? 'Đăng ký thành công' : 'Lỗi đăng ký', 
                            data.message, 
                            data.success ? 'success' : 'danger'
                        );
                        
                        // Xóa class loading
                        document.getElementById('ajax-progress-indicator').classList.remove('loading');
                        
                        // Nếu thành công, cập nhật UI
                        if (data.success) {
                            // Cập nhật số lượng slot còn lại hoặc trạng thái nút
                            // Tùy vào cách thiết kế UI
                        }
                    })
                    .catch(error => {
                        console.error('Event registration error:', error);
                        StudentApp.showToast('Lỗi', 'Đã xảy ra lỗi khi xử lý yêu cầu', 'danger');
                        document.getElementById('ajax-progress-indicator').classList.remove('loading');
                    });
                });
            }
        },
        
        showToast: function(title, message, type = 'info') {
            const toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) return;
            
            const toastId = 'toast-' + Date.now();
            const iconClass = {
                'success': 'fa-check-circle',
                'danger': 'fa-exclamation-circle',
                'warning': 'fa-exclamation-triangle',
                'info': 'fa-info-circle'
            };
            
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas ${iconClass[type] || 'fa-info-circle'} me-2"></i>
                            <strong>${title}</strong>: ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Đóng"></button>
                    </div>
                </div>
            `;
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                animation: true,
                autohide: true,
                delay: 5000
            });
            
            toast.show();
            
            // Tự động xóa toast sau khi đóng
            toastElement.addEventListener('hidden.bs.toast', function() {
                toastElement.remove();
            });
        }
    };

    // Khởi tạo ứng dụng khi DOM đã tải xong
    document.addEventListener('DOMContentLoaded', function() {
        StudentApp.init();
    });
    </script>
</body>
</html>
