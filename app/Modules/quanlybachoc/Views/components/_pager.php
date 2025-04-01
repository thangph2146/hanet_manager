<?php
/**
 * Component hiển thị phân trang và lựa chọn số bản ghi trên mỗi trang
 * 
 * Các biến cần truyền vào:
 * @var object $pager Đối tượng phân trang
 * @var int $perPage Số bản ghi trên mỗi trang
 * @var int $total Tổng số bản ghi
 */
?>

<div class="card-footer d-flex flex-wrap justify-content-between align-items-center py-2">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info">
            Hiển thị từ <?= (($pager->getCurrentPage() - 1) * $perPage + 1) ?> đến <?= min(($pager->getCurrentPage() - 1) * $perPage + $perPage, $total) ?> trong số <?= $total ?> bản ghi
        </div>
    </div>
    <div class="col-sm-12 col-md-7">
        <div class="d-flex justify-content-end align-items-center">
            <div class="me-2">
                <select id="perPageSelect" class="form-select form-select-sm d-inline-block" style="width: auto;">
                    <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                    <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                    <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                    <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
                </select>
            </div>
            <div class="pagination-container">
                <?php if ($pager) : ?>
                    <?= $pager->links() ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> 