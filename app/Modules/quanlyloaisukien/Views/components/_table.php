<?php
/**
 * Component hiển thị bảng dữ liệu check-in sự kiện
 * 
 * Các biến cần truyền vào:
 * @var array $processedData Dữ liệu check-in sự kiện
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
                    <th width="20%" class="align-middle">Tên loại sự kiện</th>
                    <th width="15%" class="align-middle">Mã loại sự kiện</th>
                    <th width="25%" class="align-middle">Mô tả</th>
                    <th width="10%" class="text-center align-middle">Thứ tự</th>
                    <th width="10%" class="text-center align-middle">Trạng thái</th>
                    <th width="10%" class="text-center align-middle">Thao tác</th>
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
                            <td><?= esc($item->mo_ta) ?></td>
                            <td class="text-center"><?= $item->thu_tu ?></td>
                            <td class="text-center">
                                <?php if ($item->status == 1): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Vô hiệu</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">
                                    <a href="<?= site_url($module_name . "/edit/{$item->loai_su_kien_id}") ?>" 
                                       class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Sửa">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                            data-id="<?= $item->loai_su_kien_id ?>" 
                                            data-name="<?= esc($item->ten_loai_su_kien) ?>"
                                            data-bs-toggle="tooltip" title="Xóa">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" class="text-center py-3">
                            <div class="empty-state">
                                <i class="bx bx-folder-open"></i>
                                <p>Không có dữ liệu loại sự kiện</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 