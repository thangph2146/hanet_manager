<?php if (isset($pager) && $pager !== null && $pager->getPageCount() > 1) : ?>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Hiển thị <?= $perPage ?> / <?= $total ?> kết quả
            </div>
            <?= $pager->links() ?>
        </div>
    </div>
<?php elseif (isset($perPage) && isset($total)) : ?>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Hiển thị <?= $perPage ?> / <?= $total ?> kết quả
            </div>
        </div>
    </div>
<?php endif ?> 