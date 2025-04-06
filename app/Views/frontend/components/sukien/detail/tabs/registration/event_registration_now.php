<?php 
// Kiểm tra người dùng đã đăng nhập chưa
$isLoggedIn = service('authstudent')->isLoggedInStudent();
$userData = $isLoggedIn ? service('authstudent')->getUserData() : null;

// Biến để kiểm tra đã đăng ký chưa
$isRegistered = false;
$registrationMessage = '';

// Kiểm tra thời gian đăng ký
$registrationOpen = true;
$registrationStatus = '';
$currentTime = time();


// Kiểm tra các trường bat_dau_dang_ky và ket_thuc_dang_ky
if (isset($event['bat_dau_dang_ky']) && isset($event['ket_thuc_dang_ky']) && 
         !empty($event['bat_dau_dang_ky']) && !empty($event['ket_thuc_dang_ky'])) {
    
    $startTime = strtotime($event['bat_dau_dang_ky']);
    $endTime = strtotime($event['ket_thuc_dang_ky']);
    $registrationTimeSource = 'old'; // Đánh dấu đang sử dụng trường cũ
    
    // Nếu chưa tới thời gian đăng ký
    if ($currentTime < $startTime) {
        $registrationOpen = false;
        $registrationStatus = 'upcoming';
        
        // Tính thời gian còn lại đến khi mở đăng ký
        $timeToStart = $startTime - $currentTime;
        $daysToStart = floor($timeToStart / (60 * 60 * 24));
        $hoursToStart = floor(($timeToStart % (60 * 60 * 24)) / (60 * 60));
        $minutesToStart = floor(($timeToStart % (60 * 60)) / 60);
        
        if ($daysToStart > 0) {
            $timeRemaining = $daysToStart . ' ngày ' . $hoursToStart . ' giờ';
        } else if ($hoursToStart > 0) {
            $timeRemaining = $hoursToStart . ' giờ ' . $minutesToStart . ' phút';
        } else {
            $timeRemaining = $minutesToStart . ' phút';
        }
        $registrationClosedReason = "Đăng ký sẽ bắt đầu sau: <strong>$timeRemaining</strong>";
    } 
    // Nếu đã hết thời gian đăng ký
    else if ($currentTime > $endTime) {
        $registrationOpen = false;
        $registrationStatus = 'closed';
        $registrationClosedReason = "Đã kết thúc thời gian đăng ký (kết thúc lúc: " . date('d/m/Y H:i', $endTime) . ")";
    } else {
        $registrationStatus = 'open';
        // Tính thời gian còn lại trước khi đóng đăng ký
        $timeToEnd = $endTime - $currentTime;
        $daysToEnd = floor($timeToEnd / (60 * 60 * 24));
        $hoursToEnd = floor(($timeToEnd % (60 * 60 * 24)) / (60 * 60));
        $minutesToEnd = floor(($timeToEnd % (60 * 60)) / 60);
        
        if ($daysToEnd > 0) {
            $timeRemaining = $daysToEnd . ' ngày ' . $hoursToEnd . ' giờ';
        } else if ($hoursToEnd > 0) {
            $timeRemaining = $hoursToEnd . ' giờ ' . $minutesToEnd . ' phút';
        } else {
            $timeRemaining = $minutesToEnd . ' phút';
        }
        $registrationTimeInfo = "Thời hạn đăng ký còn: <strong>$timeRemaining</strong>";
    }
} else {
    // Không có thông tin thời gian đăng ký
    $registrationStatus = 'unknown';
    $registrationTimeSource = 'none';
}

// Nếu đã đăng nhập và có thông tin sự kiện, kiểm tra đã đăng ký chưa
if ($isLoggedIn && $userData && isset($event['su_kien_id'])) {
    // Lấy email của người dùng
    $userEmail = $userData->Email ?? '';
    
    // Khởi tạo model đăng ký sự kiện
    $dangKySuKienModel = new \App\Modules\quanlydangkysukien\Models\DangKySuKienModel();
    
    // Kiểm tra đã đăng ký chưa dựa trên email
    $checkRegistration = $dangKySuKienModel->where('su_kien_id', $event['su_kien_id'])
                                         ->where('email', $userEmail)
                                         ->first();
    
    // Nếu đã đăng ký
    if ($checkRegistration) {
        $isRegistered = true;
        $registrationMessage = 'Bạn đã đăng ký tham gia sự kiện này.';
        $registrationTime = $checkRegistration->created_at ?? null;
    }
}

// Tạo ID duy nhất cho đồng hồ đếm ngược
$countdownId = 'registration-countdown-' . rand(1000, 9999);

