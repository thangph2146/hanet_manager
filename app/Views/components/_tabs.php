<?php
/**
 * Component để hiển thị tabs
 * 
 * @var array $tabs Mảng chứa thông tin các tab
 * @var array $data Dữ liệu được truyền vào các tab content
 * @var string $active_tab Tab đang active (mặc định là tab đầu tiên)
 * @var string $tabs_id ID của tabs container (mặc định là 'mainTabs')
 * @var bool $show_icons Hiển thị icons trong tab (mặc định là true)
 * @var string $nav_class Class CSS cho nav tabs (mặc định là 'nav-tabs')
 * @var string $content_class Class CSS cho tab content (mặc định là 'tab-content')
 * @var bool $skip_hidden_tabs Bỏ qua các tab bị ẩn khi hiển thị (true) hoặc tạo cấu trúc nhưng ẩn đi (false)
 */

// Kiểm tra dữ liệu đầu vào
if (!isset($tabs) || !is_array($tabs) || empty($tabs)): 
    echo '<div class="alert alert-warning"><i class="lni lni-warning"></i> Không có dữ liệu tabs để hiển thị.</div>';
    return;
endif;

// Lọc tabs hiển thị
$visible_tabs = array_filter($tabs, function($tab) {
    return (!isset($tab['visible']) || $tab['visible'] === true);
});

// Nếu không còn tab nào hiển thị thì dừng
if (empty($visible_tabs) && ($skip_hidden_tabs ?? true)): 
    return;
endif;

// Khởi tạo các tham số mặc định
$tabs_id = $tabs_id ?? 'mainTabs';
$show_icons = $show_icons ?? true;
$nav_class = $nav_class ?? 'nav-tabs';
$content_class = $content_class ?? 'tab-content';
$data = $data ?? [];
$skip_hidden_tabs = $skip_hidden_tabs ?? true;

// Đảm bảo active tab tồn tại và hiển thị
if (!isset($active_tab) || !array_key_exists($active_tab, array_column($visible_tabs, 'id'))) {
    $active_tab = isset($visible_tabs[0]['id']) ? $visible_tabs[0]['id'] : null;
}

// Hiển thị tab chỉ khi có ít nhất 1 tab
if (!empty($visible_tabs)):
?>

<!-- Tabs Navigation -->
<div class="w-100">
    <ul class="nav <?= $nav_class ?> mb-4" id="<?= $tabs_id ?>" role="tablist">
        <?php foreach ($tabs as $tab): ?>
            <?php
            // Kiểm tra tab có hiển thị không
            $is_visible = (!isset($tab['visible']) || $tab['visible'] === true);
            if (!$is_visible && $skip_hidden_tabs) continue;
            ?>
            <li class="nav-item" role="presentation" <?= !$is_visible ? 'style="display:none;"' : '' ?>>
                <button class="nav-link <?= ($tab['id'] == $active_tab) ? 'active' : '' ?>" 
                        id="<?= $tab['id'] ?>-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#<?= $tab['id'] ?>" 
                        type="button" 
                        role="tab" 
                        aria-controls="<?= $tab['id'] ?>" 
                        aria-selected="<?= ($tab['id'] == $active_tab) ? 'true' : 'false' ?>">
                    <?php if ($show_icons && isset($tab['icon'])): ?>
                        <i class="<?= $tab['icon'] ?>"></i>
                    <?php endif; ?>
                    <?= $tab['title'] ?>
                    <?php if (isset($tab['badge'])): ?>
                        <span class="badge <?= $tab['badge']['class'] ?? 'bg-primary' ?> ms-1 rounded-pill">
                            <?= $tab['badge']['value'] ?>
                        </span>
                    <?php endif; ?>
                </button>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Tabs Content -->
    <div class="<?= $content_class ?>" id="<?= $tabs_id ?>Content">
        <?php foreach ($tabs as $tab): ?>
            <?php
            // Kiểm tra tab có hiển thị không
            $is_visible = (!isset($tab['visible']) || $tab['visible'] === true);
            if (!$is_visible && $skip_hidden_tabs) continue;
            ?>
            <div class="tab-pane fade <?= ($tab['id'] == $active_tab) ? 'show active' : '' ?>" 
                 id="<?= $tab['id'] ?>" 
                 role="tabpanel" 
                 aria-labelledby="<?= $tab['id'] ?>-tab"
                 <?= !$is_visible ? 'style="display:none;"' : '' ?>>
                <?php if (isset($tab['content'])): ?>
                    <?= view($tab['content'], array_merge($data, [
                        'tab_id' => $tab['id'],
                        'tab_title' => $tab['title'],
                        'tab_data' => $tab['data'] ?? null
                    ])) ?>
                <?php elseif (isset($tab['html'])): ?>
                    <?= $tab['html'] ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php if (isset($include_scripts) && $include_scripts): ?>
    <!-- Scripts cho tabs -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parallax.js/1.5.0/parallax.min.js"></script>
<?php endif; ?>

<?php endif; // end if (!empty($visible_tabs)) ?> 