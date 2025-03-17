// Student Dashboard App
class StudentLayout {
    constructor() {
        this.init();
    }

    init() {
        this.initElements();
        this.initEventListeners();
        this.hideLoader();
    }

    initElements() {
        this.sidebarToggle = document.getElementById('sidebar-toggle');
        this.sidebar = document.getElementById('sidebar');
        this.sidebarBackdrop = document.getElementById('sidebar-backdrop');
        this.mobileSearch = document.getElementById('mobile-search');
        this.mobileSearchClose = document.getElementById('mobile-search-close');
        this.mainContent = document.querySelector('.main-content');
        this.dropdowns = document.querySelectorAll('.dropdown-toggle');
    }

    initEventListeners() {
        // Sidebar Toggle
        if (this.sidebarToggle && this.sidebar && this.sidebarBackdrop) {
            this.sidebarToggle.addEventListener('click', () => this.toggleSidebar());
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

        // Window resize
        window.addEventListener('resize', () => this.handleResize());

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboardShortcuts(e));
    }

    toggleSidebar() {
        this.sidebar.classList.toggle('show');
        this.sidebarBackdrop.classList.toggle('show');
        document.body.style.overflow = this.sidebar.classList.contains('show') ? 'hidden' : '';
    }

    closeSidebar() {
        this.sidebar.classList.remove('show');
        this.sidebarBackdrop.classList.remove('show');
        document.body.style.overflow = '';
    }

    openMobileSearch() {
        this.mobileSearch.classList.add('show');
        document.body.style.overflow = 'hidden';
        this.mobileSearch.querySelector('input')?.focus();
    }

    closeMobileSearch() {
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
        if (window.innerWidth > 992 && this.sidebar.classList.contains('show')) {
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
        if (e.key === 'Escape' && this.mobileSearch.classList.contains('show')) {
            this.closeMobileSearch();
        }
    }

    hideLoader() {
        setTimeout(() => {
            document.querySelector('.page-loader').style.display = 'none';
        }, 500);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new StudentLayout();
}); 