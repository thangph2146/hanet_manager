<div class="event-description mb-5 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
    <div class="border-bottom mb-4">
        <h3 class="section-title text-primary fw-bold">
            <i class="lni lni-text-format me-2"></i>
            Mô tả sự kiện
        </h3>
    </div>

    <?php if (!empty($event['mo_ta_su_kien'])): ?>
    <div class="mb-3">
        <h5 class="fw-bold text-primary mb-2">Tóm tắt</h5>
        <div class="lead">
            <?= $event['mo_ta_su_kien'] ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($event['chi_tiet_su_kien'])): ?>
    <div class="mb-4">
        <h5 class="fw-bold text-primary mb-2">Chi tiết</h5>
        <div class="event-content">
            <?= $event['chi_tiet_su_kien'] ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (!empty($event['hashtag'])): ?>
    <div class="event-hashtags mt-3">
        <?php 
        $hashtags = $event['hashtag'];
        
        // Nếu là chuỗi, chuyển đổi thành mảng
        if (is_string($hashtags)) {
            $hashtags = explode(',', $hashtags);
        }
        
        // Hiển thị các hashtag
        foreach ($hashtags as $tag): 
            $tag = trim($tag);
            if (!empty($tag)):
                // Thêm # nếu chưa có
                if (strpos($tag, '#') !== 0) {
                    $tag = '#' . $tag;
                }
        ?>
        <a href="javascript:void(0);" class="hashtag badge bg-primary-soft text-primary me-2 mb-2"><?= $tag ?></a>
        <?php 
            endif;
        endforeach; 
        ?>
    </div>
    <?php endif; ?>
</div>