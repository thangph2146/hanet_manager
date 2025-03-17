/**
 * Student App Layout JavaScript
 * Xử lý tương tác cho layout Student App
 */
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar
    const sidebarToggleBtn = document.querySelector('.header-toggle-btn');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const body = document.body;
    
    if (sidebarToggleBtn && sidebar) {
        sidebarToggleBtn.addEventListener('click', function() {
            toggleSidebar();
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            toggleSidebar();
        });
    }
    
    function toggleSidebar() {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        
        // For desktop view
        if (window.innerWidth >= 768) {
            body.classList.toggle('sidebar-closed');
        }
    }
    
    // Close sidebar on mobile when clicking a menu item
    const menuLinks = document.querySelectorAll('.menu-link');
    if (menuLinks.length > 0 && sidebar) {
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768 && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });
        });
    }
    
    // Active menu item based on current page
    function setActiveMenuItem() {
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.menu-link');
        
        menuLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href) && href !== '#' && href !== '/') {
                link.classList.add('active');
            }
        });
    }
    
    setActiveMenuItem();
    
    // Responsive handling
    function handleResize() {
        if (window.innerWidth >= 768) {
            // Desktop/tablet view
            sidebarOverlay.classList.remove('active');
        } else {
            // Mobile view
            body.classList.remove('sidebar-closed');
            if (!sidebar.classList.contains('active')) {
                sidebarOverlay.classList.remove('active');
            }
        }
    }
    
    window.addEventListener('resize', handleResize);
    handleResize(); // Initial check
});

/**
 * Layout JS
 * Xử lý các chức năng chung cho toàn bộ layout
 */
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo các component nếu chưa được khởi tạo
    if (!window.studentNotification && typeof StudentNotification !== 'undefined') {
        window.studentNotification = new StudentNotification();
    }
    
    if (!window.studentModal && typeof StudentModal !== 'undefined') {
        window.studentModal = new StudentModal();
    }
    
    // Xử lý tự động ẩn loading sau khi trang đã tải
    const pageLoader = document.querySelector('.page-loader');
    if (pageLoader) {
        setTimeout(function() {
            pageLoader.classList.add('loaded');
            setTimeout(function() {
                pageLoader.style.display = 'none';
            }, 300);
        }, 500);
    }
    
    // Xử lý dropdown menu
    initDropdowns();
    
    // Xử lý tooltip
    initTooltips();
    
    // Xử lý collapse
    initCollapses();
    
    // Xử lý tab
    initTabs();
    
    // Xử lý scroll to top
    initScrollToTop();
    
    // Xử lý theme switcher
    initThemeSwitcher();
    
    // Xử lý nút thông báo
    initNotificationButton();
});

/**
 * Khởi tạo các dropdown menu
 */
function initDropdowns() {
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const dropdown = this.closest('.dropdown');
            const menu = dropdown.querySelector('.dropdown-menu');
            
            // Đóng tất cả các dropdown khác
            document.querySelectorAll('.dropdown.show').forEach(item => {
                if (item !== dropdown) {
                    item.classList.remove('show');
                    item.querySelector('.dropdown-menu').classList.remove('show');
                }
            });
            
            // Toggle dropdown hiện tại
            dropdown.classList.toggle('show');
            menu.classList.toggle('show');
        });
    });
    
    // Đóng dropdown khi click bên ngoài
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown.show').forEach(item => {
                item.classList.remove('show');
                item.querySelector('.dropdown-menu').classList.remove('show');
            });
        }
    });
}

/**
 * Khởi tạo tooltips
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', function() {
            const text = this.getAttribute('data-tooltip');
            const position = this.getAttribute('data-tooltip-position') || 'top';
            
            // Tạo tooltip
            const tooltipEl = document.createElement('div');
            tooltipEl.className = `tooltip tooltip-${position}`;
            tooltipEl.textContent = text;
            
            // Thêm vào body
            document.body.appendChild(tooltipEl);
            
            // Tính toán vị trí
            const rect = this.getBoundingClientRect();
            const tooltipRect = tooltipEl.getBoundingClientRect();
            
            let top, left;
            
            switch (position) {
                case 'top':
                    top = rect.top - tooltipRect.height - 10;
                    left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                    break;
                case 'bottom':
                    top = rect.bottom + 10;
                    left = rect.left + (rect.width / 2) - (tooltipRect.width / 2);
                    break;
                case 'left':
                    top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                    left = rect.left - tooltipRect.width - 10;
                    break;
                case 'right':
                    top = rect.top + (rect.height / 2) - (tooltipRect.height / 2);
                    left = rect.right + 10;
                    break;
            }
            
            // Điều chỉnh để không vượt ra ngoài viewport
            if (left < 10) left = 10;
            if (left + tooltipRect.width > window.innerWidth - 10) {
                left = window.innerWidth - tooltipRect.width - 10;
            }
            
            if (top < 10) top = 10;
            if (top + tooltipRect.height > window.innerHeight - 10) {
                top = window.innerHeight - tooltipRect.height - 10;
            }
            
            tooltipEl.style.top = `${top}px`;
            tooltipEl.style.left = `${left}px`;
            
            // Hiển thị tooltip
            setTimeout(() => {
                tooltipEl.classList.add('show');
            }, 10);
            
            // Lưu tooltip vào data
            this._tooltip = tooltipEl;
        });
        
        tooltip.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.classList.remove('show');
                
                // Xóa tooltip sau khi animation kết thúc
                setTimeout(() => {
                    if (this._tooltip && this._tooltip.parentNode) {
                        this._tooltip.parentNode.removeChild(this._tooltip);
                        this._tooltip = null;
                    }
                }, 300);
            }
        });
    });
}

/**
 * Khởi tạo collapsible panels
 */
