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
?>

<?php if (empty($events)): ?>
<div class="col-md-12 text-center">
    <div class="alert alert-info">
        <h4>Không tìm thấy sự kiện</h4>
        <?php if (!empty($search)): ?>
        <p>Không tìm thấy sự kiện nào phù hợp với từ khóa "<?= esc($search) ?>". Vui lòng thử lại với từ khóa khác.</p>
        <?php elseif (!empty($category)): ?>
        <p>Hiện tại không có sự kiện nào trong danh mục <?= $category ?>. Vui lòng quay lại sau.</p>
        <?php else: ?>
        <p><?= $empty_message ?></p>
        <?php endif; ?>
        <a href="<?= site_url('su-kien/list') ?>" class="btn btn-primary mt-3">Xem tất cả sự kiện</a>
    </div>
</div>
<?php else: ?>
    <?php if ($layout === 'grid'): ?>
        <?php foreach ($events as $key => $event): ?>
        <div class="col-md-4 mb-4" data-category="<?= strtolower(str_replace(' ', '-', $event['loai_su_kien'])) ?>">
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
        <div class="col-12 mb-4" data-category="<?= strtolower(str_replace(' ', '-', $event['loai_su_kien'])) ?>">
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