<?= $this->extend('App\Modules\layouts\student\Views\dashboard\layout') ?>

<?= $this->section('styles') ?>
<style>
    .notification-item {
        border-radius: 8px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    
    .notification-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .notification-item.unread {
        border-left: 4px solid var(--primary-color);
    }
    
    .notification-item.read {
        opacity: 0.7;
    }
    
    .notification-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }
    
    .notification-icon i {
        font-size: 1.5rem;
    }
    
    .notification-content {
        flex: 1;
    }
    
    .notification-title {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .notification-time {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    .notification-filter {
        margin-bottom: 20px;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Thông báo</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= base_url('students/dashboard') ?>"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Tất cả thông báo</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Tất cả thông báo</h5>
            <div class="notification-actions">
                <button type="button" id="markAllAsRead" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-check-double"></i> Đánh dấu tất cả đã đọc
                </button>
            </div>
        </div>
        
        <div class="notification-filter">
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-sm btn-outline-primary active" data-filter="all">Tất cả</button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="unread">Chưa đọc</button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-filter="read">Đã đọc</button>
            </div>
        </div>
        
        <div class="notification-list" id="notificationsList">
            <?php if (isset($notifications) && !empty($notifications)): ?>
                <?php foreach ($notifications as $notification): ?>
                    <div class="card notification-item <?= !$notification['read'] ? 'unread' : 'read' ?>" data-id="<?= $notification['id'] ?>">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="notification-icon bg-light-<?= $notification['type'] ?> text-<?= $notification['type'] ?>">
                                    <i class="<?= $notification['icon'] ?>"></i>
                                </div>
                                <div class="notification-content ms-3">
                                    <h6 class="notification-title"><?= $notification['title'] ?></h6>
                                    <p class="mb-1"><?= $notification['content'] ?></p>
                                    <small class="notification-time"><?= $notification['time'] ?></small>
                                </div>
                                <?php if (!$notification['read']): ?>
                                <div class="ms-auto">
                                    <button type="button" class="btn btn-sm btn-light mark-as-read" data-id="<?= $notification['id'] ?>">
                                        <i class="bx bx-check"></i>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="bx bx-bell-off fs-1 text-secondary"></i>
                    <p class="mt-2">Không có thông báo</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lọc thông báo
    const filterButtons = document.querySelectorAll('.notification-filter button');
    const notificationItems = document.querySelectorAll('.notification-item');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Active state
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.getAttribute('data-filter');
            
            notificationItems.forEach(item => {
                if (filter === 'all') {
                    item.style.display = 'block';
                } else if (filter === 'unread' && item.classList.contains('unread')) {
                    item.style.display = 'block';
                } else if (filter === 'read' && item.classList.contains('read')) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // Đánh dấu đã đọc cho một thông báo
    const markAsReadButtons = document.querySelectorAll('.mark-as-read');
    markAsReadButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            markAsRead(id, this);
        });
    });
    
    // Đánh dấu tất cả đã đọc
    const markAllAsReadButton = document.getElementById('markAllAsRead');
    if (markAllAsReadButton) {
        markAllAsReadButton.addEventListener('click', markAllAsRead);
    }
    
    // Xử lý đánh dấu đã đọc cho một thông báo
    function markAsRead(id, buttonElement) {
        // Hiệu ứng loading
        buttonElement.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
        buttonElement.disabled = true;
        
        fetch(`${window.location.origin}/students/notifications/mark-as-read/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật UI
                const notificationItem = buttonElement.closest('.notification-item');
                notificationItem.classList.remove('unread');
                notificationItem.classList.add('read');
                buttonElement.remove();
            }
        })
        .catch(error => {
            console.error('Error marking notification as read:', error);
            buttonElement.innerHTML = '<i class="bx bx-check"></i>';
            buttonElement.disabled = false;
        });
    }
    
    // Xử lý đánh dấu tất cả đã đọc
    function markAllAsRead() {
        // Hiệu ứng loading
        markAllAsReadButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang xử lý...';
        markAllAsReadButton.disabled = true;
        
        fetch(`${window.location.origin}/students/notifications/mark-all-as-read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.getAttribute('content') || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật UI
                const unreadItems = document.querySelectorAll('.notification-item.unread');
                unreadItems.forEach(item => {
                    item.classList.remove('unread');
                    item.classList.add('read');
                    const markButton = item.querySelector('.mark-as-read');
                    if (markButton) markButton.remove();
                });
                
                markAllAsReadButton.innerHTML = '<i class="bx bx-check-double"></i> Đánh dấu tất cả đã đọc';
                markAllAsReadButton.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error marking all notifications as read:', error);
            markAllAsReadButton.innerHTML = '<i class="bx bx-check-double"></i> Đánh dấu tất cả đã đọc';
            markAllAsReadButton.disabled = false;
        });
    }
});
</script>
<?= $this->endSection() ?> 