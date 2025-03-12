<?php
/**
 * @param string $title Tiêu đề chính của trang
 * @param string $dashboard_url URL của trang dashboard
 * @param array $breadcrumbs Mảng các breadcrumb items [['url' => '...', 'title' => '...'], ...]
 * @param array $actions Mảng các action items [['url' => '...', 'title' => '...'], ...]
 */
?>
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3"><?= $title ?? 'Dashboard' ?></div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="<?= $dashboard_url ?? site_url('/') ?>">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                    <?php foreach ($breadcrumbs as $item): ?>
                        <li class="breadcrumb-item <?= isset($item['active']) && $item['active'] ? 'active' : '' ?>" <?= isset($item['active']) && $item['active'] ? 'aria-current="page"' : '' ?>>
                            <?php if (isset($item['url']) && !isset($item['active'])): ?>
                                <a href="<?= $item['url'] ?>"><?= $item['title'] ?></a>
                            <?php else: ?>
                                <?= $item['title'] ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ol>
        </nav>
    </div>
    <?php if (isset($actions) && is_array($actions) && count($actions) > 0): ?>
    <div class="ms-auto">
        <div class="btn-group">
            <button type="button" class="btn btn-primary">Chức năng</button>
            <button type="button" class="btn btn-primary split-bg-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-end">
                <?php foreach ($actions as $action): ?>
                    <a class="dropdown-item" href="<?= $action['url'] ?>"><?= $action['title'] ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
