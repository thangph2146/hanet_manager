<?php
$current_url = current_url();
$active_menu = $active_menu ?? '';

$menu_items = [
    'main' => [
        [
            'icon' => 'user',
            'text' => 'Thông tin cá nhân',
            'url' => 'nguoi-dung/profile',
            
        ],
        [
            'icon' => 'home',
            'text' => 'Dashboard',
            'url' => 'nguoi-dung/dashboard',
        ]
    ],
    'sự kiện' => [
        [
            'icon' => 'calendar-alt',
            'text' => 'Quản lý sự kiện',
            'url' => 'nguoi-dung/events',
            'submenu' => [
                [
                    'text' => 'Danh sách sự kiện',
                    'url' => 'nguoi-dung/events-list',
                ],
                [
                    'text' => 'Danh sách sự kiện đã tham gia',
                    'url' => 'nguoi-dung/events-checkin'
                ],
                [
                    'text' => 'Lịch sử đăng ký',
                    'url' => 'nguoi-dung/events-history-register',
                ]
            ]
        ],
    ],
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
<link rel="stylesheet" href="<?= base_url('assets/css/nguoidung/components/sidebar.css') ?>">

<!-- Sidebar Backdrop -->
<div class="sidebar-backdrop" id="sidebar-backdrop"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <a href="<?= base_url() ?>" class="sidebar-logo">
            <i class="fas fa-graduation-cap logo-icon"></i>
            <span>HUB Sự kiện</span>
        </a>
        <button class="sidebar-close d-lg-none" id="sidebar-close" aria-label="Close sidebar">
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
            <div>SỰ KIỆN</div>
        </li>
        
        <?php foreach($menu_items['sự kiện'] as $item): ?>
            <?php renderMenuItem($item, $current_url); ?>
        <?php endforeach; ?>
    </ul>
</aside>

<!-- Script cho sidebar -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Chỉ khởi tạo event listeners mới, không ghi đè class Sidebar
    const sidebarClose = document.getElementById('sidebar-close');
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function() {
            if (window.StudentUI) {
                window.StudentUI.closeSidebar();
            } else {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('sidebar-backdrop');
                if (sidebar) sidebar.classList.remove('show');
                if (backdrop) backdrop.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    }
    
    // Menu hover effect
    const menuItems = document.querySelectorAll('.sidebar-link');
    menuItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            const icon = item.querySelector('.menu-icon');
            if (icon) icon.style.transform = 'translateY(-2px)';
            if (!item.classList.contains('active')) 
                item.style.backgroundColor = 'rgba(138, 43, 226, 0.05)';
        });
        
        item.addEventListener('mouseleave', () => {
            const icon = item.querySelector('.menu-icon');
            if (icon) icon.style.transform = '';
            if (!item.classList.contains('active'))
                item.style.backgroundColor = '';
        });
    });
});
</script> 