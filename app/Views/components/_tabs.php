<?php
/**
 * Component tabs đơn giản
 * 
 * @var array $tabs Các tab cần hiển thị (bắt buộc) 
 * @var string $active_tab ID tab active (mặc định: tab đầu tiên)
 * @var string $tabs_id ID của container (mặc định: 'eventTabs')
 * @var array $data Dữ liệu cho nội dung tab (tùy chọn)
 */

// Kiểm tra đầu vào
if (empty($tabs)): 
    echo '<div class="alert alert-warning"><i class="lni lni-warning"></i> Không có dữ liệu tabs.</div>';
    return;
endif;

// Tham số mặc định
$tabs_id = $tabs_id ?? 'eventTabs';
$active_tab = $active_tab ?? $tabs[0]['id'];
$data = $data ?? [];
?>

<div class="w-100">
    <!-- Menu tabs -->
    <ul class="nav nav-tabs mb-4" id="<?= $tabs_id ?>" role="tablist">
        <?php foreach ($tabs as $tab): 
            if (isset($tab['visible']) && !$tab['visible']) continue; ?>
            <li class="nav-item" role="presentation">
                <button class="nav-link <?= ($tab['id'] == $active_tab) ? 'active' : '' ?>" 
                        id="<?= $tab['id'] ?>-tab" 
                        data-bs-toggle="tab" 
                        data-bs-target="#<?= $tab['id'] ?>" 
                        type="button" 
                        role="tab">
                    <?= isset($tab['icon']) ? '<i class="' . $tab['icon'] . '"></i> ' : '' ?>
                    <?= $tab['title'] ?>
                </button>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Nội dung tabs -->
    <div class="tab-content" id="<?= $tabs_id ?>Content">
        <?php foreach ($tabs as $tab): 
            if (isset($tab['visible']) && !$tab['visible']) continue; ?>
            <div class="tab-pane fade <?= ($tab['id'] == $active_tab) ? 'show active' : '' ?>" 
                 id="<?= $tab['id'] ?>" 
                 role="tabpanel" 
                 aria-labelledby="<?= $tab['id'] ?>-tab">
                <?php if (isset($tab['content'])): ?>
                    <?= view($tab['content'], array_merge($data, ['tab_id' => $tab['id'], 'tab_title' => $tab['title']])) ?>
                <?php elseif (isset($tab['html'])): ?>
                    <?= $tab['html'] ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div> 