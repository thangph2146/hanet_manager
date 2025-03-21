<?= $this->extend('layouts/default'); ?>

<?= $this->section('linkHref') ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/summernote/summernote-bs4.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('title') ?><?= $title ?><?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<?= view('components/_breakcrump', [
    'title' => $title,
    'dashboard_url' => site_url('users/dashboard'),
    'breadcrumbs' => [
        ['url' => site_url('loainguoidung'), 'title' => lang('LoaiNguoiDung.manageTitle')],
        ['title' => isset($loaiNguoiDung->loai_nguoi_dung_id) ? lang('LoaiNguoiDung.edit') : lang('LoaiNguoiDung.createNew'), 'active' => true]
    ],
    'actions' => [
        ['url' => site_url('loainguoidung'), 'title' => lang('LoaiNguoiDung.backToList')]
    ]
]) ?>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="card radius-10">
            <div class="card-body">
                <!-- Hiển thị thông báo lỗi nếu có -->
                <?php if (session()->has('error')) : ?>
                    <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                        <div class="text-white"><?= session('error') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('errors')) : ?>
                    <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                        <div class="text-white">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error) : ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->has('success')) : ?>
                    <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                        <div class="text-white"><?= session('success') ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Form sử dụng helper formRender -->
                <?php
                // Tải helper formRender
                helper('formRender');
                
                // Mở form
                $formAction = $loaiNguoiDung->loai_nguoi_dung_id ? base_url('loainguoidung/update/' . $loaiNguoiDung->loai_nguoi_dung_id) : base_url('loainguoidung/create');
                echo render_form_open($formAction, ['id' => 'loai-nguoi-dung-form', 'class' => 'needs-validation', 'novalidate' => '']);
                ?>
                
                <div class="form-group mb-3">
                    <?= render_form_input(
                        'ten_loai', 
                        old('ten_loai', $loaiNguoiDung->ten_loai ?? ''), 
                        ['placeholder' => lang('LoaiNguoiDung.namePlaceholder')], 
                        'text', 
                        lang('LoaiNguoiDung.name') . ' <span class="text-danger">*</span>', 
                        true, 
                        session('errors.ten_loai') ?? ''
                    ) ?>
                </div>

                <div class="form-group mb-3">
                    <?= render_form_textarea(
                        'mo_ta', 
                        old('mo_ta', $loaiNguoiDung->mo_ta ?? ''), 
                        ['id' => 'summernote', 'class' => 'form-control summernote', 'placeholder' => lang('LoaiNguoiDung.descPlaceholder')], 
                        lang('LoaiNguoiDung.description'), 
                        false, 
                        session('errors.mo_ta') ?? ''
                    ) ?>
                </div>

                <div class="form-group mb-3">
                    <?php
                    $statusOptions = [
                        '1' => lang('LoaiNguoiDung.statusActive'),
                        '0' => lang('LoaiNguoiDung.statusInactive')
                    ];
                    
                    echo render_form_dropdown(
                        'status', 
                        $statusOptions, 
                        old('status', $loaiNguoiDung->status ?? '1'), 
                        ['class' => 'form-control form-select'], 
                        lang('LoaiNguoiDung.status')
                    );
                    ?>
                </div>

                <div class="form-group mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save me-1"></i> <?= lang('LoaiNguoiDung.save') ?>
                    </button>
                    <a href="<?= base_url('loainguoidung') ?>" class="btn btn-secondary">
                        <i class="bx bx-arrow-back me-1"></i> <?= lang('LoaiNguoiDung.back') ?>
                    </a>
                </div>

                <?= render_form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('script') ?>
<script src="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.js') ?>"></script>
<script src="<?= base_url('assets/plugins/summernote/summernote-bs4.min.js') ?>"></script>
<script>
$(document).ready(function() {
    $('.summernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['view', ['fullscreen', 'help']]
        ],
        placeholder: '<?= lang('LoaiNguoiDung.descPlaceholder') ?>'
    });
    
    // Xác thực form
    $('#loai-nguoi-dung-form').on('submit', function(e) {
        if (!this.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(this).addClass('was-validated');
    });
});
</script>
<?= $this->endSection() ?> 