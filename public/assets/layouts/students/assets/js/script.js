/**
 * Students Module JavaScript
 * Mobile-first responsive layout controllers
 */

document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar on mobile
    const mobileToggle = document.querySelector('.mobile-sidebar-toggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function() {
            sidebar.classList.toggle('mobile-open');
        });
    }
    
    // Toggle sidebar collapse on desktop
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            // Save state to localStorage
            localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
        });
    }
    
    // Load sidebar state from localStorage
    if (localStorage.getItem('sidebar-collapsed') === 'true') {
        sidebar.classList.add('collapsed');
    }
    
    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const viewport = window.innerWidth;
        if (viewport < 768 && sidebar.classList.contains('mobile-open')) {
            if (!sidebar.contains(event.target) && !mobileToggle.contains(event.target)) {
                sidebar.classList.remove('mobile-open');
            }
        }
    });
    
    // Tooltips for collapsed sidebar
    const sidebarLinks = document.querySelectorAll('.sidebar-nav-link');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            if (sidebar.classList.contains('collapsed')) {
                const text = this.querySelector('.sidebar-nav-text').textContent;
                const tooltip = document.createElement('div');
                tooltip.className = 'sidebar-tooltip';
                tooltip.textContent = text;
                document.body.appendChild(tooltip);
                
                const rect = this.getBoundingClientRect();
                tooltip.style.top = rect.top + 'px';
                tooltip.style.left = (rect.right + 10) + 'px';
            }
        });
        
        link.addEventListener('mouseleave', function() {
            const tooltip = document.querySelector('.sidebar-tooltip');
            if (tooltip) {
                tooltip.remove();
            }
        });
    });
    
    // Event registration functionality
    const registerBtns = document.querySelectorAll('.event-register-btn');
    
    if (registerBtns.length > 0) {
        registerBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const eventId = this.dataset.eventId;
                
                fetch('/students/events/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        event_id: eventId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update UI
                        this.innerHTML = '<i class="fas fa-check-circle"></i> Đã đăng ký';
                        this.classList.remove('btn-primary');
                        this.classList.add('btn-success');
                        this.disabled = true;
                        
                        // Show success notification
                        showNotification('Đăng ký thành công', 'success');
                    } else {
                        showNotification(data.message || 'Đăng ký thất bại', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Đã xảy ra lỗi khi xử lý yêu cầu', 'error');
                });
            });
        });
    }
    
    // Cancel registration functionality
    const cancelBtns = document.querySelectorAll('.event-cancel-btn');
    
    if (cancelBtns.length > 0) {
        cancelBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (!confirm('Bạn có chắc chắn muốn hủy đăng ký sự kiện này?')) {
                    return;
                }
                
                const eventId = this.dataset.eventId;
                
                fetch('/students/events/cancel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        event_id: eventId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Find and update parent element
                        const eventCard = this.closest('.event-item');
                        if (eventCard) {
                            eventCard.remove();
                        }
                        
                        // Show success notification
                        showNotification('Hủy đăng ký thành công', 'success');
                    } else {
                        showNotification(data.message || 'Hủy đăng ký thất bại', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Đã xảy ra lỗi khi xử lý yêu cầu', 'error');
                });
            });
        });
    }
    
    // Check-in and check-out functionality
    const attendanceBtn = document.querySelector('.event-attendance-btn');
    
    if (attendanceBtn) {
        attendanceBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const eventId = this.dataset.eventId;
            const attendanceType = this.dataset.type; // 'checkin' or 'checkout'
            
            fetch('/students/events/attendance', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    event_id: eventId,
                    type: attendanceType
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    if (attendanceType === 'checkin') {
                        this.innerHTML = '<i class="fas fa-sign-out-alt"></i> Check-out';
                        this.dataset.type = 'checkout';
                        this.classList.remove('btn-primary');
                        this.classList.add('btn-warning');
                    } else {
                        this.innerHTML = '<i class="fas fa-check-circle"></i> Đã hoàn thành';
                        this.disabled = true;
                        this.classList.remove('btn-warning');
                        this.classList.add('btn-success');
                    }
                    
                    // Show success notification
                    showNotification(data.message || 'Cập nhật trạng thái thành công', 'success');
                } else {
                    showNotification(data.message || 'Cập nhật trạng thái thất bại', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Đã xảy ra lỗi khi xử lý yêu cầu', 'error');
            });
        });
    }
    
    // Helper function for notifications
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `toast toast-${type}`;
        notification.innerHTML = `
            <div class="toast-header">
                <strong class="me-auto">${type === 'success' ? 'Thành công' : type === 'error' ? 'Lỗi' : 'Thông báo'}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        `;
        
        const container = document.getElementById('toast-container');
        if (!container) {
            const newContainer = document.createElement('div');
            newContainer.id = 'toast-container';
            newContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(newContainer);
            newContainer.appendChild(notification);
        } else {
            container.appendChild(notification);
        }
        
        // Initialize Bootstrap toast
        new bootstrap.Toast(notification).show();
        
        // Remove after shown
        notification.addEventListener('hidden.bs.toast', function() {
            notification.remove();
        });
    }
}); 