<?php

use CodeIgniter\Pager\PagerRenderer;

/**
 * @var PagerRenderer $pager
 */

// Lấy tất cả tham số URL hiện tại
$uri = current_url(true);
$query = $uri->getQuery();
parse_str($query, $queryParams);

// Loại bỏ tham số page nếu có
unset($queryParams['page']);

// Tạo chuỗi query parameters
$queryString = http_build_query($queryParams);

// Lấy thông tin phân trang
$pageCount = $pager->getPageCount();
$currentPage = $pager->getCurrentPageNumber();
?>

<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
    <ul class="pagination justify-content-end">
        <?php if ($pager->hasPrevious()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getFirst() . ($queryString ? '?' . $queryString : '') ?>" aria-label="<?= lang('Pager.first') ?>" class="page-link">
                    <i class="bx bx-chevrons-left"></i>
                </a>
            </li>
            <li class="page-item">
                <a href="<?= $pager->getPrevious() . ($queryString ? '?' . $queryString : '') ?>" aria-label="<?= lang('Pager.previous') ?>" class="page-link">
                    <i class="bx bx-chevron-left"></i>
                </a>
            </li>
        <?php endif ?>

        <?php foreach ($pager->links() as $link) : ?>
            <li class="page-item <?= $link['active'] ? 'active' : '' ?>">
                <a href="<?= $link['uri'] . ($queryString ? '?' . $queryString : '') ?>" class="page-link">
                    <?= $link['title'] ?>
                </a>
            </li>
        <?php endforeach ?>

        <?php if ($pager->hasNext()) : ?>
            <li class="page-item">
                <a href="<?= $pager->getNext() . ($queryString ? '?' . $queryString : '') ?>" aria-label="<?= lang('Pager.next') ?>" class="page-link">
                    <i class="bx bx-chevron-right"></i>
                </a>
            </li>
            <li class="page-item">
                <a href="<?= $pager->getLast() . ($queryString ? '?' . $queryString : '') ?>" aria-label="<?= lang('Pager.last') ?>" class="page-link">
                    <i class="bx bx-chevrons-right"></i>
                </a>
            </li>
        <?php endif ?>
    </ul>
</nav>

<!-- CSS tùy chỉnh cho phân trang -->
<style>
.pagination {
    margin-bottom: 0;
}

.page-link {
    padding: 0.5rem 0.75rem;
    color: #435971;
    background-color: #fff;
    border: 1px solid #d9dee3;
    font-size: 0.9375rem;
}

.page-link:hover {
    color: #697a8d;
    background-color: #e9ecef;
    border-color: #d9dee3;
}

.page-item.active .page-link {
    background-color: #696cff;
    border-color: #696cff;
    color: #fff;
}

.page-item.disabled .page-link {
    color: #697a8d;
    background-color: #f0f2f4;
    border-color: #d9dee3;
}

.page-link:focus {
    box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .pagination {
        font-size: 0.875rem;
    }
    
    .page-link {
        padding: 0.4rem 0.6rem;
    }
}
</style> 