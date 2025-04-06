<?php
// Kiểm tra người dùng đã đăng nhập chưa và đã đăng ký sự kiện này chưa
$isLoggedIn = service('authstudent')->isLoggedInStudent();
$userData = $isLoggedIn ? service('authstudent')->getUserData() : null;
$isRegistered = false;
$registrationTime = null;

// Nếu đã đăng nhập và có dữ liệu sự kiện, kiểm tra đã đăng ký chưa
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
        $registrationTime = $checkRegistration->created_at ?? null;
    }
}

// Lấy chính xác số lượng đăng ký từ cơ sở dữ liệu
$dangKySuKienModel = new \App\Modules\quanlydangkysukien\Models\DangKySuKienModel();
$registration_total = $dangKySuKienModel->where('su_kien_id', $event['su_kien_id'])
                                      ->where('deleted_at IS NULL')
                                      ->countAllResults();

// Kiểm tra nếu có giá trị từ controller thì ưu tiên sử dụng
if (isset($registrationCount) && is_numeric($registrationCount)) {
    $registration_total = $registrationCount;
}

// Tính toán số liệu
$max_slots = $event['so_luong_tham_gia'] ?? 0;
$slots_left = max(0, $max_slots - $registration_total);
$percent = $max_slots > 0 ? min(100, round(($registration_total / $max_slots) * 100)) : 0;
$progressClass = $percent >= 80 ? 'bg-danger' : ($percent >= 50 ? 'bg-warning' : 'bg-success');

// Kiểm tra thời gian đăng ký
$registrationOpen = true;
$registrationMessage = '';
$registrationStatus = 'open'; // Trạng thái: 'open', 'upcoming', 'closed'
$timeRemaining = '';
$currentTime = time();

// Kiểm tra thời gian đăng ký từ các trường bat_dau_dang_ky và ket_thuc_dang_ky
if (isset($event['bat_dau_dang_ky']) && isset($event['ket_thuc_dang_ky']) && 
    !empty($event['bat_dau_dang_ky']) && !empty($event['ket_thuc_dang_ky'])) {
    
    $startTime = strtotime($event['bat_dau_dang_ky']);
    $endTime = strtotime($event['ket_thuc_dang_ky']);
    
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
            $registrationMessage = "Đăng ký mở sau $timeRemaining";
        } else if ($hoursToStart > 0) {
            $timeRemaining = $hoursToStart . ' giờ ' . $minutesToStart . ' phút';
            $registrationMessage = "Đăng ký mở sau $timeRemaining";
        } else {
            $timeRemaining = $minutesToStart . ' phút';
            $registrationMessage = "Đăng ký mở sau $timeRemaining";
        }
    } 
    // Nếu đã hết thời gian đăng ký
    else if ($currentTime > $endTime) {
        $registrationOpen = false;
        $registrationStatus = 'closed';
        $registrationMessage = 'Đã kết thúc thời gian đăng ký';
    } 
    // Đang trong thời gian đăng ký
    else {
        // Tính thời gian còn lại đến khi kết thúc đăng ký
        $timeToEnd = $endTime - $currentTime;
        $daysToEnd = floor($timeToEnd / (60 * 60 * 24));
        $hoursToEnd = floor(($timeToEnd % (60 * 60 * 24)) / (60 * 60));
        $minutesToEnd = floor(($timeToEnd % (60 * 60)) / 60);
        
        if ($daysToEnd > 0) {
            $timeRemaining = $daysToEnd . ' ngày ' . $hoursToEnd . ' giờ';
            $registrationMessage = "Còn $timeRemaining để đăng ký";
        } else if ($hoursToEnd > 0) {
            $timeRemaining = $hoursToEnd . ' giờ ' . $minutesToEnd . ' phút';
            $registrationMessage = "Còn $timeRemaining để đăng ký";
        } else {
            $timeRemaining = $minutesToEnd . ' phút';
            $registrationMessage = "Còn $timeRemaining để đăng ký";
        }
    }
} else {
    // Không có thông tin thời gian đăng ký
    $registrationMessage = 'Không có thông tin thời gian đăng ký';
}

// Kiểm tra nếu hết chỗ
if ($slots_left <= 0) {
    $registrationOpen = false;
    $registrationMessage = 'Hết chỗ đăng ký';
}
?>

