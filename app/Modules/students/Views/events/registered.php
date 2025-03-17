<?= $this->extend('Modules/students/Views/layouts/layout') ?>

<?= $this->section('title') ?>Sự kiện đã đăng ký<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/layouts/studentsapp/css/events.css') ?>">
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
    .my-event-status {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .attend-buttons {
        display: flex;
        gap: 10px;
    }
    
    .checkin-status {
        background-color: rgba(56, 193, 114, 0.1);
        color: #38c172;
    }
    
    .checkout-status {
        background-color: rgba(246, 153, 63, 0.1);
        color: #f6993f;
    }
    
    .not-attended-status {
        background-color: rgba(227, 52, 47, 0.1);
        color: #e3342f;
    }
    
    .event-location {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 0.85rem;
        color: var(--text-light);
    }
    
    .qr-code-container {
        text-align: center;
        margin: 15px 0;
    }
    
    .qr-code {
        max-width: 150px;
        height: auto;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        padding: 10px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid events-page">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h1 class="h3 mb-2">Sự kiện đã đăng ký</h1>
            <p class="text-muted">Quản lý và theo dõi các sự kiện bạn đã đăng ký tham gia</p>
        </div>
    </div>
    
    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" class="form-control event-search-input" placeholder="Tìm kiếm sự kiện...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="statusFilter">
                        <option value="all">Tất cả trạng thái</option>
                        <option value="upcoming">Sắp diễn ra</option>
                        <option value="ongoing">Đang diễn ra</option>
                        <option value="completed">Đã kết thúc</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Events List -->
    <div class="my-events-list">
        <?php if (empty($registered_events)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Bạn chưa đăng ký tham gia sự kiện nào.
            </div>
        </div>
        <?php else: ?>
            <?php foreach ($registered_events as $event): ?>
            <div class="my-event-item" data-status="<?= $event['status'] ?>">
                <div class="my-event-header">
                    <img src="<?= base_url($event['hinh_anh'] ?? 'assets/img/event-default.jpg') ?>" class="my-event-img" alt="<?= $event['ten_su_kien'] ?>">
                    <div class="my-event-info">
                        <h5 class="my-event-title"><?= $event['ten_su_kien'] ?></h5>
                        <div class="my-event-date">
                            <i class="far fa-calendar-alt me-1"></i>
                            <?= date('d/m/Y H:i', strtotime($event['ngay_to_chuc'])) ?>
                        </div>
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= $event['dia_diem'] ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="my-event-status">
                    <?php if ($event['status'] == 'upcoming'): ?>
                        <div class="event-status upcoming">
                            <i class="fas fa-clock me-1"></i> Sắp diễn ra
                        </div>
                    <?php elseif ($event['status'] == 'ongoing'): ?>
                        <div class="event-status ongoing">
                            <i class="fas fa-play-circle me-1"></i> Đang diễn ra
                        </div>
                    <?php else: ?>
                        <div class="event-status completed">
                            <i class="fas fa-check-circle me-1"></i> Đã kết thúc
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="my-event-actions">
                    <?php if ($event['status'] == 'upcoming'): ?>
                    <a href="<?= base_url('students/events/detail/' . $event['id']) ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-info-circle"></i> Chi tiết
                    </a>
                    <button class="btn btn-sm btn-outline-danger cancel-registration-btn" data-event-id="<?= $event['id'] ?>">
                        <i class="fas fa-times-circle"></i> Hủy đăng ký
                    </button>
                    <?php elseif ($event['status'] == 'ongoing'): ?>
                    <a href="<?= base_url('students/events/detail/' . $event['id']) ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-info-circle"></i> Chi tiết
                    </a>
                    <div class="attend-buttons">
                        <?php if (!$event['checked_in']): ?>
                        <button class="btn btn-sm btn-success checkin-btn" data-event-id="<?= $event['id'] ?>" data-bs-toggle="modal" data-bs-target="#checkinModal<?= $event['id'] ?>">
                            <i class="fas fa-sign-in-alt"></i> Check-in
                        </button>
                        <?php elseif ($event['checked_in'] && !$event['checked_out']): ?>
                        <button class="btn btn-sm btn-outline-success" disabled>
                            <i class="fas fa-check-circle"></i> Đã check-in
                        </button>
                        <button class="btn btn-sm btn-warning checkout-btn" data-event-id="<?= $event['id'] ?>" data-bs-toggle="modal" data-bs-target="#checkoutModal<?= $event['id'] ?>">
                            <i class="fas fa-sign-out-alt"></i> Check-out
                        </button>
                        <?php else: ?>
                        <button class="btn btn-sm btn-outline-success" disabled>
                            <i class="fas fa-check-circle"></i> Đã tham gia
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                    <a href="<?= base_url('students/events/detail/' . $event['id']) ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-info-circle"></i> Chi tiết
                    </a>
                    
                    <?php if ($event['checked_in'] && $event['checked_out']): ?>
                    <span class="my-event-status checkin-status">
                        <i class="fas fa-check-circle me-1"></i> Đã tham gia
                    </span>
                    <?php elseif ($event['checked_in']): ?>
                    <span class="my-event-status checkout-status">
                        <i class="fas fa-exclamation-circle me-1"></i> Không hoàn thành
                    </span>
                    <?php else: ?>
                    <span class="my-event-status not-attended-status">
                        <i class="fas fa-times-circle me-1"></i> Không tham gia
                    </span>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Check-in Modal -->
            <div class="modal fade" id="checkinModal<?= $event['id'] ?>" tabindex="-1" aria-labelledby="checkinModalLabel<?= $event['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="checkinModalLabel<?= $event['id'] ?>">Check-in sự kiện</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>Vui lòng quét mã QR dưới đây hoặc nhập mã check-in để đánh dấu việc tham gia sự kiện.</p>
                            
                            <div class="qr-code-container">
                                <img src="<?= base_url('assets/images/qr-code-demo.png') ?>" alt="QR Code" class="qr-code">
                            </div>
                            
                            <p class="text-muted">Mã check-in: <strong><?= $event['id'] . '-' . rand(1000, 9999) ?></strong></p>
                            
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Nhập mã check-in" id="checkinCode<?= $event['id'] ?>">
                                <button class="btn btn-success" type="button" id="submitCheckin<?= $event['id'] ?>">
                                    <i class="fas fa-check"></i> Xác nhận
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Check-out Modal -->
            <div class="modal fade" id="checkoutModal<?= $event['id'] ?>" tabindex="-1" aria-labelledby="checkoutModalLabel<?= $event['id'] ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="checkoutModalLabel<?= $event['id'] ?>">Check-out sự kiện</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p>Vui lòng quét mã QR dưới đây hoặc nhập mã check-out để hoàn thành sự kiện.</p>
                            
                            <div class="qr-code-container">
                                <img src="<?= base_url('assets/images/qr-code-demo.png') ?>" alt="QR Code" class="qr-code">
                            </div>
                            
                            <p class="text-muted">Mã check-out: <strong><?= $event['id'] . '-' . rand(1000, 9999) ?></strong></p>
                            
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Nhập mã check-out" id="checkoutCode<?= $event['id'] ?>">
                                <button class="btn btn-warning" type="button" id="submitCheckout<?= $event['id'] ?>">
                                    <i class="fas fa-check"></i> Xác nhận
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('assets/layouts/studentsapp/js/events.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status filtering
    const statusFilter = document.getElementById('statusFilter');
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            const selectedStatus = this.value;
            const eventItems = document.querySelectorAll('.my-event-item');
            
            eventItems.forEach(item => {
                const itemStatus = item.getAttribute('data-status');
                
                if (selectedStatus === 'all' || selectedStatus === itemStatus) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Search functionality
    const searchInput = document.querySelector('.event-search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const eventItems = document.querySelectorAll('.my-event-item');
            
            eventItems.forEach(item => {
                const eventTitle = item.querySelector('.my-event-title').textContent.toLowerCase();
                const eventDate = item.querySelector('.my-event-date').textContent.toLowerCase();
                const eventLocation = item.querySelector('.event-location span').textContent.toLowerCase();
                
                if (eventTitle.includes(searchTerm) || eventDate.includes(searchTerm) || eventLocation.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Check-in functionality
    document.querySelectorAll('[id^="submitCheckin"]').forEach(button => {
        button.addEventListener('click', function() {
            const eventId = this.id.replace('submitCheckin', '');
            const checkinCode = document.getElementById('checkinCode' + eventId).value;
            
            if (!checkinCode) {
                alert('Vui lòng nhập mã check-in');
                return;
            }
            
            // Send check-in request
            fetch(`/students/events/checkin/${eventId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ code: checkinCode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('checkinModal' + eventId));
                    modal.hide();
                    
                    // Update UI
                    const checkinBtn = document.querySelector(`.checkin-btn[data-event-id="${eventId}"]`);
                    checkinBtn.parentNode.innerHTML = `
                        <button class="btn btn-sm btn-outline-success" disabled>
                            <i class="fas fa-check-circle"></i> Đã check-in
                        </button>
                        <button class="btn btn-sm btn-warning checkout-btn" data-event-id="${eventId}" data-bs-toggle="modal" data-bs-target="#checkoutModal${eventId}">
                            <i class="fas fa-sign-out-alt"></i> Check-out
                        </button>
                    `;
                    
                    // Show success message
                    showNotification('success', 'Check-in thành công', data.message || 'Bạn đã check-in thành công vào sự kiện.');
                } else {
                    // Show error message
                    showNotification('error', 'Check-in thất bại', data.message || 'Mã check-in không hợp lệ. Vui lòng thử lại.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Check-in thất bại', 'Đã xảy ra lỗi khi check-in. Vui lòng thử lại sau.');
            });
        });
    });
    
    // Check-out functionality
    document.querySelectorAll('[id^="submitCheckout"]').forEach(button => {
        button.addEventListener('click', function() {
            const eventId = this.id.replace('submitCheckout', '');
            const checkoutCode = document.getElementById('checkoutCode' + eventId).value;
            
            if (!checkoutCode) {
                alert('Vui lòng nhập mã check-out');
                return;
            }
            
            // Send check-out request
            fetch(`/students/events/checkout/${eventId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ code: checkoutCode })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('checkoutModal' + eventId));
                    modal.hide();
                    
                    // Update UI
                    const checkoutBtn = document.querySelector(`.checkout-btn[data-event-id="${eventId}"]`);
                    checkoutBtn.parentNode.innerHTML = `
                        <button class="btn btn-sm btn-outline-success" disabled>
                            <i class="fas fa-check-circle"></i> Đã tham gia
                        </button>
                    `;
                    
                    // Show success message
                    showNotification('success', 'Check-out thành công', data.message || 'Bạn đã check-out thành công khỏi sự kiện.');
                } else {
                    // Show error message
                    showNotification('error', 'Check-out thất bại', data.message || 'Mã check-out không hợp lệ. Vui lòng thử lại.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('error', 'Check-out thất bại', 'Đã xảy ra lỗi khi check-out. Vui lòng thử lại sau.');
            });
        });
    });
    
    // Show notification function
    function showNotification(type, title, message) {
        // Check if notification container exists
        let notificationContainer = document.querySelector('.notification-container');
        
        if (!notificationContainer) {
            // Create notification container
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-container';
            document.body.appendChild(notificationContainer);
        }
        
        // Create notification
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        
        // Set icon based on type
        let icon = 'info-circle';
        if (type === 'success') icon = 'check-circle';
        if (type === 'error') icon = 'exclamation-circle';
        
        // Set content
        notification.innerHTML = `
            <div class="notification-icon">
                <i class="fas fa-${icon}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${title}</div>
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Add to container
        notificationContainer.appendChild(notification);
        
        // Show notification
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Auto close after 5 seconds
        setTimeout(() => {
            closeNotification(notification);
        }, 5000);
        
        // Close button
        const closeBtn = notification.querySelector('.notification-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeNotification(notification);
            });
        }
    }
    
    function closeNotification(notification) {
        notification.classList.remove('show');
        
        // Remove after animation
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
});
</script>
<?= $this->endSection() ?> 