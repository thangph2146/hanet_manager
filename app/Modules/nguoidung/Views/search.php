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
            <div class="ms-auto">
                <form action="<?= base_url('nguoidung/search') ?>" method="get" class="d-flex">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm kiếm..." value="<?= $keyword ?>">
                    <button type="submit" class="btn btn-primary ms-2"><i class="bx bx-search"></i></button>
                </form>
            </div>
        </div>
        
        <div class="alert alert-info">
            <i class="bx bx-info-circle"></i> Kết quả tìm kiếm cho từ khóa: <strong>"<?= $keyword ?>"</strong>
        </div>
        
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
                            <td colspan="8" class="text-center">Không tìm thấy kết quả nào</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($nguoiDungs as $nguoiDung) : ?>
                            <tr>
                                <td><?= $nguoiDung->id ?></td>
                                <td><?= $nguoiDung->account_id ?></td>
                                <td><?= $nguoiDung->ho_ten ?></td>
                                <td><?= $nguoiDung->email ?></td>
                                <td><?= $nguoiDung->so_dien_thoai ?></td>
                                <td><?= $nguoiDung->getAccountTypeText() ?></td>
                                <td>
                                    <?php if ($nguoiDung->trang_thai) : ?>
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