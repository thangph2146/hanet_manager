<div class="event-description mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.3s;">
        <h3>Giới thiệu</h3>
        <div class="p-3 bg-light rounded">
            <?= $event['mo_ta_su_kien'] ?>
        </div>
    </div>

    <?php if (!empty($event['hashtags'])): ?>
    <div class="mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
        <h3>Hashtags</h3>
        <div class="hashtags">
            <?php foreach(explode(',', $event['hashtags']) as $tag): ?>
                <span class="badge bg-primary me-2 mb-2">#<?= trim($tag) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>