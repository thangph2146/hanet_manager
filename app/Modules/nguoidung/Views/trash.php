<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('title_content') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('bread_cum_link') ?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">Quản lý người dùng</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="bx bx-home-alt"></i></a>
                </li>
                <li class="breadcrumb-item"><a href="<?= base_url('nguoidung') ?>">Danh sách người dùng</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </nav>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div>
                <a href="<?= base_url('nguoidung') ?>" class="btn btn-secondary px-3"><i class="bx bx-arrow-back"></i>Quay lại</a>
            </div>
        </div>
        
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">ID</th>
                        <th width="10%">Mã tài khoản</th>
                        <th width="15%">Họ tên</th>
                        <th width="15%">Email</th>
                        <th width="10%">Số điện thoại</th>
                        <th width="15%">Ngày xóa</th>
                        <th width="30%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($nguoiDungs)) : ?>
                        <tr>
                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($nguoiDungs as $nguoiDung) : ?>
                            <tr>
                                <td><?= $nguoiDung->id ?></td>
                                <td><?= $nguoiDung->account_id ?></td>
                                <td><?= $nguoiDung->ho_ten ?></td>
                                <td><?= $nguoiDung->email ?></td>
                                <td><?= $nguoiDung->so_dien_thoai ?></td>
                                <td><?= date('d/m/Y H:i:s', strtotime($nguoiDung->deleted_at)) ?></td>
                                <td>
                                    <a href="<?= base_url('nguoidung/restore/' . $nguoiDung->id) ?>" class="btn btn-success btn-sm" onclick="return confirm('Bạn có chắc chắn muốn khôi phục người dùng này?')"><i class="bx bx-refresh"></i> Khôi phục</a>
                                    <a href="<?= base_url('nguoidung/purge/' . $nguoiDung->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn người dùng này? Hành động này không thể hoàn tác!')"><i class="bx bx-trash"></i> Xóa vĩnh viễn</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(function() {
        // Tự động ẩn thông báo sau 5 giây
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>
<?= $this->endSection() ?> 