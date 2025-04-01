<?php
/**
 * Component hiển thị bảng dữ liệu camera đã xóa
 * 
 * Các biến cần truyền vào:
 * @var array $processedData Dữ liệu các camera đã xóa
 * @var string $module_name Tên module
 */
?>

<div class="table-responsive">
    <div class="table-container">
        <table id="dataTable" class="table table-striped table-hover m-0 w-100">
            <thead class="table-light">
                <tr>
                    <th width="5%" class="text-center align-middle">
                        <div class="form-check">
                            <input type="checkbox" id="select-all" class="form-check-input cursor-pointer">
                        </div>
                    </th>
                    <th width="5%" class="align-middle">ID</th>
                    <th width="20%" class="align-middle">Tên camera</th>
                    <th width="10%" class="align-middle">Mã camera</th>
                    <th width="10%" class="text-center align-middle">Trạng thái</th>
                    <th width="15%" class="text-center align-middle">Ngày xóa</th>
                    <th width="20%" class="text-center align-middle">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($processedData)) : ?>
                    <?php foreach ($processedData as $item) : ?>
                        <tr>
                            <td class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input checkbox-item cursor-pointer" 
                                    type="checkbox" name="selected_items[]" 
                                    value="<?= $item->camera_id ?>">
                                </div>
                            </td>
                            <td><?= esc($item->camera_id) ?></td>  
                            <td><?= esc($item->ten_camera) ?></td> 
                            <td><?= esc($item->ma_camera) ?></td>
                            <td class="text-center">
                                <?php if (method_exists($item, 'getStatusLabel')): ?>
                                    <?= $item->getStatusLabel() ?>
                                <?php else: ?>
                                    <span class="badge <?= $item->status == 1 ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $item->status == 1 ? 'Hoạt động' : 'Không hoạt động' ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if (method_exists($item, 'getDeletedAtFormatted')): ?>
                                    <?= $item->getDeletedAtFormatted() ?>
                                <?php else: ?>
                                    <?= $item->deleted_at ? date('d/m/Y H:i:s', strtotime($item->deleted_at)) : '' ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">
                                    <button type="button" class="btn btn-success btn-sm btn-restore w-100 h-100" 
                                            data-id="<?= $item->camera_id ?>" 
                                            data-name="<?= esc($item->ten_camera) ?>"
                                            data-bs-toggle="tooltip" title="Khôi phục">
                                        <i class="bx bx-revision"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-permanent-delete w-100 h-100" 
                                            data-id="<?= $item->camera_id ?>" 
                                            data-name="<?= esc($item->ten_camera) ?>"
                                            data-bs-toggle="tooltip" title="Xóa vĩnh viễn">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center py-3">
                            <div class="empty-state">
                                <i class="bx bx-folder-open"></i>
                                <p>Không có dữ liệu</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 