/**
 * JavaScript cho trang thông tin cá nhân
 */

"use strict";

// Biến toàn cục
let profilePage;

// Class quản lý trang hồ sơ
class ProfilePage {
    constructor() {
        this.init();
    }
    
    init() {
        // Khởi tạo các thành phần
        this.initElements();
        this.initEventListeners();
        this.initTooltips();
        
        // Thêm hiệu ứng xuất hiện cho các phần tử
        this.addEntryAnimations();
    }
    
    initElements() {
        // Các elements chính
        this.editProfileBtn = document.getElementById('edit-profile-btn');
        this.saveProfileBtn = document.getElementById('save-profile-btn');
        
        // Forms
        this.editProfileForm = document.getElementById('edit-profile-form');
        
        // Modals
        this.editProfileModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    }
    
    initEventListeners() {
        // Sự kiện mở modal chỉnh sửa hồ sơ
        if (this.editProfileBtn) {
            this.editProfileBtn.addEventListener('click', () => this.openEditModal());
        }
        
        // Sự kiện lưu thông tin hồ sơ
        if (this.saveProfileBtn) {
            this.saveProfileBtn.addEventListener('click', () => this.saveProfile());
        }
        
        // Xử lý chọn file ảnh đại diện
        const avatarInput = document.getElementById('avatar');
        if (avatarInput) {
            avatarInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 2 * 1024 * 1024) { // 2MB
                        this.showToast('Ảnh đại diện không được vượt quá 2MB', 'error');
                        avatarInput.value = '';
                    } else if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                        this.showToast('Chỉ chấp nhận định dạng JPG hoặc PNG', 'error');
                        avatarInput.value = '';
                    } else {
                        // Hiển thị xem trước nếu hợp lệ
                        this.showImagePreview(file);
                    }
                }
            });
        }
        
        // Cải thiện UX của form bằng real-time validation
        this.setupFormValidation();
    }
    
    setupFormValidation() {
        const fullnameInput = document.getElementById('fullname');
        const phoneInput = document.getElementById('phone');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        
        if (fullnameInput) {
            fullnameInput.addEventListener('input', () => {
                this.validateInput(fullnameInput, fullnameInput.value.trim().length > 0, 
                    'Họ tên không được để trống');
            });
        }
        
        if (phoneInput) {
            phoneInput.addEventListener('input', () => {
                const isValid = /^[0-9]{10,11}$/.test(phoneInput.value.trim());
                this.validateInput(phoneInput, isValid, 'Số điện thoại phải có 10-11 chữ số');
            });
        }
        
        if (password && passwordConfirm) {
            // Kiểm tra độ mạnh của mật khẩu
            password.addEventListener('input', () => {
                if (password.value.length > 0) {
                    const strength = this.checkPasswordStrength(password.value);
                    this.showPasswordStrength(strength);
                    
                    // Kiểm tra xác nhận mật khẩu nếu đã có
                    if (passwordConfirm.value.length > 0) {
                        this.validateInput(passwordConfirm, 
                            password.value === passwordConfirm.value,
                            'Mật khẩu xác nhận không khớp');
                    }
                } else {
                    // Xóa thông báo độ mạnh mật khẩu
                    const strengthEl = document.getElementById('password-strength');
                    if (strengthEl) strengthEl.remove();
                }
            });
            
            // Kiểm tra mật khẩu xác nhận
            passwordConfirm.addEventListener('input', () => {
                if (passwordConfirm.value.length > 0) {
                    this.validateInput(passwordConfirm, 
                        password.value === passwordConfirm.value,
                        'Mật khẩu xác nhận không khớp');
                }
            });
        }
    }
    
    checkPasswordStrength(password) {
        if (password.length < 8) return { score: 1, text: 'Yếu', color: '#e74a3b' };
        
        let score = 0;
        // Kiểm tra có chữ thường
        if (/[a-z]/.test(password)) score++;
        // Kiểm tra có chữ hoa
        if (/[A-Z]/.test(password)) score++;
        // Kiểm tra có số
        if (/[0-9]/.test(password)) score++;
        // Kiểm tra có ký tự đặc biệt
        if (/[^A-Za-z0-9]/.test(password)) score++;
        
        if (score === 4) return { score: 5, text: 'Rất mạnh', color: '#1cc88a' };
        if (score === 3) return { score: 4, text: 'Mạnh', color: '#36b9cc' };
        if (score === 2) return { score: 3, text: 'Trung bình', color: '#f6c23e' };
        return { score: 2, text: 'Yếu', color: '#e74a3b' };
    }
    
    showPasswordStrength(strength) {
        let strengthEl = document.getElementById('password-strength');
        
        if (!strengthEl) {
            strengthEl = document.createElement('div');
            strengthEl.id = 'password-strength';
            strengthEl.className = 'password-strength mt-2';
            
            const password = document.getElementById('password');
            password.parentNode.appendChild(strengthEl);
        }
        
        const strengthBar = `
            <div class="strength-bar-container">
                <div class="strength-bar" style="width: ${strength.score * 20}%; background-color: ${strength.color};"></div>
            </div>
            <div class="strength-text" style="color: ${strength.color};">${strength.text}</div>
        `;
        
        strengthEl.innerHTML = strengthBar;
    }
    
    validateInput(input, isValid, errorMessage) {
        // Xóa thông báo lỗi cũ nếu có
        const existingFeedback = input.nextElementSibling;
        if (existingFeedback && existingFeedback.classList.contains('invalid-feedback')) {
            existingFeedback.remove();
        }
        
        if (!isValid) {
            // Thêm class lỗi và thông báo
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');
            
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = errorMessage;
            input.parentNode.appendChild(feedback);
        } else {
            // Đánh dấu hợp lệ
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
        
        return isValid;
    }
    
    showImagePreview(file) {
        // Kiểm tra đã có container chưa
        let previewContainer = document.getElementById('avatar-preview-container');
        
        if (!previewContainer) {
            // Tạo container nếu chưa có
            previewContainer = document.createElement('div');
            previewContainer.id = 'avatar-preview-container';
            previewContainer.className = 'avatar-preview-container mt-3 text-center';
            
            const avatarInput = document.getElementById('avatar');
            avatarInput.parentNode.appendChild(previewContainer);
        }
        
        // Đọc file và hiển thị
        const reader = new FileReader();
        reader.onload = function(e) {
            previewContainer.innerHTML = `
                <div class="avatar-preview">
                    <img src="${e.target.result}" alt="Xem trước ảnh đại diện" class="img-fluid rounded-circle">
                </div>
                <div class="avatar-preview-text mt-2">Ảnh được chọn</div>
            `;
        };
        reader.readAsDataURL(file);
    }
    
    initTooltips() {
        // Khởi tạo tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body
            });
        });
    }
    
    addEntryAnimations() {
        // Thêm hiệu ứng xuất hiện mượt mà cho các phần tử
        const profileHeader = document.querySelector('.profile-header');
        const profileDetail = document.querySelector('.profile-detail');
        const infoGroups = document.querySelectorAll('.info-group');
        
        if (profileHeader) {
            profileHeader.style.opacity = '0';
            profileHeader.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                profileHeader.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                profileHeader.style.opacity = '1';
                profileHeader.style.transform = 'translateY(0)';
            }, 100);
        }
        
        if (profileDetail) {
            profileDetail.style.opacity = '0';
            profileDetail.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                profileDetail.style.transition = 'all 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                profileDetail.style.opacity = '1';
                profileDetail.style.transform = 'translateY(0)';
            }, 400);
        }
        
        // Hiệu ứng xuất hiện từng nhóm thông tin
        infoGroups.forEach((group, index) => {
            group.style.opacity = '0';
            group.style.transform = 'translateX(-20px)';
            
            setTimeout(() => {
                group.style.transition = 'all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                group.style.opacity = '1';
                group.style.transform = 'translateX(0)';
            }, 600 + (index * 100)); // Tăng delay theo thứ tự
        });
    }
    
    openEditModal() {
        this.editProfileModal.show();
        
        // Hiệu ứng xuất hiện các trường trong modal
        setTimeout(() => {
            const formElements = document.querySelectorAll('.modal-body .form-control');
            formElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(10px)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.3s ease';
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, 100 + (index * 50));
            });
        }, 300);
    }
    
    saveProfile() {
        if (this.validateProfileForm()) {
            const formData = new FormData(this.editProfileForm);
            this.showLoading();
            
            // Hiệu ứng lưu
            this.saveProfileBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...';
            this.saveProfileBtn.disabled = true;
            
            fetch(API_CONFIG.profileUpdate, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': API_CONFIG.csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                this.hideLoading();
                
                if (data.success) {
                    this.showToast('Thông tin cá nhân đã được cập nhật', 'success');
                    
                    // Đóng modal với hiệu ứng
                    const modalBackdrop = document.querySelector('.modal-backdrop');
                    const modalContent = document.querySelector('.modal-content');
                    
                    if (modalContent) {
                        modalContent.style.transition = 'all 0.3s ease';
                        modalContent.style.transform = 'scale(0.9)';
                        modalContent.style.opacity = '0';
                    }
                    
                    setTimeout(() => {
                        this.editProfileModal.hide();
                        
                        // Hiệu ứng làm mới trang
                        const overlay = document.createElement('div');
                        overlay.className = 'refresh-overlay';
                        overlay.innerHTML = `
                            <div class="refresh-spinner">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p>Đang cập nhật...</p>
                            </div>
                        `;
                        document.body.appendChild(overlay);
                        
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }, 300);
                } else {
                    this.saveProfileBtn.innerHTML = 'Lưu thay đổi';
                    this.saveProfileBtn.disabled = false;
                    this.showToast(data.message || 'Có lỗi xảy ra. Vui lòng thử lại sau.', 'error');
                }
            })
            .catch(error => {
                this.hideLoading();
                this.saveProfileBtn.innerHTML = 'Lưu thay đổi';
                this.saveProfileBtn.disabled = false;
                console.error('Error:', error);
                this.showToast('Có lỗi xảy ra khi cập nhật thông tin', 'error');
            });
        }
    }
    
    validateProfileForm() {
        // Xác thực form cơ bản
        let isValid = true;
        
        const fullname = document.getElementById('fullname');
        const phone = document.getElementById('phone');
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirm');
        
        // Kiểm tra họ tên
        if (fullname) {
            isValid = this.validateInput(fullname, fullname.value.trim().length > 0, 
                'Vui lòng nhập họ và tên') && isValid;
        }
        
        // Kiểm tra số điện thoại
        if (phone) {
            const phoneValid = /^[0-9]{10,11}$/.test(phone.value.trim());
            isValid = this.validateInput(phone, phoneValid, 
                'Số điện thoại phải có 10-11 chữ số') && isValid;
        }
        
        // Kiểm tra mật khẩu nếu có
        if (password && passwordConfirm && password.value.length > 0) {
            // Kiểm tra độ dài mật khẩu
            isValid = this.validateInput(password, password.value.length >= 8, 
                'Mật khẩu phải có ít nhất 8 ký tự') && isValid;
            
            // Kiểm tra mật khẩu xác nhận
            isValid = this.validateInput(passwordConfirm, 
                password.value === passwordConfirm.value,
                'Mật khẩu xác nhận không khớp') && isValid;
        }
        
        return isValid;
    }
    
    showLoading() {
        // Tạo overlay loading
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'loading-overlay';
        loadingOverlay.innerHTML = `
            <div class="spinner-container">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang xử lý...</span>
                </div>
                <p>Đang xử lý...</p>
            </div>
        `;
        document.body.appendChild(loadingOverlay);
        
        // Hiệu ứng xuất hiện
        setTimeout(() => {
            loadingOverlay.style.opacity = '1';
        }, 10);
    }
    
    hideLoading() {
        // Xóa overlay loading với hiệu ứng
        const loadingOverlay = document.querySelector('.loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.style.opacity = '0';
            setTimeout(() => {
                loadingOverlay.remove();
            }, 300);
        }
    }
    
    showToast(message, type = 'info') {
        // Xóa toast cũ nếu có
        const existingToasts = document.querySelectorAll('.toast-notification');
        existingToasts.forEach(toast => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        });
        
        // Tạo element toast
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        
        // Icon cho toast
        let icon = '';
        switch (type) {
            case 'success':
                icon = '<i class="fas fa-check-circle"></i>';
                break;
            case 'error':
                icon = '<i class="fas fa-exclamation-circle"></i>';
                break;
            default:
                icon = '<i class="fas fa-info-circle"></i>';
        }
        
        // Nội dung toast
        toast.innerHTML = `
            ${icon}
            <div class="toast-message">${message}</div>
        `;
        
        // Thêm vào body
        document.body.appendChild(toast);
        
        // Hiển thị toast
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);
        
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }
}

