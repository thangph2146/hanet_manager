<div class="event-topics mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
    <h3>Chủ đề</h3>
    <?php
    // Lấy chủ đề từ biến event
    $topics = isset($event['chu_de']) ? $event['chu_de'] : [];
    
    // Kiểm tra xem $topics có phải là chuỗi JSON không
    if (is_string($topics) && !empty($topics)) {
        // Thử giải mã JSON
        $decoded = json_decode($topics, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $topics = $decoded;
        } else {
            // Nếu không phải JSON hợp lệ, gán mảng rỗng để tránh lỗi
            $topics = [];
        }
    }
    
    // Đảm bảo $topics luôn là mảng
    if (!is_array($topics)) {
        $topics = [];
    }
    
    if (!empty($topics)): 
    ?>
    <div class="topic-list">
        <?php foreach ($topics as $topic): ?>
        <div class="topic-item">
            <h5><?= $topic['tieu_de'] ?? '' ?></h5>
            <p><?= $topic['mo_ta'] ?? '' ?></p>
            <?php if(!empty($topic['danh_sach'])): ?>
            <ul>
                <?php foreach($topic['danh_sach'] as $item): ?>
                <li><?= $item ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info">
        <i class="lni lni-information me-2"></i> Chưa có thông tin chủ đề cho sự kiện này.
    </div>
    <?php endif; ?>
</div>