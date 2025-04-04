/**
 * Script cho trang Danh sách sự kiện - Đại Học Ngân Hàng TP.HCM
 */
document.addEventListener('DOMContentLoaded', function() {
    // Khởi tạo AOS Animation Library
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 50,
        delay: 50,
        mirror: false
    });
    
    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    const backToTop = document.querySelector('.back-to-top');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
            backToTop.classList.add('active');
        } else {
            navbar.classList.remove('scrolled');
            backToTop.classList.remove('active');
        }
    });
    
    // Back to top button với animation mượt mà
    if (backToTop) {
        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
    
    // Tìm kiếm sự kiện - cải thiện
    const searchInput = document.getElementById('eventSearchInput');
    const searchButton = document.getElementById('searchButton');
    const resetButton = document.getElementById('resetSearch');
    const eventCards = document.querySelectorAll('#eventContainer .col-md-4');
    const noEventsFound = document.getElementById('noEventsFound');
    
    // Thêm debounce function để tối ưu hóa việc tìm kiếm
    function debounce(func, wait = 300) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Hàm xử lý tìm kiếm nâng cao
    function handleSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        let hasResults = false;
        let visibleCount = 0;
        
        // Thêm loading state
        if (searchTerm.length > 0) {
            document.body.classList.add('searching');
        }
        
        // Áp dụng hiệu ứng cho từng card
        eventCards.forEach((card, index) => {
            const eventTitle = card.querySelector('.event-title').textContent.toLowerCase();
            const eventDescription = card.querySelector('.event-description').textContent.toLowerCase();
            const eventMeta = card.querySelector('.event-meta').textContent.toLowerCase();
            const eventType = card.dataset.type;
            
            // Tìm kiếm mở rộng
            if (searchTerm === '' || 
                eventTitle.includes(searchTerm) || 
                eventDescription.includes(searchTerm) || 
                eventMeta.includes(searchTerm)) {
                
                // Thêm hiệu ứng animation staggered delay
                setTimeout(() => {
                    card.style.display = '';
                    card.classList.add('fade-in');
                    card.style.animationDelay = `${index * 50}ms`;
                }, 50);
                
                hasResults = true;
                visibleCount++;
            } else {
                card.classList.remove('fade-in');
                card.classList.add('filter-transition', 'filter-hidden');
                setTimeout(() => {
                    card.style.display = 'none';
                    card.classList.remove('filter-transition', 'filter-hidden');
                }, 300);
            }
        });
        
        // Hiển thị thông báo "Không tìm thấy sự kiện" với animation
        if (hasResults) {
            if (!noEventsFound.classList.contains('d-none')) {
                noEventsFound.classList.add('filter-transition', 'filter-hidden');
                setTimeout(() => {
                    noEventsFound.classList.add('d-none');
                    noEventsFound.classList.remove('filter-transition', 'filter-hidden');
                }, 300);
            }
        } else {
            noEventsFound.classList.remove('d-none');
            setTimeout(() => {
                noEventsFound.classList.add('fade-in');
            }, 10);
        }
        
        // Hiển thị số kết quả tìm kiếm
        if (searchTerm.length > 0) {
            showNotification(`Tìm thấy ${visibleCount} sự kiện phù hợp`, visibleCount > 0 ? 'info' : 'error');
        }
        
        // Xóa loading state
        setTimeout(() => {
            document.body.classList.remove('searching');
        }, 300);
    }
    
    // Áp dụng debounce để tìm kiếm khi gõ
    const debouncedSearch = debounce(handleSearch, 300);
    
    // Đăng ký sự kiện tìm kiếm
    if (searchButton) {
        searchButton.addEventListener('click', handleSearch);
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', debouncedSearch);
        searchInput.addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });
        
        // Focus animation
        searchInput.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        searchInput.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    }
    
    // Đặt lại tìm kiếm với hiệu ứng
    if (resetButton) {
        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            
            // Thêm hiệu ứng animation khi reset
            eventCards.forEach((card, index) => {
                card.style.display = '';
                setTimeout(() => {
                    card.classList.add('fade-in');
                    card.style.animationDelay = `${index * 50}ms`;
                }, 50);
            });
            
            // Ẩn thông báo không tìm thấy
            noEventsFound.classList.add('filter-transition', 'filter-hidden');
            setTimeout(() => {
                noEventsFound.classList.add('d-none');
                noEventsFound.classList.remove('filter-transition', 'filter-hidden');
            }, 300);
            
            showNotification('Đã đặt lại bộ lọc', 'info');
        });
    }
    
    // Lọc theo loại sự kiện - nâng cao
    const filterButtons = document.querySelectorAll('.filter-btn');
    const allEventsButton = document.getElementById('btnAllEvents');
    
    // Hàm xử lý lọc sự kiện với hiệu ứng
    function filterEvents(eventType) {
        let hasResults = false;
        let visibleCount = 0;
        
        // Thêm loading state
        document.body.classList.add('filtering');
        
        // Áp dụng hiệu ứng cho từng card
        eventCards.forEach((card, index) => {
            if (eventType === 'all' || card.dataset.type === eventType) {
                // Thêm staggered animation
                setTimeout(() => {
                    card.style.display = '';
                    card.classList.add('fade-in');
                    card.style.animationDelay = `${index * 50}ms`;
                }, 50);
                
                hasResults = true;
                visibleCount++;
            } else {
                card.classList.remove('fade-in');
                card.classList.add('filter-transition', 'filter-hidden');
                setTimeout(() => {
                    card.style.display = 'none';
                    card.classList.remove('filter-transition', 'filter-hidden');
                }, 300);
            }
        });
        
        // Hiển thị thông báo "Không tìm thấy sự kiện" với animation
        if (hasResults) {
            if (!noEventsFound.classList.contains('d-none')) {
                noEventsFound.classList.add('filter-transition', 'filter-hidden');
                setTimeout(() => {
                    noEventsFound.classList.add('d-none');
                    noEventsFound.classList.remove('filter-transition', 'filter-hidden');
                }, 300);
            }
        } else {
            noEventsFound.classList.remove('d-none');
            setTimeout(() => {
                noEventsFound.classList.add('fade-in');
            }, 10);
        }
        
        // Hiển thị số kết quả lọc
        const filterName = eventType === 'all' ? 'tất cả' : 
                          eventType === 'hoi-thao' ? 'hội thảo' :
                          eventType === 'hoi-nghi' ? 'hội nghị' :
                          eventType === 'workshop' ? 'workshop' : eventType;
        
        showNotification(`Đang hiển thị ${visibleCount} sự kiện ${filterName}`, 'info');
        
        // Xóa loading state
        setTimeout(() => {
            document.body.classList.remove('filtering');
        }, 500);
    }
    
    // Đăng ký sự kiện cho các nút lọc
    filterButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Xóa lớp active từ tất cả các nút
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Thêm lớp active vào nút được nhấp
            this.classList.add('active');
            
            // Lọc sự kiện theo loại
            const filterType = this.dataset.filter;
            filterEvents(filterType);
        });
    });
    
    // Nút "Tất cả" với hiệu ứng pulse
    if (allEventsButton) {
        allEventsButton.addEventListener('click', function() {
            // Xóa lớp active từ tất cả các nút lọc
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Thêm hiệu ứng khi nhấp
            this.classList.add('active', 'pulse');
            setTimeout(() => {
                this.classList.remove('pulse');
            }, 700);
            
            // Hiển thị tất cả sự kiện
            filterEvents('all');
        });
    }
    
    // Hiệu ứng hover tiên tiến cho thẻ sự kiện
    const eventCardElements = document.querySelectorAll('.event-card');
    eventCardElements.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.querySelector('.event-image img').style.transform = 'scale(1.1)';
            this.querySelector('.event-image').style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
            
            // Hiệu ứng nâng card
            this.style.transform = 'translateY(-10px)';
            this.style.boxShadow = '0 15px 35px rgba(0,0,0,0.1)';
            
            // Hiệu ứng button
            const viewDetailsBtn = this.querySelector('.btn-view-details');
            if (viewDetailsBtn) {
                viewDetailsBtn.style.color = 'var(--primary-light)';
                viewDetailsBtn.querySelector('i').style.transform = 'translateX(5px)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            this.querySelector('.event-image img').style.transform = 'scale(1)';
            this.querySelector('.event-image').style.boxShadow = 'none';
            
            // Reset hiệu ứng nâng card
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 10px 30px rgba(0,0,0,0.05)';
            
            // Reset hiệu ứng button
            const viewDetailsBtn = this.querySelector('.btn-view-details');
            if (viewDetailsBtn) {
                viewDetailsBtn.style.color = '';
                viewDetailsBtn.querySelector('i').style.transform = '';
            }
        });
        
        // Thêm hiệu ứng focus outline cho accessibility
        card.addEventListener('focus', function() {
            this.style.outline = '2px solid var(--primary)';
        });
        
        card.addEventListener('blur', function() {
            this.style.outline = 'none';
        });
    });
    
    // Smooth scrolling for anchor links với easing
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                e.preventDefault();
                const offsetTop = targetElement.getBoundingClientRect().top + window.pageYOffset - 100;
                
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
                
                // Highlight target khi scroll đến
                setTimeout(() => {
                    targetElement.classList.add('highlight-target');
                    setTimeout(() => {
                        targetElement.classList.remove('highlight-target');
                    }, 1500);
                }, 700);
            }
        });
    });
    
    // Thông báo khi đăng ký sự kiện (demo) với hiệu ứng nâng cao
    const registerButtons = document.querySelectorAll('.event-actions .btn-primary');
    
    registerButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const eventCard = this.closest('.event-card');
            const eventTitle = eventCard.querySelector('.event-title').textContent.trim();
            
            // Thêm hiệu ứng khi đăng ký
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang đăng ký...';
            this.disabled = true;
            
            // Hiệu ứng nhấp nháy cho card
            eventCard.classList.add('registering');
            
            // Giả lập xử lý đăng ký (setTimeout)
            setTimeout(() => {
                // Hiển thị thông báo
                showNotification(`Đăng ký tham gia "${eventTitle}" thành công!`, 'success');
                
                // Khôi phục button
                this.innerHTML = 'Đã đăng ký';
                this.classList.remove('btn-primary');
                this.classList.add('btn-success');
                this.disabled = false;
                
                // Xóa hiệu ứng
                eventCard.classList.remove('registering');
                
                // Cập nhật số lượng đăng ký
                const statElement = eventCard.querySelector('.event-stats .stat:last-child');
                if (statElement) {
                    const currentText = statElement.textContent;
                    const numMatch = currentText.match(/(\d+)(?=\s+đã đăng ký)/);
                    if (numMatch && numMatch[1]) {
                        const currentNum = parseInt(numMatch[1]);
                        statElement.innerHTML = `<i class="far fa-user"></i> ${currentNum + 1} đã đăng ký`;
                    }
                }
            }, 1500);
        });
    });
    
    // Hàm hiển thị thông báo được cải thiện
    function showNotification(message, type = 'info') {
        // Kiểm tra xem đã có thông báo chưa
        const existingNotification = document.querySelector('.notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Chọn icon phù hợp với loại thông báo
        let icon = '';
        switch(type) {
            case 'success':
                icon = '<i class="fas fa-check-circle text-success me-2"></i>';
                break;
            case 'error':
                icon = '<i class="fas fa-times-circle text-danger me-2"></i>';
                break;
            case 'info':
            default:
                icon = '<i class="fas fa-info-circle text-primary me-2"></i>';
                break;
        }
        
        // Tạo phần tử thông báo
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        const content = document.createElement('div');
        content.className = 'notification-content';
        
        const messageElement = document.createElement('div');
        messageElement.className = 'd-flex align-items-center';
        messageElement.innerHTML = icon + message;
        
        const closeButton = document.createElement('button');
        closeButton.className = 'notification-close';
        closeButton.innerHTML = '&times;';
        closeButton.addEventListener('click', function() {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        });
        
        content.appendChild(messageElement);
        content.appendChild(closeButton);
        notification.appendChild(content);
        
        document.body.appendChild(notification);
        
        // Hiệu ứng hiển thị
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Tự động ẩn sau 5 giây
        const autoHideTimer = setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
        
        // Tạm dừng hẹn giờ khi hover
        notification.addEventListener('mouseenter', () => {
            clearTimeout(autoHideTimer);
        });
        
        // Tiếp tục hẹn giờ khi không hover
        notification.addEventListener('mouseleave', () => {
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        notification.remove();
                    }
                }, 300);
            }, 2000);
        });
    }
    
    // Lazy loading for images với hiệu ứng fade-in
    if ('loading' in HTMLImageElement.prototype) {
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        lazyImages.forEach(img => {
            img.classList.add('lazy-image');
            img.addEventListener('load', () => {
                img.classList.add('loaded');
            });
            img.src = img.dataset.src;
        });
    } else {
        // Fallback with IntersectionObserver
        const lazyImageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const lazyImage = entry.target;
                    lazyImage.classList.add('lazy-image');
                    lazyImage.src = lazyImage.dataset.src;
                    
                    lazyImage.addEventListener('load', () => {
                        lazyImage.classList.add('loaded');
                    });
                    
                    observer.unobserve(lazyImage);
                }
            });
        }, {
            rootMargin: '0px 0px 200px 0px' // Preload images 200px before they appear in viewport
        });
        
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            lazyImageObserver.observe(img);
        });
    }
    
    // Thêm animation cho tất cả các event cards khi trang tải xong
    setTimeout(() => {
        eventCardElements.forEach((card, index) => {
            card.classList.add('fade-in');
            card.style.animationDelay = `${index * 100}ms`;
        });
    }, 200);
    
    // Thêm CSS nếu cần
    const additionalStyles = `
        /* Thêm styles cho các hiệu ứng mới */
        .lazy-image {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        
        .lazy-image.loaded {
            opacity: 1;
        }
        
        .highlight-target {
            animation: highlight-pulse 1.5s ease-in-out;
        }
        
        @keyframes highlight-pulse {
            0%, 100% { box-shadow: 0 0 0 0 rgba(128, 0, 0, 0); }
            50% { box-shadow: 0 0 0 10px rgba(128, 0, 0, 0.1); }
        }
        
        .pulse {
            animation: button-pulse 0.7s ease-in-out;
        }
        
        @keyframes button-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .registering {
            animation: registering-pulse 1.5s infinite;
        }
        
        @keyframes registering-pulse {
            0%, 100% { box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); }
            50% { box-shadow: 0 10px 30px rgba(128, 0, 0, 0.15); }
        }
        
        .focused {
            box-shadow: 0 0 0 3px rgba(128, 0, 0, 0.2);
            border-radius: 50px;
        }
        
        body.searching,
        body.filtering {
            cursor: progress;
        }
    `;
    
    // Thêm CSS vào trang nếu chưa có
    if (!document.getElementById('additional-styles')) {
        const styleElement = document.createElement('style');
        styleElement.id = 'additional-styles';
        styleElement.textContent = additionalStyles;
        document.head.appendChild(styleElement);
    }
});
