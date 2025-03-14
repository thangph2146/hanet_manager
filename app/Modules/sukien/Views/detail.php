<?= $this->extend('App\Modules\sukien\Views\layouts\sukien_layout') ?>

<?= $this->section('title') ?><?= isset($meta_title) ? $meta_title : $event['ten_su_kien'] . ' - Đại Học Ngân Hàng TP.HCM' ?><?= $this->endSection() ?>

<?= $this->section('description') ?><?= isset($meta_description) ? $meta_description : $event['mo_ta_su_kien'] ?><?= $this->endSection() ?>

<?= $this->section('keywords') ?><?= isset($meta_keywords) ? $meta_keywords : $event['ten_su_kien'] . ', ' . $event['loai_su_kien'] . ', sự kiện hub, đại học ngân hàng' ?><?= $this->endSection() ?>

<?= $this->section('additional_css') ?>
<?php if(isset($canonical_url)): ?>
<link rel="canonical" href="<?= $canonical_url ?>" />
<?php endif; ?>

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?= current_url() ?>">
<meta property="og:title" content="<?= $event['ten_su_kien'] ?>">
<meta property="og:description" content="<?= isset($meta_description) ? $meta_description : $event['mo_ta_su_kien'] ?>">
<meta property="og:image" content="<?= isset($og_image) ? $og_image : base_url($event['hinh_anh']) ?>">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="<?= current_url() ?>">
<meta property="twitter:title" content="<?= $event['ten_su_kien'] ?>">
<meta property="twitter:description" content="<?= isset($meta_description) ? $meta_description : $event['mo_ta_su_kien'] ?>">
<meta property="twitter:image" content="<?= isset($og_image) ? $og_image : base_url($event['hinh_anh']) ?>">

