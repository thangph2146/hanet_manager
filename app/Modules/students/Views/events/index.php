<?= $this->extend('App\Modules\layouts\student\Views\dashboard\layout') ?>

<?= $this->section('styles') ?>
<style>
    .event-card {
        transition: transform 0.3s;
        margin-bottom: 20px;
        height: 100%;
    }
    
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.12), 0 4px 8px rgba(0,0,0,0.06);
    }
    
    .event-banner {
        height: 180px;
        object-fit: cover;
    }
    
    .event-card .card-body {
        display: flex;
        flex-direction: column;
    }
    
    .event-card .card-text {
        flex-grow: 1;
    }
    
    .event-status {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 10;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
                <div class="input-group w-50">
                    <input type="text" class="form-control" placeholder="Tìm kiếm sự kiện...">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc sự kiện -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <select class="form-control">
                                <option value="">Tất cả trạng thái</option>
                                <option value="upcoming">Sắp diễn ra</option>
                                <option value="registration">Đang mở đăng ký</option>
                                <option value="past">Đã kết thúc</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-control">
                                <option value="">Tất cả địa điểm</option>
                                <option value="hall_a">Hội trường A</option>
                                <option value="hall_b">Hội trường B</option>
                                <option value="lab">Phòng Lab</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control" placeholder="Ngày tổ chức">
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <button class="btn btn-primary btn-block">Lọc</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(empty($events)): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-1"></i> Không có sự kiện nào hiện tại. Vui lòng quay lại sau.
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="row">
        <?php foreach($events as $event): ?>
        <div class="col-lg-4 col-md-6">
            <div class="card shadow event-card">
                <span class="badge badge-<?= $event['status_color'] ?? 'primary' ?> event-status"><?= $event['status'] ?></span>
                <img class="card-img-top event-banner" src="<?= base_url('assets/modules/students/img/events/'.$event['banner']) ?>" alt="<?= $event['name'] ?>">
                <div class="card-body">
                    <h5 class="card-title"><?= $event['name'] ?></h5>
                    <p class="card-text text-muted">
                        <i class="fas fa-calendar-alt mr-1"></i> <?= $event['time'] ?><br>
                        <i class="fas fa-map-marker-alt mr-1"></i> <?= $event['location'] ?>
                    </p>
                    <p class="card-text"><?= $event['description'] ?></p>
                    <div class="mt-auto">
                        <a href="<?= base_url('students/events/'.$event['id']) ?>" class="btn btn-outline-primary btn-sm btn-block">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Phân trang -->
    <div class="row mt-4">
        <div class="col-12">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Trước</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    console.log('Events page loaded!');
</script>
<?= $this->endSection() ?> 