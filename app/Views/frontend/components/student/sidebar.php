<?php
$current_url = current_url();

$menu_items = [
    'main' => [
        [
            'icon' => 'tachometer-alt',
            'text' => 'Bảng điều khiển',
            'url' => 'student/dashboard',
            'badge' => '5',
            'submenu' => [
                [
                    'text' => 'Phân tích',
                    'url' => 'student/dashboard/analytics',
                    'badge' => 'New'
                ],
                [
                    'text' => 'Học tập',
                    'url' => 'student/dashboard/learning'
                ],
                [
                    'text' => 'Thành tích',
                    'url' => 'student/dashboard/achievements',
                    'pro' => true
                ]
            ]
        ]
    ],
    'apps' => [
        [
            'icon' => 'envelope',
            'text' => 'Email',
            'url' => 'student/email',
            'pro' => true,
            'submenu' => [
                [
                    'text' => 'Hộp thư đến',
                    'url' => 'student/email/inbox',
                    'badge' => '3'
                ],
                [
                    'text' => 'Soạn thư',
                    'url' => 'student/email/compose'
                ],
                [
                    'text' => 'Cài đặt email',
                    'url' => 'student/email/settings'
                ]
            ]
        ],
        [
            'icon' => 'comments',
            'text' => 'Trò chuyện',
            'url' => 'student/chat',
            'pro' => true
        ],
        [
            'icon' => 'calendar-alt',
            'text' => 'Lịch',
            'url' => 'student/calendar',
            'pro' => true
        ]
    ],
    'interface' => [
        [
            'icon' => 'layer-group',
            'text' => 'Giao diện',
            'url' => 'student/ui',
            'submenu' => [
                [
                    'text' => 'Typography',
                    'url' => 'student/ui/typography'
                ],
                [
                    'text' => 'Colors',
                    'url' => 'student/ui/colors'
                ],
                [
                    'text' => 'Icons',
                    'url' => 'student/ui/icons'
                ]
            ]
        ],
        [
            'icon' => 'puzzle-piece',
            'text' => 'Components',
            'url' => 'student/components',
            'submenu' => [
                [
                    'text' => 'Alerts',
                    'url' => 'student/components/alerts'
                ],
                [
                    'text' => 'Buttons',
                    'url' => 'student/components/buttons'
                ],
                [
                    'text' => 'Cards',
                    'url' => 'student/components/cards',
                    'pro' => true
                ]
            ]
        ]
    ]
];

function renderMenuItem($item, $current_url) {
    $isActive = strpos($current_url, $item['url']) !== false;
    $hasSubmenu = isset($item['submenu']);
    ?>
    <li class="sidebar-menu-item">
        <a href="<?= $hasSubmenu ? '#' : base_url($item['url']) ?>" 
           class="sidebar-link <?= $isActive ? 'active' : '' ?> <?= $hasSubmenu ? 'has-submenu' : '' ?>"
           <?= $hasSubmenu ? 'data-bs-toggle="collapse" data-bs-target="#submenu-'.str_replace('/', '-', $item['url']).'"' : '' ?>>
            <div class="menu-icon">
                <i class="fas fa-<?= $item['icon'] ?>"></i>
            </div>
            <span><?= $item['text'] ?></span>
            <?php if(isset($item['badge'])): ?>
                <span class="badge-pro"><?= $item['badge'] ?></span>
            <?php endif; ?>
            <?php if(isset($item['pro']) && $item['pro']): ?>
                <span class="badge-pro">Pro</span>
            <?php endif; ?>
            <?php if($hasSubmenu): ?>
                <i class="fas fa-chevron-right submenu-arrow"></i>
            <?php endif; ?>
        </a>
        <?php if($hasSubmenu): ?>
            <div class="collapse submenu <?= $isActive ? 'show' : '' ?>" id="submenu-<?= str_replace('/', '-', $item['url']) ?>">
                <ul>
                    <?php foreach($item['submenu'] as $subitem): ?>
                        <li>
                            <a href="<?= base_url($subitem['url']) ?>" 
                               class="submenu-link <?= strpos($current_url, $subitem['url']) !== false ? 'active' : '' ?>">
                                <span><?= $subitem['text'] ?></span>
                                <?php if(isset($subitem['badge'])): ?>
                                    <span class="badge-sub"><?= $subitem['badge'] ?></span>
                                <?php endif; ?>
                                <?php if(isset($subitem['pro']) && $subitem['pro']): ?>
                                    <span class="badge-pro">Pro</span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </li>
    <?php
}
?>

<!-- Link CSS file -->
<link rel="stylesheet" href="<?= base_url('assets/css/student/components/sidebar.css') ?>">

<!-- Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= base_url() ?>" class="sidebar-logo">
            <i class="fas fa-graduation-cap logo-icon"></i>
            <span>MATERIO</span>
        </a>
    </div>
    
    <ul class="sidebar-menu">
        <!-- Main Menu -->
        <?php foreach($menu_items['main'] as $item): ?>
            <?php renderMenuItem($item, $current_url); ?>
        <?php endforeach; ?>

        <!-- Apps & Pages -->
        <li class="sidebar-menu-divider">
            <div>ỨNG DỤNG & TRANG</div>
        </li>
        
        <?php foreach($menu_items['apps'] as $item): ?>
            <?php renderMenuItem($item, $current_url); ?>
        <?php endforeach; ?>

        <!-- User Interface -->
        <li class="sidebar-menu-divider">
            <div>GIAO DIỆN NGƯỜI DÙNG</div>
        </li>

        <?php foreach($menu_items['interface'] as $item): ?>
            <?php renderMenuItem($item, $current_url); ?>
        <?php endforeach; ?>
    </ul>
    
    <div class="sidebar-footer">
        <a href="#" class="upgrade-pro-btn">
            <i class="fas fa-rocket"></i>
            <span>Nâng cấp lên Pro</span>
        </a>
        
        <div class="sidebar-footer-stats">
            <div class="storage-info">
                <div class="storage-text">
                    <span>Dung lượng</span>
                    <span>24.3GB / 40GB</span>
                </div>
                <div class="progress">
                    <div class="progress-bar" style="width: 60%"></div>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Link JS file -->
<script src="<?= base_url('assets/js/student/components/sidebar.js') ?>"></script> 