<div class="mb-4">
    <h5 class="registration-title">
        <i class="fas fa-clipboard-list me-2 text-primary"></i>
        Tình trạng đăng ký
    </h5>
    
    <?php if ($isRegistered): ?>
    <!-- Hiển thị thông tin đã đăng ký -->
    <div class="alert alert-success mb-3 registration-success-card">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle fa-2x me-3 pulse-success"></i>
            </div>
            <div class="flex-grow-1">
                <strong>Bạn đã đăng ký sự kiện này!</strong>
                <?php if ($registrationTime): ?>
                <div class="small text-muted mt-1">Đăng ký lúc: <?= date('d/m/Y H:i', strtotime($registrationTime)) ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Hiển thị thông tin thời gian đăng ký -->
    <?php 
    $displayStartTime = null;
    $displayEndTime = null;
    
    if (isset($event['bat_dau_dang_ky']) && isset($event['ket_thuc_dang_ky']) && 
        !empty($event['bat_dau_dang_ky']) && !empty($event['ket_thuc_dang_ky'])) {
        $displayStartTime = $event['bat_dau_dang_ky'];
        $displayEndTime = $event['ket_thuc_dang_ky'];
    }
    
    if ($displayStartTime && $displayEndTime): 
    
    // Tạo ID duy nhất cho đồng hồ đếm ngược để có nhiều đồng hồ trên cùng trang
    $countdownId = 'countdown-' . rand(1000, 9999);
    ?>
    <div class="card border-0 shadow-sm mb-3 registration-time-card animate__animated animate__fadeIn">
        <div class="card-header <?= $registrationStatus == 'open' ? 'bg-success' : ($registrationStatus == 'upcoming' ? 'bg-info' : 'bg-secondary') ?> text-white">
            <h6 class="card-title m-0 text-white d-flex align-items-center">
                <i class="fas <?= $registrationStatus == 'open' ? 'fa-calendar-check' : ($registrationStatus == 'upcoming' ? 'fa-calendar-alt' : 'fa-calendar-times') ?> me-2"></i>
                <?php if ($registrationStatus == 'open'): ?>
                    <span class="status-pulse">Đang mở đăng ký</span>
                <?php elseif ($registrationStatus == 'upcoming'): ?>
                    Sắp mở đăng ký
                <?php else: ?>
                    Đã kết thúc đăng ký
                <?php endif; ?>
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-5">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Bắt đầu đăng ký</h6>
                            <div class="fw-bold"><?= date('d/m/Y', strtotime($displayStartTime)) ?></div>
                            <div><?= date('H:i', strtotime($displayStartTime)) ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Kết thúc đăng ký</h6>
                            <div class="fw-bold"><?= date('d/m/Y', strtotime($displayEndTime)) ?></div>
                            <div><?= date('H:i', strtotime($displayEndTime)) ?></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="d-flex h-100 align-items-center justify-content-center">
                        <?php if ($registrationStatus == 'open'): ?>
                            <span class="badge bg-success p-2 text-white">ĐANG MỞ</span>
                        <?php elseif ($registrationStatus == 'upcoming'): ?>
                            <span class="badge bg-info p-2 text-white">SẮP MỞ</span>
                        <?php else: ?>
                            <span class="badge bg-secondary p-2 text-white">ĐÃ ĐÓNG</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <?php if ($timeRemaining): ?>
            <div class="alert <?= $registrationStatus == 'open' ? 'alert-success time-alert' : 'alert-info' ?> mt-3 mb-0 d-flex align-items-center">
                <i class="fas fa-clock me-2 <?= $registrationStatus == 'open' ? 'fa-pulse' : '' ?>"></i>
                <div class="me-2"><?= str_replace($timeRemaining, '<span id="'.$countdownId.'" class="countdown-timer fw-bold">'.$timeRemaining.'</span>', $registrationMessage) ?></div>
                
                <?php if ($registrationStatus == 'upcoming'): ?>
                <div class="ms-auto">
                    <div class="small text-muted">Bắt đầu ngày <?= date('d/m/Y', strtotime($displayStartTime)) ?></div>
                </div>
                <?php elseif ($registrationStatus == 'open'): ?>
                <div class="ms-auto">
                    <div class="small text-muted">Kết thúc ngày <?= date('d/m/Y', strtotime($displayEndTime)) ?></div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Thêm script đếm ngược -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Set thời gian đích
                    <?php if ($registrationStatus == 'upcoming'): ?>
                    var targetTime = <?= $startTime ?> * 1000; // Convert to milliseconds
                    <?php else: ?>
                    var targetTime = <?= $endTime ?> * 1000; // Convert to milliseconds
                    <?php endif; ?>
                    
                    // Cập nhật đồng hồ đếm ngược mỗi giây
                    var countdownInterval = setInterval(function() {
                        var now = new Date().getTime();
                        var distance = targetTime - now;
                        
                        if (distance < 0) {
                            clearInterval(countdownInterval);
                            document.getElementById("<?= $countdownId ?>").innerHTML = "0 phút";
                            // Tải lại trang sau 3 giây
                            setTimeout(function() {
                                location.reload();
                            }, 3000);
                            return;
                        }
                        
                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        
                        var countdown = "";
                        if (days > 0) {
                            countdown = days + " ngày " + hours + " giờ";
                        } else if (hours > 0) {
                            countdown = hours + " giờ " + minutes + " phút";
                        } else {
                            countdown = minutes + " phút";
                        }
                        
                        document.getElementById("<?= $countdownId ?>").innerHTML = countdown;
                    }, 1000);
                });
            </script>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="card border-0 shadow-sm animate__animated animate__fadeIn">
        <div class="card-body">
            <!-- Hiển thị số liệu đăng ký chi tiết -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="registration-stat p-3 h-100 rounded bg-light">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-soft-primary me-3">
                                <i class="fas fa-users text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Tổng số chỗ</h6>
                                <h3 class="mb-0 fw-bold"><?= number_format($max_slots) ?> <small class="text-muted fs-6">chỗ</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="registration-stat p-3 h-100 rounded bg-light">
                        <div class="d-flex align-items-center">
                            <div class="stat-icon bg-soft-success me-3">
                                <i class="fas fa-user-check text-success"></i>
                            </div>
                            <div>
                                <h6 class="text-muted mb-1">Đã đăng ký</h6>
                                <h3 class="mb-0 fw-bold"><?= number_format($registration_total) ?> <small class="text-muted fs-6">người</small></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tiến trình đăng ký -->
            <div class="progress-section mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="fw-bold">Tiến độ đăng ký:</span>
                    <span class="badge bg-<?= $progressClass ?> rounded-pill"><?= $percent ?>%</span>
                </div>
                
                <!-- Progress bar -->
                <div class="progress" style="height: 15px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated <?= $progressClass ?>" 
                        role="progressbar" style="width: <?= $percent ?>%" 
                        aria-valuenow="<?= $registration_total ?>" 
                        aria-valuemin="0" 
                        aria-valuemax="<?= $max_slots ?>">
                    </div>
                </div>
                
                <!-- Hiển thị chỗ còn trống -->
                <div class="text-end mt-2">
                    <span class="fw-bold <?= $slots_left > 0 ? 'text-success' : 'text-danger' ?>">
                        <?php if ($slots_left > 0): ?>
                            <i class="fas fa-check-circle"></i> Còn trống: <?= number_format($slots_left) ?> chỗ
                        <?php else: ?>
                            <i class="fas fa-exclamation-circle"></i> Hết chỗ
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            
            <!-- Thông báo trạng thái -->
            <div class="mt-3">
                <?php if (!$registrationOpen): ?>
                    <div class="alert alert-warning mb-0 py-2 animate__animated animate__pulse">
                        <i class="fas fa-exclamation-triangle me-1"></i> <?= $registrationMessage ?>
                    </div>
                <?php elseif ($percent >= 90): ?>
                    <div class="alert alert-danger mb-0 py-2 animate__animated animate__pulse">
                        <i class="fas fa-alarm-clock me-1"></i> Sắp hết chỗ, hãy đăng ký ngay!
                    </div>
                <?php elseif ($percent >= 70): ?>
                    <div class="alert alert-warning mb-0 py-2">
                        <i class="fas fa-hourglass me-1"></i> Số lượng chỗ còn lại đang giảm dần
                    </div>
                <?php else: ?>
                    <div class="alert alert-success mb-0 py-2">
                        <i class="fas fa-check-circle me-1"></i> Còn nhiều chỗ trống
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
/* Cải thiện style */
.registration-title {
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
    position: relative;
    padding-bottom: 0.5rem;
}
.registration-title:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: linear-gradient(to right, #007bff, #6610f2);
}
.registration-stat {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}
.registration-stat:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.stat-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.bg-soft-primary {
    background-color: rgba(13, 110, 253, 0.15);
}
.bg-soft-success {
    background-color: rgba(25, 135, 84, 0.15);
}
.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.registration-time-card {
    border-left: 5px solid;
    border-left-color: var(--bs-primary);
    transition: all 0.3s ease;
}
.registration-time-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.registration-time-card .card-header {
    border-radius: 0;
    border-top-right-radius: 0.25rem;
}
.registration-success-card {
    animation: successPulse 2s ease-in-out;
}
.pulse-success {
    animation: successPulse 2s infinite;
}
.status-pulse {
    position: relative;
    animation: pulse 1.5s ease-in-out infinite;
}
.pulse-badge {
    animation: pulseLight 1.5s infinite;
}
.time-alert {
    border-left: 4px solid #198754;
}
.countdown-timer {
    font-size: 1.1em;
    color: #d63384;
    animation: countdownPulse 1s ease-in-out infinite;
}

/* Định nghĩa animations */
@keyframes successPulse {
    0% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(25, 135, 84, 0); }
    100% { box-shadow: 0 0 0 0 rgba(25, 135, 84, 0); }
}
@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}
@keyframes pulseLight {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}
@keyframes countdownPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Sử dụng một số class từ Animate.css */
.animate__animated {
    animation-duration: 1s;
    animation-fill-mode: both;
}
.animate__fadeIn {
    animation-name: fadeIn;
}
.animate__pulse {
    animation-name: pulse;
    animation-timing-function: ease-in-out;
    animation-duration: 1s;
    animation-iteration-count: infinite;
}
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>