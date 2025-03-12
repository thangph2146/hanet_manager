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
        <div class="d-flex align-items-center mb-4">
            <div>
                <a href="<?= base_url('nguoidung') ?>" class="btn btn-secondary px-3"><i class="bx bx-arrow-back"></i>Quay lại</a>
                <a href="<?= base_url('nguoidung/edit/' . $nguoiDung->id) ?>" class="btn btn-warning px-3"><i class="bx bx-edit"></i>Chỉnh sửa</a>
                <a href="<?= base_url('nguoidung/delete/' . $nguoiDung->id) ?>" class="btn btn-danger px-3" onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')"><i class="bx bx-trash"></i>Xóa</a>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card border shadow-none">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin tài khoản</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">ID</th>
                                <td><?= $nguoiDung->id ?></td>
                            </tr>
                            <tr>
                                <th>Mã tài khoản</th>
                                <td><?= $nguoiDung->AccountId ?></td>
                            </tr>
                            <tr>
                                <th>Loại tài khoản</th>
                                <td><?= $nguoiDung->getAccountTypeText() ?></td>
                            </tr>
                            <tr>
                                <th>Họ</th>
                                <td><?= $nguoiDung->FirstName ?></td>
                            </tr>
                            <tr>
                                <th>Họ tên đầy đủ</th>
                                <td><?= $nguoiDung->FullName ?></td>
                            </tr>
                            <tr>
                                <th>Trạng thái</th>
                                <td>
                                    <?php if ($nguoiDung->status) : ?>
                                        <span class="badge bg-success">Hoạt động</span>
                                    <?php else : ?>
                                        <span class="badge bg-danger">Không hoạt động</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="card border shadow-none mt-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Thông tin liên hệ</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Email</th>
                                <td><?= $nguoiDung->Email ?></td>
                            </tr>
                            <tr>
                                <th>Số điện thoại</th>
                                <td><?= $nguoiDung->MobilePhone ?></td>
                            </tr>
                            <tr>
                                <th>Điện thoại nhà</th>
                                <td><?= $nguoiDung->HomePhone ?? 'Chưa có thông tin' ?></td>
                            </tr>
                            <tr>
                                <th>Điện thoại nhà khác</th>
                                <td><?= $nguoiDung->HomePhone1 ?? 'Chưa có thông tin' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card border shadow-none">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Thông tin học tập</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Loại người dùng</th>
                                <td>
                                    <?php
                                    $loaiNguoiDung = [
                                        1 => 'Quản trị viên',
                                        2 => 'Giảng viên',
                                        3 => 'Sinh viên',
                                        4 => 'Nhân viên'
                                    ];
                                    echo $loaiNguoiDung[$nguoiDung->loai_nguoi_dung_id] ?? 'Không xác định';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Năm học</th>
                                <td>
                                    <?php
                                    $namHoc = [
                                        1 => '2023-2024',
                                        2 => '2024-2025'
                                    ];
                                    echo $namHoc[$nguoiDung->nam_hoc_id] ?? 'Không xác định';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Bậc học</th>
                                <td>
                                    <?php
                                    $bacHoc = [
                                        1 => 'Đại học',
                                        2 => 'Cao đẳng',
                                        3 => 'Thạc sĩ',
                                        4 => 'Tiến sĩ'
                                    ];
                                    echo $bacHoc[$nguoiDung->bac_hoc_id] ?? 'Không xác định';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Hệ đào tạo</th>
                                <td>
                                    <?php
                                    $heDaoTao = [
                                        1 => 'Chính quy',
                                        2 => 'Liên thông',
                                        3 => 'Vừa làm vừa học'
                                    ];
                                    echo $heDaoTao[$nguoiDung->he_dao_tao_id] ?? 'Không xác định';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Ngành</th>
                                <td>
                                    <?php
                                    $nganh = [
                                        1 => 'Công nghệ thông tin',
                                        2 => 'Kế toán',
                                        3 => 'Quản trị kinh doanh'
                                    ];
                                    echo $nganh[$nguoiDung->nganh_id] ?? 'Không xác định';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Phòng/Khoa</th>
                                <td>
                                    <?php
                                    $phongKhoa = [
                                        1 => 'Phòng đào tạo',
                                        2 => 'Khoa CNTT',
                                        3 => 'Khoa Kinh tế',
                                        4 => 'Phòng hành chính'
                                    ];
                                    echo $phongKhoa[$nguoiDung->phong_khoa_id] ?? 'Không xác định';
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="card border shadow-none mt-4">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">Thông tin hệ thống</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Ngày tạo</th>
                                <td><?= $nguoiDung->created_at ? date('d/m/Y H:i:s', strtotime($nguoiDung->created_at)) : 'Chưa có thông tin' ?></td>
                            </tr>
                            <tr>
                                <th>Ngày cập nhật</th>
                                <td><?= $nguoiDung->updated_at ? date('d/m/Y H:i:s', strtotime($nguoiDung->updated_at)) : 'Chưa có thông tin' ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?> 