<?= $this->extend('frontend\layouts\sukien_layout') ?>

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
    <?= $this->include('frontend\components\sukien\body\page_header_detail') ?>

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
               <?= $this->include('frontend\components\sukien\detail\tabs\tabs_detail') ?>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4 animate__animated animate__fadeInRight">
                <?= $this->include('frontend\components\sukien\detail\sidebar\event_sidebar') ?>
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