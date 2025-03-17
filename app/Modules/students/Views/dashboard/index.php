<?= $this->extend('students/layouts/layout') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Welcome Message & Stats Row -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-3">
                        <div class="avatar-container me-sm-3 mb-3 mb-sm-0 text-center">
                            <?php if (isset($student_data) && !empty($student_data['picture'])): ?>
                                <img src="<?= base_url($student_data['picture']) ?>" alt="Profile" class="rounded-circle" width="80" height="80">
                            <?php else: ?>
                                <div class="avatar-placeholder rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px;">
                                    <?= substr(isset($student_data['fullname']) ? $student_data['fullname'] : 'U', 0, 1) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h4 class="mb-1">Xin chào, <?= $student_data['fullname'] ?></h4>
                            <p class="mb-0 text-muted">MSSV: <?= $student_data['student_id'] ?></p>
                        </div>
                    </div>
                    <p class="mb-0">Chào mừng bạn trở lại với Hệ thống Quản lý Sinh viên. Dưới đây là thông tin tổng quan về các sự kiện và hoạt động của bạn.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="row h-100">
                <div class="col-6 mb-4 mb-lg-0">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-3">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 mb-2">
                                <i class="fas fa-calendar-event fs-4 text-primary"></i>
                            </div>
                            <h2 class="mb-0"><?= $active_events ?></h2>
                            <p class="text-muted small mb-0">Sự kiện đang diễn ra</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 mb-4 mb-lg-0">
                    <div class="card stat-card h-100">
                        <div class="card-body d-flex flex-column align-items-center justify-content-center text-center p-3">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 mb-2">
                                <i class="fas fa-check-circle fs-4 text-success"></i>
                            </div>
                            <h2 class="mb-0"><?= $registered_events ?></h2>
                            <p class="text-muted small mb-0">Sự kiện đã đăng ký</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Upcoming Events Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Sự kiện sắp diễn ra</h5>
                    <a href="<?= base_url('students/events') ?>" class="btn btn-sm btn-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tên sự kiện</th>
                                    <th>Thời gian</th>
                                    <th class="d-none d-md-table-cell">Địa điểm</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($events) && count($events) > 0): ?>
                                    <?php foreach ($events as $event): ?>
                                    <tr>
                                        <td>
                                            <a href="<?= base_url('students/events/detail/' . $event['id']) ?>" class="text-decoration-none">
                                                <?= $event['name'] ?>
                                            </a>
                                        </td>
                                        <td><?= $event['time'] ?></td>
                                        <td class="d-none d-md-table-cell"><?= $event['location'] ?></td>
                                        <td>
                                            <span class="badge bg-<?= $event['status_color'] ?>"><?= $event['status'] ?></span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= base_url('students/events/detail/' . $event['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-info-circle d-inline d-md-none"></i>
                                                    <span class="d-none d-md-inline">Chi tiết</span>
                                                </a>
                                                <?php if ($event['status'] === 'Đang mở đăng ký'): ?>
                                                <button class="btn btn-sm btn-primary ms-1 event-register-btn" data-event-id="<?= $event['id'] ?>" data-event-name="<?= $event['name'] ?>">
                                                    <i class="fas fa-check d-inline d-md-none"></i>
                                                    <span class="d-none d-md-inline">Đăng ký</span>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-3">Không có sự kiện sắp diễn ra</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Event Stats & Notifications -->
    <div class="row">
        <!-- Event Stats -->
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Thống kê sự kiện</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 p-3 rounded-circle mb-2">
                                    <i class="fas fa-calendar fs-4 text-primary"></i>
                                </div>
                                <h3 class="mb-1"><?= $upcoming_events ?></h3>
                                <p class="text-muted small mb-0">Sự kiện sắp tới</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 p-3 rounded-circle mb-2">
                                    <i class="fas fa-check-double fs-4 text-success"></i>
                                </div>
                                <h3 class="mb-1"><?= $registered_events ?></h3>
                                <p class="text-muted small mb-0">Đã đăng ký</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-info bg-opacity-10 p-3 rounded-circle mb-2">
                                    <i class="fas fa-clock fs-4 text-info"></i>
                                </div>
                                <h3 class="mb-1"><?= $active_events ?></h3>
                                <p class="text-muted small mb-0">Đang diễn ra</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3 text-center">
                                <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 p-3 rounded-circle mb-2">
                                    <i class="fas fa-certificate fs-4 text-warning"></i>
                                </div>
                                <h3 class="mb-1"><?= $certificates ?></h3>
                                <p class="text-muted small mb-0">Chứng chỉ</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent text-center">
                    <a href="<?= base_url('students/events/registered') ?>" class="btn btn-primary">Quản lý sự kiện của tôi</a>
                </div>
            </div>
        </div>
        
        <!-- Notifications -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thông báo gần đây</h5>
                    <a href="<?= base_url('students/notifications') ?>" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php if (isset($recent_notifications) && count($recent_notifications) > 0): ?>
                            <?php foreach ($recent_notifications as $notification): ?>
                            <div class="list-group-item border-0 border-bottom p-3">
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <div class="notification-icon bg-light-<?= $notification['type'] ?>">
                                            <i class="fas <?= str_replace('bx ', 'fa-', $notification['icon']) ?>"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1"><?= $notification['title'] ?></h6>
                                        <p class="text-muted mb-1 small"><?= $notification['content'] ?></p>
                                        <p class="text-muted mb-0 x-small"><?= $notification['time'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center p-4">
                                <div class="mb-3">
                                    <i class="fas fa-bell-slash fs-1 text-muted"></i>
                                </div>
                                <p class="text-muted">Không có thông báo mới</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý đăng ký sự kiện
    const registerBtns = document.querySelectorAll('.event-register-btn');
    const registerModal = new bootstrap.Modal(document.getElementById('eventRegisterModal'));
    const eventNameToRegister = document.getElementById('eventNameToRegister');
    const confirmRegisterBtn = document.getElementById('confirmRegisterBtn');
    
    let currentEventId = null;
    
    registerBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            currentEventId = this.dataset.eventId;
            eventNameToRegister.textContent = this.dataset.eventName;
            registerModal.show();
        });
    });
    
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
                const btn = document.querySelector(`.event-register-btn[data-event-id="${currentEventId}"]`);
                if (btn) {
                    btn.innerHTML = '<i class="fas fa-check-circle d-inline d-md-none"></i><span class="d-none d-md-inline">Đã đăng ký</span>';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-success');
                    btn.disabled = true;
                }
            }
            
            // Reset trạng thái nút
            confirmRegisterBtn.innerHTML = 'Xác nhận đăng ký';
            confirmRegisterBtn.disabled = false;
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
});
</script>
<?= $this->endSection() ?> 