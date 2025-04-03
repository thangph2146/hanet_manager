// Student Dashboard App - Main Layout Controller
class StudentLayout {
    constructor() {
        // Lưu trữ tham chiếu toàn cục
        window.StudentUI = this;
        this.init();
    }

    init() {
        this.initElements();
        this.initEventListeners();
        this.checkActiveSidebar();
    }

    initElements() {
        this.sidebarToggle = document.getElementById('sidebar-toggle');
        this.sidebar = document.getElementById('sidebar');
        this.sidebarBackdrop = document.getElementById('sidebar-backdrop');
        this.mobileSearch = document.getElementById('mobile-search');
        this.mobileSearchClose = document.getElementById('mobile-search-close');
        this.mainContent = document.querySelector('.main-content');
        this.dropdowns = document.querySelectorAll('.dropdown-toggle');
        this.sidebarItems = document.querySelectorAll('.sidebar-menu .nav-link');
    }

    initEventListeners() {
        // Sidebar Toggle
        if (this.sidebarToggle && this.sidebar && this.sidebarBackdrop) {
            this.sidebarToggle.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleSidebar();
            });
            this.sidebarBackdrop.addEventListener('click', () => this.closeSidebar());
        }

        // Mobile Search
        const mobileSearchBtn = document.querySelector('.mobile-search-btn');
        if (mobileSearchBtn && this.mobileSearch && this.mobileSearchClose) {
            mobileSearchBtn.addEventListener('click', () => this.openMobileSearch());
            this.mobileSearchClose.addEventListener('click', () => this.closeMobileSearch());
        }

        // Handle Dropdowns on Mobile
        this.dropdowns.forEach(dropdown => {
            dropdown.addEventListener('click', () => this.handleDropdownClick());
        });

        document.addEventListener('hidden.bs.dropdown', () => this.handleDropdownHide());

        // Sidebar Item Click
        this.sidebarItems.forEach(item => {
            item.addEventListener('click', (e) => {
                this.sidebarItems.forEach(i => i.classList.remove('active'));
                item.classList.add('active');
                
                // Auto close sidebar on mobile
                if (window.innerWidth <= 992) {
                    setTimeout(() => this.closeSidebar(), 300);
                }
            });
        });

        // Window resize
        window.addEventListener('resize', () => this.handleResize());

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboardShortcuts(e));
    }

    toggleSidebar() {
        if (!this.sidebar || !this.sidebarBackdrop) return;
        
        this.sidebar.classList.toggle('show');
        this.sidebarBackdrop.classList.toggle('show');
        document.body.style.overflow = this.sidebar.classList.contains('show') ? 'hidden' : '';
    }

    closeSidebar() {
        if (!this.sidebar || !this.sidebarBackdrop) return;
        
        this.sidebar.classList.remove('show');
        this.sidebarBackdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    checkActiveSidebar() {
        // Kiểm tra URL hiện tại để highlight menu tương ứng
        const currentPath = window.location.pathname;
        
        this.sidebarItems.forEach(item => {
            const href = item.getAttribute('href');
            if (!href) return;
            
            const path = href.split('?')[0]; // Bỏ query params nếu có
            
            if (currentPath === path || 
                currentPath.startsWith(path) && path !== '/' && path !== '/nguoi-dung') {
                item.classList.add('active');
                
                // Mở menu cha nếu item nằm trong dropdown
                const parentDropdown = item.closest('.nav-item.dropdown');
                if (parentDropdown) {
                    parentDropdown.classList.add('show');
                    const dropdownMenu = parentDropdown.querySelector('.dropdown-menu');
                    if (dropdownMenu) {
                        dropdownMenu.classList.add('show');
                    }
                }
            } else {
                item.classList.remove('active');
            }
        });
    }

    openMobileSearch() {
        if (!this.mobileSearch) return;
        
        this.mobileSearch.classList.add('show');
        document.body.style.overflow = 'hidden';
        this.mobileSearch.querySelector('input')?.focus();
    }

    closeMobileSearch() {
        if (!this.mobileSearch) return;
        
        this.mobileSearch.classList.remove('show');
        document.body.style.overflow = '';
    }

    handleDropdownClick() {
        if (window.innerWidth <= 576) {
            document.body.style.overflow = 'hidden';
        }
    }

    handleDropdownHide() {
        if (window.innerWidth <= 576) {
            document.body.style.overflow = '';
        }
    }

    handleResize() {
        if (window.innerWidth > 992 && this.sidebar && this.sidebar.classList.contains('show')) {
            this.closeSidebar();
        }
    }

    handleKeyboardShortcuts(e) {
        // Ctrl + / for search
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            if (window.innerWidth <= 992) {
                this.openMobileSearch();
            } else {
                document.querySelector('.nav-search input')?.focus();
            }
        }

        // Escape to close mobile search
        if (e.key === 'Escape' && this.mobileSearch && this.mobileSearch.classList.contains('show')) {
            this.closeMobileSearch();
        }
        
        // Escape to close sidebar
        if (e.key === 'Escape' && this.sidebar && this.sidebar.classList.contains('show')) {
            this.closeSidebar();
        }
    }

    // API cho components khác sử dụng
    showToast(message, type = 'success') {
        // Tạo toast từ bootstrap hoặc custom
        const toastContainer = document.querySelector('.toast-container') || this.createToastContainer();
        
        const toast = document.createElement('div');
        toast.className = `toast show bg-${type}`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="toast-header">
                <strong class="me-auto">${type === 'success' ? 'Thành công' : 'Thông báo'}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body text-white">
                ${message}
            </div>
        `;
        
        toastContainer.appendChild(toast);
        
        // Tự động ẩn sau 5s
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    }
    
    createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
        document.body.appendChild(container);
        return container;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new StudentLayout();
}); 