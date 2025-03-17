<?= $this->extend('app\Modules\layouts\students\dashboard') ?>

<?= $this->section('content') ?>
<div class="dashboard-container">
    <div class="dashboard-header">
        <h2 class="dashboard-title">Dashboard</h2>
        <p class="dashboard-subtitle">Xin chào, <?= session()->get('name') ?? 'Sinh viên' ?>!</p>
        <div class="dashboard-welcome">Chào mừng bạn quay trở lại</div>
        <div class="dashboard-date"><?= date('l, d F Y') ?></div>
    </div>
    
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['registered_events'] ?? 0 ?></div>
                <div class="stat-label">Sự kiện đã đăng ký</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['completed_events'] ?? 0 ?></div>
                <div class="stat-label">Sự kiện đã hoàn thành</div>
                <?php if (isset($stats['completed_percentage'])): ?>
                <div class="stat-change up">
                    <i class="fas fa-arrow-up"></i> <?= $stats['completed_percentage'] ?>%
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['upcoming_events'] ?? 0 ?></div>
                <div class="stat-label">Sự kiện sắp diễn ra</div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['total_points'] ?? 0 ?></div>
                <div class="stat-label">Điểm hoạt động</div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-two-columns">
        <div class="column-left">
            <!-- Upcoming Events -->
            <div class="upcoming-events">
                <div class="section-header">
                    <h3 class="section-title">Sự kiện sắp diễn ra</h3>
                    <a href="<?= base_url('students/events') ?>" class="section-link">
                        Xem tất cả <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="events-list">
                    <?php if (empty($upcoming_events) || !is_array($upcoming_events)): ?>
                    <div class="event-item" style="text-align: center; padding: 30px 15px;">
                        <div style="color: var(--text-light);">
                            <i class="fas fa-calendar-xmark" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>Không có sự kiện sắp diễn ra</p>
                        </div>
                    </div>
                    <?php else: ?>
                        <?php foreach ($upcoming_events as $event): ?>
                        <div class="event-item">
                            <div class="event-date">
                                <div class="event-day"><?= date('d', strtotime($event['ngay_to_chuc'])) ?></div>
                                <div class="event-month"><?= date('M', strtotime($event['ngay_to_chuc'])) ?></div>
                            </div>
                            <div class="event-details">
                                <div class="event-title"><?= $event['ten_su_kien'] ?></div>
                                <div class="event-info">
                                    <div class="event-time">
                                        <i class="far fa-clock"></i>
                                        <?= date('H:i', strtotime($event['ngay_to_chuc'])) ?>
                                    </div>
                                    <div class="event-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?= $event['dia_diem'] ?>
                                    </div>
                                </div>
                                <div class="event-actions">
                                    <a href="<?= base_url('students/events/detail/' . $event['id']) ?>" class="event-btn">
                                        <i class="fas fa-info-circle"></i> Chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Announcements -->
            <div class="announcements">
                <div class="section-header">
                    <h3 class="section-title">Thông báo gần đây</h3>
                    <a href="#" class="section-link">
                        Xem tất cả <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="announcement-list">
                    <?php if (empty($announcements)): ?>
                    <div class="announcement-item" style="text-align: center; padding: 30px 15px;">
                        <div style="color: var(--text-light);">
                            <i class="fas fa-bell-slash" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>Không có thông báo mới</p>
                        </div>
                    </div>
                    <?php else: ?>
                        <?php foreach ($announcements as $announcement): ?>
                        <div class="announcement-item">
                            <div class="announcement-header">
                                <div class="announcement-title"><?= $announcement['title'] ?></div>
                                <div class="announcement-date"><?= date('d/m/Y', strtotime($announcement['created_at'])) ?></div>
                            </div>
                            <div class="announcement-content">
                                <?= $announcement['content'] ?>
                            </div>
                            <div class="announcement-footer">
                                <div class="announcement-author">
                                    <img src="<?= base_url($announcement['author_avatar'] ?? 'assets/img/avatar-default.png') ?>" alt="Avatar">
                                    <?= $announcement['author_name'] ?>
                                </div>
                                <div class="announcement-actions">
                                    <a href="#">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="column-right">
            <!-- Calendar -->
            <div class="calendar-container">
                <div class="calendar-header">
                    <div class="calendar-title">Tháng <?= date('m/Y') ?></div>
                    <div class="calendar-nav">
                        <button class="calendar-nav-btn prev-month">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="calendar-nav-btn next-month">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                
                <div class="calendar-grid">
                    <div class="calendar-weekdays">
                        <div class="calendar-weekday">CN</div>
                        <div class="calendar-weekday">T2</div>
                        <div class="calendar-weekday">T3</div>
                        <div class="calendar-weekday">T4</div>
                        <div class="calendar-weekday">T5</div>
                        <div class="calendar-weekday">T6</div>
                        <div class="calendar-weekday">T7</div>
                    </div>
                    
                    <div class="calendar-days" id="calendarDays">
                        <!-- Calendar days will be generated by JavaScript -->
                    </div>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="quick-links">
                <div class="section-header">
                    <h3 class="section-title">Truy cập nhanh</h3>
                </div>
                
                <div class="links-grid">
                    <a href="<?= base_url('students/events') ?>" class="link-card">
                        <div class="link-icon primary">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="link-title">Sự kiện</div>
                    </a>
                    
                    <a href="<?= base_url('students/profile') ?>" class="link-card">
                        <div class="link-icon success">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="link-title">Hồ sơ</div>
                    </a>
                    
                    <a href="<?= base_url('students/certificates') ?>" class="link-card">
                        <div class="link-icon warning">
                            <i class="fas fa-certificate"></i>
                        </div>
                        <div class="link-title">Chứng chỉ</div>
                    </a>
                    
                    <a href="<?= base_url('students/reports') ?>" class="link-card">
                        <div class="link-icon danger">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="link-title">Báo cáo</div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Generate calendar
    generateCalendar();
    
    // Handle month navigation
    document.querySelector('.prev-month').addEventListener('click', function() {
        changeMonth(-1);
    });
    
    document.querySelector('.next-month').addEventListener('click', function() {
        changeMonth(1);
    });
    
    // Current month and year
    let currentMonth = new Date().getMonth();
    let currentYear = new Date().getFullYear();
    
    // Calendar days with events
    const eventDays = <?= json_encode($event_days ?? []) ?>;
    
    // Change month and regenerate calendar
    function changeMonth(delta) {
        currentMonth += delta;
        
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        } else if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        
        document.querySelector('.calendar-title').textContent = `Tháng ${currentMonth + 1}/${currentYear}`;
        generateCalendar();
    }
    
    // Generate calendar days
    function generateCalendar() {
        const firstDay = new Date(currentYear, currentMonth, 1);
        const lastDay = new Date(currentYear, currentMonth + 1, 0);
        const calendarDays = document.getElementById('calendarDays');
        
        // Clear previous days
        calendarDays.innerHTML = '';
        
        // Add days from previous month
        const daysFromPrevMonth = firstDay.getDay();
        const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
        
        for (let i = 0; i < daysFromPrevMonth; i++) {
            const dayNumber = prevMonthLastDay - daysFromPrevMonth + i + 1;
            const dayEl = createDayElement(dayNumber, 'other-month');
            calendarDays.appendChild(dayEl);
        }
        
        // Add days from current month
        const today = new Date();
        
        for (let i = 1; i <= lastDay.getDate(); i++) {
            let classes = [];
            
            // Check if today
            if (today.getDate() === i && today.getMonth() === currentMonth && today.getFullYear() === currentYear) {
                classes.push('today');
            }
            
            // Check if day has event
            const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            if (eventDays.includes(dateStr)) {
                classes.push('has-event');
            }
            
            const dayEl = createDayElement(i, classes.join(' '));
            calendarDays.appendChild(dayEl);
        }
        
        // Add days from next month
        const remainingDays = 42 - (daysFromPrevMonth + lastDay.getDate()); // 6 rows x 7 days
        
        for (let i = 1; i <= remainingDays; i++) {
            const dayEl = createDayElement(i, 'other-month');
            calendarDays.appendChild(dayEl);
        }
    }
    
    // Create day element
    function createDayElement(day, classes) {
        const dayEl = document.createElement('div');
        dayEl.className = `calendar-day ${classes}`;
        dayEl.textContent = day;
        
        // Add click event
        dayEl.addEventListener('click', function() {
            // Remove selected class from all days
            document.querySelectorAll('.calendar-day').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selected class to clicked day
            this.classList.add('selected');
            
            // If day has event, show event list
            if (this.classList.contains('has-event')) {
                // Handle click on event day
                // You can show event details or navigate to events page
            }
        });
        
        return dayEl;
    }
});
</script>
<?= $this->endSection() ?> 