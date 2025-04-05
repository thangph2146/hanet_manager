document.addEventListener('DOMContentLoaded', function() {
    // Lấy các phần tử DOM cần thiết
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const alertContainer = document.getElementById('alertContainer');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const loginBtn = document.querySelector('button[type="submit"]');
    const loginCard = document.querySelector('.login-card');
    
    // Animation cho login card
    setTimeout(() => {
        loginCard.style.opacity = '1';
        loginCard.style.transform = 'translateY(0)';
    }, 300);
    
    // Kiểm tra các tham số URL để hiển thị thông báo nếu có
    const urlParams = new URLSearchParams(window.location.search);
    const loginStatus = urlParams.get('status');
    const message = urlParams.get('message');
    
    if (loginStatus && message) {
        if (loginStatus === 'success') {
            showAlert(message, 'success');
        } else if (loginStatus === 'error') {
            showAlert(message, 'danger');
        }
    }
    
    // Xử lý hiển thị/ẩn mật khẩu
    if (togglePasswordBtn) {
        togglePasswordBtn.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Thay đổi icon
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
    
    // Xử lý focus vào input
    const formControls = document.querySelectorAll('.form-control');
    formControls.forEach(input => {
        const inputGroup = input.closest('.input-group');
        const icon = inputGroup ? inputGroup.querySelector('.input-icon i') : null;
        
        input.addEventListener('focus', function() {
            inputGroup.classList.add('focused');
            if (icon) icon.style.color = 'var(--primary-color)';
        });
        
        input.addEventListener('blur', function() {
            inputGroup.classList.remove('focused');
            if (icon) icon.style.color = '';
        });
        
        // Nếu input đã có giá trị (do autocomplete)
        if (input.value) {
            inputGroup.classList.add('has-value');
        }
        
        input.addEventListener('input', function() {
            if (this.value) {
                inputGroup.classList.add('has-value');
            } else {
                inputGroup.classList.remove('has-value');
            }
        });
    });
    
    // Xử lý submit form
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Xóa thông báo lỗi cũ
            clearAlerts();
            
            // Kiểm tra dữ liệu nhập
            if (!validateForm()) {
                return false;
            }
            
            // Hiển thị loading
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            loginBtn.disabled = true;
            
            // Submit form
            setTimeout(() => {
                this.submit();
            }, 800);
        });
    }
    
    // Kiểm tra dữ liệu nhập
    function validateForm() {
        let isValid = true;
        
        // Kiểm tra email
        if (!emailInput.value.trim()) {
            showAlert('Vui lòng nhập địa chỉ email của bạn.', 'danger');
            emailInput.classList.add('is-invalid');
            emailInput.closest('.input-group').classList.add('shake');
            setTimeout(() => {
                emailInput.closest('.input-group').classList.remove('shake');
            }, 500);
            isValid = false;
        } else if (!isValidEmail(emailInput.value.trim())) {
            showAlert('Vui lòng nhập địa chỉ email hợp lệ.', 'danger');
            emailInput.classList.add('is-invalid');
            emailInput.closest('.input-group').classList.add('shake');
            setTimeout(() => {
                emailInput.closest('.input-group').classList.remove('shake');
            }, 500);
            isValid = false;
        } else {
            emailInput.classList.remove('is-invalid');
        }
        
        // Kiểm tra mật khẩu
        if (!passwordInput.value) {
            showAlert('Vui lòng nhập mật khẩu của bạn.', 'danger');
            passwordInput.classList.add('is-invalid');
            passwordInput.closest('.input-group').classList.add('shake');
            setTimeout(() => {
                passwordInput.closest('.input-group').classList.remove('shake');
            }, 500);
            isValid = false;
        } else {
            passwordInput.classList.remove('is-invalid');
        }
        
        return isValid;
    }
    
    // Kiểm tra định dạng email
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Hiển thị thông báo
    function showAlert(message, type = 'info') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Tự động ẩn thông báo thành công sau 5 giây
        if (type === 'success') {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => alert.remove(), 300);
            }, 5000);
        }
    }
    
    // Xóa tất cả thông báo
    function clearAlerts() {
        alertContainer.innerHTML = '';
    }
    
    // Xử lý form quên mật khẩu
    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    if (forgotPasswordForm) {
        forgotPasswordForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const resetEmail = document.getElementById('resetEmail').value.trim();
            if (!resetEmail || !isValidEmail(resetEmail)) {
                showAlert('Vui lòng nhập địa chỉ email hợp lệ.', 'danger');
                const emailGroup = document.getElementById('resetEmail').closest('.input-group');
                emailGroup.classList.add('shake');
                setTimeout(() => {
                    emailGroup.classList.remove('shake');
                }, 500);
                return false;
            }
            
            // Hiển thị loading trên nút submit
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang gửi...';
            submitBtn.disabled = true;
            
            // Mô phỏng gửi yêu cầu (thực tế sẽ sử dụng AJAX)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                // Đóng modal và hiển thị thông báo
                const forgotPasswordModal = bootstrap.Modal.getInstance(document.getElementById('forgotPasswordModal'));
                forgotPasswordModal.hide();
                
                showAlert('Yêu cầu đặt lại mật khẩu đã được gửi. Vui lòng kiểm tra email của bạn.', 'success');
            }, 1500);
        });
    }

    // Initialize particles.js
    initParticles();
});

// Particles.js configuration and initialization
function initParticles() {
    if (document.getElementById('particles-js')) {
        particlesJS('particles-js', {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#ffffff"
                },
                "shape": {
                    "type": "circle",
                    "stroke": {
                        "width": 0,
                        "color": "#000000"
                    },
                    "polygon": {
                        "nb_sides": 5
                    }
                },
                "opacity": {
                    "value": 0.4,
                    "random": true,
                    "anim": {
                        "enable": true,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
                "line_linked": {
                    "enable": true,
                    "distance": 150,
                    "color": "#ffffff",
                    "opacity": 0.3,
                    "width": 1
                },
                "move": {
                    "enable": true,
                    "speed": 2,
                    "direction": "none",
                    "random": true,
                    "straight": false,
                    "out_mode": "out",
                    "bounce": false,
                    "attract": {
                        "enable": true,
                        "rotateX": 600,
                        "rotateY": 1200
                    }
                }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "grab"
                    },
                    "onclick": {
                        "enable": true,
                        "mode": "push"
                    },
                    "resize": true
                },
                "modes": {
                    "grab": {
                        "distance": 140,
                        "line_linked": {
                            "opacity": 1
                        }
                    },
                    "bubble": {
                        "distance": 400,
                        "size": 40,
                        "duration": 2,
                        "opacity": 8,
                        "speed": 3
                    },
                    "repulse": {
                        "distance": 200,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                    "remove": {
                        "particles_nb": 2
                    }
                }
            },
            "retina_detect": true
        });
    }
}
