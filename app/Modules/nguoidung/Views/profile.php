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
                        <div class="col-md-3 text-center text-md-start mb-3 mb-md-0">
                            <img src="<?= !empty($profile->avatar) ? base_url('uploads/avatars/' . $profile->avatar) : base_url('assets/images/avatars/default.jpg') ?>" alt="Avatar" class="profile-avatar" data-bs-toggle="tooltip" title="Ảnh đại diện">
                        </div>
                        <div class="col-md-7">
                            <p class="mb-2 text-white"><?= $profile->AccountType ?></p> 
                            <h4 class="mb-1"><?= $profile->FullName ?></h4>
                            <p class="mb-2 text-white"><?= $profile->Email ?></p>

                            <p class="mb-0">
                                <span class="badge bg-info" style="top: 1rem; right: 5rem;">Đăng nhập gần đây: <?= date('d/m/Y H:i', strtotime($profile->last_login)) ?></span>
                            </p>
                        </div>
                        <div class="col-md-2 text-center text-md-end mt-3 mt-md-0">
                            <button class="btn btn-outline-primary" id="edit-profile-btn" data-bs-toggle="tooltip" title="Chỉnh sửa thông tin cá nhân">
                                <i class="fas fa-edit me-1"></i> Sửa thông tin
                            </button>
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
                                    <label class="info-label">Email</label>
                                    <p class="info-value"><?= $profile->Email ?></p>
                                </div>
                                <div class="info-group">
                                    <label class="info-label">Số điện thoại</label>
                                    <p class="info-value"><?= $profile->MobilePhone ?? 'Chưa cập nhật' ?></p>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfileModalLabel">Chỉnh sửa thông tin cá nhân</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit-profile-form" data-ajax="true" action="<?= base_url('nguoi-dung/profile/update') ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="nguoi_dung_id" value="<?= $profile->nguoi_dung_id ?>">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fullname" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fullname" name="FullName" value="<?= $profile->FullName ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="MobilePhone" value="<?= $profile->MobilePhone ?>" required>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="avatar" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            <small class="text-muted">Định dạng: JPG, PNG. Tối đa 2MB</small>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="avatar-preview">
                                <img src="<?= !empty($profile->avatar) ? base_url('uploads/avatars/' . $profile->avatar) : base_url('assets/images/avatars/default.jpg') ?>" alt="Avatar Preview" class="img-thumbnail" id="avatar-preview">
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
    profileUpdate: '<?= base_url('api/nguoi-dung/profile/update') ?>',
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
            throw new Error('Lỗi mạng: ' + response.status);
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
                const avatarSrc = '<?= base_url('uploads/avatars/') ?>' + data.data.avatar;
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

