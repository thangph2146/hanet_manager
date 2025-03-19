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
            ['url' => site_url('loainguoidung'), 'title' => 'Loại người dùng'],
            ['title' => isset($loaiNguoiDung->loai_nguoi_dung_id) ? 'Cập nhật' : 'Thêm mới', 'active' => true]
        ],
        'actions' => [
            ['url' => site_url('loainguoidung'), 'title' => 'Quay lại danh sách', 'icon' => 'fas fa-arrow-left']
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
                    <form action="<?= $loaiNguoiDung->loai_nguoi_dung_id ? base_url('loainguoidung/update/' . $loaiNguoiDung->loai_nguoi_dung_id) : base_url('loainguoidung/create') ?>" method="post">
                        <?= csrf_field() ?>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="ten_loai">Tên loại người dùng <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="ten_loai" name="ten_loai" value="<?= old('ten_loai', $loaiNguoiDung->ten_loai ?? '') ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="mo_ta">Mô tả</label>
                                <textarea class="form-control summernote" id="mo_ta" name="mo_ta" rows="5"><?= old('mo_ta', $loaiNguoiDung->mo_ta ?? '') ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" <?= (old('status', $loaiNguoiDung->status ?? 1) == 1) ? 'selected' : '' ?>>Kích hoạt</option>
                                    <option value="0" <?= (old('status', $loaiNguoiDung->status ?? 1) == 0) ? 'selected' : '' ?>>Vô hiệu hóa</option>
                                </select>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i> Lưu
                            </button>
                            <a href="<?= base_url('loainguoidung') ?>" class="btn btn-secondary">
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
        placeholder: 'Nhập mô tả ở đây...'
    });
});
</script>
<?= $this->endSection() ?> 