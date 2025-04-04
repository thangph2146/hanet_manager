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

// Lưu ý: Hàm getEventTypeName() đã được định nghĩa trong event_list.php
?>

<div class="event-card-horizontal <?= $featured ? 'featured' : '' ?>">
    <div class="row g-0">
        <div class="col-md-4 position-relative">
            <?php 
            // Xử lý trường hợp loại sự kiện là đối tượng hoặc chuỗi
            $loai_su_kien = '';
            if (is_object($event['loai_su_kien'])) {
                // Nếu là đối tượng LoaiSuKien
                if (method_exists($event['loai_su_kien'], 'getTenLoaiSuKien')) {
                    $loai_su_kien = $event['loai_su_kien']->getTenLoaiSuKien();
                } else if (isset($event['loai_su_kien']->ten_loai_su_kien)) {
                    $loai_su_kien = $event['loai_su_kien']->ten_loai_su_kien;
                } else if (isset($event['loai_su_kien']->attributes) && isset($event['loai_su_kien']->attributes['ten_loai_su_kien'])) {
                    $loai_su_kien = $event['loai_su_kien']->attributes['ten_loai_su_kien'];
                } else {
                    // Trường hợp không xác định được thuộc tính
                    $loai_su_kien = 'Sự kiện';
                }
            } else if (is_array($event['loai_su_kien']) && isset($event['loai_su_kien']['ten_loai_su_kien'])) {
                $loai_su_kien = $event['loai_su_kien']['ten_loai_su_kien'];
            } else {
                // Nếu là chuỗi hoặc khác
                $loai_su_kien = $event['loai_su_kien'];
            }
            $categorySlug = isset($event['loai_su_kien_slug']) ? $event['loai_su_kien_slug'] : strtolower(str_replace(' ', '-', $loai_su_kien));
            ?>
            <div class="event-category">
                <a href="<?= site_url('su-kien/loai/' . $categorySlug) ?>" class="category-tag <?= $categorySlug ?>">
                    <i class="fas fa-tag"></i> <?= $loai_su_kien ?>
                </a>
            </div>
            <img src="<?= base_url($event['hinh_anh']) ?>" alt="<?= $event['ten_su_kien'] ?>" class="img-fluid h-100 w-100 object-fit-cover">
        </div>
        <div class="col-md-8">
            <div class="card-body h-100 d-flex flex-column">
                <h5 class="card-title"><?= $event['ten_su_kien'] ?></h5>
                
                <div class="event-meta">
                    <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime(isset($event['thoi_gian_bat_dau']) ? $event['thoi_gian_bat_dau'] : $event['ngay_to_chuc'])) ?></span>
                    <span><i class="fas fa-map-marker-alt"></i> <?= $event['dia_diem'] ?></span>
                    <span><i class="fas fa-users"></i> <?= isset($event['so_luong_tham_gia']) ? $event['so_luong_tham_gia'] : rand(50, 200) ?> người tham gia</span>
                    <?php if (isset($event['hinh_thuc'])): ?>
                    <span><i class="fas fa-<?= strtolower($event['hinh_thuc']) === 'online' ? 'video' : 'map-marked-alt' ?>"></i> <?= $event['hinh_thuc'] ?></span>
                    <?php endif; ?>
                </div>
                
                <p class="card-text flex-grow-1">
                    <?php 
                    // Hiển thị mô tả ngắn gọn
                    $description = isset($event['mo_ta_su_kien']) ? $event['mo_ta_su_kien'] : '';
                    echo substr($description, 0, 200) . (strlen($description) > 200 ? '...' : '');
                    ?>
                </p>
                
                <div class="d-flex justify-content-between align-items-center mt-auto">
                    <a href="<?= site_url('su-kien/chi-tiet/' . $event['slug']) ?>" class="btn-link">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
                    
                    <?php if (isset($event['thoi_gian_bat_dau']) ? strtotime($event['thoi_gian_bat_dau']) > time() : strtotime($event['ngay_to_chuc']) > time()): ?>
                    <a href="<?= site_url('su-kien/register?event=' . $event['su_kien_id']) ?>" class="btn btn-sm btn-outline-primary">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div> 