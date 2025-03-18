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
            <div class="table-responsive-container">
                <!-- Tab điều hướng cho mobile -->
                <div class="d-md-none mb-3 overflow-auto">
                    <div class="nav nav-tabs mobile-tabs" role="tablist">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info-content" type="button" role="tab" aria-selected="true">Thông tin</button>
                        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-content" type="button" role="tab" aria-selected="false">Liên hệ</button>
                        <button class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status-content" type="button" role="tab" aria-selected="false">Trạng thái</button>
                    </div>
                </div>

                <!-- Hiển thị dạng card trên mobile -->
                <div class="d-md-none tab-content">
                    <div class="tab-pane fade show active" id="info-content" role="tabpanel">
                        <?php if (isset($registrations) && !empty($registrations)): ?>
                            <?php foreach ($registrations as $index => $participant): ?>
                                <div class="card mb-3 participant-card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?= $participant['ho_ten'] ?></h6>
                                        <?php if ($participant['da_tham_gia'] == 1): ?>
                                            <span class="badge bg-success">Đã điểm danh</span>
                                        <?php elseif ($participant['trang_thai'] == 0): ?>
                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Đã xác nhận</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">Mã SV/GV:</small>
                                            <div><?= $participant['ma_sv'] ?? 'N/A' ?></div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Khoa/Lớp:</small>
                                            <div><?= $participant['khoa'] . ($participant['lop'] ? ' / ' . $participant['lop'] : '') ?></div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Ngày đăng ký:</small>
                                            <div><?= date('d/m/Y H:i', strtotime($participant['ngay_dang_ky'])) ?></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Chưa có người đăng ký tham gia sự kiện này
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="contact-content" role="tabpanel">
                        <?php if (isset($registrations) && !empty($registrations)): ?>
                            <?php foreach ($registrations as $index => $participant): ?>
                                <div class="card mb-3 participant-card">
                                    <div class="card-header">
                                        <h6 class="mb-0"><?= $participant['ho_ten'] ?></h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">Email:</small>
                                            <div><a href="mailto:<?= $participant['email'] ?>"><?= $participant['email'] ?></a></div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Số điện thoại:</small>
                                            <div><a href="tel:<?= $participant['so_dien_thoai'] ?>"><?= $participant['so_dien_thoai'] ?></a></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Chưa có người đăng ký tham gia sự kiện này
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane fade" id="status-content" role="tabpanel">
                        <?php if (isset($registrations) && !empty($registrations)): ?>
                            <?php foreach ($registrations as $index => $participant): ?>
                                <div class="card mb-3 participant-card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0"><?= $participant['ho_ten'] ?></h6>
                                        <?php if ($participant['da_tham_gia'] == 1): ?>
                                            <span class="badge bg-success">Đã điểm danh</span>
                                        <?php elseif ($participant['trang_thai'] == 0): ?>
                                            <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Đã xác nhận</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2">
                                            <small class="text-muted">Ngày đăng ký:</small>
                                            <div><?= date('d/m/Y H:i', strtotime($participant['ngay_dang_ky'])) ?></div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Trạng thái:</small>
                                            <div>
                                                <?php if ($participant['trang_thai'] == 1): ?>
                                                    Đã xác nhận
                                                    <?php if ($participant['da_tham_gia'] == 1): ?>
                                                        | Đã điểm danh
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    Chờ xác nhận
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-info">
                                Chưa có người đăng ký tham gia sự kiện này
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Bảng đầy đủ cho desktop -->
                <div class="table-responsive d-none d-md-block">
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
            
            <!-- CSS cho tối ưu mobile -->
            <style>
                .mobile-tabs {
                    display: flex;
                    white-space: nowrap;
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                }
                .mobile-tabs .nav-link {
                    flex-shrink: 0;
                    min-width: 100px;
                    text-align: center;
                }
                .participant-card {
                    transition: transform 0.2s;
                }
                .participant-card:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }
                @media (max-width: 767.98px) {
                    .table-responsive-container {
                        padding: 0;
                    }
                }
                /* CSS cho desktop table */
                .table-responsive {
                    overflow-x: auto;
                    -webkit-overflow-scrolling: touch;
                    max-width: 100%;
                    margin-bottom: 1rem;
                }
                .table {
                    width: 100%;
                    min-width: 800px; /* Đảm bảo bảng có độ rộng tối thiểu */
                    border-collapse: collapse;
                }
                /* Tăng padding cho các cell trên desktop */
                @media (min-width: 768px) {
                    .table th,
                    .table td {
                        padding: 0.75rem;
                        white-space: nowrap;
                    }
                }
            </style>
        </div>
    </div>
</div> 