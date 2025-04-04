<div class="card shadow-sm mb-4 animate__animated animate__fadeInRight" style="animation-delay: 0.6s;">
        <div class="card-header text-white py-3">
            <h4 class="card-title mb-0"><i class="lni lni-calendar me-2"></i> Sự kiện liên quan</h4>
        </div>
        <div class="card-body">
            <div class="related-events">
                <?php if(isset($related_events) && !empty($related_events)): ?>
                    <?php foreach ($related_events as $related): ?>
                    <div class="related-event-item mb-3">
                        <a href="<?= site_url('su-kien/chi-tiet/' . $related['slug']) ?>" class="text-decoration-none">
                            <div class="d-flex align-items-center">
                                <div class="event-date text-center me-3">
                                    <div class="date-day"><?= date('d', strtotime($related['ngay_to_chuc'])) ?></div>
                                    <div class="date-month"><?= date('m/Y', strtotime($related['ngay_to_chuc'])) ?></div>
                                </div>
                                <div>
                                    <h6 class="mb-1"><?= $related['ten_su_kien'] ?></h6>
                                    <p class="text-muted small mb-0">
                                        <i class="lni lni-map-marker me-1"></i> <?= $related['dia_diem'] ?>
                                        <br>
                                        <i class="lni lni-alarm-clock me-1"></i> <?= date('H:i', strtotime($related['ngay_to_chuc'])) ?>
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