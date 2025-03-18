<div class="mb-4">
                <h5>Tình trạng đăng ký</h5>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <!-- Hiển thị số liệu đăng ký -->
                        <?php 
                        // Sử dụng biến registrationCount từ controller
                        $registration_total = isset($registrationCount) ? $registrationCount : 0;
                        $max_slots = $event['so_luong_tham_gia'];
                        $slots_left = max(0, $max_slots - $registration_total);
                        $percent = $max_slots > 0 ? min(100, round(($registration_total / $max_slots) * 100)) : 0;
                        $progressClass = $percent >= 80 ? 'bg-danger' : ($percent >= 50 ? 'bg-warning' : 'bg-success');
                        ?>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <span class="d-block fs-4 fw-bold text-primary"><?= $registration_total ?></span>
                                <span class="text-muted small">Đã đăng ký</span>
                            </div>
                            <div class="text-center">
                                <span class="d-block fs-5 badge bg-<?= $progressClass ?> rounded-pill"><?= $percent ?>%</span>
                                <span class="text-muted small">Đã lấp đầy</span>
                            </div>
                            <div class="text-end">
                                <span class="d-block fs-4 fw-bold text-success"><?= $slots_left ?></span>
                                <span class="text-muted small">Chỗ còn trống</span>
                            </div>
                        </div>
                        
                        <!-- Progress bar -->
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated <?= $progressClass ?>" 
                                role="progressbar" style="width: <?= $percent ?>%" 
                                aria-valuenow="<?= $registration_total ?>" 
                                aria-valuemin="0" 
                                aria-valuemax="<?= $max_slots ?>">
                            </div>
                        </div>
                        
                        <!-- Thông báo trạng thái -->
                        <div class="mt-2 text-center small">
                            <?php if ($percent >= 90): ?>
                                <div class="text-danger">
                                    <i class="lni lni-alarm-clock me-1"></i> Sắp hết chỗ, hãy đăng ký ngay!
                                </div>
                            <?php elseif ($percent >= 70): ?>
                                <div class="text-warning">
                                    <i class="lni lni-hourglass me-1"></i> Số lượng chỗ còn lại đang giảm dần
                                </div>
                            <?php else: ?>
                                <div class="text-success">
                                    <i class="lni lni-checkmark-circle me-1"></i> Còn nhiều chỗ trống
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>