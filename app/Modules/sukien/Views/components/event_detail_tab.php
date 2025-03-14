<?php
/**
 * Component hiển thị tab chi tiết sự kiện
 */
?>

<div class="tab-pane fade show active" id="event-details" role="tabpanel" aria-labelledby="event-details-tab">
    <!-- Event Banner -->
    <div class="event-banner mb-4 animate__animated animate__fadeIn">
        <img src="<?= base_url($event['hinh_anh']) ?>" class="img-fluid rounded" alt="<?= $event['ten_su_kien'] ?>">
    </div>

    <!-- Event Meta -->
    <div class="event-meta mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <div class="row">
            <div class="col-md-6">
                <div class="meta-item">
                    <i class="lni lni-calendar"></i>
                    <span>Ngày: <?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="meta-item">
                    <i class="lni lni-clock"></i>
                    <span>Thời gian: <?= $event['thoi_gian'] ?></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="meta-item">
                    <i class="lni lni-map-marker"></i>
                    <span>Địa điểm: <?= $event['dia_diem'] ?></span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="meta-item">
                    <i class="lni lni-users"></i>
                    <span>Số lượng: <?= $event['so_luong_tham_gia'] ?> người</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Description -->
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

    <!-- Event Objectives -->
    <div class="event-objectives mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.5s;">
        <h3>Mục tiêu</h3>
        <div class="card">
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex align-items-center">
                        <i class="lni lni-checkmark-circle text-primary me-3"></i>
                        <span>Thảo luận về những thách thức và cơ hội của ngành tài chính ngân hàng trong thời đại công nghệ số</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="lni lni-checkmark-circle text-primary me-3"></i>
                        <span>Chia sẻ kinh nghiệm và giải pháp ứng dụng công nghệ trong hoạt động ngân hàng</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="lni lni-checkmark-circle text-primary me-3"></i>
                        <span>Định hướng phát triển nguồn nhân lực ngành tài chính - ngân hàng trong tương lai</span>
                    </li>
                    <li class="list-group-item d-flex align-items-center">
                        <i class="lni lni-checkmark-circle text-primary me-3"></i>
                        <span>Tăng cường hợp tác giữa nhà trường và doanh nghiệp</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Event Topics -->
    <div class="event-topics mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.6s;">
        <h3>Chủ đề chính</h3>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="topic-item">
                    <i class="lni lni-checkmark-circle"></i>
                    <span>Xu hướng phát triển của ngân hàng số</span>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="topic-item">
                    <i class="lni lni-checkmark-circle"></i>
                    <span>Ứng dụng blockchain trong tài chính</span>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="topic-item">
                    <i class="lni lni-checkmark-circle"></i>
                    <span>Bảo mật và quản lý rủi ro trong ngân hàng số</span>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="topic-item">
                    <i class="lni lni-checkmark-circle"></i>
                    <span>Đào tạo nguồn nhân lực cho ngân hàng số</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Schedule -->
    <div class="event-schedule mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.7s;">
        <h3>Lịch trình</h3>
        <div class="schedule-timeline">
            <div class="schedule-item">
                <div class="time">08:00 - 08:30</div>
                <div class="content">
                    <h5>Đăng ký và khai mạc</h5>
                    <p>Đón tiếp đại biểu và phát biểu khai mạc</p>
                </div>
            </div>
            <div class="schedule-item">
                <div class="time">08:30 - 10:00</div>
                <div class="content">
                    <h5>Phiên thảo luận 1</h5>
                    <p>Xu hướng phát triển của ngân hàng số</p>
                </div>
            </div>
            <div class="schedule-item">
                <div class="time">10:00 - 10:30</div>
                <div class="content">
                    <h5>Giải lao</h5>
                    <p>Tea break và giao lưu</p>
                </div>
            </div>
            <div class="schedule-item">
                <div class="time">10:30 - 12:00</div>
                <div class="content">
                    <h5>Phiên thảo luận 2</h5>
                    <p>Ứng dụng blockchain trong tài chính</p>
                </div>
            </div>
            <div class="schedule-item">
                <div class="time">12:00 - 13:30</div>
                <div class="content">
                    <h5>Nghỉ trưa</h5>
                    <p>Tiệc trưa và giao lưu</p>
                </div>
            </div>
            <div class="schedule-item">
                <div class="time">13:30 - 15:00</div>
                <div class="content">
                    <h5>Phiên thảo luận 3</h5>
                    <p>Bảo mật và quản lý rủi ro trong ngân hàng số</p>
                </div>
            </div>
            <div class="schedule-item">
                <div class="time">15:00 - 15:30</div>
                <div class="content">
                    <h5>Giải lao</h5>
                    <p>Tea break và giao lưu</p>
                </div>
            </div>
            <div class="schedule-item">
                <div class="time">15:30 - 17:00</div>
                <div class="content">
                    <h5>Phiên thảo luận 4</h5>
                    <p>Đào tạo nguồn nhân lực cho ngân hàng số</p>
                </div>
            </div>
            <div class="schedule-item">
                <div class="time">17:00 - 17:30</div>
                <div class="content">
                    <h5>Bế mạc</h5>
                    <p>Tổng kết và trao chứng nhận</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Speakers -->
    <div class="event-speakers mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.8s;">
        <h3>Diễn giả</h3>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="speaker-card">
                    <img src="<?= base_url('assets/images/speaker-1.jpg') ?>" class="speaker-image" alt="Speaker 1">
                    <h5>TS. Nguyễn Đình Thọ</h5>
                    <p class="text-muted">Hiệu trưởng, Trường ĐH Ngân hàng TP.HCM</p>
                    <div class="speaker-social mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="lni lni-facebook-filled"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="lni lni-linkedin-original"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle"><i class="lni lni-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="speaker-card">
                    <img src="<?= base_url('assets/images/speaker-2.jpg') ?>" class="speaker-image" alt="Speaker 2">
                    <h5>PGS.TS. Phạm Thị Hoàng Anh</h5>
                    <p class="text-muted">Trưởng khoa Ngân hàng</p>
                    <div class="speaker-social mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="lni lni-facebook-filled"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="lni lni-linkedin-original"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle"><i class="lni lni-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="speaker-card">
                    <img src="<?= base_url('assets/images/speaker-3.jpg') ?>" class="speaker-image" alt="Speaker 3">
                    <h5>TS. Nguyễn Minh Hà</h5>
                    <p class="text-muted">Trưởng khoa Tài chính</p>
                    <div class="speaker-social mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="lni lni-facebook-filled"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="lni lni-linkedin-original"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle"><i class="lni lni-envelope"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="speaker-card">
                    <img src="<?= base_url('assets/images/speaker-4.jpg') ?>" class="speaker-image" alt="Speaker 4">
                    <h5>PGS.TS. Lê Thanh Tâm</h5>
                    <p class="text-muted">Trưởng khoa Kinh tế</p>
                    <div class="speaker-social mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="lni lni-facebook-filled"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-1"><i class="lni lni-linkedin-original"></i></a>
                        <a href="#" class="btn btn-sm btn-outline-primary rounded-circle"><i class="lni lni-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Share -->
    <div class="social-share mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.9s;">
        <h3>Chia sẻ sự kiện</h3>
        <div class="card">
            <div class="card-body">
                <p class="mb-3">Hãy chia sẻ sự kiện này đến bạn bè và đồng nghiệp của bạn:</p>
                <div class="share-buttons">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" class="btn btn-facebook me-2 mb-2"><i class="lni lni-facebook-filled me-1"></i> Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($event['ten_su_kien']) ?>" target="_blank" class="btn btn-twitter me-2 mb-2"><i class="lni lni-twitter-filled me-1"></i> Twitter</a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(current_url()) ?>&title=<?= urlencode($event['ten_su_kien']) ?>" target="_blank" class="btn btn-linkedin me-2 mb-2"><i class="lni lni-linkedin-original me-1"></i> LinkedIn</a>
                    <a href="mailto:?subject=<?= urlencode($event['ten_su_kien']) ?>&body=<?= urlencode('Xem chi tiết sự kiện tại: ' . current_url()) ?>" class="btn btn-outline-primary mb-2"><i class="lni lni-envelope me-1"></i> Email</a>
                </div>
            </div>
        </div>
    </div>
</div> 