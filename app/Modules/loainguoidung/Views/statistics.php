<?= $this->extend('layouts/default') ?>

<?= $this->section('linkHref') ?>
<!-- Chart.js -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/chart.js/Chart.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('title') ?>Thống kê loại người dùng<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Thống kê loại người dùng',
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý loại người dùng', 'url' => site_url('loainguoidung/dashboard')],
        ['title' => 'Thống kê', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/loainguoidung/dashboard'), 'title' => 'Tổng quan'],
        ['url' => site_url('/loainguoidung'), 'title' => 'Danh sách loại người dùng']
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="row">
    <!-- Biểu đồ theo thời gian -->
    <div class="col-12 mb-4">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Biểu đồ loại người dùng theo thời gian</h6>
                    </div>
                </div>
                <div class="chart-container-1 mt-4">
                    <canvas id="timelineChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Các thông tin thống kê khác -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Dữ liệu thống kê theo tháng</h6>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tháng</th>
                                <th>Số lượng</th>
                                <th>Biểu đồ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $maxValue = 1;
                            foreach ($stats as $stat) {
                                if ($stat['count'] > $maxValue) {
                                    $maxValue = $stat['count'];
                                }
                            }
                            ?>
                            
                            <?php foreach ($stats as $stat): ?>
                            <tr>
                                <td><?= $stat['label'] ?></td>
                                <td><?= $stat['count'] ?></td>
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar bg-gradient-blues" role="progressbar" 
                                             style="width: <?= ($stat['count'] / max(1, $maxValue)) * 100 ?>%"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if (empty($stats)): ?>
                            <tr>
                                <td colspan="3" class="text-center">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Widget tổng hợp -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Phân tích xu hướng</h6>
                    </div>
                </div>
                <div class="chart-container mt-4">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<!-- Chart.js -->
<script src="<?= base_url('assets/plugins/chart.js/Chart.bundle.min.js') ?>"></script>

<script>
$(document).ready(function() {
    // Dữ liệu cho biểu đồ theo thời gian
    var timelineData = {
        labels: [
            <?php foreach ($stats as $stat): ?>
            '<?= $stat['label'] ?>',
            <?php endforeach; ?>
        ],
        datasets: [{
            label: 'Số lượng loại người dùng',
            data: [
                <?php foreach ($stats as $stat): ?>
                <?= $stat['count'] ?>,
                <?php endforeach; ?>
            ],
            fill: false,
            borderColor: '#36a2eb',
            tension: 0.1
        }]
    };
    
    // Biểu đồ theo thời gian
    var timelineCtx = document.getElementById('timelineChart').getContext('2d');
    var timelineChart = new Chart(timelineCtx, {
        type: 'line',
        data: timelineData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    
    // Biểu đồ xu hướng
    var trendCtx = document.getElementById('trendChart').getContext('2d');
    var trendChart = new Chart(trendCtx, {
        type: 'bar',
        data: {
            labels: [
                <?php foreach ($stats as $stat): ?>
                '<?= $stat['label'] ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                label: 'Số lượng loại người dùng',
                data: [
                    <?php foreach ($stats as $stat): ?>
                    <?= $stat['count'] ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?> 