if ($isLoggedIn): ?>
                <!-- Người dùng đã đăng nhập - Hiển thị nút đăng ký ngay hoặc thông báo đã đăng ký -->
                <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeIn">
                        <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <?php if ($isRegistered): ?>
                                <div class="registration-success-icon">
                                    <i class="fas fa-check-circle text-success pulse-icon"></i>
                                </div>
                            <?php else: ?>
                                <div class="registration-user-icon mb-3">
                                    <i class="fas fa-user-check text-primary"></i>
                                </div>
                            <?php endif; ?>
                            
                            <h4 class="mb-3"><?= $userData ? 'Xin chào, ' . ($userData->FullName ?? 'bạn') : 'Bạn đã đăng nhập thành công' ?></h4>
                        </div>
                        
                        <?php if ($isRegistered): ?>
                            <!-- Hiển thị thông báo đã đăng ký -->
                            <div class="alert alert-success mb-4 registration-success-alert">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-check-circle fa-2x me-3"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong><?= $registrationMessage ?></strong>
                                        <?php if (isset($registrationTime)): ?>
                                        <div class="small text-muted mt-1">Thời gian đăng ký: <?= date('d/m/Y H:i', strtotime($registrationTime)) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hiển thị thông tin về sự kiện -->
                            <div class="registration-info-box p-3 bg-light rounded mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <h6 class="mb-0">Thông tin sự kiện</h6>
                                </div>
                                <div class="ps-4">
                                    <div class="mb-2"><strong>Sự kiện:</strong> <?= $event['ten_su_kien'] ?? 'Không xác định' ?></div>
                                    <div class="mb-2"><strong>Thời gian:</strong> <?= isset($event['thoi_gian_bat_dau']) ? date('d/m/Y H:i', strtotime($event['thoi_gian_bat_dau'])) : 'Không xác định' ?></div>
                                    <div><strong>Địa điểm:</strong> <?= $event['dia_diem'] ?? 'Không xác định' ?></div>
                                </div>
                            </div>
                            
                        <?php elseif (!$registrationOpen): ?>
                            <!-- Hiển thị thông báo đăng ký không khả dụng -->
                            <div class="alert alert-warning mb-4 animate__animated animate__pulse">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="alert-heading">Đăng ký chưa mở!</h5>
                                        <p class="mb-0"><?= $registrationClosedReason ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($registrationStatus == 'upcoming'): ?>
                            <!-- Đồng hồ đếm ngược -->
                            <div class="countdown-container text-center mb-4">
                                <div class="countdown-label">Thời gian còn lại đến khi mở đăng ký</div>
                                <div id="<?= $countdownId ?>" class="countdown-display">
                                    <div class="countdown-time"><?= $timeRemaining ?></div>
                                </div>
                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var targetTime = <?= $startTime ?> * 1000;
                                    var countdownInterval = setInterval(function() {
                                        var now = new Date().getTime();
                                        var distance = targetTime - now;
                                        
                                        if (distance < 0) {
                                            clearInterval(countdownInterval);
                                            document.getElementById("<?= $countdownId ?>").innerHTML = '<div class="countdown-time">0 phút</div>';
                                            // Reload sau 3 giây
                                            setTimeout(function() { location.reload(); }, 3000);
                                            return;
                                        }
                                        
                                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        
                                        var countdownText = "";
                                        if (days > 0) {
                                            countdownText = days + " ngày " + hours + " giờ";
                                        } else if (hours > 0) {
                                            countdownText = hours + " giờ " + minutes + " phút";
                                        } else {
                                            countdownText = minutes + " phút";
                                        }
                                        
                                        document.getElementById("<?= $countdownId ?>").innerHTML = '<div class="countdown-time">' + countdownText + '</div>';
                                    }, 1000);
                                });
                                </script>
                            </div>
                            <?php endif; ?>
                            
                            <!-- Thông tin về thời gian đăng ký -->
                            <div class="registration-time-info p-3 rounded">
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-plus text-primary me-2"></i>
                                            <div>
                                                <div class="small text-muted">Bắt đầu đăng ký</div>
                                                <strong><?= date('d/m/Y H:i', $startTime) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-calendar-times text-danger me-2"></i>
                                            <div>
                                                <div class="small text-muted">Kết thúc đăng ký</div>
                                                <strong><?= date('d/m/Y H:i', $endTime) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <!-- Hiển thị form đăng ký -->
                            <div class="alert alert-info mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-info-circle fa-lg me-3"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="mb-0">Bạn có thể đăng ký tham gia sự kiện ngay bây giờ</p>
                                        <?php if (isset($registrationTimeInfo)): ?>
                                        <div class="mt-1 text-primary"><i class="fas fa-clock me-1"></i> <?= $registrationTimeInfo ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="registration-form mb-3">
                                <form method="post" action="<?= base_url('/su-kien/register-now') ?>" class="needs-validation" novalidate>
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="su_kien_id" value="<?= isset($event['su_kien_id']) ? $event['su_kien_id'] : '' ?>">
                                    <input type="hidden" name="ho_ten" value="<?= $userData ? ($userData->FullName ?? '') : '' ?>">
                                    <input type="hidden" name="email" value="<?= $userData ? ($userData->Email ?? '') : '' ?>">
                                    <input type="hidden" name="so_dien_thoai" value="<?= $userData ? ($userData->MobilePhone ?? '') : '' ?>">
                                    
                                    <div class="user-info-summary mb-4">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="info-label">Họ tên:</div>
                                                    <div class="info-value"><?= $userData ? ($userData->FullName ?? 'Không có') : 'Không có' ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <div class="d-flex align-items-center">
                                                    <div class="info-label">Email:</div>
                                                    <div class="info-value"><?= $userData ? ($userData->Email ?? 'Không có') : 'Không có' ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="info-label">Điện thoại:</div>
                                                    <div class="info-value"><?= $userData ? ($userData->MobilePhone ?? 'Không có') : 'Không có' ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-danger btn-lg register-button animate__animated animate__pulse">
                                            <i class="fas fa-check-circle me-2"></i> Đăng ký ngay
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="registration-note">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i> 
                                    Bằng cách nhấn "Đăng ký ngay", bạn đồng ý tham gia sự kiện và chia sẻ thông tin cá nhân cho ban tổ chức.
                                </small>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
            <?php else: ?>
                <!-- Người dùng chưa đăng nhập - Hiển thị nút đăng nhập -->
                <div class="card border-0 shadow-sm mb-4 animate__animated animate__fadeIn">
                    <div class="card-body p-5 text-center">
                        <div class="mb-4">
                            <img src="<?= base_url('assets/images/login-illustration.svg') ?>" alt="Đăng nhập" class="img-fluid" style="max-height: 200px;">
                        </div>
                        <h4 class="mb-3">Đăng nhập để tiếp tục</h4>
                        <p class="mb-4 text-muted">Đăng nhập tài khoản để đăng ký tham gia sự kiện một cách nhanh chóng và thuận tiện hơn</p>
                        <div class="d-grid gap-2 col-md-8 mx-auto">
                            <a href="<?= base_url('login/nguoi-dung?redirect=' . current_url()) ?>" class="btn btn-danger btn-lg animate__animated animate__pulse">
                                <i class="fas fa-sign-in-alt me-2"></i> Đăng nhập để tiếp tục
                            </a>
                            <a href="<?= base_url('dang-ky?redirect=' . current_url()) ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-user-plus me-2"></i> Đăng ký tài khoản mới
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
<style>
/* Thêm màu đỏ đô tùy chỉnh */
:root {
    --bs-danger-rgb: 128, 0, 0;
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

/* Cải thiện style cho thẻ đăng ký */
.registration-success-icon {
    font-size: 5rem;
    color: #198754;
    margin-bottom: 1.5rem;
    animation: successPulse 2s infinite;
}

.registration-user-icon {
    font-size: 4rem;
    color: #0d6efd;
    margin-bottom: 1.5rem;
}

.pulse-icon {
    animation: successPulse 2s infinite;
}

.registration-success-alert {
    border-left: 4px solid #198754;
    animation: successFadeIn 1s;
}

.registration-info-box {
    border-left: 4px solid #0d6efd;
}

.registration-time-info {
    background-color: #f8f9fa;
    border-left: 4px solid #6c757d;
}

.countdown-container {
    background-color: #f0f7ff;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    border-left: 4px solid #0dcaf0;
}

.countdown-label {
    color: #6c757d;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.countdown-display {
    font-size: 1.5rem;
    font-weight: 700;
    color: #0d6efd;
}

.countdown-time {
    animation: countdownPulse 1s infinite;
}

.info-label {
    min-width: 85px;
    font-weight: 600;
    color: #6c757d;
}

.info-value {
    flex-grow: 1;
    padding-left: 0.5rem;
}

.user-info-summary {
    background-color: #f8f9fa;
    padding: 1rem;
    border-radius: 0.5rem;
    border-left: 4px solid #0d6efd;
}

.register-button {
    transition: all 0.3s ease;
}

.register-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.registration-note {
    margin-top: 1rem;
    padding: 0.5rem;
    border-top: 1px solid #eee;
}

/* Định nghĩa animations */
@keyframes successPulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

@keyframes successFadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes countdownPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Animate.css classes */
.animate__animated {
    animation-duration: 1s;
    animation-fill-mode: both;
}

.animate__fadeIn {
    animation-name: fadeIn;
}

.animate__pulse {
    animation-name: pulse;
    animation-duration: 2s;
    animation-iteration-count: infinite;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}
</style>