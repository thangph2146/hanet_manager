<?= $this->extend('layouts/default') ?>

<?= $this->section('title') ?>
$title
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">$title</h4>
                    <div class="card-tools">
                        <a href="<?= base_url($module_name) ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                        <a href="<?= base_url($module_name . '/edit/' . $data->camera_id) ?>" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th style="width: 200px">ID</th>
                                    <td><?= $data->camera_id ?></td>
                                </tr>
                                <tr>
                                    <th>Tên Camera</th>
                                    <td><?= $data->ten_camera ?></td>
                                </tr>
                                <tr>
                                    <th>Mã Camera</th>
                                    <td><?= $data->ma_camera ?></td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        <span class="badge <?= $data->status ? 'badge-success' : 'badge-danger' ?>">
                                            <?= $data->status ? 'Hoạt động' : 'Không hoạt động' ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td><?= $data->created_at ? $data->created_at->format('d/m/Y H:i:s') : '' ?></td>
                                </tr>
                                <tr>
                                    <th>Ngày cập nhật</th>
                                    <td><?= $data->updated_at ? $data->updated_at->format('d/m/Y H:i:s') : '' ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
