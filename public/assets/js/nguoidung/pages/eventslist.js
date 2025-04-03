/**
 * JavaScript cho trang danh sách sự kiện
 */

"use strict";

class EventsList {
    constructor() {
        // Khởi tạo các thuộc tính
        this.currentPage = 1;
        this.elementsPerPage = 12;
        this.totalEvents = 0;
        this.urlParams = new URLSearchParams(window.location.search);
        
        // Lưu trạng thái bộ lọc
        this.filters = {
            search: this.urlParams.get('search') || '',
            category: this.urlParams.get('category') || '',
            status: this.urlParams.get('status') || '',
            sort: this.urlParams.get('sort') || 'newest'
        };
        
        // Khởi tạo đối tượng khi trang đã tải
        document.addEventListener('DOMContentLoaded', () => this.init());
    }
    
    /**
     * Khởi tạo các chức năng cần thiết
     */
    init() {
        this.initElements();
        this.initEventListeners();
        this.initAnimations();
        this.updateCounters();
        this.addCardInteractions();
        this.startEventCountdowns();
        
        // Khôi phục các bộ lọc từ URL
        this.restoreFiltersFromUrl();
        
        // Hiện tại mặc định áp dụng các bộ lọc
        this.filterEvents();
    }
    
    /**
     * Khởi tạo các phần tử DOM
     */
    initElements() {
        this.searchInput = document.getElementById('eventSearch');
        this.categorySelect = document.getElementById('eventCategory');
        this.statusSelect = document.getElementById('eventStatus');
        this.sortSelect = document.getElementById('eventSort');
        this.applyFiltersBtn = document.getElementById('applyFilters');
        this.resetFiltersBtn = document.getElementById('resetFilters');
        this.eventCards = document.querySelectorAll('.event-card');
        this.searchTimeout = null;
        
        // Phần tử thống kê
        this.totalEventsEl = document.querySelector('#total-events');
        this.upcomingEventsEl = document.querySelector('#upcoming-events');
        this.registeredEventsEl = document.querySelector('#registered-events');
        this.attendedEventsEl = document.querySelector('#attended-events');
        
        // Phần tử phân trang
        this.paginationContainer = document.querySelector('.pagination-container');
    }
    
