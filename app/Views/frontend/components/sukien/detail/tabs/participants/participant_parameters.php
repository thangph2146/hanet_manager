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