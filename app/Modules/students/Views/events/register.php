<?= $this->extend('App\Modules\layouts\student\Views\dashboard\layout') ?>

<?= $this->section('styles') ?>
<style>
    .event-banner {
        height: 200px;
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
    
    .form-section {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 30px;
    }
    
    .form-section h5 {
        border-bottom: 1px solid #e3e6f0;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    
    .form-check-label {
        margin-left: 5px;
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
                    <li class="breadcrumb-item"><a href="<?= base_url('students/events/'.$event['id']) ?>"><?= $event['name'] ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Đăng ký</li>
                </ol>
            </nav>
        </div>
    </div>

    <?php if(isset($event)): ?>
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Đăng ký tham gia sự kiện</h1>
    </div>

    <div class="row">
        <!-- Event Info Column -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin sự kiện</h6>
                </div>
                <div class="card-body">
                    <img src="<?= base_url('assets/modules/students/img/events/'.$event['banner']) ?>" alt="<?= $event['name'] ?>" class="img-fluid event-banner mb-3">
                    
                    <h5 class="card-title"><?= $event['name'] ?></h5>
                    <span class="badge badge-<?= $event['status_color'] ?? 'primary' ?> mb-3"><?= $event['status'] ?></span>
                    
                    <div class="event-info mt-3">
                        <p class="mb-2"><i class="fas fa-calendar-alt mr-2"></i> <?= $event['time'] ?></p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt mr-2"></i> <?= $event['location'] ?></p>
                        <p class="mb-0"><i class="fas fa-info-circle mr-2"></i> <?= $event['description'] ?></p>
                    </div>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-exclamation-circle mr-1"></i> Vui lòng điền đầy đủ thông tin để hoàn tất đăng ký tham gia sự kiện.
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Registration Form Column -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin đăng ký</h6>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('students/events/save-registration/'.$event['id']) ?>" method="post">
                        <div class="form-section mb-4">
                            <h5>Thông tin cá nhân</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="fullName">Họ và tên</label>
                                    <input type="text" class="form-control" id="fullName" name="fullName" value="Nguyễn Văn A" required readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="studentId">Mã số sinh viên</label>
                                    <input type="text" class="form-control" id="studentId" name="studentId" value="SV12345" required readonly>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="student@example.com" required readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="0912345678" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="faculty">Khoa/Ngành</label>
                                    <input type="text" class="form-control" id="faculty" name="faculty" value="Công nghệ thông tin" required readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="class">Lớp</label>
                                    <input type="text" class="form-control" id="class" name="class" value="CNTT1" required readonly>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section mb-4">
                            <h5>Thông tin bổ sung</h5>
                            
                            <div class="form-group">
                                <label>Bạn có kinh nghiệm tham gia sự kiện tương tự trước đây không?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="experience" id="experienceYes" value="yes">
                                    <label class="form-check-label" for="experienceYes">Có</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="experience" id="experienceNo" value="no" checked>
                                    <label class="form-check-label" for="experienceNo">Không</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="expectation">Bạn mong đợi điều gì từ sự kiện này?</label>
                                <textarea class="form-control" id="expectation" name="expectation" rows="3" placeholder="Nhập mong đợi của bạn..."></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label>Bạn biết về sự kiện này từ đâu?</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sourceWebsite" name="source[]" value="website">
                                    <label class="form-check-label" for="sourceWebsite">Website trường</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sourceFacebook" name="source[]" value="facebook">
                                    <label class="form-check-label" for="sourceFacebook">Facebook</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sourceFriend" name="source[]" value="friend">
                                    <label class="form-check-label" for="sourceFriend">Bạn bè giới thiệu</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sourceTeacher" name="source[]" value="teacher">
                                    <label class="form-check-label" for="sourceTeacher">Giảng viên thông báo</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="sourceOther" name="source[]" value="other">
                                    <label class="form-check-label" for="sourceOther">Khác</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-section mb-4">
                            <h5>Điều khoản và điều kiện</h5>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="termsCheck" name="termsCheck" required>
                                    <label class="form-check-label" for="termsCheck">
                                        Tôi đồng ý với <a href="#" data-toggle="modal" data-target="#termsModal">điều khoản và điều kiện</a> của sự kiện.
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="privacyCheck" name="privacyCheck" required>
                                    <label class="form-check-label" for="privacyCheck">
                                        Tôi đồng ý cho phép Ban tổ chức sử dụng thông tin của tôi để liên hệ về sự kiện.
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group text-center">
                            <a href="<?= base_url('students/events/'.$event['id']) ?>" class="btn btn-secondary mr-2">Hủy</a>
                            <button type="submit" class="btn btn-primary">Hoàn tất đăng ký</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Terms Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Điều khoản và điều kiện</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>1. Quy định tham gia</h6>
                    <p>- Sinh viên đăng ký tham gia sự kiện cần có thẻ sinh viên hoặc giấy tờ tùy thân.</p>
                    <p>- Tham gia đúng giờ và tuân thủ quy định của Ban tổ chức.</p>
                    
                    <h6>2. Quyền và trách nhiệm</h6>
                    <p>- Ban tổ chức có quyền điều chỉnh thời gian, địa điểm nếu cần thiết.</p>
                    <p>- Sinh viên tham gia có trách nhiệm bảo vệ tài sản tại địa điểm tổ chức sự kiện.</p>
                    
                    <h6>3. Hủy đăng ký</h6>
                    <p>- Sinh viên có thể hủy đăng ký trước 24 giờ khi sự kiện diễn ra.</p>
                    <p>- Sinh viên đăng ký nhưng không tham gia mà không có lý do chính đáng sẽ bị hạn chế tham gia các sự kiện tiếp theo.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Đồng ý</button>
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
    $(document).ready(function() {
        // Form validation
        $('form').on('submit', function(e) {
            if (!$('#termsCheck').is(':checked') || !$('#privacyCheck').is(':checked')) {
                e.preventDefault();
                alert('Vui lòng đồng ý với điều khoản và điều kiện để tiếp tục.');
                return false;
            }
            
            if ($('#phone').val().length < 10) {
                e.preventDefault();
                alert('Vui lòng nhập số điện thoại hợp lệ.');
                return false;
            }
            
            return true;
        });
    });
</script>
<?= $this->endSection() ?> 