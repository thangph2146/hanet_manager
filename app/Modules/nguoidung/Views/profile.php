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
                            <img src="<?= base_url('assets/images/avatars/default.jpg') ?>" alt="Avatar" class="profile-avatar" data-bs-toggle="tooltip" title="Ảnh đại diện">
                        </div>
                        <div class="col-md-8">
                            <h4 class="mb-1"><?= isset($user) ? $user['name'] : 'Nguyễn Văn A' ?></h4>
                            <p class="text-muted mb-2"><?= isset($user) ? $user['email'] : 'nguyen.van.a@example.com' ?></p>
                            <p class="mb-0">
                                <span class="badge bg-primary me-2">Sinh viên</span>
                                <span class="badge bg-success">Đã tham gia 5 sự kiện</span>
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
                                <span class="badge rounded-pill bg-primary ms-1">2</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="attended-tab" data-bs-toggle="tab" data-bs-target="#attended-events" type="button" role="tab" aria-controls="attended-events" aria-selected="false">
                                <i class="fas fa-check-circle me-2"></i>Đã tham gia
                                <span class="badge rounded-pill bg-success ms-1">1</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="canceled-tab" data-bs-toggle="tab" data-bs-target="#canceled-events" type="button" role="tab" aria-controls="canceled-events" aria-selected="false">
                                <i class="fas fa-times-circle me-2"></i>Đã hủy
                                <span class="badge rounded-pill bg-danger ms-1">1</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="available-tab" data-bs-toggle="tab" data-bs-target="#available-events" type="button" role="tab" aria-controls="available-events" aria-selected="false">
                                <i class="fas fa-calendar-alt me-2"></i>Sự kiện có thể đăng ký
                                <span class="badge rounded-pill bg-info ms-1">3</span>
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
                                        <p class="form-control"><?= isset($user) ? $user['name'] : 'Nguyễn Văn A' ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <p class="form-control"><?= isset($user) ? $user['email'] : 'nguyen.van.a@example.com' ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Số điện thoại</label>
                                        <p class="form-control"><?= isset($user) ? $user['phone'] : '0987654321' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="mb-3">
                                        <label class="form-label">Ngày sinh</label>
                                        <p class="form-control"><?= isset($user) ? $user['birthday'] : '01/01/2000' ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Lớp</label>
                                        <p class="form-control"><?= isset($user) ? $user['class'] : 'DI20V7A2' ?></p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Khoa</label>
                                        <p class="form-control"><?= isset($user) ? $user['faculty'] : 'Công nghệ thông tin' ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Các sự kiện đã đăng ký -->
                        <div class="tab-pane fade" id="registered-events" role="tabpanel" aria-labelledby="registered-tab">
                            <div class="row">
                                <!-- Event Card 1 -->
                                <div class="col-md-4 mb-4">
                                    <div class="event-card">
                                        <div class="event-image">
                                            <span class="event-badge registered">Đã đăng ký</span>
                                            <img src="<?= base_url('assets/images/events/event1.jpg') ?>" alt="Sự kiện">
                                        </div>
                                        <div class="card-body">
                                            <div class="event-meta">
                                                <span class="event-date" data-bs-toggle="tooltip" title="Thời gian diễn ra">
                                                    <i class="far fa-calendar"></i>
                                                    25/04/2024 09:00
                                                </span>
                                                <span class="event-location" data-bs-toggle="tooltip" title="Địa điểm">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    Phòng A1.01
                                                </span>
                                            </div>
                                            <h5 class="card-title mb-2">Workshop Kỹ năng mềm</h5>
                                            <p class="card-text text-secondary">Học cách giao tiếp hiệu quả và làm việc nhóm</p>
                                            <div class="event-footer">
                                                <span class="event-category">
                                                    <i class="fas fa-tag me-1"></i>
                                                    Workshop
                                                </span>
                                                <a href="#" class="btn btn-danger btn-sm cancel-event-btn" data-bs-toggle="tooltip" title="Hủy đăng ký sự kiện này">Hủy đăng ký</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Event Card 2 -->
                                <div class="col-md-4 mb-4">
                                    <div class="event-card">
                                        <div class="event-image">
                                            <span class="event-badge registered">Đã đăng ký</span>
                                            <img src="<?= base_url('assets/images/events/event2.jpg') ?>" alt="Sự kiện">
                                        </div>
                                        <div class="card-body">
                                            <div class="event-meta">
                                                <span class="event-date" data-bs-toggle="tooltip" title="Thời gian diễn ra">
                                                    <i class="far fa-calendar"></i>
                                                    01/05/2024 14:00
                                                </span>
                                                <span class="event-location" data-bs-toggle="tooltip" title="Địa điểm">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    Hội trường lớn
                                                </span>
                                            </div>
                                            <h5 class="card-title mb-2">Hội thảo Công nghệ 2024</h5>
                                            <p class="card-text text-secondary">Cập nhật xu hướng công nghệ mới nhất</p>
                                            <div class="event-footer">
                                                <span class="event-category">
                                                    <i class="fas fa-tag me-1"></i>
                                                    Hội thảo
                                                </span>
                                                <a href="#" class="btn btn-danger btn-sm cancel-event-btn" data-bs-toggle="tooltip" title="Hủy đăng ký sự kiện này">Hủy đăng ký</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Các sự kiện đã tham gia -->
                        <div class="tab-pane fade" id="attended-events" role="tabpanel" aria-labelledby="attended-tab">
                            <div class="row">
                                <!-- Event Card -->
                                <div class="col-md-4 mb-4">
                                    <div class="event-card">
                                        <div class="event-image">
                                            <span class="event-badge attended">Đã tham gia</span>
                                            <img src="<?= base_url('assets/images/events/event3.jpg') ?>" alt="Sự kiện">
                                        </div>
                                        <div class="card-body">
                                            <div class="event-meta">
                                                <span class="event-date" data-bs-toggle="tooltip" title="Thời gian diễn ra">
                                                    <i class="far fa-calendar"></i>
                                                    15/03/2024 08:00
                                                </span>
                                                <span class="event-location" data-bs-toggle="tooltip" title="Địa điểm">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    Sảnh A
                                                </span>
                                            </div>
                                            <h5 class="card-title mb-2">Ngày hội Việc làm IT</h5>
                                            <p class="card-text text-secondary">Cơ hội việc làm từ 50+ công ty công nghệ</p>
                                            <div class="event-footer">
                                                <span class="event-category">
                                                    <i class="fas fa-tag me-1"></i>
                                                    Career Fair
                                                </span>
                                                <a href="#" class="btn btn-primary btn-sm view-feedback-btn" data-bs-toggle="tooltip" title="Xem đánh giá của bạn về sự kiện">Xem đánh giá</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Các sự kiện đã hủy -->
                        <div class="tab-pane fade" id="canceled-events" role="tabpanel" aria-labelledby="canceled-tab">
                            <div class="row">
                                <!-- Event Card -->
                                <div class="col-md-4 mb-4">
                                    <div class="event-card">
                                        <div class="event-image">
                                            <span class="event-badge canceled">Đã hủy</span>
                                            <img src="<?= base_url('assets/images/events/event4.jpg') ?>" alt="Sự kiện">
                                        </div>
                                        <div class="card-body">
                                            <div class="event-meta">
                                                <span class="event-date" data-bs-toggle="tooltip" title="Thời gian diễn ra">
                                                    <i class="far fa-calendar"></i>
                                                    05/04/2024 10:00
                                                </span>
                                                <span class="event-location" data-bs-toggle="tooltip" title="Địa điểm">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    Phòng B2.04
                                                </span>
                                            </div>
                                            <h5 class="card-title mb-2">Seminar AI và Machine Learning</h5>
                                            <p class="card-text text-secondary">Tìm hiểu về ứng dụng AI trong đời sống</p>
                                            <div class="event-footer">
                                                <span class="event-category">
                                                    <i class="fas fa-tag me-1"></i>
                                                    Seminar
                                                </span>
                                                <a href="#" class="btn btn-success btn-sm register-event-btn" data-bs-toggle="tooltip" title="Đăng ký lại sự kiện này">Đăng ký lại</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Các sự kiện có thể đăng ký -->
                        <div class="tab-pane fade" id="available-events" role="tabpanel" aria-labelledby="available-tab">
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tìm kiếm sự kiện..." id="search-events" aria-label="Tìm kiếm sự kiện">
                                        <button class="btn btn-primary" type="button" data-bs-toggle="tooltip" title="Tìm kiếm">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <!-- Event Card 1 -->
                                <div class="col-md-4 mb-4">
                                    <div class="event-card">
                                        <div class="event-image">
                                            <span class="event-badge popular">Popular</span>
                                            <img src="<?= base_url('assets/images/events/event5.jpg') ?>" alt="Sự kiện">
                                        </div>
                                        <div class="card-body">
                                            <div class="event-meta">
                                                <span class="event-date" data-bs-toggle="tooltip" title="Thời gian diễn ra">
                                                    <i class="far fa-calendar"></i>
                                                    10/05/2024 09:00
                                                </span>
                                                <span class="event-location" data-bs-toggle="tooltip" title="Địa điểm">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    Phòng C3.05
                                                </span>
                                            </div>
                                            <h5 class="card-title mb-2">Workshop UX/UI Design</h5>
                                            <p class="card-text text-secondary">Học cách thiết kế giao diện người dùng hiệu quả</p>
                                            <div class="event-footer">
                                                <span class="event-category">
                                                    <i class="fas fa-tag me-1"></i>
                                                    Workshop
                                                </span>
                                                <a href="#" class="btn btn-success btn-sm register-event-btn" data-bs-toggle="tooltip" title="Đăng ký tham gia sự kiện này">Đăng ký</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Event Card 2 -->
                                <div class="col-md-4 mb-4">
                                    <div class="event-card">
                                        <div class="event-image">
                                            <img src="<?= base_url('assets/images/events/event6.jpg') ?>" alt="Sự kiện">
                                        </div>
                                        <div class="card-body">
                                            <div class="event-meta">
                                                <span class="event-date" data-bs-toggle="tooltip" title="Thời gian diễn ra">
                                                    <i class="far fa-calendar"></i>
                                                    15/05/2024 14:00
                                                </span>
                                                <span class="event-location" data-bs-toggle="tooltip" title="Địa điểm">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    Phòng Hội thảo 2
                                                </span>
                                            </div>
                                            <h5 class="card-title mb-2">Hội thảo Khởi nghiệp</h5>
                                            <p class="card-text text-secondary">Chia sẻ kinh nghiệm từ các doanh nhân thành công</p>
                                            <div class="event-footer">
                                                <span class="event-category">
                                                    <i class="fas fa-tag me-1"></i>
                                                    Hội thảo
                                                </span>
                                                <a href="#" class="btn btn-success btn-sm register-event-btn" data-bs-toggle="tooltip" title="Đăng ký tham gia sự kiện này">Đăng ký</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Event Card 3 -->
                                <div class="col-md-4 mb-4">
                                    <div class="event-card">
                                        <div class="event-image">
                                            <span class="event-badge popular">Popular</span>
                                            <img src="<?= base_url('assets/images/events/event7.jpg') ?>" alt="Sự kiện">
                                        </div>
                                        <div class="card-body">
                                            <div class="event-meta">
                                                <span class="event-date" data-bs-toggle="tooltip" title="Thời gian diễn ra">
                                                    <i class="far fa-calendar"></i>
                                                    20/05/2024 10:00
                                                </span>
                                                <span class="event-location" data-bs-toggle="tooltip" title="Địa điểm">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    Sân vận động
                                                </span>
                                            </div>
                                            <h5 class="card-title mb-2">Ngày hội Thể thao</h5>
                                            <p class="card-text text-secondary">Các hoạt động thể thao và giải trí ngoài trời</p>
                                            <div class="event-footer">
                                                <span class="event-category">
                                                    <i class="fas fa-tag me-1"></i>
                                                    Thể thao
                                                </span>
                                                <a href="#" class="btn btn-success btn-sm register-event-btn" data-bs-toggle="tooltip" title="Đăng ký tham gia sự kiện này">Đăng ký</a>
                                            </div>
                                        </div>
                                    </div>
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
                <form id="edit-profile-form">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fullname" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="fullname" value="<?= isset($user) ? $user['name'] : 'Nguyễn Văn A' ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" value="<?= isset($user) ? $user['email'] : 'nguyen.van.a@example.com' ?>" readonly>
                            <small class="text-muted">Email không thể thay đổi</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" value="<?= isset($user) ? $user['phone'] : '0987654321' ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="birthday" class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="birthday" value="<?= isset($user) ? $user['birthday'] : '2000-01-01' ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="class" class="form-label">Lớp</label>
                            <input type="text" class="form-control" id="class" value="<?= isset($user) ? $user['class'] : 'DI20V7A2' ?>" readonly>
                            <small class="text-muted">Liên hệ Phòng Đào tạo để thay đổi</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="faculty" class="form-label">Khoa</label>
                            <input type="text" class="form-control" id="faculty" value="<?= isset($user) ? $user['faculty'] : 'Công nghệ thông tin' ?>" readonly>
                            <small class="text-muted">Liên hệ Phòng Đào tạo để thay đổi</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="avatar" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" id="avatar" accept="image/*">
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
<script src="<?= base_url('assets/js/nguoidung/pages/profile.js') ?>"></script>
<?= $this->endSection() ?>

