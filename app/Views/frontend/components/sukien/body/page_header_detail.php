<section class="page-header bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= site_url('su-kien') ?>">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="<?= site_url('su-kien') ?>">Danh sách sự kiện</a></li>
                            <?php if (isset($event['loai_su_kien'])): ?>
                            <li class="breadcrumb-item">
                                <?php 
                                // Lấy slug của loại sự kiện
                                $categorySlug = isset($event['loai_su_kien_slug']) ? $event['loai_su_kien_slug'] : strtolower(str_replace(' ', '-', $event['loai_su_kien']));
                                ?>
                                <a href="<?= site_url('su-kien/loai/' . $categorySlug) ?>">
                                    <?= $event['loai_su_kien'] ?>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="breadcrumb-item active" aria-current="page"><?= $event['ten_su_kien'] ?></li>
                        </ol>
                    </nav>
                    <h1 class="fw-bold text-white animate__animated animate__fadeInDown"><?= $event['ten_su_kien'] ?></h1>
                    <div class="event-meta-stats d-flex justify-content-start mt-2 animate__animated animate__fadeInUp">
                        <div class="meta-stat me-4">
                            <i class="lni lni-eye"></i> <?= isset($event['so_luot_xem']) ? number_format($event['so_luot_xem']) : 0 ?> lượt xem
                        </div>
                        <div class="meta-stat me-4">
                            <i class="lni lni-users"></i> <?= isset($registrationCount) ? number_format($registrationCount) : 0 ?> người đăng ký
                        </div>
                        <div class="meta-stat">
                            <i class="lni lni-calendar"></i> <?= date('d/m/Y', strtotime($event['ngay_to_chuc'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>