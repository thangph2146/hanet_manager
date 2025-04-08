<?php
// Xử lý dữ liệu sự kiện liên quan nếu chưa có
if (!isset($related_events) && isset($event['su_kien_id']) && isset($event['loai_su_kien_id'])) {
    $suKienModel = new \App\Modules\quanlysukien\Models\SuKienModel();
    
    // Lấy sự kiện liên quan từ model (cùng loại, không bao gồm sự kiện hiện tại)
    $related_events = $suKienModel->getRelatedEvents($event['su_kien_id'], $event['loai_su_kien_id'], 3);
    
    // Chuyển đổi sang định dạng mảng nếu là đối tượng
    if (!empty($related_events) && is_object($related_events[0])) {
        $formattedEvents = [];
        foreach ($related_events as $rel) {
            $formattedEvents[] = [
                'su_kien_id' => $rel->su_kien_id,
                'ten_su_kien' => $rel->ten_su_kien,
                'dia_diem' => $rel->dia_diem,
                'ngay_to_chuc' => $rel->thoi_gian_bat_dau_su_kien ?? $rel->ngay_to_chuc,
                'slug' => $rel->slug
            ];
        }
        $related_events = $formattedEvents;
    }
}
?>

<div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.6s;">
    <div class="card-header text-white py-3">
        <h4 class="card-title mb-0"><i class="lni lni-calendar me-2"></i> Sự kiện liên quan</h4>
    </div>
    <div class="card-body">
        <div class="related-events">
            <?php if(isset($related_events) && !empty($related_events)): ?>
                <?php foreach ($related_events as $related): ?>
                <div class="related-event-item mb-3">
                    <a href="<?= site_url('su-kien/chi-tiet/' . ($related['slug'] ?? '')) ?>" class="text-decoration-none">
                        <div class="d-flex align-items-center">
                            <div class="event-date text-center me-3">
                                <?php 
                                $eventDate = isset($related['ngay_to_chuc']) ? $related['ngay_to_chuc'] : 
                                    (isset($related['thoi_gian_bat_dau_su_kien']) ? $related['thoi_gian_bat_dau_su_kien'] : date('Y-m-d'));
                                $eventTime = isset($related['gio_bat_dau']) ? $related['gio_bat_dau'] : 
                                    (isset($related['thoi_gian_bat_dau_su_kien']) ? date('H:i', strtotime($related['thoi_gian_bat_dau_su_kien'])) : '08:00');
                                ?>
                                <div class="date-day"><?= date('d', strtotime($eventDate)) ?></div>
                                <div class="date-month"><?= date('m/Y', strtotime($eventDate)) ?></div>
                            </div>
                            <div>
                                <h6 class="mb-1"><?= esc($related['ten_su_kien']) ?></h6>
                                <p class="text-muted small mb-0">
                                    <i class="lni lni-map-marker me-1"></i> <?= esc($related['dia_diem'] ?? 'Chưa cập nhật') ?>
                                    <br>
                                    <i class="lni lni-alarm-clock me-1"></i> <?= $eventTime ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Không có sự kiện liên quan</p>
            <?php endif; ?>
        </div>
    </div>
</div>