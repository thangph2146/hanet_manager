/**
 * Sidebar Manager - BUH Events Student Dashboard
 * @version 2.0
 */

const SidebarManager = {
    /**
     * Khởi tạo Sidebar Manager
     */
    init: function() {
        this.setupSidebarToggle();
        this.setupMenuAccordion();
        this.setupMobileDetection();
        this.setupResizeHandler();
        this.updateSidebarState();
    },
    
    /**
     * Thiết lập nút chuyển đổi sidebar
     */
    setupSidebarToggle: function() {
        const toggleButtons = document.querySelectorAll('.sidebar-toggle, .toggle-icon');
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const sidebar = document.querySelector('.sidebar-wrapper');
        const overlay = document.getElementById('overlay');
        
        if (!sidebar) return;
        
        // Xử lý nút toggle trong sidebar
        toggleButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleSidebar();
            });
        });
        
        // Xử lý nút menu mobile
        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggleSidebar();
            });
        }
        
        // Đóng sidebar khi click vào overlay
        if (overlay) {
            overlay.addEventListener('click', () => {
                this.closeSidebar();
            });
        }
    },
    
    /**
     * Mở/đóng sidebar
     */
    toggleSidebar: function() {
        const sidebar = document.querySelector('.sidebar-wrapper');
        const overlay = document.getElementById('overlay');
        const wrapper = document.querySelector('.wrapper') || document.body;
        
        if (!sidebar) return;
        
        // Xử lý khác nhau giữa mobile và desktop
        if (window.innerWidth < 1025) {
            // Mobile: Hiển thị/ẩn sidebar hoàn toàn
            sidebar.classList.toggle('toggled');
            document.body.classList.toggle('sidebar-toggled');
            
            // Hiển thị/ẩn overlay trên mobile
            if (overlay) {
                if (sidebar.classList.contains('toggled')) {
                    overlay.style.display = 'none';
                } else {
                    overlay.style.display = 'block';
                }
            }
        } else {
            // Desktop: Chuyển đổi giữa sidebar đầy đủ và thu gọn (mini)
            wrapper.classList.toggle('sidebar-mini');
            
            // Xoay biểu tượng mũi tên (đã có trong CSS: .wrapper.sidebar-mini .toggle-icon i)
        }
    },
    
    /**
     * Đóng sidebar (chủ yếu sử dụng trên mobile)
     */
    closeSidebar: function() {
        const sidebar = document.querySelector('.sidebar-wrapper');
        const overlay = document.getElementById('overlay');
        
        if (!sidebar) return;
        
        // Thêm classes
        sidebar.classList.add('toggled');
        document.body.classList.add('sidebar-toggled');
        
        // Ẩn overlay
        if (overlay) {
            overlay.style.display = 'none';
        }
    },
    
    /**
     * Thiết lập menu accordion
     */
    setupMenuAccordion: function() {
        const menuItems = document.querySelectorAll('.sidebar-item.has-submenu');
        
        menuItems.forEach(item => {
            const link = item.querySelector('.sidebar-link');
            const submenu = item.querySelector('.sidebar-submenu');
            
            if (link && submenu) {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    
                    // Toggle active class
                    item.classList.toggle('active');
                    
                    // Mở/đóng submenu với animation
                    if (item.classList.contains('active')) {
                        submenu.style.height = submenu.scrollHeight + 'px';
                    } else {
                        submenu.style.height = '0';
                    }
                });
            }
        });
        
        // Mở submenu của menu item đang active
        const activeItems = document.querySelectorAll('.sidebar-item.active');
        activeItems.forEach(item => {
            const submenu = item.querySelector('.sidebar-submenu');
            if (submenu) {
                submenu.style.height = submenu.scrollHeight + 'px';
            }
        });
    },
    
    /**
     * Thiết lập phát hiện thiết bị di động
     */
    setupMobileDetection: function() {
        const isMobile = window.innerWidth < 1025;
        if (isMobile) {
            document.body.classList.add('is-mobile');
            this.closeSidebar();
        } else {
            document.body.classList.remove('is-mobile');
            this.openSidebar();
        }
    },
    
    /**
     * Mở sidebar (chủ yếu sử dụng trên desktop)
     */
    openSidebar: function() {
        const sidebar = document.querySelector('.sidebar-wrapper');
        
        if (!sidebar) return;
        
        // Xóa classes
        sidebar.classList.remove('toggled');
        document.body.classList.remove('sidebar-toggled');
    },
    
    /**
     * Thiết lập xử lý khi thay đổi kích thước màn hình
     */
    setupResizeHandler: function() {
        let resizeTimer;
        
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                this.setupMobileDetection();
                this.updateSidebarState();
            }, 250);
        });
    },
    
    /**
     * Cập nhật trạng thái sidebar dựa trên kích thước màn hình
     */
    updateSidebarState: function() {
        const sidebar = document.querySelector('.sidebar-wrapper');
        const overlay = document.getElementById('overlay');
        
        if (!sidebar) return;
        
        if (window.innerWidth < 1025) {
            // Trên mobile, mặc định sidebar đóng
            sidebar.classList.add('toggled');
            document.body.classList.add('sidebar-toggled');
            
            if (overlay) {
                overlay.style.display = 'none';
            }
        } else {
            // Trên desktop, mặc định sidebar mở
            sidebar.classList.remove('toggled');
            document.body.classList.remove('sidebar-toggled');
            
            if (overlay) {
                overlay.style.display = 'none';
            }
        }
    },
    
    /**
     * Cập nhật trạng thái active cho menu dựa trên URL hiện tại
     */
    updateActiveMenu: function() {
        const currentPath = window.location.pathname;
        const menuItems = document.querySelectorAll('.sidebar-item');
        
        // Xóa tất cả active class
        menuItems.forEach(item => {
            item.classList.remove('active');
            const submenu = item.querySelector('.sidebar-submenu');
            if (submenu) {
                submenu.style.height = '0';
            }
        });
        
        // Thiết lập active class cho menu item phù hợp
        menuItems.forEach(item => {
            const link = item.querySelector('.sidebar-link');
            if (!link) return;
            
            const href = link.getAttribute('href');
            if (!href) return;
            
            // Kiểm tra nếu URL hiện tại chứa href của menu item
            if (currentPath === href || (currentPath.startsWith(href) && href !== '/')) {
                item.classList.add('active');
                
                // Nếu item là con của submenu, kích hoạt parent
                const parentSubmenu = item.closest('.sidebar-submenu');
                if (parentSubmenu) {
                    const parentItem = parentSubmenu.closest('.sidebar-item');
                    if (parentItem) {
                        parentItem.classList.add('active');
                        parentSubmenu.style.height = parentSubmenu.scrollHeight + 'px';
                    }
                }
                
                // Mở submenu
                const submenu = item.querySelector('.sidebar-submenu');
                if (submenu) {
                    submenu.style.height = submenu.scrollHeight + 'px';
                }
            }
        });
    }
};

// Khởi tạo Sidebar Manager khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    SidebarManager.init();
    SidebarManager.updateActiveMenu();
});
