/**
 * JavaScript cho trang sự kiện đã tham gia
 */

"use strict";

class EventsCheckin {
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
        // Đếm thống kê
        this.initCounters();
    }
    
    initElements() {
        this.searchInput = document.getElementById('eventSearch');
        this.sortSelect = document.getElementById('eventSort');
        this.eventCards = document.querySelectorAll('.event-card');
        this.statValues = document.querySelectorAll('.stats-value');
        this.certificateButtons = document.querySelectorAll('.btn-download-certificate');
    }
    
    initEventListeners() {
        // Sự kiện tìm kiếm
        if (this.searchInput) {
            this.searchInput.addEventListener('input', () => {
                this.searchEvents();
            });
            
            // Xóa kết quả tìm kiếm khi nhấn Escape
            this.searchInput.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    this.searchInput.value = '';
                    this.searchEvents();
                }
            });
        }
        
        // Sự kiện sắp xếp
        if (this.sortSelect) {
            this.sortSelect.addEventListener('change', () => {
                this.sortEvents();
            });
        }
        
        // Xử lý tải chứng chỉ
        if (this.certificateButtons.length > 0) {
            this.certificateButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const certificateUrl = button.getAttribute('href');
                    this.downloadCertificate(certificateUrl);
                });
            });
        }
        
        // Thêm hiệu ứng hover cho các card
        this.eventCards.forEach(card => {
            this.addCardInteractions(card);
        });
        
        // Xử lý checkout
        const checkoutButtons = document.querySelectorAll('.btn-checkout');
        if (checkoutButtons.length > 0) {
            checkoutButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const checkoutUrl = button.getAttribute('href');
                    this.confirmCheckout(checkoutUrl);
                });
            });
        }
    }
    
    addCardInteractions(card) {
        // Hiệu ứng hover
        card.addEventListener('mouseenter', () => {
            const img = card.querySelector('.event-image img');
            if (img) {
                img.style.transform = 'scale(1.05)';
            }
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.1)';
        });
        
        card.addEventListener('mouseleave', () => {
            const img = card.querySelector('.event-image img');
            if (img) {
                img.style.transform = '';
            }
            card.style.transform = '';
            card.style.boxShadow = '';
        });
    }
    
    confirmCheckout(url) {
        if (confirm('Bạn có chắc chắn muốn check-out khỏi sự kiện này?')) {
            this.showToast('Đang xử lý check-out...', 'info');
            window.location.href = url;
        }
    }
    
    downloadCertificate(url) {
        if (!url) return;
        
        // Hiển thị thông báo đang tải
        this.showToast('Đang tải chứng chỉ...', 'info');
        
        // Mở URL trong tab mới
        window.open(url, '_blank');
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
    
    initCounters() {
        // Hiệu ứng đếm số
        this.statValues.forEach(statValue => {
            this.animateCounter(statValue);
        });
    }
    
    animateCounter(element) {
        if (!element) return;
        
        const finalValue = parseInt(element.textContent);
        let currentValue = 0;
        
        const duration = 1500; // Thời gian hoàn thành (ms)
        const frameDuration = 1000 / 60; // 60fps
        const totalFrames = Math.round(duration / frameDuration);
        
        let frame = 0;
        const countUp = () => {
            frame++;
            const progress = frame / totalFrames;
            const easeOutQuad = progress * (2 - progress);
            const currentCount = Math.round(easeOutQuad * finalValue);
            
            if (currentCount > currentValue) {
                currentValue = currentCount;
                element.textContent = currentValue;
            }
            
            if (frame < totalFrames) {
                requestAnimationFrame(countUp);
            }
        };
        
        requestAnimationFrame(countUp);
    }
    
    searchEvents() {
        const searchTerm = this.searchInput.value.toLowerCase().trim();
        let visibleCount = 0;
        
        this.eventCards.forEach(card => {
            const eventName = card.getAttribute('data-event-name').toLowerCase();
            
            if (searchTerm === '' || eventName.includes(searchTerm)) {
                card.style.display = '';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Cập nhật số lượng hiển thị
        if (this.statValues.length > 0) {
            this.statValues[0].textContent = visibleCount;
        }
    }
    
    sortEvents() {
        const sortBy = this.sortSelect.value;
        const container = document.querySelector('.attended-events-list');
        
        if (!container) return;
        
        const cards = Array.from(this.eventCards);
        
        cards.sort((a, b) => {
            if (sortBy === 'newest') {
                const dateA = new Date(a.getAttribute('data-event-date'));
                const dateB = new Date(b.getAttribute('data-event-date'));
                return dateB - dateA;
            } else if (sortBy === 'oldest') {
                const dateA = new Date(a.getAttribute('data-event-date'));
                const dateB = new Date(b.getAttribute('data-event-date'));
                return dateA - dateB;
            } else if (sortBy === 'name') {
                const nameA = a.getAttribute('data-event-name').toLowerCase();
                const nameB = b.getAttribute('data-event-name').toLowerCase();
                return nameA.localeCompare(nameB);
            }
            
            return 0;
        });
        
        // Xóa các card hiện tại
        this.eventCards.forEach(card => {
            card.remove();
        });
        
        // Thêm lại các card đã sắp xếp
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            container.appendChild(card);
        });
        
        // Cập nhật lại hiệu ứng
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
    new EventsCheckin();
});
