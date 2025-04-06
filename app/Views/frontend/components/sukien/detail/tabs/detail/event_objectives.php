<div class="event-objectives mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
    <h3>Mục tiêu</h3>
    <?php
    // Lấy mục tiêu từ biến event
    $objectives = isset($event['muc_tieu']) ? $event['muc_tieu'] : [];
    
    // Kiểm tra xem $objectives có phải là chuỗi JSON không
    if (is_string($objectives) && !empty($objectives)) {
        // Thử giải mã JSON
        $decoded = json_decode($objectives, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $objectives = $decoded;
        } else {
            // Nếu không phải JSON hợp lệ, gán mảng rỗng để tránh lỗi
            $objectives = [];
        }
    }
    
    // Đảm bảo $objectives luôn là mảng
    if (!is_array($objectives)) {
        $objectives = [];
    }
    
    if (!empty($objectives)): 
    ?>
    <div class="objectives-list">
        <ul>
            <?php foreach ($objectives as $objective): ?>
            <li>
                <i class="lni lni-checkmark-circle"></i>
                <span><?= is_array($objective) ? ($objective['noi_dung'] ?? '') : $objective ?></span>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="lni lni-information me-2"></i> Chưa có thông tin mục tiêu cho sự kiện này.
    </div>
    <?php endif; ?>
</div>