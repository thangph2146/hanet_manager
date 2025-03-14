<?php
/**
 * Component Event Card Horizontal
 * Hiển thị thông tin sự kiện trong một thẻ theo chiều ngang
 * 
 * @param array $event Mảng chứa thông tin sự kiện
 * @param bool $featured Có phải là sự kiện nổi bật không
 */

// Đảm bảo biến $event tồn tại
if (!isset($event) || empty($event)) {
    return;
}

// Đặt giá trị mặc định cho biến $featured
$featured = isset($featured) ? $featured : false;
?>

<div class="event-card-horizontal <?= $featured ? 'featured' : '' ?>">
    <div class="row g-0">
        <div class="col-md-4 position-relative">
            <div class="event-category">
                <a href="<?= site_url('su-kien/category/' . strtolower(str_replace(' ', '-', $event['loai_su_kien']))) ?>" class="category-tag <?= strtolower(str_replace(' ', '-', $event['loai_su_kien'])) ?>">
                    <i class="fas fa-tag"></i> <?= $event['loai_su_kien'] ?>
                </a>
            </div>
            <img src="<?= base_url($event['hinh_anh']) ?>" alt="<?= $event['ten_su_kien'] ?>" class="img-fluid h-100 w-100 object-fit-cover">
        </div>
        <div class="col-md-8">
            <div class="card-body h-100 d-flex flex-column">
                <h5 class="card-title"><?= $event['ten_su_kien'] ?></h5>
                
                <div class="event-meta">
                    <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?></span>
                    <span><i class="fas fa-map-marker-alt"></i> <?= $event['dia_diem'] ?></span>
                    <span><i class="fas fa-users"></i> <?= isset($event['so_luong_tham_gia']) ? $event['so_luong_tham_gia'] : rand(50, 200) ?> người tham gia</span>
                </div>
                
                <p class="card-text flex-grow-1">
                    <?php 
                    // Hiển thị mô tả ngắn gọn
                    $description = isset($event['mo_ta_su_kien']) ? $event['mo_ta_su_kien'] : '';
                    echo substr($description, 0, 200) . (strlen($description) > 200 ? '...' : '');
                    ?>
                </p>
                
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <a href="<?= site_url('su-kien/detail/' . $event['slug']) ?>" class="btn-link">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                    
                    <?php if (strtotime($event['ngay_to_chuc']) > time()): ?>
                    <a href="<?= site_url('su-kien/register?event=' . $event['id_su_kien']) ?>" class="btn btn-sm btn-outline-primary">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 