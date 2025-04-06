<?= $this->extend('frontend\layouts\sukien_layout') ?>

<?= $this->section('title') ?><?= isset($meta_title) ? $meta_title : 'Danh Sách Sự Kiện - Đại Học Ngân Hàng TP.HCM' ?><?= $this->endSection() ?>

<?= $this->section('description') ?><?= isset($meta_description) ? $meta_description : 'Danh sách tất cả sự kiện tại Trường Đại học Ngân hàng TP.HCM. Cập nhật các sự kiện, hội thảo và hoạt động mới nhất.' ?><?= $this->endSection() ?>

<?= $this->section('keywords') ?><?= isset($meta_keywords) ? $meta_keywords : 'sự kiện hub, danh sách sự kiện, hội thảo, workshop, ngày hội việc làm' ?><?= $this->endSection() ?>

<?php if(isset($canonical_url)): ?>
<?= $this->section('additional_css') ?>
<link rel="canonical" href="<?= $canonical_url ?>" />
<?= $this->endSection() ?>
<?php endif; ?>

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
                    <p class="text-muted">Khám phá tất cả các sự kiện sắp diễn ra tại HUB</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Search and Filter Section -->
    <section class="container py-5">
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