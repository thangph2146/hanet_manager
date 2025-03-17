/**
 * Main JavaScript - BUH Events Student Dashboard
 * @version 2.0
 * Tối ưu hiệu suất và UX/UI
 */

// Main Manager điều khiển tất cả các thành phần
const MainManager = {
    /**
     * Thiết lập ban đầu
     */
    init: function() {
        this.setupTheme();
        this.setupServiceWorker();
        this.setupAjaxRequests();
        this.setupNProgress();
        this.setupBackToTop();
        this.setupOverlay();
        this.setupPageTransitions();
        this.setupLazyLoading();
        this.setupBodyClasses();
        
        // Đăng ký sự kiện
        window.addEventListener('resize', this.throttle(this.handleResize.bind(this), 250));
        window.addEventListener('scroll', this.throttle(this.handleScroll.bind(this), 100));
        window.addEventListener('load', this.handlePageLoad.bind(this));
        
        console.info('BUH Events - MainManager đã khởi tạo');
    },
    
    /**
     * Thiết lập theme (dark/light)
     */
    setupTheme: function() {
        // Lấy theme từ localStorage
        const storedTheme = localStorage.getItem('theme-preference');
        if (storedTheme) {
            document.documentElement.className = storedTheme === 'dark' ? 'dark-theme' : 'light-theme';
        } else {
            // Dò người dùng có preferDarkMode không
            const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (prefersDark) {
                document.documentElement.className = 'dark-theme';
                localStorage.setItem('theme-preference', 'dark');
            }
        }
        
        // Thiết lập toggle theme nếu có
        const themeToggle = document.querySelector('#theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', this.toggleTheme.bind(this));
        }
    },
    
    /**
     * Chuyển đổi theme
     */
    toggleTheme: function() {
        const currentTheme = document.documentElement.className;
        if (currentTheme === 'light-theme') {
            document.documentElement.className = 'dark-theme';
            localStorage.setItem('theme-preference', 'dark');
        } else {
            document.documentElement.className = 'light-theme';
            localStorage.setItem('theme-preference', 'light');
        }
    },
    
    /**
     * Thiết lập Service Worker
     */
    setupServiceWorker: function() {
        if ('serviceWorker' in navigator && window.location.hostname !== 'localhost') {
            navigator.serviceWorker.register('/sw.js')
            .then(registration => {
                console.log('ServiceWorker đăng ký thành công');
            })
            .catch(error => {
                console.error('ServiceWorker đăng ký thất bại:', error);
            });
        }
    },
    
    /**
     * Thiết lập AJAX requests
     */
    setupAjaxRequests: function() {
        // Cài đặt mặc định cho tất cả AJAX requests
        const csrfToken = document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content');
        
        // Thiết lập CSRF token cho tất cả requests
        if (csrfToken) {
            // Thiết lập cho jQuery AJAX nếu có jQuery
            if (typeof $ !== 'undefined') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
            }
            
            // Thêm token vào tất cả forms
            document.querySelectorAll('form').forEach(form => {
                if (!form.querySelector(`input[name="_token"]`)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = '_token';
                    input.value = csrfToken;
                    form.appendChild(input);
                }
            });
        }
        
        // Xử lý forms với data-ajax="true"
        this.setupAjaxForms();
        
        // Xử lý buttons với data-ajax-action
        this.setupAjaxButtons();
        
        // Xử lý links với data-ajax-link
        this.setupAjaxLinks();
    },
    
    /**
     * Thiết lập AJAX forms
     */
    setupAjaxForms: function() {
        document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                
                // Hiển thị loading
                if (typeof NProgress !== 'undefined') {
                    NProgress.start();
                }
                
                // Lấy thông tin form
                const formData = new FormData(form);
                const submitUrl = form.getAttribute('action') || window.location.href;
                const method = form.getAttribute('method') || 'POST';
                const responseTarget = form.getAttribute('data-target');
                
                // Disable form trong khi submit
                const submitBtn = form.querySelector('[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('loading');
                }
                
                // Gửi request
                fetch(submitUrl, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content')
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
                    
                    // Enable form
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                    }
                    
                    // Xử lý phản hồi
                    if (data.success) {
                        // Reset validation errors
                        form.querySelectorAll('.is-invalid').forEach(el => {
                            el.classList.remove('is-invalid');
                            const feedback = el.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.remove();
                            }
                        });
                        
                        // Nếu thành công
                        if (data.message && typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Thành công',
                                text: data.message,
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                        
                        // Nếu có redirect_url
                        if (data.redirect_url) {
                            this.loadPageWithTransition(data.redirect_url);
                        }
                        
                        // Nếu có responseTarget, cập nhật nội dung
                        if (responseTarget && data.html) {
                            const targetElement = document.querySelector(responseTarget);
                            if (targetElement) {
                                targetElement.innerHTML = data.html;
                                // Kích hoạt lazy loading cho nội dung mới
                                this.setupLazyLoading();
                            }
                        }
                        
                        // Reset form nếu cần
                        if (data.reset_form) {
                            form.reset();
                        }
                        
                        // Callback nếu cần
                        if (data.callback && typeof window[data.callback] === 'function') {
                            window[data.callback](data);
                        }
                    } else {
                        // Nếu có lỗi
                        if (data.message && typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Lỗi',
                                text: data.message,
                                icon: 'error',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000
                            });
                        }
                        
                        // Hiển thị lỗi validation trên form
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
                    
                    // Enable form
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('loading');
                    }
                    
                    console.error('Error:', error);
                    
                    // Hiển thị thông báo lỗi
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Lỗi',
                            text: 'Đã có lỗi xảy ra, vui lòng thử lại sau.',
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                    }
                });
            });
        });
    },
    
    /**
     * Thiết lập AJAX buttons
     */
    setupAjaxButtons: function() {
        document.querySelectorAll('[data-ajax-action]').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Hiển thị loading
                if (typeof NProgress !== 'undefined') {
                    NProgress.start();
                }
                
                // Lấy thông tin hành động
                const url = button.getAttribute('data-ajax-action');
                const method = button.getAttribute('data-method') || 'GET';
                const confirmMessage = button.getAttribute('data-confirm');
                const target = button.getAttribute('data-target');
                
                // Thêm loading cho button
                button.classList.add('loading');
                const originalText = button.innerHTML;
                if (!button.classList.contains('btn-icon')) {
                    button.innerHTML = '';
                }
                
                // Nếu cần xác nhận
                const processAction = () => {
                    fetch(url, {
                        method: method,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content')
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
                        
                        // Restore button
                        button.classList.remove('loading');
                        if (!button.classList.contains('btn-icon')) {
                            button.innerHTML = originalText;
                        }
                        
                        // Xử lý phản hồi
                        if (data.success) {
                            // Nếu thành công
                            if (data.message && typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Thành công',
                                    text: data.message,
                                    icon: 'success',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                            
                            // Nếu có redirect_url
                            if (data.redirect_url) {
                                this.loadPageWithTransition(data.redirect_url);
                            }
                            
                            // Nếu có target, cập nhật nội dung
                            if (target && data.html) {
                                const targetElement = document.querySelector(target);
                                if (targetElement) {
                                    targetElement.innerHTML = data.html;
                                    // Kích hoạt lazy loading cho nội dung mới
                                    this.setupLazyLoading();
                                }
                            }
                            
                            // Callback nếu cần
                            if (data.callback && typeof window[data.callback] === 'function') {
                                window[data.callback](data);
                            }
                        } else {
                            // Nếu có lỗi
                            if (data.message && typeof Swal !== 'undefined') {
                                Swal.fire({
                                    title: 'Lỗi',
                                    text: data.message,
                                    icon: 'error',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 5000
                                });
                            }
                        }
                    })
                    .catch(error => {
                        // Kết thúc loading
                        if (typeof NProgress !== 'undefined') {
                            NProgress.done();
                        }
                        
                        // Restore button
                        button.classList.remove('loading');
                        if (!button.classList.contains('btn-icon')) {
                            button.innerHTML = originalText;
                        }
                        
                        console.error('Error:', error);
                        
                        // Hiển thị thông báo lỗi
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Lỗi',
                                text: 'Đã có lỗi xảy ra, vui lòng thử lại sau.',
                                icon: 'error',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000
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
                        cancelButtonText: 'Hủy',
                        confirmButtonColor: '#0d6efd',
                        cancelButtonColor: '#6c757d'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            processAction();
                        } else {
                            // Kết thúc loading nếu người dùng hủy
                            if (typeof NProgress !== 'undefined') {
                                NProgress.done();
                            }
                            
                            // Restore button
                            button.classList.remove('loading');
                            if (!button.classList.contains('btn-icon')) {
                                button.innerHTML = originalText;
                            }
                        }
                    });
                } else {
                    processAction();
                }
            });
        });
    },
    
    /**
     * Thiết lập AJAX links
     */
    setupAjaxLinks: function() {
        document.querySelectorAll('a[data-ajax="true"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Hiển thị loading
                if (typeof NProgress !== 'undefined') {
                    NProgress.start();
                }
                
                // Lấy URL từ href
                const url = link.getAttribute('href');
                const target = link.getAttribute('data-target');
                
                // Nếu có target, tải nội dung vào target đó
                if (target) {
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        // Kết thúc loading
                        if (typeof NProgress !== 'undefined') {
                            NProgress.done();
                        }
                        
                        // Cập nhật nội dung
                        const targetElement = document.querySelector(target);
                        if (targetElement) {
                            targetElement.innerHTML = html;
                            // Kích hoạt lazy loading cho nội dung mới
                            this.setupLazyLoading();
                        }
                        
                        // Cập nhật URL nếu cần
                        if (link.getAttribute('data-push-state') === 'true') {
                            window.history.pushState({}, '', url);
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
                                icon: 'error',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000
                            });
                        }
                    });
                } else {
                    // Nếu không có target, chuyển hướng đến URL
                    this.loadPageWithTransition(url);
                }
            });
        });
    },
    
    /**
     * Thiết lập NProgress
     */
    setupNProgress: function() {
        if (typeof NProgress !== 'undefined') {
            NProgress.configure({
                showSpinner: false,
                minimum: 0.2,
                speed: 500,
                trickleSpeed: 200,
                parent: '#nprogress-container'
            });
        }
    },
    
    /**
     * Thiết lập nút Back to top
     */
    setupBackToTop: function() {
        const backToTop = document.querySelector('.back-to-top');
        if (!backToTop) return;
        
        // Hiển thị/ẩn nút back to top
        window.addEventListener('scroll', this.throttle(() => {
            if (window.scrollY > 200) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        }, 200));
        
        // Scroll to top khi click
        backToTop.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    },
    
    /**
     * Thiết lập overlay
     */
    setupOverlay: function() {
        const overlay = document.getElementById('overlay');
        if (!overlay) return;
        
        overlay.addEventListener('click', () => {
            // Đóng sidebar khi click overlay
            const sidebar = document.querySelector('.sidebar-wrapper');
            if (sidebar) {
                sidebar.classList.add('toggled');
                document.body.classList.add('sidebar-toggled');
                overlay.style.display = 'none';
            }
        });
    },
    
    /**
     * Thiết lập transitions giữa các trang
     */
    setupPageTransitions: function() {
        // Chặn mọi click vào link không thuộc data-no-transition
        document.addEventListener('click', (e) => {
            const target = e.target.closest('a');
            if (target && !target.hasAttribute('data-no-transition') && !target.hasAttribute('data-ajax') && 
                !target.getAttribute('href').startsWith('#') && !target.getAttribute('href').startsWith('javascript:') &&
                !target.getAttribute('target') && !target.getAttribute('download')) {
                
                e.preventDefault();
                this.loadPageWithTransition(target.getAttribute('href'));
            }
        });
    },
    
    /**
     * Tải trang với hiệu ứng chuyển tiếp
     */
    loadPageWithTransition: function(url) {
        if (typeof NProgress !== 'undefined') {
            NProgress.start();
        }
        
        // Tạo transition
        let transition = document.querySelector('.page-transition');
        if (!transition) {
            transition = document.createElement('div');
            transition.className = 'page-transition';
            document.body.appendChild(transition);
        }
        
        // Hiển thị transition
        transition.classList.add('active');
        
        // Đợi một chút rồi chuyển trang
        setTimeout(() => {
            window.location.href = url;
        }, 300);
    },
    
    /**
     * Thiết lập lazy loading cho hình ảnh và nội dung
     */
    setupLazyLoading: function() {
        // Lazy loading cho hình ảnh
        const lazyImages = document.querySelectorAll('img[data-src]');
        if (lazyImages.length > 0) {
            if ('IntersectionObserver' in window) {
                const imageObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const image = entry.target;
                            image.src = image.dataset.src;
                            image.removeAttribute('data-src');
                            imageObserver.unobserve(image);
                        }
                    });
                });
                
                lazyImages.forEach(image => {
                    imageObserver.observe(image);
                });
            } else {
                // Fallback cho trình duyệt không hỗ trợ IntersectionObserver
                lazyImages.forEach(image => {
                    image.src = image.dataset.src;
                    image.removeAttribute('data-src');
                });
            }
        }
        
        // Lazy loading cho nội dung
        const lazyContents = document.querySelectorAll('[data-lazy-content]');
        if (lazyContents.length > 0) {
            if ('IntersectionObserver' in window) {
                const contentObserver = new IntersectionObserver((entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const container = entry.target;
                            const url = container.dataset.lazyContent;
                            
                            // Tải nội dung
                            fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content')
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.text();
                            })
                            .then(html => {
                                container.innerHTML = html;
                                container.removeAttribute('data-lazy-content');
                                contentObserver.unobserve(container);
                                
                                // Kích hoạt lazy loading cho nội dung mới
                                this.setupLazyLoading();
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                container.innerHTML = `<div class="alert alert-danger">Đã có lỗi xảy ra khi tải nội dung</div>`;
                                container.removeAttribute('data-lazy-content');
                                contentObserver.unobserve(container);
                            });
                        }
                    });
                }, {
                    rootMargin: '100px' // Tải trước khi hiển thị 100px
                });
                
                lazyContents.forEach(container => {
                    contentObserver.observe(container);
                });
            } else {
                // Fallback cho trình duyệt không hỗ trợ IntersectionObserver
                lazyContents.forEach(container => {
                    const url = container.dataset.lazyContent;
                    
                    // Tải nội dung
                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.text();
                    })
                    .then(html => {
                        container.innerHTML = html;
                        container.removeAttribute('data-lazy-content');
                        
                        // Kích hoạt lazy loading cho nội dung mới
                        this.setupLazyLoading();
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        container.innerHTML = `<div class="alert alert-danger">Đã có lỗi xảy ra khi tải nội dung</div>`;
                        container.removeAttribute('data-lazy-content');
                    });
                });
            }
        }
    },
    
    /**
     * Thiết lập các classes cho body
     */
    setupBodyClasses: function() {
        // Kiểm tra và thiết lập classes dựa trên kích thước màn hình
        const isMobile = window.innerWidth < 1025; // Chính xác 1025px theo yêu cầu
        if (isMobile) {
            document.body.classList.add('is-mobile');
        } else {
            document.body.classList.remove('is-mobile');
        }
    },
    
    /**
     * Xử lý khi thay đổi kích thước màn hình
     */
    handleResize: function() {
        this.setupBodyClasses();
    },
    
    /**
     * Xử lý khi scroll
     */
    handleScroll: function() {
        // Sẽ được xử lý bởi các hàm đã đăng ký sự kiện scroll
    },
    
    /**
     * Xử lý khi trang đã tải xong
     */
    handlePageLoad: function() {
        // Ẩn loading page nếu có
        const pageLoader = document.querySelector('.page-loader');
        if (pageLoader) {
            pageLoader.classList.add('loaded');
            setTimeout(() => {
                pageLoader.style.display = 'none';
            }, 500);
        }
        
        // Kết thúc NProgress nếu đang chạy
        if (typeof NProgress !== 'undefined') {
            NProgress.done();
        }
    },
    
    /**
     * Utility function: Throttle - Giới hạn số lần gọi hàm
     */
    throttle: function(callback, delay) {
        let last = 0;
        return function() {
            const now = new Date().getTime();
            if (now - last < delay) {
                return;
            }
            last = now;
            return callback.apply(null, arguments);
        };
    },
    
    /**
     * Utility function: Debounce - Trì hoãn gọi hàm cho đến khi ngừng sự kiện
     */
    debounce: function(callback, delay) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                callback.apply(context, args);
            }, delay);
        };
    }
};

