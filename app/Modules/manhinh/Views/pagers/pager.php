<?php
/**
 * @var \App\Modules\manhinh\Libraries\Pager $pager
 */

// Tính toán thông tin phân trang
$total = $pager->getTotal();
$perPage = $pager->getPerPage();
$currentPage = $pager->getCurrentPage();
$startRecord = ($currentPage - 1) * $perPage + 1;
$endRecord = min($total, $currentPage * $perPage);
?>

<div class="d-flex align-items-center">
    <div class="pagination-info me-3">
        Hiển thị <span class="fw-semibold"><?= $startRecord ?>-<?= $endRecord ?></span> trên <span class="fw-semibold"><?= $total ?></span> bản ghi
    </div>

    <?php if ($pager->hasPrevious() || $pager->hasNext()) : ?>
    <nav aria-label="Phân trang" class="custom-pagination">
        <ul class="pagination pagination-sm mb-0">
            <?php if ($pager->hasPrevious()) : ?>
                <!-- Nút về trang đầu tiên -->
                <li class="page-item">
                    <a href="<?= $pager->getFirst() ?>" class="page-link" aria-label="Trang đầu tiên">
                        <span aria-hidden="true"><i class="bx bx-chevrons-left"></i></span>
                    </a>
                </li>
                
                <!-- Nút về trang trước -->
                <li class="page-item">
                    <a href="<?= $pager->getPrevious() ?>" class="page-link" aria-label="Trang trước">
                        <span aria-hidden="true"><i class="bx bx-chevron-left"></i></span>
                    </a>
                </li>
            <?php else : ?>
                <!-- Nút về trang đầu tiên (không hoạt động) -->
                <li class="page-item disabled">
                    <a href="#" class="page-link" aria-label="Trang đầu tiên">
                        <span aria-hidden="true"><i class="bx bx-chevrons-left"></i></span>
                    </a>
                </li>
                
                <!-- Nút về trang trước (không hoạt động) -->
                <li class="page-item disabled">
                    <a href="#" class="page-link" aria-label="Trang trước">
                        <span aria-hidden="true"><i class="bx bx-chevron-left"></i></span>
                    </a>
                </li>
            <?php endif; ?>
            
            <!-- Hiển thị danh sách các trang -->
            <?php foreach ($pager->getPageNumbers() as $page) : ?>
                <?php if ($page === '...') : ?>
                    <!-- Hiển thị dấu chấm lửng -->
                    <li class="page-item disabled">
                        <a href="#" class="page-link">...</a>
                    </li>
                <?php else : ?>
                    <!-- Hiển thị số trang và đánh dấu trang hiện tại -->
                    <li class="page-item <?= ($page == $pager->getCurrentPage()) ? 'active' : '' ?>">
                        <a href="<?= $pager->getPageURL($page) ?>" class="page-link"><?= $page ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <?php if ($pager->hasNext()) : ?>
                <!-- Nút tới trang sau -->
                <li class="page-item">
                    <a href="<?= $pager->getNext() ?>" class="page-link" aria-label="Trang sau">
                        <span aria-hidden="true"><i class="bx bx-chevron-right"></i></span>
                    </a>
                </li>
                
                <!-- Nút tới trang cuối cùng -->
                <li class="page-item">
                    <a href="<?= $pager->getLast() ?>" class="page-link" aria-label="Trang cuối cùng">
                        <span aria-hidden="true"><i class="bx bx-chevrons-right"></i></span>
                    </a>
                </li>
            <?php else : ?>
                <!-- Nút tới trang sau (không hoạt động) -->
                <li class="page-item disabled">
                    <a href="#" class="page-link" aria-label="Trang sau">
                        <span aria-hidden="true"><i class="bx bx-chevron-right"></i></span>
                    </a>
                </li>
                
                <!-- Nút tới trang cuối cùng (không hoạt động) -->
                <li class="page-item disabled">
                    <a href="#" class="page-link" aria-label="Trang cuối cùng">
                        <span aria-hidden="true"><i class="bx bx-chevrons-right"></i></span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php endif ?> 
</div> 