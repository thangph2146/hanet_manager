<?= $this->extend('Modules/students/Views/layouts/layout') ?>

<?= $this->section('styles') ?>
<style>
    .event-card {
        transition: transform 0.3s;
        height: 100%;
    }
    .event-card:hover {
        transform: translateY(-5px);
    }
    .event-img {
        height: 180px;
        object-fit: cover;
    }
    .filter-group {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .event-description {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 4.5rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- Page Title and Filter Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div class="mb-3 mb-md-0">
                    <h1 class="h3 mb-2">Danh sách sự kiện</h1>
                    <p class="text-muted">Khám phá các sự kiện đang diễn ra và sắp tới</p>
                </div>
                
                <div class="d-flex flex-column flex-sm-row gap-2">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm sự kiện..." id="searchEvent">
                        <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-filter me-1"></i> Lọc
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item" href="#" data-filter="all">Tất cả</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="upcoming">Sắp diễn ra</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="ongoing">Đang diễn ra</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="completed">Đã kết thúc</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-filter="registered">Đã đăng ký</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Event Category Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills nav-fill flex-column flex-sm-row">
                <li class="nav-item">
                    <a class="nav-link active" href="#" data-category="all">Tất cả</a>
                </li>
                <?php foreach ($event_types as $type): ?>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-category="<?= $type['id'] ?>"><?= $type['loai_su_kien'] ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
    <!-- Events List -->
    <div class="row" id="eventsList">
        <?php if (empty($events)): ?>
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i> Không có sự kiện nào được tìm thấy.
            </div>
        </div>
        <?php else: ?>
            <?php foreach ($events as $event): ?>
            <div class="col-12 col-md-6 col-lg-4 mb-4 event-item" 
                 data-category="<?= $event['loai_su_kien_id'] ?>" 
                 data-status="<?= $event['status'] ?>" 
                 data-registered="<?= $event['is_registered'] ? 'yes' : 'no' ?>">
                <div class="card h-100 shadow-sm event-card">
                    <?php if ($event['status'] == 'upcoming'): ?>
                        <div class="event-badge">
                            <span class="badge bg-primary">Sắp diễn ra</span>
                        </div>
                    <?php elseif ($event['status'] == 'ongoing'): ?>
                        <div class="event-badge">
                            <span class="badge bg-success">Đang diễn ra</span>
                        </div>
                    <?php else: ?>
                        <div class="event-badge">
                            <span class="badge bg-secondary">Đã kết thúc</span>
                        </div>
                    <?php endif; ?>
                    
                    <img src="<?= base_url($event['hinh_anh'] ?? 'assets/img/event-default.jpg') ?>" class="card-img-top" alt="<?= $event['ten_su_kien'] ?>">
                    
                    <div class="card-body">
                        <div class="event-date mb-2">
                            <i class="far fa-calendar-alt me-1"></i>
                            <?= date('d/m/Y H:i', strtotime($event['ngay_to_chuc'])) ?>
                        </div>
                        
                        <h5 class="card-title"><?= $event['ten_su_kien'] ?></h5>
                        
                        <p class="card-text text-truncate">
                            <?= $event['mo_ta_ngan'] ?>
                        </p>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <i class="fas fa-map-marker-alt text-muted"></i>
                                <small class="text-muted"><?= $event['dia_diem'] ?></small>
                            </div>
                            <div>
                                <i class="fas fa-users text-muted"></i>
                                <small class="text-muted"><?= $event['so_nguoi_tham_gia'] ?? 0 ?> người tham gia</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?= base_url('students/events/detail/' . $event['id']) ?>" class="btn btn-outline-primary">
                                <i class="fas fa-info-circle"></i> Chi tiết
                            </a>
                            
                            <?php if ($event['is_registered']): ?>
                                <button class="btn btn-success" disabled>
                                    <i class="fas fa-check-circle"></i> Đã đăng ký
                                </button>
                            <?php elseif ($event['status'] != 'completed'): ?>
                                <button class="btn btn-primary event-register-btn" data-event-id="<?= $event['id'] ?>">
                                    <i class="fas fa-calendar-check"></i> Đăng ký
                                </button>
                            <?php else: ?>
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-calendar-times"></i> Đã kết thúc
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?php if (!empty($pager) && $pager->getPageCount() > 1): ?>
    <div class="row">
        <div class="col-12">
            <nav aria-label="Page navigation">
                <?= $pager->links() ?>
            </nav>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category filter
    const categoryLinks = document.querySelectorAll('[data-category]');
    categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            categoryLinks.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            filterEvents();
        });
    });
    
    // Status filter
    const filterLinks = document.querySelectorAll('[data-filter]');
    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const filter = this.dataset.filter;
            document.getElementById('filterDropdown').textContent = this.textContent;
            filterEvents();
        });
    });
    
    // Search
    document.getElementById('searchBtn').addEventListener('click', function() {
        filterEvents();
    });
    
    document.getElementById('searchEvent').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            filterEvents();
        }
    });
    
    // Filter events based on criteria
    function filterEvents() {
        const searchTerm = document.getElementById('searchEvent').value.toLowerCase();
        const activeCategory = document.querySelector('[data-category].active').dataset.category;
        const activeFilter = document.getElementById('filterDropdown').textContent.trim();
        
        const eventItems = document.querySelectorAll('.event-item');
        
        eventItems.forEach(item => {
            let showByCategory = activeCategory === 'all' || item.dataset.category === activeCategory;
            let showByStatus = true;
            
            // Filter by status
            if (activeFilter.includes('Sắp diễn ra')) {
                showByStatus = item.dataset.status === 'upcoming';
            } else if (activeFilter.includes('Đang diễn ra')) {
                showByStatus = item.dataset.status === 'ongoing';
            } else if (activeFilter.includes('Đã kết thúc')) {
                showByStatus = item.dataset.status === 'completed';
            } else if (activeFilter.includes('Đã đăng ký')) {
                showByStatus = item.dataset.registered === 'yes';
            }
            
            // Filter by search term
            let showBySearch = true;
            if (searchTerm) {
                const eventTitle = item.querySelector('.card-title').textContent.toLowerCase();
                const eventDesc = item.querySelector('.card-text').textContent.toLowerCase();
                showBySearch = eventTitle.includes(searchTerm) || eventDesc.includes(searchTerm);
            }
            
            // Show or hide based on all criteria
            if (showByCategory && showByStatus && showBySearch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
        
        // Check if any events are visible
        const visibleEvents = document.querySelectorAll('.event-item[style="display: block"]');
        const noResultsMessage = document.querySelector('.alert-info');
        
        if (visibleEvents.length === 0) {
            if (!noResultsMessage) {
                const message = document.createElement('div');
                message.className = 'col-12 alert alert-info';
                message.innerHTML = '<i class="fas fa-info-circle me-2"></i> Không có sự kiện nào phù hợp với tiêu chí tìm kiếm.';
                document.getElementById('eventsList').appendChild(message);
            }
        } else {
            if (noResultsMessage) {
                noResultsMessage.remove();
            }
        }
    }
});
</script>
<?= $this->endSection() ?> 