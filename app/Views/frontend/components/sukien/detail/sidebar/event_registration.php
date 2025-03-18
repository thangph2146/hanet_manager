<div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.3s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-stats-up me-2"></i> Thông tin đăng ký</h4>
        </div>
        <div class="card-body">
            <div class="text-center mb-3">
                <div class="stats-number"><?= isset($registrationCount) ? $registrationCount : 0 ?></div>
                <p class="mb-0">Người đã đăng ký</p>
            </div>
            <div class="progress mb-3" style="height: 10px;">
                <?php 
                    $percent = isset($registrationCount) && $event['so_luong_tham_gia'] > 0 
                        ? min(100, round(($registrationCount / $event['so_luong_tham_gia']) * 100)) 
                        : 0;
                ?>
                <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?= $percent ?>%" 
                    aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= $percent ?>%
                </div>
            </div>
            <p class="text-center text-muted small mb-4">
                <i class="lni lni-ticket me-1"></i> Còn <?= max(0, $event['so_luong_tham_gia'] - (isset($registrationCount) ? $registrationCount : 0)) ?> chỗ trống
            </p>
            <a href="#" class="btn btn-primary w-100 pulse" data-bs-toggle="tab" data-bs-target="#event-registration" role="tab" aria-controls="event-registration" aria-selected="false">
                <i class="lni lni-pencil me-2"></i> Đăng ký ngay
            </a>
        </div>
    </div>