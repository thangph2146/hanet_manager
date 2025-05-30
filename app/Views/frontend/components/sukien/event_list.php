<?php
/**
 * Component Event List
 * Hiển thị danh sách sự kiện
 * 
 * @param array $events Mảng chứa danh sách sự kiện
 * @param string $layout Kiểu hiển thị: 'grid' hoặc 'list'
 * @param bool $show_featured Có hiển thị sự kiện nổi bật không
 * @param string $empty_message Thông báo khi không có sự kiện
 * @param string $category Danh mục sự kiện hiện tại (nếu có)
 * @param string $search Từ khóa tìm kiếm (nếu có)
 */

// Đảm bảo biến $events tồn tại
if (!isset($events)) {
    $events = [];
}

// Đặt giá trị mặc định cho các biến
$layout = isset($layout) ? $layout : 'grid';
$show_featured = isset($show_featured) ? $show_featured : true;
$empty_message = isset($empty_message) ? $empty_message : 'Hiện tại không có sự kiện nào.';
$category = isset($category) ? $category : null;
$search = isset($search) ? $search : null;

// Kiểm tra nếu loai_su_kien là đối tượng, trích xuất tên từ thuộc tính
function getEventTypeName($event_type) {
    if (is_object($event_type)) {
        // Nếu là đối tượng LoaiSuKien
        if (method_exists($event_type, 'getTenLoaiSuKien')) {
            return $event_type->getTenLoaiSuKien();
        } else if (isset($event_type->ten_loai_su_kien)) {
            return $event_type->ten_loai_su_kien;
        } else if (isset($event_type->attributes) && isset($event_type->attributes['ten_loai_su_kien'])) {
            return $event_type->attributes['ten_loai_su_kien'];
        }
        
        // Trường hợp không xác định được thuộc tính
        return 'Sự kiện';
    } else if (is_array($event_type) && isset($event_type['ten_loai_su_kien'])) {
        return $event_type['ten_loai_su_kien'];
    }
    
    // Nếu là chuỗi hoặc khác
    return $event_type;
}
?>

<?php if (empty($events)): ?>
<div class="col-md-12 text-center">
    <div class="alert alert-info">
        <h4>Không tìm thấy sự kiện</h4>
        <?php if (!empty($search)): ?>
        <p>Không tìm thấy sự kiện nào sắp diễn ra phù hợp với từ khóa "<?= esc($search) ?>". Vui lòng thử lại với từ khóa khác.</p>
        <?php elseif (!empty($category)): ?>
        <p>Hiện tại không có sự kiện nào sắp diễn ra trong danh mục <?= $category ?>. Vui lòng quay lại sau.</p>
        <?php else: ?>
        <p><?= $empty_message ?></p>
        <p><small class="text-muted">Chúng tôi chỉ hiển thị các sự kiện có trạng thái hoạt động và thời gian diễn ra trong tương lai.</small></p>
        <?php endif; ?>
        <a href="<?= site_url('su-kien') ?>" class="btn btn-primary mt-3">Xem tất cả sự kiện</a>
    </div>
</div>
<?php else: ?>
    <?php if ($layout === 'grid'): ?>
        <?php foreach ($events as $key => $event): ?>
        <?php 
            // Xử lý trường hợp loại sự kiện là đối tượng hoặc chuỗi bằng hàm getEventTypeName
            $loai_su_kien = getEventTypeName($event['loai_su_kien']);
        ?>
        <div class="col-md-4 mb-4" data-category="<?= strtolower(str_replace(' ', '-', $loai_su_kien)) ?>">
            <?php 
            // Sử dụng component event_card
            echo view('frontend\components\sukien\event_card', [
                'event' => $event,
                'featured' => ($show_featured && $key === 0 && !$search && !$category) // Chỉ nổi bật sự kiện đầu tiên khi không tìm kiếm hoặc lọc
            ]);
            ?>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <?php foreach ($events as $key => $event): ?>
        <?php 
            // Xử lý trường hợp loại sự kiện là đối tượng hoặc chuỗi bằng hàm getEventTypeName
            $loai_su_kien = getEventTypeName($event['loai_su_kien']);
        ?>
        <div class="col-12 mb-4" data-category="<?= strtolower(str_replace(' ', '-', $loai_su_kien)) ?>">
            <?php 
            // Sử dụng component event_card_horizontal
            echo view('frontend\components\sukien\event_card_horizontal', [
                'event' => $event,
                'featured' => ($show_featured && $key === 0 && !$search && !$category) // Chỉ nổi bật sự kiện đầu tiên khi không tìm kiếm hoặc lọc
            ]);
            ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endif; ?> 