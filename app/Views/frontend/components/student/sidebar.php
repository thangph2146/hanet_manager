<?php
$current_url = current_url();

$menu_items = [
    'main' => [
        [
            'icon' => 'tachometer-alt',
            'text' => 'Bảng điều khiển',
            'url' => 'students/dashboard',
            'submenu' => [
                [
                    'text' => 'Phân tích',
                    'url' => 'students/dashboard/analytics',
                    'badge' => 'New'
                ],
                [
                    'text' => 'Học tập',
                    'url' => 'students/dashboard/learning'
                ],
                [
                    'text' => 'Thành tích',
                    'url' => 'students/dashboard/achievements',
                ]
            ]
        ]
    ],
    'apps' => [
        [
            'icon' => 'envelope',
            'text' => 'Email',
            'url' => 'students/email',
            'submenu' => [
                [
                    'text' => 'Hộp thư đến',
                    'url' => 'students/email/inbox',
                    'badge' => '3'
                ],
                [
                    'text' => 'Soạn thư',
                    'url' => 'students/email/compose'
                ],
                [
                    'text' => 'Cài đặt email',
                    'url' => 'students/email/settings'
                ]
            ]
        ],
        [
            'icon' => 'comments',
            'text' => 'Trò chuyện',
            'url' => 'students/chat',
        ],
        [
            'icon' => 'calendar-alt',
            'text' => 'Lịch',
            'url' => 'students/calendar',
        ]
    ],
    'interface' => [
        [
            'icon' => 'layer-group',
            'text' => 'Giao diện',
            'url' => 'students/ui',
            'submenu' => [
                [
                    'text' => 'Typography',
                    'url' => 'students/ui/typography'
                ],
                [
                    'text' => 'Colors',
                    'url' => 'students/ui/colors'
                ],
                [
                    'text' => 'Icons',
                    'url' => 'students/ui/icons'
                ]
            ]
        ],
        [
            'icon' => 'puzzle-piece',
            'text' => 'Components',
            'url' => 'students/components',
            'submenu' => [
                [
                    'text' => 'Alerts',
                    'url' => 'students/components/alerts'
                ],
                [
                    'text' => 'Buttons',
                    'url' => 'students/components/buttons'
                ],
                [
                    'text' => 'Cards',
                    'url' => 'students/components/cards',
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
            <span>HUB Sự kiện</span>
        </a>
        <button class="sidebar-close d-lg-none" id="sidebar-close">
            <i class="fas fa-times"></i>
        </button>
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
</aside>

<!-- Link JS file -->
<script src="<?= base_url('assets/js/student/components/sidebar.js') ?>"></script> 