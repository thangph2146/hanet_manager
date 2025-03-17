<?= $this->extend('App\Modules\layouts\student\Views\dashboard\layout') ?>

<?= $this->section('styles') ?>
<!-- Thêm CSS riêng cho trang này nếu cần -->
<style>
    .dashboard-card {
        border-radius: 10px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .card-icon {
        font-size: 2.5rem;
        color: #4e5bf2;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <h4>Xin chào, <?= $student_data['fullname'] ?? 'Sinh viên' ?>!</h4>
        <p class="text-secondary mb-4">Dưới đây là tổng quan về hoạt động của bạn.</p>
    </div>
</div>

<!-- Thống kê tổng quan -->
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-3 mb-4">
    <div class="col">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light">
                        <i class="bx bx-calendar-event text-primary"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0"><?= $active_events ?? 0 ?></h5>
                        <p class="mb-0">Sự kiện đang diễn ra</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light">
                        <i class="bx bx-check-circle text-success"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0"><?= $registered_events ?? 0 ?></h5>
                        <p class="mb-0">Sự kiện đã đăng ký</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light">
                        <i class="bx bx-certification text-warning"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0"><?= $certificates ?? 0 ?></h5>
                        <p class="mb-0">Chứng chỉ đã nhận</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card dashboard-card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light">
                        <i class="bx bx-time text-info"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0"><?= $upcoming_events ?? 0 ?></h5>
                        <p class="mb-0">Sự kiện sắp diễn ra</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sự kiện sắp diễn ra -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Sự kiện sắp diễn ra</h5>
                    <a href="<?= base_url('students/events') ?>" class="btn btn-sm btn-link ms-auto">Xem tất cả</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tên sự kiện</th>
                                <th>Thời gian</th>
                                <th>Địa điểm</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($events) && count($events) > 0): ?>
                                <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td><?= $event['name'] ?></td>
                                        <td><?= $event['time'] ?></td>
                                        <td><?= $event['location'] ?></td>
                                        <td><span class="badge bg-<?= $event['status_color'] ?>"><?= $event['status'] ?></span></td>
                                        <td>
                                            <a href="<?= base_url('students/events/view/' . $event['id']) ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">Không có sự kiện nào sắp diễn ra</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Thông báo gần đây -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <h5 class="mb-0">Thông báo mới nhất</h5>
                    <a href="#" class="btn btn-sm btn-link ms-auto">Xem tất cả</a>
                </div>
            </div>
            <div class="card-body">
                <?php if (isset($recent_notifications) && count($recent_notifications) > 0): ?>
                    <?php foreach ($recent_notifications as $notification): ?>
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            <div class="text-<?= $notification['type'] ?> me-3">
                                <i class="<?= $notification['icon'] ?> fs-4"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><?= $notification['title'] ?></h6>
                                <p class="mb-0"><?= $notification['content'] ?></p>
                                <small class="text-muted"><?= $notification['time'] ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Không có thông báo mới</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // JavaScript cho dashboard
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard loaded');
    });
</script>
<?= $this->endSection() ?> 