    /**
     * Khởi tạo các trình nghe sự kiện
     */
    initEventListeners() {
        // Sự kiện cho bộ lọc
        if (this.applyFiltersBtn) {
            this.applyFiltersBtn.addEventListener('click', () => this.applyFilters());
        }
        
        if (this.resetFiltersBtn) {
            this.resetFiltersBtn.addEventListener('click', () => this.resetFilters());
        }
        
        // Sự kiện tìm kiếm realtime
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                this.filters.search = e.target.value.trim();
                this.filterEvents();
            });
        }
        
        // Sự kiện thay đổi bộ lọc danh mục
        if (this.categorySelect) {
            this.categorySelect.addEventListener('change', (e) => {
                this.filters.category = e.target.value;
                this.filterEvents();
            });
        }
        
        // Sự kiện thay đổi bộ lọc trạng thái
        if (this.statusSelect) {
            this.statusSelect.addEventListener('change', (e) => {
                this.filters.status = e.target.value;
                this.filterEvents();
            });
        }
        
        // Sự kiện thay đổi sắp xếp
        if (this.sortSelect) {
            this.sortSelect.addEventListener('change', (e) => {
                this.filters.sort = e.target.value;
                this.sortEvents();
                this.filterEvents();
            });
        }
        
        // Xử lý đăng ký sự kiện
        const registerButtons = document.querySelectorAll('.btn-register');
        if (registerButtons.length > 0) {
            registerButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const registerUrl = button.getAttribute('href');
                    this.confirmRegister(registerUrl);
                });
            });
        }
        
        // Xử lý hủy đăng ký sự kiện
        const cancelButtons = document.querySelectorAll('.btn-cancel');
        if (cancelButtons.length > 0) {
            cancelButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const cancelUrl = button.getAttribute('href');
                    this.confirmCancel(cancelUrl);
                });
            });
        }
        
        // Thêm hiệu ứng hover cho các card
        this.eventCards.forEach(card => {
            this.addCardInteractions(card);
        });
    }
    
    /**
     * Khôi phục các bộ lọc từ URL
     */
    restoreFiltersFromUrl() {
        // Đặt giá trị tìm kiếm
        if (this.searchInput && this.filters.search) {
            this.searchInput.value = this.filters.search;
        }
        
        // Đặt giá trị danh mục
        if (this.categorySelect && this.filters.category) {
            this.categorySelect.value = this.filters.category;
        }
        
        // Đặt giá trị trạng thái
        if (this.statusSelect && this.filters.status) {
            this.statusSelect.value = this.filters.status;
        }
        
        // Đặt giá trị sắp xếp
        if (this.sortSelect && this.filters.sort) {
            this.sortSelect.value = this.filters.sort;
        }
    }
    
    /**
     * Lọc danh sách sự kiện dựa trên bộ lọc
     */
    filterEvents() {
        let visibleCount = 0;
        
        // Lặp qua từng thẻ sự kiện để áp dụng bộ lọc
        this.eventCards.forEach(card => {
            // Lấy dữ liệu thẻ
            const title = card.querySelector('.event-title').textContent.toLowerCase();
            const description = card.querySelector('.event-description')?.textContent.toLowerCase() || '';
            const category = card.querySelector('.event-category')?.textContent.toLowerCase() || '';
            const location = card.querySelector('.event-location')?.textContent.toLowerCase() || '';
            const hasRegistered = card.hasAttribute('data-registered');
            const hasAttended = card.hasAttribute('data-attended');
            
            // Kiểm tra từ khóa tìm kiếm
            const searchMatch = !this.filters.search || 
                title.includes(this.filters.search.toLowerCase()) || 
                description.includes(this.filters.search.toLowerCase()) ||
                location.includes(this.filters.search.toLowerCase());
            
            // Kiểm tra danh mục
            const categoryMatch = !this.filters.category || 
                category.includes(this.filters.category.toLowerCase()) || 
                this.filters.category === 'all';
            
            // Kiểm tra trạng thái
            let statusMatch = true;
            
            if (this.filters.status === 'registered') {
                statusMatch = hasRegistered;
            } else if (this.filters.status === 'attended') {
                statusMatch = hasAttended;
            } else if (this.filters.status === 'upcoming') {
                statusMatch = !hasAttended;
            }
            
            // Áp dụng tất cả bộ lọc
            const isVisible = searchMatch && categoryMatch && statusMatch;
            
            // Hiển thị hoặc ẩn thẻ
            card.style.display = isVisible ? 'flex' : 'none';
            
            // Tăng số lượng thấy được
            if (isVisible) {
                visibleCount++;
            }
        });
        
        // Kiểm tra nếu không có kết quả nào, hiển thị trạng thái trống
        this.checkEmptyResults();
    }
    
    /**
     * Áp dụng bộ lọc và tải lại trang
     */
    applyFilters() {
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        
        // Thêm các tham số tìm kiếm
        if (this.filters.search) {
            params.set('search', this.filters.search);
        } else {
            params.delete('search');
        }
        
        // Thêm các tham số danh mục
        if (this.filters.category && this.filters.category !== 'all') {
            params.set('category', this.filters.category);
        } else {
            params.delete('category');
        }
        
        // Thêm các tham số trạng thái
        if (this.filters.status && this.filters.status !== 'all') {
            params.set('status', this.filters.status);
        } else {
            params.delete('status');
        }
        
        // Thêm các tham số sắp xếp
        if (this.filters.sort && this.filters.sort !== 'newest') {
            params.set('sort', this.filters.sort);
        } else {
            params.delete('sort');
        }
        
        // Đặt các tham số mới và chuyển hướng
        url.search = params.toString();
        window.location.href = url.toString();
    }
    
    /**
     * Đặt lại bộ lọc
     */
    resetFilters() {
        // Đặt lại các giá trị bộ lọc
        if (this.searchInput) this.searchInput.value = '';
        if (this.categorySelect) this.categorySelect.value = 'all';
        if (this.statusSelect) this.statusSelect.value = 'all';
        if (this.sortSelect) this.sortSelect.value = 'newest';
        
        // Đặt lại đối tượng bộ lọc
        this.filters = {
            search: '',
            category: 'all',
            status: 'all',
            sort: 'newest'
        };
        
        // Tải lại trang không có tham số
        window.location.href = window.location.pathname;
    }
    
    /**
     * Sắp xếp danh sách sự kiện
     */
    sortEvents() {
        const sortBy = this.sortSelect.value;
        const container = document.querySelector('.events-grid');
        
        if (!container) return;
        
        const cards = Array.from(this.eventCards);
        
        cards.sort((a, b) => {
            if (sortBy === 'upcoming') {
                // Sắp xếp theo thời gian sắp diễn ra
                const dateA = new Date(a.getAttribute('data-date'));
                const dateB = new Date(b.getAttribute('data-date'));
                return dateA - dateB;
            } else if (sortBy === 'newest') {
                // Sắp xếp theo thời gian mới nhất
                const dateA = new Date(a.getAttribute('data-date'));
                const dateB = new Date(b.getAttribute('data-date'));
                return dateB - dateA;
            } else if (sortBy === 'popular') {
                // Sắp xếp theo số lượt xem
                const viewsA = parseInt(a.getAttribute('data-views') || '0');
                const viewsB = parseInt(b.getAttribute('data-views') || '0');
                return viewsB - viewsA;
            }
            
            return 0;
        });
        
        // Xóa các card hiện tại
        this.eventCards.forEach(card => {
            card.remove();
        });
        
        // Thêm lại các card đã sắp xếp
        cards.forEach((card, index) => {
            // Thêm hiệu ứng delay cho animation
            card.style.animationDelay = `${index * 0.1}s`;
            container.appendChild(card);
        });
        
        // Cập nhật lại danh sách card sau khi sắp xếp
        this.eventCards = document.querySelectorAll('.event-card');
        this.eventCards.forEach(card => {
            this.addCardInteractions(card);
        });
    }
    
    /**
     * Thêm hiệu ứng tương tác cho thẻ sự kiện
     */
    addCardInteractions(card) {
        // Hiệu ứng hover
        card.addEventListener('mouseenter', () => {
            this.addHoverEffect(card);
        });
        
        card.addEventListener('mouseleave', () => {
            this.removeHoverEffect(card);
        });
        
        // Khởi tạo thanh tiến trình sức chứa
        const capacityBar = card.querySelector('.capacity-bar');
        if (capacityBar) {
            const capacity = parseInt(card.getAttribute('data-capacity') || 0);
            const registered = parseInt(card.getAttribute('data-registered-count') || 0);
            const percentage = capacity > 0 ? (registered / capacity) * 100 : 0;
            
            capacityBar.style.width = `${Math.min(percentage, 100)}%`;
            
            // Thay đổi màu dựa trên tỷ lệ
            if (percentage >= 90) {
                capacityBar.style.background = 'linear-gradient(to right, #e74a3b, #be2617)';
            } else if (percentage >= 70) {
                capacityBar.style.background = 'linear-gradient(to right, #f6c23e, #dda20a)';
            }
        }
    }
    
    addHoverEffect(card) {
        // Thêm hiệu ứng khi hover vào card
        const img = card.querySelector('.event-image img');
        if (img) {
            img.style.transform = 'scale(1.05)';
        }
        
        // Thêm hiệu ứng đổ bóng
        card.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.1)';
        card.style.transform = 'translateY(-5px)';
    }
    
    removeHoverEffect(card) {
        // Xóa hiệu ứng khi hover ra
        const img = card.querySelector('.event-image img');
        if (img) {
            img.style.transform = '';
        }
        
        // Xóa hiệu ứng đổ bóng
        card.style.boxShadow = '';
        card.style.transform = '';
    }
    
    confirmRegister(url) {
        if (confirm('Bạn có chắc muốn đăng ký tham gia sự kiện này?')) {
            this.showToast('Đang xử lý đăng ký...', 'info');
            window.location.href = url;
        }
    }
    
    confirmCancel(url) {
        if (confirm('Bạn có chắc chắn muốn hủy đăng ký sự kiện này?')) {
            this.showToast('Đang xử lý hủy đăng ký...', 'info');
            window.location.href = url;
        }
    }
    
    /**
     * Bắt đầu đếm ngược cho các sự kiện sắp diễn ra
     */
    startEventCountdowns() {
        this.eventCards.forEach(card => {
            const countdownEl = card.querySelector('.event-countdown');
            if (!countdownEl) return;
            
            const eventDate = new Date(card.getAttribute('data-date') || 0);
            const eventEndDate = new Date(card.getAttribute('data-end-date') || 0);
            const now = new Date();
            
            // Sự kiện đã kết thúc
            if (now > eventEndDate) {
                countdownEl.innerHTML = '<i class="fas fa-hourglass-end"></i> Đã kết thúc';
                return;
            }
            
            // Sự kiện đang diễn ra
            if (now >= eventDate && now <= eventEndDate) {
                countdownEl.innerHTML = '<i class="fas fa-fire"></i> Đang diễn ra';
                countdownEl.classList.add('ongoing');
                return;
            }
            
            // Cập nhật đếm ngược mỗi giây
            const updateCountdown = () => {
                const now = new Date();
                const diff = eventDate - now;
                
                if (diff <= 0) {
                    // Đã đến thời gian sự kiện, reload trang để cập nhật
                    location.reload();
                    return;
                }
                
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                
                if (days > 0) {
                    countdownEl.innerHTML = `<i class="fas fa-hourglass-half"></i> Còn ${days} ngày`;
                } else if (hours > 0) {
                    countdownEl.innerHTML = `<i class="fas fa-hourglass-half"></i> Còn ${hours} giờ`;
                } else {
                    countdownEl.innerHTML = `<i class="fas fa-hourglass-half"></i> Còn ${minutes} phút`;
                }
            };
            
            // Cập nhật ngay lập tức và sau đó mỗi phút
            updateCountdown();
            setInterval(updateCountdown, 60000);
        });
    }
    
    /**
     * Khởi tạo các hiệu ứng
     */
    initAnimations() {
        // Hiệu ứng cuộn
        if ('IntersectionObserver' in window) {
            const cards = document.querySelectorAll('.event-card');
            const statsItems = document.querySelectorAll('.stats-item');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                root: null,
                threshold: 0.15
            });
            
            // Quan sát các thẻ sự kiện
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.transitionDelay = `${index * 0.1}s`;
                observer.observe(card);
            });
            
            // Quan sát các thẻ thống kê
            statsItems.forEach(item => {
                observer.observe(item);
            });
        } else {
            // Fallback cho trình duyệt không hỗ trợ IntersectionObserver
            document.querySelectorAll('.event-card, .stats-item').forEach(el => {
                el.classList.add('visible');
            });
        }
    }
    
    /**
     * Cập nhật các bộ đếm với hiệu ứng
     */
    updateCounters() {
        const elements = [
            { el: this.totalEventsEl, attr: 'data-count' },
            { el: this.upcomingEventsEl, attr: 'data-count' },
            { el: this.registeredEventsEl, attr: 'data-count' },
            { el: this.attendedEventsEl, attr: 'data-count' }
        ];
        
        elements.forEach(item => {
            if (item.el) {
                const finalValue = parseInt(item.el.getAttribute(item.attr) || 0);
                this.animateCounter(item.el, 0, finalValue, 1500);
            }
        });
    }
    
    /**
     * Tạo hiệu ứng đếm
     */
    animateCounter(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            const value = Math.floor(progress * (end - start) + start);
            element.textContent = value.toLocaleString();
            
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        
        window.requestAnimationFrame(step);
    }
    
    /**
     * Hiển thị thông báo toast
     */
    showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            </div>
            <div class="toast-content">${message}</div>
            <button class="toast-close"><i class="fas fa-times"></i></button>
        `;
        
        document.body.appendChild(toast);
        
        // Hiện toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Xử lý nút đóng
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        });
        
        // Tự động đóng sau 5 giây
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    }
    
    checkEmptyResults() {
        const visibleCards = Array.from(this.eventCards).filter(card => 
            card.style.display !== 'none'
        );
        
        const container = document.querySelector('.events-grid');
        const emptyState = document.querySelector('.empty-state');
        
        if (visibleCards.length === 0) {
            if (container) container.style.display = 'none';
            if (emptyState) emptyState.style.display = 'flex';
        } else {
            if (container) container.style.display = 'grid';
            if (emptyState) emptyState.style.display = 'none';
        }
    }
    
    /**
     * Định dạng ngày giờ theo kiểu dd/mm/yyyy h:i:s
     * 
     * @param {Date} date Đối tượng Date cần định dạng
     * @return {string} Chuỗi ngày giờ đã định dạng
     */
    formatDateTimeVN(date) {
        if (!date || isNaN(date.getTime())) return '';
        
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');
        
        return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
    }
}

// Thêm CSS cho toast notification
const styleToast = document.createElement('style');
styleToast.textContent = `
.toast {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background-color: #333;
    color: #fff;
    padding: 12px 20px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    z-index: 9999;
    transform: translateY(30px);
    opacity: 0;
    transition: all 0.3s ease;
}
.toast i {
    font-size: 1.2rem;
}
.toast.show {
    transform: translateY(0);
    opacity: 1;
}
.toast-info {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
}
.toast-success {
    background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
}
.toast-error {
    background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
}
.visible {
    opacity: 1 !important;
    transform: translateY(0) !important;
}
`;
document.head.appendChild(styleToast);

// Khởi tạo khi DOM đã load xong
document.addEventListener('DOMContentLoaded', () => {
    new EventsList();
});
