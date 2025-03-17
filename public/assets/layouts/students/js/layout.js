/**
 * Student App Layout JavaScript
 * Xử lý tương tác cho layout Student App
 */
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Sidebar
    const sidebarToggleBtn = document.querySelector('.header-toggle-btn');
    const sidebar = document.querySelector('.sidebar');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const body = document.body;
    
    if (sidebarToggleBtn && sidebar) {
        sidebarToggleBtn.addEventListener('click', function() {
            toggleSidebar();
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            toggleSidebar();
        });
    }
    
    function toggleSidebar() {
        sidebar.classList.toggle('active');
        sidebarOverlay.classList.toggle('active');
        
        // For desktop view
        if (window.innerWidth >= 768) {
            body.classList.toggle('sidebar-closed');
        }
    }
    
    // Close sidebar on mobile when clicking a menu item
    const menuLinks = document.querySelectorAll('.menu-link');
    if (menuLinks.length > 0 && sidebar) {
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 768 && sidebar.classList.contains('active')) {
                    toggleSidebar();
                }
            });
        });
    }
    
    // Active menu item based on current page
    function setActiveMenuItem() {
        const currentPath = window.location.pathname;
        const menuLinks = document.querySelectorAll('.menu-link');
        
        menuLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href && currentPath.includes(href) && href !== '#' && href !== '/') {
                link.classList.add('active');
            }
        });
    }
    
    setActiveMenuItem();
    
    // Responsive handling
    function handleResize() {
        if (window.innerWidth >= 768) {
            // Desktop/tablet view
            sidebarOverlay.classList.remove('active');
        } else {
            // Mobile view
            body.classList.remove('sidebar-closed');
            if (!sidebar.classList.contains('active')) {
                sidebarOverlay.classList.remove('active');
            }
        }
    }
    
    window.addEventListener('resize', handleResize);
    handleResize(); // Initial check
}); 