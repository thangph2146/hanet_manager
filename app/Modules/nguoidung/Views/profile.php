<?= $this->extend('frontend/layouts/nguoidung_layout') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/pages/profile.css') ?>">
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
                                <div class="info-group">
                                    <label class="info-label">Số điện thoại nhà</label>
                                    <p class="info-value"><?= $profile->HomePhone ?? 'Chưa cập nhật' ?></p>
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
                                <?php if (!empty($profile->created_at)): ?>
                                <div class="info-group">
                                    <label class="info-label">Ngày tạo tài khoản</label>
                                    <p class="info-value"><?= date('d/m/Y', strtotime($profile->created_at)) ?></p>
                                </div>
                                <?php endif; ?>
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
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?= $profile->Email ?>" readonly>
                            <small class="text-muted">Email không thể thay đổi</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="MobilePhone" value="<?= $profile->MobilePhone ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="homephone" class="form-label">Số điện thoại nhà</label>
                            <input type="text" class="form-control" id="homephone" name="HomePhone" value="<?= $profile->HomePhone ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="account-id" class="form-label">Mã tài khoản</label>
                            <input type="text" class="form-control" id="account-id" value="<?= $profile->AccountId ?>" readonly>
                            <small class="text-muted">Mã tài khoản không thể thay đổi</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="account-type" class="form-label">Loại tài khoản</label>
                            <input type="text" class="form-control" id="account-type" value="<?= $profile->AccountType ?>" readonly>
                            <small class="text-muted">Loại tài khoản không thể thay đổi</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="avatar" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            <small class="text-muted">Định dạng: JPG, PNG. Tối đa 2MB</small>
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
<script>
// Cấu hình API endpoints và user ID
const API_CONFIG = {
    profileUpdate: '<?= base_url('api/nguoi-dung/profile/update') ?>',
    userId: <?= $profile->nguoi_dung_id ?>,
    csrfToken: '<?= csrf_hash() ?>'
};
</script>
<script src="<?= base_url('assets/js/nguoidung/pages/profile.js') ?>"></script>
<?= $this->endSection() ?>

