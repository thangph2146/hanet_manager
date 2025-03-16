<?php
/**
 * Component hiển thị tab danh sách người tham gia sự kiện
 */
?>

<div class="tab-pane fade" id="event-participants" role="tabpanel" aria-labelledby="event-participants-tab">
    <div class="card shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">Danh sách người tham gia</h3>
            
            <!-- Thống kê -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card text-center p-3 border rounded">
                        <div class="stats-number"><?= isset($registrationCount) ? $registrationCount : 0 ?></div>
                        <p class="mb-0">Tổng số người đăng ký</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center p-3 border rounded">
                        <div class="stats-number"><?= isset($attendedCount) ? $attendedCount : 0 ?></div>
                        <p class="mb-0">Số người đã điểm danh</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center p-3 border rounded">
                        <div class="stats-number"><?= max(0, $event['so_luong_tham_gia'] - (isset($registrationCount) ? $registrationCount : 0)) ?></div>
                        <p class="mb-0">Số chỗ còn trống</p>
                    </div>
                </div>
            </div>
            
            <!-- Thanh tiến trình -->
            <div class="progress mb-4">
                <?php 
                    $percent = isset($registrationCount) && $event['so_luong_tham_gia'] > 0 
                        ? min(100, round(($registrationCount / $event['so_luong_tham_gia']) * 100)) 
                        : 0;
                ?>
                <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $percent ?>%" 
                    aria-valuenow="<?= $percent ?>" aria-valuemin="0" aria-valuemax="100">
                    <?= $percent ?>%
                </div>
            </div>
            
            <!-- Bộ lọc -->
            <div class="mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search-participant" placeholder="Tìm kiếm...">
                            <button class="btn btn-outline-secondary" type="button"><i class="lni lni-search-alt"></i></button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select class="form-select" id="filter-status">
                            <option value="all">Tất cả trạng thái</option>
                            <option value="1">Đã điểm danh</option>
                            <option value="0">Chưa điểm danh</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Bảng danh sách -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Họ và tên</th>
                            <th scope="col">Email</th>
                            <th scope="col">Số điện thoại</th>
                            <th scope="col">Mã SV/GV</th>
                            <th scope="col">Khoa/Lớp</th>
                            <th scope="col">Ngày đăng ký</th>
                            <th scope="col">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($registrations) && !empty($registrations)): ?>
                            <?php foreach ($registrations as $index => $participant): ?>
                            <tr>
                                <th scope="row"><?= $index + 1 ?></th>
                                <td><?= $participant['ho_ten'] ?></td>
                                <td><?= $participant['email'] ?></td>
                                <td><?= $participant['so_dien_thoai'] ?></td>
                                <td><?= $participant['ma_sv'] ?? 'N/A' ?></td>
                                <td><?= $participant['khoa'] . ($participant['lop'] ? ' / ' . $participant['lop'] : '') ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($participant['ngay_dang_ky'])) ?></td>
                                <td>
                                    <?php if ($participant['da_tham_gia'] == 1): ?>
                                        <span class="badge bg-success">Đã điểm danh</span>
                                    <?php elseif ($participant['trang_thai'] == 0): ?>
                                        <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Đã xác nhận</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center">Chưa có người đăng ký tham gia sự kiện này</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Thông tin thêm -->
            <div class="alert alert-info mt-4">
                <h5><i class="lni lni-information"></i> Lưu ý:</h5>
                <ul class="mb-0">
                    <li>Danh sách cập nhật tự động sau mỗi 5 phút</li>
                    <li>Trạng thái điểm danh được cập nhật tại sự kiện</li>
                    <li>Vui lòng liên hệ BTC nếu có thay đổi thông tin đăng ký</li>
                </ul>
            </div>
        </div>
    </div>
</div> 