<?= $this->extend('layouts/default'); ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="<?= base_url('assets/plugins/sweetalert2/sweetalert2.min.css') ?>">
<link rel="stylesheet" href="<?= base_url('assets/plugins/summernote/summernote-bs4.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content'); ?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <!-- Breadcrumb -->
    <?= view('components/_breakcrump', [
        'title' => $title,
        'dashboard_url' => site_url('admin'),
        'breadcrumbs' => [
            ['url' => site_url('phongkhoa'), 'title' => 'Phòng khoa'],
            ['title' => isset($phongKhoa->phong_khoa_id) ? 'Cập nhật' : 'Thêm mới', 'active' => true]
        ],
        'actions' => [
            ['url' => site_url('phongkhoa'), 'title' => 'Quay lại danh sách', 'icon' => 'fas fa-arrow-left']
        ]
    ]) ?>
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?= $title ?></h3>
                    </div>
                    <!-- Hiển thị thông báo lỗi nếu có -->
                    <?php if (session()->has('error')) : ?>
                        <div class="alert alert-danger">
                            <?= session('error') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session('errors') as $error) : ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->has('success')) : ?>
                        <div class="alert alert-success">
                            <?= session('success') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form -->
                    <form action="<?= $phongKhoa->phong_khoa_id ? base_url('phongkhoa/update/' . $phongKhoa->phong_khoa_id) : base_url('phongkhoa/create') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="ma_phong_khoa">Mã phòng khoa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ma_phong_khoa" name="ma_phong_khoa" value="<?= old('ma_phong_khoa', $phongKhoa->ma_phong_khoa ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="ten_phong_khoa">Tên phòng khoa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ten_phong_khoa" name="ten_phong_khoa" value="<?= old('ten_phong_khoa', $phongKhoa->ten_phong_khoa ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="ghi_chu">Ghi chú</label>
                                <textarea class="form-control summernote" id="ghi_chu" name="ghi_chu" rows="5"><?= old('ghi_chu', $phongKhoa->ghi_chu ?? '') ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" <?= (old('status', $phongKhoa->status ?? 1) == 1) ? 'selected' : '' ?>>Kích hoạt</option>
                                    <option value="0" <?= (old('status', $phongKhoa->status ?? 1) == 0) ? 'selected' : '' ?>>Vô hiệu hóa</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Lưu
                            </button>
                            <a href="<?= base_url('phongkhoa') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->

<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
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
        placeholder: 'Nhập ghi chú ở đây...'
    });
});
</script>
<?= $this->endSection() ?> 