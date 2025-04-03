/**
 * JavaScript cho trang danh sách sự kiện
 */

"use strict";

class EventsList {
    constructor() {
        this.init();
    }
    
    init() {
        // Khởi tạo các element
        this.initElements();
        
        // Khởi tạo các sự kiện
        this.initEventListeners();
        
        // Khởi tạo các animation
        this.initAnimations();
    }
    
    initElements() {
        this.searchInput = document.getElementById('eventSearch');
        this.categorySelect = document.getElementById('eventCategory');
        this.statusSelect = document.getElementById('eventStatus');
        this.sortSelect = document.getElementById('eventSort');
        this.applyFiltersBtn = document.getElementById('applyFilters');
        this.resetFiltersBtn = document.getElementById('resetFilters');
        this.eventCards = document.querySelectorAll('.event-card');
        this.searchTimeout = null;
    }
    
    initEventListeners() {
        // Xử lý khi nhấn nút lọc
        if (this.applyFiltersBtn) {
            this.applyFiltersBtn.addEventListener('click', () => {
                this.applyFilters();
            });
        }
        
        // Xử lý khi nhấn nút reset bộ lọc
        if (this.resetFiltersBtn) {
            this.resetFiltersBtn.addEventListener('click', () => {
                this.resetFilters();
            });
        }
        
        // Sự kiện tìm kiếm
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.filterEvents();
                }, 500);
            });
            
            // Xử lý khi nhấn Enter trong ô tìm kiếm
            this.searchInput.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') {
                    clearTimeout(this.searchTimeout);
                    this.applyFilters();
                }
            });
            
            // Xử lý khi nhấn Escape trong ô tìm kiếm
            this.searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.searchInput.value = '';
                    this.filterEvents();
                }
            });
        }
        
        // Sự kiện lọc theo phân loại
        if (this.categorySelect) {
            this.categorySelect.addEventListener('change', () => {
                this.filterEvents();
            });
        }
        
        // Sự kiện lọc theo trạng thái
        if (this.statusSelect) {
            this.statusSelect.addEventListener('change', () => {
                this.filterEvents();
            });
        }
        
        // Sự kiện sắp xếp
        if (this.sortSelect) {
            this.sortSelect.addEventListener('change', () => {
                this.sortEvents();
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
    
    addCardInteractions(card) {
        // Hiệu ứng hover
        card.addEventListener('mouseenter', () => {
            this.addHoverEffect(card);
        });
        
        card.addEventListener('mouseleave', () => {
            this.removeHoverEffect(card);
        });
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
    
    showToast(message, type = 'info') {
        // Tạo phần tử toast
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icon = document.createElement('i');
        if (type === 'info') {
            icon.className = 'fas fa-info-circle';
        } else if (type === 'success') {
            icon.className = 'fas fa-check-circle';
        } else if (type === 'error') {
            icon.className = 'fas fa-exclamation-circle';
        }
        
        const content = document.createElement('div');
        content.className = 'toast-content';
        content.textContent = message;
        
        toast.appendChild(icon);
        toast.appendChild(content);
        document.body.appendChild(toast);
        
        // Hiển thị toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Ẩn toast sau 3 giây
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
    
    initAnimations() {
        // Thêm animation khi scroll
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            
            this.eventCards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                card.style.transitionDelay = `${index * 0.1}s`;
                observer.observe(card);
            });
        } else {
            // Fallback cho trình duyệt không hỗ trợ IntersectionObserver
            this.eventCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }
    }
    
    applyFilters() {
        // Tạo URL mới với các tham số lọc
        const searchTerm = this.searchInput ? this.searchInput.value.trim() : '';
        const category = this.categorySelect ? this.categorySelect.value : 'all';
        const status = this.statusSelect ? this.statusSelect.value : 'all';
        const sort = this.sortSelect ? this.sortSelect.value : 'upcoming';
        
        let url = new URL(window.location.href);
        
        // Xóa các tham số hiện tại
        url.searchParams.delete('search');
        url.searchParams.delete('category');
        url.searchParams.delete('status');
        url.searchParams.delete('sort');
        url.searchParams.delete('page_events');
        
        // Thêm các tham số mới
        if (searchTerm) {
            url.searchParams.append('search', searchTerm);
        }
        
        if (category && category !== 'all') {
            url.searchParams.append('category', category);
        }
        
        if (status && status !== 'all') {
            url.searchParams.append('status', status);
        }
        
        url.searchParams.append('sort', sort);
        
        // Hiển thị thông báo đang tải
        this.showToast('Đang tải dữ liệu...', 'info');
        
        // Chuyển hướng đến URL mới
        window.location.href = url.toString();
    }
    
    resetFilters() {
        // Chuyển về trang gốc không có bộ lọc
        window.location.href = window.location.pathname;
    }
    
    filterEvents() {
        const searchTerm = this.searchInput ? this.searchInput.value.toLowerCase().trim() : '';
        const category = this.categorySelect ? this.categorySelect.value : 'all';
        const status = this.statusSelect ? this.statusSelect.value : 'all';
        
        this.eventCards.forEach(card => {
            const eventName = card.querySelector('.event-title').textContent.toLowerCase();
            const eventCategory = card.getAttribute('data-category').toLowerCase();
            const eventStatus = card.getAttribute('data-status');
            
            const matchesSearch = searchTerm === '' || eventName.includes(searchTerm);
            const matchesCategory = category === 'all' || eventCategory === category.toLowerCase();
            const matchesStatus = status === 'all' || eventStatus === status;
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Kiểm tra nếu không có kết quả nào, hiển thị trạng thái trống
        this.checkEmptyResults();
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
