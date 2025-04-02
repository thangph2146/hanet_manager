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
                    <th width="15%" class="align-middle">Họ tên</th>
                    <th width="15%" class="align-middle">Email</th>
                    <th width="15%" class="align-middle">Sự kiện</th>
                    <th width="12%" class="align-middle">Thời gian check-in</th>
                    <th width="8%" class="text-center align-middle">Loại check-in</th>
                    <th width="10%" class="text-center align-middle">Hình thức</th>
                    <th width="8%" class="text-center align-middle">Trạng thái</th>
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
                                    value="<?= $item->checkin_sukien_id ?>">
                                </div>
                            </td>
                            <td><?= esc($item->checkin_sukien_id) ?></td>  
                            <td><?= esc($item->ho_ten) ?></td>
                            <td><?= esc($item->email) ?></td>
                            <td><?= esc($item->ten_su_kien ?? '(Không xác định)') ?></td>
                            <td>
                                <?= $item->getThoiGianCheckInFormatted('d/m/Y H:i') ?>
                            </td>
                            <td class="text-center">
                                <?= $item->getCheckinTypeText() ?>
                            </td>
                            <td class="text-center">
                                <?= $item->getHinhThucThamGiaText() ?>
                            </td>
                            <td class="text-center">
                                <?= $item->getStatusText() ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">
                                    <a href="<?= site_url($module_name . "/detail/{$item->checkin_sukien_id}") ?>" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                        <i class="bx bx-info-circle text-white"></i>
                                    </a>
                                    <a href="<?= site_url($module_name . "/edit/{$item->checkin_sukien_id}") ?>" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Sửa">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                            data-id="<?= $item->checkin_sukien_id ?>" 
                                            data-name="ID: <?= esc($item->checkin_sukien_id) ?> - <?= esc($item->ho_ten) ?>"
                                            data-bs-toggle="tooltip" title="Xóa">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10" class="text-center py-3">
                            <div class="empty-state">
                                <i class="bx bx-folder-open"></i>
                                <p>Không có dữ liệu check-in</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 