/**
 * Sidebar JavaScript - Xử lý tương tác và tính năng của sidebar
 * Tối ưu cho thiết bị di động và desktop
 * @version 3.0
 */

// Sử dụng IIFE để ngăn ô nhiễm biến toàn cục
(function() {
    'use strict';

    // Các hằng số và cài đặt
    const MOBILE_BREAKPOINT = 1025;
    const SIDEBAR_TOGGLED_CLASS = 'sidebar-toggled';
    const SIDEBAR_MINI_CLASS = 'sidebar-mini';
    const ACTIVE_CLASS = 'mm-active';
    const SHOW_SUBMENU_CLASS = 'mm-show';
    const ANIMATE_CLASS = 'animate-sidebar';
    
    // Cache các phần tử DOM
    const body = document.body;
    const wrapper = document.querySelector('.wrapper');
    const sidebar = document.querySelector('.sidebar-wrapper');
    const overlay = document.getElementById('overlay');
    const toggleIcon = document.querySelector('.toggle-icon');
    const mobileMenuBtn = document.querySelector('.mobile-menu-button');
    const metisMenu = document.getElementById('sidemenu');
    const menuItems = metisMenu ? metisMenu.querySelectorAll('li') : [];
    const sidebarSearch = document.querySelector('.sidebar-search .search-input');
    const sidebarSearchClear = document.querySelector('.sidebar-search-clear');
    
    // Biến state
    let isMiniSidebar = false;
    let isSidebarOpen = false;
    let isResizing = false;
    let lastWindowWidth = window.innerWidth;
    let activeMenuItems = {};
    
    /**
     * Kiểm tra nếu đang ở chế độ mobile
     * @returns {boolean} true nếu màn hình nhỏ hơn breakpoint
     */
    const isMobile = () => window.innerWidth < MOBILE_BREAKPOINT;
    
    /**
     * Khởi tạo sidebar
     */
    function initSidebar() {
        setupEventListeners();
        restoreSidebarState();
        highlightActiveMenu();
        handleInitialState();
        
        // Thêm các data-menu-id cho các menu item
        setupMenuIdentifiers();
    }
    
    /**
     * Thiết lập ID cho các menu items để theo dõi trạng thái active
     */
    function setupMenuIdentifiers() {
        if (!metisMenu) return;
        
        // Gán ID cho tất cả menu items
        const menuLinks = metisMenu.querySelectorAll('a[href]');
        
        menuLinks.forEach((link, index) => {
            const href = link.getAttribute('href');
            if (!href || href === '#') return;
            
            // Tạo ID dựa trên tên menu hoặc URL
            const menuText = link.querySelector('.menu-title');
            const menuName = menuText ? menuText.textContent.trim().toLowerCase().replace(/\s+/g, '-') : `menu-${index}`;
            const menuId = `menu-item-${menuName}`;
            
            // Gán data attribute
            link.setAttribute('data-menu-id', menuId);
            
            // Thêm vào danh sách để theo dõi
            activeMenuItems[menuId] = link;
        });
    }
    
    /**
     * Thiết lập trạng thái ban đầu dựa trên kích thước màn hình
     */
    function handleInitialState() {
        if (isMobile()) {
            // Trên mobile luôn ẩn sidebar khi tải trang
            closeSidebar();
            
            // Đảm bảo không có chế độ mini trên mobile
            if (wrapper) {
                wrapper.classList.remove(SIDEBAR_MINI_CLASS);
            }
        } else {
            // Trên desktop, sử dụng trạng thái đã lưu
            if (isMiniSidebar) {
                toggleMiniSidebar(true, false);
            } else {
                openSidebar(false);
            }
        }
        
        // Thêm class cho animation sau khi thiết lập trạng thái
        if (sidebar) {
            setTimeout(() => {
                sidebar.classList.add('sidebar-initialized');
            }, 100);
        }
    }
    
    /**
     * Thiết lập các event listener
     */
    function setupEventListeners() {
        // Toggle mini sidebar
        if (toggleIcon) {
            toggleIcon.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMiniSidebar();
            });
        }
        
        // Mobile menu toggle
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSidebar();
            });
        }
        
        // Sự kiện resize cửa sổ
        window.addEventListener('resize', debounce(handleResize, 150));
        
        // Click vào overlay để đóng sidebar
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }
        
        // Xử lý submenu dropdowns
        setupSubmenus();
        
        // Xử lý tìm kiếm
        setupSearch();
        
        // Lắng nghe sự kiện từ header
        document.addEventListener('header:menuToggled', function(e) {
            if (e.detail && e.detail.isOpen !== undefined) {
                if (e.detail.isOpen) {
                    openSidebar(false);
                } else {
                    closeSidebar(false);
                }
            }
        });
        
        // Lắng nghe sự kiện active menu từ header
        document.addEventListener('header:activeMenu', function(e) {
            if (e.detail && e.detail.menuId) {
                syncMenuFromHeader(e.detail.menuId);
            }
        });
    }

    /**
     * Đồng bộ menu active từ header
     * @param {string} menuId - ID của menu cần đánh dấu active
     */
    function syncMenuFromHeader(menuId) {
        if (!menuId || !metisMenu) return;

        // Xóa tất cả indicator hiện tại
        document.querySelectorAll('.menu-active-indicator').forEach(indicator => {
            indicator.remove();
        });

        // Lưu menuId vào data attribute để CSS có thể sử dụng
        document.documentElement.style.setProperty('--active-menu', `"${menuId}"`);
        
        // Xóa tất cả active class hiện tại
        metisMenu.querySelectorAll('.' + ACTIVE_CLASS).forEach(item => {
            item.classList.remove(ACTIVE_CLASS);
        });
        
        // Xóa tất cả submenu đang mở
        metisMenu.querySelectorAll('.' + SHOW_SUBMENU_CLASS).forEach(submenu => {
            submenu.classList.remove(SHOW_SUBMENU_CLASS);
            submenu.style.maxHeight = null;
        });
        
        // Tìm và đánh dấu active menu mới
        const menuItem = metisMenu.querySelector(`a[data-menu-id="${menuId}"]`);
        
        if (menuItem) {
            // Đánh dấu active cho item và các parent
            let current = menuItem.closest('li');
            current.classList.add(ACTIVE_CLASS);
            
            // Mở các submenu parent nếu có
            while (current) {
                const parentUl = current.parentElement;
                if (parentUl && parentUl.classList.contains('mm-collapse')) {
                    parentUl.classList.add(SHOW_SUBMENU_CLASS);
                    parentUl.style.maxHeight = parentUl.scrollHeight + 'px';
                    
                    // Đánh dấu parent li active
                    const parentLi = parentUl.closest('li');
                    if (parentLi) {
                        parentLi.classList.add(ACTIVE_CLASS);
                        current = parentLi;
                    } else {
                        break;
                    }
                } else {
                    break;
                }
            }
            
            // Cuộn sidebar để menu active hiển thị trong tầm nhìn
            setTimeout(() => {
                if (menuItem.scrollIntoView) {
                    menuItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 300);

            // Thêm active indicator cho menu được chọn
            const indicator = document.createElement('span');
            indicator.classList.add('menu-active-indicator');
            menuItem.appendChild(indicator);
        }
    }

    /**
     * Thiết lập submenus
     */
    function setupSubmenus() {
        if (!metisMenu) return;

        // Xử lý các dropdown menus
        const hasArrow = metisMenu.querySelectorAll('.has-arrow');
        hasArrow.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                const parent = this.closest('li');
                if (!parent) return;
                
                const submenu = parent.querySelector('ul');
                if (!submenu) return;
                
                // Toggle active class
                const isActive = parent.classList.contains(ACTIVE_CLASS);
                
                // Đóng các submenu khác nếu không phải là submenu con
                if (!isActive) {
                    const siblings = Array.from(parent.parentElement.children).filter(
                        el => el !== parent && el.classList.contains(ACTIVE_CLASS)
                    );
                    
                    siblings.forEach(sibling => {
                        sibling.classList.remove(ACTIVE_CLASS);
                        const siblingSubmenu = sibling.querySelector('ul');
                        if (siblingSubmenu) {
                            siblingSubmenu.classList.remove(SHOW_SUBMENU_CLASS);
                            siblingSubmenu.style.maxHeight = null;
                        }
                    });
                }
                
                // Toggle submenu hiện tại
                parent.classList.toggle(ACTIVE_CLASS);
                submenu.classList.toggle(SHOW_SUBMENU_CLASS);
                
                // Đặt max-height cho animation
                if (submenu.classList.contains(SHOW_SUBMENU_CLASS)) {
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                } else {
                    submenu.style.maxHeight = null;
                }
            });
        });
        
        // Xử lý các menu items không có submenu
        metisMenu.querySelectorAll('li a:not(.has-arrow)').forEach(link => {
            link.addEventListener('click', function(e) {
                // Nếu ở chế độ mobile, đóng sidebar sau khi chọn menu
                if (isMobile() && isSidebarOpen) {
                    setTimeout(() => {
                        closeSidebar();
                    }, 100);
                }
                
                // Xác định active menu mới
                const menuItem = this.closest('li');
                if (menuItem) {
                    updateActiveMenu(menuItem, this);
                }
            });
        });
    }
    
    /**
     * Cập nhật active menu
     * @param {HTMLElement} menuItem - Menu item được chọn
     * @param {HTMLElement} menuLink - Link được click
     */
    function updateActiveMenu(menuItem, menuLink) {
        if (!menuItem || !menuLink) return;
        
        // Lấy menu ID
        const menuId = menuLink.getAttribute('data-menu-id');
        if (!menuId) return;
        
        // Xóa tất cả indicator hiện tại
        document.querySelectorAll('.menu-active-indicator').forEach(indicator => {
            indicator.remove();
        });
        
        // Thêm active indicator cho menu được chọn
        const indicator = document.createElement('span');
        indicator.classList.add('menu-active-indicator');
        menuLink.appendChild(indicator);
        
        // Thông báo cho header về menu active mới
        const event = new CustomEvent('sidebar:activeMenu', {
            detail: { menuId: menuId }
        });
        document.dispatchEvent(event);
        
        // Lưu trạng thái
        localStorage.setItem('activeMenuId', menuId);
    }
    
    /**
     * Thiết lập tìm kiếm trong sidebar
     */
    function setupSearch() {
        if (!sidebarSearch) return;
        
        sidebarSearch.addEventListener('input', debounce(function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            // Nếu không có từ khóa tìm kiếm, hiển thị tất cả
            if (!searchTerm) {
                menuItems.forEach(item => {
                    item.style.display = '';
                });
                return;
            }
            
            // Tìm kiếm các menu items
            menuItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                const isMatch = text.includes(searchTerm);
                item.style.display = isMatch ? '' : 'none';
                
                // Nếu là phần tử có submenu và khớp với tìm kiếm, hiển thị submenu
                if (isMatch && item.querySelector('ul')) {
                    const submenu = item.querySelector('ul');
                    if (submenu) {
                        item.classList.add(ACTIVE_CLASS);
                        submenu.classList.add(SHOW_SUBMENU_CLASS);
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    }
                }
            });
        }, 300));
        
        // Xử lý xóa tìm kiếm
        if (sidebarSearchClear) {
            sidebarSearchClear.addEventListener('click', function() {
                if (sidebarSearch) {
                    sidebarSearch.value = '';
                    // Trigger sự kiện input để reset hiển thị menu
                    sidebarSearch.dispatchEvent(new Event('input'));
                    sidebarSearch.focus();
                }
            });
        }
    }
    
    /**
     * Toggle chế độ mini sidebar
     * @param {boolean} [forceState] - Force state: true để bật mini sidebar, false để tắt
     * @param {boolean} [saveState=true] - Có lưu trạng thái hay không
     */
    function toggleMiniSidebar(forceState, saveState = true) {
        if (isMobile() || !wrapper) return;
        
        const newState = forceState !== undefined ? forceState : !isMiniSidebar;
        
        if (newState === isMiniSidebar) return;
        
        isMiniSidebar = newState;
        isResizing = true;
        
        if (isMiniSidebar) {
            wrapper.classList.add(SIDEBAR_MINI_CLASS);
            if (toggleIcon) {
                toggleIcon.querySelector('i').style.transform = 'rotate(180deg)';
            }
        } else {
            wrapper.classList.remove(SIDEBAR_MINI_CLASS);
            if (toggleIcon) {
                toggleIcon.querySelector('i').style.transform = 'rotate(0deg)';
            }
        }
        
        // Lưu trạng thái
        if (saveState) {
            localStorage.setItem('miniSidebar', isMiniSidebar ? 'true' : 'false');
        }
        
        // Sau khi animation hoàn tất
        setTimeout(() => {
            isResizing = false;
            
            // Điều chỉnh max-height cho các submenu đang mở
            document.querySelectorAll('.' + SHOW_SUBMENU_CLASS).forEach(submenu => {
                if (submenu.style.maxHeight) {
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                }
            });
        }, 300);
    }
    
    /**
     * Toggle sidebar (mở/đóng)
     */
    function toggleSidebar() {
        if (isSidebarOpen) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }
    
    /**
     * Mở sidebar
     * @param {boolean} [notifyHeader=true] - Có thông báo cho header không
     */
    function openSidebar(notifyHeader = true) {
        if (!sidebar) return;
        
        isSidebarOpen = true;
        
        // Xử lý hiệu ứng
        sidebar.style.transform = 'translateX(0)';
        body.classList.add(SIDEBAR_TOGGLED_CLASS);
        
        // Hiển thị overlay nếu ở chế độ mobile
        if (isMobile() && overlay) {
            overlay.style.display = 'block';
            setTimeout(() => {
                overlay.classList.add('show');
            }, 10);
        }
        
        // Thêm ứng dụng trải nghiệm người dùng - thêm animation cho sidebar
        sidebar.classList.add(ANIMATE_CLASS);
        setTimeout(() => {
            sidebar.classList.remove(ANIMATE_CLASS);
        }, 300);
        
        // Thông báo cho header về trạng thái sidebar
        if (notifyHeader) {
            const event = new CustomEvent('sidebar:toggled', {
                detail: { isOpen: true }
            });
            document.dispatchEvent(event);
        }
    }
    
    /**
     * Đóng sidebar
     * @param {boolean} [notifyHeader=true] - Có thông báo cho header không
     */
    function closeSidebar(notifyHeader = true) {
        if (!sidebar) return;
        
        isSidebarOpen = false;
        
        // Xử lý hiệu ứng
        sidebar.style.transform = 'translateX(-100%)';
        body.classList.remove(SIDEBAR_TOGGLED_CLASS);
        
        // Ẩn overlay
        if (overlay) {
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 300);
        }
        
        // Thông báo cho header về trạng thái sidebar
        if (notifyHeader) {
            const event = new CustomEvent('sidebar:toggled', {
                detail: { isOpen: false }
            });
            document.dispatchEvent(event);
        }
    }
    
    /**
     * Xử lý sự kiện resize cửa sổ
     */
    function handleResize() {
        const currentWidth = window.innerWidth;
        
        // Kiểm tra nếu chuyển từ desktop -> mobile hoặc ngược lại
        const wasDesktop = lastWindowWidth >= MOBILE_BREAKPOINT;
        const isDesktopNow = currentWidth >= MOBILE_BREAKPOINT;
        
        if (wasDesktop !== isDesktopNow) {
            // Chuyển từ desktop -> mobile
            if (!isDesktopNow) {
                // Chuyển sang mobile
                if (isMiniSidebar) {
                    // Tạm thời bỏ mini sidebar trên mobile
                    if (wrapper) {
                        wrapper.classList.remove(SIDEBAR_MINI_CLASS);
                    }
                }
                
                // Đóng sidebar trên mobile
                closeSidebar(false);
            } 
            // Chuyển từ mobile -> desktop
            else {
                // Khôi phục trạng thái mini sidebar nếu cần
                if (isMiniSidebar) {
                    toggleMiniSidebar(true, false);
                } else {
                    openSidebar(false);
                }
            }
        }
        
        // Cập nhật lại submenu height
        if (!isResizing) {
            document.querySelectorAll('.' + SHOW_SUBMENU_CLASS).forEach(submenu => {
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
            });
        }
        
        lastWindowWidth = currentWidth;
    }
    
    /**
     * Khôi phục trạng thái sidebar từ localStorage
     */
    function restoreSidebarState() {
        // Khôi phục trạng thái mini sidebar
        const savedMiniState = localStorage.getItem('miniSidebar');
        isMiniSidebar = savedMiniState === 'true';
        
        // Khôi phục active menu
        const savedMenuId = localStorage.getItem('activeMenuId');
        if (savedMenuId) {
            // Sẽ được xử lý trong highlightActiveMenu
            body.setAttribute('data-active-menu', savedMenuId);
        }
    }
    
    /**
     * Đánh dấu menu đang active dựa vào URL hiện tại
     */
    function highlightActiveMenu() {
        if (!metisMenu) return;
        
        const currentUrl = window.location.pathname;
        const savedMenuId = body.getAttribute('data-active-menu');
        let found = false;
        
        // Nếu có active menu id được lưu, thử khôi phục
        if (savedMenuId) {
            const menuLink = metisMenu.querySelector(`a[data-menu-id="${savedMenuId}"]`);
            if (menuLink) {
                const menuItem = menuLink.closest('li');
                if (menuItem) {
                    syncMenuFromHeader(savedMenuId);
                    found = true;
                }
            }
        }
        
        // Nếu không tìm thấy qua savedMenuId, thử dựa vào URL
        if (!found) {
            const menuLinks = metisMenu.querySelectorAll('a[href]');
            
            for (let i = 0; i < menuLinks.length; i++) {
                const link = menuLinks[i];
                const href = link.getAttribute('href');
                
                if (!href || href === '#') continue;
                
                try {
                    const linkPath = new URL(href, window.location.origin).pathname;
                    
                    // Kiểm tra nếu URL hiện tại khớp với href của link
                    if (currentUrl === linkPath || 
                        (linkPath !== '/' && currentUrl.startsWith(linkPath))) {
                        
                        // Đã tìm thấy menu khớp
                        const menuItem = link.closest('li');
                        if (menuItem) {
                            updateActiveMenu(menuItem, link);
                            found = true;
                            break;
                        }
                    }
                } catch (e) {
                    console.warn('Lỗi khi phân tích URL menu:', e);
                }
            }
        }
        
        // Nếu sau tất cả vẫn không tìm thấy, đánh dấu menu đầu tiên
        if (!found && metisMenu.querySelector('li a[data-menu-id]')) {
            const firstMenuItem = metisMenu.querySelector('li a[data-menu-id]');
            const menuId = firstMenuItem.getAttribute('data-menu-id');
            body.setAttribute('data-active-menu', menuId);
        }
    }
    
    /**
     * Debounce function để hạn chế tần suất gọi các hàm xử lý sự kiện
     * @param {Function} func - Hàm cần debounce
     * @param {number} wait - Thời gian chờ (ms)
     * @returns {Function} - Hàm đã được debounce
     */
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this;
            const args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                func.apply(context, args);
            }, wait);
        };
    }
    
    // Đợi DOM load xong rồi khởi tạo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
    } else {
        initSidebar();
    }
})();
