<div class="table-responsive">
    <div class="table-container">
        <table id="dataTable" class="table table-striped table-hover m-0 w-100">
            <thead class="table-light">
                <tr>
                    <th width="3%" class="text-center align-middle">
                        <div class="form-check">
                            <input type="checkbox" id="select-all" class="form-check-input cursor-pointer">
                        </div>
                    </th>
                    <th width="3%" class="align-middle">ID</th>
                    <th width="17%" class="align-middle">Tên sự kiện</th>
                    <th width="15%" class="align-middle">Thời gian</th>
                    <th width="12%" class="align-middle">Địa điểm</th>
                    <th width="10%" class="align-middle">Loại sự kiện</th>
                    <th width="10%" class="align-middle">Hình thức</th>
                    <th width="10%" class="align-middle">Tham gia</th>
                    <th width="5%" class="text-center align-middle">Trạng thái</th>
                    <th width="8%" class="text-center align-middle">Thao tác</th>
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
                            <td><?= $item->getId() ?></td>
                            <td>
                                <div class="fw-bold"><?= esc($item->getTenSuKien()) ?></div>
                                <?php if ($item->getSlug()): ?>
                                <div class="small text-muted"><?= $item->getSlug() ?></div>
                                <?php endif; ?>
                                <?php if ($item->getDonViToChuc()): ?>
                                <div class="small text-muted">
                                    <i class="bx bx-buildings me-1"></i><?= esc($item->getDonViToChuc()) ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div>Từ: <?= $item->getThoiGianBatDauFormatted('d/m/Y H:i') ?></div>
                                <div>Đến: <?= $item->getThoiGianKetThucFormatted('d/m/Y H:i') ?></div>
                                <?php if ($item->getThoiGianCheckinBatDau()): ?>
                                <div class="small text-muted">
                                    <i class="bx bx-log-in me-1"></i>Check-in: 
                                    <?= $item->getThoiGianCheckinBatDauFormatted('d/m/Y H:i') ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?= esc($item->getDiaDiem()) ?></div>
                                <?php if ($item->getDiaChiCuThe()): ?>
                                <div class="small text-muted"><?= esc($item->getDiaChiCuThe()) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($item->getTenLoaiSuKien()) ?></td>
                            <td>
                                <?php $hinhThuc = $item->getHinhThuc(); ?>
                                <?php if ($hinhThuc == 'offline'): ?>
                                    <span class="badge bg-primary">Trực tiếp</span>
                                <?php elseif ($hinhThuc == 'online'): ?>
                                    <span class="badge bg-info">Trực tuyến</span>
                                    <?php if ($item->getLinkOnline()): ?>
                                    <div class="small mt-1">
                                        <a href="<?= esc($item->getLinkOnline()) ?>" target="_blank" class="text-primary">
                                            <i class="bx bx-link-external me-1"></i>Link
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                <?php elseif ($hinhThuc == 'hybrid'): ?>
                                    <span class="badge bg-secondary">Kết hợp</span>
                                <?php else: ?>
                                    <span class="badge bg-dark">Không xác định</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-user me-1"></i>
                                    <span>Giới hạn: <?= $item->getSoLuongThamGia() ?></span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="bx bx-user-check me-1 text-success"></i>
                                    <span class="text-success">Đăng ký: <?= $item->getTongDangKy() ?></span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="bx bx-log-in-circle me-1 text-info"></i>
                                    <span class="text-info">Check-in: <?= $item->getTongCheckIn() ?></span>
                                </div>
                            </td>
                            <td class="text-center text-black">
                                <?= $item->getStatusHtml() ?>
                                <?php if ($item->getSoLuotXem() > 0): ?>
                                <div class="small text-muted mt-1">
                                    <i class="bx bx-show me-1"></i><?= $item->getSoLuotXem() ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-1 action-btn-group">
                                    <a href="<?= $item->getDetailUrl() ?>" 
                                       class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="Xem chi tiết">
                                        <i class="bx bx-info-circle text-white"></i>
                                    </a>
                                    <a href="<?= $item->getEditUrl() ?>" 
                                       class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Sửa">
                                        <i class="bx bx-edit"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm btn-delete" 
                                            data-id="<?= $item->getId() ?>" 
                                            data-name="<?= esc($item->getTenSuKien()) ?>"
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
                                <p>Không có dữ liệu sự kiện</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div> 