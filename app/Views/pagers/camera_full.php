<?php

/**
 * @var \CodeIgniter\Pager\PagerRenderer $pager
 */

// Không gọi trực tiếp setSurroundCount ở đây vì nó đã được cài đặt trong controller

// Hàm helper để lấy trang hiện tại
$getCurrentPage = function() use ($pager) {
    // Trích xuất trang hiện tại từ URL
    $segments = explode('/', $pager->getCurrent());
    $lastSegment = end($segments);
    
    // Nếu có tham số truy vấn, trích xuất giá trị trang
    if (strpos($lastSegment, 'page=') !== false) {
        preg_match('/page=(\d+)/', $lastSegment, $matches);
        return isset($matches[1]) ? (int)$matches[1] : 1;
    }
    
    // Nếu không có tham số truy vấn, giả định trang hiện tại từ phân đoạn
    if (is_numeric($lastSegment)) {
        return (int)$lastSegment;
    }
    
    return 1;
};

$currentPage = $getCurrentPage();
?>

<?php if ($pager->hasPrevious() || $pager->hasNext()) : ?>
<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="pagination pagination-sm mb-0 justify-content-center">
        <!-- First & Prev Page -->
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a class="page-link rounded-start" href="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>" title="<?= lang('Pager.first') ?>">
                    <span aria-hidden="true"><i class="bx bx-chevrons-left"></i></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="<?= lang('Pager.previous') ?>" title="<?= lang('Pager.previous') ?>">
                    <span aria-hidden="true"><i class="bx bx-chevron-left"></i></span>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link rounded-start" title="<?= lang('Pager.first') ?>"><i class="bx bx-chevrons-left"></i></span>
            </li>
            <li class="page-item disabled">
                <span class="page-link" title="<?= lang('Pager.previous') ?>"><i class="bx bx-chevron-left"></i></span>
            </li>
        <?php endif ?>

        <!-- Page Links -->
        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a class="page-link" href="<?= $link['uri'] ?>" title="<?= lang('Pager.pageNavigation') ?> <?= $link['title'] ?>">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <!-- Next & Last Page -->
        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="<?= lang('Pager.next') ?>" title="<?= lang('Pager.next') ?>">
                    <span aria-hidden="true"><i class="bx bx-chevron-right"></i></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link rounded-end" href="<?= $pager->getLast() ?>" aria-label="<?= lang('Pager.last') ?>" title="<?= lang('Pager.last') ?>">
                    <span aria-hidden="true"><i class="bx bx-chevrons-right"></i></span>
                </a>
            </li>
        <?php else : ?>
            <li class="page-item disabled">
                <span class="page-link" title="<?= lang('Pager.next') ?>"><i class="bx bx-chevron-right"></i></span>
            </li>
            <li class="page-item disabled">
                <span class="page-link rounded-end" title="<?= lang('Pager.last') ?>"><i class="bx bx-chevrons-right"></i></span>
            </li>
        <?php endif ?>
    </ul>
</nav>
<?php endif ?> 