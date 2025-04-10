/**
 * JavaScript cho trang chi tiết sự kiện
 */
document.addEventListener('DOMContentLoaded', function() {
    // Thêm class pulse cho nút đăng ký
    const registerBtn = document.querySelector('.card .btn-primary');
    if (registerBtn) {
        registerBtn.classList.add('pulse');
    }
    
    // Xử lý chuyển tab khi click vào nút đăng ký
    const registerButtons = document.querySelectorAll('[data-bs-toggle="tab"][data-bs-target="#event-registration"]');
    registerButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tabEl = document.querySelector('#event-registration-tab');
            const tab = new bootstrap.Tab(tabEl);
            tab.show();
            
            // Cuộn đến form đăng ký
            setTimeout(() => {
                const formElement = document.querySelector('#registration-form');
                if (formElement) {
                    formElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }, 300);
        });
    });
    
    // Xử lý tìm kiếm người tham gia
    const searchInput = document.querySelector('#search-participant');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('#event-participants table tbody tr');
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Xử lý lọc trạng thái người tham gia
    const filterStatus = document.querySelector('#filter-status');
    if (filterStatus) {
        filterStatus.addEventListener('change', function() {
            const selectedValue = this.value;
            const tableRows = document.querySelectorAll('#event-participants table tbody tr');
            
            tableRows.forEach(row => {
                if (selectedValue === 'all') {
                    row.style.display = '';
                    return;
                }
                
                const statusCell = row.querySelector('td:last-child span');
                if (statusCell) {
                    const statusText = statusCell.textContent.toLowerCase();
                    
                    if (
                        (selectedValue === 'registered' && statusText === 'đã đăng ký') ||
                        (selectedValue === 'attended' && statusText === 'đã điểm danh') ||
                        (selectedValue === 'absent' && statusText === 'vắng mặt')
                    ) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        });
    }
    
    // Xử lý form đăng ký
    const registrationForm = document.querySelector('#registration-form');
    if (registrationForm) {
        // Thêm hiệu ứng khi focus vào input
        const formInputs = registrationForm.querySelectorAll('.form-control, .form-select');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('input-focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('input-focused');
            });
        });
        
        // Xử lý submit form
        registrationForm.addEventListener('submit', function(e) {
            // Kiểm tra form trước khi submit
            const hoTen = document.querySelector('#ho_ten').value.trim();
            const email = document.querySelector('#email').value.trim();
            const dienThoai = document.querySelector('#dien_thoai').value.trim();
            const loaiNguoiDangKy = document.querySelector('#loai_nguoi_dang_ky').value;
            const maSinhVien = document.querySelector('#nguoi_dung_id')?.value.trim();
            const agreeTerms = document.querySelector('#agree_terms').checked;
            
            let isValid = true;
            let errorMessage = '';
            
            // Kiểm tra họ tên
            if (!hoTen) {
                isValid = false;
                errorMessage += 'Vui lòng nhập họ tên. ';
                highlightError('#ho_ten');
            } else if (!/^[a-zA-ZÀ-ỹ\s]{2,}$/.test(hoTen)) {
                isValid = false;
                errorMessage += 'Họ tên không hợp lệ. ';
                highlightError('#ho_ten');
            }
            
            // Kiểm tra email
            if (!email) {
                isValid = false;
                errorMessage += 'Vui lòng nhập email. ';
                highlightError('#email');
            } else if (!isValidEmail(email)) {
                isValid = false;
                errorMessage += 'Email không hợp lệ. ';
                highlightError('#email');
            }
            
            // Kiểm tra số điện thoại
            if (!dienThoai) {
                isValid = false;
                errorMessage += 'Vui lòng nhập số điện thoại. ';
                highlightError('#dien_thoai');
            } else if (!isValidPhone(dienThoai)) {
                isValid = false;
                errorMessage += 'Số điện thoại không hợp lệ. ';
                highlightError('#dien_thoai');
            }
            
            // Kiểm tra loại người đăng ký
            if (!loaiNguoiDangKy) {
                isValid = false;
                errorMessage += 'Vui lòng chọn loại người đăng ký. ';
                highlightError('#loai_nguoi_dang_ky');
            }
            
            // Kiểm tra mã sinh viên nếu là sinh viên
            if (loaiNguoiDangKy === 'sinh_vien') {
                if (!maSinhVien) {
                    isValid = false;
                    errorMessage += 'Vui lòng nhập mã sinh viên. ';
                    highlightError('#nguoi_dung_id');
                } else if (!/^[0-9]{8,10}$/.test(maSinhVien)) {
                    isValid = false;
                    errorMessage += 'Mã sinh viên không hợp lệ. ';
                    highlightError('#nguoi_dung_id');
                }
            }
            
            // Kiểm tra điều khoản
            if (!agreeTerms) {
                isValid = false;
                errorMessage += 'Vui lòng đồng ý với điều khoản tham gia. ';
                highlightError('#agree_terms');
            }
            
            if (!isValid) {
                e.preventDefault();
                showErrorAlert(errorMessage);
                
                // Hiệu ứng rung form khi có lỗi
                registrationForm.classList.add('animate__animated', 'animate__shakeX');
                setTimeout(() => {
                    registrationForm.classList.remove('animate__animated', 'animate__shakeX');
                }, 1000);
                
                // Cuộn đến trường lỗi đầu tiên
                const firstInvalidField = registrationForm.querySelector('.is-invalid');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            } else {
                // Hiển thị loading khi submit form
                showLoadingOverlay();
            }
        });
    }
    
    // Hàm kiểm tra email hợp lệ
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
    
    // Hàm kiểm tra số điện thoại hợp lệ
    function isValidPhone(phone) {
        const re = /^(0|\+84)(\s|\.)?((3[2-9])|(5[689])|(7[06-9])|(8[1-689])|(9[0-46-9]))(\d)(\s|\.)?(\d{3})(\s|\.)?(\d{3})$/;
        return re.test(phone);
    }
    
    // Hàm highlight lỗi input
    function highlightError(selector) {
        const element = document.querySelector(selector);
        if (element) {
            element.classList.add('is-invalid');
            element.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            }, { once: true });
        }
    }
    
    // Hàm hiển thị thông báo lỗi
    function showErrorAlert(message) {
        const alertHtml = `
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <strong>Lỗi!</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        const formElement = document.querySelector('#registration-form');
        if (formElement) {
            formElement.insertAdjacentHTML('beforebegin', alertHtml);
            
            // Tự động ẩn thông báo sau 5 giây
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }
    }
    
    // Hàm hiển thị loading overlay
    function showLoadingOverlay() {
        const loadingHtml = `
            <div class="loading-overlay">
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Đang xử lý...</span>
                </div>
                <p class="mt-2 text-light">Đang xử lý đăng ký...</p>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', loadingHtml);
    }
    
    // Xử lý hiệu ứng khi cuộn trang
    window.addEventListener('scroll', function() {
        const scrollPosition = window.scrollY;
        
        // Hiệu ứng parallax cho banner
        const banner = document.querySelector('.event-banner img');
        if (banner) {
            banner.style.transform = `translateY(${scrollPosition * 0.1}px)`;
        }
        
        // Hiển thị nút "Đăng ký ngay" cố định khi cuộn xuống
        const registerButton = document.querySelector('.fixed-register-btn');
        if (registerButton) {
            if (scrollPosition > 300) {
                registerButton.classList.add('show');
            } else {
                registerButton.classList.remove('show');
            }
        }
        
        // Hiệu ứng xuất hiện cho các phần tử khi cuộn đến
        const animateElements = document.querySelectorAll('.schedule-item, .speaker-card, .topic-item, .stats-card');
        animateElements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const windowHeight = window.innerHeight;
            
            if (elementPosition < windowHeight - 100) {
                element.classList.add('animate__animated', 'animate__fadeInUp');
            }
        });
    });
    
    // Thêm hiệu ứng hover cho các thẻ
    const tabLinks = document.querySelectorAll('.nav-tabs .nav-link');
    tabLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(-3px)';
            }
        });
        
        link.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
    
    // Thêm hiệu ứng đếm số
    const statsNumbers = document.querySelectorAll('.stats-number');
    statsNumbers.forEach(element => {
        const finalValue = parseInt(element.textContent);
        let startValue = 0;
        const duration = 2000;
        const step = Math.ceil(finalValue / (duration / 20));
        
        function updateNumber() {
            startValue += step;
            if (startValue > finalValue) {
                element.textContent = finalValue;
            } else {
                element.textContent = startValue;
                requestAnimationFrame(updateNumber);
            }
        }
        
        // Bắt đầu đếm khi phần tử hiển thị trong viewport
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    updateNumber();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(element);
    });
}); 