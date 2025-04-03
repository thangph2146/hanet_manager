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
                        <div class="col-md-2 text-center text-md-start mb-3 mb-md-0">
                            <img src="<?= !empty($profile->avatar) ? base_url('uploads/avatars/' . $profile->avatar) : base_url('assets/images/avatars/default.jpg') ?>" alt="Avatar" class="profile-avatar" data-bs-toggle="tooltip" title="Ảnh đại diện">
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-1"><?= $profile->FullName ?></h4>
                            <p class="text-muted mb-2"><?= $profile->Email ?></p>
                            <p class="mb-0">
                                <span class="badge bg-primary me-2"><?= $profile->AccountType ?></span>
                                <span class="badge bg-success">Đã tham gia <?= isset($event_count) ? $event_count : 0 ?> sự kiện</span>
                            </p>
                        </div>
                        <div class="col-md-2 text-center text-md-end mt-3 mt-md-0">
                            <button class="btn btn-outline-primary btn-sm" id="edit-profile-btn" data-bs-toggle="tooltip" title="Chỉnh sửa thông tin cá nhân">
                                <i class="fas fa-edit me-1"></i> Sửa thông tin
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Navigation -->
                <div class="profile-info">
                    <ul class="nav nav-pills mb-4" id="profileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal-info" type="button" role="tab" aria-controls="personal-info" aria-selected="true">
                                <i class="fas fa-user me-2"></i>Thông tin cá nhân
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="registered-tab" data-bs-toggle="tab" data-bs-target="#registered-events" type="button" role="tab" aria-controls="registered-events" aria-selected="false">
                                <i class="fas fa-calendar-check me-2"></i>Đã đăng ký
                                <span class="badge rounded-pill bg-primary ms-1"><?= isset($registered_count) ? $registered_count : 0 ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="attended-tab" data-bs-toggle="tab" data-bs-target="#attended-events" type="button" role="tab" aria-controls="attended-events" aria-selected="false">
                                <i class="fas fa-check-circle me-2"></i>Đã tham gia
                                <span class="badge rounded-pill bg-success ms-1"><?= isset($attended_count) ? $attended_count : 0 ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled-events" type="button" role="tab" aria-controls="canceled-events" aria-selected="false">
                                <i class="fas fa-times-circle me-2"></i>Đã hủy
                                <span class="badge rounded-pill bg-danger ms-1"><?= isset($canceled_count) ? $canceled_count : 0 ?></span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available-events" type="button" role="tab" aria-controls="available-events" aria-selected="false">
                                <i class="fas fa-calendar-alt me-2"></i>Sự kiện có thể đăng ký
                                <span class="badge rounded-pill bg-info ms-1"><?= isset($available_count) ? $available_count : 0 ?></span>
                            </button>
                        </li>
                    </ul>
                    
                    <!-- Tab Content -->
                    <div class="tab-content" id="profileTabContent">
                        <!-- Thông tin cá nhân -->
                        <div class="tab-pane fade show active" id="personal-info" role="tabpanel" aria-labelledby="personal-tab">
                            <div class="row personal-info">
                                <div class="col-md-6 mb-4">
                                    <div class="mb-3">
                                        <label class="form-label">Họ và tên</label>
                                        <p class="form-control"><?= $profile->FullName ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <p class="form-control"><?= $profile->Email ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <p class="form-control"><?= $profile->MobilePhone ?? 'Chưa cập nhật' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="mb-3">
                                        <label class="form-label">Mã tài khoản</label>
                                        <p class="form-control"><?= $profile->AccountId ?? 'Chưa cập nhật' ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Loại tài khoản</label>
                                        <p class="form-control"><?= $profile->AccountType ?? 'Chưa cập nhật' ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Đăng nhập gần đây</label>
                                        <p class="form-control"><?= date('d/m/Y H:i', strtotime($profile->last_login)) ?? 'Chưa cập nhật' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Các sự kiện đã đăng ký -->
                        <div class="tab-pane fade" id="registered-events" role="tabpanel" aria-labelledby="registered-tab">
                            <div class="row" id="registered-events-container">
                                <div class="col-12 text-center py-5 loading-container">
                                    <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                    <p>Đang tải dữ liệu...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Các sự kiện đã tham gia -->
                        <div class="tab-pane fade" id="attended-events" role="tabpanel" aria-labelledby="attended-tab">
                            <div class="row" id="attended-events-container">
                                <div class="col-12 text-center py-5 loading-container">
                                    <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                    <p>Đang tải dữ liệu...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Các sự kiện đã hủy -->
                        <div class="tab-pane fade" id="canceled-events" role="tabpanel" aria-labelledby="canceled-tab">
                            <div class="row" id="canceled-events-container">
                                <div class="col-12 text-center py-5 loading-container">
                                    <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                    <p>Đang tải dữ liệu...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Các sự kiện có thể đăng ký -->
                        <div class="tab-pane fade" id="available-events" role="tabpanel" aria-labelledby="available-tab">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tìm kiếm sự kiện..." id="search-events" aria-label="Tìm kiếm sự kiện">
                                        <button class="btn btn-primary" type="button" id="search-events-btn" data-bs-toggle="tooltip" title="Tìm kiếm">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row" id="available-events-container">
                                <div class="col-12 text-center py-5 loading-container">
                                    <i class="fas fa-spinner fa-spin fa-2x mb-3"></i>
                                    <p>Đang tải dữ liệu...</p>
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

