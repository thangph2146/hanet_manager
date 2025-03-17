<?= $this->extend('app\Views\students\layouts\layout') ?>

<?= $this->section('content') ?>
<div class="events-container">
    <div class="events-header">
        <h2 class="events-title">Danh sách sự kiện</h2>
        <p class="events-subtitle">Khám phá và đăng ký tham gia các sự kiện sắp diễn ra</p>
    </div>
    
    <div class="events-filters">
        <form id="event-filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Tìm kiếm</label>
                    <input type="text" class="filter-input" placeholder="Nhập tên sự kiện..." id="searchEvent">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Loại sự kiện</label>
                    <select class="filter-select" id="eventCategory">
                        <option value="">Tất cả loại</option>
                        <?php foreach ($event_types as $type): ?>
                        <option value="<?= $type['id'] ?>"><?= $type['loai_su_kien'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">Trạng thái</label>
                    <select class="filter-select" id="eventStatus">
                        <option value="">Tất cả trạng thái</option>
                        <option value="upcoming">Sắp diễn ra</option>
                        <option value="ongoing">Đang diễn ra</option>
                        <option value="completed">Đã kết thúc</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Thời gian</label>
                    <input type="date" class="filter-input" id="eventDate">
                </div>
            </div>
            
            <div class="filter-buttons">
                <button type="button" class="btn-filter btn-reset">Xóa bộ lọc</button>
                <button type="submit" class="btn-filter btn-apply">Áp dụng</button>
            </div>
        </form>
    </div>
    
    <div class="tab-container">
        <div class="tab-buttons">
            <button class="tab-button active" data-tab="events-tab">Danh sách sự kiện</button>
            <button class="tab-button" data-tab="registered-tab">Sự kiện đã đăng ký</button>
        </div>
        
        <div class="tab-content active" id="events-tab">
            <div class="events-list">
                <?php if (empty($events)): ?>
                <div class="events-empty">
                    <div class="events-empty-icon">
                        <i class="fas fa-calendar-xmark"></i>
                    </div>
                    <h3 class="events-empty-title">Không có sự kiện nào</h3>
                    <p class="events-empty-text">Hiện tại không có sự kiện nào phù hợp với tìm kiếm của bạn.</p>
                </div>
                <?php else: ?>
                    <?php foreach ($events as $event): ?>
                    <div class="event-card">
                        <div class="event-image">
                            <img src="<?= base_url($event['hinh_anh'] ?? 'assets/img/event-default.jpg') ?>" alt="<?= $event['ten_su_kien'] ?>">
                            <div class="event-category"><?= $event['loai_su_kien'] ?></div>
                            <div class="event-status <?= $event['status'] ?>">
                                <?php if ($event['status'] == 'upcoming'): ?>
                                    Sắp diễn ra
                                <?php elseif ($event['status'] == 'ongoing'): ?>
                                    Đang diễn ra
                                <?php else: ?>
                                    Đã kết thúc
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="event-content">
                            <div class="event-header">
                                <h3 class="event-title"><?= $event['ten_su_kien'] ?></h3>
                                <div class="event-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?= date('d/m/Y - H:i', strtotime($event['ngay_to_chuc'])) ?>
                                </div>
                            </div>
                            <div class="event-description">
                                <?= $event['mo_ta_ngan'] ?>
                            </div>
                            <div class="event-footer">
                                <div class="event-meta">
                                    <div class="event-meta-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?= $event['dia_diem'] ?>
                                    </div>
                                    <div class="event-meta-item">
                                        <i class="fas fa-users"></i>
                                        <?= $event['so_nguoi_tham_gia'] ?? 0 ?>
                                    </div>
                                </div>
                                <div class="event-action">
                                    <?php if ($event['is_registered']): ?>
                                        <button class="btn btn-success" disabled>Đã đăng ký</button>
                                    <?php elseif ($event['status'] != 'completed'): ?>
                                        <button class="btn btn-primary event-register-btn" data-event-id="<?= $event['id'] ?>">Đăng ký</button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary" disabled>Đã kết thúc</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($pager) && $pager->getPageCount() > 1): ?>
            <div class="events-pagination">
                <?= $pager->links() ?>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="tab-content" id="registered-tab">
            <div class="registered-header">
                <h3 class="registered-title">Sự kiện đã đăng ký</h3>
            </div>
            
            <div class="registered-list">
                <?php if (empty($registered_events)): ?>
                <div class="events-empty">
                    <div class="events-empty-icon">
                        <i class="fas fa-calendar-xmark"></i>
                    </div>
                    <h3 class="events-empty-title">Chưa có sự kiện đăng ký</h3>
                    <p class="events-empty-text">Bạn chưa đăng ký sự kiện nào, hãy khám phá và đăng ký các sự kiện để tham gia.</p>
                    <a href="#events-tab" class="events-empty-btn">Khám phá sự kiện</a>
                </div>
                <?php else: ?>
                    <?php foreach ($registered_events as $event): ?>
                    <div class="registered-card">
                        <div class="registered-status <?= $event['status'] ?>">
                            <?php if ($event['status'] == 'registered'): ?>
                                Đã đăng ký
                            <?php elseif ($event['status'] == 'checked-in'): ?>
                                Đã check-in
                            <?php elseif ($event['status'] == 'checked-out'): ?>
                                Đã check-out
                            <?php elseif ($event['status'] == 'completed'): ?>
                                Hoàn thành
                            <?php else: ?>
                                Vắng mặt
                            <?php endif; ?>
                        </div>
                        <div class="registered-info">
                            <div class="registered-image">
                                <img src="<?= base_url($event['hinh_anh'] ?? 'assets/img/event-default.jpg') ?>" alt="<?= $event['ten_su_kien'] ?>">
                            </div>
                            <div class="registered-content">
                                <h4 class="registered-event-title"><?= $event['ten_su_kien'] ?></h4>
                                <div class="registered-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?= date('d/m/Y - H:i', strtotime($event['ngay_to_chuc'])) ?>
                                </div>
                                <div class="registered-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= $event['dia_diem'] ?>
                                </div>
                            </div>
                        </div>
                        <div class="registered-actions">
                            <button class="registered-qr-btn" 
                                    data-event-id="<?= $event['id'] ?>"
                                    data-event-title="<?= $event['ten_su_kien'] ?>"
                                    data-event-date="<?= date('d/m/Y - H:i', strtotime($event['ngay_to_chuc'])) ?>"
                                    data-event-location="<?= $event['dia_diem'] ?>">
                                <i class="fas fa-qrcode"></i> Mã QR
                            </button>
                            
                            <?php if ($event['status'] == 'registered'): ?>
                            <button class="registered-cancel-btn" 
                                    data-event-id="<?= $event['id'] ?>"
                                    data-registration-id="<?= $event['registration_id'] ?>">
                                <i class="fas fa-times"></i> Hủy đăng ký
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="qr-modal">
    <div class="qr-modal-content">
        <div class="qr-modal-header">
            <h3 class="qr-modal-title">Mã QR tham gia sự kiện</h3>
            <div class="qr-modal-close">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <div class="qr-modal-body">
            <div class="qr-code">
                <!-- QR code sẽ được thêm qua JavaScript -->
            </div>
            <div class="qr-event-info">
                <h4 class="qr-event-title"></h4>
                <p class="qr-event-date"></p>
                <p class="qr-event-location"></p>
            </div>
            <button class="qr-download-btn">
                <i class="fas fa-download"></i> Tải mã QR
            </button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>