/**
 * Student App Events JavaScript
 * Xử lý tương tác cho trang quản lý sự kiện
 */
document.addEventListener('DOMContentLoaded', function() {
    // Filter events
    const eventTabs = document.querySelectorAll('.event-tab');
    const eventItems = document.querySelectorAll('.event-item');
    const searchInput = document.querySelector('.event-search-input');
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox input');
    const filterResetBtn = document.querySelector('.filter-reset');
    
    // Tab filtering
    if (eventTabs.length > 0) {
        eventTabs.forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                const target = this.getAttribute('data-target');
                
                // Update active tab
                eventTabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Filter events
                filterEvents();
            });
        });
    }
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterEvents();
        });
    }
    
    // Checkbox filters
    if (filterCheckboxes.length > 0) {
        filterCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                filterEvents();
            });
        });
    }
    
    // Reset filters
    if (filterResetBtn) {
        filterResetBtn.addEventListener('click', function() {
            // Reset search
            if (searchInput) {
                searchInput.value = '';
            }
            
            // Reset checkboxes
            if (filterCheckboxes.length > 0) {
                filterCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
            
            // Reset tabs
            if (eventTabs.length > 0) {
                eventTabs.forEach(tab => {
                    tab.classList.remove('active');
                });
                eventTabs[0].classList.add('active');
            }
            
            // Apply reset filters
            filterEvents();
        });
    }
    
    // Main filter function
    function filterEvents() {
        // Get active tab
        const activeTab = document.querySelector('.event-tab.active');
        const activeFilter = activeTab ? activeTab.getAttribute('data-target') : 'all';
        
        // Get search term
        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        
        // Get selected filters
        const selectedFilters = {
            status: [],
            category: []
        };
        
        filterCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const filterType = checkbox.getAttribute('data-filter-type');
                const filterValue = checkbox.getAttribute('data-filter-value');
                
                if (filterType && filterValue) {
                    selectedFilters[filterType].push(filterValue);
                }
            }
        });
        
        // Apply filters to each event item
        eventItems.forEach(item => {
            const itemTitle = item.querySelector('.event-title').textContent.toLowerCase();
            const itemDescription = item.querySelector('.event-description').textContent.toLowerCase();
            const itemStatus = item.getAttribute('data-status');
            const itemCategory = item.getAttribute('data-category');
            
            // Check if matches search term
            const matchesSearch = searchTerm === '' || 
                itemTitle.includes(searchTerm) || 
                itemDescription.includes(searchTerm);
            
            // Check if matches tab filter
            const matchesTab = activeFilter === 'all' || 
                (activeFilter === 'registered' && item.getAttribute('data-registered') === 'yes') ||
                (activeFilter === itemStatus);
            
            // Check if matches selected filters
            const matchesStatusFilter = selectedFilters.status.length === 0 || 
                selectedFilters.status.includes(itemStatus);
                
            const matchesCategoryFilter = selectedFilters.category.length === 0 || 
                selectedFilters.category.includes(itemCategory);
            
            // Show/hide based on all filters
            if (matchesSearch && matchesTab && matchesStatusFilter && matchesCategoryFilter) {
                item.classList.remove('d-none');
            } else {
                item.classList.add('d-none');
            }
        });
        
        // Show "no events" message if all are hidden
        const visibleEvents = document.querySelectorAll('.event-item:not(.d-none)');
        const noEventsMessage = document.querySelector('.no-events-message');
        
        if (visibleEvents.length === 0) {
            if (!noEventsMessage) {
                const message = document.createElement('div');
                message.className = 'no-events-message alert alert-info mt-3';
                message.textContent = 'Không có sự kiện nào phù hợp với tiêu chí tìm kiếm.';
                
                const eventsContainer = document.querySelector('.events-list-container');
                if (eventsContainer) {
                    eventsContainer.appendChild(message);
                }
            }
        } else if (noEventsMessage) {
            noEventsMessage.remove();
        }
    }
    
    // Register events
    const registerButtons = document.querySelectorAll('.register-event-btn');
    if (registerButtons.length > 0) {
        registerButtons.forEach(button => {
            button.addEventListener('click', function() {
                const eventId = this.getAttribute('data-event-id');
                registerForEvent(eventId, this);
            });
        });
    }
    
    function registerForEvent(eventId, button) {
        // Disable button to prevent multiple clicks
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        
        // AJAX request to register
        fetch(`/students/events/register/${eventId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ event_id: eventId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update UI
                button.className = 'btn btn-success';
                button.innerHTML = '<i class="fas fa-check-circle"></i> Đã đăng ký';
                button.disabled = true;
                
                // Show success message
                showNotification('success', 'Đăng ký thành công', data.message);
            } else {
                // Enable button again
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-calendar-check"></i> Đăng ký';
                
                // Show error message
                showNotification('error', 'Đăng ký thất bại', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Enable button again
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-calendar-check"></i> Đăng ký';
            
            // Show error message
            showNotification('error', 'Đăng ký thất bại', 'Đã xảy ra lỗi khi đăng ký sự kiện. Vui lòng thử lại sau.');
        });
    }
    
    // Cancel registration
    const cancelButtons = document.querySelectorAll('.cancel-registration-btn');
    if (cancelButtons.length > 0) {
        cancelButtons.forEach(button => {
            button.addEventListener('click', function() {
                const eventId = this.getAttribute('data-event-id');
                
                if (confirm('Bạn có chắc chắn muốn hủy đăng ký tham gia sự kiện này?')) {
                    cancelRegistration(eventId, this);
                }
            });
        });
    }
    
    function cancelRegistration(eventId, button) {
        // Disable button to prevent multiple clicks
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        
        // AJAX request to cancel registration
        fetch(`/students/events/cancel/${eventId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the event item from the list
                const eventItem = button.closest('.my-event-item');
                if (eventItem) {
                    eventItem.remove();
                    
                    // Check if there are any events left
                    const remainingEvents = document.querySelectorAll('.my-event-item');
                    if (remainingEvents.length === 0) {
                        const eventsContainer = document.querySelector('.my-events-list');
                        if (eventsContainer) {
                            eventsContainer.innerHTML = '<div class="alert alert-info">Bạn chưa đăng ký tham gia sự kiện nào.</div>';
                        }
                    }
                }
                
                // Show success message
                showNotification('success', 'Hủy đăng ký thành công', data.message);
            } else {
                // Enable button again
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-times-circle"></i> Hủy đăng ký';
                
                // Show error message
                showNotification('error', 'Hủy đăng ký thất bại', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Enable button again
            button.disabled = false;
            button.innerHTML = '<i class="fas fa-times-circle"></i> Hủy đăng ký';
            
            // Show error message
            showNotification('error', 'Hủy đăng ký thất bại', 'Đã xảy ra lỗi khi hủy đăng ký sự kiện. Vui lòng thử lại sau.');
        });
    }
    
    // Show notification
    function showNotification(type, title, message) {
        // Check if notification container exists
        let notificationContainer = document.querySelector('.notification-container');
        
        if (!notificationContainer) {
            // Create notification container
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-container';
            document.body.appendChild(notificationContainer);
        }
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        // Set icon based on type
        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';
        
        // Set content
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-${icon}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Add to container
        notificationContainer.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Auto close after 5 seconds
        setTimeout(() => {
            closeNotification(notification);
        }, 5000);
        
        // Close button
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeNotification(notification);
            });
        }
    }
    
    function closeNotification(notification) {
        notification.classList.remove('show');
        
        // Remove after animation
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
    
    // Initialize events page
    function initEventsPage() {
        // Show default tab
        if (eventTabs.length > 0) {
            eventTabs[0].classList.add('active');
        }
        
        // Apply initial filtering
        filterEvents();
    }
    
    // Check if we're on events page
    if (document.querySelector('.events-page')) {
        initEventsPage();
    }
}); 