<!-- Structured Data JSON-LD -->
<?php if(isset($structured_data)): ?>
<script type="application/ld+json">
<?= $structured_data ?>
</script>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Page Header -->
    <section class="page-header bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= site_url('sukien') ?>">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="<?= site_url('su-kien/list') ?>">Danh sách sự kiện</a></li>
                            <?php if (isset($event['loai_su_kien'])): ?>
                            <li class="breadcrumb-item">
                                <a href="<?= site_url('su-kien/category/' . strtolower(str_replace(' ', '-', $event['loai_su_kien']))) ?>">
                                    <?= $event['loai_su_kien'] ?>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="breadcrumb-item active" aria-current="page"><?= $event['ten_su_kien'] ?></li>
                        </ol>
                    </nav>
                    <h1 class="fw-bold"><?= $event['ten_su_kien'] ?></h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Detail -->
    <section class="container py-5">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Event Banner -->
                <div class="event-banner mb-4">
                    <img src="<?= base_url($event['hinh_anh']) ?>" class="img-fluid rounded" alt="<?= $event['ten_su_kien'] ?>">
                </div>

                <!-- Event Meta -->
                <div class="event-meta mb-4">
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
                <div class="event-description mb-4">
                    <h3>Giới thiệu</h3>
                    <?= $event['mo_ta_su_kien'] ?>
                </div>

                <?php if (!empty($event['hashtags'])): ?>
                <div class="mb-4">
                    <h3>Hashtags</h3>
                    <p><?= implode(', ', explode(',', $event['hashtags'])) ?></p>
                </div>
                <?php endif; ?>

                <!-- Event Objectives -->
                <div class="event-objectives mb-4">
                    <h3>Mục tiêu</h3>
                    <ul>
                        <li>Thảo luận về những thách thức và cơ hội của ngành tài chính ngân hàng trong thời đại công nghệ số</li>
                        <li>Chia sẻ kinh nghiệm và giải pháp ứng dụng công nghệ trong hoạt động ngân hàng</li>
                        <li>Định hướng phát triển nguồn nhân lực ngành tài chính - ngân hàng trong tương lai</li>
                        <li>Tăng cường hợp tác giữa nhà trường và doanh nghiệp</li>
                    </ul>
                </div>

                <!-- Event Topics -->
                <div class="event-topics mb-4">
                    <h3>Chủ đề chính</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="topic-item">
                                <i class="lni lni-checkmark-circle"></i>
                                <span>Xu hướng phát triển của ngân hàng số</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="topic-item">
                                <i class="lni lni-checkmark-circle"></i>
                                <span>Ứng dụng blockchain trong tài chính</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="topic-item">
                                <i class="lni lni-checkmark-circle"></i>
                                <span>Bảo mật và quản lý rủi ro trong ngân hàng số</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="topic-item">
                                <i class="lni lni-checkmark-circle"></i>
                                <span>Đào tạo nguồn nhân lực cho ngân hàng số</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Schedule -->
                <div class="event-schedule mb-4">
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
                <div class="event-speakers mb-4">
                    <h3>Diễn giả</h3>
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="speaker-card">
                                <img src="<?= base_url('assets/images/speaker-1.jpg') ?>" class="speaker-image" alt="Speaker 1">
                                <h5>TS. Nguyễn Đình Thọ</h5>
                                <p class="text-muted">Hiệu trưởng, Trường ĐH Ngân hàng TP.HCM</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="speaker-card">
                                <img src="<?= base_url('assets/images/speaker-2.jpg') ?>" class="speaker-image" alt="Speaker 2">
                                <h5>PGS.TS. Phạm Thị Hoàng Anh</h5>
                                <p class="text-muted">Trưởng khoa Ngân hàng</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="speaker-card">
                                <img src="<?= base_url('assets/images/speaker-3.jpg') ?>" class="speaker-image" alt="Speaker 3">
                                <h5>TS. Nguyễn Minh Hà</h5>
                                <p class="text-muted">Trưởng khoa Tài chính</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="speaker-card">
                                <img src="<?= base_url('assets/images/speaker-4.jpg') ?>" class="speaker-image" alt="Speaker 4">
                                <h5>PGS.TS. Lê Thanh Tâm</h5>
                                <p class="text-muted">Trưởng khoa Kinh tế</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Share -->
                <div class="social-share mb-4">
                    <h3>Chia sẻ sự kiện</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" class="btn btn-facebook me-2"><i class="lni lni-facebook-filled"></i> Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(current_url()) ?>&text=<?= urlencode($event['ten_su_kien']) ?>" target="_blank" class="btn btn-twitter me-2"><i class="lni lni-twitter-filled"></i> Twitter</a>
                        <a href="#" class="btn btn-linkedin"><i class="lni lni-linkedin-original"></i> LinkedIn</a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Registration Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Đăng ký tham gia</h4>
                        <form action="<?= site_url('su-kien/register') ?>" method="post">
                            <input type="hidden" name="id_su_kien" value="<?= $event['id_su_kien'] ?>">
                            <input type="hidden" name="nguoi_dung_id" value="<?= rand(1000, 9999) ?>"> <!-- Giả lập ID người dùng -->
                            
                            <div class="mb-3">
                                <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ho_ten" name="ho_ten" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="so_dien_thoai" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai" required>
                            </div>
                            <div class="mb-3">
                                <label for="ma_sinh_vien" class="form-label">Mã sinh viên (nếu có)</label>
                                <input type="text" class="form-control" id="ma_sinh_vien" name="ma_sinh_vien">
                            </div>
                            <div class="mb-3">
                                <label for="noi_dung_gop_y" class="form-label">Góp ý/Câu hỏi (nếu có)</label>
                                <textarea class="form-control" id="noi_dung_gop_y" name="noi_dung_gop_y" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="nguon_gioi_thieu" class="form-label">Bạn biết đến sự kiện này từ đâu?</label>
                                <select class="form-select" id="nguon_gioi_thieu" name="nguon_gioi_thieu">
                                    <option value="Website trường">Website trường</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Email từ trường">Email từ trường</option>
                                    <option value="Bạn bè">Bạn bè</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                        </form>
                    </div>
                </div>

                <!-- Event Stats -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Thông tin đăng ký</h4>
                        <div class="text-center mb-3">
                            <div class="stats-number"><?= isset($registration_count) ? $registration_count : 0 ?></div>
                            <p class="mb-0">Người đã đăng ký</p>
                        </div>
                        <div class="progress mb-3">
                            <?php 
                                $percent = isset($registration_count) && $event['so_luong_tham_gia'] > 0 
                                    ? min(100, round(($registration_count / $event['so_luong_tham_gia']) * 100)) 
                                    : 0;
                            ?>
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percent ?>%" 
                                aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= $percent ?>%
                            </div>
                        </div>
                        <p class="text-center text-muted small">
                            Còn <?= max(0, $event['so_luong_tham_gia'] - (isset($registration_count) ? $registration_count : 0)) ?> chỗ trống
                        </p>
                    </div>
                </div>

                <!-- Event Organizer -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Đơn vị tổ chức</h4>
                        <div class="organizer-info">
                            <img src="<?= base_url('assets/images/hub-logo.png') ?>" alt="HUB Logo" class="img-fluid mb-3">
                            <h5>Trường Đại học Ngân hàng TP.HCM</h5>
                            <p class="text-muted mb-2">36 Tôn Thất Đạm, Quận 1, TP.HCM</p>
                            <p class="text-muted mb-2">Điện thoại: (028) 38 212 593</p>
                            <p class="text-muted">Email: info@hub.edu.vn</p>
                        </div>
                    </div>
                </div>

                <!-- Related Events -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Sự kiện liên quan</h4>
                        <div class="related-events">
                            <?php foreach ($related_events as $related): ?>
                            <div class="related-event-item mb-3">
                                <a href="<?= site_url('su-kien/detail/' . $related['slug']) ?>" class="text-decoration-none">
                                    <h6><?= $related['ten_su_kien'] ?></h6>
                                    <p class="text-muted small mb-0"><?= date('d/m/Y', strtotime($related['ngay_to_chuc'])) ?></p>
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?= $this->endSection() ?> 