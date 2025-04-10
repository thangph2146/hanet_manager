<?php
// Include form component
include APPPATH . 'Views/components/_form.php';

// Định nghĩa form đăng ký sự kiện
$formElements = [
    [
        'type' => 'hidden',
        'name' => 'su_kien_id',
        'value' => $event['su_kien_id'] ?? ''
    ],
    [
        'type' => 'hidden',
        'name' => 'nguoi_dung_id',
        'value' => session()->get('nguoi_dung_id') ?? ''
    ],
    // Row 1: Họ tên và Email
    [
        'isRow' => true,
        'rowClass' => 'row g-3',
        'elements' => [
            [
                'type' => 'text',
                'name' => 'ho_ten',
                'label' => 'Họ và tên <span class="text-danger">*</span>',
                'placeholder' => 'Họ và tên',
                'class' => 'form-control',
                'required' => true,
                'error' => 'Vui lòng nhập họ và tên',
                'floating' => true,
                'colClass' => 'col-md-6',
                'pattern' => '^[a-zA-ZÀ-ỹ\s]{2,}$',
                'data-validate' => 'name'
            ],
            [
                'type' => 'email',
                'name' => 'email',
                'label' => 'Email <span class="text-danger">*</span>',
                'placeholder' => 'Email',
                'class' => 'form-control',
                'required' => true,
                'error' => 'Vui lòng nhập email hợp lệ',
                'floating' => true,
                'colClass' => 'col-md-6',
                'data-validate' => 'email'
            ]
        ]
    ],
    // Row 2: Số điện thoại và Mã sinh viên
    [
        'isRow' => true,
        'rowClass' => 'row g-3',
        'elements' => [
            [
                'type' => 'tel',
                'name' => 'dien_thoai',
                'label' => 'Số điện thoại <span class="text-danger">*</span>',
                'placeholder' => 'Số điện thoại',
                'class' => 'form-control',
                'required' => true,
                'error' => 'Vui lòng nhập số điện thoại hợp lệ (10-15 số)',
                'floating' => true,
                'colClass' => 'col-md-6',
                'pattern' => '^[0-9]{10,15}$',
                'data-validate' => 'phone'
            ],
            [
                'type' => 'text',
                'name' => 'nguoi_dung_id',
                'label' => 'Mã sinh viên (nếu có)',
                'placeholder' => 'Mã sinh viên',
                'class' => 'form-control',
                'floating' => true,
                'colClass' => 'col-md-6',
                'pattern' => '^[0-9]{8,10}$',
                'data-validate' => 'student_id'
            ]
        ]
    ],
    // Row 3: Loại người đăng ký và Chức danh
    [
        'isRow' => true,
        'rowClass' => 'row g-3',
        'elements' => [
            [
                'type' => 'select',
                'name' => 'loai_nguoi_dang_ky',
                'label' => 'Bạn là <span class="text-danger">*</span>',
                'class' => 'form-select',
                'required' => true,
                'options' => [
                    '' => 'Chọn loại người đăng ký',
                    'sinh_vien' => 'Sinh viên',
                    'giang_vien' => 'Giảng viên',
                    'khach' => 'Khách'
                ],
                'error' => 'Vui lòng chọn loại người đăng ký',
                'floating' => true,
                'colClass' => 'col-md-6',
                'data-validate' => 'registration_type'
            ],
            [
                'type' => 'text',
                'name' => 'chuc_danh',
                'label' => 'Chức danh/Vai trò (nếu có)',
                'placeholder' => 'Chức danh/Vai trò',
                'class' => 'form-control',
                'floating' => true,
                'colClass' => 'col-md-6'
            ]
        ]
    ],
    // Row 4: Trình độ học vấn và Nguồn giới thiệu
    [
        'isRow' => true,
        'rowClass' => 'row g-3',
        'elements' => [
            [
                'type' => 'select',
                'name' => 'trinh_do_hoc_van',
                'label' => 'Trình độ học vấn',
                'class' => 'form-select',
                'options' => [
                    '' => 'Chọn trình độ học vấn',
                    'Trung học' => 'Trung học',
                    'Trung cấp' => 'Trung cấp',
                    'Cao đẳng' => 'Cao đẳng',
                    'Đại học' => 'Đại học',
                    'Thạc sĩ' => 'Thạc sĩ',
                    'Tiến sĩ' => 'Tiến sĩ',
                    'Khác' => 'Khác'
                ],
                'floating' => true,
                'colClass' => 'col-md-6'
            ],
            [
                'type' => 'select',
                'name' => 'nguon_gioi_thieu',
                'label' => 'Bạn biết đến sự kiện này từ đâu?',
                'class' => 'form-select',
                'options' => [
                    '' => 'Chọn nguồn thông tin',
                    'Website trường' => 'Website trường',
                    'Facebook' => 'Facebook',
                    'Email từ trường' => 'Email từ trường',
                    'Bạn bè' => 'Bạn bè',
                    'Khác' => 'Khác'
                ],
                'floating' => true,
                'wrapperClass' => 'mb-4'
            ]
        ]
    ],
    // Góp ý/Câu hỏi
    [
        'type' => 'textarea',
        'name' => 'noi_dung_gop_y',
        'label' => 'Góp ý/Câu hỏi (nếu có)',
        'placeholder' => 'Nội dung góp ý',
        'class' => 'form-control',
        'style' => 'height: 100px',
        'floating' => true,
        'wrapperClass' => 'mb-4',
        'maxlength' => '500'
    ],
    // Đồng ý điều khoản
    [
        'type' => 'checkbox',
        'name' => 'agree_terms',
        'label' => 'Tôi đồng ý với <a href="javascript:void(0)" id="showTermsBtn" class="text-decoration-none fw-bold text-danger">điều khoản tham gia</a> của sự kiện',
        'required' => true,
        'error' => 'Bạn phải đồng ý với điều khoản để tiếp tục',
        'class' => 'form-check-input',
        'wrapperClass' => 'mb-4'
    ]
];

