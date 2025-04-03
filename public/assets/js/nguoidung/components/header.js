// Header Component
class Header {
    constructor() {
        this.init();
    }

    init() {
        this.initMobileSearch();
        this.initNotifications();
        this.initSearchShortcut();
        this.initSidebarToggle();
        this.initUserDropdown();
    }

    initMobileSearch() {
        const mobileSearchBtn = document.querySelector('.mobile-search-btn');
        const mobileSearch = document.querySelector('.mobile-search');
        const mobileSearchClose = document.querySelector('.mobile-search-close');
        const searchInput = document.querySelector('.mobile-search input');

        if (mobileSearchBtn && mobileSearch && mobileSearchClose) {
            mobileSearchBtn.addEventListener('click', () => {
                mobileSearch.classList.add('show');
                document.body.style.overflow = 'hidden';
                setTimeout(() => searchInput?.focus(), 300);
            });

            mobileSearchClose.addEventListener('click', () => {
                mobileSearch.classList.remove('show');
                document.body.style.overflow = '';
                searchInput.value = '';
                this.clearSearchResults();
            });
        }
    }

    initNotifications() {
        const notificationCloseButtons = document.querySelectorAll('.notification-close');
        const markAllReadBtn = document.querySelector('.dropdown-header a');
        const notificationBadge = document.querySelector('#notifications-dropdown .badge');

        notificationCloseButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const item = btn.closest('.notification-item');
                item.style.height = item.offsetHeight + 'px';
                
                // Trigger reflow
                item.offsetHeight;
                
                item.style.height = '0';
                item.style.opacity = '0';
                item.style.marginTop = '0';
                item.style.marginBottom = '0';
                item.style.padding = '0';
                
                setTimeout(() => {
                    item.remove();
                    this.updateNotificationCount();
                }, 300);
            });
        });

        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const items = document.querySelectorAll('.notification-item');
                items.forEach(item => {
                    item.style.opacity = '0.5';
                });
                if (notificationBadge) {
                    notificationBadge.style.display = 'none';
                }
            });
        }
    }

    updateNotificationCount() {
        const badge = document.querySelector('#notifications-dropdown .badge');
        const items = document.querySelectorAll('.notification-item');
        if (badge) {
            const count = items.length;
            badge.textContent = count;
            if (count === 0) {
                badge.style.display = 'none';
            }
        }
    }

    initSearchShortcut() {
        document.addEventListener('keydown', (e) => {
            // Ctrl + / shortcut
            if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                e.preventDefault();
                if (window.innerWidth <= 992) {
                    // Mobile: Show mobile search
                    const mobileSearch = document.querySelector('.mobile-search');
                    const searchInput = document.querySelector('.mobile-search input');
                    if (mobileSearch && searchInput) {
                        mobileSearch.classList.add('show');
                        document.body.style.overflow = 'hidden';
                        setTimeout(() => searchInput.focus(), 300);
                    }
                } else {
                    // Desktop: Focus main search
                    const searchInput = document.querySelector('.nav-search input');
                    searchInput?.focus();
                }
            }

            // Escape to close mobile search
            if (e.key === 'Escape') {
                const mobileSearch = document.querySelector('.mobile-search');
                if (mobileSearch?.classList.contains('show')) {
                    mobileSearch.classList.remove('show');
                    document.body.style.overflow = '';
                }
            }
        });
    }

    initSidebarToggle() {
        const sidebarToggle = document.querySelector('.sidebar-toggle-btn');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                const sidebar = document.querySelector('.sidebar');
                const backdrop = document.querySelector('.sidebar-backdrop');
                
                if (sidebar && backdrop) {
                    sidebar.classList.toggle('show');
                    backdrop.style.visibility = 'visible';
                    backdrop.style.opacity = '1';
                    document.body.style.overflow = 'hidden';
                }
            });
        }
    }

    initUserDropdown() {
        const userDropdownToggle = document.getElementById('user-dropdown');
        const userMenu = document.querySelector('.dropdown-menu.user-menu');
        
        if (!userDropdownToggle || !userMenu) return;
        
        // Sử dụng Bootstrap API nếu có sẵn
        if (typeof bootstrap !== 'undefined') {
            const dropdownInstance = new bootstrap.Dropdown(userDropdownToggle, {
                autoClose: true
            });
            
            // Lắng nghe sự kiện hiển thị/ẩn để cập nhật trạng thái
            userDropdownToggle.addEventListener('shown.bs.dropdown', () => {
                console.log('Dropdown đã hiển thị');
            });
            
            userDropdownToggle.addEventListener('hidden.bs.dropdown', () => {
                console.log('Dropdown đã ẩn');
            });
            
            return;
        }
        
        // Fallback thủ công nếu không có Bootstrap
        userDropdownToggle.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            // Chuyển đổi trạng thái
            userMenu.classList.toggle('show');
            
            if (userMenu.classList.contains('show')) {
                // Đóng các dropdown khác
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    if (menu !== userMenu) menu.classList.remove('show');
                });
                
                // Định vị dropdown
                const rect = userDropdownToggle.getBoundingClientRect();
                userMenu.style.top = rect.bottom + 'px';
                userMenu.style.right = (window.innerWidth - rect.right) + 'px';
                userMenu.style.left = 'auto';
                
                // Lắng nghe click bên ngoài
                setTimeout(() => {
                    document.addEventListener('click', this.closeUserDropdown);
                }, 10);
            }
        });
        
        // Lưu trữ tham chiếu
        this.userDropdownToggle = userDropdownToggle;
        this.userMenu = userMenu;
    }
    
    closeUserDropdown = (e) => {
        if (!this.userMenu) return;
        
        if (!this.userDropdownToggle.contains(e.target) && !this.userMenu.contains(e.target)) {
            this.userMenu.classList.remove('show');
            document.removeEventListener('click', this.closeUserDropdown);
        }
    }

    clearSearchResults() {
        const resultsContainer = document.querySelector('.mobile-search-results');
        if (resultsContainer) {
            resultsContainer.innerHTML = `
                <div class="search-empty-state text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Nhập từ khóa để tìm kiếm</p>
                </div>
            `;
        }
    }
}

// Initialize header when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new Header();
}); 