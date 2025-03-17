/**
 * Header Manager - BUH Events Student Dashboard
 * @version 2.0
 */

const HeaderManager = {
    /**
     * Khởi tạo Header Manager
     */
    init: function() {
        this.setupUserDropdown();
        this.setupNotifications();
        this.setupThemeToggle();
        this.setupMobileMenuButton();
        this.setupSearchBar();
        this.setupScrollHeader();
    },
    
    /**
     * Thiết lập user dropdown
     */
    setupUserDropdown: function() {
        const userDropdownToggle = document.querySelector('.user-dropdown-toggle');
        const userDropdownMenu = document.querySelector('.user-dropdown-menu');
        
        if (!userDropdownToggle || !userDropdownMenu) return;
        
        // Toggle dropdown khi click
        userDropdownToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            userDropdownMenu.classList.toggle('show');
        });
        
        // Đóng dropdown khi click ra ngoài
        document.addEventListener('click', (e) => {
            if (!userDropdownToggle.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                userDropdownMenu.classList.remove('show');
            }
        });
    },
    
    /**
     * Thiết lập thông báo
     */
    setupNotifications: function() {
        const notificationToggle = document.querySelector('.notification-toggle');
        const notificationDropdown = document.querySelector('.notification-dropdown');
        
        if (!notificationToggle || !notificationDropdown) return;
        
        // Toggle dropdown khi click
        notificationToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
            
            // Đánh dấu tất cả thông báo là đã đọc khi mở dropdown
            if (notificationDropdown.classList.contains('show')) {
                this.markNotificationsAsSeen();
            }
        });
        
        // Đóng dropdown khi click ra ngoài
        document.addEventListener('click', (e) => {
            if (!notificationToggle.contains(e.target) && !notificationDropdown.contains(e.target)) {
                notificationDropdown.classList.remove('show');
            }
        });
        
        // Xử lý click vào nút "Đánh dấu tất cả là đã đọc"
        const markAllReadBtn = document.querySelector('.mark-all-read');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.markAllNotificationsAsRead();
            });
        }
        
        // Xử lý click vào từng thông báo
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach(item => {
            item.addEventListener('click', (e) => {
                const id = item.getAttribute('data-id');
                if (id) {
                    this.markNotificationAsRead(id);
                }
            });
        });
    },
    
    /**
     * Đánh dấu thông báo là đã thấy (khi mở dropdown)
     */
    markNotificationsAsSeen: function() {
        const unseenCount = document.querySelector('#notifySeen');
        if (unseenCount) {
            unseenCount.textContent = '0';
            unseenCount.style.display = 'none';
        }
        
        // Gửi request để đánh dấu là đã thấy
        fetch('/students/notifications/mark-seen', {
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
        .catch(error => {
            console.error('Error:', error);
        });
    },
    
    /**
     * Đánh dấu tất cả thông báo là đã đọc
     */
    markAllNotificationsAsRead: function() {
        // Update UI
        const notificationItems = document.querySelectorAll('.notification-item.unread');
        notificationItems.forEach(item => {
            item.classList.remove('unread');
        });
        
        const notifyBadge = document.querySelector('#notifyBadge');
        const notifyCount = document.querySelector('#notifyCount');
        if (notifyBadge && notifyCount) {
            notifyCount.textContent = '0';
            notifyBadge.style.display = 'none';
        }
        
        // Gửi request để đánh dấu tất cả là đã đọc
        fetch('/students/notifications/mark-all-read', {
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
        .catch(error => {
            console.error('Error:', error);
        });
    },
    
    /**
     * Đánh dấu một thông báo cụ thể là đã đọc
     */
    markNotificationAsRead: function(id) {
        // Update UI
        const notificationItem = document.querySelector(`.notification-item[data-id="${id}"]`);
        if (notificationItem && notificationItem.classList.contains('unread')) {
            notificationItem.classList.remove('unread');
            
            // Cập nhật badge nếu cần
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
        }
        
        // Sử dụng handler từ ajaxHandlers
        if (window.ajaxHandlers && typeof window.ajaxHandlers.markNotificationAsRead === 'function') {
            window.ajaxHandlers.markNotificationAsRead(id);
        }
    },
    
    /**
     * Thiết lập toggle theme
     */
    setupThemeToggle: function() {
        const themeToggle = document.querySelector('.theme-toggle');
        if (!themeToggle) return;
        
        themeToggle.addEventListener('click', () => {
            // Theme toggle được xử lý trong MainManager
            if (window.MainManager && typeof window.MainManager.toggleTheme === 'function') {
                window.MainManager.toggleTheme();
            } else {
                // Fallback nếu MainManager không có sẵn
                const currentTheme = document.documentElement.className;
                const newTheme = currentTheme.includes('dark-theme') ? 'light-theme' : 'dark-theme';
                
                document.documentElement.className = newTheme;
                localStorage.setItem('theme-preference', newTheme === 'dark-theme' ? 'dark' : 'light');
                
                // Update icon
                const iconElement = themeToggle.querySelector('i');
                if (iconElement) {
                    iconElement.className = newTheme === 'dark-theme' ? 'bi bi-sun-fill' : 'bi bi-moon-fill';
                }
            }
        });
    },
    
    /**
     * Thiết lập nút menu mobile
     */
    setupMobileMenuButton: function() {
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        if (!mobileMenuButton) return;
        
        mobileMenuButton.addEventListener('click', (e) => {
            e.preventDefault();
            
            // Sử dụng SidebarManager nếu có
            if (window.SidebarManager && typeof window.SidebarManager.toggleSidebar === 'function') {
                window.SidebarManager.toggleSidebar();
            } else {
                // Fallback nếu SidebarManager không có sẵn
                const sidebar = document.querySelector('.sidebar-wrapper');
                const overlay = document.getElementById('overlay');
                
                if (sidebar) {
                    sidebar.classList.toggle('toggled');
                    document.body.classList.toggle('sidebar-toggled');
                    
                    // Hiển thị/ẩn overlay
                    if (overlay) {
                        if (sidebar.classList.contains('toggled')) {
                            overlay.style.display = 'none';
                        } else {
                            overlay.style.display = 'block';
                        }
                    }
                }
            }
        });
        
        // Điều chỉnh hiển thị của nút mobile-menu-button dựa trên kích thước màn hình
        this.adjustMobileMenuButton();
        
        // Thêm sự kiện resize để luôn cập nhật trạng thái
        window.addEventListener('resize', this.throttle(() => {
            this.adjustMobileMenuButton();
        }, 250));
    },
    
    /**
     * Điều chỉnh hiển thị của nút mobile-menu-button
     */
    adjustMobileMenuButton: function() {
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        if (!mobileMenuButton) return;
        
        const isMobile = window.innerWidth < 1025; // Chính xác 1025px theo yêu cầu
        
        if (isMobile) {
            mobileMenuButton.style.display = 'flex';
        } else {
            mobileMenuButton.style.display = 'none';
        }
    },
    
    /**
     * Thiết lập thanh tìm kiếm
     */
    setupSearchBar: function() {
        const searchInput = document.querySelector('.search-input');
        const searchForm = document.querySelector('.search-form');
        
        if (!searchInput || !searchForm) return;
        
        // Xử lý submit form tìm kiếm
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const query = searchInput.value.trim();
            if (query === '') return;
            
            // Thêm loader
            if (typeof NProgress !== 'undefined') {
                NProgress.start();
            }
            
            // Chuyển hướng đến trang tìm kiếm với tham số query
            window.location.href = `/students/search?q=${encodeURIComponent(query)}`;
        });
        
        // Xử lý live search (AJAX) nếu cần
        searchInput.addEventListener('input', this.debounce((e) => {
            const query = e.target.value.trim();
            
            // Chỉ thực hiện live search nếu có ít nhất 2 ký tự
            if (query.length >= 2) {
                this.performLiveSearch(query);
            }
        }, 500));
    },
    
    /**
     * Thực hiện live search
     */
    performLiveSearch: function(query) {
        // Hiển thị loader hoặc indicator
        const searchResults = document.querySelector('.search-results');
        if (!searchResults) return;
        
        searchResults.innerHTML = `
            <div class="search-loading">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Đang tìm kiếm...</span>
                </div>
                <span class="ms-2">Đang tìm kiếm...</span>
            </div>
        `;
        searchResults.style.display = 'block';
        
        // Gửi request AJAX để lấy kết quả tìm kiếm
        fetch(`/students/api/search?q=${encodeURIComponent(query)}`, {
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
            // Hiển thị kết quả tìm kiếm
            if (data.results && data.results.length > 0) {
                let resultsHtml = `<div class="search-result-list">`;
                
                data.results.forEach(result => {
                    resultsHtml += `
                        <a href="${result.url}" class="search-result-item">
                            <div class="search-result-icon">
                                <i class="${result.icon || 'bi bi-calendar-event'}"></i>
                            </div>
                            <div class="search-result-content">
                                <div class="search-result-title">${result.title}</div>
                                <div class="search-result-desc">${result.description || ''}</div>
                            </div>
                        </a>
                    `;
                });
                
                resultsHtml += `</div>`;
                
                if (data.total_count > data.results.length) {
                    resultsHtml += `
                        <div class="search-result-footer">
                            <a href="/students/search?q=${encodeURIComponent(query)}" class="search-view-all">
                                Xem tất cả ${data.total_count} kết quả
                            </a>
                        </div>
                    `;
                }
                
                searchResults.innerHTML = resultsHtml;
            } else {
                searchResults.innerHTML = `
                    <div class="search-empty">
                        <div class="search-empty-icon">
                            <i class="bi bi-search"></i>
                        </div>
                        <div class="search-empty-text">Không tìm thấy kết quả phù hợp</div>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            searchResults.innerHTML = `
                <div class="search-error">
                    <div class="search-error-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>
                    <div class="search-error-text">Đã có lỗi xảy ra khi tìm kiếm</div>
                </div>
            `;
        });
    },
    
    /**
     * Thiết lập hiệu ứng cho header khi scroll
     */
    setupScrollHeader: function() {
        const header = document.querySelector('.top-header');
        if (!header) return;
        
        // Thiết lập trạng thái ban đầu
        if (window.scrollY > 10) {
            header.classList.add('scrolled');
        }
        
        window.addEventListener('scroll', this.throttle(() => {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }, 100));
        
        // Theo dõi trạng thái sidebar để điều chỉnh header
        const sidebar = document.querySelector('.sidebar-wrapper');
        const wrapper = document.querySelector('.wrapper');
        
        if (sidebar && wrapper) {
            // Kiểm tra trạng thái ban đầu
            this.adjustHeaderWidth(header, wrapper, sidebar);
            
            // Theo dõi sự kiện hover trên sidebar
            let hoverTimeout; // Biến để lưu trữ timeout
            
            sidebar.addEventListener('mouseenter', () => {
                clearTimeout(hoverTimeout);
                if (wrapper.classList.contains('sidebar-mini')) {
                    // Khi hover vào sidebar thu gọn, header mở rộng tạm thời
                    setTimeout(() => {
                        header.style.marginLeft = '250px'; 
                        header.style.width = 'calc(100% - 250px)';
                    }, 50); // Thêm độ trễ để đồng bộ với hiệu ứng của sidebar
                }
            });
            
            sidebar.addEventListener('mouseleave', () => {
                // Khi hover ra, trở về trạng thái trước đó sau một khoảng thời gian
                clearTimeout(hoverTimeout);
                hoverTimeout = setTimeout(() => {
                    this.adjustHeaderWidth(header, wrapper, sidebar);
                }, 150); // Thêm độ trễ để tránh giật
            });
            
            // Theo dõi sự kiện thay đổi class
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        // Khi class của wrapper thay đổi (mini/không mini)
                        this.adjustHeaderWidth(header, wrapper, sidebar);
                    }
                });
            });
            
            observer.observe(wrapper, { attributes: true });
            
            // Theo dõi sự kiện resize để điều chỉnh khi thay đổi kích thước màn hình
            window.addEventListener('resize', this.throttle(() => {
                this.adjustHeaderWidth(header, wrapper, sidebar);
            }, 250));
        }
    },
    
    /**
     * Điều chỉnh độ rộng header theo trạng thái sidebar
     */
    adjustHeaderWidth: function(header, wrapper, sidebar) {
        if (!header || !wrapper || !sidebar) return;
        
        // Trên mobile
        if (window.innerWidth < 1025) {
            header.style.marginLeft = '0';
            header.style.width = '100%';
            return;
        }
        
        // Trên desktop
        if (wrapper.classList.contains('sidebar-mini')) {
            // Sidebar đang thu gọn
            header.style.marginLeft = '70px';
            header.style.width = 'calc(100% - 70px)';
        } else {
            // Sidebar đang mở rộng
            header.style.marginLeft = '250px';
            header.style.width = 'calc(100% - 250px)';
        }
    },
    
    /**
     * Utility function: Throttle
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
     * Utility function: Debounce
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

// Khởi tạo Header Manager khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    HeaderManager.init();
});

/**
 * Mở rộng HeaderManager với xử lý dropdown nâng cao
 * Xử lý dropdown bị giới hạn trong scope block
 * @version 1.0
 */

// Thêm chức năng xử lý fixed dropdown
HeaderManager.setupFixedDropdowns = function() {
    // Di chuyển tất cả các dropdown ra ngoài scope giới hạn sử dụng template
    const container = document.getElementById('fixed-dropdowns-container');
    
    // Di chuyển notification dropdown
    const notifyTemplate = document.getElementById('notification-dropdown-template');
    if (notifyTemplate && container) {
        container.appendChild(notifyTemplate.content.cloneNode(true));
    }
    
    // Di chuyển user dropdown
    const userTemplate = document.getElementById('user-dropdown-template');
    if (userTemplate && container) {
        container.appendChild(userTemplate.content.cloneNode(true));
    }
    
    // Thiết lập các toggle cho dropdown
    this.setupDropdownEvents();
};

HeaderManager.setupDropdownEvents = function() {
    // Lấy tất cả các dropdown trigger và fixed-dropdown
    const triggers = document.querySelectorAll('[id]');
    const fixedDropdowns = document.querySelectorAll('.fixed-dropdown[data-dropdown-for]');
    
    triggers.forEach(trigger => {
        const id = trigger.getAttribute('id');
        const dropdown = document.querySelector(`.fixed-dropdown[data-dropdown-for="${id}"]`);
        
        if (trigger && dropdown) {
            // Click event cho trigger
            trigger.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                // Ẩn tất cả các dropdown khác
                fixedDropdowns.forEach(d => {
                    if (d !== dropdown) {
                        d.classList.remove('show');
                    }
                });
                
                // Toggle dropdown hiện tại
                dropdown.classList.toggle('show');
                
                if (dropdown.classList.contains('show')) {
                    // Định vị dropdown dưới trigger
                    this.positionDropdown(trigger, dropdown);
                }
            });
        }
    });
    
    // Click outside để đóng dropdown
    document.addEventListener('click', (e) => {
        let clickedInside = false;
        
        // Kiểm tra xem click có nằm trong dropdown hoặc trigger không
        triggers.forEach(trigger => {
            if (trigger.contains(e.target)) {
                clickedInside = true;
            }
        });
        
        fixedDropdowns.forEach(dropdown => {
            if (dropdown.contains(e.target)) {
                clickedInside = true;
            }
        });
        
        // Nếu click bên ngoài, đóng tất cả dropdown
        if (!clickedInside) {
            fixedDropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    });
    
    // Xử lý resize window
    window.addEventListener('resize', this.throttle(() => {
        fixedDropdowns.forEach(dropdown => {
            if (dropdown.classList.contains('show')) {
                const forId = dropdown.getAttribute('data-dropdown-for');
                const trigger = document.getElementById(forId);
                if (trigger) {
                    this.positionDropdown(trigger, dropdown);
                }
            }
        });
    }, 250));
};

// Hàm tính toán vị trí cho dropdown
HeaderManager.positionDropdown = function(trigger, dropdown) {
    const triggerRect = trigger.getBoundingClientRect();
    const dropdownWidth = dropdown.offsetWidth;
    const windowWidth = window.innerWidth;
    
    // Xác định vị trí trên/dưới
    let top = triggerRect.bottom + window.scrollY;
    
    // Xác định vị trí trái/phải
    let left;
    if (triggerRect.right - dropdownWidth < 0) {
        // Không đủ không gian bên trái
        left = triggerRect.left;
    } else {
        // Căn phải
        left = triggerRect.right - dropdownWidth;
    }
    
    // Đảm bảo dropdown không bị tràn ra khỏi màn hình
    if (left + dropdownWidth > windowWidth) {
        left = windowWidth - dropdownWidth - 5;
    }
    
    if (left < 0) {
        left = 5;
    }
    
    // Áp dụng vị trí
    dropdown.style.top = `${top}px`;
    dropdown.style.left = `${left}px`;
};

// Mở rộng init để bao gồm chức năng fixed dropdown
const originalInit = HeaderManager.init;
HeaderManager.init = function() {
    originalInit.call(this);
    this.setupFixedDropdowns();
};

// Khởi tạo lại nếu DOM đã sẵn sàng
if (document.readyState === 'complete' || document.readyState === 'interactive') {
    HeaderManager.setupFixedDropdowns();
}
