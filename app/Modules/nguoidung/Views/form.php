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
                <li class="breadcrumb-item"><a href="<?= base_url() ?>"><i class="bx bx-home-alt"></i></a></li>
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
            </div>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($validation)) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    <?php foreach ($validation as $error) : ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?= $form_open ?>
        <div class="row">
            <div class="col-md-6">
                <div class="card border shadow-none">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Thông tin tài khoản</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Mã tài khoản <span class="text-danger">*</span></label>
                            <?= $fields['AccountId'] ?>
                            <?php if (isset($errors['AccountId'])) : ?>
                                <div class="text-danger"><?= $errors['AccountId'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Loại tài khoản <span class="text-danger">*</span></label>
                            <?= $fields['AccountType'] ?>
                            <?php if (isset($errors['AccountType'])) : ?>
                                <div class="text-danger"><?= $errors['AccountType'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Họ</label>
                            <?= $fields['FirstName'] ?>
                            <?php if (isset($errors['FirstName'])) : ?>
                                <div class="text-danger"><?= $errors['FirstName'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Họ tên đầy đủ <span class="text-danger">*</span></label>
                            <?= $fields['FullName'] ?>
                            <?php if (isset($errors['FullName'])) : ?>
                                <div class="text-danger"><?= $errors['FullName'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu <?= isset($nguoiDung) ? '' : '<span class="text-danger">*</span>' ?></label>
                            <?= $fields['PW'] ?>
                            <?php if (isset($errors['PW'])) : ?>
                                <div class="text-danger"><?= $errors['PW'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mật khẩu local</label>
                            <?= $fields['mat_khau_local'] ?>
                            <?php if (isset($errors['mat_khau_local'])) : ?>
                                <div class="text-danger"><?= $errors['mat_khau_local'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái</label>
                            <?= $fields['status'] ?>
                            <?php if (isset($errors['status'])) : ?>
                                <div class="text-danger"><?= $errors['status'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border shadow-none">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Thông tin liên hệ</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <?= $fields['Email'] ?>
                            <?php if (isset($errors['Email'])) : ?>
                                <div class="text-danger"><?= $errors['Email'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <?= $fields['MobilePhone'] ?>
                            <?php if (isset($errors['MobilePhone'])) : ?>
                                <div class="text-danger"><?= $errors['MobilePhone'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Điện thoại nhà</label>
                            <?= $fields['HomePhone'] ?>
                            <?php if (isset($errors['HomePhone'])) : ?>
                                <div class="text-danger"><?= $errors['HomePhone'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Điện thoại nhà khác</label>
                            <?= $fields['HomePhone1'] ?>
                            <?php if (isset($errors['HomePhone1'])) : ?>
                                <div class="text-danger"><?= $errors['HomePhone1'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card border shadow-none mt-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Thông tin học tập</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Loại người dùng</label>
                            <?= $fields['loai_nguoi_dung_id'] ?>
                            <?php if (isset($errors['loai_nguoi_dung_id'])) : ?>
                                <div class="text-danger"><?= $errors['loai_nguoi_dung_id'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Năm học</label>
                            <?= $fields['nam_hoc_id'] ?>
                            <?php if (isset($errors['nam_hoc_id'])) : ?>
                                <div class="text-danger"><?= $errors['nam_hoc_id'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Bậc học</label>
                            <?= $fields['bac_hoc_id'] ?>
                            <?php if (isset($errors['bac_hoc_id'])) : ?>
                                <div class="text-danger"><?= $errors['bac_hoc_id'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hệ đào tạo</label>
                            <?= $fields['he_dao_tao_id'] ?>
                            <?php if (isset($errors['he_dao_tao_id'])) : ?>
                                <div class="text-danger"><?= $errors['he_dao_tao_id'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngành</label>
                            <?= $fields['nganh_id'] ?>
                            <?php if (isset($errors['nganh_id'])) : ?>
                                <div class="text-danger"><?= $errors['nganh_id'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phòng/Khoa</label>
                            <?= $fields['phong_khoa_id'] ?>
                            <?php if (isset($errors['phong_khoa_id'])) : ?>
                                <div class="text-danger"><?= $errors['phong_khoa_id'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <?= $fields['submit'] ?>
        </div>
        <?= $form_close ?>
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