<?php
/**
 * Component hiển thị chi tiết check-in sự kiện
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu check-in cần hiển thị
 * @var string $module_name Tên module
 */
?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= $title ?? 'Chi tiết check-in sự kiện' ?></h4>
        <div class="card-tools">
            <a href="<?= base_url($module_name) ?>" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="<?= base_url($module_name . '/edit/' . $data->checkin_sukien_id) ?>" class="btn btn-sm btn-primary">
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
                        <td><?= $data->checkin_sukien_id ?></td>
                    </tr>
                    <tr>
                        <th>Sự kiện</th>
                        <td>
                            <?php
                            $suKien = $data->getSuKien();
                            echo $suKien ? esc($suKien->ten_su_kien) : esc($data->ten_su_kien ?? '(Không xác định)');
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Họ tên</th>
                        <td><?= esc($data->ho_ten) ?></td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><?= esc($data->email) ?></td>
                    </tr>
                    <tr>
                        <th>Thời gian check-in</th>
                        <td><?= $data->getThoiGianCheckInFormatted() ?></td>
                    </tr>
                    <tr>
                        <th>Loại check-in</th>
                        <td><?= $data->getCheckinTypeHtml() ?></td>
                    </tr>
                    <tr>
                        <th>Hình thức tham gia</th>
                        <td><?= $data->getHinhThucThamGiaHtml() ?></td>
                    </tr>
                    <tr>
                        <th>Mã xác nhận</th>
                        <td><?= esc($data->ma_xac_nhan) ?: '-' ?></td>
                    </tr>
                    <?php if ($data->isFaceCheckIn()): ?>
                    <tr>
                        <th>Xác minh khuôn mặt</th>
                        <td><?= $data->getFaceVerifiedHtml() ?></td>
                    </tr>
                    <?php if ($data->hasFaceImage()): ?>
                    <tr>
                        <th>Ảnh khuôn mặt</th>
                        <td>
                            <img src="<?= $data->getFaceImageUrl() ?>" alt="Ảnh khuôn mặt" class="img-thumbnail" style="max-width: 200px">
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($data->getFaceMatchScore() !== null): ?>
                    <tr>
                        <th>Điểm số khớp khuôn mặt</th>
                        <td><?= $data->getFaceMatchScorePercent() ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php endif; ?>
                    <tr>
                        <th>Trạng thái</th>
                        <td><?= $data->getStatusHtml() ?></td>
                    </tr>
                    <?php if (!empty($data->getLocationData())): ?>
                    <tr>
                        <th>Dữ liệu vị trí</th>
                        <td><?= esc($data->getLocationData()) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($data->getDeviceInfo())): ?>
                    <tr>
                        <th>Thông tin thiết bị</th>
                        <td><?= esc($data->getFormattedDeviceInfo() ?: $data->getDeviceInfo()) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($data->getIpAddress())): ?>
                    <tr>
                        <th>Địa chỉ IP</th>
                        <td><?= esc($data->getIpAddress()) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php 
                    $formattedInfo = $data->getFormattedThongTinBoSung();
                    if (!empty($formattedInfo)): 
                    ?>
                    <tr>
                        <th>Thông tin bổ sung</th>
                        <td>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($formattedInfo as $label => $value): ?>
                                <li><strong><?= esc($label) ?>:</strong> <?= esc($value) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($data->getGhiChu())): ?>
                    <tr>
                        <th>Ghi chú</th>
                        <td><?= nl2br(esc($data->getGhiChu())) ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Ngày tạo</th>
                        <td><?= $data->getCreatedAtFormatted() ?></td>
                    </tr>
                    <tr>
                        <th>Ngày cập nhật</th>
                        <td><?= $data->getUpdatedAtFormatted() ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> 