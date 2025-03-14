<?= $this->extend('App\Modules\sukien\Views\layouts\sukien_layout') ?>

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
                            <li class="breadcrumb-item"><a href="<?= site_url('sukien') ?>">Trang chủ</a></li>
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
    <section class="container py-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <form action="<?= site_url('su-kien/list') ?>" method="get" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm sự kiện..." value="<?= isset($search) ? esc($search) : '' ?>">
                    <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                </form>
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="<?= site_url('su-kien/list') ?>" class="btn <?= !isset($category) ? 'btn-primary' : 'btn-outline-primary' ?> me-2 mb-2">Tất cả</a>
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Lọc theo loại
                </button>
                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                    <?php foreach ($event_types as $type): ?>
                    <li>
                        <a class="dropdown-item <?= (isset($category) && $category === $type['loai_su_kien']) ? 'active' : '' ?>" 
                           href="<?= site_url('su-kien/category/' . strtolower(str_replace(' ', '-', $type['loai_su_kien']))) ?>">
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
                    <a href="<?= site_url('su-kien/list') ?>" class="btn btn-outline-primary me-2 mb-2 <?= !isset($category) ? 'active' : '' ?>">Tất cả</a>
                    <?php foreach ($event_types as $type): ?>
                    <a href="<?= site_url('su-kien/category/' . strtolower(str_replace(' ', '-', $type['loai_su_kien']))) ?>" 
                       class="btn btn-outline-primary me-2 mb-2 <?= (isset($category) && $category === $type['loai_su_kien']) ? 'active' : '' ?>">
                        <?= $type['loai_su_kien'] ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Events List -->
    <section class="container py-5">
        <div class="row">
            <?php if (empty($events)): ?>
            <div class="col-md-12 text-center">
                <div class="alert alert-info">
                    <h4>Không tìm thấy sự kiện</h4>
                    <?php if (isset($search) && !empty($search)): ?>
                    <p>Không tìm thấy sự kiện nào phù hợp với từ khóa "<?= esc($search) ?>". Vui lòng thử lại với từ khóa khác.</p>
                    <?php elseif (isset($category)): ?>
                    <p>Hiện tại không có sự kiện nào trong danh mục <?= $category ?>. Vui lòng quay lại sau.</p>
                    <?php else: ?>
                    <p>Hiện tại không có sự kiện nào. Vui lòng quay lại sau.</p>
                    <?php endif; ?>
                    <a href="<?= site_url('su-kien/list') ?>" class="btn btn-primary mt-3">Xem tất cả sự kiện</a>
                </div>
            </div>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                <div class="col-md-4 mb-4" data-category="<?= strtolower(str_replace(' ', '-', $event['loai_su_kien'])) ?>">
                    <div class="event-card h-100">
                        <img src="<?= base_url($event['hinh_anh']) ?>" class="card-img-top" alt="<?= $event['ten_su_kien'] ?>">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="badge bg-<?php 
                                    switch($event['loai_su_kien']) {
                                        case 'Hội thảo': echo 'primary'; break;
                                        case 'Nghề nghiệp': echo 'success'; break;
                                        case 'Workshop': echo 'warning text-dark'; break;
                                        case 'Hoạt động sinh viên': echo 'info'; break;
                                        default: echo 'secondary';
                                    }
                                ?>"><?= $event['loai_su_kien'] ?></span>
                                <span class="text-muted"><i class="lni lni-calendar"></i> <?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?></span>
                            </div>
                            <h5 class="card-title"><?= $event['ten_su_kien'] ?></h5>
                            <p class="card-text"><?= $event['mo_ta_su_kien'] ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <a href="<?= site_url('su-kien/detail/' . $event['slug']) ?>" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                <span><i class="lni lni-map-marker"></i> <?= $event['dia_diem'] ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
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
    });
</script>
<?= $this->endSection() ?> 