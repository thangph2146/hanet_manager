/**
 * Header JavaScript - Xử lý giao diện và chức năng của header
 * Tối ưu cho thiết bị di động và desktop
 * @version 3.0
 */

// Sử dụng IIFE để cách ly phạm vi biến và tránh ô nhiễm phạm vi toàn cục
(function() {
    'use strict';

    // Các hằng số
    const MOBILE_BREAKPOINT = 1025;
    const STICKY_CLASS = 'scrolled';
    const MOBILE_SEARCH_ACTIVE = 'mobile-search-active';
    const DROPDOWN_SHOW = 'show';
    const MENU_ACTIVE_CLASS = 'mm-active';
    const SIDEBAR_TOGGLED_CLASS = 'sidebar-toggled';
    const SIDEBAR_MINI_CLASS = 'sidebar-mini';

    // Cache các phần tử DOM để tối ưu hiệu suất
    const header = document.querySelector('.top-header');
    const body = document.body;
    const wrapper = document.querySelector('.wrapper');
    const searchToggleBtn = document.getElementById('toggle-search');
    const mobileSearchContainer = document.querySelector('.mobile-search-container');
    const searchInput = document.querySelector('.searchbar .form-control');
    const mobileSearchInput = document.querySelector('.mobile-search-container .form-control');
    const searchForm = document.getElementById('searchForm');
    const mobileSearchForm = document.getElementById('mobileSearchForm');
    const searchCloseIcon = document.querySelector('.search-close-icon');
    const mobileSearchCloseIcon = document.querySelector('.mobile-search-close');
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    const fixedDropdownContainer = document.getElementById('fixed-dropdowns-container');
    const overlay = document.getElementById('overlay');
    const sidebarWrapper = document.querySelector('.sidebar-wrapper');

    // Biến để theo dõi trạng thái
    let lastScrollTop = 0;
    let isScrolling = false;
    let isSearchActive = false;
    let isMobileMenuOpen = false;
    let activeDropdowns = [];
    let currentActivePage = '';
    
    /**
     * Kiểm tra nếu đang ở chế độ mobile
     * @returns {boolean} true nếu màn hình nhỏ hơn giá trị breakpoint
     */
    const isMobile = () => window.innerWidth < MOBILE_BREAKPOINT;
    
    /**
     * Xử lý khi scroll trang
     */
    function handleScroll() {
        if (!header) return;
    
        const currentScroll = window.pageYOffset || document.documentElement.scrollTop;
        
        // Thêm hiệu ứng sticky khi scroll xuống
        if (currentScroll > 10) {
            header.classList.add(STICKY_CLASS);
        } else {
            header.classList.remove(STICKY_CLASS);
        }
        
        // Xử lý ẩn/hiện header khi scroll (chỉ áp dụng trên mobile)
        if (isMobile() && currentScroll > 100) {
            // Scroll down - ẩn header
            if (currentScroll > lastScrollTop + 50 && !isScrolling) {
                header.style.transform = 'translateY(-100%)';
                isScrolling = true;
                
                // Đóng mobile search nếu đang mở
                if (isSearchActive) {
                    toggleMobileSearch(false);
                }
            } 
            // Scroll up - hiện header
            else if (currentScroll < lastScrollTop - 10 && isScrolling) {
                header.style.transform = 'translateY(0)';
                isScrolling = false;
            }
        } else {
            header.style.transform = 'translateY(0)';
            isScrolling = false;
        }
        
        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    }
    
    /**
     * Thêm các event listeners cho header
     */
    function setupEventListeners() {
        // Scroll handler với debounce nhẹ để cải thiện hiệu suất
        window.addEventListener('scroll', debounce(handleScroll, 10));
        
        // Resize handler
        window.addEventListener('resize', debounce(handleResize, 150));
        
        // Mobile search toggle
        if (searchToggleBtn) {
            searchToggleBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMobileSearch();
            });
        }
        
        // Search form handlers
        setupSearchBars();
        
        // Setup dropdown templates
        setupDropdownTemplates();
        
        // Setup dropdown events
        setupDropdowns();
        
        // Setup click outside handlers
        document.addEventListener('click', handleDocumentClick);
        
        // Mobile menu toggle - sử dụng cả nút trong header và sidebar
        const mobileMenuBtn = document.querySelector('.mobile-menu-button');
        const sidebarMenuButton = document.querySelector('.menu-button');
        
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMobileMenu();
            });
        }
        
        if (sidebarMenuButton) {
            sidebarMenuButton.addEventListener('click', function(e) {
                e.preventDefault();
                toggleMobileMenu();
            });
        }
        
        // Xử lý nút đóng mobile search
        if (mobileSearchCloseIcon) {
            mobileSearchCloseIcon.addEventListener('click', function() {
                toggleMobileSearch(false);
            });
        }
        
        // Xử lý nhấn ESC key để đóng dropdowns và search
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
                if (isSearchActive) {
                    toggleMobileSearch(false);
                }
                // Đồng bộ với sidebar - đóng sidebar mobile nếu đang mở
                if (isMobileMenuOpen) {
                    toggleMobileMenu(false);
                }
            }
        });

        // Custom event để lắng nghe sidebar toggle
        document.addEventListener('sidebar:toggled', function(e) {
            if (e.detail && e.detail.isOpen !== undefined) {
                isMobileMenuOpen = e.detail.isOpen;
                updateOverlayState();
                
                // Cập nhật class cho body
                if (isMobileMenuOpen) {
                    body.classList.add(SIDEBAR_TOGGLED_CLASS);
                } else {
                    body.classList.remove(SIDEBAR_TOGGLED_CLASS);
                }
            }
        });
        
        // Lắng nghe sự kiện active menu từ sidebar
        document.addEventListener('sidebar:activeMenu', function(e) {
            if (e.detail && e.detail.menuId) {
                updateActiveMenuState(e.detail.menuId);
            }
        });
        
        // Khởi tạo trạng thái active menu dựa vào URL hiện tại
        detectCurrentActivePage();
    }
    
    /**
     * Phát hiện trang active hiện tại dựa trên URL
     */
    function detectCurrentActivePage() {
        const currentPath = window.location.pathname;
        
        // Tìm trang active dựa trên path
        const menuItems = document.querySelectorAll('#sidemenu a[href]');
        let activeMenuId = '';
        
        menuItems.forEach(item => {
            if (!item.getAttribute('href')) return;
            try {
                const itemPath = new URL(item.getAttribute('href'), window.location.origin).pathname;
                
                // Kiểm tra nếu URL hiện tại khớp với href của link
                if (currentPath === itemPath || 
                    (itemPath !== '/' && currentPath.startsWith(itemPath))) {
                    
                    activeMenuId = item.getAttribute('data-menu-id') || '';
                    // Lưu lại menuId active
                    currentActivePage = activeMenuId;
                }
            } catch (e) {
                console.warn('Lỗi khi phân tích URL menu:', e);
            }
        });
        
        // Cập nhật trạng thái active
        if (activeMenuId) {
            updateActiveMenuState(activeMenuId);
        }
    }
    
    /**
     * Cập nhật trạng thái active menu
     * @param {string} menuId - ID của menu active
     */
    function updateActiveMenuState(menuId) {
        // Thêm data attribute vào body để CSS có thể chọn menu active
        body.setAttribute('data-active-menu', menuId);
        
        // Lưu trạng thái active
        currentActivePage = menuId;
        
        // Gửi sự kiện cho sidebar nếu cần
        const event = new CustomEvent('header:activeMenu', {
            detail: { menuId: menuId }
        });
        document.dispatchEvent(event);
    }
    
    /**
     * Thiết lập các thanh tìm kiếm
     */
    function setupSearchBars() {
        // Desktop search form
        if (searchForm) {
            searchForm.addEventListener('submit', handleSearchSubmit);
            
            // Xử lý nút xóa
            if (searchCloseIcon) {
                searchCloseIcon.addEventListener('click', function() {
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.focus();
                        this.style.display = 'none';
                    }
                });
            }
            
            // Live search với debounce 
            if (searchInput) {
                searchInput.addEventListener('input', debounce(function() {
                    if (searchCloseIcon) {
                        searchCloseIcon.style.display = this.value.length > 0 ? 'block' : 'none';
                    }
                    
                    // Thêm logic live search ở đây nếu cần
                    handleLiveSearch(this.value);
                }, 300));
            }
        }
        
        // Mobile search form
        if (mobileSearchForm) {
            mobileSearchForm.addEventListener('submit', handleSearchSubmit);
            
            // Đồng bộ dữ liệu giữa hai thanh tìm kiếm
            if (mobileSearchInput && searchInput) {
                mobileSearchInput.addEventListener('input', debounce(function() {
                    searchInput.value = this.value;
                    
                    // Đồng bộ hiển thị nút xóa
                    if (searchCloseIcon) {
                        searchCloseIcon.style.display = this.value.length > 0 ? 'block' : 'none';
                    }
                    
                    // Thêm logic live search ở đây
                    handleLiveSearch(this.value);
                }, 300));
            }
        }
    }

    /**
     * Xử lý live search
     * @param {string} query - Từ khóa tìm kiếm
     */
    function handleLiveSearch(query) {
        const searchResultsDropdown = document.querySelector('.search-results-dropdown');
        const searchResults = document.querySelector('.search-results');
        const searchLoading = document.querySelector('.search-loading');
        const searchEmpty = document.querySelector('.search-empty');
        
        if (!searchResultsDropdown || !searchResults) return;
        
        if (!query || query.length < 2) {
            searchResultsDropdown.style.display = 'none';
            return;
        }
        
        // Hiển thị dropdown và loading
        searchResultsDropdown.style.display = 'block';
        if (searchLoading) searchLoading.style.display = 'block';
        if (searchEmpty) searchEmpty.style.display = 'none';
        searchResults.innerHTML = '';
        
        // Giả lập API call để tìm kiếm (thay thế bằng API thực tế)
        setTimeout(() => {
            if (searchLoading) searchLoading.style.display = 'none';
            
            // Logic tìm kiếm thực tế sẽ được thêm vào đây
            // Đây chỉ là mẫu
            if (query.toLowerCase().includes('event') || query.toLowerCase().includes('sự kiện')) {
                searchResults.innerHTML = `
                    <a href="#" class="search-results-item d-flex align-items-center p-2">
                        <div class="me-2">
                            <i class="bi bi-calendar-event fs-5 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">Sự kiện mẫu</h6>
                            <p class="mb-0 small text-muted">Ngày 20/10/2023</p>
                        </div>
                        <div>
                            <span class="badge bg-primary">Sắp diễn ra</span>
                        </div>
                    </a>
                `;
            } else {
                if (searchEmpty) searchEmpty.style.display = 'block';
            }
        }, 500);
    }
    
    /**
     * Xử lý sự kiện click trên document để đóng các dropdown và search
     * @param {Event} e - Sự kiện click
     */
    function handleDocumentClick(e) {
        // Xử lý đóng dropdown khi click bên ngoài
        if (!e.target.closest('.dropdown') && !e.target.closest('.fixed-dropdown')) {
            closeAllDropdowns();
        }
        
        // Đóng mobile search khi click bên ngoài
        if (isSearchActive && 
            !e.target.closest('.mobile-search-container') && 
            !e.target.closest('#toggle-search')) {
            toggleMobileSearch(false);
        }
    }
    
    /**
     * Thiết lập các dropdown templates
     */
    function setupDropdownTemplates() {
        if (!fixedDropdownContainer) return;
        
        // Di chuyển tất cả templates vào container
        const templates = document.querySelectorAll('template');
        templates.forEach(template => {
            const id = template.id;
            if (id && id.includes('dropdown')) {
                fixedDropdownContainer.appendChild(template.content.cloneNode(true));
            }
        });
    }
    
    /**
     * Thiết lập xử lý dropdowns
     */
    function setupDropdowns() {
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const toggleId = this.id;
                if (!toggleId) return;
                
                // Tìm dropdown tương ứng theo ID của toggle
                const dropdown = document.querySelector(`.fixed-dropdown[data-dropdown-for="${toggleId}"]`);
                
                if (dropdown) {
                    // Đóng tất cả các dropdown khác
                    document.querySelectorAll('.fixed-dropdown.show').forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            otherDropdown.classList.remove(DROPDOWN_SHOW);
                            
                            // Cập nhật active state
                            const forId = otherDropdown.getAttribute('data-dropdown-for');
                            if (forId) {
                                const otherToggle = document.getElementById(forId);
                                if (otherToggle) {
                                    otherToggle.classList.remove('active');
                                }
                            }
                        }
                    });
                    
                    // Toggle dropdown hiện tại
                    const isShowing = dropdown.classList.toggle(DROPDOWN_SHOW);
                    
                    // Cập nhật trạng thái toggle
                    this.classList.toggle('active', isShowing);
                    
                    // Cập nhật danh sách active dropdowns
                    if (isShowing) {
                        activeDropdowns.push(toggleId);
                        positionDropdown(this, dropdown);
                    } else {
                        activeDropdowns = activeDropdowns.filter(id => id !== toggleId);
                    }
                }
            });
        });
        
        // Xử lý nút đánh dấu đã đọc thông báo
        const markAllReadBtn = document.querySelector('.mark-all-read');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', function() {
                // Xử lý đánh dấu đã đọc (thêm logic thực tế ở đây)
                document.querySelectorAll('.notification-item.unread').forEach(item => {
                    item.classList.remove('unread');
                });
                
                // Cập nhật badge số lượng
                const notifyBadge = document.getElementById('notifyBadge');
                const notifyCount = document.getElementById('notifyCount');
                if (notifyBadge) notifyBadge.style.display = 'none';
                if (notifyCount) notifyCount.textContent = '0';
            });
        }
    }
    
    /**
     * Điều chỉnh vị trí dropdown dựa trên toggle button
     * @param {HTMLElement} toggle - Toggle button
     * @param {HTMLElement} dropdown - Dropdown element
     */
    function positionDropdown(toggle, dropdown) {
        if (!toggle || !dropdown) return;
        
        const toggleRect = toggle.getBoundingClientRect();
        const isNotificationDropdown = dropdown.classList.contains('dropdown-notify');
        
        // Đặt vị trí dựa trên mobile hay desktop
        if (isMobile()) {
            // Trên mobile - căn giữa dropdown
            dropdown.style.left = '50%';
            dropdown.style.right = 'auto';
            dropdown.style.transform = 'translateX(-50%)';
            dropdown.style.top = (toggleRect.bottom + window.scrollY + 5) + 'px';
            
            // Đảm bảo kích thước phù hợp
            dropdown.style.maxWidth = isNotificationDropdown ? '400px' : '350px';
            dropdown.style.width = '90%';
        } else {
            // Trên desktop - căn theo toggle
            const rightAlign = toggle.closest('.dropdown').classList.contains('dropdown-large');
            
            if (rightAlign) {
                // Căn phải
                dropdown.style.right = (window.innerWidth - toggleRect.right) + 'px';
                dropdown.style.left = 'auto';
                dropdown.style.transform = 'none';
            } else {
                // Căn trái
                dropdown.style.left = toggleRect.left + 'px';
                dropdown.style.right = 'auto';
                dropdown.style.transform = 'none';
            }
            
            dropdown.style.top = (toggleRect.bottom + window.scrollY + 5) + 'px';
            dropdown.style.maxWidth = '';
            dropdown.style.width = '';
            
            // Kiểm tra và điều chỉnh nếu dropdown vượt quá viewport
            const dropdownRect = dropdown.getBoundingClientRect();
            if (dropdownRect.right > window.innerWidth) {
                dropdown.style.right = '10px';
                dropdown.style.left = 'auto';
            }
            if (dropdownRect.left < 0) {
                dropdown.style.left = '10px';
                dropdown.style.right = 'auto';
            }
        }
    }
    
    /**
     * Đóng tất cả dropdowns
     */
    function closeAllDropdowns() {
        document.querySelectorAll('.fixed-dropdown.' + DROPDOWN_SHOW).forEach(menu => {
            menu.classList.remove(DROPDOWN_SHOW);
            
            // Cập nhật active state của toggle
            const forId = menu.getAttribute('data-dropdown-for');
            if (forId) {
                const toggle = document.getElementById(forId);
                if (toggle) {
                    toggle.classList.remove('active');
                }
            }
        });
        
        // Reset danh sách active dropdowns
        activeDropdowns = [];
    }
    
    /**
     * Toggle tìm kiếm trên mobile
     * @param {boolean|undefined} forceState - Trạng thái bắt buộc (true để mở, false để đóng)
     */
    function toggleMobileSearch(forceState) {
        if (!mobileSearchContainer) return;
        
        isSearchActive = forceState !== undefined ? forceState : !isSearchActive;
        
        if (isSearchActive) {
            // Khi mở search, đóng mobile menu nếu đang mở
            if (isMobileMenuOpen) {
                toggleMobileMenu(false);
            }
            
            body.classList.add(MOBILE_SEARCH_ACTIVE);
            mobileSearchContainer.style.display = 'flex';
            header.classList.add('has-search');
            
            setTimeout(() => {
                mobileSearchContainer.classList.add('show');
            }, 10);
            
            // Focus vào input search
            if (mobileSearchInput) {
                setTimeout(() => {
                    mobileSearchInput.focus();
                }, 100);
            }
            
            // Điều chỉnh padding cho page-content-wrapper
            const contentWrapper = document.querySelector('.page-content-wrapper');
            if (contentWrapper) {
                contentWrapper.classList.add('has-search');
            }
        } else {
            body.classList.remove(MOBILE_SEARCH_ACTIVE);
            mobileSearchContainer.classList.remove('show');
            header.classList.remove('has-search');
            
            // Đợi kết thúc animation rồi mới ẩn
            setTimeout(() => {
                mobileSearchContainer.style.display = 'none';
                
                // Khôi phục padding cho page-content-wrapper
                const contentWrapper = document.querySelector('.page-content-wrapper');
                if (contentWrapper) {
                    contentWrapper.classList.remove('has-search');
                }
            }, 300);
        }
    }
    
    /**
     * Xử lý sự kiện submit form tìm kiếm
     * @param {Event} e - Sự kiện submit
     */
    function handleSearchSubmit(e) {
        const input = this.querySelector('.form-control');
        
        if (!input || !input.value.trim()) {
            e.preventDefault();
            return;
        }
        
        // Lấy URL từ data attribute
        const searchUrl = input.getAttribute('data-search-url');
        if (searchUrl) {
            e.preventDefault();
            window.location.href = `${searchUrl}?q=${encodeURIComponent(input.value.trim())}`;
        }
        
        // Đóng mobile search sau khi submit
        if (isMobile() && isSearchActive) {
            setTimeout(() => {
                toggleMobileSearch(false);
            }, 200);
        }
    }
    
    /**
     * Xử lý sự kiện thay đổi kích thước màn hình
     */
    function handleResize() {
        // Xử lý responsive trên mobile/desktop
        if (!isMobile() && isSearchActive) {
            // Chuyển từ mobile sang desktop, ẩn mobile search
            toggleMobileSearch(false);
        }
        
        // Điều chỉnh vị trí các dropdown nếu có mở
        activeDropdowns.forEach(toggleId => {
            const toggle = document.getElementById(toggleId);
            const dropdown = document.querySelector(`.fixed-dropdown[data-dropdown-for="${toggleId}"]`);
            if (toggle && dropdown && dropdown.classList.contains(DROPDOWN_SHOW)) {
                positionDropdown(toggle, dropdown);
            }
        });
        
        // Điều chỉnh vị trí header
        handleScroll();

        // Kiểm tra và điều chỉnh menu toggle khi thay đổi kích thước màn hình
        if (!isMobile() && isMobileMenuOpen) {
            // Nếu chuyển từ mobile sang desktop với menu đang mở, đóng menu
            toggleMobileMenu(false);
        }
    }
    
    /**
     * Toggle mobile menu
     * @param {boolean|undefined} forceState - Trạng thái bắt buộc
     */
    function toggleMobileMenu(forceState) {
        // Xử lý nút menu cho cả desktop và mobile
        if (!isMobile() && forceState === undefined) {
            // Sử dụng cùng nút menu cho cả desktop và mobile với hành vi khác nhau
            toggleMiniSidebar();
            // Thay đổi góc xoay của icon
            const menuButton = document.querySelector('.menu-button i');
            if (menuButton) {
                wrapper.classList.contains(SIDEBAR_MINI_CLASS) 
                    ? menuButton.style.transform = 'rotate(180deg)' 
                    : menuButton.style.transform = 'rotate(0deg)';
            }
            return;
        }
        
        const newState = forceState !== undefined ? forceState : !isMobileMenuOpen;
        
        // Nếu trạng thái không thay đổi, không làm gì
        if (isMobileMenuOpen === newState) return;
        
        isMobileMenuOpen = newState;
        
        // Đóng mobile search nếu đang mở
        if (isSearchActive) {
            toggleMobileSearch(false);
        }
        
        // Toggle class trên body
        if (isMobileMenuOpen) {
            body.classList.add(SIDEBAR_TOGGLED_CLASS);
        } else {
            body.classList.remove(SIDEBAR_TOGGLED_CLASS);
        }
        
        // Dispatch custom event để sidebar có thể lắng nghe
        const event = new CustomEvent('header:menuToggled', {
            detail: { isOpen: isMobileMenuOpen }
        });
        document.dispatchEvent(event);
        
        // Cập nhật trạng thái overlay
        updateOverlayState();
    }
    
    /**
     * Toggle sidebar mini mode (chỉ áp dụng trên desktop)
     */
    function toggleMiniSidebar() {
        if (isMobile()) return;
        
        if (wrapper) {
            const isMini = wrapper.classList.toggle('sidebar-mini');
            
            // Lưu trạng thái
            localStorage.setItem('miniSidebar', isMini ? 'true' : 'false');
        }
    }
    
    /**
     * Cập nhật trạng thái overlay dựa trên trạng thái menu
     */
    function updateOverlayState() {
        if (!overlay) return;
        
        if (isMobileMenuOpen) {
            overlay.style.display = 'block';
            setTimeout(() => {
                overlay.classList.add(DROPDOWN_SHOW);
            }, 10);
            
            // Thêm event listener cho overlay để đóng menu khi click
            overlay.addEventListener('click', handleOverlayClick);
        } else {
            overlay.classList.remove(DROPDOWN_SHOW);
            setTimeout(() => {
                overlay.style.display = 'none';
                overlay.removeEventListener('click', handleOverlayClick);
            }, 300);
        }
    }
    
    /**
     * Xử lý click trên overlay
     */
    function handleOverlayClick() {
        if (isMobileMenuOpen) {
            toggleMobileMenu(false);
        }
    }
    
    /**
     * Đồng bộ trạng thái active menu giữa sidebar và header
     * @param {string} menuPath - Đường dẫn menu được kích hoạt
     */
    function syncActiveMenu(menuPath) {
        // Thêm logic đồng bộ active menu giữa sidebar và header
        // (Được gọi từ sidebar thông qua custom event)
        
        // Highlight menu item tương ứng trong header nếu cần
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
    
    /**
     * Khởi tạo header
     */
    function initHeader() {
        setupEventListeners();
        handleScroll(); // Gọi ngay để thiết lập trạng thái ban đầu
    }
    
    // Tối ưu hiệu suất load
    function domReady(callback) {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', callback);
        } else {
            callback();
        }
    }
    
    // Khởi chạy khi DOM đã sẵn sàng
    domReady(initHeader);
})();
