<?php
/**
 * Component hiển thị bảng dữ liệu loại sự kiện đã xóa
 * 
 * Các biến cần truyền vào:
 * @var array $processedData Dữ liệu loại sự kiện đã xóa
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
                    <th width="25%" class="align-middle">Tên loại sự kiện</th>
                    <th width="15%" class="align-middle">Mã loại sự kiện</th>
                    <th width="15%" class="align-middle">Ngày xóa</th>
                    <th width="15%" class="text-center align-middle">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($processedData)) : ?>
                    <?php foreach ($processedData as $item) : ?>
                        <tr>
                            <td class="text-center">
                                <div class="form-check">
                                    <input class="form-check-input checkbox-item cursor-pointer" 
                                    type="checkbox" name="selected_ids[]" 
                                    value="<?= $item->loai_su_kien_id ?>">
                                </div>
                            </td>
                            <td><?= esc($item->loai_su_kien_id) ?></td>  
                            <td><?= esc($item->ten_loai_su_kien) ?></td>
                            <td><?= esc($item->ma_loai_su_kien) ?></td>
                            <td><?= $item->getDeletedAt() ? $item->getDeletedAt()->toDateTimeString() : '-' ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">
                                    <button type="button" class="btn btn-success btn-sm btn-restore" 
                                            data-id="<?= $item->loai_su_kien_id ?>" 
                                            data-name="ID: <?= esc($item->loai_su_kien_id) ?> - <?= esc($item->ten_loai_su_kien) ?>"
                                            data-bs-toggle="tooltip" title="Khôi phục">
                                        <i class="bx bx-revision"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-permanent-delete" 
                                            data-id="<?= $item->loai_su_kien_id ?>" 
                                            data-name="ID: <?= esc($item->loai_su_kien_id) ?> - <?= esc($item->ten_loai_su_kien) ?>"
                                            data-bs-toggle="tooltip" title="Xóa vĩnh viễn">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center py-3">
                            <div class="empty-state">
                                <i class="bx bx-folder-open"></i>
                                <p>Không có dữ liệu loại sự kiện đã xóa</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 