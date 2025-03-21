<?= $this->extend('layouts/default') ?>

<?= $this->section('linkHref') ?>
<!-- Chart.js -->
<link rel="stylesheet" href="<?= base_url('assets/plugins/chart.js/Chart.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('title') ?>Quản lý loại người dùng<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => 'Tổng quan loại người dùng',
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['title' => 'Quản lý loại người dùng', 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('/loainguoidung/new'), 'title' => 'Thêm loại người dùng mới'],
        ['url' => site_url('/loainguoidung'), 'title' => 'Danh sách loại người dùng']
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section("content") ?>
<div class="row">
    <!-- Thống kê tổng quan -->
    <div class="col-12 mb-4">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Tổng quan loại người dùng</h6>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mt-3">
                    <div class="col">
                        <div class="card radius-10 border-start border-3 border-info">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Tổng số loại</p>
                                        <h4 class="my-1 text-info"><?= $totalAll ?></h4>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto">
                                        <i class="bx bxs-group"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border-start border-3 border-success">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Đang hoạt động</p>
                                        <h4 class="my-1 text-success"><?= $totalActive ?></h4>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto">
                                        <i class="bx bxs-check-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border-start border-3 border-warning">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Không hoạt động</p>
                                        <h4 class="my-1 text-warning"><?= $totalInactive ?></h4>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto">
                                        <i class="bx bxs-x-circle"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 border-start border-3 border-danger">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary">Đã xóa</p>
                                        <h4 class="my-1 text-danger"><?= $totalDeleted ?></h4>
                                    </div>
                                    <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto">
                                        <i class="bx bxs-trash"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Loại người dùng mới nhất -->
    <div class="col-12 col-lg-8">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Loại người dùng mới nhất</h6>
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tên loại</th>
                                <th>Mô tả</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentItems as $item): ?>
                            <tr>
                                <td><?= $item->getId() ?></td>
                                <td><?= esc($item->getTenLoai()) ?></td>
                                <td><?= esc($item->getMoTa()) ?></td>
                                <td>
                                    <?php if ($item->isActive()): ?>
                                        <span class="badge bg-success">Hoạt động</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Không hoạt động</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $item->getCreatedAtFormatted() ?></td>
                                <td>
                                    <div class="d-flex">
                                        <a href="<?= site_url('loainguoidung/edit/' . $item->getId()) ?>" class="btn btn-sm btn-primary me-2"><i class="bx bx-edit"></i></a>
                                        <a href="javascript:;" class="btn btn-sm btn-danger delete-item" data-id="<?= $item->getId() ?>"><i class="bx bx-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if (empty($recentItems)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Thống kê nhanh -->
    <div class="col-12 col-lg-4">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Phân bố loại người dùng</h6>
                    </div>
                </div>
                <div class="my-3">
                    <div class="chart-container" style="position: relative; height: 250px;">
                        <canvas id="loaiNguoiDungChart"></canvas>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <p class="mb-0 text-secondary">Hoạt động</p>
                        <p class="mb-0 text-success"><?= number_format(($totalActive / max(1, $totalAll)) * 100, 1) ?>%</p>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= ($totalActive / max(1, $totalAll)) * 100 ?>%"></div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <p class="mb-0 text-secondary">Không hoạt động</p>
                        <p class="mb-0 text-warning"><?= number_format(($totalInactive / max(1, $totalAll)) * 100, 1) ?>%</p>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?= ($totalInactive / max(1, $totalAll)) * 100 ?>%"></div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-2">
                        <p class="mb-0 text-secondary">Đã xóa</p>
                        <p class="mb-0 text-danger"><?= number_format(($totalDeleted / max(1, $totalAll + $totalDeleted)) * 100, 1) ?>%</p>
                    </div>
                    <div class="progress" style="height: 5px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: <?= ($totalDeleted / max(1, $totalAll + $totalDeleted)) * 100 ?>%"></div>
                    </div>
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
    // Biểu đồ phân bố loại người dùng
    var ctx = document.getElementById('loaiNguoiDungChart').getContext('2d');
    var loaiNguoiDungChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Hoạt động', 'Không hoạt động', 'Đã xóa'],
            datasets: [{
                data: [<?= $totalActive ?>, <?= $totalInactive ?>, <?= $totalDeleted ?>],
                backgroundColor: ['#36a2eb', '#ffcd56', '#ff6384'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
    
    // Xử lý nút xóa
    $('.delete-item').on('click', function() {
        var id = $(this).data('id');
        if (confirm('Bạn có chắc chắn muốn xóa mục này?')) {
            window.location.href = '<?= site_url('loainguoidung/delete/') ?>' + id;
        }
    });
});
</script>
<?= $this->endSection() ?> 