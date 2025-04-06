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

// Lưu ý: Hàm getEventTypeName() đã được định nghĩa trong event_list.php
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

// Lấy slug của loại sự kiện
$categorySlug = isset($event['loai_su_kien_slug']) ? $event['loai_su_kien_slug'] : strtolower(str_replace(' ', '-', $loai_su_kien));

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

// Kiểm tra người dùng đã đăng nhập chưa và đã đăng ký sự kiện này chưa
$isLoggedIn = service('authstudent')->isLoggedInStudent();
$userData = $isLoggedIn ? service('authstudent')->getUserData() : null;
$isRegistered = false;

// Nếu đã đăng nhập và có dữ liệu sự kiện, kiểm tra đã đăng ký chưa
if ($isLoggedIn && $userData && isset($event['su_kien_id'])) {
    // Lấy email của người dùng
    $userEmail = $userData->Email ?? '';
    
    // Khởi tạo model đăng ký sự kiện
    $dangKySuKienModel = new \App\Modules\quanlydangkysukien\Models\DangKySuKienModel();
    
    // Kiểm tra đã đăng ký chưa dựa trên email
    $checkRegistration = $dangKySuKienModel->where('su_kien_id', $event['su_kien_id'])
                                         ->where('email', $userEmail)
                                         ->first();
    
    // Nếu đã đăng ký
    if ($checkRegistration) {
        $isRegistered = true;
    }
}
?>
<div class="event-card h-100  <?= $featured ? 'featured' : '' ?>">
    <div class="event-category" style="background-color: <?= $randomColor ?>;">
        <a href="<?= site_url('su-kien/loai/' . $categorySlug) ?>"
         class="category-tag <?= strtolower(str_replace(' ', '-', $loai_su_kien)) ?>"
         style="background-color: <?= $randomColor ?>;">
            <i class="fas fa-tag" style="font-size: 1.25rem; background-color: <?= $randomColor ?>;"></i> <?= $loai_su_kien ?>
        </a>
    </div>
    
    
    <div class="card-body">
        <h5 class="card-title"><?= $event['ten_su_kien'] ?></h5>
        
        <div class="event-meta">
            <span><i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime(isset($event['thoi_gian_bat_dau']) ? $event['thoi_gian_bat_dau'] : $event['ngay_to_chuc'])) ?></span>
            <span><i class="fas fa-map-marker-alt"></i> <?= $event['dia_diem'] ?></span>
            <span><i class="fas fa-users"></i> <?= isset($event['so_luong_tham_gia']) ? $event['so_luong_tham_gia'] : rand(50, 200) ?> người tham gia</span>
            <?php if (isset($event['hinh_thuc'])): ?>
            <span><i class="fas fa-<?= strtolower($event['hinh_thuc']) === 'online' ? 'video' : 'map-marked-alt' ?>"></i> <?= $event['hinh_thuc'] ?></span>
            <?php endif; ?>
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
        <a href="<?= site_url('su-kien/chi-tiet/' . $event['slug']) ?>" class="btn-link">Xem chi tiết <i class="fas fa-arrow-right"></i></a>
        
        <?php if (strtotime($event['ngay_to_chuc']) > time()): ?>
            <?php if ($isRegistered): ?>
                <!-- Người dùng đã đăng ký -->
                <span class="badge bg-success"><i class="fas fa-check"></i> Đã đăng ký</span>
            <?php elseif ($isLoggedIn): ?>
                <!-- Đã đăng nhập, hiển thị form đăng ký ngay -->
                <form method="post" action="<?= base_url('/su-kien/register-now') ?>" class="d-inline">
                    <?= csrf_field() ?>
                    <input type="hidden" name="su_kien_id" value="<?= $event['su_kien_id'] ?>">
                    <input type="hidden" name="ho_ten" value="<?= $userData->FullName ?? '' ?>">
                    <input type="hidden" name="email" value="<?= $userData->Email ?? '' ?>">
                    <input type="hidden" name="so_dien_thoai" value="<?= $userData->MobilePhone ?? '' ?>">
                    <button type="submit" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-check-circle"></i> Đăng ký ngay
                    </button>
                </form>
            <?php else: ?>
                <!-- Chưa đăng nhập, hiển thị link đăng nhập -->
                <a href="<?= site_url('login/nguoi-dung?redirect=' . current_url()) ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-sign-in-alt"></i> Đăng nhập để đăng ký
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div> 