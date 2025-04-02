<?php
/**
 * Component hiển thị bảng dữ liệu đăng ký sự kiện đã xóa
 * 
 * Các biến cần truyền vào:
 * @var array $processedData Dữ liệu đăng ký sự kiện đã xóa
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
                    <th width="10%" class="text-center align-middle">Loại</th>
                    <th width="10%" class="text-center align-middle">Trạng thái</th>
                    <th width="10%" class="text-center align-middle">Ngày xóa</th>
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
                                    value="<?= $item->getId() ?>">
                                </div>
                            </td>
                            <td><?= esc($item->getId()) ?></td>  
                            <td>
                                <div class="fw-bold"><?= esc($item->getHoTen()) ?></div>
                                <?php if ($item->getDonViToChuc()): ?>
                                    <div class="small text-muted">
                                        <i class="bx bx-building me-1"></i> <?= esc($item->getDonViToChuc()) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($item->getEmail()) ?></td>
                            <td>
                                <?php if ($suKien = $item->getSuKien()): ?>
                                    <?= esc($suKien->getTenSuKien()) ?>
                                    <?php if ($item->getMaXacNhan()): ?>
                                        <div class="small text-muted">
                                            <i class="bx bx-hash me-1"></i> <?= esc($item->getMaXacNhan()) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">Không có</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php
                                $loaiClass = '';
                                switch ($item->getLoaiNguoiDangKy()) {
                                    case 'sinh_vien':
                                        $loaiClass = 'bg-info';
                                        break;
                                    case 'giang_vien':
                                        $loaiClass = 'bg-primary';
                                        break;
                                    default:
                                        $loaiClass = 'bg-secondary';
                                }
                                ?>
                                <span class="badge <?= $loaiClass ?>"><?= $item->getLoaiNguoiDangKyText() ?></span>
                            </td>
                            <td class="text-center">
                                <?php
                                $statusClass = '';
                                switch ($item->getStatus()) {
                                    case 1:
                                        $statusClass = 'bg-success';
                                        break;
                                    case 0:
                                        $statusClass = 'bg-warning';
                                        break;
                                    case -1:
                                        $statusClass = 'bg-danger';
                                        break;
                                }
                                ?>
                                <span class="badge <?= $statusClass ?>"><?= $item->getStatusText() ?></span>
                            </td>
                            <td class="text-center">
                                <?php if ($deletedAt = $item->getDeletedAt()): ?>
                                    <?= $deletedAt->format('d/m/Y H:i:s') ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">
                                    <button type="submit" class="btn btn-success btn-sm btn-restore w-100 h-100" 
                                        data-id="<?= $item->getId() ?>" 
                                        data-name="<?= esc($item->getHoTen()) ?>"
                                        data-bs-toggle="tooltip" title="Khôi phục">
                                    <i class="bx bx-revision"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-permanent-delete w-100 h-100" 
                                            data-id="<?= $item->getId() ?>" 
                                            data-name="<?= esc($item->getHoTen()) ?>"
                                            data-bs-toggle="tooltip" title="Xóa vĩnh viễn">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" class="text-center py-3">
                            <div class="empty-state">
                                <i class="bx bx-folder-open"></i>
                                <p>Không có dữ liệu đăng ký đã xóa</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 