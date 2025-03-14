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
                        <div class="stats-number"><?= isset($registration_count) ? $registration_count : 0 ?></div>
                        <p class="mb-0">Tổng số người đăng ký</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center p-3 border rounded">
                        <div class="stats-number"><?= isset($attendance_count) ? $attendance_count : 0 ?></div>
                        <p class="mb-0">Số người đã điểm danh</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card text-center p-3 border rounded">
                        <div class="stats-number"><?= max(0, $event['so_luong_tham_gia'] - (isset($registration_count) ? $registration_count : 0)) ?></div>
                        <p class="mb-0">Số chỗ còn trống</p>
                    </div>
                </div>
            </div>
            
            <!-- Thanh tiến trình -->
            <div class="progress mb-4">
                <?php 
                    $percent = isset($registration_count) && $event['so_luong_tham_gia'] > 0 
                        ? min(100, round(($registration_count / $event['so_luong_tham_gia']) * 100)) 
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
                            <option value="registered">Đã đăng ký</option>
                            <option value="attended">Đã điểm danh</option>
                            <option value="absent">Vắng mặt</option>
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
                            <th scope="col">Mã sinh viên</th>
                            <th scope="col">Ngày đăng ký</th>
                            <th scope="col">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($participants) && !empty($participants)): ?>
                            <?php foreach ($participants as $index => $participant): ?>
                            <tr>
                                <th scope="row"><?= $index + 1 ?></th>
                                <td><?= $participant['ho_ten'] ?></td>
                                <td><?= $participant['email'] ?></td>
                                <td><?= $participant['so_dien_thoai'] ?></td>
                                <td><?= $participant['ma_sinh_vien'] ?? 'N/A' ?></td>
                                <td><?= date('d/m/Y H:i', strtotime($participant['ngay_dang_ky'])) ?></td>
                                <td>
                                    <?php if ($participant['trang_thai'] == 'attended'): ?>
                                        <span class="badge bg-success">Đã điểm danh</span>
                                    <?php elseif ($participant['trang_thai'] == 'absent'): ?>
                                        <span class="badge bg-danger">Vắng mặt</span>
                                    <?php else: ?>
                                        <span class="badge bg-primary">Đã đăng ký</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Chưa có người đăng ký tham gia sự kiện này</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Phân trang -->
            <?php if (isset($pager)): ?>
            <div class="d-flex justify-content-center mt-4">
                <?= $pager->links() ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div> 