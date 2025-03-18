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
                    <span class="badge bg-primary rounded-pill"><?= isset($registrationCount) ? number_format($registrationCount) : 0 ?></span>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><i class="lni lni-checkmark-circle text-primary me-2"></i> Đã tham gia</span>
                    <span class="badge bg-primary rounded-pill"><?= isset($attendedCount) ? number_format($attendedCount) : 0 ?></span>
                </li>
            </ul>
        </div>
    </div>