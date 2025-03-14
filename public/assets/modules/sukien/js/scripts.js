/**
 * Thư viện JavaScript cho module sự kiện
 * Xử lý đếm ngược và các hiệu ứng tương tác
 */

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý đếm ngược
    let countdownDate = null;
    const eventDateElem = document.getElementById('event-date');
    
    if (eventDateElem) {
        const eventDate = eventDateElem.value;
        if (eventDate) {
            countdownDate = new Date(eventDate).getTime();
        }
    }
    
    // Nếu không có ngày sự kiện cụ thể, đặt mặc định là 30 ngày kể từ hiện tại
    if (!countdownDate) {
        countdownDate = new Date();
        countdownDate.setDate(countdownDate.getDate() + 30);
        countdownDate = countdownDate.getTime();
    }
    
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
        if (document.getElementById('countdown-days')) {
            document.getElementById('countdown-days').innerText = days < 10 ? '0' + days : days;
        }
        if (document.getElementById('countdown-hours')) {
            document.getElementById('countdown-hours').innerText = hours < 10 ? '0' + hours : hours;
        }
        if (document.getElementById('countdown-minutes')) {
            document.getElementById('countdown-minutes').innerText = minutes < 10 ? '0' + minutes : minutes;
        }
        if (document.getElementById('countdown-seconds')) {
            document.getElementById('countdown-seconds').innerText = seconds < 10 ? '0' + seconds : seconds;
        }
        
        // Khi đếm ngược kết thúc
        if (distance < 0) {
            clearInterval(countdown);
            
            if (document.getElementById('countdown-days')) {
                document.getElementById('countdown-days').innerText = '00';
            }
            if (document.getElementById('countdown-hours')) {
                document.getElementById('countdown-hours').innerText = '00';
            }
            if (document.getElementById('countdown-minutes')) {
                document.getElementById('countdown-minutes').innerText = '00';
            }
            if (document.getElementById('countdown-seconds')) {
                document.getElementById('countdown-seconds').innerText = '00';
            }
            
            // Hiển thị thông báo sự kiện đã diễn ra
            const countdownTitle = document.querySelector('.countdown-title');
            if (countdownTitle) {
                countdownTitle.innerText = 'Sự kiện đã diễn ra!';
            }
        }
    }, 1000);
    
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