<!DOCTYPE html>
<html lang="vi" class="light-theme">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="BUH Events - Hệ thống quản lý sự kiện sinh viên">
    <meta name="keywords" content="dashboard, events, student, management">
    <meta name="author" content="BUH Dev Team">
    <meta name="X-CSRF-TOKEN" content="<?= csrf_hash() ?>">
    <meta name="theme-color" content="#0d6efd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    
    <!-- Title -->
    <title><?= $this->renderSection('title') ?> | BUH Events</title>
    
    <!-- Favicons -->
    <link rel="icon" href="<?= base_url('assets/images/favicon-32x32.png') ?>" type="image/png" />
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/apple-touch-icon.png') ?>" />
    
    <!-- Preload fonts -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" as="style" />
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" as="style" />
    
    <!-- Preload Critical CSS -->
    <link rel="preload" href="<?= base_url('assets/css/bootstrap.min.css') ?>" as="style" />
    <link rel="preload" href="<?= base_url('assets/modules/layouts/student/css/header.css') ?>" as="style" />
    <link rel="preload" href="<?= base_url('assets/modules/layouts/student/css/sidebar.css') ?>" as="style" />
    
    <!-- Preload Critical JS -->
    <link rel="preload" href="<?= base_url('assets/js/jquery.min.js') ?>" as="script" />
    <link rel="preload" href="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>" as="script" />
    
    <!-- CSS Libraries - Critical Path -->
    <link href="<?= base_url('assets/css/bootstrap.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/css/icons.css') ?>" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    
    <!-- NProgress for Ajax loading -->
    <link href="<?= base_url('assets/plugins/nprogress/nprogress.css') ?>" rel="stylesheet" />
    
    <!-- Layout Component CSS - Critical Path -->
    <link href="<?= base_url('assets/modules/layouts/student/css/header.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/modules/layouts/student/css/sidebar.css') ?>" rel="stylesheet" />
    
   
    
    <!-- Non-Critical CSS (deferred) -->
    <link href="<?= base_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet" media="print" onload="this.media='all'" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" media="print" onload="this.media='all'" />
    <link href="<?= base_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" media="print" onload="this.media='all'" />
    <link href="<?= base_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" media="print" onload="this.media='all'" />
    
    <!-- Theme Style CSS (deferred) -->
    <link href="<?= base_url('assets/css/dark-theme.css') ?>" rel="stylesheet" media="print" onload="this.media='all'" />
    <link href="<?= base_url('assets/css/semi-dark.css') ?>" rel="stylesheet" media="print" onload="this.media='all'" />
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" media="print" onload="this.media='all'" />
    
    <!-- Additional Layout Component CSS (deferred) -->
    <link href="<?= base_url('assets/modules/layouts/student/css/footer.css') ?>" rel="stylesheet" media="print" onload="this.media='all'" />
    
    <!-- Custom CSS (deferred) -->
    <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet" media="print" onload="this.media='all'" />
    
    <!-- Component CSS -->
    <?= $this->renderSection('styles') ?>
    
    <!-- Fallback for deferred CSS loading -->
    <noscript>
        <link href="<?= base_url('assets/css/bootstrap-extended.css') ?>" rel="stylesheet" />
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
        <link href="<?= base_url('assets/plugins/simplebar/css/simplebar.css') ?>" rel="stylesheet" />
        <link href="<?= base_url('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') ?>" rel="stylesheet" />
        <link href="<?= base_url('assets/css/dark-theme.css') ?>" rel="stylesheet" />
        <link href="<?= base_url('assets/css/semi-dark.css') ?>" rel="stylesheet" />
        <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet" />
        <link href="<?= base_url('assets/modules/layouts/student/css/footer.css') ?>" rel="stylesheet" />
        <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet" />
    </noscript>
</head>

