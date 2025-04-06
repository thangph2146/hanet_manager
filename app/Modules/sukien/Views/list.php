<?= $this->extend('frontend\layouts\sukien_layout') ?>

<?= $this->section('title') ?><?= isset($meta_title) ? $meta_title : 'Danh Sách Sự Kiện - Đại Học Ngân Hàng TP.HCM' ?><?= $this->endSection() ?>

<?= $this->section('description') ?><?= isset($meta_description) ? $meta_description : 'Danh sách tất cả sự kiện tại Trường Đại học Ngân hàng TP.HCM. Cập nhật các sự kiện, hội thảo và hoạt động mới nhất.' ?><?= $this->endSection() ?>

<?= $this->section('keywords') ?><?= isset($meta_keywords) ? $meta_keywords : 'sự kiện hub, danh sách sự kiện, hội thảo, workshop, ngày hội việc làm' ?><?= $this->endSection() ?>

<?= $this->section('additional_css') ?>
<?php if(isset($canonical_url)): ?>
<link rel="canonical" href="<?= $canonical_url ?>" />
<?php endif; ?>

<style>
    /* CSS để làm nổi bật thông báo lỗi */
    .alert-danger {
        border-left: 5px solid #dc3545;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .alert-heading {
        color: #dc3545;
        font-weight: 600;
    }
    
    /* Hiệu ứng làm nổi bật thông báo */
    @keyframes highlight {
        0% { background-color: #ffecec; }
        50% { background-color: #fff5f5; }
        100% { background-color: #ffecec; }
    }
    
    .alert-danger {
        animation: highlight 2s ease-in-out;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= site_url('su-kien') ?>">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Danh sách sự kiện</li>
                            <?php if (isset($category)): ?>
                            <li class="breadcrumb-item active" aria-current="page"><?= $category ?></li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                    <h1 class="fw-bold">
                        <?php if (isset($search) && !empty($search)): ?>
                            Kết quả tìm kiếm: <?= esc($search) ?>
                        <?php elseif (isset($category)): ?>
                            Sự Kiện <?= $category ?>
                        <?php else: ?>
                            Danh Sách Sự Kiện
                        <?php endif; ?>
                    </h1>
                    <?php if (isset($category)): ?>
                    <p class="text-muted">Sự kiện thuộc danh mục: <strong><?= $category ?></strong></p>
                    <?php elseif (isset($search) && !empty($search)): ?>
                    <p class="text-muted">Tìm thấy <?= count($events) ?> sự kiện phù hợp với từ khóa "<?= esc($search) ?>"</p>
                    <?php else: ?>
                    <p class="text-muted">Khám phá tất cả các sự kiện sắp diễn ra tại HUB <span class="badge bg-info ms-1">Chỉ hiển thị sự kiện trong tương lai</span></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
    <div class="container mt-4">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Không tìm thấy sự kiện</h5>
            <p class="mb-0"><?= session()->getFlashdata('error') ?></p>
            <?php if (session()->getFlashdata('search_term')): ?>
            <p class="mt-2">Bạn đang tìm: <strong>"<?= session()->getFlashdata('search_term') ?>"</strong></p>
            <?php endif; ?>
            <hr>
            <p class="mb-0">
                <a href="<?= site_url('su-kien') ?>" class="alert-link"><i class="fas fa-list me-1"></i>Xem tất cả sự kiện</a> hoặc
                <a href="#search-area" class="alert-link"><i class="fas fa-search me-1"></i>Tìm kiếm sự kiện khác</a>
            </p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Search and Filter Section -->
    <section class="container py-5" id="search-area">
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="<?= site_url('su-kien') ?>" method="get" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm sự kiện..." value="<?= isset($search) ? esc($search) : '' ?>">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </form>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="<?= site_url('su-kien') ?>" class="btn <?= !isset($category) ? 'btn-primary' : 'btn-outline-primary' ?> ms-2">Tất cả</a>
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Lọc theo loại
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <?php foreach ($event_types as $type): ?>
                    <li>
                        <a class="dropdown-item <?= (isset($category) && $category === $type['loai_su_kien']) ? 'active' : '' ?>" 
                           href="<?= site_url('su-kien/loai/' . $type['slug']) ?>">
                            <?= $type['loai_su_kien'] ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="filter-buttons text-center d-none d-md-block">
                    <a href="<?= site_url('su-kien') ?>" class="btn btn-outline-primary me-2 mb-2 <?= !isset($category) ? 'active' : '' ?>">Tất cả</a>
                    <?php foreach ($event_types as $type): ?>
                    <a href="<?= site_url('su-kien/loai/' . $type['slug']) ?>" 
                       class="btn btn-outline-primary me-2 mb-2 <?= (isset($category) && $category === $type['loai_su_kien']) ? 'active' : '' ?>">
                        <?= $type['loai_su_kien'] ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row event-container" id="event-container">
            <?php 
            // Sử dụng component event_list
            echo view('frontend\components\sukien\event_list', [
                'events' => $events,
                'layout' => 'grid', // Mặc định là grid
                'show_featured' => true,
                'category' => isset($category) ? $category : null,
                'search' => isset($search) ? $search : null
            ]);
            ?>
        </div>

        <!-- Pagination -->
        <?php if (!empty($events) && isset($pager) && $pager['total_pages'] > 1): ?>
        <div class="row mt-5">
            <div class="col-md-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php if ($pager['has_previous']): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= current_url() ?>?<?= http_build_query(array_merge($_GET, ['page' => $pager['previous_page']])) ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&laquo;</a>
                        </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $pager['total_pages']; $i++): ?>
                        <li class="page-item <?= $i == $pager['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="<?= current_url() ?>?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php if ($pager['has_next']): ?>
                        <li class="page-item">
                            <a class="page-link" href="<?= current_url() ?>?<?= http_build_query(array_merge($_GET, ['page' => $pager['next_page']])) ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">&raquo;</a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </section>

    <!-- Hiển thị sự kiện tương tự nếu có -->
    <?php if (session()->getFlashdata('similar_events')): ?>
    <div class="container mt-4">
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h4 class="mb-0">
                    <?php if (session()->getFlashdata('search_term')): ?>
                    Các sự kiện tương tự với "<?= session()->getFlashdata('search_term') ?>"
                    <?php else: ?>
                    Các sự kiện bạn có thể quan tâm
                    <?php endif; ?>
                </h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach (session()->getFlashdata('similar_events') as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 event-card">
                            <div class="event-image">
                                <?php 
                                $imageSrc = !empty($event['hinh_anh']) 
                                    ? (is_array($event['hinh_anh']) && isset($event['hinh_anh']['url']) 
                                        ? $event['hinh_anh']['url'] 
                                        : (is_string($event['hinh_anh']) ? $event['hinh_anh'] : ''))
                                    : 'assets/modules/sukien/images/event-default.jpg';
                                ?>
                                <img src="<?= base_url($imageSrc) ?>" class="card-img-top" alt="<?= $event['ten_su_kien'] ?>">
                                <div class="event-date">
                                    <span class="day"><?= date('d', strtotime($event['ngay_to_chuc'])) ?></span>
                                    <span class="month"><?= date('M', strtotime($event['ngay_to_chuc'])) ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="<?= site_url('su-kien/chi-tiet/' . $event['slug']) ?>" class="text-decoration-none">
                                        <?= $event['ten_su_kien'] ?>
                                    </a>
                                </h5>
                                <p class="card-text event-meta">
                                    <span class="event-time"><i class="far fa-clock me-1"></i><?= $event['thoi_gian'] ?></span>
                                    <span class="event-location"><i class="fas fa-map-marker-alt me-1"></i><?= $event['dia_diem'] ?></span>
                                </p>
                                <p class="card-text event-description"><?= mb_substr(strip_tags($event['mo_ta_su_kien']), 0, 80) ?>...</p>
                            </div>
                            <div class="card-footer bg-white">
                                <a href="<?= site_url('su-kien/chi-tiet/' . $event['slug']) ?>" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Danh sách tất cả sự kiện -->
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Xử lý flash messages
    document.addEventListener('DOMContentLoaded', function() {
        // Hiển thị thông báo nếu có
        const urlParams = new URLSearchParams(window.location.search);
        const successMsg = urlParams.get('success');
        const errorMsg = urlParams.get('error');
        
        if (successMsg) {
            showAlert('success', decodeURIComponent(successMsg));
        }
        
        if (errorMsg) {
            showAlert('danger', decodeURIComponent(errorMsg));
        }
        
        function showAlert(type, message) {
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            const container = document.querySelector('.container');
            container.insertBefore(alertDiv, container.firstChild);
            
            // Tự động ẩn sau 5 giây
            setTimeout(function() {
                alertDiv.classList.remove('show');
                setTimeout(function() {
                    alertDiv.remove();
                }, 150);
            }, 5000);
        }
        
        // Xử lý chuyển đổi chế độ xem
        const viewToggles = document.querySelectorAll('.view-toggle');
        const eventContainer = document.getElementById('event-container');
        
        viewToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                
                // Cập nhật trạng thái active
                viewToggles.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                
                // Lấy dữ liệu hiện tại
                const events = <?= json_encode($events) ?>;
                const category = <?= isset($category) ? json_encode($category) : 'null' ?>;
                const search = <?= isset($search) ? json_encode($search) : 'null' ?>;
                
                // Gọi AJAX để lấy HTML mới
                fetch('<?= site_url('su-kien/get-events-view') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        events: events,
                        layout: view,
                        category: category,
                        search: search
                    })
                })
                .then(response => response.text())
                .then(html => {
                    eventContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    
                    // Fallback nếu AJAX thất bại
                    if (view === 'grid') {
                        eventContainer.classList.remove('list-view');
                        eventContainer.classList.add('grid-view');
                    } else {
                        eventContainer.classList.remove('grid-view');
                        eventContainer.classList.add('list-view');
                    }
                });
            });
        });
    });
</script>
<?= $this->endSection() ?> 