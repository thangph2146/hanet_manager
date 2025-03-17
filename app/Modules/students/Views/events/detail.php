<?= $this->extend('students/layouts/layout') ?>

<?= $this->section('styles') ?>
<style>
    .event-header {
        position: relative;
        overflow: hidden;
        border-radius: 0.5rem;
    }
    .event-cover {
        height: 250px;
        object-fit: cover;
        width: 100%;
    }
    .event-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
        padding: 2rem 1rem 1rem;
        color: white;
    }
    .nav-tabs .nav-link.active {
        border-bottom: 2px solid var(--primary-color);
        color: var(--primary-color);
        font-weight: 500;
    }
    .nav-tabs .nav-link {
        border: none;
        color: var(--gray-700);
        padding: 0.75rem 1rem;
    }
    .event-schedule-item {
        position: relative;
        padding-left: 50px;
        margin-bottom: 1.5rem;
    }
    .event-schedule-item:before {
        content: '';
        position: absolute;
        left: 22px;
        top: 0;
        bottom: -1.5rem;
        width: 2px;
        background-color: var(--gray-300);
    }
    .event-schedule-item:last-child:before {
        bottom: 0;
    }
    .event-schedule-dot {
        position: absolute;
        left: 15px;
        top: 0;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background-color: var(--primary-color);
    }
    .event-participant-item {
        transition: all 0.2s;
        border-radius: 0.5rem;
    }
    .event-participant-item:hover {
        background-color: rgba(67, 97, 238, 0.05);
    }
    .sidebar-widget {
        margin-bottom: 1.5rem;
    }
    .progress {
        height: 0.5rem;
    }
    .related-event-item {
        transition: all 0.2s;
    }
    .related-event-item:hover {
        transform: translateY(-5px);
    }
    .related-event-img {
        height: 80px;
        object-fit: cover;
        border-radius: 0.25rem;
    }
    .back-button {
        position: absolute;
        top: 1rem;
        left: 1rem;
        z-index: 10;
        background-color: rgba(255, 255, 255, 0.9);
    }
    @media (max-width: 767.98px) {
        .event-cover {
            height: 200px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <?php if ($event): ?>
    
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('students/dashboard') ?>">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('students/events') ?>">Sự kiện</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $event['name'] ?></li>
        </ol>
    </nav>
    
    <!-- Event Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-4">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-<?= $event['status_color'] ?> me-2"><?= $event['status'] ?></span>
                                <span class="badge bg-secondary"><?= $event['category'] ?></span>
                            </div>
                            <h1 class="h3 mb-2"><?= $event['name'] ?></h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar-alt me-2"></i> <?= $event['date'] ?> | 
                                <i class="fas fa-clock me-2"></i> <?= $event['time'] ?> | 
                                <i class="fas fa-map-marker-alt me-2"></i> <?= $event['location'] ?>
                            </p>
                        </div>
                        <div class="mt-3 mt-md-0">
                            <?php if ($event['status'] === 'Đã kết thúc'): ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-calendar-times me-2"></i> Sự kiện đã kết thúc
                                </button>
                            <?php elseif ($is_registered): ?>
                                <button class="btn btn-success" disabled>
                                    <i class="fas fa-check-circle me-2"></i> Đã đăng ký
                                </button>
                                <button class="btn btn-outline-danger ms-2 event-cancel-btn" data-event-id="<?= $event['id'] ?>">
                                    <i class="fas fa-times-circle me-2"></i> Hủy đăng ký
                                </button>
                            <?php else: ?>
                                <button class="btn btn-primary event-register-btn" data-event-id="<?= $event['id'] ?>" data-event-name="<?= $event['name'] ?>">
                                    <i class="fas fa-calendar-check me-2"></i> Đăng ký tham gia
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($event['days_left'] > 0 && $event['status'] !== 'Đã kết thúc'): ?>
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <div>
                            Sự kiện sẽ diễn ra sau <?= $event['days_left'] ?> ngày nữa. Hãy đăng ký sớm để đảm bảo bạn có một chỗ!
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row mt-4">
                        <div class="col-md-8 mb-4 mb-md-0">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted small mb-1">Đơn vị tổ chức</div>
                                        <div class="fw-bold"><?= $event['organizer'] ?></div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted small mb-1">Số lượng</div>
                                        <div class="fw-bold"><?= $event['registered'] ?>/<?= $event['capacity'] ?></div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted small mb-1">Thời gian</div>
                                        <div class="fw-bold"><?= $event['time'] ?></div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="border rounded p-3 text-center">
                                        <div class="text-muted small mb-1">Liên hệ</div>
                                        <div class="fw-bold text-truncate" title="<?= $event['organizer_email'] ?>"><?= $event['organizer_phone'] ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="progress" style="height: 8px;">
                                <?php 
                                $percentage = min(100, round(($event['registered'] / $event['capacity']) * 100));
                                $progressColor = $percentage < 50 ? 'success' : ($percentage < 80 ? 'warning' : 'danger');
                                ?>
                                <div class="progress-bar bg-<?= $progressColor ?>" role="progressbar" style="width: <?= $percentage ?>%;" 
                                     aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <span class="small text-muted">Đã đăng ký: <?= $event['registered'] ?></span>
                                <span class="small text-muted">Còn trống: <?= $event['capacity'] - $event['registered'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Event Content -->
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Thông tin chi tiết</h5>
                </div>
                <div class="card-body">
                    <div class="event-description mb-4">
                        <?= $event['description'] ?>
                    </div>
                    
                    <?php if ($event['requirements']): ?>
                    <div class="mt-4">
                        <h5>Yêu cầu tham gia</h5>
                        <ul class="mb-0">
                            <?php foreach (explode("\n", $event['requirements']) as $req): ?>
                            <li><?= $req ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($event['benefits']): ?>
                    <div class="mt-4">
                        <h5>Lợi ích khi tham gia</h5>
                        <ul class="mb-0">
                            <?php foreach (explode("\n", $event['benefits']) as $benefit): ?>
                            <li><?= $benefit ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Lịch trình sự kiện</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thời gian</th>
                                    <th>Hoạt động</th>
                                    <th class="d-none d-md-table-cell">Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($event['schedule'] as $item): ?>
                                <tr>
                                    <td class="text-nowrap"><?= $item['time'] ?></td>
                                    <td><?= $item['title'] ?></td>
                                    <td class="d-none d-md-table-cell"><?= $item['description'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Side Content -->
        <div class="col-lg-4">
            <!-- Organizer Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Thông tin đơn vị tổ chức</h5>
                </div>
                <div class="card-body">
                    <h6><?= $event['organizer'] ?></h6>
                    <p class="mb-3">Chịu trách nhiệm tổ chức và điều phối sự kiện.</p>
                    
                    <div class="d-flex align-items-center mb-2">
                        <i class="fas fa-envelope text-muted me-2"></i>
                        <a href="mailto:<?= $event['organizer_email'] ?>"><?= $event['organizer_email'] ?></a>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-phone text-muted me-2"></i>
                        <a href="tel:<?= $event['organizer_phone'] ?>"><?= $event['organizer_phone'] ?></a>
                    </div>
                </div>
            </div>
            
            <!-- Related Events -->
            <div class="card shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Sự kiện liên quan</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($related_events)): ?>
                    <div class="p-4 text-center">
                        <p class="text-muted mb-0">Không có sự kiện liên quan</p>
                    </div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($related_events as $related): ?>
                        <a href="<?= base_url('students/events/detail/' . $related['id']) ?>" class="list-group-item list-group-item-action p-3">
                            <div class="d-flex">
                                <?php if (!empty($related['image'])): ?>
                                <div class="flex-shrink-0 me-3">
                                    <img src="<?= base_url($related['image']) ?>" alt="<?= $related['name'] ?>" width="60" height="60" class="rounded" style="object-fit: cover;">
                                </div>
                                <?php endif; ?>
                                <div>
                                    <h6 class="mb-1"><?= $related['name'] ?></h6>
                                    <p class="mb-1 small text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i> <?= $related['date'] ?>
                                    </p>
                                    <p class="mb-0 small text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i> <?= $related['location'] ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Đăng ký sự kiện -->
    <div class="modal fade" id="eventRegisterModal" tabindex="-1" aria-labelledby="eventRegisterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventRegisterModalLabel">Đăng ký tham gia sự kiện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn đang đăng ký tham gia sự kiện: <strong id="eventNameToRegister"></strong></p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i> Vui lòng xác nhận để hoàn tất đăng ký.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-primary" id="confirmRegisterBtn">Xác nhận đăng ký</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal Hủy đăng ký -->
    <div class="modal fade" id="eventCancelModal" tabindex="-1" aria-labelledby="eventCancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventCancelModalLabel">Hủy đăng ký tham gia sự kiện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i> Bạn có chắc chắn muốn hủy đăng ký tham gia sự kiện này?
                    </div>
                    <p class="mb-0">Sau khi hủy đăng ký, bạn vẫn có thể đăng ký lại nếu sự kiện còn chỗ.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không, giữ đăng ký</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelBtn">Xác nhận hủy</button>
                </div>
            </div>
        </div>
    </div>
    
    <?php else: ?>
    <!-- Event not found -->
    <div class="card shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="fas fa-calendar-times fa-4x text-muted"></i>
            </div>
            <h3>Không tìm thấy sự kiện</h3>
            <p class="text-muted mb-4">Sự kiện bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
            <a href="<?= base_url('students/events') ?>" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i> Quay lại danh sách sự kiện
            </a>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý đăng ký sự kiện
    const registerBtns = document.querySelectorAll('.event-register-btn');
    const registerModal = new bootstrap.Modal(document.getElementById('eventRegisterModal'));
    const eventNameToRegister = document.getElementById('eventNameToRegister');
    const confirmRegisterBtn = document.getElementById('confirmRegisterBtn');
    
    let currentEventId = null;
    
    if (registerBtns.length > 0) {
        registerBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                currentEventId = this.dataset.eventId;
                eventNameToRegister.textContent = this.dataset.eventName;
                registerModal.show();
            });
        });
    }
    
    if (confirmRegisterBtn) {
        confirmRegisterBtn.addEventListener('click', function() {
            // Hiển thị loading
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
            this.disabled = true;
            
            // Gửi yêu cầu đăng ký
            fetch('/students/events/register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    event_id: currentEventId
                })
            })
            .then(response => response.json())
            .then(data => {
                registerModal.hide();
                
                // Hiển thị thông báo
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `toast ${data.success ? 'bg-success' : 'bg-danger'} text-white`;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                
                toast.innerHTML = `
                    <div class="toast-header ${data.success ? 'bg-success' : 'bg-danger'} text-white">
                        <strong class="me-auto">${data.success ? 'Thành công' : 'Lỗi'}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${data.message}
                    </div>
                `;
                
                toastContainer.appendChild(toast);
                new bootstrap.Toast(toast).show();
                
                // Cập nhật UI nếu thành công
                if (data.success) {
                    // Reload trang sau 2 giây
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    // Reset trạng thái nút
                    confirmRegisterBtn.innerHTML = 'Xác nhận đăng ký';
                    confirmRegisterBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Hiển thị thông báo lỗi
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = 'toast bg-danger text-white';
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                
                toast.innerHTML = `
                    <div class="toast-header bg-danger text-white">
                        <strong class="me-auto">Lỗi</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Đã xảy ra lỗi khi xử lý yêu cầu.
                    </div>
                `;
                
                toastContainer.appendChild(toast);
                new bootstrap.Toast(toast).show();
                
                // Ẩn modal và reset trạng thái nút
                registerModal.hide();
                confirmRegisterBtn.innerHTML = 'Xác nhận đăng ký';
                confirmRegisterBtn.disabled = false;
            });
        });
    }
    
    // Xử lý hủy đăng ký sự kiện
    const cancelBtns = document.querySelectorAll('.event-cancel-btn');
    const cancelModal = new bootstrap.Modal(document.getElementById('eventCancelModal'));
    const confirmCancelBtn = document.getElementById('confirmCancelBtn');
    
    if (cancelBtns.length > 0) {
        cancelBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                currentEventId = this.dataset.eventId;
                cancelModal.show();
            });
        });
    }
    
    if (confirmCancelBtn) {
        confirmCancelBtn.addEventListener('click', function() {
            // Hiển thị loading
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
            this.disabled = true;
            
            // Gửi yêu cầu hủy đăng ký
            fetch('/students/events/cancel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    event_id: currentEventId
                })
            })
            .then(response => response.json())
            .then(data => {
                cancelModal.hide();
                
                // Hiển thị thông báo
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = `toast ${data.success ? 'bg-success' : 'bg-danger'} text-white`;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                
                toast.innerHTML = `
                    <div class="toast-header ${data.success ? 'bg-success' : 'bg-danger'} text-white">
                        <strong class="me-auto">${data.success ? 'Thành công' : 'Lỗi'}</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${data.message}
                    </div>
                `;
                
                toastContainer.appendChild(toast);
                new bootstrap.Toast(toast).show();
                
                // Cập nhật UI nếu thành công
                if (data.success) {
                    // Reload trang sau 2 giây
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    // Reset trạng thái nút
                    confirmCancelBtn.innerHTML = 'Xác nhận hủy';
                    confirmCancelBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Hiển thị thông báo lỗi
                const toastContainer = document.getElementById('toast-container');
                const toast = document.createElement('div');
                toast.className = 'toast bg-danger text-white';
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                
                toast.innerHTML = `
                    <div class="toast-header bg-danger text-white">
                        <strong class="me-auto">Lỗi</strong>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        Đã xảy ra lỗi khi xử lý yêu cầu.
                    </div>
                `;
                
                toastContainer.appendChild(toast);
                new bootstrap.Toast(toast).show();
                
                // Ẩn modal và reset trạng thái nút
                cancelModal.hide();
                confirmCancelBtn.innerHTML = 'Xác nhận hủy';
                confirmCancelBtn.disabled = false;
            });
        });
    }
});
</script>
<?= $this->endSection() ?> 