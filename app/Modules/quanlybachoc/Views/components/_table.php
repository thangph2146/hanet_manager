<?php
/**
 * Component hiển thị bảng dữ liệu bậc học
 * 
 * Các biến cần truyền vào:
 * @var array $processedData Dữ liệu các bậc học
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
                    <th width="20%" class="align-middle">Bậc học</th>
                    <th width="20%" class="align-middle">Mã bậc học</th>
                    <th width="10%" class="text-center align-middle">Trạng thái</th>
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
                                    value="<?= $item->bac_hoc_id ?>">
                                </div>
                            </td>
                            <td><?= esc($item->bac_hoc_id) ?></td>  
                            <td><?= esc($item->ten_bac_hoc) ?></td> 
                            <td><?= esc($item->ma_bac_hoc) ?></td>
                            <td class="text-center">
                                <form action="<?= site_url($module_name . '/statusMultiple') ?>" 
                                    method="post" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="selected_ids[]" value="<?= $item->bac_hoc_id ?>">
                                    <input type="hidden" name="return_url" value="<?= current_url() ?>">
                                            <button type="submit" class="btn btn-sm <?= $item->isActive() ? 'btn-success' : 'btn-danger' ?> status-toggle" 
                                                    data-bs-toggle="tooltip" 
                                                    title="<?= $item->isActive() ? 'Đang hoạt động - Click để tắt' : 'Đang tắt - Click để bật' ?>">
                                                <?= $item->getStatusLabel() ?>
                                </button>
                            </form>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">
                                    <a href="<?= site_url($module_name . "/view/{$item->bac_hoc_id}") ?>" class="btn btn-info btn-sm w-100 h-100" data-bs-toggle="tooltip" title="Xem chi tiết">
                                        <i class="bx bx-info-circle text-white"></i>
                                    </a>
                                    <a href="<?= site_url($module_name . "/edit/{$item->bac_hoc_id}") ?>" class="btn btn-primary btn-sm w-100 h-100" data-bs-toggle="tooltip" title="Sửa">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete w-100 h-100" 
                                            data-id="<?= $item->bac_hoc_id ?>" 
                                            data-name="ID: <?= esc($item->bac_hoc_id) ?> - <?= esc($item->ten_bac_hoc) ?>"
                                            data-bs-toggle="tooltip" title="Xóa">
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
                                <p>Không có dữ liệu</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 