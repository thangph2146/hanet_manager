<?php
$upcoming_events = [
    [
        'image' => 'event1.jpg',
        'title' => 'Workshop Kỹ năng mềm',
        'date' => '2024-03-25 09:00:00',
        'location' => 'Phòng A1.01',
        'description' => 'Học cách giao tiếp hiệu quả và làm việc nhóm',
        'category' => 'Workshop',
        'is_popular' => true
    ],
    [
        'image' => 'event2.jpg', 
        'title' => 'Hội thảo Công nghệ 2024',
        'date' => '2024-04-01 14:00:00',
        'location' => 'Hội trường lớn',
        'description' => 'Cập nhật xu hướng công nghệ mới nhất',
        'category' => 'Hội thảo',
        'is_popular' => false
    ],
    [
        'image' => 'event3.jpg',
        'title' => 'Ngày hội Việc làm IT',
        'date' => '2024-04-15 08:00:00',
        'location' => 'Sảnh A',
        'description' => 'Cơ hội việc làm từ 50+ công ty công nghệ',
        'category' => 'Career Fair',
        'is_popular' => true
    ]
];
?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Sự kiện sắp diễn ra</h5>
                <div class="card-actions">
                    <button class="card-action-btn" data-bs-toggle="dropdown">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">Xem tất cả</a>
                        <a class="dropdown-item" href="#">Lọc sự kiện</a>
                        <a class="dropdown-item" href="#">Xuất lịch</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach($upcoming_events as $index => $event): 
                        $date = new DateTime($event['date']);
                    ?>
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="event-card <?= $index === 0 ? 'featured-event' : '' ?>">
                            <div class="event-image">
                                <?php if($event['is_popular']): ?>
                                <span class="event-badge popular">Popular</span>
                                <?php endif; ?>
                                <img src="<?= base_url('assets/images/events/' . $event['image']) ?>" alt="<?= $event['title'] ?>">
                            </div>
                            <div class="card-body">
                                <div class="event-meta">
                                    <span class="event-date">
                                        <i class="far fa-calendar"></i>
                                        <?= $date->format('d/m/Y H:i') ?>
                                    </span>
                                    <?php if($event['location']): ?>
                                    <span class="event-location">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <?= $event['location'] ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <h5 class="card-title mb-2"><?= $event['title'] ?></h5>
                                <p class="card-text text-secondary">
                                    <?= strlen($event['description']) > 100 ? substr($event['description'], 0, 100) . '...' : $event['description'] ?>
                                </p>
                                
                                <div class="event-footer">
                                    <span class="event-category">
                                        <i class="fas fa-tag me-1"></i>
                                        <?= $event['category'] ?>
                                    </span>
                                    <a href="#" class="btn btn-primary btn-sm">Chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div> 