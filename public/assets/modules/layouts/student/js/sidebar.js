/**
 * Quản lý sidebar component
 * Sử dụng Ajax để cập nhật trạng thái real-time
 * @version 2.0
 */

// Đối tượng quản lý sidebar
const SidebarManager = {
  // Cấu hình
  config: {
    selectors: {
      wrapper: '.wrapper',
      sidebar: '.sidebar-wrapper',
      toggleIcon: '.toggle-icon',
      mobileMenuButton: '.mobile-menu-button',
      overlay: '.overlay',
      pageContent: '.page-content'
    },
    classes: {
      mini: 'sidebar-mini',
      toggled: 'toggled',
      hover: 'sidebar-hover',
      animate: 'animate-sidebar'
    },
    mediaQuery: window.matchMedia('(max-width: 1024px)'),
    csrfToken: document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || '',
    animationDuration: 300 // ms
  },
  
  // Khởi tạo
  init() {
    console.log("Khởi tạo SidebarManager...");
    this.cacheDom();
    this.fixInitialVisibility();
    this.bindEvents();
    this.restoreState();
    
    // Ghi log thông tin sidebar
    setTimeout(() => {
      if (this.dom.sidebar) {
        console.log("Sidebar width:", getComputedStyle(this.dom.sidebar).width);
        console.log("Sidebar left:", getComputedStyle(this.dom.sidebar).left);
      }
    }, 500);
  },
  
  // Cache DOM elements
  cacheDom() {
    const { selectors } = this.config;
    this.dom = {
      wrapper: document.querySelector(selectors.wrapper),
      sidebar: document.querySelector(selectors.sidebar),
      toggleIcon: document.querySelector(selectors.toggleIcon),
      toggleIconArrow: document.querySelector(`${selectors.toggleIcon} i`),
      mobileMenuButton: document.querySelector(selectors.mobileMenuButton),
      overlay: document.querySelector(selectors.overlay),
      pageContent: document.querySelector(selectors.pageContent),
      menuItems: document.querySelectorAll('.metismenu li a')
    };
    
    console.log("DOM elements cached:", {
      wrapper: !!this.dom.wrapper,
      sidebar: !!this.dom.sidebar,
      toggleIcon: !!this.dom.toggleIcon,
      mobileMenuButton: !!this.dom.mobileMenuButton
    });
  },
  
  // Sửa hiển thị ban đầu
  fixInitialVisibility() {
    if (!this.dom.sidebar || !this.dom.wrapper) return;
    
    const { mediaQuery } = this.config;
    
    if (mediaQuery.matches) {
      // Mobile - ẩn sidebar ban đầu
      this.dom.sidebar.style.left = '-250px';
    } else {
      // Desktop - hiển thị sidebar
      this.dom.sidebar.style.left = '0';
    }
  },
  
  // Gắn sự kiện
  bindEvents() {
    const { mediaQuery } = this.config;
    
    // Toggle icon click
    if (this.dom.toggleIcon && this.dom.wrapper) {
      this.dom.toggleIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        this.toggleSidebar();
      });
    }
    
    // Mobile menu button click
    if (this.dom.mobileMenuButton && this.dom.wrapper) {
      this.dom.mobileMenuButton.addEventListener('click', (e) => {
        e.stopPropagation();
        this.toggleMobileSidebar();
      });
    }
    
    // Overlay click
    if (this.dom.overlay) {
      this.dom.overlay.addEventListener('click', () => {
        this.closeSidebar();
      });
    }
    
    // Media query change
    mediaQuery.addEventListener('change', (e) => {
      this.handleScreenSizeChange(e.matches);
    });
    
    // Hover events (desktop only)
    if (window.innerWidth > 1024 && this.dom.sidebar) {
      this.dom.sidebar.addEventListener('mouseenter', () => {
        this.handleSidebarHover(true);
      });
      
      this.dom.sidebar.addEventListener('mouseleave', () => {
        this.handleSidebarHover(false);
      });
    }
    
    // Active menu item
    this.setActiveMenuItem();
  },
  
  // Toggle sidebar on desktop
  toggleSidebar() {
    const { classes, mediaQuery } = this.config;
    console.log("Toggle sidebar");
    
    // Bắt đầu animation
    this.startAnimation();
    
    if (mediaQuery.matches) {
      // Mobile - toggle full sidebar
      this.dom.wrapper.classList.toggle(classes.toggled);
      this.updateState(classes.toggled, this.dom.wrapper.classList.contains(classes.toggled));
      
      // Update sidebar position
      this.updateMobileSidebarPosition();
    } else {
      // Desktop - toggle mini mode
      this.dom.wrapper.classList.toggle(classes.mini);
      this.updateState(classes.mini, this.dom.wrapper.classList.contains(classes.mini));
      
      // Update toggle icon
      this.updateToggleIcon();
    }
    
    // Kết thúc animation sau khi hoàn thành
    setTimeout(() => this.endAnimation(), this.config.animationDuration);
  },
  
  // Toggle sidebar on mobile
  toggleMobileSidebar() {
    const { classes } = this.config;
    console.log("Toggle mobile sidebar");
    
    // Bắt đầu animation
    this.startAnimation();
    
    // Toggle class
    this.dom.wrapper.classList.toggle(classes.toggled);
    this.updateState(classes.toggled, this.dom.wrapper.classList.contains(classes.toggled));
    
    // Update sidebar position
    this.updateMobileSidebarPosition();
    
    // Kết thúc animation sau khi hoàn thành
    setTimeout(() => this.endAnimation(), this.config.animationDuration);
  },
  
  // Close sidebar (for overlay click)
  closeSidebar() {
    const { classes, mediaQuery } = this.config;
    console.log("Close sidebar");
    
    // Bắt đầu animation
    this.startAnimation();
    
    // Remove toggled class
    this.dom.wrapper.classList.remove(classes.toggled);
    this.updateState(classes.toggled, false);
    
    // Update sidebar position on mobile
    if (mediaQuery.matches && this.dom.sidebar) {
      this.dom.sidebar.style.left = '-250px';
    }
    
    // Kết thúc animation sau khi hoàn thành
    setTimeout(() => this.endAnimation(), this.config.animationDuration);
  },
  
  // Handle screen size change
  handleScreenSizeChange(isMobile) {
    const { classes } = this.config;
    console.log("Media query changed:", isMobile ? "mobile" : "desktop");
    
    // Bắt đầu animation
    this.startAnimation();
    
    if (isMobile) {
      // Mobile - reset classes and hide sidebar
      this.dom.wrapper.classList.remove(classes.mini);
      this.dom.wrapper.classList.remove(classes.toggled);
      this.updateState(classes.mini, false);
      this.updateState(classes.toggled, false);
      
      if (this.dom.sidebar) {
        this.dom.sidebar.style.left = '-250px';
      }
    } else {
      // Desktop - restore mini state
      this.dom.wrapper.classList.remove(classes.toggled);
      this.updateState(classes.toggled, false);
      
      const isMini = localStorage.getItem(classes.mini) === 'true';
      this.dom.wrapper.classList.toggle(classes.mini, isMini);
      this.updateState(classes.mini, isMini);
      
      // Update toggle icon
      this.updateToggleIcon();
      
      // Ensure sidebar is visible on desktop
      if (this.dom.sidebar) {
        this.dom.sidebar.style.left = '0';
        setTimeout(() => { this.dom.sidebar.style.left = ''; }, 300);
      }
    }
    
    // Kết thúc animation sau khi hoàn thành
    setTimeout(() => this.endAnimation(), this.config.animationDuration);
  },
  
  // Handle sidebar hover
  handleSidebarHover(isHovering) {
    const { classes } = this.config;
    
    if (isHovering) {
      console.log("Sidebar hovered");
      if (this.dom.wrapper.classList.contains(classes.mini)) {
        this.dom.wrapper.classList.add(classes.hover);
        this.updateState(classes.hover, true);
      }
    } else {
      console.log("Sidebar unhovered");
      this.dom.wrapper.classList.remove(classes.hover);
      this.updateState(classes.hover, false);
    }
  },
  
  // Update mobile sidebar position
  updateMobileSidebarPosition() {
    const { classes } = this.config;
    
    if (this.dom.sidebar) {
      if (this.dom.wrapper.classList.contains(classes.toggled)) {
        this.dom.sidebar.style.left = '0';
      } else {
        this.dom.sidebar.style.left = '-250px';
      }
    }
  },
  
  // Update toggle icon direction
  updateToggleIcon() {
    const { classes } = this.config;
    
    if (this.dom.toggleIconArrow && this.dom.wrapper) {
      if (this.dom.wrapper.classList.contains(classes.mini)) {
        this.dom.toggleIconArrow.className = 'bx bx-arrow-to-right';
      } else {
        this.dom.toggleIconArrow.className = 'bx bx-arrow-to-left';
      }
    }
  },
  
  // Set active menu item based on current URL
  setActiveMenuItem() {
    const currentUrl = window.location.href;
    
    this.dom.menuItems.forEach(item => {
      const href = item.getAttribute('href');
      if (href && currentUrl.includes(href)) {
        // Remove active class from all items
        document.querySelectorAll('.metismenu li').forEach(li => {
          li.classList.remove('mm-active');
        });
        
        // Add active class to current item
        const parentLi = item.closest('li');
        if (parentLi) {
          parentLi.classList.add('mm-active');
        }
      }
    });
  },
  
  // Start sidebar animation
  startAnimation() {
    const { classes } = this.config;
    
    if (this.dom.sidebar) {
      this.dom.sidebar.classList.add(classes.animate);
    }
    
    if (this.dom.pageContent) {
      this.dom.pageContent.classList.add(classes.animate);
    }
  },
  
  // End sidebar animation
  endAnimation() {
    const { classes } = this.config;
    
    if (this.dom.sidebar) {
      this.dom.sidebar.classList.remove(classes.animate);
    }
    
    if (this.dom.pageContent) {
      this.dom.pageContent.classList.remove(classes.animate);
    }
  },
  
  // Update state in localStorage and on server via Ajax
  updateState(key, value) {
    // Update localStorage
    localStorage.setItem(key, value);
    
    // Update on server via Ajax
    this.sendStateToServer(key, value);
  },
  
  // Send state to server via Ajax
  sendStateToServer(key, value) {
    const { csrfToken } = this.config;
    
    fetch(`${window.location.origin}/sidebar/update-state`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
      },
      body: JSON.stringify({
        action: 'update_sidebar_state',
        state_key: key,
        state_value: value
      })
    })
    .then(response => {
      if (!response.ok) {
        console.log('Sidebar state update failed');
        return response.json().then(data => Promise.reject(data));
      }
      return response.json();
    })
    .then(data => {
      console.log('Sidebar state updated on server:', data);
    })
    .catch(error => {
      console.error('Error updating sidebar state:', error);
    });
  },
  
  // Restore sidebar state
  restoreState() {
    const { classes, mediaQuery } = this.config;
    
    if (!this.dom.wrapper) return;
    
    // Remove all classes that might conflict
    this.dom.wrapper.classList.remove(classes.toggled, classes.mini, classes.hover);
    
    // First try to get state from server
    this.getStateFromServer()
      .then(serverStates => {
        // Fallback to localStorage
        const localIsMini = localStorage.getItem(classes.mini) === 'true';
        const localIsToggled = localStorage.getItem(classes.toggled) === 'true';
        
        // Prefer server state, fallback to localStorage
        const isMini = (serverStates[classes.mini] !== undefined) ? serverStates[classes.mini] : localIsMini;
        const isToggled = (serverStates[classes.toggled] !== undefined) ? serverStates[classes.toggled] : localIsToggled;
        
        console.log("Restoring state:", {
          isMini,
          isToggled,
          fromServer: Object.keys(serverStates).length > 0
        });
        
        // Apply state based on screen size
        if (!mediaQuery.matches) {
          // Desktop
          if (isMini) {
            this.dom.wrapper.classList.add(classes.mini);
          }
          
          // Fix sidebar visibility
          this.fixDesktopSidebarVisibility();
          
          // Update toggle icon
          this.updateToggleIcon();
        } else {
          // Mobile
          if (isToggled) {
            this.dom.wrapper.classList.add(classes.toggled);
            this.updateMobileSidebarPosition();
            
            // Show overlay
            if (this.dom.overlay) {
              this.dom.overlay.style.display = 'block';
            }
          } else {
            // Hide sidebar
            if (this.dom.sidebar) {
              this.dom.sidebar.style.left = '-250px';
            }
          }
        }
      })
      .catch(error => {
        console.error("Error restoring state:", error);
        this.fallbackRestoreState();
      });
  },
  
  // Fallback restore state from localStorage
  fallbackRestoreState() {
    const { classes, mediaQuery } = this.config;
    
    if (!mediaQuery.matches) {
      // Desktop
      const isMini = localStorage.getItem(classes.mini) === 'true';
      if (isMini) {
        this.dom.wrapper.classList.add(classes.mini);
      }
      
      this.fixDesktopSidebarVisibility();
      this.updateToggleIcon();
    } else {
      // Mobile
      const isToggled = localStorage.getItem(classes.toggled) === 'true';
      if (isToggled) {
        this.dom.wrapper.classList.add(classes.toggled);
        this.updateMobileSidebarPosition();
        
        if (this.dom.overlay) {
          this.dom.overlay.style.display = 'block';
        }
      } else if (this.dom.sidebar) {
        this.dom.sidebar.style.left = '-250px';
      }
    }
  },
  
  // Fix desktop sidebar visibility
  fixDesktopSidebarVisibility() {
    if (!this.dom.sidebar) return;
    
    const sidebarLeft = getComputedStyle(this.dom.sidebar).left;
    
    if (sidebarLeft === '-250px' || sidebarLeft === '-260px' || parseInt(sidebarLeft) < 0) {
      this.dom.sidebar.style.left = '0';
      
      // Set width based on mini state
      if (this.dom.wrapper.classList.contains(this.config.classes.mini)) {
        this.dom.sidebar.style.width = '70px';
      } else {
        this.dom.sidebar.style.width = '250px';
      }
      
      // Remove inline style after fixing
      setTimeout(() => {
        if (this.dom.sidebar && this.dom.sidebar.style.left === '0px') {
          this.dom.sidebar.style.left = '';
        }
      }, 300);
    }
  },
  
  // Get state from server
  getStateFromServer() {
    const { csrfToken } = this.config;
    
    return fetch(`${window.location.origin}/sidebar/get-state`, {
      method: 'GET',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': csrfToken
      }
    })
    .then(response => {
      if (!response.ok) {
        console.log('Get sidebar state failed');
        return response.json().then(data => Promise.reject(data));
      }
      return response.json();
    })
    .then(data => {
      console.log('Got sidebar state from server:', data);
      return data.states || {};
    })
    .catch(error => {
      console.error('Error getting sidebar state:', error);
      return {}; // Return empty object if error
    });
  }
};

// Khởi tạo sidebar khi trang đã load
document.addEventListener('DOMContentLoaded', function() {
  console.log("Document loaded, initializing sidebar...");
  setTimeout(() => SidebarManager.init(), 50);
});
