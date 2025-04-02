<?php
/**
 * Component hiển thị bảng dữ liệu đăng ký sự kiện
 * 
 * Các biến cần truyền vào:
 * @var array $processedData Dữ liệu đăng ký sự kiện
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
                    <th width="12%" class="align-middle">Thời gian đăng ký</th>
                    <th width="8%" class="text-center align-middle">Hình thức</th>
                    <th width="8%" class="text-center align-middle">Trạng thái</th>
                    <th width="15%" class="text-center align-middle">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($processedData)): ?>
                    <?php foreach ($processedData as $item): ?>
                        <tr>
                            <td class="text-center align-middle">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input cursor-pointer select-item" value="<?= $item->getId() ?>">
                                </div>
                            </td>
                            <td class="align-middle"><?= $item->getId() ?></td>
                            <td class="align-middle"><?= esc($item->getHoTen()) ?></td>
                            <td class="align-middle"><?= esc($item->getEmail()) ?></td>
                            <td class="align-middle"><?= esc($item->getSuKien()->getTenSuKien()) ?></td>
                            <td class="align-middle"><?= $item->getThoiGianDangKyFormatted() ?></td>
                            <td class="text-center align-middle">
                                <span class="badge bg-<?= $item->getHinhThucThamGia() === 'online' ? 'info' : 'success' ?>">
                                    <?= $item->getHinhThucThamGiaText() ?>
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge bg-<?= $item->getStatus() ? 'success' : 'danger' ?>">
                                    <?= $item->getStatusText() ?>
                                </span>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <a href="<?= base_url($module_name . '/detail/' . $item->getId()) ?>" 
                                       class="btn btn-sm btn-info" 
                                       title="Chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= base_url($module_name . '/edit/' . $item->getId()) ?>" 
                                       class="btn btn-sm btn-warning" 
                                       title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-danger btn-delete" 
                                            data-id="<?= $item->getId() ?>" 
                                            title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center py-3">Không có dữ liệu</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 