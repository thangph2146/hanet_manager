/* 
 * Welcome Page JavaScript
 * ĐH Ngân hàng TP.HCM - Event Management System
 */

(function() {
    'use strict';

    // DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initNavbarScroll();
        initCounterAnimation();
        initAOS();
        initBackToTop();
    });

    /**
     * Navbar scroll effect
     * Adds 'scrolled' class to navbar when scrolling down
     */
    function initNavbarScroll() {
        const navbar = document.querySelector('.navbar');
        if (!navbar) return;

        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    /**
     * Counter animation for stats
     * Animate numbers incrementing from 0 to target value
     */
    function initCounterAnimation() {
        const statNumbers = document.querySelectorAll('.stat-number');
        
        if (!statNumbers.length) return;

        // Only start animation when element is in viewport
        const isInViewport = function(elem) {
            const rect = elem.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        };

        // Animate counter
        const animateCounter = function(el) {
            const target = parseInt(el.innerText);
            const suffix = el.innerText.replace(/[0-9]/g, '');
            const duration = 2000; // 2 seconds
            const step = Math.ceil(target / (duration / 16)); // Approx. 60fps
            let current = 0;

            const updateCounter = function() {
                current += step;
                if (current >= target) {
                    el.textContent = target + suffix;
                    return;
                }
                el.textContent = current + suffix;
                requestAnimationFrame(updateCounter);
            };

            el.textContent = 0 + suffix;
            requestAnimationFrame(updateCounter);
        };

        // Check if elements are in viewport on scroll
        let animated = new Set();
        
        function checkAndAnimate() {
            statNumbers.forEach(function(statNumber) {
                if (!animated.has(statNumber) && isInViewport(statNumber)) {
                    animateCounter(statNumber);
                    animated.add(statNumber);
                }
            });
        }

        // Initial check
        checkAndAnimate();
        
        // Check on scroll
        window.addEventListener('scroll', checkAndAnimate);
    }

    /**
     * Initialize AOS (Animate On Scroll) library if available
     */
    function initAOS() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true,
                mirror: false
            });
        }
    }

    /**
     * Add active class to nav item based on current page
     */
    function setActiveNavItem() {
        const navLinks = document.querySelectorAll('.nav-link');
        const currentUrl = window.location.pathname;
        
        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            if (href === currentUrl || (currentUrl === '/' && href === '/index' || href === '#')) {
                link.parentElement.classList.add('active');
            }
        });
    }

    /**
     * Initialize back to top button
     */
    function initBackToTop() {
        let backToTop = document.querySelector('.back-to-top');
        
        if (!backToTop) return;
        
        // When the user scrolls down 200px from the top of the document, show the button
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        });
        
        // When the user clicks on the button, scroll to the top of the document
        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Run immediately
    setActiveNavItem();

    // Additional interactive elements
    const eventCards = document.querySelectorAll('.event-card');
    
    eventCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Sticky navbar effect on scroll
    const navbar = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            navbar.style.boxShadow = '0 10px 30px rgba(0, 0, 0, 0.1)';
        } else {
            navbar.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.1)';
        }
    });

    // Add active class to nav links based on scroll position
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

    window.addEventListener('scroll', function() {
        let current = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (window.scrollY >= (sectionTop - 200)) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });

    // Event registration button animation
    const eventButtons = document.querySelectorAll('.btn-event');
    
    eventButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.textContent = 'Đăng Ký Ngay!';
        });
        
        button.addEventListener('mouseleave', function() {
            this.textContent = 'Đăng Ký Tham Gia';
        });
    });

    // Responsive navbar behavior
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');
    
    if (navbarToggler) {
        document.addEventListener('click', function(e) {
            // Close the menu when clicking outside
            if (!navbarToggler.contains(e.target) && !navbarCollapse.contains(e.target) && navbarCollapse.classList.contains('show')) {
                navbarToggler.click();
            }
        });
    }

})(); 