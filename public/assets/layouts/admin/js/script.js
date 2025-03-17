// Toggle sidebar visibility on mobile
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.querySelector('.mobile-menu-toggle');
    const sidebar = document.querySelector('.sidebar-container');
    
    if (toggleBtn) {
        toggleBtn.addEventListener('click', function() {
            sidebar.style.display = sidebar.style.display === 'block' ? 'none' : 'block';
        });
    }
    
    // Collapsible menu
    const menuToggles = document.querySelectorAll('.nav-link[data-bs-toggle="collapse"]');
    
    menuToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const icon = this.querySelector('.fas.fa-angle-down');
            if (icon) {
                icon.classList.toggle('fa-rotate-180');
            }
        });
    });
}); 