<?= $this->extend('nguoidung/layouts/master') ?>

<?= $this->section('content') ?>
<div class="events-list-page">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-header-overlay"></div>
        <h1 class="page-title">Danh sách sự kiện</h1>
        <p class="page-description">Khám phá và đăng ký tham gia các sự kiện đang diễn ra</p>
    </div>

    <!-- Thống kê sự kiện -->
    <div class="stats-container">
        <div class="stats-item stats-total">
            <div class="stats-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stats-info">
                <div id="total-events" class="stats-value" data-count="<?= count($events) ?>"><?= count($events) ?></div>
                <div class="stats-label">Tổng số sự kiện</div>
            </div>
        </div>
        
        <div class="stats-item stats-upcoming">
            <div class="stats-icon">
                <i class="fas fa-hourglass-start"></i>
            </div>
            <div class="stats-info">
                <div id="upcoming-events" class="stats-value" data-count="<?= count(array_filter($events, function($event) {
                    return strtotime($event['thoigian_batdau']) > time();
                })) ?>"><?= count(array_filter($events, function($event) {
                    return strtotime($event['thoigian_batdau']) > time();
                })) ?></div>
                <div class="stats-label">Sự kiện sắp diễn ra</div>
            </div>
        </div>
        
        <div class="stats-item stats-registered">
            <div class="stats-icon">
                <i class="fas fa-clipboard-check"></i>
            </div>
            <div class="stats-info">
                <div id="registered-events" class="stats-value" data-count="<?= isset($registered_count) ? $registered_count : 0 ?>"><?= isset($registered_count) ? $registered_count : 0 ?></div>
                <div class="stats-label">Sự kiện đã đăng ký</div>
            </div>
        </div>
        
        <div class="stats-item stats-attended">
            <div class="stats-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stats-info">
                <div id="attended-events" class="stats-value" data-count="<?= isset($attended_count) ? $attended_count : 0 ?>"><?= isset($attended_count) ? $attended_count : 0 ?></div>
                <div class="stats-label">Sự kiện đã tham gia</div>
            </div>
        </div>
    </div>

    <!-- Bộ lọc -->
    <div class="filter-container">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Tìm kiếm sự kiện...">
        </div>
        
        <div class="filter-options">
            <div class="filter-group">
                <label for="category-select">Danh mục</label>
                <select id="category-select">
                    <option value="all">Tất cả danh mục</option>
                    <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"><?= $category['tenloai'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="status-select">Trạng thái</label>
                <select id="status-select">
                    <option value="all">Tất cả</option>
                    <option value="upcoming">Sắp diễn ra</option>
                    <option value="ongoing">Đang diễn ra</option>
                    <option value="ended">Đã kết thúc</option>
                    <option value="registered">Đã đăng ký</option>
                    <option value="attended">Đã tham gia</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="sort-select">Sắp xếp</label>
                <select id="sort-select">
                    <option value="newest">Mới nhất</option>
                    <option value="oldest">Cũ nhất</option>
                    <option value="a-z">A-Z</option>
                    <option value="z-a">Z-A</option>
                    <option value="most-participants">Số người tham gia: Cao đến thấp</option>
                    <option value="least-participants">Số người tham gia: Thấp đến cao</option>
                </select>
            </div>
            
            <button class="btn-apply-filters"><i class="fas fa-filter"></i> Lọc</button>
            <button class="btn-reset-filters"><i class="fas fa-undo"></i> Đặt lại</button>
        </div>
    </div>

    <!-- Danh sách sự kiện -->
    <div class="events-container">
        <?php if (empty($events)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h3 class="empty-state-title">Không tìm thấy sự kiện nào</h3>
            <p class="empty-state-description">Hiện tại chưa có sự kiện nào. Vui lòng quay lại sau.</p>
        </div>
        <?php else: ?>
        <div class="events-grid">
            <?php foreach ($events as $event): 
                // Kiểm tra người dùng đã đăng ký chưa
                $isRegistered = isset($registered_events) && in_array($event['id'], $registered_events);
                // Kiểm tra người dùng đã tham gia chưa
                $isAttended = isset($attended_events) && in_array($event['id'], $attended_events);
                // Kiểm tra trạng thái sự kiện
                $eventStartTime = strtotime($event['thoigian_batdau']);
                $eventEndTime = strtotime($event['thoigian_ketthuc']);
                $currentTime = time();
                $isUpcoming = $eventStartTime > $currentTime;
                $isOngoing = $eventStartTime <= $currentTime && $eventEndTime >= $currentTime;
                $isEnded = $eventEndTime < $currentTime;
                $isFull = $event['so_luong_dang_ky'] >= $event['so_luong_toi_da'];
                // Tính phần trăm đăng ký
                $registerPercentage = ($event['so_luong_toi_da'] > 0) ? 
                    min(100, round(($event['so_luong_dang_ky'] / $event['so_luong_toi_da']) * 100)) : 0;
            ?>
            <div class="event-card" 
                data-date="<?= $event['thoigian_batdau'] ?>" 
                data-end-date="<?= $event['thoigian_ketthuc'] ?>"
                data-category="<?= $event['id_loai_su_kien'] ?>"
                data-views="<?= $event['luot_xem'] ?>"
                data-capacity="<?= $event['so_luong_toi_da'] ?>"
                data-registered-count="<?= $event['so_luong_dang_ky'] ?>"
                data-participants="<?= $event['so_luong_dang_ky'] ?>"
                <?= $isRegistered ? 'data-registered="true"' : '' ?>
                <?= $isAttended ? 'data-attended="true"' : '' ?>>
                
                <div class="event-image">
                    <img src="<?= base_url($event['hinh_anh'] ?: 'assets/img/default-event.jpg') ?>" alt="<?= $event['ten_su_kien'] ?>">
                    
                    <div class="event-countdown">
                        <i class="fas fa-hourglass-half"></i>
                        <?php if ($isEnded): ?>
                            Đã kết thúc
                        <?php elseif ($isOngoing): ?>
                            Đang diễn ra
                        <?php else: ?>
                            <?php 
                                $diff = $eventStartTime - $currentTime;
                                $days = floor($diff / (60 * 60 * 24));
                                $hours = floor(($diff % (60 * 60 * 24)) / (60 * 60));
                                
                                if ($days > 0) {
                                    echo "Còn {$days} ngày";
                                } else {
                                    echo "Còn {$hours} giờ";
                                }
                            ?>
                        <?php endif; ?>
                    </div>
                    
                    <div class="event-date-badge">
                        <div class="event-day"><?= date('d', $eventStartTime) ?></div>
                        <div class="event-month"><?= date('M', $eventStartTime) ?></div>
                        <div class="event-year"><?= date('Y', $eventStartTime) ?></div>
                    </div>
                    
                    <?php if ($isRegistered || $isAttended): ?>
                    <div class="event-registered-badge <?= $isAttended ? 'attended' : '' ?>">
                        <i class="fas <?= $isAttended ? 'fa-user-check' : 'fa-clipboard-check' ?>"></i>
                        <?= $isAttended ? 'Đã tham gia' : 'Đã đăng ký' ?>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="event-content">
                    <div class="event-meta">
                        <div class="event-category">
                            <?= isset($categories_map[$event['id_loai_su_kien']]) ? $categories_map[$event['id_loai_su_kien']]['tenloai'] : 'Chưa phân loại' ?>
                        </div>
                        
                        <div class="event-views">
                            <i class="fas fa-eye"></i> <?= number_format($event['luot_xem']) ?>
                        </div>
                    </div>
                    
                    <h3 class="event-title"><?= $event['ten_su_kien'] ?></h3>
                    
                    <div class="event-details">
                        <div class="event-time">
                            <i class="far fa-clock"></i>
                            <span><?= date('d/m/Y H:i', $eventStartTime) ?> - <?= date('d/m/Y H:i', $eventEndTime) ?></span>
                        </div>
                        
                        <div class="event-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= $event['dia_diem'] ?: 'Chưa cập nhật địa điểm' ?></span>
                        </div>
                        
                        <div class="event-organizer">
                            <i class="fas fa-user-tie"></i>
                            <span><?= $event['nguoi_to_chuc'] ?: 'Chưa cập nhật người tổ chức' ?></span>
                        </div>
                    </div>
                    
                    <div class="event-description">
                        <?= character_limiter(strip_tags($event['mo_ta']), 150) ?>
                    </div>
                    
                    <div class="event-stats">
                        <div class="event-stat">
                            <i class="fas fa-users"></i>
                            <?= number_format($event['so_luong_dang_ky']) ?> đăng ký
                        </div>
                        
                        <?php if (!empty($event['so_luong_toi_da'])): ?>
                        <div class="event-stat">
                            <i class="fas fa-user-plus"></i>
                            Còn <?= max(0, $event['so_luong_toi_da'] - $event['so_luong_dang_ky']) ?> chỗ
                        </div>
                        
                        <div class="event-stat capacity">
                            <i class="fas fa-chart-pie"></i>
                            <?= $registerPercentage ?>% đã đăng ký
                            <div class="capacity-bar-container">
                                <div class="capacity-bar" style="width: <?= $registerPercentage ?>%;"></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="event-actions">
                        <a href="<?= base_url('events/detail/' . $event['id']) ?>" class="btn btn-details">
                            <i class="fas fa-info-circle"></i> Chi tiết
                        </a>
                        
                        <?php if (session()->has('isLoggedIn')): ?>
                            <?php if ($isAttended): ?>
                                <a href="#" class="btn btn-attended">
                                    <i class="fas fa-user-check"></i> Đã tham gia
                                </a>
                            <?php elseif ($isRegistered): ?>
                                <a href="<?= base_url('events/cancel-registration/' . $event['id']) ?>" 
                                   class="btn btn-cancel" 
                                   onclick="return confirm('Bạn có chắc chắn muốn hủy đăng ký sự kiện này?')">
                                    <i class="fas fa-times-circle"></i> Hủy đăng ký
                                </a>
                            <?php elseif ($isEnded): ?>
                                <a href="#" class="btn btn-disabled">
                                    <i class="fas fa-hourglass-end"></i> Đã kết thúc
                                </a>
                            <?php elseif ($isFull): ?>
                                <a href="#" class="btn btn-full">
                                    <i class="fas fa-users-slash"></i> Đã đủ chỗ
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('events/register/' . $event['id']) ?>" class="btn btn-register">
                                    <i class="fas fa-clipboard-check"></i> Đăng ký
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="<?= base_url('login?redirect=' . current_url()) ?>" class="btn btn-register">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập để đăng ký
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- Phân trang -->
    <?= isset($pager) ? $pager->links('default', 'custom_pagination') : '' ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script src="<?= base_url('assets/js/nguoidung/pages/eventslist.js') ?>"></script>
<?= $this->endSection() ?> 