// Wrap form trong card
echo '<div class="card border-0 shadow-sm mb-4">';
echo '<div class="card-header bg-danger bg-gradient text-white py-3">';
echo '<h5 class="card-title mb-0 text-white"><i class="bi bi-person-plus-fill me-2"></i>Thông tin đăng ký</h5>';
echo '</div>';
echo '<div class="card-body p-4">';

// Render form
renderForm([
    'method' => 'POST',
    'action' => site_url('su-kien/register'),
    'attributes' => [
        'id' => 'registration-form',
        'class' => 'needs-validation',
        'novalidate' => 'novalidate'
    ],
    'layout' => [
        'useGrid' => true,
        'gridClass' => 'row g-3',
        'useFloatingLabels' => false
    ],
    'buttonOptions' => [
        'submitText' => '<i class="bi bi-check-circle-fill me-2"></i>Đăng ký tham gia',
        'showReset' => false,
        'submitClass' => 'btn btn-danger btn-lg rounded-pill',
        'wrapperClass' => 'd-grid',
        'containerClass' => 'mb-3'
    ],
    'elements' => $formElements
]);

echo '</div>'; // card-body
echo '</div>'; // card

// Sử dụng component modal chung
$modalParams = [
    'id' => 'termsModal',
    'title' => '<i class="bi bi-file-text me-2"></i>Điều khoản tham gia sự kiện',
    'size' => 'lg',
    'closeBtn' => true,
    'saveBtn' => false,
    'container' => true,
    'centered' => true,
    'scrollable' => true,
    'backdrop' => 'static',
    'keyboard' => false,
    'content' => '
<div class="alert alert-danger mb-4">
    <i class="bi bi-info-circle-fill me-2"></i> Vui lòng đọc kỹ các điều khoản sau trước khi đăng ký tham gia sự kiện.
</div>

<div class="card mb-3 border-start border-danger border-4 shadow-sm">
    <div class="card-body">
        <h6 class="card-title"><i class="bi bi-1-circle-fill me-2 text-danger"></i>Quy định chung</h6>
        <p class="card-text">Người tham gia cần tuân thủ các quy định của Ban tổ chức và địa điểm tổ chức sự kiện.</p>
    </div>
</div>

<div class="card mb-3 border-start border-danger border-4 shadow-sm">
    <div class="card-body">
        <h6 class="card-title"><i class="bi bi-2-circle-fill me-2 text-danger"></i>Đăng ký và xác nhận</h6>
        <p class="card-text">Việc đăng ký tham gia sự kiện sẽ được xác nhận qua email. Người tham gia cần mang theo email xác nhận khi đến tham dự sự kiện.</p>
    </div>
</div>

<div class="card mb-3 border-start border-danger border-4 shadow-sm">
    <div class="card-body">
        <h6 class="card-title"><i class="bi bi-3-circle-fill me-2 text-danger"></i>Điểm danh và chứng nhận</h6>
        <p class="card-text">Người tham gia cần điểm danh khi đến tham dự sự kiện. Chứng nhận tham gia (nếu có) sẽ chỉ được cấp cho những người tham dự đầy đủ chương trình.</p>
    </div>
</div>

<div class="card mb-3 border-start border-danger border-4 shadow-sm">
    <div class="card-body">
        <h6 class="card-title"><i class="bi bi-4-circle-fill me-2 text-danger"></i>Quyền riêng tư và hình ảnh</h6>
        <p class="card-text">Ban tổ chức có quyền sử dụng hình ảnh, video được ghi lại trong sự kiện cho mục đích truyền thông và báo cáo.</p>
    </div>
</div>

<div class="card mb-3 border-start border-danger border-4 shadow-sm">
    <div class="card-body">
        <h6 class="card-title"><i class="bi bi-5-circle-fill me-2 text-danger"></i>Hủy đăng ký</h6>
        <p class="card-text">Nếu không thể tham gia, người đăng ký vui lòng thông báo cho Ban tổ chức ít nhất 24 giờ trước khi sự kiện diễn ra.</p>
    </div>
</div>
',
    'footer' => '
<button type="button" class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">
    <i class="bi bi-x-circle me-2"></i>Đóng
</button>
<button type="button" class="btn btn-danger rounded-pill" id="agreeTermsBtn">
    <i class="bi bi-check-circle me-2"></i>Đồng ý với điều khoản
</button>
'
];

