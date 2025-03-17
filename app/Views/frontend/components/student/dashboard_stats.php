<?php
$stats = [
    [
        'icon' => 'chart-line',
        'bg' => 'primary',
        'title' => 'Bài tập',
        'value' => '245k',
        'growth' => '+28.14%'
    ],
    [
        'icon' => 'users',
        'bg' => 'success',
        'title' => 'Sự kiện',
        'value' => '12.5k',
        'growth' => '+42.7%'  
    ],
    [
        'icon' => 'box',
        'bg' => 'warning',
        'title' => 'Dự án',
        'value' => '1.54k',
        'growth' => '+38%'
    ],
    [
        'icon' => 'dollar-sign',
        'bg' => 'info',
        'title' => 'Điểm',
        'value' => '88k',
        'growth' => '+48.5%'
    ]
];
?>

<!-- Welcome Card -->
<div class="row mb-4">
    <div class="col-md-8 col-lg-6 mb-4 mb-md-0">
        <div class="card welcome-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="card-title">Chúc mừng John! 🎉</h5>
                        <p class="card-text text-secondary mb-0">Sinh viên xuất sắc của tháng</p>
                    </div>
                    <img src="<?= base_url('assets/images/icons/trophy.png') ?>" alt="Trophy" width="70">
                </div>
                
                <h3 class="text-primary mb-1">$42.8k</h3>
                <p class="mb-2">78% của mục tiêu 🚀</p>
                
                <div class="target-progress">
                    <div class="progress-bar" style="width: 78%"></div>
                </div>
                
                <a href="#" class="btn btn-primary btn-sm mt-3">Xem thành tích</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4 col-lg-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0">Hoạt động gần đây</h5>
                    <div class="dropdown">
                        <button class="card-action-btn" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Xem tất cả</a>
                            <a class="dropdown-item" href="#">Làm mới</a>
                        </div>
                    </div>
                </div>
                
                <p class="text-success mb-2">
                    <i class="fas fa-arrow-up me-1"></i> 
                    Tổng <?= $stats[3]['growth'] ?> tăng trưởng 📈 tháng này
                </p>
                
                <div class="row mt-4">
                    <?php foreach($stats as $stat): ?>
                    <div class="col-6 col-md-3 mb-4 mb-md-0">
                        <div class="stats-card">
                            <div class="stats-icon bg-<?= $stat['bg'] ?>-light">
                                <i class="fas fa-<?= $stat['icon'] ?>"></i>
                            </div>
                            <div class="stats-info">
                                <p class="stats-title"><?= $stat['title'] ?></p>
                                <h5 class="stats-number"><?= $stat['value'] ?></h5>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div> 