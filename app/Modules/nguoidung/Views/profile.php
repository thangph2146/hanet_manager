<?= $this->extend('frontend/layouts/nguoidung_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/pages/profile.css') ?>">
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="profile-container">
                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="row align-items-center">
                        <div class="col-lg-3 col-md-4 col-sm-12 text-center text-md-start mb-3 mb-md-0">
                            <img src="<?= !empty($profile->avatar) ? base_url($profile->avatar) : base_url('assets/images/avatars/user.png') ?>" alt="Avatar" class="profile-avatar" data-bs-toggle="tooltip" title="Ảnh đại diện">
                        </div>
                        <div class="col-lg-7 col-md-6 col-sm-12">
                            <div class="profile-info-header">
                                <p class="mb-2 text-white profile-type"><?= $profile->AccountType ?></p> 
                                <h4 class="mb-1 profile-name"><?= $profile->FullName ?></h4>
                                <p class="mb-2 text-white profile-email"><?= $profile->Email ?></p>
                                <p class="mb-0 d-none d-md-block">
                                    <span class="badge bg-info" style="top: 15px; right: 75px">Đăng nhập gần đây: <?= date('d/m/Y H:i', strtotime($profile->last_login)) ?></span>
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-12 text-center text-md-end mt-3 mt-md-0">
                            <button class="btn btn-outline-primary btn-edit-profile" id="edit-profile-btn" data-bs-toggle="tooltip" title="Chỉnh sửa thông tin cá nhân">
                                <i class="fas fa-edit me-1"></i> <span class="d-none d-md-inline">Sửa thông tin</span>
                            </button>
                        </div>
                    </div>
                    <div class="row d-md-none mt-2">
                        <div class="col-12 text-center">
                            <span class="badge bg-info">Đăng nhập gần đây: <?= date('d/m/Y H:i', strtotime($profile->last_login)) ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Information -->
                <div class="profile-info">
                    <h5 class="section-title"><i class="fas fa-user-circle me-2"></i>Thông tin cá nhân</h5>
                    <div class="info-card">
                        <div class="row personal-info">
                            <div class="col-md-6 mb-4">
                                <div class="info-group">
                                    <label class="info-label">Họ và tên</label>
                                    <p class="info-value"><?= $profile->FullName ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Họ</label>
                                    <p class="info-value"><?= $profile->LastName ?? 'Chưa cập nhật' ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Tên đệm</label>
                                    <p class="info-value"><?= $profile->MiddleName ?? 'Chưa cập nhật' ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Tên</label>
                                    <p class="info-value"><?= $profile->FirstName ?? 'Chưa cập nhật' ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Email</label>
                                    <p class="info-value"><?= $profile->Email ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Số điện thoại</label>
                                    <p class="info-value"><?= $profile->MobilePhone ?? 'Chưa cập nhật' ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Số điện thoại nhà</label>
                                    <p class="info-value"><?= $profile->HomePhone ?? 'Chưa cập nhật' ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Số điện thoại nhà (2)</label>
                                    <p class="info-value"><?= $profile->HomePhone1 ?? 'Chưa cập nhật' ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="info-group">
                                    <label class="info-label">Mã tài khoản</label>
                                    <p class="info-value"><?= $profile->AccountId ?? 'Chưa cập nhật' ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Loại tài khoản</label>
                                    <p class="info-value"><?= $profile->AccountType ?? 'Chưa cập nhật' ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Đăng nhập gần đây</label>
                                    <p class="info-value"><?= date('d/m/Y H:i', strtotime($profile->last_login)) ?? 'Chưa cập nhật' ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa thông tin -->
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Chỉnh sửa thông tin cá nhân</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-profile-form" data-ajax="true" action="<?= base_url('nguoi-dung/profile/update') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="nguoi_dung_id" value="<?= $profile->nguoi_dung_id ?>">
                    <input type="hidden" name="avatar_folder" value="uploads/avatars/">
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 mb-3">
                            <label for="lastname" class="form-label">Họ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="lastname" name="LastName" value="<?= $profile->LastName ?>" required>
                        </div>
                        <div class="col-md-4 col-sm-12 mb-3">
                            <label for="middlename" class="form-label">Tên đệm</label>
                            <input type="text" class="form-control" id="middlename" name="MiddleName" value="<?= $profile->MiddleName ?>">
                        </div>
                        <div class="col-md-4 col-sm-12 mb-3">
                            <label for="firstname" class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="firstname" name="FirstName" value="<?= $profile->FirstName ?>" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="fullname" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fullname" name="FullName" value="<?= $profile->FullName ?>" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="MobilePhone" value="<?= $profile->MobilePhone ?>" required>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="homephone" class="form-label">Số điện thoại nhà</label>
                            <input type="text" class="form-control" id="homephone" name="HomePhone" value="<?= $profile->HomePhone ?>">
                        </div>
                        <div class="col-md-6 col-sm-12 mb-3">
                            <label for="homephone1" class="form-label">Số điện thoại nhà (2)</label>
                            <input type="text" class="form-control" id="homephone1" name="HomePhone1" value="<?= $profile->HomePhone1 ?>">
                        </div>
                        <div class="col-12 mb-3">
                            <label for="avatar" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            <small class="text-muted">Định dạng: JPG, PNG. Tối đa 2MB</small>
                        </div>
                        <div class="col-12 mb-3 text-center">
                            <div class="avatar-preview">
                                <img src="<?= !empty($profile->avatar) ? base_url($profile->avatar) : base_url('assets/images/avatars/user.png') ?>" alt="Avatar Preview" id="avatar-preview">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="save-profile-btn">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
