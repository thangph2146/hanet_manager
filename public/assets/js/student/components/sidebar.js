// Sidebar Component
class Sidebar {
    constructor() {
        this.initElements();
        this.initEventListeners();
        this.checkActiveLinks();
        this.initSubmenuState();
    }

    initElements() {
        this.sidebar = document.getElementById('sidebar');
        this.backdrop = document.getElementById('sidebar-backdrop');
        this.menuItems = document.querySelectorAll('.sidebar-link');
        this.submenuItems = document.querySelectorAll('.submenu-link');
        this.upgradeBtn = document.querySelector('.upgrade-pro-btn');
        this.submenus = document.querySelectorAll('.submenu');
    }

    initEventListeners() {
        // Handle menu item hover effects
        this.menuItems.forEach(item => {
            item.addEventListener('mouseenter', () => this.handleMenuHover(item, true));
            item.addEventListener('mouseleave', () => this.handleMenuHover(item, false));
        });

        // Handle submenu item hover effects
        this.submenuItems.forEach(item => {
            item.addEventListener('mouseenter', () => this.handleSubmenuHover(item, true));
            item.addEventListener('mouseleave', () => this.handleSubmenuHover(item, false));
        });

        // Handle backdrop click
        if (this.backdrop) {
            this.backdrop.addEventListener('click', () => this.closeSidebar());
        }

        // Handle window resize
        window.addEventListener('resize', () => this.handleResize());

        // Handle upgrade button hover
        if (this.upgradeBtn) {
            this.upgradeBtn.addEventListener('mouseenter', () => this.handleUpgradeHover(true));
            this.upgradeBtn.addEventListener('mouseleave', () => this.handleUpgradeHover(false));
        }

        // Handle submenu collapse events
        this.submenus.forEach(submenu => {
            submenu.addEventListener('show.bs.collapse', () => this.handleSubmenuShow(submenu));
            submenu.addEventListener('hide.bs.collapse', () => this.handleSubmenuHide(submenu));
        });
    }

    handleMenuHover(item, isHovering) {
        const icon = item.querySelector('.menu-icon');
        const badge = item.querySelector('.badge-pro');
        const arrow = item.querySelector('.submenu-arrow');
        
        if (icon) {
            icon.style.transform = isHovering ? 'translateY(-2px)' : '';
        }
        
        if (badge && isHovering) {
            badge.style.transform = 'scale(1.1)';
            badge.style.transition = 'transform 0.3s ease';
        } else if (badge) {
            badge.style.transform = '';
        }

        if (arrow && !item.classList.contains('active')) {
            arrow.style.color = isHovering ? 'var(--primary-color)' : '';
        }
    }

    handleSubmenuHover(item, isHovering) {
        const badge = item.querySelector('.badge-sub, .badge-pro');
        
        if (badge) {
            badge.style.transform = isHovering ? 'scale(1.1)' : '';
            badge.style.transition = 'transform 0.3s ease';
        }
    }

    handleSubmenuShow(submenu) {
        const parentLink = submenu.previousElementSibling;
        if (parentLink) {
            parentLink.classList.add('active');
        }
    }

    handleSubmenuHide(submenu) {
        const parentLink = submenu.previousElementSibling;
        if (parentLink && !this.hasActiveChild(submenu)) {
            parentLink.classList.remove('active');
        }
    }

    hasActiveChild(submenu) {
        return submenu.querySelector('.submenu-link.active') !== null;
    }

    initSubmenuState() {
        // Open submenu if it contains active item
        this.submenus.forEach(submenu => {
            if (this.hasActiveChild(submenu)) {
                const bsCollapse = new bootstrap.Collapse(submenu, {
                    toggle: false
                });
                bsCollapse.show();
            }
        });
    }

    checkActiveLinks() {
        const currentPath = window.location.pathname;
        
        // Check main menu items
        this.menuItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && href !== '#' && currentPath.includes(href)) {
                item.classList.add('active');
                this.scrollToActiveItem(item);
            }
        });

        // Check submenu items
        this.submenuItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && currentPath.includes(href)) {
                item.classList.add('active');
                this.scrollToActiveItem(item);
                
                // Activate parent menu
                const parentSubmenu = item.closest('.submenu');
                if (parentSubmenu) {
                    const parentLink = parentSubmenu.previousElementSibling;
                    if (parentLink) {
                        parentLink.classList.add('active');
                    }
                }
            }
        });
    }

    scrollToActiveItem(item) {
        const container = document.querySelector('.sidebar-menu');
        if (container) {
            const itemOffset = item.offsetTop;
            const containerHeight = container.offsetHeight;
            const scrollOffset = itemOffset - (containerHeight / 2);
            
            container.scrollTo({
                top: Math.max(0, scrollOffset),
                behavior: 'smooth'
            });
        }
    }

    handleResize() {
        if (window.innerWidth > 992) {
            this.closeSidebar();
        }
    }

    toggleSidebar() {
        if (this.sidebar) {
            this.sidebar.classList.toggle('show');
            if (this.backdrop) {
                this.backdrop.classList.toggle('show');
            }
            document.body.style.overflow = this.sidebar.classList.contains('show') ? 'hidden' : '';
        }
    }

    closeSidebar() {
        if (this.sidebar && this.sidebar.classList.contains('show')) {
            this.sidebar.classList.remove('show');
            if (this.backdrop) {
                this.backdrop.classList.remove('show');
            }
            document.body.style.overflow = '';
        }
    }

    handleUpgradeHover(isHovering) {
        if (isHovering) {
            this.upgradeBtn.style.transform = 'translateY(-2px)';
            this.upgradeBtn.style.boxShadow = '0 6px 16px rgba(138, 43, 226, 0.2)';
        } else {
            this.upgradeBtn.style.transform = '';
            this.upgradeBtn.style.boxShadow = '';
        }
    }
}

// Initialize sidebar when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.sidebarInstance = new Sidebar();
}); 