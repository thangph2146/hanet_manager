/**
 * Dashboard Page JS - Phiên bản cải tiến
 * Xử lý các tương tác và hiển thị dữ liệu cho trang dashboard người dùng
 */

"use strict";

class DashboardPage {
    constructor() {
        this.init();
    }
    
    init() {
        // Khởi tạo các thành phần
        this.initElements();
        this.initEventListeners();
        this.initTooltips();
        this.initAnimations();
        
        // Thêm hiệu ứng loading
        this.addLoadingEffect();
    }
    
    initElements() {
        // Các elements chính của trang dashboard
        this.statCards = document.querySelectorAll('.stat-card');
        this.dashboardCards = document.querySelectorAll('.dashboard-card');
        this.eventLinks = document.querySelectorAll('.event-link');
        this.upcomingEventCards = document.querySelectorAll('.upcoming-event-card');
        this.eventItems = document.querySelectorAll('.event-item');
        this.welcomeBanner = document.querySelector('.welcome-banner');
    }
    
    initEventListeners() {
        // Xử lý sự kiện khi nhấn vào thẻ sự kiện
        this.upcomingEventCards.forEach(card => {
            card.addEventListener('click', (e) => {
                if (!e.target.closest('a')) {
                    const detailLink = card.querySelector('.event-actions a');
                    if (detailLink) {
                        this.addRippleEffect(card);
                        setTimeout(() => {
                            window.location.href = detailLink.href;
                        }, 300);
                    }
                }
            });
        });
        
        // Thêm hiệu ứng hover cho các thẻ sự kiện
        this.addHoverEffects();
        
        // Xử lý click vào các badge
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            if (badge.classList.contains('bg-info') && badge.textContent.includes('Chứng chỉ')) {
                badge.style.cursor = 'pointer';
                
                // Thêm hiệu ứng rung nhẹ cho badge chứng chỉ
                badge.addEventListener('mouseenter', () => {
                    badge.classList.add('animate__animated', 'animate__pulse');
                });
                
                badge.addEventListener('mouseleave', () => {
                    badge.classList.remove('animate__animated', 'animate__pulse');
                });
            }
        });
        
        // Thêm hiệu ứng cho các event item
        this.eventItems.forEach(item => {
            item.addEventListener('mouseenter', () => {
                const dateElement = item.querySelector('.event-date');
                if (dateElement) {
                    dateElement.style.transform = 'scale(1.05)';
                }
            });
            
            item.addEventListener('mouseleave', () => {
                const dateElement = item.querySelector('.event-date');
                if (dateElement) {
                    dateElement.style.transform = '';
                }
            });
        });
    }
    
    initTooltips() {
        // Khởi tạo tooltips cho các nút và thẻ
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
    
    initAnimations() {
        // Sử dụng Intersection Observer để kích hoạt animation khi scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        // Observe các elements cần animation
        document.querySelectorAll('.dashboard-card, .upcoming-event-card, .stat-card').forEach((el, index) => {
            el.style.animationDelay = `${index * 0.1}s`;
            observer.observe(el);
        });
        
        // Thêm hiệu ứng tài khoản người dùng ở welcome banner
        if (this.welcomeBanner) {
            this.welcomeBanner.querySelector('.welcome-title')?.classList.add('animate__animated', 'animate__fadeInDown');
            this.welcomeBanner.querySelector('.welcome-text')?.classList.add('animate__animated', 'animate__fadeIn');
            this.welcomeBanner.querySelector('.btn-primary')?.classList.add('animate__animated', 'animate__fadeInUp');
        }
    }
    
    addLoadingEffect() {
        // Hiển thị hiệu ứng loading cho các stat cards
        this.statCards.forEach(card => {
            const statValue = card.querySelector('.stat-value');
            if (statValue) {
                const finalValue = parseInt(statValue.textContent);
                let currentValue = 0;
                
                if (finalValue > 0) {
                    statValue.textContent = '0';
                    const interval = setInterval(() => {
                        currentValue += Math.ceil(finalValue / 20);
                        if (currentValue >= finalValue) {
                            clearInterval(interval);
                            currentValue = finalValue;
                        }
                        statValue.textContent = currentValue;
                    }, 50);
                }
            }
        });
    }
    
    addHoverEffects() {
        // Thêm hiệu ứng hover cho các thẻ sự kiện
        this.upcomingEventCards.forEach(card => {
            card.style.cursor = 'pointer';
        });
        
        // Thêm hiệu ứng hover cho các thẻ thống kê
        this.statCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-8px)';
                card.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.12)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
                card.style.boxShadow = '';
            });
        });
    }
    
    // Thêm hiệu ứng ripple khi click
    addRippleEffect(element) {
        const ripple = document.createElement('div');
        ripple.className = 'ripple-effect';
        
        // Tạo style cho ripple effect
        ripple.style.position = 'absolute';
        ripple.style.backgroundColor = 'rgba(255, 255, 255, 0.7)';
        ripple.style.borderRadius = '50%';
        ripple.style.pointerEvents = 'none';
        ripple.style.width = '100px';
        ripple.style.height = '100px';
        ripple.style.transform = 'translate(-50%, -50%) scale(0)';
        ripple.style.animation = 'ripple 0.5s linear';
        
        // Thêm keyframes animation
        if (!document.querySelector('#ripple-animation')) {
            const style = document.createElement('style');
            style.id = 'ripple-animation';
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: translate(-50%, -50%) scale(3);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
        
        // Đặt ripple ở vị trí click
        const rect = element.getBoundingClientRect();
        ripple.style.left = '50%';
        ripple.style.top = '50%';
        
        // Đảm bảo element có position relative
        if (getComputedStyle(element).position === 'static') {
            element.style.position = 'relative';
        }
        
        element.appendChild(ripple);
        
        // Xóa ripple sau khi hoàn thành animation
        setTimeout(() => {
            ripple.remove();
        }, 500);
    }
    
    // Format date cho hiển thị
    static formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('vi-VN', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
    
    // Format time cho hiển thị
    static formatTime(timeString) {
        // Nhận định dạng HH:MM:SS và chuyển thành HH:MM
        return timeString.substring(0, 5);
    }
    
    // Cập nhật dữ liệu thống kê
    static updateStatistics(registered, attended, completionRate) {
        const statElements = {
            registered: document.querySelector('.stat-card:nth-child(1) .stat-value'),
            attended: document.querySelector('.stat-card:nth-child(2) .stat-value'),
            completionRate: document.querySelector('.stat-card:nth-child(3) .stat-value')
        };
        
        // Animated counter
        for (const [key, element] of Object.entries(statElements)) {
            if (!element) continue;
            
            const currentValue = parseInt(element.textContent.replace('%', ''));
            let newValue;
            
            switch (key) {
                case 'registered':
                    newValue = registered;
                    break;
                case 'attended':
                    newValue = attended;
                    break;
                case 'completionRate':
                    newValue = completionRate;
                    break;
                default:
                    newValue = 0;
            }
            
            DashboardPage.animateCounter(element, currentValue, newValue, key === 'completionRate');
        }
    }
    
    // Animate counter
    static animateCounter(element, start, end, isPercentage = false) {
        const duration = 1000; // 1 giây
        const stepTime = 20;
        const steps = duration / stepTime;
        const increment = (end - start) / steps;
        let current = start;
        
        const timer = setInterval(() => {
            current += increment;
            
            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                current = end;
                clearInterval(timer);
            }
            
            element.textContent = isPercentage
                ? `${Math.round(current)}%`
                : Math.round(current);
                
            // Thêm hiệu ứng nếu có sự thay đổi lớn
            if (Math.abs(end - start) > 5) {
                element.style.color = '#4e73df';
                setTimeout(() => {
                    element.style.color = '';
                }, 1000);
            }
        }, stepTime);
    }
}

// Khởi tạo trang khi DOM đã tải xong
document.addEventListener('DOMContentLoaded', () => {
    window.dashboardPage = new DashboardPage();
    
    // Giả lập cập nhật dữ liệu sau 2 giây
    setTimeout(() => {
        DashboardPage.updateStatistics(15, 12, 80);
    }, 3000);
});
