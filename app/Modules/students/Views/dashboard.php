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
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Trang chủ</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Xin chào, <?= session()->get('student_name') ?? 'Sinh viên' ?>!</h1>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
    <div class="col">
        <div class="card dashboard-card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Sự kiện đang diễn ra</p>
                        <h4 class="my-1"><?= $active_events ?? 5 ?></h4>
                        <p class="mb-0 font-13 text-success"><i class="bx bxs-up-arrow align-middle"></i> <span>Đang diễn ra</span></p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-white ms-auto"><i class="bx bx-calendar card-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card dashboard-card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Đã đăng ký</p>
                        <h4 class="my-1"><?= $registered_events ?? 3 ?></h4>
                        <p class="mb-0 font-13 text-info"><i class="bx bxs-calendar-check align-middle"></i> <span>Sự kiện</span></p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-success text-white ms-auto"><i class="bx bx-user-check card-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card dashboard-card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Chứng chỉ đã nhận</p>
                        <h4 class="my-1"><?= $certificates ?? 2 ?></h4>
                        <p class="mb-0 font-13 text-warning"><i class="bx bxs-badge-check align-middle"></i> <span>Chứng chỉ</span></p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-warning text-white ms-auto"><i class="bx bx-certification card-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card dashboard-card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Sự kiện sắp diễn ra</p>
                        <h4 class="my-1"><?= $upcoming_events ?? 8 ?></h4>
                        <p class="mb-0 font-13 text-danger"><i class="bx bxs-time-five align-middle"></i> <span>Sắp diễn ra</span></p>
                    </div>
                    <div class="widgets-icons-2 rounded-circle bg-gradient-danger text-white ms-auto"><i class="bx bx-calendar-event card-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12 col-lg-8">
        <div class="card radius-10">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Sự kiện sắp diễn ra</h6>
                    </div>
                    <div class="ms-auto">
                        <a href="<?= base_url('students/events') ?>" class="btn btn-sm btn-primary">Xem tất cả</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
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
                            <?php if (isset($events) && is_array($events) && count($events) > 0): ?>
                                <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td><?= $event['name'] ?></td>
                                        <td><?= $event['time'] ?></td>
                                        <td><?= $event['location'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $event['status_color'] ?>"><?= $event['status'] ?></span>
                                        </td>
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
    <div class="col-12 col-lg-4">
        <div class="card radius-10">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">Thông báo mới nhất</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="notifications-list">
                    <?php if (isset($recent_notifications) && is_array($recent_notifications) && count($recent_notifications) > 0): ?>
                        <?php foreach ($recent_notifications as $notification): ?>
                            <div class="notification-item d-flex mb-3">
                                <div class="notify bg-light-<?= $notification['type'] ?> text-<?= $notification['type'] ?>">
                                    <i class="<?= $notification['icon'] ?>"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="msg-name"><?= $notification['title'] ?></h6>
                                    <p class="msg-info"><?= $notification['content'] ?></p>
                                    <small class="time-info"><?= $notification['time'] ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="bx bx-bell-off fs-1 text-secondary"></i>
                            <p class="mt-2">Không có thông báo mới</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Thêm JavaScript riêng cho trang này nếu cần -->
<script>
    $(document).ready(function() {
        console.log('Trang dashboard đã sẵn sàng');
    });
</script>
<?= $this->endSection() ?> 