// Khởi tạo MainManager khi DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {
    MainManager.init();
});

// AJAX handlers cho các sự kiện phổ biến
window.ajaxHandlers = {
    /**
     * Reload một phần nội dung
     */
    reloadContent: function(selector, url) {
        const container = document.querySelector(selector);
        if (!container) return;
        
        // Hiển thị skeleton loading
        container.innerHTML = `
            <div class="skeleton-loading">
                <div class="skeleton-item skeleton-title"></div>
                <div class="skeleton-item skeleton-text"></div>
                <div class="skeleton-item skeleton-text"></div>
                <div class="skeleton-item skeleton-text"></div>
            </div>
        `;
        
        // Tải nội dung
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(html => {
            container.innerHTML = html;
            
            // Kích hoạt lazy loading cho nội dung mới
            MainManager.setupLazyLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `<div class="alert alert-danger">Đã có lỗi xảy ra khi tải nội dung</div>`;
        });
    },
    
    /**
     * Đánh dấu thông báo là đã đọc
     */
    markNotificationAsRead: function(id) {
        fetch(`/students/notifications/mark-read/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Cập nhật UI
                const notificationItem = document.querySelector(`.notification-item[data-id="${id}"]`);
                if (notificationItem) {
                    notificationItem.classList.remove('unread');
                }
                
                // Cập nhật badge
                const notifyBadge = document.querySelector('#notifyBadge');
                const notifyCount = document.querySelector('#notifyCount');
                
                if (notifyBadge && notifyCount) {
                    let count = parseInt(notifyCount.textContent);
                    if (count > 0) {
                        count--;
                        notifyCount.textContent = count;
                        
                        if (count === 0) {
                            notifyBadge.style.display = 'none';
                        } else {
                            notifyBadge.textContent = count;
                        }
                    }
                }
                
                // Chuyển hướng nếu có
                if (data.redirect_url) {
                    MainManager.loadPageWithTransition(data.redirect_url);
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
};

// Thiết lập một số shortcut functions cho sử dụng toàn cục
window.BUHEvents = {
    /**
     * Tải nội dung vào container
     */
    loadContent: function(selector, url) {
        window.ajaxHandlers.reloadContent(selector, url);
    },
    
    /**
     * Hiển thị thông báo
     */
    showNotification: function(title, message, type = 'success') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                text: message,
                icon: type,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: type === 'success' ? 3000 : 5000
            });
        } else {
            alert(`${title}: ${message}`);
        }
    },
    
    /**
     * Scroll to element
     */
    scrollTo: function(selector) {
        const element = document.querySelector(selector);
        if (element) {
            window.scrollTo({
                top: element.offsetTop - 80, // Trừ height của header
                behavior: 'smooth'
            });
        }
    }
};
