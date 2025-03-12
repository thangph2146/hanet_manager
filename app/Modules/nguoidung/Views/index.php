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
                <li class="breadcrumb-item active" aria-current="page">Danh sách người dùng</li>
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
                <a href="<?= base_url('nguoidung/create') ?>" class="btn btn-primary px-3"><i class="bx bx-plus"></i>Thêm mới</a>
                <a href="<?= base_url('nguoidung/trash') ?>" class="btn btn-outline-danger px-3"><i class="bx bx-trash"></i>Thùng rác</a>
            </div>
            <div class="ms-auto">
                <form action="<?= base_url('nguoidung/search') ?>" method="get" class="d-flex">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm...">
                    <button type="submit" class="btn btn-primary ms-2"><i class="bx bx-search"></i></button>
                </form>
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
                        <th width="10%">Loại tài khoản</th>
                        <th width="10%">Trạng thái</th>
                        <th width="25%">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($nguoiDungs)) : ?>
                        <tr>
                            <td colspan="8" class="text-center">Không có dữ liệu</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($nguoiDungs as $nguoiDung) : ?>
                            <tr>
                                <td><?= $nguoiDung->id ?></td>
                                <td><?= $nguoiDung->AccountId ?></td>
                                <td><?= $nguoiDung->FullName ?></td>
                                <td><?= $nguoiDung->Email ?></td>
                                <td><?= $nguoiDung->MobilePhone ?></td>
                                <td><?= $nguoiDung->getAccountTypeText() ?></td>
                                <td>
                                    <?php if ($nguoiDung->status) : ?>
                                        <span class="badge bg-success">Hoạt động</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Không hoạt động</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('nguoidung/show/' . $nguoiDung->id) ?>" class="btn btn-info btn-sm"><i class="bx bx-show"></i></a>
                                    <a href="<?= base_url('nguoidung/edit/' . $nguoiDung->id) ?>" class="btn btn-warning btn-sm"><i class="bx bx-edit"></i></a>
                                    <a href="<?= base_url('nguoidung/delete/' . $nguoiDung->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')"><i class="bx bx-trash"></i></a>
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