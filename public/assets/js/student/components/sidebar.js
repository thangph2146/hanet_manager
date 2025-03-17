// Sidebar Component
class Sidebar {
    constructor() {
        this.sidebar = document.querySelector('.sidebar');
        this.backdrop = document.querySelector('.sidebar-backdrop');
        this.closeBtn = document.querySelector('.sidebar-close');
        this.searchInput = document.querySelector('.sidebar-search input');
        this.menuItems = document.querySelectorAll('.sidebar-link');
        this.submenuItems = document.querySelectorAll('.submenu-link');
        this.upgradeBtn = document.querySelector('.upgrade-pro-btn');
        this.submenus = document.querySelectorAll('.submenu');
        
        this.init();
    }

    init() {
        this.initSubmenuState();
        this.initEventListeners();
        this.initSearch();
        this.scrollToActiveItem();
    }

    initEventListeners() {
        // Close button click
        if (this.closeBtn) {
            this.closeBtn.addEventListener('click', () => this.closeSidebar());
        }

        // Backdrop click
        if (this.backdrop) {
            this.backdrop.addEventListener('click', () => this.closeSidebar());
        }

        // Menu item hover
        this.menuItems.forEach(item => {
            item.addEventListener('mouseenter', () => this.handleMenuHover(item, true));
            item.addEventListener('mouseleave', () => this.handleMenuHover(item, false));
        });

        // Submenu item hover
        this.submenuItems.forEach(item => {
            item.addEventListener('mouseenter', () => this.handleSubmenuHover(item, true));
            item.addEventListener('mouseleave', () => this.handleSubmenuHover(item, false));
        });

        // Window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) {
                this.closeSidebar();
            }
        });

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

    initSearch() {
        if (!this.searchInput) return;

        this.searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const allLinks = [...this.menuItems, ...this.submenuItems];

            allLinks.forEach(link => {
                const text = link.textContent.toLowerCase();
                const listItem = link.closest('li');
                
                if (text.includes(searchTerm)) {
                    listItem.style.display = '';
                    // If it's a submenu item, show its parent menu
                    if (link.classList.contains('submenu-link')) {
                        const parentSubmenu = link.closest('.submenu');
                        if (parentSubmenu) {
                            parentSubmenu.style.display = '';
                            const parentCollapse = parentSubmenu.closest('.collapse');
                            if (parentCollapse) {
                                parentCollapse.classList.add('show');
                            }
                        }
                    }
                } else {
                    listItem.style.display = 'none';
                }
            });
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

        if (isHovering) {
            item.style.backgroundColor = 'rgba(138, 43, 226, 0.05)';
        } else {
            item.style.backgroundColor = '';
        }
    }

    handleSubmenuHover(item, isHovering) {
        const badge = item.querySelector('.badge-sub, .badge-pro');
        
        if (badge) {
            badge.style.transform = isHovering ? 'scale(1.1)' : '';
            badge.style.transition = 'transform 0.3s ease';
        }

        if (isHovering) {
            item.style.backgroundColor = 'rgba(138, 43, 226, 0.03)';
        } else {
            item.style.backgroundColor = '';
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
        const activeSubmenuLink = document.querySelector('.submenu-link.active');
        if (activeSubmenuLink) {
            const parentCollapse = activeSubmenuLink.closest('.collapse');
            if (parentCollapse) {
                parentCollapse.classList.add('show');
            }
        }
    }

    scrollToActiveItem() {
        const activeItem = document.querySelector('.sidebar-link.active, .submenu-link.active');
        if (activeItem) {
            setTimeout(() => {
                activeItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 300);
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
        this.sidebar.classList.remove('show');
        if (this.backdrop) {
            this.backdrop.style.visibility = 'hidden';
            this.backdrop.style.opacity = '0';
        }
        document.body.style.overflow = '';
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
    new Sidebar();
}); 