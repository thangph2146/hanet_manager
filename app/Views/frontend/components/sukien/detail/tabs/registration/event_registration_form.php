<!-- Form đăng ký sự kiện với UX/UI đã tối ưu -->
<form action="<?= site_url('su-kien/register') ?>" method="post" id="registration-form" class="needs-validation" novalidate>
    <input type="hidden" name="sukien_id" value="<?= $event['id_su_kien'] ?>">
    <input type="hidden" name="user_id" value="<?= session()->get('user_id') ?? '' ?>">
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-danger bg-gradient text-white py-3">
            <h5 class="card-title mb-0 text-white"><i class="bi bi-person-plus-fill me-2"></i>Thông tin đăng ký</h5>
        </div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="ho_ten" name="ho_ten" required>
                        <label for="ho_ten">Họ và tên <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">Vui lòng nhập họ và tên</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="email" name="email" required>
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">Vui lòng nhập email hợp lệ</div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai" required>
                        <label for="so_dien_thoai">Số điện thoại <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">Vui lòng nhập số điện thoại hợp lệ (10-11 số)</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="student_id" name="student_id">
                        <label for="student_id">Mã sinh viên (nếu có)</label>
                    </div>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="loai_nguoi_dang_ky" name="loai_nguoi_dang_ky" required>
                            <option value="" selected disabled>Chọn loại người đăng ký</optio   n>
                            <option value="khach">Khách</option>
                            <option value="sinh_vien">Sinh viên</option>
                            <option value="giang_vien">Giảng viên</option>
                        </select>
                        <label for="loai_nguoi_dang_ky">Bạn là <span class="text-danger">*</span></label>
                        <div class="invalid-feedback">Vui lòng chọn loại người đăng ký</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="loai_nguoi_dung" name="loai_nguoi_dung">
                        <label for="loai_nguoi_dung">Chức danh/Vai trò (nếu có)</label>
                    </div>
                </div>
            </div>
            
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="trinh_do_hoc_van" name="trinh_do_hoc_van">
                            <option value="" selected disabled>Chọn trình độ học vấn</option>
                            <option value="Trung học">Trung học</option>
                            <option value="Trung cấp">Trung cấp</option>
                            <option value="Cao đẳng">Cao đẳng</option>
                            <option value="Đại học">Đại học</option>
                            <option value="Thạc sĩ">Thạc sĩ</option>
                            <option value="Tiến sĩ">Tiến sĩ</option>
                            <option value="Khác">Khác</option>
                        </select>
                        <label for="trinh_do_hoc_van">Trình độ học vấn</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select class="form-select" id="nguon_gioi_thieu" name="nguon_gioi_thieu">
                            <option value="" selected disabled>Chọn nguồn thông tin</option>
                            <option value="Website trường">Website trường</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Email từ trường">Email từ trường</option>
                            <option value="Bạn bè">Bạn bè</option>
                            <option value="Khác">Khác</option>
                        </select>
                        <label for="nguon_gioi_thieu">Bạn biết đến sự kiện này từ đâu?</label>
                    </div>
                </div>
            </div>
            
            <div class="form-floating mb-4">
                <textarea class="form-control" id="noi_dung_gop_y" name="noi_dung_gop_y" rows="3" style="height: 100px" placeholder="Nội dung góp ý"></textarea>
                <label for="noi_dung_gop_y">Góp ý/Câu hỏi (nếu có)</label>
            </div>
            
            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="agree_terms" name="agree_terms" required>
                <label class="form-check-label" for="agree_terms">
                    Tôi đồng ý với <a href="javascript:void(0)" id="showTermsBtn" class="text-decoration-none fw-bold text-danger">điều khoản tham gia</a> của sự kiện
                </label>
                <div class="invalid-feedback">Bạn phải đồng ý với điều khoản để tiếp tục</div>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-danger btn-lg rounded-pill" id="submit-btn">
                    <span class="spinner-border spinner-border-sm d-none me-2" id="loading-spinner" role="status"></span>
                    <i class="bi bi-check-circle-fill me-2"></i>Đăng ký tham gia
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Sử dụng component modal chung -->
<?php
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
    const studentIdField = document.getElementById('student_id');
    
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
    const submitBtn = document.getElementById('submit-btn');
    const loadingSpinner = document.getElementById('loading-spinner');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            
            // Hiệu ứng rung khi có lỗi
            this.classList.add('animate__animated', 'animate__shakeX');
            setTimeout(() => {
                this.classList.remove('animate__animated', 'animate__shakeX');
            }, 1000);
        } else {
            // Hiển thị trạng thái loading khi submit form
            submitBtn.disabled = true;
            loadingSpinner.classList.remove('d-none');
            submitBtn.querySelector('i').classList.add('d-none');
        }
        form.classList.add('was-validated');
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