function initCollapses() {
    const collapseToggles = document.querySelectorAll('[data-toggle="collapse"]');
    
    collapseToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('data-target');
            const target = document.querySelector(targetId);
            
            if (!target) return;
            
            // Toggle collapse
            target.classList.toggle('show');
            
            // Cập nhật aria-expanded
            this.setAttribute('aria-expanded', target.classList.contains('show') ? 'true' : 'false');
        });
    });
}

/**
 * Khởi tạo tabs
 */
function initTabs() {
    const tabToggles = document.querySelectorAll('[data-toggle="tab"]');
    
    tabToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('data-target');
            const target = document.querySelector(targetId);
            
            if (!target) return;
            
            // Lấy tab container
            const tabContainer = this.closest('.tabs');
            if (!tabContainer) return;
            
            // Bỏ active ở tất cả các tab
            tabContainer.querySelectorAll('.tab-toggle').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Bỏ active ở tất cả các tab content
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.remove('active');
            });
            
            // Thêm active cho tab hiện tại
            this.classList.add('active');
            target.classList.add('active');
            
            // Lưu trạng thái tab vào localStorage nếu có id
            const tabId = tabContainer.id;
            if (tabId) {
                localStorage.setItem(`tab_${tabId}`, targetId);
            }
        });
    });
    
    // Khôi phục tab từ localStorage
    document.querySelectorAll('.tabs').forEach(tabContainer => {
        const tabId = tabContainer.id;
        if (!tabId) return;
        
        const savedTab = localStorage.getItem(`tab_${tabId}`);
        if (savedTab) {
            const toggle = tabContainer.querySelector(`[data-target="${savedTab}"]`);
            if (toggle) {
                toggle.click();
            }
        }
    });
}

/**
 * Khởi tạo scroll to top button
 */
function initScrollToTop() {
    const scrollBtn = document.querySelector('.scroll-to-top');
    if (!scrollBtn) return;
    
    // Hiện/ẩn nút khi scroll
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollBtn.classList.add('show');
        } else {
            scrollBtn.classList.remove('show');
        }
    });
    
    // Xử lý click
    scrollBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Khởi tạo theme switcher
 */
function initThemeSwitcher() {
    const themeSwitcher = document.querySelector('.theme-switcher');
    if (!themeSwitcher) return;
    
    // Lấy theme từ localStorage
    const currentTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-theme', currentTheme);
    
    // Cập nhật trạng thái switch
    if (currentTheme === 'dark') {
        themeSwitcher.classList.add('active');
        themeSwitcher.querySelector('input[type="checkbox"]').checked = true;
    }
    
    // Xử lý click
    themeSwitcher.addEventListener('click', function() {
        const checkbox = this.querySelector('input[type="checkbox"]');
        checkbox.checked = !checkbox.checked;
        
        const newTheme = checkbox.checked ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        this.classList.toggle('active', checkbox.checked);
    });
}

/**
 * Khởi tạo nút thông báo
 */
function initNotificationButton() {
    const notificationBtn = document.querySelector('.notification-btn');
    if (!notificationBtn) return;
    
    notificationBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        // Hiển thị panel thông báo
        const panel = document.querySelector('.notification-panel');
        if (!panel) return;
        
        panel.classList.toggle('show');
        
        // Đánh dấu đã đọc (xóa badge)
        const badge = this.querySelector('.notification-badge');
        if (badge) {
            badge.classList.add('hidden');
        }
    });
    
    // Đóng panel khi click bên ngoài
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.notification-panel') && !e.target.closest('.notification-btn')) {
            const panel = document.querySelector('.notification-panel');
            if (panel && panel.classList.contains('show')) {
                panel.classList.remove('show');
            }
        }
    });
}

/**
 * Hiển thị loading spinner
 * @param {boolean} show - true để hiển thị, false để ẩn
 */
function showLoading(show = true) {
    let loader = document.querySelector('.ajax-loader');
    
    if (!loader) {
        loader = document.createElement('div');
        loader.className = 'ajax-loader';
        loader.innerHTML = '<div class="spinner"></div>';
        document.body.appendChild(loader);
    }
    
    if (show) {
        loader.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    } else {
        loader.style.display = 'none';
        document.body.style.overflow = '';
    }
}

/**
 * Định dạng số, thêm dấu phân cách hàng nghìn
 * @param {number} number - Số cần định dạng
 * @param {number} decimals - Số chữ số thập phân
 * @param {string} decPoint - Dấu thập phân
 * @param {string} thousandsSep - Dấu phân cách hàng nghìn
 * @returns {string} - Chuỗi đã định dạng
 */
function formatNumber(number, decimals = 0, decPoint = '.', thousandsSep = ',') {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    const n = !isFinite(+number) ? 0 : +number;
    const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals);
    const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep;
    const dec = (typeof decPoint === 'undefined') ? '.' : decPoint;
    
    const toFixedFix = function(n, prec) {
        const k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
    };
    
    const s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    
    return s.join(dec);
}

/**
 * Định dạng ngày tháng
 * @param {Date|string} date - Đối tượng Date hoặc chuỗi ngày tháng
 * @param {string} format - Định dạng mong muốn
 * @returns {string} - Chuỗi ngày tháng đã định dạng
 */
function formatDate(date, format = 'dd/MM/yyyy') {
    if (!(date instanceof Date)) {
        date = new Date(date);
    }
    
    if (isNaN(date.getTime())) {
        return '';
    }
    
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');
    
    return format
        .replace('dd', day)
        .replace('MM', month)
        .replace('yyyy', year)
        .replace('HH', hours)
        .replace('mm', minutes)
        .replace('ss', seconds);
}

// Xuất các hàm utility để sử dụng global
window.studentUtils = {
    showLoading,
    formatNumber,
    formatDate
}; 