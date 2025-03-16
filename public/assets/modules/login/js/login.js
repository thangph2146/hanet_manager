document.addEventListener('DOMContentLoaded', function() {
    // Hiệu ứng hiển thị/ẩn mật khẩu
    const passwordToggle = document.querySelector('#show_hide_password a');
    if (passwordToggle) {
        passwordToggle.addEventListener('click', function(e) {
            e.preventDefault();
            const passwordInput = document.querySelector('#inputChoosePassword');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'text') {
                passwordInput.type = 'password';
                icon.classList.add('bx-hide');
                icon.classList.remove('bx-show');
            } else if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            }
        });
    }
    
    // Thêm class cho input-group để có hiệu ứng animation
    const inputGroups = document.querySelectorAll('.input-group');
    inputGroups.forEach(group => {
        group.classList.add('input-group-animated');
    });
    
    // Thêm class cho các nút để có hiệu ứng animation
    const buttons = document.querySelectorAll('.btn-primary, .btn-danger');
    buttons.forEach(button => {
        button.classList.add('btn-animated');
        
        // Hiệu ứng ripple khi click nút
        button.addEventListener('click', function(e) {
            const x = e.clientX - e.target.getBoundingClientRect().left;
            const y = e.clientY - e.target.getBoundingClientRect().top;
            
            const ripple = document.createElement('span');
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            ripple.className = 'ripple-effect';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
        
        // Hiệu ứng animation cho icon trong nút
        button.addEventListener('mouseenter', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.add('animate__animated', 'animate__heartBeat');
            }
        });
        
        button.addEventListener('mouseleave', function() {
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.remove('animate__animated', 'animate__heartBeat');
            }
        });
    });
    
    // Thêm hiệu ứng pulse cho logo
    const logos = document.querySelectorAll('.school-logo, .mobile-logo');
    logos.forEach(logo => {
        logo.addEventListener('mouseenter', function() {
            this.style.animation = 'pulse 0.5s';
            this.style.transform = 'scale(1.05)';
        });
        
        logo.addEventListener('mouseleave', function() {
            this.style.animation = '';
            this.style.transform = 'scale(1)';
        });
    });
}); 