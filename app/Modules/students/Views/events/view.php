<?= $this->extend('App\Modules\layouts\student\Views\dashboard\layout') ?>

<?= $this->section('styles') ?>
<style>
    .event-banner {
        height: 300px;
        object-fit: cover;
        border-radius: 10px;
    }
    
    .event-info {
        background-color: #f8f9fc;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .event-info i {
        width: 20px;
        text-align: center;
        color: #4e73df;
    }
    
    .event-action-btn {
        padding: 10px 20px;
    }
    
    .company-card {
        margin-bottom: 10px;
        transition: transform 0.3s;
    }
    
    .company-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12), 0 4px 8px rgba(0,0,0,0.06);
    }
    
    .speaker-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .speaker-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
        object-fit: cover;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url('students/dashboard') ?>">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('students/events') ?>">Sự kiện</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?= $event['name'] ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if(isset($event)): ?>
    <!-- Event Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h1 class="h3 mb-2 text-gray-800"><?= $event['name'] ?></h1>
                            <span class="badge badge-<?= $event['status_color'] ?? 'primary' ?> mb-3"><?= $event['status'] ?></span>
                            <p><?= $event['description'] ?></p>
                        </div>
                        <div class="col-md-4 text-md-right">
                            <?php if(isset($event['status']) && $event['status'] == 'Đang mở đăng ký'): ?>
                            <a href="<?= base_url('students/events/register/'.$event['id']) ?>" class="btn btn-primary btn-lg event-action-btn">
                                <i class="fas fa-calendar-check mr-1"></i> Đăng ký tham gia
                            </a>
                            <?php elseif(isset($event['status']) && $event['status'] == 'Sắp diễn ra'): ?>
                            <button class="btn btn-outline-primary btn-lg event-action-btn">
                                <i class="fas fa-bell mr-1"></i> Nhận thông báo
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Detail -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Banner Image -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <img src="<?= base_url('assets/modules/students/img/events/'.$event['banner']) ?>" alt="<?= $event['name'] ?>" class="img-fluid event-banner mb-3">
                    
                    <!-- Tabs -->
                    <ul class="nav nav-tabs" id="eventDetailTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="about-tab" data-toggle="tab" href="#about" role="tab" aria-controls="about" aria-selected="true">
                                <i class="fas fa-info-circle mr-1"></i> Thông tin chi tiết
                            </a>
                        </li>
                        <?php if(!empty($event['companies'] ?? [])): ?>
                        <li class="nav-item">
                            <a class="nav-link" id="companies-tab" data-toggle="tab" href="#companies" role="tab" aria-controls="companies" aria-selected="false">
                                <i class="fas fa-building mr-1"></i> Doanh nghiệp
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if(!empty($event['speakers'] ?? [])): ?>
                        <li class="nav-item">
                            <a class="nav-link" id="speakers-tab" data-toggle="tab" href="#speakers" role="tab" aria-controls="speakers" aria-selected="false">
                                <i class="fas fa-user-tie mr-1"></i> Diễn giả
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments" aria-selected="false">
                                <i class="fas fa-comments mr-1"></i> Bình luận
                            </a>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-3" id="eventDetailTabsContent">
                        <!-- About Tab -->
                        <div class="tab-pane fade show active" id="about" role="tabpanel" aria-labelledby="about-tab">
                            <h4 class="mb-3">Mô tả chi tiết</h4>
                            <p><?= $event['details'] ?? $event['description'] ?></p>
                            
                            <h4 class="mb-3 mt-4">Nội dung chương trình</h4>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">09:00</div>
                                    <div class="timeline-item-content">
                                        <h5>Đón tiếp đại biểu và khách mời</h5>
                                        <p>Đón tiếp và đăng ký tham dự cho các đại biểu, khách mời và sinh viên.</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">09:30</div>
                                    <div class="timeline-item-content">
                                        <h5>Khai mạc sự kiện</h5>
                                        <p>Phát biểu khai mạc và giới thiệu về sự kiện từ Ban tổ chức.</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">10:00</div>
                                    <div class="timeline-item-content">
                                        <h5>Hoạt động chính</h5>
                                        <p>Diễn ra các hoạt động chính của sự kiện.</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">12:00</div>
                                    <div class="timeline-item-content">
                                        <h5>Giải lao</h5>
                                        <p>Thời gian nghỉ trưa và giao lưu.</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">13:30</div>
                                    <div class="timeline-item-content">
                                        <h5>Tiếp tục chương trình</h5>
                                        <p>Tiếp tục các hoạt động.</p>
                                    </div>
                                </div>
                                <div class="timeline-item">
                                    <div class="timeline-item-marker">16:00</div>
                                    <div class="timeline-item-content">
                                        <h5>Bế mạc sự kiện</h5>
                                        <p>Trao giải thưởng và bế mạc sự kiện.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Companies Tab -->
                        <?php if(!empty($event['companies'] ?? [])): ?>
                        <div class="tab-pane fade" id="companies" role="tabpanel" aria-labelledby="companies-tab">
                            <h4 class="mb-3">Doanh nghiệp tham gia</h4>
                            <div class="row">
                                <?php foreach($event['companies'] as $company): ?>
                                <div class="col-md-4 col-sm-6">
                                    <div class="card company-card">
                                        <div class="card-body text-center">
                                            <img src="<?= base_url('assets/modules/students/img/companies/placeholder.png') ?>" alt="<?= $company ?>" class="img-fluid mb-3" style="max-height: 80px;">
                                            <h5 class="card-title"><?= $company ?></h5>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Speakers Tab -->
                        <?php if(!empty($event['speakers'] ?? [])): ?>
                        <div class="tab-pane fade" id="speakers" role="tabpanel" aria-labelledby="speakers-tab">
                            <h4 class="mb-3">Diễn giả</h4>
                            <?php foreach($event['speakers'] as $speaker): ?>
                            <div class="speaker-item">
                                <img src="<?= base_url('assets/modules/students/img/speakers/avatar.png') ?>" alt="<?= $speaker ?>" class="speaker-avatar">
                                <div>
                                    <h5 class="mb-1"><?= $speaker ?></h5>
                                    <p class="text-muted mb-0">Chuyên gia</p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Comments Tab -->
                        <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                            <h4 class="mb-3">Bình luận</h4>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <textarea class="form-control" rows="3" placeholder="Viết bình luận của bạn..."></textarea>
                                        </div>
                                        <button type="button" class="btn btn-primary">Đăng bình luận</button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <img src="<?= base_url('assets/modules/students/img/profile/default.jpg') ?>" alt="User" class="rounded-circle mr-3" style="width: 40px; height: 40px;">
                                        <div>
                                            <h5 class="mb-1">Nguyễn Văn A</h5>
                                            <p class="text-muted small">2 giờ trước</p>
                                            <p>Sự kiện rất thú vị, tôi đã đăng ký và không thể đợi để tham gia!</p>
                                            <button class="btn btn-sm btn-link text-primary">Thích</button>
                                            <button class="btn btn-sm btn-link text-primary">Trả lời</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex">
                                        <img src="<?= base_url('assets/modules/students/img/profile/default.jpg') ?>" alt="User" class="rounded-circle mr-3" style="width: 40px; height: 40px;">
                                        <div>
                                            <h5 class="mb-1">Trần Thị B</h5>
                                            <p class="text-muted small">1 ngày trước</p>
                                            <p>Tôi đã tham gia sự kiện này năm ngoái, rất đáng để tham dự!</p>
                                            <button class="btn btn-sm btn-link text-primary">Thích</button>
                                            <button class="btn btn-sm btn-link text-primary">Trả lời</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Event Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin sự kiện</h6>
                </div>
                <div class="card-body">
                    <div class="event-info">
                        <p class="mb-2"><i class="fas fa-calendar-alt mr-2"></i> <?= $event['time'] ?></p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt mr-2"></i> <?= $event['location'] ?></p>
                        <p class="mb-0"><i class="fas fa-user-check mr-2"></i> Tình trạng: <?= $event['status'] ?></p>
                    </div>
                    
                    <div class="text-center">
                        <?php if(isset($event['status']) && $event['status'] == 'Đang mở đăng ký'): ?>
                        <a href="<?= base_url('students/events/register/'.$event['id']) ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-calendar-check mr-1"></i> Đăng ký tham gia
                        </a>
                        <?php elseif(isset($event['status']) && $event['status'] == 'Sắp diễn ra'): ?>
                        <button class="btn btn-outline-primary btn-block">
                            <i class="fas fa-bell mr-1"></i> Nhận thông báo
                        </button>
                        <?php endif; ?>
                        
                        <button class="btn btn-outline-info btn-block mt-2">
                            <i class="fas fa-share-alt mr-1"></i> Chia sẻ sự kiện
                        </button>
                        <button class="btn btn-outline-success btn-block mt-2">
                            <i class="fas fa-calendar-plus mr-1"></i> Thêm vào lịch
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Similar Events -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sự kiện tương tự</h6>
                </div>
                <div class="card-body">
                    <div class="similar-event mb-3">
                        <div class="row">
                            <div class="col-4">
                                <img src="<?= base_url('assets/modules/students/img/events/banner1.jpg') ?>" alt="Event" class="img-fluid rounded">
                            </div>
                            <div class="col-8">
                                <h6 class="mb-1">Hội thảo Khởi nghiệp</h6>
                                <p class="text-muted small mb-0"><i class="fas fa-calendar-alt mr-1"></i> 28/03/2024</p>
                                <a href="#" class="btn btn-sm btn-link p-0">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="similar-event mb-3">
                        <div class="row">
                            <div class="col-4">
                                <img src="<?= base_url('assets/modules/students/img/events/banner2.jpg') ?>" alt="Event" class="img-fluid rounded">
                            </div>
                            <div class="col-8">
                                <h6 class="mb-1">Workshop Thiết kế CV</h6>
                                <p class="text-muted small mb-0"><i class="fas fa-calendar-alt mr-1"></i> 05/04/2024</p>
                                <a href="#" class="btn btn-sm btn-link p-0">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="similar-event">
                        <div class="row">
                            <div class="col-4">
                                <img src="<?= base_url('assets/modules/students/img/events/banner3.jpg') ?>" alt="Event" class="img-fluid rounded">
                            </div>
                            <div class="col-8">
                                <h6 class="mb-1">Tập huấn Kỹ năng thuyết trình</h6>
                                <p class="text-muted small mb-0"><i class="fas fa-calendar-alt mr-1"></i> 10/04/2024</p>
                                <a href="#" class="btn btn-sm btn-link p-0">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle mr-1"></i> Không tìm thấy thông tin sự kiện. Vui lòng quay lại <a href="<?= base_url('students/events') ?>">trang danh sách sự kiện</a>.
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Kích hoạt các tabs
    $(document).ready(function() {
        $('#eventDetailTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
<?= $this->endSection() ?> 