<!-- Modal xác nhận hủy đăng ký -->
<div class="modal fade" id="cancelEventModal" tabindex="-1" aria-labelledby="cancelEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelEventModalLabel">Xác nhận hủy đăng ký</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cancel-event-form">
                    <input type="hidden" id="cancel-event-id" name="event_id">
                    <p>Bạn có chắc chắn muốn hủy đăng ký sự kiện này?</p>
                    <div class="mb-3">
                        <label for="cancel-reason" class="form-label">Lý do hủy <span class="text-danger">*</span></label>
                        <select class="form-select" id="cancel-reason" name="reason" required>
                            <option value="">Chọn lý do...</option>
                            <option value="time_conflict">Trùng lịch</option>
                            <option value="personal">Lý do cá nhân</option>
                            <option value="no_longer_interested">Không còn quan tâm</option>
                            <option value="other">Khác</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="other-reason-container">
                        <label for="other-reason" class="form-label">Lý do khác <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="other-reason" name="other_reason" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-danger" id="confirm-cancel-btn">Xác nhận hủy</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal đánh giá sự kiện -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Đánh giá sự kiện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="feedback-form" data-ajax="true" action="<?= base_url('nguoi-dung/events/feedback') ?>" method="post">
                    <input type="hidden" id="feedback-event-id" name="event_id">
                    <div class="mb-3 text-center">
                        <h5 id="feedback-event-title" class="mb-3"></h5>
                        <div class="rating">
                            <i class="far fa-star rating-star" data-value="1"></i>
                            <i class="far fa-star rating-star" data-value="2"></i>
                            <i class="far fa-star rating-star" data-value="3"></i>
                            <i class="far fa-star rating-star" data-value="4"></i>
                            <i class="far fa-star rating-star" data-value="5"></i>
                        </div>
                        <input type="hidden" id="rating-value" name="rating" value="0">
                    </div>
                    <div class="mb-3">
                        <label for="feedback-comments" class="form-label">Nhận xét về sự kiện</label>
                        <textarea class="form-control" id="feedback-comments" name="comments" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về sự kiện..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="save-feedback-btn">Gửi đánh giá</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Cấu hình API endpoints và user ID
const API_CONFIG = {
    baseUrl: '<?= base_url('api') ?>',
    registeredEvents: '<?= base_url('api/nguoi-dung/events/registered') ?>',
    attendedEvents: '<?= base_url('api/nguoi-dung/events/attended') ?>',
    canceledEvents: '<?= base_url('api/nguoi-dung/events/canceled') ?>',
    availableEvents: '<?= base_url('api/nguoi-dung/events/available') ?>',
    cancelEvent: '<?= base_url('api/nguoi-dung/events/cancel') ?>',
    registerEvent: '<?= base_url('api/nguoi-dung/events/register') ?>',
    profileUpdate: '<?= base_url('api/nguoi-dung/profile/update') ?>',
    feedback: '<?= base_url('api/nguoi-dung/events/feedback') ?>',
    userId: <?= $profile->nguoi_dung_id ?>,
    csrfToken: '<?= csrf_hash() ?>'
};
</script>
<script src="<?= base_url('assets/js/nguoidung/pages/profile.js') ?>"></script>
<?= $this->endSection() ?>

