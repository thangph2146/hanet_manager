<?php
/**
 * Component hiển thị chi tiết camera
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu camera cần hiển thị
 * @var string $module_name Tên module
 */
?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= $title ?? 'Chi tiết camera' ?></h4>
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
                        <th>IP Camera</th>
                        <td><?= $data->ip_camera ?? '-' ?></td>
                    </tr>
                    <tr>
                        <th>Port</th>
                        <td><?= $data->port ?? '-' ?></td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <?php if (method_exists($data, 'getStatusLabel')): ?>
                                <?= $data->getStatusLabel() ?>
                            <?php else: ?>
                                <span class="badge <?= $data->status ? 'bg-success' : 'bg-danger' ?>">
                                    <?= $data->status ? 'Hoạt động' : 'Không hoạt động' ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo</th>
                        <td>
                            <?php if (method_exists($data, 'getCreatedAtFormatted')): ?>
                                <?= $data->getCreatedAtFormatted() ?>
                            <?php else: ?>
                                <?= $data->created_at ? $data->created_at->format('d/m/Y H:i:s') : '-' ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày cập nhật</th>
                        <td>
                            <?php if (method_exists($data, 'getUpdatedAtFormatted')): ?>
                                <?= $data->getUpdatedAtFormatted() ?>
                            <?php else: ?>
                                <?= $data->updated_at ? $data->updated_at->format('d/m/Y H:i:s') : '-' ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> 