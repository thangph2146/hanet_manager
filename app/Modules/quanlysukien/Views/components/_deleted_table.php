<?php
/**
 * Component hiển thị bảng dữ liệu sự kiện đã xóa
 * 
 * Các biến cần truyền vào:
 * @var array $processedData Dữ liệu sự kiện đã xóa
 * @var string $module_name Tên module
 */

// Lấy danh sách loại sự kiện để hiển thị tên loại sự kiện
$loaiSuKienModel = model('App\Modules\quanlyloaisukien\Models\LoaiSuKienModel');
$loaiSuKienList = $loaiSuKienModel->getForDropdown(false);
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
                    <th width="15%" class="align-middle">Tên sự kiện</th>
                    <th width="10%" class="align-middle">Thời gian</th>
                    <th width="10%" class="align-middle">Địa điểm</th>
                    <th width="10%" class="align-middle">Loại sự kiện</th>
                    <th width="10%" class="text-center align-middle">Hình thức</th>
                    <th width="5%" class="text-center align-middle">Trạng thái</th>
                    <th width="10%" class="text-center align-middle">Ngày xóa</th>
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
                                           value="<?= $item->su_kien_id ?>">
                                </div>
                            </td>
                            <td><?= esc($item->su_kien_id) ?></td>
                            <td><?= esc($item->ten_su_kien) ?></td>
                            <td>
                                <div>Từ: <?= date('d/m/Y H:i', strtotime($item->thoi_gian_bat_dau)) ?></div>
                                <div>Đến: <?= date('d/m/Y H:i', strtotime($item->thoi_gian_ket_thuc)) ?></div>
                            </td>
                            <td><?= esc($item->dia_diem) ?></td>
                            <td><?= esc($loaiSuKienList[$item->loai_su_kien_id] ?? 'Không xác định') ?></td>
                            <td class="text-center">
                                <?php if ($item->hinh_thuc == 'offline'): ?>
                                    <span class="badge bg-primary">Trực tiếp</span>
                                <?php elseif ($item->hinh_thuc == 'online'): ?>
                                    <span class="badge bg-info">Trực tuyến</span>
                                <?php elseif ($item->hinh_thuc == 'hybrid'): ?>
                                    <span class="badge bg-secondary">Kết hợp</span>
                                <?php else: ?>
                                    <span class="badge bg-dark">Không xác định</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($item->status == 1): ?>
                                    <span class="badge bg-success">Hoạt động</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Vô hiệu</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?= $item->getDeletedAtFormatted() ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">
                                    <button type="button" class="btn btn-success btn-sm btn-restore" 
                                            data-id="<?= $item->su_kien_id ?>" 
                                            data-name="<?= esc($item->ten_su_kien) ?>"
                                            data-bs-toggle="tooltip" title="Khôi phục">
                                        <i class="bx bx-revision"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm btn-permanent-delete" 
                                            data-id="<?= $item->su_kien_id ?>" 
                                            data-name="<?= esc($item->ten_su_kien) ?>"
                                            data-bs-toggle="tooltip" title="Xóa vĩnh viễn">
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
                                <p>Không có dữ liệu sự kiện đã xóa</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 