<body>
    <!-- Page progress bar -->
    <div id="nprogress-container"></div>
    
    <!-- Wrapper -->
    <div class="wrapper">
        <!-- Overlay -->
        <div class="overlay" id="overlay"></div>
         <!-- Header - Tách riêng ra ngoài page-content-wrapper -->
         <?php include(APPPATH . 'Modules/layouts/student/Views/components/header.php'); ?>
        
       
        
        <!-- Main Content -->
        <div class="page-content-wrapper">
        <!-- Sidebar -->
        <?php include(APPPATH . 'Modules/layouts/student/Views/components/sidebar.php'); ?>

            <!-- Page Content -->
            <div class="page-content">
                <div class="container-fluid">
                    <?= $this->renderSection('content') ?>
                </div>
                  <!-- Footer -->
            <?php include(APPPATH . 'Modules/layouts/student/Views/components/footer.php'); ?>
            </div>
            
          
        </div>
    </div>
    
    <!-- Back to top button -->
    <a href="javascript:void(0);" class="back-to-top"><i class="bi bi-arrow-up"></i></a>
    
    <!-- Critical JS Libraries -->
    <script src="<?= base_url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/nprogress/nprogress.js') ?>"></script>
    
    <!-- Deferred JS Libraries -->
    <script src="<?= base_url('assets/plugins/simplebar/js/simplebar.min.js') ?>" defer></script>
    <script src="<?= base_url('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') ?>" defer></script>
    
    <!-- Base Layout JS -->
    <script src="<?= base_url('assets/modules/layouts/student/js/main.js') ?>"></script>
    <script src="<?= base_url('assets/modules/layouts/student/js/sidebar.js') ?>" defer></script>
    <script src="<?= base_url('assets/modules/layouts/student/js/header.js') ?>"></script>
    
    <!-- Component JS -->
    <?= $this->renderSection('scripts') ?>
    
    <!-- CSRF Token Handler -->
    <script>
    // Đính kèm CSRF token vào tất cả form
    document.addEventListener('DOMContentLoaded', function() {
        // Thêm token vào form
        const csrfToken = '<?= csrf_hash() ?>';
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            // Chỉ thêm nếu form không có trường CSRF
            if (!form.querySelector('input[name="<?= csrf_token() ?>"]')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '<?= csrf_token() ?>';
                input.value = csrfToken;
                form.appendChild(input);
            }
        });
        
        // Áp dụng simplebar cho các phần tử có scroll
        if (typeof SimpleBar !== 'undefined') {
            const scrollableElements = document.querySelectorAll('[data-simplebar="true"]');
            scrollableElements.forEach(function(element) {
                new SimpleBar(element);
            });
        }
        
        // Kiểm tra theme từ localStorage
        const storedTheme = localStorage.getItem('theme-preference');
        if (storedTheme) {
            document.documentElement.className = storedTheme === 'dark' ? 'dark-theme' : 'light-theme';
        }
        
        // Kiểm tra và điều chỉnh mobile-menu-button dựa trên kích thước màn hình
        function adjustForMobile() {
            const isMobile = window.innerWidth < 1025; // Chính xác 1025px theo yêu cầu
            const sidebarWrapper = document.querySelector('.sidebar-wrapper');
            
            // Thêm/xóa class cho body
            if (isMobile) {
                document.body.classList.add('is-mobile');
                
                // Đảm bảo sidebar ẩn khi tải trang trên mobile
                if (sidebarWrapper && !sidebarWrapper.classList.contains('toggled')) {
                    sidebarWrapper.classList.add('toggled');
                    document.body.classList.add('sidebar-toggled');
                }
            } else {
                document.body.classList.remove('is-mobile');
            }
        }
        
        // Thực hiện điều chỉnh khi trang tải xong
        adjustForMobile();
        
        // Thêm sự kiện resize để điều chỉnh khi thay đổi kích thước màn hình
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(adjustForMobile, 250);
        });
    });
    </script>
    
    <!-- AJAX Handler -->
    <script>
    // Xử lý các request AJAX
    document.addEventListener('DOMContentLoaded', function() {
        // Nếu trình duyệt hỗ trợ Service Worker và đang ở môi trường production
        if ('serviceWorker' in navigator && window.location.hostname !== 'localhost') {
            navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('ServiceWorker registration successful');
            })
            .catch(error => {
                console.error('ServiceWorker registration failed:', error);
            });
        }
        
        // Xử lý tất cả các form trong trang
        document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Hiển thị loading
                if (typeof NProgress !== 'undefined') {
                    NProgress.start();
                }
                
                // Lấy dữ liệu form
                const formData = new FormData(this);
                const submitUrl = this.getAttribute('action') || window.location.href;
                const method = this.getAttribute('method') || 'POST';
                const responseTarget = this.getAttribute('data-target');
                
                // Gửi request
                fetch(submitUrl, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Kết thúc loading
                    if (typeof NProgress !== 'undefined') {
                        NProgress.done();
                    }
                    
                    // Xử lý phản hồi
                    if (data.success) {
                        // Nếu thành công
                        if (data.message && typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Thành công',
                                text: data.message,
                                icon: 'success'
                            });
                        }
                        
                        // Nếu có redirect_url
                        if (data.redirect_url) {
                            setTimeout(() => {
                                window.location.href = data.redirect_url;
                            }, 1000);
                        }
                        
                        // Nếu có responseTarget, cập nhật nội dung
                        if (responseTarget) {
                            const targetElement = document.querySelector(responseTarget);
                            if (targetElement && data.html) {
                                targetElement.innerHTML = data.html;
                            }
                        }
                        
                        // Reset form nếu cần
                        if (data.reset_form) {
                            form.reset();
                        }
                    } else {
                        // Nếu có lỗi
                        if (data.message && typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Lỗi',
                                text: data.message,
                                icon: 'error'
                            });
                        }
                        
                        // Hiển thị lỗi trên form nếu có
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const inputElement = form.querySelector(`[name="${field}"]`);
                                if (inputElement) {
                                    inputElement.classList.add('is-invalid');
                                    
                                    // Tạo feedback
                                    let feedbackElement = inputElement.nextElementSibling;
                                    if (!feedbackElement || !feedbackElement.classList.contains('invalid-feedback')) {
                                        feedbackElement = document.createElement('div');
                                        feedbackElement.className = 'invalid-feedback';
                                        inputElement.parentNode.insertBefore(feedbackElement, inputElement.nextSibling);
                                    }
                                    
                                    feedbackElement.textContent = data.errors[field];
                                }
                            });
                        }
                    }
                })
                .catch(error => {
                    // Kết thúc loading
                    if (typeof NProgress !== 'undefined') {
                        NProgress.done();
                    }
                    
                    console.error('Error:', error);
                    
                    // Hiển thị thông báo lỗi
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Lỗi',
                            text: 'Đã có lỗi xảy ra, vui lòng thử lại sau.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
        
        // Xử lý tất cả các nút có data-ajax-action
        document.querySelectorAll('[data-ajax-action]').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Hiển thị loading
                if (typeof NProgress !== 'undefined') {
                    NProgress.start();
                }
                
                // Lấy thông tin hành động
                const url = this.getAttribute('data-ajax-action');
                const method = this.getAttribute('data-method') || 'GET';
                const confirmMessage = this.getAttribute('data-confirm');
                const target = this.getAttribute('data-target');
                
                // Nếu cần xác nhận
                const processAction = () => {
                    fetch(url, {
                        method: method,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Kết thúc loading
                        if (typeof NProgress !== 'undefined') {
                            NProgress.done();
                        }
                        
                        // Xử lý phản hồi
                        if (data.success) {
                            // Nếu thành công
                            if (data.message && typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Thành công',
                                    text: data.message,
                                    icon: 'success'
                                });
                            }
                            
                            // Nếu có redirect_url
                            if (data.redirect_url) {
                                setTimeout(() => {
                                    window.location.href = data.redirect_url;
                                }, 1000);
                            }
                            
                            // Nếu có target, cập nhật nội dung
                            if (target && data.html) {
                                const targetElement = document.querySelector(target);
                                if (targetElement) {
                                    targetElement.innerHTML = data.html;
                                }
                            }
                        } else {
                            // Nếu có lỗi
                            if (data.message && typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Lỗi',
                                    text: data.message,
                                    icon: 'error'
                                });
                            }
                        }
                    })
                    .catch(error => {
                        // Kết thúc loading
                        if (typeof NProgress !== 'undefined') {
                            NProgress.done();
                        }
                        
                        console.error('Error:', error);
                        
                        // Hiển thị thông báo lỗi
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Lỗi',
                                text: 'Đã có lỗi xảy ra, vui lòng thử lại sau.',
                                icon: 'error'
                            });
                        }
                    });
                };
                
                // Xác nhận trước khi thực hiện hành động nếu có confirmMessage
                if (confirmMessage && typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Xác nhận',
                        text: confirmMessage,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Xác nhận',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            processAction();
                        } else {
                            // Kết thúc loading nếu người dùng hủy
                            if (typeof NProgress !== 'undefined') {
                                NProgress.done();
                            }
                        }
                    });
                } else {
                    processAction();
                }
            });
        });
    });
    </script>
</body>
</html> 