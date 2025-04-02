<?php
/**
 * Component Event Card
 * Hiển thị thông tin sự kiện trong một thẻ
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
<?php
// Tạo mảng màu nền ngẫu nhiên
$backgroundColors = [
    '#D32F2F', '#C2185B', '#7B1FA2', '#512DA8', '#303F9F', 
    '#1976D2', '#0288D1', '#0097A7', '#00796B', '#388E3C',
    '#689F38', '#AFB42B', '#FBC02D', '#FFA000', '#F57C00',
    '#E64A19', '#5D4037', '#455A64', '#7E57C2', '#00ACC1'
];
$randomColor = $backgroundColors[array_rand($backgroundColors)];

// Lấy slug của loại sự kiện
$categorySlug = isset($event['loai_su_kien_slug']) ? $event['loai_su_kien_slug'] : strtolower(str_replace(' ', '-', $event['loai_su_kien']));

// Lấy số lượng đăng ký thực tế nếu có
$sukienModel = new \App\Modules\sukien\Models\SukienModel();
$registrationCount = isset($event['registration_count']) ? $event['registration_count'] : 0;

// Nếu không có sẵn thông tin registration_count, có thể lấy từ model
if ($registrationCount === 0 && isset($event['su_kien_id'])) {
    // Thông thường bạn sẽ lấy từ controller và truyền vào view
    // Nhưng ở đây chúng ta gọi trực tiếp từ model
    $registrations = $sukienModel->getRegistrations($event['su_kien_id']);
    $registrationCount = count($registrations);
}
?>
<div class="event-card h-100  <?= $featured ? 'featured' : '' ?>">
    <div class="event-category" style="background-color: <?= $randomColor ?>;">
        <a href="<?= site_url('su-kien/loai/' . $categorySlug) ?>"
         class="category-tag <?= strtolower(str_replace(' ', '-', $event['loai_su_kien'])) ?>"
         style="background-color: <?= $randomColor ?>;">
            <i class="fas fa-tag" style="font-size: 1.25rem; background-color: <?= $randomColor ?>;"></i> <?= $event['loai_su_kien'] ?>
        </a>
    </div>
    
    
    <div class="card-body">
        <h5 class="card-title"><?= $event['ten_su_kien'] ?></h5>
        
        <div class="event-meta">
            <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?></span>
            <span><i class="fas fa-map-marker-alt"></i> <?= $event['dia_diem'] ?></span>
            <span><i class="fas fa-users"></i> <?= isset($event['so_luong_tham_gia']) ? $event['so_luong_tham_gia'] : rand(50, 200) ?> người tham gia</span>
        </div>
        
        <p class="card-text">
            <?php 
            // Hiển thị mô tả ngắn gọn
            $description = isset($event['mo_ta_su_kien']) ? $event['mo_ta_su_kien'] : '';
            echo substr($description, 0, 120) . (strlen($description) > 120 ? '...' : '');
            ?>
        </p>
        
        <!-- Thêm thông tin lượt xem và người đăng ký -->
        <div class="event-stats d-flex justify-content-between mt-2">
            <span class="event-views small text-muted">
                <i class="far fa-eye"></i> <?= isset($event['so_luot_xem']) ? number_format($event['so_luot_xem']) : 0 ?> lượt xem
            </span>
            <span class="event-registrations small text-muted">
                <i class="far fa-user"></i> <?= number_format($registrationCount) ?> đã đăng ký
            </span>
        </div>
    </div>
    
    <div class="card-footer">
        <a href="<?= site_url('su-kien/detail/' . $event['slug']) ?>" class="btn-link">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
        
        <?php if (strtotime($event['ngay_to_chuc']) > time()): ?>
        <a href="<?= site_url('su-kien/register?event=' . $event['su_kien_id']) ?>" class="btn btn-sm btn-outline-primary">Đăng ký</a>
        <?php endif; ?>
    </div>
</div> 