// Include modal component
include APPPATH . 'Views/components/_modal.php';
?>

<!-- Script để xử lý validate form và hiệu ứng -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize particles.js only if the container exists
    const particlesContainer = document.getElementById('particles-js');
    if (particlesContainer) {
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: '#ffffff'
                },
                shape: {
                    type: 'circle'
                },
                opacity: {
                    value: 0.5,
                    random: false
                },
                size: {
                    value: 3,
                    random: true
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#ffffff',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 6,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'repulse'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                }
            },
            retina_detect: true
        });
    }

    // Initialize countdown only if the element exists
    const countdownElement = document.getElementById('countdown');
    if (countdownElement) {
        function updateCountdown() {
            const eventDate = new Date(countdownElement.getAttribute('data-event-date'));
            const now = new Date();
            const diff = eventDate - now;

            if (diff <= 0) {
                countdownElement.innerHTML = 'Sự kiện đã bắt đầu!';
                return;
            }

            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            countdownElement.innerHTML = `
                <div class="countdown-item">
                    <span class="countdown-value">${days}</span>
                    <span class="countdown-label">Ngày</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value">${hours}</span>
                    <span class="countdown-label">Giờ</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value">${minutes}</span>
                    <span class="countdown-label">Phút</span>
                </div>
                <div class="countdown-item">
                    <span class="countdown-value">${seconds}</span>
                    <span class="countdown-label">Giây</span>
                </div>
            `;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }

    // Form validation
    const registrationForm = document.getElementById('registration-form');
    if (registrationForm) {
        registrationForm.addEventListener('submit', function(e) {
            const phoneInput = document.getElementById('dien_thoai');
            const phoneValue = phoneInput.value.trim();
            const phoneRegex = /^[0-9]{10,15}$/;

            if (!phoneRegex.test(phoneValue)) {
                e.preventDefault();
                alert('Vui lòng nhập số điện thoại hợp lệ (10-15 số)');
                phoneInput.focus();
                return false;
            }
        });
    }

    // Khởi tạo bootstrap modal
    const termsModal = new bootstrap.Modal(document.getElementById('termsModal'), {
        backdrop: 'static',  // Không đóng modal khi click bên ngoài
        keyboard: false      // Không đóng modal khi ấn Esc
    });
    
    // Sự kiện khi click vào hiển thị điều khoản
    document.getElementById('showTermsBtn').addEventListener('click', function() {
        termsModal.show();
    });
    
    // Hiển thị/ẩn các trường dựa trên loại người đăng ký
    const loaiNguoiDangKySelect = document.getElementById('loai_nguoi_dang_ky');
    const studentIdField = document.getElementById('nguoi_dung_id');
    
    loaiNguoiDangKySelect.addEventListener('change', function() {
        if (this.value === 'sinh_vien') {
            studentIdField.setAttribute('required', 'required');
            studentIdField.closest('.form-floating').querySelector('label').innerHTML = 'Mã sinh viên <span class="text-danger">*</span>';
        } else {
            studentIdField.removeAttribute('required');
            studentIdField.closest('.form-floating').querySelector('label').innerHTML = 'Mã sinh viên (nếu có)';
        }
    });
    
    // Form validation
    const form = document.getElementById('registration-form');
    const submitBtn = document.querySelector('#registration-form button[type="submit"]');
    const loadingSpinner = document.getElementById('loading-spinner') || document.createElement('span');
    let isSubmitting = false; // Biến theo dõi trạng thái đang gửi form
    let hasValidationError = false; // Biến theo dõi trạng thái lỗi validation
    
    // Hàm đặt lại trạng thái nút
    function resetButtonState() {
        submitBtn.disabled = false;
        if (loadingSpinner.parentNode) {
        loadingSpinner.classList.add('d-none');
        }
        submitBtn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Đăng ký tham gia';
        isSubmitting = false;
        hasValidationError = false;
    }
    
    // Hàm hiển thị trạng thái loading
    function showLoadingState() {
        if (hasValidationError) {
            console.log('Không hiển thị loading vì có lỗi validation');
            return;
        }
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Đang xử lý...';
    }
    
    // Reset nút mỗi khi DOM được tải
    resetButtonState();
    
    // Kiểm soát việc nút submit bị vô hiệu hóa
    document.addEventListener('invalid', function(e) {
        // Đảm bảo nút không bị vô hiệu hóa khi có lỗi validation
        if (form.contains(e.target)) {
            // Reset nút về trạng thái ban đầu
            setTimeout(resetButtonState, 0);
        }
    }, true);
    
    // Tự động reset nút submit khi có thao tác trên form
    form.addEventListener('click', function() {
        if (hasValidationError || isSubmitting) {
            resetButtonState();
        }
    });
    
    // Xử lý validation và hiển thị lỗi
    function checkAndResetSubmitButton() {
        // Kiểm tra nếu bất kỳ field nào không hợp lệ
        const invalidFields = form.querySelectorAll(':invalid');
        
        if (invalidFields.length > 0) {
            console.log('Đã phát hiện', invalidFields.length, 'field không hợp lệ');
            hasValidationError = true;
            resetButtonState();
            return false;
        }
        
        return true;
    }
    
    // Bắt sự kiện invalid ở cấp document
    document.addEventListener('invalid', function(e) {
        if (form.contains(e.target)) {
            console.log('Phát hiện lỗi validation ở field:', e.target.name || e.target.id);
            hasValidationError = true;
            resetButtonState();
            
            // Ngăn thông báo mặc định của trình duyệt
            e.preventDefault();
            
            // Đánh dấu field là invalid
            e.target.classList.add('is-invalid');
            
            // Đánh dấu form đã validate
            form.classList.add('was-validated');
        }
    }, true); // Capture phase
    
    // Xử lý kiểm tra validation và hiển thị lỗi
    function validateForm(showErrors = true) {
        // Thêm class was-validated để hiển thị feedback
        if (showErrors) {
            form.classList.add('was-validated');
        }
        
        // Kiểm tra tính hợp lệ
        const isValid = form.checkValidity();
        
        if (!isValid) {
            // Đánh dấu có lỗi validation
            hasValidationError = true;
            
            if (showErrors) {
                // Hiệu ứng rung khi có lỗi
                form.classList.add('animate__animated', 'animate__shakeX');
                setTimeout(() => {
                    form.classList.remove('animate__animated', 'animate__shakeX');
                }, 1000);
                
                // Cuộn đến trường lỗi đầu tiên
                const firstInvalidField = form.querySelector(':invalid');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
            
            // Luôn tắt trạng thái loading khi có lỗi
            resetButtonState();
        } else {
            hasValidationError = false;
        }
        
        return isValid;
    }
    
    // Kiểm tra field có hợp lệ không và reset nút nếu cần
    function validateField(field) {
        // Kiểm tra field có valid không
        const isValid = field.checkValidity();
        
        // Nếu field không hợp lệ, đánh dấu và reset nút
        if (!isValid) {
            field.classList.add('is-invalid');
            hasValidationError = true;
            
            // Nếu đang trong trạng thái submit, reset nút
            if (isSubmitting) {
                resetButtonState();
            }
        } else {
            field.classList.remove('is-invalid');
            field.classList.add('is-valid');
        }
        
        return isValid;
    }
    
    form.addEventListener('submit', function(event) {
        // Luôn ngăn gửi form mặc định trước khi kiểm tra
        event.preventDefault();
        
        // Nếu đang trong quá trình gửi, không làm gì cả
        if (isSubmitting) return;
        
        // Reset biến tracking
        hasValidationError = false;
        
        // Đánh dấu đang bắt đầu gửi
        isSubmitting = true;
        
        // Hiển thị trạng thái đang xử lý trước khi kiểm tra
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Đang xử lý...';
        
        // Kiểm tra form ngay lập tức
        let isValid = form.checkValidity();
        
        // Nếu không hợp lệ, đánh dấu và hiển thị lỗi
        if (!isValid) {
            hasValidationError = true;
            form.classList.add('was-validated');
            
            // Kiểm tra các field và highlight lỗi
            const invalidFields = form.querySelectorAll(':invalid');
            invalidFields.forEach(field => {
                field.classList.add('is-invalid');
            });
            
            // Reset trạng thái nút ngay lập tức
            setTimeout(function() {
                resetButtonState();
                
                // Hiệu ứng rung form khi có lỗi
                form.classList.add('animate__animated', 'animate__shakeX');
                setTimeout(() => {
                    form.classList.remove('animate__animated', 'animate__shakeX');
                }, 1000);
                
                // Cuộn đến trường lỗi đầu tiên
                const firstInvalidField = form.querySelector(':invalid');
                if (firstInvalidField) {
                    firstInvalidField.focus();
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 50);
            
            return;
        }
        
        // Nếu không có lỗi, tiếp tục giữ trạng thái loading và gửi form
        console.log('Form hợp lệ, đang gửi...');
        try {
            setTimeout(function() {
                form.submit();
            }, 50);
        } catch (e) {
            console.error('Form submission error:', e);
            resetButtonState();
        }
    });
    
    // Kiểm tra validation ngay khi người dùng tương tác với các field
    form.querySelectorAll('input, select, textarea').forEach(element => {
        // Xử lý khi nhập liệu
        element.addEventListener('input', function() {
            // Nếu form đã được đánh dấu là validated, kiểm tra field này
            if (form.classList.contains('was-validated')) {
                validateField(this);
            }
            
            // Luôn reset nút nếu đang trong trạng thái submit
            if (isSubmitting) {
                resetButtonState();
            }
        });
        
        // Xử lý khi thay đổi giá trị
        element.addEventListener('change', function() {
            validateField(this);
            
            // Reset nút nếu đang trong trạng thái submit
            if (isSubmitting) {
                resetButtonState();
            }
        });
        
        // Xử lý khi rời khỏi field
        element.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Xử lý khi nhấn Enter
        element.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                
                // Validate field hiện tại trước
                if (!validateField(this)) {
                    // Field không hợp lệ, không làm gì cả
                    return;
                }
                
                // Nếu không đang trong quá trình submit, thực hiện submit
                if (!isSubmitting) {
                    submitBtn.click();
                }
            }
        });
    });
    
    // Theo dõi thay đổi class của form
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'attributes' && 
                mutation.attributeName === 'class' &&
                form.classList.contains('was-validated')) {
                
                // Kiểm tra lại nút submit khi form được đánh dấu là validated
                checkAndResetSubmitButton();
            }
        });
    });
    
    // Bắt đầu theo dõi form
    observer.observe(form, { attributes: true });
    
    // Xử lý khi window trước khi unload
    window.addEventListener('beforeunload', function() {
        resetButtonState();
    });
    
    // Xử lý đồng ý điều khoản từ modal
    document.getElementById('agreeTermsBtn').addEventListener('click', function() {
        const checkbox = document.getElementById('agree_terms');
        checkbox.checked = true;
        checkbox.dispatchEvent(new Event('change'));
        termsModal.hide();
        
        // Thêm hiệu ứng highlight cho checkbox khi đồng ý
        const checkboxLabel = document.querySelector('label[for="agree_terms"]');
        checkboxLabel.classList.add('text-success', 'fw-bold');
        setTimeout(() => {
            checkboxLabel.classList.remove('text-success', 'fw-bold');
        }, 2000);
    });
    
    // Highlight cho input khi focus
    const formControls = document.querySelectorAll('.form-control, .form-select');
    formControls.forEach(element => {
        element.addEventListener('focus', function() {
            this.parentElement.classList.add('shadow-sm', 'border-danger');
        });
        element.addEventListener('blur', function() {
            this.parentElement.classList.remove('shadow-sm', 'border-danger');
        });
    });
    
    // Kiểm tra tự động form khi thay đổi giá trị
    const inputs = form.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.checkValidity()) {
                this.classList.add('is-valid');
                this.classList.remove('is-invalid');
            } else {
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            }
        });
    });
});
</script>

<!-- Thêm CSS cho animation -->
<style>
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
    20%, 40%, 60%, 80% { transform: translateX(5px); }
}

.animate__shakeX {
    animation: shake 0.8s;
}

.form-floating input:focus,
.form-floating select:focus,
.form-floating textarea:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
}

.modal-content {
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Thêm màu đỏ đô tùy chỉnh */
:root {
    --bs-danger-rgb: 128, 0, 0;
}

.bg-danger {
    background-color: #800000 !important;
}

.btn-danger {
    background-color: #800000;
    border-color: #800000;
}

.btn-danger:hover {
    background-color: #600000;
    border-color: #600000;
}

.text-danger {
    color: #800000 !important;
}

.border-danger {
    border-color: #800000 !important;
}

.form-check-input:checked {
    background-color: #800000;
    border-color: #800000;
}
</style>

<div class="countdown-wrapper">
    <div id="countdown" data-event-date="<?= isset($event['thoi_gian_bat_dau']) ? date('c', strtotime($event['thoi_gian_bat_dau'])) : '' ?>"></div>
</div>
