<?php
/**
 * Component hiển thị phân trang
 * 
 * @var object $pager Đối tượng phân trang
 * @var int $total Tổng số bản ghi
 * @var int $perPage Số bản ghi trên mỗi trang
 * @var int $page Trang hiện tại
 */
?>

<?php if ($pager && $total > 0) : ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-3">
    <!-- Hiển thị thông tin số bản ghi -->
    <div class="text-muted">
        Hiển thị <?= ($page - 1) * $perPage + 1 ?> đến <?= min($page * $perPage, $total) ?> 
        trong tổng số <?= number_format($total) ?> bản ghi
    </div>

    <!-- Phân trang -->
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
            <?php if ($pager->hasPrevious()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getFirst() ?>" aria-label="Trang đầu">
                        <i class="bx bx-chevrons-left"></i>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getPrevious() ?>" aria-label="Trang trước">
                        <i class="bx bx-chevron-left"></i>
                    </a>
                </li>
            <?php endif ?>

            <?php 
            $currentPage = $pager->getCurrentPage();
            $totalPages = ceil($total / $perPage);
            $delta = 2; // Số trang hiển thị xung quanh trang hiện tại
            
            for ($i = 1; $i <= $totalPages; $i++) {
                // Hiển thị trang đầu, trang cuối và các trang xung quanh trang hiện tại
                if ($i == 1 || $i == $totalPages || 
                    ($i >= $currentPage - $delta && $i <= $currentPage + $delta)) {
                    if ($i == $currentPage) {
                        echo '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="' . 
                             $pager->getPageURI($i) . '">' . $i . '</a></li>';
                    }
                } 
                // Hiển thị dấu ... nếu có khoảng cách
                elseif (($i == $currentPage - $delta - 1) || ($i == $currentPage + $delta + 1)) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }
            ?>

            <?php if ($pager->hasNext()) : ?>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getNext() ?>" aria-label="Trang sau">
                        <i class="bx bx-chevron-right"></i>
                    </a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="<?= $pager->getLast() ?>" aria-label="Trang cuối">
                        <i class="bx bx-chevrons-right"></i>
                    </a>
                </li>
            <?php endif ?>
        </ul>
    </nav>
</div>

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
<?php endif; ?> 