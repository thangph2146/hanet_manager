<?= $this->extend('Modules/students/Views/layouts/layout') ?>

<?= $this->section('styles') ?>
<style>
    .nav-pills .nav-link.active {
        background-color: var(--primary-color);
    }
    .event-list-item {
        transition: all 0.3s;
    }
    .event-list-item:hover {
        background-color: rgba(67, 97, 238, 0.05);
        transform: translateX(5px);
    }
    .certificate-badge {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 1rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 mb-2">Sự kiện đã đăng ký</h1>
                    <p class="text-muted">Quản lý các sự kiện mà bạn đã đăng ký tham gia</p>
                </div>
                
                <div class="d-flex gap-2">
                    <a href="<?= base_url('students/events') ?>" class="btn btn-outline-primary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Status Filter Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills nav-fill flex-column flex-sm-row">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-status="all">Tất cả</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-status="upcoming">Sắp diễn ra</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-status="ongoing">Đang diễn ra</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-status="completed">Đã kết thúc</a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Registered Events List -->
    <div class="row" id="registeredEventsList">
        <?php if (empty($registered_events)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Bạn chưa đăng ký tham gia sự kiện nào.
                <a href="<?= base_url('students/events') ?>" class="alert-link">Khám phá các sự kiện</a>
            </div>
        </div>
        <?php else: ?>
            <?php foreach ($registered_events as $event): ?>
            <div class="col-12 mb-4 event-item" data-status="<?= $event['status'] ?>">
                <div class="card h-100 shadow-sm">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <img src="<?= base_url($event['hinh_anh'] ?? 'assets/img/event-default.jpg') ?>" 
                                     class="img-fluid rounded-start h-100" style="object-fit: cover;" 
                                     alt="<?= $event['ten_su_kien'] ?>">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                                        <div>
                                            <h5 class="card-title mb-1"><?= $event['ten_su_kien'] ?></h5>
                                            <div class="text-muted small mb-2">
                                                <i class="fas fa-tag me-1"></i> <?= $event['loai_su_kien'] ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ($event['status'] == 'upcoming'): ?>
                                            <span class="badge bg-primary">Sắp diễn ra</span>
                                        <?php elseif ($event['status'] == 'ongoing'): ?>
                                            <span class="badge bg-success">Đang diễn ra</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Đã kết thúc</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="card-text mb-3"><?= substr($event['mo_ta_ngan'], 0, 150) . (strlen($event['mo_ta_ngan']) > 150 ? '...' : '') ?></p>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                <span><?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-clock text-muted me-2"></i>
                                                <span><?= date('H:i', strtotime($event['ngay_to_chuc'])) ?> - <?= date('H:i', strtotime($event['ngay_ket_thuc'])) ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                <span><?= $event['dia_diem'] ?></span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-users text-muted me-2"></i>
                                                <span><?= $event['so_nguoi_tham_gia'] ?? 0 ?> người tham gia</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex flex-wrap gap-2">
                                        <a href="<?= base_url('students/events/detail/' . $event['id']) ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-info-circle me-1"></i> Chi tiết
                                        </a>
                                        
                                        <?php if ($event['status'] == 'upcoming'): ?>
                                            <button class="btn btn-danger event-cancel-btn" data-event-id="<?= $event['id'] ?>">
                                                <i class="fas fa-times-circle me-1"></i> Hủy đăng ký
                                            </button>
                                        <?php elseif ($event['status'] == 'ongoing'): ?>
                                            <?php if (!$event['has_checked_in']): ?>
                                                <button class="btn btn-success event-attendance-btn" data-event-id="<?= $event['id'] ?>" data-type="checkin">
                                                    <i class="fas fa-sign-in-alt me-1"></i> Check-in
                                                </button>
                                            <?php elseif (!$event['has_checked_out']): ?>
                                                <button class="btn btn-warning event-attendance-btn" data-event-id="<?= $event['id'] ?>" data-type="checkout">
                                                    <i class="fas fa-sign-out-alt me-1"></i> Check-out
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-success" disabled>
                                                    <i class="fas fa-check-circle me-1"></i> Đã tham gia
                                                </button>
                                            <?php endif; ?>
                                        <?php elseif ($event['status'] == 'completed' && $event['has_checked_in'] && $event['has_checked_out']): ?>
                                            <a href="<?= base_url('students/events/certificate/' . $event['id']) ?>" class="btn btn-primary">
                                                <i class="fas fa-certificate me-1"></i> Xem chứng chỉ
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($event['status'] == 'completed' && (!$event['has_checked_in'] || !$event['has_checked_out'])): ?>
                                            <span class="badge bg-danger p-2">Không tham gia</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status filter for registered events
    const statusLinks = document.querySelectorAll('[data-status]');
    statusLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            statusLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            const status = this.dataset.status;
            const eventItems = document.querySelectorAll('.event-item');
            
            eventItems.forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Check if any events are visible
            const visibleEvents = document.querySelectorAll('.event-item[style="display: block"]');
            const noResultsMessage = document.querySelector('.alert-info');
            
            if (visibleEvents.length === 0 && !noResultsMessage) {
                const message = document.createElement('div');
                message.className = 'col-12 alert alert-info';
                message.innerHTML = '<i class="fas fa-info-circle me-2"></i> Không có sự kiện nào trong trạng thái này.';
                document.getElementById('registeredEventsList').appendChild(message);
            } else if (visibleEvents.length > 0 && noResultsMessage) {
                noResultsMessage.remove();
            }
        });
    });
});
</script>
<?= $this->endSection() ?> 