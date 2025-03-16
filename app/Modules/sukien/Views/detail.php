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

<!-- Animate.css -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<!-- CSS cho trang chi tiết sự kiện -->
<link rel="stylesheet" href="<?= base_url('assets/modules/sukien/css/event-detail.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Page Header -->
    <section class="page-header bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?= site_url('su-kien') ?>">Trang chủ</a></li>
                            <li class="breadcrumb-item"><a href="<?= site_url('su-kien/list') ?>">Danh sách sự kiện</a></li>
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
            <div class="col-lg-8 animate__animated animate__fadeInLeft">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs mb-4" id="eventTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="event-details-tab" data-bs-toggle="tab" data-bs-target="#event-details" type="button" role="tab" aria-controls="event-details" aria-selected="true">
                            <i class="lni lni-information"></i> Chi tiết sự kiện
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="event-participants-tab" data-bs-toggle="tab" data-bs-target="#event-participants" type="button" role="tab" aria-controls="event-participants" aria-selected="false">
                            <i class="lni lni-users"></i> Người tham gia
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="event-registration-tab" data-bs-toggle="tab" data-bs-target="#event-registration" type="button" role="tab" aria-controls="event-registration" aria-selected="false">
                            <i class="lni lni-pencil-alt"></i> Đăng ký tham gia
                        </button>
                    </li>
                </ul>
                
                <!-- Tabs Content -->
                <div class="tab-content" id="eventTabsContent">
                    <!-- Tab Chi tiết sự kiện -->
                    <?= $this->include('App\Modules\sukien\Views\components\event_detail_tab') ?>
                    
                    <!-- Tab Danh sách người tham gia -->
                    <?= $this->include('App\Modules\sukien\Views\components\event_participants_tab') ?>
                    
                    <!-- Tab Form đăng ký -->
                    <?= $this->include('App\Modules\sukien\Views\components\event_registration_tab') ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 animate__animated animate__fadeInRight">
                <?= $this->include('App\Modules\sukien\Views\components\event_sidebar') ?>
            </div>
        </div>
    </section>
    
    <!-- Nút đăng ký cố định khi cuộn trang -->
    <div class="fixed-register-btn">
        <button class="btn btn-primary rounded-circle shadow" data-bs-toggle="tab" data-bs-target="#event-registration" role="tab" aria-controls="event-registration" aria-selected="false">
            <i class="lni lni-pencil-alt"></i>
        </button>
    </div>
<?= $this->endSection() ?>

<?= $this->section('additional_js') ?>
<!-- JavaScript cho trang chi tiết sự kiện -->
<script src="<?= base_url('assets/modules/sukien/js/event-detail.js') ?>"></script>
<?= $this->endSection() ?> 