// Cấu hình API endpoints và user ID
const API_CONFIG = {
    profileUpdate: '<?= base_url('nguoi-dung/profile/update') ?>',
    userId: <?= $profile->nguoi_dung_id ?>,
    csrfToken: '<?= csrf_hash() ?>'
};

// Xem trước ảnh đại diện khi chọn file
document.getElementById('avatar').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar-preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
});

// Xử lý hiển thị modal chỉnh sửa
document.getElementById('edit-profile-btn').addEventListener('click', function() {
    const modal = new bootstrap.Modal(document.getElementById('editProfileModal'));
    modal.show();
});

// Xử lý lưu thông tin
document.getElementById('save-profile-btn').addEventListener('click', function() {
    const form = document.getElementById('edit-profile-form');
    const formData = new FormData(form);
    
    // Tự động cập nhật FullName nếu các trường họ, tên đệm, tên thay đổi
    const lastName = document.getElementById('lastname').value;
    const middleName = document.getElementById('middlename').value;
    const firstName = document.getElementById('firstname').value;
    
    // Tạo FullName từ các thành phần
    let fullName = lastName;
    if (middleName) fullName += ' ' + middleName;
    fullName += ' ' + firstName;
    
    // Cập nhật giá trị FullName trong form
    document.getElementById('fullname').value = fullName;
    
    // Hiển thị thông báo đang xử lý
    const saveBtn = this;
    saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
    saveBtn.disabled = true;
    
    // Thêm CSRF token nếu cần
    formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => {
                throw new Error('Lỗi mạng: ' + response.status + ' - ' + text);
            });
        }
        return response.json();
    })
    .then(data => {
        // Khôi phục trạng thái nút
        saveBtn.innerHTML = 'Lưu thay đổi';
        saveBtn.disabled = false;
        
        if (data.success) {
            // Hiển thị thông báo thành công
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: data.message,
                confirmButtonText: 'Đóng'
            });
            
            // Cập nhật thông tin trên trang
            document.querySelector('.profile-header h4').textContent = data.data.FullName;
            document.querySelector('.info-group:nth-child(1) .info-value').textContent = data.data.FullName;
            document.querySelector('.info-group:nth-child(3) .info-value').textContent = data.data.MobilePhone;
            
            // Cập nhật ảnh đại diện nếu có
            if (data.data.avatar) {
                const avatarSrc = '<?= base_url('') ?>' + data.data.avatar;
                document.querySelector('.profile-avatar').src = avatarSrc;
            }
            
            // Đóng modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
            modal.hide();
            
            // Tải lại trang sau 1 giây
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            // Hiển thị thông báo lỗi
            let errorMsg = data.message;
            if (data.errors) {
                // Tạo danh sách lỗi
                errorMsg += '<ul>';
                for (const key in data.errors) {
                    errorMsg += `<li>${data.errors[key]}</li>`;
                }
                errorMsg += '</ul>';
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                html: errorMsg,
                confirmButtonText: 'Đóng'
            });
            
            console.error('Lỗi:', data.errors);
        }
    })
    .catch(error => {
        // Khôi phục trạng thái nút
        saveBtn.innerHTML = 'Lưu thay đổi';
        saveBtn.disabled = false;
        
        console.error('Error:', error);
        
        Swal.fire({
            icon: 'error',
            title: 'Lỗi!',
            text: 'Đã xảy ra lỗi khi cập nhật thông tin: ' + error.message,
            confirmButtonText: 'Đóng'
        });
    });
});
</script>
<?= $this->endSection() ?>

