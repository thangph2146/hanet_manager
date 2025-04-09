/**
 * Thư viện JavaScript cho module sự kiện
 * Xử lý đếm ngược và các hiệu ứng tương tác
 */

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý đếm ngược cho tất cả các timer
    const countdownTimers = document.querySelectorAll('.countdown-timer[data-countdown]');
    
    countdownTimers.forEach(function(timerElement) {
        const countdownDate = new Date(timerElement.dataset.countdown).getTime();
        
        // Cập nhật đếm ngược mỗi giây
        const countdown = setInterval(function() {
            const now = new Date().getTime();
            const distance = countdownDate - now;
            
            // Tính toán các đơn vị thời gian
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            // Cập nhật giao diện
            const daysElement = timerElement.querySelector('.days');
            const hoursElement = timerElement.querySelector('.hours');
            const minutesElement = timerElement.querySelector('.minutes');
            const secondsElement = timerElement.querySelector('.seconds');
            
            if (daysElement) {
                daysElement.innerText = days < 10 ? '0' + days : days;
            }
            if (hoursElement) {
                hoursElement.innerText = hours < 10 ? '0' + hours : hours;
            }
            if (minutesElement) {
                minutesElement.innerText = minutes < 10 ? '0' + minutes : minutes;
            }
            if (secondsElement) {
                secondsElement.innerText = seconds < 10 ? '0' + seconds : seconds;
            }
            
            // Khi đếm ngược kết thúc
            if (distance < 0) {
                clearInterval(countdown);
                
                if (daysElement) daysElement.innerText = '00';
                if (hoursElement) hoursElement.innerText = '00';
                if (minutesElement) minutesElement.innerText = '00';
                if (secondsElement) secondsElement.innerText = '00';
                
                // Tự động reload trang để cập nhật trạng thái
                window.location.reload();
            }
        }, 1000);
    });
    
    // Xử lý remaining time cho sự kiện đang diễn ra
    const remainingTimeElements = document.querySelectorAll('.remaining-time[data-endtime]');
    
    remainingTimeElements.forEach(function(element) {
        const endTime = new Date(element.dataset.endtime).getTime();
        
        const updateRemainingTime = function() {
            const now = new Date().getTime();
            const distance = endTime - now;
            
            if (distance > 0) {
                const hours = Math.ceil(distance / (1000 * 60 * 60));
                element.innerText = hours + ' giờ';
            } else {
                element.innerText = 'Đã kết thúc';
                // Tự động reload trang để cập nhật trạng thái
                window.location.reload();
            }
        };
        
        updateRemainingTime();
        setInterval(updateRemainingTime, 60000); // Cập nhật mỗi phút
    });
    
    // Back to top button
    const backToTopButton = document.querySelector('.back-to-top');
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('active');
            } else {
                backToTopButton.classList.remove('active');
            }
        });
        
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
    
    // Hiệu ứng scroll animation
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    
    if (animateElements.length > 0) {
        const checkInView = function() {
            animateElements.forEach(function(element) {
                const elementPosition = element.getBoundingClientRect().top;
                const windowHeight = window.innerHeight;
                
                if (elementPosition < windowHeight - 100) {
                    element.classList.add('animate-fade-in-up');
                }
            });
        };
        
        // Kiểm tra khi trang tải
        checkInView();
        
        // Kiểm tra khi cuộn trang
        window.addEventListener('scroll', checkInView);
    }
    
    // Hiệu ứng parallax cho hero section
    const heroSection = document.querySelector('.hero-section');
    
    if (heroSection) {
        window.addEventListener('scroll', function() {
            const scrollPosition = window.pageYOffset;
            heroSection.style.backgroundPositionY = scrollPosition * 0.5 + 'px';
        });
    }
    
    // Xử lý form đăng ký
    const registrationForm = document.querySelector('form[action*="register"]');
    
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
            submitButton.disabled = true;
        });
    }
}); 