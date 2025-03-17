<?php
$segments = current_url(true)->getSegments();
$segmentCount = count($segments);
?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="<?= base_url('dashboard') ?>">
                <i class="fa fa-home"></i> Trang chủ
            </a>
        </li>
        
        <?php if ($segmentCount > 1): ?>
            <?php
            $path = '';
            for ($i = 1; $i < $segmentCount; $i++):
                $path .= '/' . $segments[$i];
                $isActive = ($i === $segmentCount - 1) ? 'active' : '';
                $label = ucwords(str_replace('-', ' ', $segments[$i]));
                $url = base_url($path);
            ?>
                <li class="breadcrumb-item <?= $isActive ?>">
                    <?php if ($isActive): ?>
                        <?= $label ?>
                    <?php else: ?>
                        <a href="<?= $url ?>"><?= $label ?></a>
                    <?php endif; ?>
                </li>
            <?php endfor; ?>
        <?php endif; ?>
    </ol>
</nav> 