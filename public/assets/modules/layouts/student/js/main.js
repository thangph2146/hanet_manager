/**
 * Main JavaScript cho dashboard student layout
 * Quản lý các thành phần giao diện và tối ưu hiệu suất
 * @version 2.0
 */

// Đối tượng quản lý giao diện chính
const LayoutManager = {
  // Cấu hình
  config: {
    components: {
      sidebar: SidebarManager
    },
    preloadedComponents: [],
    themeKey: 'dark-theme'
  },
  
  // Khởi tạo
  init() {
    console.log('Khởi tạo LayoutManager...');
    this.initializeComponents();
    this.initThemeMode();
    this.initScrollToTop();
    this.initSearchBar();
    this.initDropdowns();
    this.initAlerts();
    this.initTooltips();
    this.initLazyLoad();
    this.detectBrowser();
    
    // Đánh dấu đã khởi tạo xong
    document.documentElement.setAttribute('data-layout-initialized', 'true');
    console.log('LayoutManager đã khởi tạo xong');
  },
  
  // Khởi tạo các component
  initializeComponents() {
    // Kiểm tra và đánh dấu các component đã preload
    this.config.preloadedComponents = Array.from(document.querySelectorAll('link[rel="preload"]'))
      .map(link => link.getAttribute('href'))
      .filter(Boolean);
    
    console.log('Components đã preload:', this.config.preloadedComponents.length);
    
    // Khởi tạo sidebar nếu chưa được khởi tạo
    if (this.config.components.sidebar && 
        document.querySelector('.sidebar-wrapper') && 
        !document.querySelector('.sidebar-wrapper').hasAttribute('data-sidebar-initialized')) {
      this.config.components.sidebar.init();
    }
  },
  
  /**
   * Khởi tạo chế độ theme (Dark/Light mode)
   */
  initThemeMode() {
    const themeToggler = document.querySelector('.theme-toggler');
    const { themeKey } = this.config;
    
    if (themeToggler) {
      themeToggler.addEventListener('click', () => {
        document.documentElement.classList.toggle('dark-theme');
        // Lưu giá trị theme vào localStorage
        const isDarkMode = document.documentElement.classList.contains('dark-theme');
        localStorage.setItem(themeKey, isDarkMode);
        
        // Phát sự kiện theme đã thay đổi
        window.dispatchEvent(new CustomEvent('theme-changed', { 
          detail: { isDarkMode } 
        }));
      });
      
      // Kiểm tra và áp dụng theme từ localStorage khi tải trang
      const savedDarkMode = localStorage.getItem(themeKey) === 'true';
      if (savedDarkMode) {
        document.documentElement.classList.add('dark-theme');
      }
    }
  },
  
  /**
   * Khởi tạo nút scroll-to-top
   */
  initScrollToTop() {
    const backToTop = document.querySelector('.back-to-top');
    
    if (backToTop) {
      // Hiển thị nút khi cuộn xuống
      window.addEventListener('scroll', () => {
        if (window.pageYOffset > 300) {
          backToTop.classList.add('show');
        } else {
          backToTop.classList.remove('show');
        }
      }, { passive: true });
      
      // Scroll lên đầu trang khi nhấn nút
      backToTop.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      });
    }
  },
  
  /**
   * Khởi tạo thanh tìm kiếm
   */
  initSearchBar() {
    const searchBar = document.querySelector('.searchbar');
    const searchInput = document.querySelector('.searchbar .form-control');
    const searchClose = document.querySelector('.search-close-icon');
    
    if (searchBar && searchInput && searchClose) {
      // Xóa nội dung khi nhấn nút close
      searchClose.addEventListener('click', () => {
        searchInput.value = '';
        searchInput.focus();
      });
      
      // Xử lý search
      searchInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
          if (searchInput.value.trim().length > 0) {
            window.location.href = 'students/events?search=' + encodeURIComponent(searchInput.value.trim());
          }
        }
      });
    }
  },
  
  /**
   * Khởi tạo các dropdown
   */
  initDropdowns() {
    // Đóng dropdown khi click ngoài
    document.addEventListener('click', (e) => {
      const dropdowns = document.querySelectorAll('.dropdown-menu.show');
      
      dropdowns.forEach(dropdown => {
        if (!dropdown.contains(e.target) && !dropdown.previousElementSibling.contains(e.target)) {
          dropdown.classList.remove('show');
        }
      });
    });
  },
  
  /**
   * Khởi tạo các thông báo
   */
  initAlerts() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    
    alerts.forEach(alert => {
      // Tự động ẩn sau 5 giây
      setTimeout(() => {
        if (document.body.contains(alert)) {
          alert.classList.add('fade-out');
          
          setTimeout(() => {
            if (document.body.contains(alert)) {
              alert.remove();
            }
          }, 500);
        }
      }, 5000);
      
      // Xử lý nút đóng
      const closeButton = alert.querySelector('.btn-close');
      if (closeButton) {
        closeButton.addEventListener('click', () => {
          alert.classList.add('fade-out');
          
          setTimeout(() => {
            if (document.body.contains(alert)) {
              alert.remove();
            }
          }, 500);
        });
      }
    });
  },
  
  /**
   * Khởi tạo tooltips
   */
  initTooltips() {
    // Sử dụng Bootstrap Tooltips nếu có
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
      [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
    }
  },
  
  /**
   * Khởi tạo lazy load cho hình ảnh
   */
  initLazyLoad() {
    // Lazy load images
    const lazyImages = document.querySelectorAll('img[loading="lazy"]');
    
    // Sử dụng Intersection Observer nếu được hỗ trợ
    if ('IntersectionObserver' in window) {
      const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            const lazyImage = entry.target;
            const src = lazyImage.dataset.src;
            
            if (src) {
              lazyImage.src = src;
              lazyImage.removeAttribute('data-src');
            }
            
            lazyImage.classList.add('loaded');
            imageObserver.unobserve(lazyImage);
          }
        });
      });
      
      lazyImages.forEach(image => {
        if (image.dataset.src) {
          imageObserver.observe(image);
        }
      });
    } else {
      // Fallback cho trình duyệt không hỗ trợ Intersection Observer
      lazyImages.forEach(image => {
        if (image.dataset.src) {
          image.src = image.dataset.src;
          image.removeAttribute('data-src');
        }
      });
    }
  },
  
  /**
   * Phát hiện trình duyệt và thiết bị
   */
  detectBrowser() {
    const userAgent = navigator.userAgent.toLowerCase();
    const html = document.documentElement;
    
    // Phát hiện trình duyệt
    if (userAgent.indexOf('edge') > -1) {
      html.classList.add('edge-browser');
    } else if (userAgent.indexOf('chrome') > -1 && userAgent.indexOf('edge') === -1) {
      html.classList.add('chrome-browser');
    } else if (userAgent.indexOf('firefox') > -1) {
      html.classList.add('firefox-browser');
    } else if (userAgent.indexOf('safari') > -1 && userAgent.indexOf('chrome') === -1) {
      html.classList.add('safari-browser');
    }
    
    // Phát hiện thiết bị
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    if (isMobile) {
      html.classList.add('mobile-device');
    } else {
      html.classList.add('desktop-device');
    }
  }
};

// Khởi tạo khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
  console.log('Document đã tải xong, khởi tạo LayoutManager...');
  LayoutManager.init();
}); 