// Thêm CSS cho toast, loading và các phần tử UI tương tác
document.addEventListener('DOMContentLoaded', function() {
    // CSS cho các phần tử
    const styleElement = document.createElement('style');
    styleElement.textContent = `
        .toast-notification {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: white;
            color: #333;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            padding: 18px 25px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            z-index: 9999;
            transform: translateY(30px);
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            max-width: 350px;
        }
        
        .toast-notification.show {
            transform: translateY(0);
            opacity: 1;
        }
        
        .toast-notification i {
            font-size: 1.7rem;
            margin-right: 15px;
        }
        
        .toast-message {
            font-size: 0.95rem;
            font-weight: 500;
        }
        
        .toast-success {
            border-left: 5px solid #1cc88a;
        }
        
        .toast-error {
            border-left: 5px solid #e74a3b;
        }
        
        .toast-info {
            border-left: 5px solid #4e73df;
        }
        
        .toast-success i {
            color: #1cc88a;
        }
        
        .toast-error i {
            color: #e74a3b;
        }
        
        .toast-info i {
            color: #4e73df;
        }
        
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .spinner-container {
            text-align: center;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(78, 115, 223, 0.1);
            animation: scaleIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        @keyframes scaleIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 0.25rem;
        }
        
        .spinner-container p {
            margin-top: 20px;
            color: #4e73df;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        /* Styles cho form validation */
        .is-invalid {
            border-color: #e74a3b !important;
            padding-right: calc(1.5em + 0.75rem) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%23e74a3b' viewBox='0 0 12 12'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23e74a3b' stroke='none'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right calc(0.375em + 0.1875rem) center !important;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
        }
        
        .is-valid {
            border-color: #1cc88a !important;
            padding-right: calc(1.5em + 0.75rem) !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 8 8'%3e%3cpath fill='%231cc88a' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right calc(0.375em + 0.1875rem) center !important;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem) !important;
        }
        
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            font-size: 0.875rem;
            color: #e74a3b;
        }
        
        /* Avatar preview */
        .avatar-preview-container {
            margin-top: 15px;
            animation: fadeIn 0.5s ease;
        }
        
        .avatar-preview {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto;
            border: 5px solid rgba(78, 115, 223, 0.1);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .avatar-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .avatar-preview-text {
            font-size: 0.9rem;
            color: #4e73df;
            font-weight: 600;
        }
        
        /* Password strength */
        .password-strength {
            margin-top: 10px;
            font-size: 0.875rem;
        }
        
        .strength-bar-container {
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            margin-bottom: 5px;
            overflow: hidden;
        }
        
        .strength-bar {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        .strength-text {
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        /* Refresh effect */
        .refresh-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            -webkit-backdrop-filter: blur(5px);
            backdrop-filter: blur(5px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }
        
        .refresh-spinner {
            text-align: center;
        }
        
        .refresh-spinner p {
            margin-top: 15px;
            color: #4e73df;
            font-weight: 600;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    `;
    document.head.appendChild(styleElement);
    
    // Khởi tạo trang
    profilePage = new ProfilePage();
});