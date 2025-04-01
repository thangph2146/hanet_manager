<?php
/**
 * Component hiển thị chi tiết check-out sự kiện
 * 
 * Các biến cần truyền vào:
 * @var object $data Dữ liệu check-out cần hiển thị
 * @var string $module_name Tên module
 */
?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= $title ?? 'Chi tiết check-out sự kiện' ?></h4>
        <div class="card-tools">
            <a href="<?= base_url($module_name) ?>" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
            <a href="<?= base_url($module_name . '/edit/' . $data->checkout_sukien_id) ?>" class="btn btn-sm btn-primary">
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
                        <td><?= $data->checkout_sukien_id ?></td>
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
                        <th>Thời gian check-out</th>
                        <td><?= $data->getThoiGianCheckOutFormatted() ?></td>
                    </tr>
                    <tr>
                        <th>Loại check-out</th>
                        <td><?= $data->getCheckoutTypeText() ?></td>
                    </tr>
                    <tr>
                        <th>Hình thức tham gia</th>
                        <td><?= $data->getHinhThucThamGiaText() ?></td>
                    </tr>
                    <tr>
                        <th>Mã xác nhận</th>
                        <td><?= esc($data->ma_xac_nhan) ?: '-' ?></td>
                    </tr>
                    <?php if ($data->checkout_type == 'face_id'): ?>
                    <tr>
                        <th>Xác minh khuôn mặt</th>
                        <td><?= $data->isFaceVerified() ? '<span class="badge bg-success">Đã xác minh</span>' : '<span class="badge bg-danger">Chưa xác minh</span>' ?></td>
                    </tr>
                    <?php if ($data->face_image_path): ?>
                    <tr>
                        <th>Ảnh khuôn mặt</th>
                        <td>
                            <img src="<?= base_url($data->face_image_path) ?>" alt="Ảnh khuôn mặt" class="img-thumbnail" style="max-width: 200px">
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($data->face_match_score !== null): ?>
                    <tr>
                        <th>Điểm số khớp khuôn mặt</th>
                        <td><?= number_format($data->face_match_score * 100, 2) ?>%</td>
                    </tr>
                    <?php endif; ?>
                    <?php endif; ?>
                    <tr>
                        <th>Trạng thái</th>
                        <td><?= $data->getStatusText() ?></td>
                    </tr>
                    <?php if ($data->attendance_duration_minutes): ?>
                    <tr>
                        <th>Thời gian tham dự</th>
                        <td><?= $data->getAttendanceDurationFormatted() ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($data->danh_gia): ?>
                    <tr>
                        <th>Đánh giá</th>
                        <td><?= $data->getDanhGiaStars() ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($data->noi_dung_danh_gia): ?>
                    <tr>
                        <th>Nội dung đánh giá</th>
                        <td><?= nl2br(esc($data->noi_dung_danh_gia)) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($data->feedback): ?>
                    <tr>
                        <th>Phản hồi</th>
                        <td><?= nl2br(esc($data->feedback)) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($data->location_data)): ?>
                    <tr>
                        <th>Dữ liệu vị trí</th>
                        <td><?= esc($data->location_data) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($data->device_info)): ?>
                    <tr>
                        <th>Thông tin thiết bị</th>
                        <td><?= esc($data->device_info) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($data->ip_address)): ?>
                    <tr>
                        <th>Địa chỉ IP</th>
                        <td><?= esc($data->ip_address) ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php 
                    $formattedInfo = $data->getThongTinBoSung();
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
                    <?php if (!empty($data->ghi_chu)): ?>
                    <tr>
                        <th>Ghi chú</th>
                        <td><?= nl2br(esc($data->ghi_chu)) ?></td>
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