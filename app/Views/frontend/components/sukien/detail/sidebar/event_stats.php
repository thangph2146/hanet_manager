<?php
// Lấy thống kê tham gia từ model nếu chưa có sẵn
if (!isset($registrationCount) && isset($event['su_kien_id'])) {
    $dangKySuKienModel = new \App\Modules\quanlydangkysukien\Models\DangKySuKienModel();
    
    // Số người đăng ký
    $registrationCount = isset($event['tong_dang_ky']) ? $event['tong_dang_ky'] : 
        $dangKySuKienModel->countRegistrationsByEvent($event['su_kien_id']);
    
    // Số người tham gia (check-in)
    $attendedCount = isset($event['tong_check_in']) ? $event['tong_check_in'] : 
        $dangKySuKienModel->countRegistrationsByEvent($event['su_kien_id'], ['da_check_in' => 1]);
    
    // Số người hoàn thành (check-out)
    $completedCount = isset($event['tong_check_out']) ? $event['tong_check_out'] : 
        $dangKySuKienModel->countRegistrationsByEvent($event['su_kien_id'], ['da_check_out' => 1]);
}

// Đảm bảo các biến đã được định nghĩa
$registrationCount = $registrationCount ?? 0;
$attendedCount = $attendedCount ?? 0;
$completedCount = $completedCount ?? 0;
?>

<div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.35s;">
    <div class="card-header text-white py-3">
        <h4 class="card-title mb-0"><i class="lni lni-bar-chart me-2"></i> Thống kê sự kiện</h4>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="lni lni-eye text-primary me-2"></i> Lượt xem</span>
                <span class="badge bg-primary rounded-pill"><?= isset($event['so_luot_xem']) ? number_format($event['so_luot_xem']) : 0 ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="lni lni-users text-primary me-2"></i> Người đăng ký</span>
                <span class="badge bg-primary rounded-pill"><?= number_format($registrationCount) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="lni lni-checkmark-circle text-primary me-2"></i> Đã tham gia</span>
                <span class="badge bg-primary rounded-pill"><?= number_format($attendedCount) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="lni lni-certificate text-primary me-2"></i> Đã hoàn thành</span>
                <span class="badge bg-primary rounded-pill"><?= number_format($completedCount) ?></span>
            </li>
            
            <?php if ($registrationCount > 0 && isset($event['so_luong_tham_gia']) && $event['so_luong_tham_gia'] > 0): ?>
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span><i class="lni lni-spinner text-primary me-2"></i> Tỷ lệ đăng ký</span>
                    <span class="small"><?= min(100, round(($registrationCount / $event['so_luong_tham_gia']) * 100)) ?>%</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" 
                         style="width: <?= min(100, round(($registrationCount / $event['so_luong_tham_gia']) * 100)) ?>%;" 
                         aria-valuenow="<?= $registrationCount ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="<?= $event['so_luong_tham_gia'] ?>">
                    </div>
                </div>
            </li>
            <?php endif; ?>
            
            <?php if ($registrationCount > 0 && $attendedCount > 0): ?>
            <li class="list-group-item">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span><i class="lni lni-spinner text-primary me-2"></i> Tỷ lệ tham gia</span>
                    <span class="small"><?= min(100, round(($attendedCount / $registrationCount) * 100)) ?>%</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-info" role="progressbar" 
                         style="width: <?= min(100, round(($attendedCount / $registrationCount) * 100)) ?>%;" 
                         aria-valuenow="<?= $attendedCount ?>" 
                         aria-valuemin="0" 
                         aria-valuemax="<?= $registrationCount ?>">
                    </div>
                </div>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</div>