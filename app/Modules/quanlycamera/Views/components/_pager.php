<?php if ($pager->getPageCount() > 1) : ?>
    <div class="card-footer bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Hiển thị <?= $perPage ?> / <?= $total ?> kết quả
            </div>
            <?= $pager->links() ?>
        </div>
    </div>
<?php endif ?> 