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

if (isset($event['bat_dau_dang_ky']) && isset($event['ket_thuc_dang_ky'])) {
    $currentTime = time();
    $startTime = strtotime($event['bat_dau_dang_ky']);
    $endTime = strtotime($event['ket_thuc_dang_ky']);
    
    if ($currentTime < $startTime) {
        $registrationOpen = false;
        $registrationMessage = 'Chưa mở đăng ký';
        $daysToStart = ceil(($startTime - $currentTime) / (60 * 60 * 24));
        $registrationMessage .= ' (còn ' . $daysToStart . ' ngày)';
    } elseif ($currentTime > $endTime) {
        $registrationOpen = false;
        $registrationMessage = 'Đã kết thúc đăng ký';
    } elseif ($slots_left <= 0) {
        $registrationOpen = false;
        $registrationMessage = 'Hết chỗ đăng ký';
    }
}
?>

<div class="mb-4">
    <h5>Tình trạng đăng ký</h5>
    
    <?php if ($isRegistered): ?>
    <!-- Hiển thị thông tin đã đăng ký -->
    <div class="alert alert-success mb-3">
        <div class="d-flex align-items-center">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle fa-2x me-3"></i>
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
    
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <!-- Hiển thị số liệu đăng ký chi tiết -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="registration-stat p-3 h-100 rounded bg-light">
                        <div class="d-flex align-items-center">
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
                    <div class="alert alert-warning mb-0 py-2">
                        <i class="fas fa-exclamation-triangle me-1"></i> <?= $registrationMessage ?>
                    </div>
                <?php elseif ($percent >= 90): ?>
                    <div class="alert alert-danger mb-0 py-2">
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
            
            <!-- Thời gian đăng ký -->
            <?php if (isset($event['bat_dau_dang_ky']) && isset($event['ket_thuc_dang_ky'])): ?>
            <div class="registration-time-info mt-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-light rounded p-2 me-2">
                                <i class="fas fa-calendar-plus text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Bắt đầu đăng ký</small>
                                <strong><?= date('d/m/Y H:i', strtotime($event['bat_dau_dang_ky'])) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <div class="icon-box bg-light rounded p-2 me-2">
                                <i class="fas fa-calendar-minus text-danger"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Kết thúc đăng ký</small>
                                <strong><?= date('d/m/Y H:i', strtotime($event['ket_thuc_dang_ky'])) ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.registration-stat {
    transition: all 0.3s ease;
    border: 1px solid rgba(0,0,0,0.05);
}
.registration-stat:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.icon-box {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
</style>