// Header Component
class Header {
    constructor() {
        this.initElements();
        this.initEventListeners();
    }

    initElements() {
        // Search elements
        this.searchInput = document.querySelector('.nav-search input');
        this.mobileSearchBtn = document.querySelector('.mobile-search-btn');
        this.mobileSearch = document.getElementById('mobile-search');
        this.mobileSearchClose = document.getElementById('mobile-search-close');
        this.mobileSearchInput = this.mobileSearch?.querySelector('input');

        // Notification elements
        this.notificationDropdown = document.getElementById('notifications-dropdown');
        this.notificationItems = document.querySelectorAll('.notification-item');

        // User dropdown
        this.userDropdown = document.getElementById('user-dropdown');
    }

    initEventListeners() {
        // Search functionality
        if (this.searchInput) {
            this.searchInput.addEventListener('focus', () => this.handleSearchFocus());
            this.searchInput.addEventListener('blur', () => this.handleSearchBlur());
        }

        // Mobile search
        if (this.mobileSearchBtn && this.mobileSearch && this.mobileSearchClose) {
            this.mobileSearchBtn.addEventListener('click', () => this.toggleMobileSearch(true));
            this.mobileSearchClose.addEventListener('click', () => this.toggleMobileSearch(false));
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboardShortcuts(e));

        // Notifications hover effect
        this.notificationItems.forEach(item => {
            item.addEventListener('mouseenter', () => this.handleNotificationHover(item, true));
            item.addEventListener('mouseleave', () => this.handleNotificationHover(item, false));
        });

        // Handle dropdowns on mobile
        if (this.notificationDropdown) {
            this.notificationDropdown.addEventListener('show.bs.dropdown', () => this.handleDropdownShow());
            this.notificationDropdown.addEventListener('hide.bs.dropdown', () => this.handleDropdownHide());
        }

        if (this.userDropdown) {
            this.userDropdown.addEventListener('show.bs.dropdown', () => this.handleDropdownShow());
            this.userDropdown.addEventListener('hide.bs.dropdown', () => this.handleDropdownHide());
        }
    }

    handleSearchFocus() {
        this.searchInput.parentElement.classList.add('focused');
    }

    handleSearchBlur() {
        this.searchInput.parentElement.classList.remove('focused');
    }

    toggleMobileSearch(show) {
        if (show) {
            this.mobileSearch.classList.add('show');
            document.body.style.overflow = 'hidden';
            this.mobileSearchInput?.focus();
        } else {
            this.mobileSearch.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    handleKeyboardShortcuts(e) {
        // Search shortcut (Ctrl + /)
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            if (window.innerWidth <= 992) {
                this.toggleMobileSearch(true);
            } else {
                this.searchInput?.focus();
            }
        }

        // Close mobile search with Escape
        if (e.key === 'Escape' && this.mobileSearch?.classList.contains('show')) {
            this.toggleMobileSearch(false);
        }
    }

    handleNotificationHover(item, isHovering) {
        const icon = item.querySelector('.notification-icon');
        if (icon) {
            icon.style.transform = isHovering ? 'scale(1.1)' : '';
            icon.style.transition = 'transform 0.3s ease';
        }
    }

    handleDropdownShow() {
        if (window.innerWidth <= 576) {
            document.body.style.overflow = 'hidden';
        }
    }

    handleDropdownHide() {
        if (window.innerWidth <= 576) {
            document.body.style.overflow = '';
        }
    }
}

// Initialize header when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new Header();
}); 